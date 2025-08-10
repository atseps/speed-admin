<?php
/**
 * ThinkPHP微信缓存类
 *
 */
namespace core\service\wechat;

use Psr\SimpleCache\CacheInterface;
use think\Cache;

class CacheBridge implements CacheInterface
{
    protected $cache = null;

    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
    }

    public function get(string $key, mixed $default = null):mixed
    {
        return $this->cache->get($key, $default);
    }

    public function set(string $key, mixed $value, null|int|\DateInterval $ttl = null): bool
    {
        return $this->cache->set($key, $value, $ttl);
    }

    public function delete(string $key): bool
    {
        return $this->cache->delete($key);
    }

    public function clear(): bool
    {
        return $this->cache->clear();
    }

    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        throw new \Exception("not support");
    }

    public function setMultiple(iterable $values, null|int|\DateInterval $ttl = null): bool
    {
        throw new \Exception("not support");
    }

    public function deleteMultiple(iterable $keys): bool
    {
        $success = true; 
        foreach ($keys as $key) {
            if (!$this->delete($key)) { 
                $success = false;
            }
        }
        return $success;
    }
    
    public function has(string $key): bool
    {
        return $this->cache->has($key);
    }

}