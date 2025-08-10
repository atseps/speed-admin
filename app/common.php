<?php
// 应用公共文件
use Hashids\Hashids;
use core\facade\Util;
use app\service\system\DictService;

/**
 * 保留小数
 * @param  number  $number 数值
 * @param  number  $place 保留几位小数
 * @return number
 */
function keep_number($num,  int $place = 2)
{
    return floatval(number_format($num, (int)$place));
}

/**
 * 获取数据字典缓存
 *
 * @param  string   $type 字典类型
 * @param  boolean  $fullInfo  返回完整信息
 * @return array
 */
function get_dict_cache($type, $fullInfo = false)
{
    return DictService::getCacheData($type, $fullInfo);
}

/**
 * 获取字典映射
 *
 * @param  string    $typeof   字典类型
 * @param  string    $value    字典值
 * @param  boolean   $pattern  没有数据时返回的默认值
 * @param  boolean   $fullInfo 返回完整信息
 * @return array
 */
function get_dict_map($type, $value, $pattern = '-', $fullInfo = false)
{
    $data = get_dict_cache($type, $fullInfo);
    return $data[$value] ?? $pattern;
}

/**
 * 当前登录用户是否是超级管理员
 *
 * @return bool
 */
function is_super_admin()
{
    $roles = request()->user()->getRolesId();
    $super_admin_id = config('system.super_admin_id');
    if (in_array($super_admin_id, $roles)) {
        return true;
    }
    return false;
}


/**
 * 获取权限节点
 * @return string
 */
function get_rule()
{
    $rule = implode(':', Util::parseRule());
    $rule = strtolower($rule);
    return $rule;
}



/**
 * 检查权限
 * @param $rule     string|array  需要验证的规则
 * @param $uid   int          要检查权限的用户 ID
 * @param $relation string    如果为 'or' 表示满足任一条规则即通过验证;如果为 'and'则表示需满足所有规则才能通过验证
 * @return boolean            通过验证返回true;失败返回false
 */
function auth_check($rule = null, $uid = null, $relation = 'or')
{

    if (is_null($uid)) {
        $uid = request()->uid();
    }

    if (empty($uid)) {
        return false;
    }

    $auth = app()->make(\core\Permissions::class);

    if (empty($rule)) {
        $rule = get_rule();
    }

    return $auth->check($rule, $uid, $relation);
}



/**
 * 根据一个指定的key,生成新的数组
 *
 * @param  string $key
 * @return array
 */
function array_pick(string $key, array $array): array
{
    $newArray = array();
    if (!is_string($key) || !is_array($array)) return array();
    $keys = explode(",", $key);
    foreach ($keys as $v) {
        if (array_key_exists($v, $array)) {
            $newArray[$v] = $array[$v];
        }
    }
    return $newArray;
}



/**
 * 是否为空字符串
 * @param  string  value
 * @return boolval
 */
function is_empty_str($value): bool
{
    if (isset($value) && $value === '') {
        return true;
    }
    return false;
}



/**
 * [日期格式]时间轴函数，单位以unix时间戳计算
 *
 * @param int $pubtime 时间
 * @return string
 */
function time_format($pubtime)
{
    if ($pubtime == 0)
        return '-';
    $time = time();
    if (idate('Y', $time) != idate('Y', $pubtime)) {
        return date('Y-m-d', $pubtime);
    }
    $seconds = $time - $pubtime;
    $days = idate('z', $time) - idate('z', $pubtime);
    if ($days == 0) {
        if ($seconds < 3600) {
            if ($seconds < 60) {
                if (3 > $seconds) {
                    return '刚刚';
                } else {
                    return $seconds . '秒前';
                }
            }
            return intval($seconds / 60) . '分钟前';
        }
        return '今天' . date('H:i', $pubtime);
    }
    if ($days == 1) {
        return '昨天' . date('H:i', $pubtime);
    }
    if ($days == 2) {
        return '前天 ' . date('H:i', $pubtime);
    }
    return date('n/j H:i', $pubtime);
}



/**
 * 导出csv文件,适用于大量数据导出
 * @param   string    $fileName  文件名称
 * @param   array     $headArr   表头
 * @param   array     $data      导出数据
 * @return  file      文件流
 */
function export_csv($fileName = '', $headArr = [], $data = [])
{
    ini_set('memory_limit', '1024M');
    //设置程序运行的内存
    ini_set('max_execution_time', 0);
    //设置程序的执行时间,0为无上限
    ob_end_clean();
    //清除内存
    ob_start();
    header("Content-Type: text/csv");
    header("Access-Control-Allow-Origin: *");
    header("Content-Disposition:filename=" . urlencode($fileName) . '.csv');
    header("Access-Control-Expose-Headers: Content-Disposition");

    $fp = fopen('php://output', 'w');
    fwrite($fp, chr(0xEF) . chr(0xBB) . chr(0xBF));
    fputcsv($fp, $headArr);
    $index = 0;
    foreach ($data as $item) {
        if ($index == 1000) {
            //每次写入1000条数据清除内存
            $index = 0;
            ob_flush();
            //清除内存
            flush();
        }
        $index++;
        fputcsv($fp, $item);
    }
    ob_flush();
    flush();
    ob_end_clean();
    exit();
}



