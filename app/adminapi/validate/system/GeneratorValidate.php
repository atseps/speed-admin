<?php

namespace app\adminapi\validate\system;

use core\base\BaseValidate;
use think\facade\Db;

class GeneratorValidate extends BaseValidate
{

    protected $rule = [
        'table_name|表名称' =>  'require|max:200|regex:/^[A-Za-z0-9_]+$/',
        'table_comment|表描述'  =>  'require|max:300',
        'generate_type|生成方式' => 'require',
        'module_name|模块名' => 'require|max:100|regex:/^[A-Za-z0-9_]+$/',
        'delete_type|删除类型'=> 'require',
        'menu_pid|父级菜单' => 'require',
        'menu_name|菜单名称' => 'require|max:30',
        'menu_type|菜单构建方式' => 'require',
        'column|字段信息' => 'require|array|checkColumn',
        'table|导入数据' => 'require|array|checkTable',
        'class_dir|类目录' => 'max:100|regex:/^[A-Za-z0-9_]+(?:\/[A-Za-z0-9_]+)*$/',
    ];

    /**
     * 自定义错误提示
     * regex 规则的默认提示（"xx不符合指定规则"）说不清具体要求，这里按字段给出准确的格式说明
     * @var array
     */
    protected $message = [
        'table_name.regex'  => '表名称只能由字母、数字、下划线组成',
        'module_name.regex' => '模块名只能由字母、数字、下划线组成',
        'class_dir.regex'   => '类目录只能由字母、数字、下划线组成，多级目录请用斜杠分隔，如 user/detail',
    ];


    /**
     * 选择数据表场景
     * 
     * @return GeneratorValidate
     */
    public function sceneEdit()
    {
        return $this->remove('table',true);
    }

    /**
     * 选择数据表场景
     * 
     * @return GeneratorValidate
     */
    public function sceneAdd()
    {
        return $this->only(['table']);
    }

    /**
     * @notes 校验选择的数据表信息
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     */
    protected function checkTable($value, $rule, $data)
    {
        foreach ($value as $item) {
            if (!isset($item['table_name']) || !isset($item['table_comment'])) {
                return '参数缺失';
            }
            if (!preg_match('/^[A-Za-z0-9_]+$/', $item['table_name'])) {
                return '表名称格式不正确：' . $item['table_name'];
            }
            $exist = Db::query(
                "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?",
                [$item['table_name']]
            );
            if (empty($exist)) {
                return '当前数据库不存在' . $item['table_name'] . '表';
            }
        }
        return true;
    }

    /**
     * @notes 校验表字段参数
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     */
    protected function checkColumn($value, $rule, $data)
    {
        foreach ($value as $item) {
            if (!isset($item['id'])) {
                return '表字段id参数缺失';
            }
            if (!isset($item['table_id'])) {
                return '表格id参数缺失';
            }
            if (!isset($item['name'])) {
                return '表字段名称参数缺失';
            }
            if (!isset($item['search_type'])) {
                return '请选择查询方式';
            }
            if (!isset($item['show_type'])) {
                return '请选择显示类型';
            }
        }
        return true;
    }
}
