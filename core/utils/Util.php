<?php

namespace core\utils;

use think\helper\Str;
use think\facade\Request;
use Overtrue\Pinyin\Pinyin;


class Util
{
    /**
     * 字符串转换成数组
     *
     * @param string $string
     * @param string $dep
     * @return array
     */
    public function strToArr(string  $string, $dep = ','): array
    {
        if (Str::contains($string, $dep)) {
            return explode($dep, trim($string, $dep));
        }

        return [$string];
    }

    /**
     * 过滤搜索参数
     *
     * @param array $params
     * @param array $range
     * @return array
     */
    public function filterSearchParams(array $params): array
    {
        $search = [];
        foreach ($params as $k => $v) {
            if (is_empty_str($v) || is_null($v)) {
                unset($params[$k]);
            }
        }
        return array_merge($search, $params);
    }


    /**
     *  解析 Rule 规则
     *
     * @param $rule
     * @return array
     */
    public function parseRule()
    {

        $rule = Request::controller(true);

        $action = Request::action(true);

        [$module, $controller] = explode(Str::contains($rule, '.') ? '.' : '/', $rule);

        return [$module, $controller, $action];
    }


    /**
     * 汉字拼音转换
     *
     * @param  string $str
     * @return string
     */
    public function toPinyin($str)
    {
        $pinyin = new Pinyin();
        $result = $pinyin->abbr($str);
        return strtolower($result);
    }



    /**
     * 地址转换为gps坐标
     * @param   string     $address  地址
     * @return  string
     */
    public function addrToGps($address)
    {
        $url = 'https://restapi.amap.com/v3/geocode/geo';
        $params = [
            'key' => config('system.map_key'),
            'address' => $address
        ];
        $response = json_decode(Http::get($url, $params));
        if (empty($response->geocodes)) {
            return '';
        }
        return $response->geocodes[0]->location;
    }


    /**
     * gps转换为地址
     * @param   string     $address  地址
     * @return  string
     */
    public function gpsToAddr($location)
    {
        $url = 'https://restapi.amap.com/v3/geocode/regeo';
        $params = [
            'key' => config('system.map_key'),
            'location' => $location
        ];
        $city = cache($location . 'city');
        if ($city) return $city;
        $response = json_decode(Http::get($url, $params));
        if ($response->status == 1) {
            $city = $response->regeocode->addressComponent->city;
            cache($location . 'city', $city);
            return $city;
        }
        return '';
    }



    /**
     * 打码号码
     * @param string $phone
     * @return string
     */
    public function maskNumber($phone)
    {
        $hasMatch = preg_match('/(0[0-9]{2,3}[\-]?[2-9][0-9]{6,7}[\-]?[0-9]?)/i', $phone); //固定电话
        if ($hasMatch == 1) {
            return preg_replace('/(0[0-9]{2,3}[\-]?[2-9])[0-9]{3,4}([0-9]{3}[\-]?[0-9]?)/i', '$1****$2', $phone);
        } else {
            return  preg_replace('/(1[345789]{1}[0-9])[0-9]{4}([0-9]{4})/i', '$1****$2', $phone);
        }
    }



    /**
     * 打码姓名
     * 
     * @param string $str
     * @return string
     */
    public function maskName($str)
    {
        //按照中文字符计算长度
        $len = mb_strlen($str, 'UTF-8');
        if ($len >= 3) {
            //三个字符或三个字符以上掐头取尾，中间用*代替
            $str = mb_substr($str, 0, 1, 'UTF-8') . '**';
        } elseif ($len == 2) {
            //两个字符
            $str = mb_substr($str, 0, 1, 'UTF-8') . '*';
        }
        return $str;
    }



    /** 
     * 打码地址
     * 
     * @param string $str
     * @return string 
     */
    public function maskAddr($str)
    {
        return preg_replace('/\d+/', '*', $str);
    }



    /**
     * 人民币大写转换函数
     *
     * @param float $money
     * @return string 
     */
    public function chineseRmb($money)
    {
        $money = round($money, 2); // 四舍五入
        if ($money <= 0) {
            return '零元';
        }
        $units = array(
            '',
            '拾',
            '佰',
            '仟',
            '',
            '万',
            '亿',
            '兆'
        );
        $amount = array(
            '零',
            '壹',
            '贰',
            '叁',
            '肆',
            '伍',
            '陆',
            '柒',
            '捌',
            '玖'
        );
        $arr = explode('.', $money); // 拆分小数点
        $money = strrev($arr[0]); // 翻转整数
        $length = strlen($money); // 获取数字的长度
        for ($i = 0; $i < $length; $i++) {
            $int[$i] = $amount[$money[$i]]; // 获取大写数字
            if (!empty($money[$i])) {
                $int[$i] .= $units[$i % 4]; // 获取整数位
            }
            if ($i % 4 == 0) {
                $int[$i] .= $units[4 + floor($i / 4)]; // 取整
            }
        }
        $con = isset($arr[1]) ? '元' . $amount[$arr[1][0]] . '角' . $amount[$arr[1][1]] . '分' : '元整';
        $str = implode('', array_reverse($int)) . $con; // 整合数组为字符串

        if ($money != 0) {
            while (1) {
                if (substr_count($str, "零零") == 0 && substr_count($str, "零元") == 0 && substr_count($str, "零万") == 0 && substr_count($str, "零亿") == 0) {
                    break;
                }
                $str = str_replace("零零", "零", $str);
                $str = str_replace("零元", "元", $str);
                $str = str_replace("零万", "万", $str);
                $str = str_replace("零亿", "亿", $str);
            }
        }
        return $str;
    }

}
