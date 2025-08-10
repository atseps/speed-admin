<?php

namespace core;

use think\db\Query;
use core\facade\Util;
use think\Paginator;
use think\helper\Str;
use core\exception\FailedException;
class BasisQuery extends Query
{



    /**
     * 搜索 
     * @param array $params
     * @return BasisQuery
     */
    public function search(array $params = []): BasisQuery
    {

        $params = empty($params) ? request()->param() : $params;

        if (empty($params)) {
            return $this;
        }

        $params = Util::filterSearchParams($params);

        foreach ($params as $field => $value) {
            $method = 'search' . Str::studly($field) . 'Attr';
            if (method_exists($this->model, $method)) {
                $this->model->$method($this, $value, $params);
            }
        }

        return $this;
    }


    /**
     * 排序
     * @param string $order 排序的方式
     * @return BasisQuery
     */
    public function sort($order = 'desc'): BasisQuery
    {
        if (in_array('sort', array_keys($this->getFields()))) {
            $this->order($this->getTable() . '.sort', $order);
        }

        if (in_array('weight', array_keys($this->getFields()))) {
            $this->order($this->getTable() . '.weight', $order);
        }

        $this->order($this->getTable() . '.' . $this->getPk(), $order);

        return $this;
    }



    /**
     * 加密id
     * @param string $field 字段
     * @return BasisQuery
     */
    public function hashids($field = 'id')
    {
        $this->withAttr($field, function ($value) {
            return auth_code($value);
        });
        return $this;
    }


    /**
     * 获取别名
     * 
     * @return mixed
     */
    public function getAlias()
    {
        return isset($this->options['alias']) ? $this->options['alias'][$this->getTable()] : $this->getTable();
    }


    /**
     * 重写findOrFail
     * throw FailedException
     * @param int $id
     * @return array
     */
    public function findOrFail($id = null)
    {
        $data = $this->findOrEmpty($id);
        if ($data->isEmpty()) {
            throw new FailedException('数据不存在');
        }
        return $data;
    }


    /**
     * 重写whereLike
     *
     * @param string $field
     * @param mixed $condition
     * @param string $option
     * @param string $logic
     * @return Query
     */
    public function whereLike(string $field, $condition, string $logic = 'AND', $option = 'both'): Query
    {
        switch ($option) {
            case 'both':
                $condition = '%' . $condition . '%';
                break;
            case 'left':
                $condition = '%' . $condition;
                break;
            default:
                $condition .= '%';
        }

        return parent::whereLike($this->getAlias() . '.' . $field, $condition, $logic);
    }


    /**
     * @param string $field
     * @param $condition
     * @param string $logic
     * @return Query
     */
    public function whereLeftLike(string $field, $condition, string $logic = 'AND'): Query
    {
        return $this->where($field, $condition, $logic, 'left');
    }



    /**
     * @param string $field
     * @param $condition
     * @param string $logic
     * @return Query
     */
    public function whereRightLike(string $field, $condition, string $logic = 'AND'): Query
    {
        return $this->where($field, $condition, $logic, 'right');
    }



    /**
     * 重写分页,自动获取每页数量
     *
     * @param  number   $listRows 每页显示多少数据
     * @param  boolean  $simple   是否简洁分页
     * @param  int  $maxRows      每页最多查询的数据
     * @return Paginator
     */
    public function paginate($listRows = null, $simple = false, $pageSizeMax = null): Paginator
    {
        $pageSizeMax = config('system.page_size_max');
        if (!$listRows) {
            $listRows = request()->param('pageSize', 15);
            if ($listRows > $pageSizeMax) {
                $listRows = $pageSizeMax;
            }
        }
        return parent::paginate($listRows, $simple);
    }
}
