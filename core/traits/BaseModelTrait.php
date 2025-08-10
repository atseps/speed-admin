<?php

namespace core\traits;

use core\facade\Util;
use core\base\BaseModel;

/**
 * 模型操作
 * trait baseModelTrait
 * @package core\traits
 */
trait BaseModelTrait {


    use DataRangScopeTrait;


    /**
     * 查询单个数据
     * @param  string $id      id主键
     * @param  array  $field   查询的字段
     * @param  bool   $trash   是否查询软删除的数据
     * @return mixed
     */
    public function findBy($id, $field = ['*'], $trash = false) {
        if ($trash) {
            return static::onlyTrashed()->find($id);
        }

        return static::where($this->getPk(), $id)->field($field)->find();
    }




    /**
     * 删除数据
     * @param string|array  $id  id主键
     * @param  bool $force    软删除/真删除
     * @return mixed
     */
    public function deleteBy($id, $force = false) {
        return static::destroy(is_array($id) ? $id : Util::strToArr($id), $force);
    }




    /**
     * 添加一条数据
     * @param  array  $data       添加的数据
     * @param  bool   $isValidate 是否需要验证数据
     * @return bool | int
     */
    public function storeBy(array $data) {
        if ($this->allowField($this->field)->save($this->filterSaveData($data))) {
            return $this->{$this->getPk()};
        }

        return false;
    }



    /**
     * 用于循环插入
     *
     * @param  array  $data
     * @return mixed
     */
    public function createBy(array $data) {

        $model = parent::create($this->filterSaveData($data), $this->field, true);

        return $model->{$this->getPk()};
    }



    /**
     * 批量插入
     *
     * @param array $data
     * @return mixed
     */
    public function insertAllBy(array $data) {
        $newData = [];
        foreach ($data as $item) {
            foreach ($item as $field => $value) {
                if (!in_array($field, $this->field)) {
                    unset($item[$field]);
                }
                if (in_array('create_time', $this->field)) {
                    $item['create_time'] = time();
                }
            }
            $newData[] = $item;
        }
        return $this->insertAll($newData);
    }



    /**
     * 更新一条数据
     * @param  string $id           id主键
     * @param  array  $data         更新的数据
     * @return bool
     */
    public function updateBy($id, $data, $field = ''): bool {
        if (static::update($data, [$field ?: $this->getPk() => $id], $this->field)) {
            return true;
        }
        return false;
    }


    /**
     * 禁用/启用 如果表里面有 status 字段默认使用，当然也可以自定义字段
     *
     * @param $id
     * @param string $field
     * @return mixed
     */
    public function disOrEnable($id, $field = 'status') {
        $model = $this->findBy($id);

        $status = $model->{$field} == BaseModel::DISABLE ? BaseModel::ENABLE : BaseModel::DISABLE;

        $model->{$field} = $status;

        return $model->save();
    }



    /**
     * 恢复软删除的数据
     * @param  string $id 
     * @return mixed
     */
    public function recover($id) {
        return static::onlyTrashed()->find($id)->restore();
    }





    /**
     * 过滤添加数据
     *
     * @param array $data
     * @return mixed
     */
    public function filterSaveData(array $data) {
        foreach ($data as $field => $value) {
            if (is_null($value)) {
                unset($data[$field]);
            }

            if ($field == $this->getPk()) {
                unset($data[$field]);
            }
        }

        return $data;
    }



    /**
     * 获取错误描述
     *
     * @return mixed
     */
    public function getError() {
        return $this->error;
    }







}
