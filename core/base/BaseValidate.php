<?php
namespace core\base;
use think\Validate;
use think\exception\ValidateException;
class BaseValidate extends Validate
{


    /**
     * 验证是否是有效的数字(小数、负数、整数)
     * @param string $value 验证内容
     * @param string $rule 验证规则
     * @param $data
     * @param string $field 验证的字段名
     * @return  bool
     */
    protected function validNumber($value, $rule, $data, $field)
    {
        return filter_var($value, FILTER_VALIDATE_FLOAT) !== false;
    }


    /**
     * 验证密码必须包含数字和字母,且不小于6位
     * @param string $value 验证内容
     * @param string $rule 验证规则
     * @param $data
     * @param string $field 验证的字段名
     * @return  bool
     */
    protected function alphaPwd($value, $rule, $data, $field)
    {
        if (!preg_match('/^(?![^a-zA-Z]+$)(?!\D+$).{6,}$/', $value)) {
            return false;
        }
        return true;
    }

    
    /**
     * 验证不是汉字
     * @param string $value 验证内容
     * @param string $rule 验证规则
     * @param $data
     * @param string $field 验证的字段名
     * @return  bool
     */
    protected function notChs($value, $rule, $data, $field)
    {    
        if (!preg_match('/[\x{4e00}-\x{9fa5}]+/u', $value)) {
            return true;
        }
        return false;
    }
    

    /**
     * 重写unique方法，验证是否唯一，支持复杂条件验证
     * @access public
     * @param mixed  $value 字段值
     * @param mixed  $rule  验证规则 格式：数据表,字段名,排除ID,主键名
     * @param array  $data  数据
     * @param string $field 验证字段名
     * @return bool
     */
    public function unique($value, $rule, array $data = [], string $field = ''): bool
    {
        if (is_string($rule)) {
            $rule = explode(',', $rule);
        }

        if (false !== strpos($rule[0], '\\')) {
            // 指定模型类
            $db = new $rule[0];
        } else {
            $db = $this->db->name($rule[0]);
        }

        $key = $rule[1] ?? $field;
        $map = [];

        if (strpos($key, '^')) {
            // 支持多个字段验证
            $fields = explode('^', $key);
            foreach ($fields as $key) {
                if (isset($data[$key])) {
                    $map[] = [$key, '=', $data[$key]];
                }
            }
        }elseif (strpos($key, '=')) {
            //支持复杂验证条件
            $fields = explode('&', $key);
            $map_arr=[];
            foreach ($fields as $k) {
                //判断验证条件是否传参，没有传参就使用$data中对应的值
                if (strpos($k, '=')) {
                    $str_map = explode('=', $k);
                    $map[] = [$str_map[0], '=', $str_map[1]];
                    $map_arr[]=$str_map[0];
                }else{
                    $map[] = [$k, '=', $data[$k]];
                    $map_arr[]=$k;
                }
            }
            if(!in_array($field, $map_arr)){
                $map[] = [$field, '=', $data[$field]];
            }
       } elseif (isset($data[$field])) {
            $map[] = [$key, '=', $data[$field]];
        } else {
            $map = [];
        }
        $pk = !empty($rule[3]) ? $rule[3] : $db->getPk();

        if (is_string($pk)) {
            if (isset($rule[2])) {
                $map[] = [$pk, '<>', $rule[2]];
            } elseif (isset($data[$pk])) {
                $map[] = [$pk, '<>', $data[$pk]];
            }
        }
        if ($db->where($map)->field($pk)->find()) {
            return false;
        }

        return true;
    }




    /**
     * 切面验证接收到的参数
     * @param null $scene 场景验证
     * @param null $field 接收的请求参数，为空接收所有
     * @param array $validateData 验证参数，可追加和覆盖掉接收的参数
     * @return array
     */
    public function validated($scene = null, $field = '', array $validateData = []): array
    {
        //接收参数
	    $params = request()->param($field);
		
        //合并验证参数
        $params = array_merge($params, $validateData);

        //场景
        if ($scene) {
            $result = $this->scene($scene)->check($params);
        } else {
            $result = $this->check($params);
        }

        if (!$result) {
            $exception = is_array($this->error) ? implode(';', $this->error) : $this->error;
            throw new ValidateException($exception);
        }
        // 验证成功返回数据
        return $params;
    }




}