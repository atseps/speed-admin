<?php

namespace core\service\jwt;

use think\facade\Request;
use think\helper\Arr;
use think\facade\Cache;

use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\Clock\SystemClock;
use Lcobucci\JWT\Validation\Validator;
use Lcobucci\JWT\Encoding\{ChainedFormatter, CannotDecodeContent, JoseEncoder};
use Lcobucci\JWT\Token\{Builder, Plain, InvalidTokenStructure, UnsupportedHeaderFound};
use Lcobucci\JWT\Validation\Constraint\{SignedWith, ValidAt, StrictValidAt};
use core\service\jwt\exception\{
    TokenInvalidException,
    TokenExpiredException,
    TokenBlacklistException
};
use DateTimeZone;

class JwtAuth
{
    private const TOKEN_TYPE_ACCESS = 'access_token';
    private const TOKEN_TYPE_REFRESH = 'refresh_token';
    private const TIMEZONE = 'Asia/Shanghai';

    public SystemClock $clock;

    public function __construct(
        private readonly array $config,
        private readonly string $scene,
    ) {
        $this->clock = new SystemClock(new DateTimeZone(self::TIMEZONE));
    }
    public function generateToken(array $claims): array
    {
        return [
            'access_token' => $this->generateAccessToken($claims),
            'refresh_token' => $this->generateRefreshToken($claims),
            'expires_in' => (int) $this->getConfig('ttl'),
        ];
    }

    private function generateAccessToken(array $claims): string
    {
        return $this->buildToken($claims, $this->getConfig('ttl'), self::TOKEN_TYPE_ACCESS);
    }

    private function generateRefreshToken(array $claims): string
    {
        return $this->buildToken($claims, $this->getConfig('refresh_ttl'), self::TOKEN_TYPE_REFRESH);
    }

    private function buildToken(array $claims, int $ttl, string $tokenType): string
    {
        $now = $this->clock->now();
        $jti = $this->generateTokenId($claims);

        $builder = (new Builder(new JoseEncoder(), ChainedFormatter::default()))
            ->identifiedBy($jti)
            ->issuedAt($now)
            ->expiresAt($now->modify("+{$ttl} second"))
            ->withClaim('token_type', $tokenType)
            ->withClaim('scene', $this->scene);

        foreach ($claims as $key => $value) {
            $builder->withClaim($key, $value);
        }

        return $builder
            ->getToken($this->getConfig('alg'), $this->getSigningKey())
            ->toString();
    }

    /**
     * 验证访问令牌
     */
    public function verifyAccessToken(): array
    {
        $token = $this->getToken();
        $token = $this->parseToken($token);
        $this->validateToken($token, self::TOKEN_TYPE_ACCESS);
        $this->hasBlacklist($token);
        return $token->claims()->all();
    }

    /**
     * 验证刷新令牌
     */
    public function verifyRefreshToken(string $token): Plain
    {
        $plain = $this->parseToken($token);
        $this->validateToken($plain, self::TOKEN_TYPE_REFRESH);
        $this->hasBlacklist($plain);
        return $plain;
    }

    /**
     * 刷新令牌
     */
    public function refreshToken(string $token): array
    {
        $plain = $this->verifyRefreshToken($token);
        $this->addBlacklist($token);
        $claims = $this->extractRefreshableClaims($plain);
        return $this->generateToken($claims);
    }

    /**
     * 从刷新令牌中提取可用的声明
     */
    private function extractRefreshableClaims(Plain $token): array
    {
        $claims = $token->claims()->all();
        $excluded = ['jti', 'iat', 'exp', 'sub', 'token_type', 'scene'];
        return array_diff_key($claims, array_flip($excluded));
    }


    /**
     * 验证令牌
     */
    private function validateToken(Plain $token, string $expectedType): void
    {
        $validator = new Validator();
        $signingKey = $this->getSigningKey();
        $algorithm = $this->getConfig('alg');

        if (!$validator->validate($token, new SignedWith($algorithm, $signingKey))) {
            throw new TokenInvalidException('令牌签名错误');
        }

        if (!$validator->validate($token, new ValidAt($this->clock))) {
            throw new TokenExpiredException();
        }

        if ($token->claims()->get('token_type') !== $expectedType) {
            throw new TokenInvalidException('令牌类型错误');
        }
    }

    /**
     * 解析令牌字符串
     */
    public function parseToken(string $token): Plain
    {
        try {
            return (new Parser(new JoseEncoder()))->parse($token);
        } catch (CannotDecodeContent | InvalidTokenStructure | UnsupportedHeaderFound $e) {
            throw new TokenInvalidException('令牌解析失败: ' . $e->getMessage());
        }
    }


    /**
     * 检查令牌是否在黑名单中
     *
     * @throws TokenBlacklistException 黑名单异常
     */
    public function hasBlacklist(Plain $token): void
    {
        $blacklistGracePeriod = $this->getConfig('blacklist_grace_period');
        $blacklistedAt = Cache::get($this->getCacheKey($token));
        if ($blacklistedAt && (time() - $blacklistedAt > $blacklistGracePeriod)) {
            throw new TokenBlacklistException('令牌已注销');
        }
    }


    /**
     * 将令牌加入黑名单
     */
    public function addBlacklist(string $token): void
    {
        $plain = $this->parseToken($token);
        Cache::tag($this->getCachePrefix())
            ->set(
                $this->getCacheKey($plain),
                time(), //存储加入黑名单的时间
                $this->getBlacklistTtl()
            );
    }

    /**
     * 从黑名单中移除令牌
     */
    public function removeBlacklist(Plain $token): void
    {
        Cache::delete($this->getCacheKey($token));
    }


    /**
     * 获取请求中的Token
     * @param string $key 用于URL参数和Cookie的键名
     * @return string|null
     */
    public  function getToken($key = 'token'): string
    {

        if (Request::header('Authorization')) {
            return str_replace('Bearer ', '', Request::header('Authorization'));
        }

        if (Request::cookie($key)) {
            return  Request::cookie($key);
        }

        if (Request::param($key)) {
            return Request::param($key);
        }

        return '';
    }

    /**
     * 获取缓存键
     */
    private function getCacheKey(Plain $token): string
    {
        return sprintf('%s_%s', $this->getCachePrefix(), $token->claims()->get('jti'));
    }

    /**
     * 获取缓存前缀
     */
    private function getCachePrefix(): string
    {
        return $this->getConfig('cache_prefix') ?? 'jwt_blacklist';
    }

    /**
     * 获取黑名单有效期
     */
    private function getBlacklistTtl(): int
    {
        return $this->getConfig('blacklist_ttl') ?? 86400;
    }


    /**
     * 生成令牌唯一标识
     */
    private function generateTokenId(array $claims): string
    {
        $userKey = $claims[$this->getConfig('key')] ?? '';
        return md5(uniqid($userKey, true) . microtime(true));
    }

    /**
     * 获取签名密钥
     */
    private function getSigningKey(): InMemory
    {
        return InMemory::plainText($this->getConfig('secret'));
    }

    /**
     * 获取配置项
     */
    private function getConfig(string $key, mixed $default = null): mixed
    {
        return Arr::get($this->config, $key, $default);
    }
}