/**
 * id加解密函数
 *
 * @param string $value
 * @param bool $operation
 * @return string
 */

function auth_code($value, $operation = true)
{

    $secret_key = config('system.secret_key') ?? '';
    $hashids = new Hashids($secret_key, 20);

    //数组id解密
    if (is_array($value) && !empty($value)) {
        $ids = array();
        foreach ($value as $v) {
            $ids[] = $hashids->decode($v);
        }
        return $ids;
    }

    //加解密
    if ($operation) {
        return $hashids->encode($value);
    } else {
        $ids = $hashids->decode($value);
        return implode('', $ids);
    }
}



/**
 * 获取客户ip地址
 *
 * @return string
 */
function get_client_ip($type = 0)
{
    $type = $type ? 1 : 0;
    static $ip = NULL;
    if ($ip !== NULL) return $ip[$type];
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $pos = array_search('unknown', $arr);
        if (false !== $pos) unset($arr[$pos]);
        $ip = trim($arr[0]);
    } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = sprintf("%u", ip2long($ip));
    $ip = $long ? array($ip, $long) : array('0.0.0.0', 0);
    return $ip[$type];
}




/**
 * 日期转换时间戳(不保留时间)
 * 例如: 2020-04-01 08:15:08 => 1585670400
 * @param string $date
 * @return false|int
 */
function str2time_date(string $date)
{
    return strtotime(date('Y-m-d', strtotime($date)));
}

/**
 * 格式化时间
 * 例如: 2020-04-01 08:15:08 => 1585670400
 * @param string $date
 * @return false|int
 */
function format_moment_time($time)
{
    $time = trim($time, '&quot;');
    $time = str2time_date($time);
    return $time;
}

/**
 * 格式化起止时间(为了兼容前端RangePicker组件)
 * 2020-04-01T08:15:08.891Z => 1585670400
 * @param array $times
 * @return array
 */
function between_time(array $times)
{
    foreach ($times as &$time) {
        $time = trim($time, '&quot;');
        $time = str2time_date($time);
    }

    return [current($times), next($times) + 86399];
}




/**
 * 保存数组变量到php文件
 * @param string $path 保存路径
 * @param mixed  $var  要保存的变量
 * @return boolean 保存成功返回true,否则false
 */
function save_var($path, $var)
{
    $result = file_put_contents($path, "<?php\treturn " . var_export($var, true) . ";");
    return $result;
}


/**
 * 根据指定路径创建文件夹，不存在继续创建
 * @param string $dir     路径
 * @param string $mode    文件夹权限
 */
function force_mkdir($dir, $mode = 0777)
{
    if (is_dir($dir) || @mkdir($dir, $mode))
        return true;
    if (!force_mkdir(dirname($dir), $mode))
        return false;
    return @mkdir($dir, $mode);
}



/*
 *  生成随机字符串
 *
 *  $length    字符串长度
 */
function random_str($length)
{
    // 密码字符集，可任意添加你需要的字符
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $str = '';
    for ($i = 0; $i < $length; $i++) {
        // 这里提供两种字符获取方式
        // 第一种是使用 substr 截取$chars中的任意一位字符；
        // 第二种是取字符数组 $chars 的任意元素
        $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        //$str .= $chars[mt_rand(0, strlen($chars) - 1)];
    }
    return $str;
}



/**
 * 获取请求的根路径
 * 
 * @return string
 */
function get_root_host()
{
    $host = request()->domain();
    $str = request()->baseFile();
    return $host . preg_replace('#/[^/]*\.php$#', '', $str);
}


/**
 * @notes 删除目标目录
 * @param $path
 * @param $delDir
 * @return bool|void
 */
function del_target_dir($path, $delDir)
{
    //没找到，不处理
    if (!file_exists($path)) {
        return false;
    }

    //打开目录句柄
    $handle = opendir($path);
    if ($handle) {
        while (false !== ($item = readdir($handle))) {
            if ($item != "." && $item != "..") {
                if (is_dir("$path/$item")) {
                    del_target_dir("$path/$item", $delDir);
                } else {
                    unlink("$path/$item");
                }
            }
        }
        closedir($handle);
        if ($delDir) {
            return rmdir($path);
        }
    } else {
        if (file_exists($path)) {
            return unlink($path);
        }
        return false;
    }
}