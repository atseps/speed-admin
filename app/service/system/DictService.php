<?php

namespace app\service\system;

use core\base\BaseService;
use app\model\system\Dict;
use core\exception\FailedException;
use think\facade\{Cache, Db};

class DictService extends BaseService
{

    public function __construct(Dict $model)
    {
        $this->model = $model;
    }

    /**
     * @var array 默认排序规则
     */
    public $defaultOrder = ['sort' => 'asc', 'id'];

    /**
     * 获取列表
     * @return array
     */
    public function getList()
    {
        $data = $this->model->search()->where('value', '<>', Dict::CATEGORYNAME)->order($this->defaultOrder)->select();
        return $data;
    }


    /**
     * 获取字典数据
     * @param string|array $types 字典类型或类型数组
     * @param bool $toString 是否转换为字符串
     * @return array
     */
    public  function getDictData($types, $toString = false)
    {
        $data = [];
        $statusMap = $this->model->whereIn('value', $types)->column('status', 'value');
        foreach ((array)$types as $type) {
            if (isset($statusMap[$type]) && $statusMap[$type] != 1) {
                continue;
            }
            $cache = self::getCacheData($type, true);
            if ($cache === false) continue;
            $items = [];
            foreach ($cache as $value => $item) {
                if ($item['status'] != 1) {
                    continue;
                }
                $formattedItem = [
                    'value' => $toString ? strval($value) : $value,
                    'label' => $toString ? strval($item['name']) : $item['name']
                ];
                $items[] = $formattedItem;
            }
            is_array($types) ? $data[$type] = $items :  $data = $items;
        }
        return $data;
    }


    /**
     * 新增
     * @param array $data
     * @return int|bool
     */
    public function save(array $data)
    {
        return $this->model->storeBy($data);
    }


    /**
     * 更新字典数据
     * @param int $id 主键ID
     * @param array $data 更新数据
     * @return bool
     * @throws FailedException 业务异常
     * @throws \Exception 系统异常
     */
    public function update(int $id, array $data): bool
    {
        $dict = $this->model->findOrFail($id); 
        Db::startTrans();
        try {
            // 主数据更新
            $updated = $this->model->updateBy($id, $data);
            if (!$updated) {
                throw new FailedException('更新失败');
            }

            // 如果是字典类型变更，同步关联数据
            if (isset($data['type']) && $data['type'] === Dict::CATEGORYNAME) {
                $updateData = [
                    'type' => $data['value'],
                    'widget_type' => $data['widget_type']
                ];
                $this->model->where('type', $dict->value)->update($updateData);
            }

            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            throw $e instanceof FailedException ? $e : new \Exception('系统错误', 500, $e);
        }
    }

    /**
     * 更新排序
     * @param int $id
     * @param array $data
     * @return collection
     */
    public function updateSort(array $data)
    {
        foreach ($data as $value) {
            if (!array_key_exists('id', $value)) {
                throw new FailedException('主键不存在');
            }
        }
        return $this->model->saveAll($data);
    }


    /**
     * 修改状态
     * @param  $id  id主键
     * @return boolean
     */
    public function changeStatus($id)
    {
        $this->model->findOrFail($id);
        return $this->model->disOrEnable($id);
    }


    /**
     * 删除
     * @param  $id  id主键
     * @return boolean
     */
    public function delete($id)
    {
        $dict = $this->model->findOrFail($id);
        try {
            Db::startTrans();
            $result = $this->model->deleteBy($id);
            if (!$result) return false;
            //删除的是字典类型，同步删除关联的字典
            if ($dict->type == Dict::CATEGORYNAME) {
                $this->model->where('type', $dict->value)->delete();
            }
            Db::commit();
            return true;
        } catch (\Exception) {
            Db::rollback();
            return false;
        }
    }

    /**
     * 更新字典缓存
     * @return boolean
     */
    public function updateCache()
    {
        // 获取所有字典数据
        $allData = $this->model->order($this->defaultOrder)->select()->toArray();
        if (empty($allData)) {
            return false;
        }
        // 将数据进行分组
        $groupedData = [];
        foreach ($allData as $item) {
            $groupedData[$item['type']][] = $item;
        }
        // 批量设置缓存
        $success = true;
        foreach ($groupedData as $type => $value) {
            $dict = [];
            foreach ($value as $v) {
                $dict[$v['value']] = $v;
            }
            $success = cache('dict_' . $type, $dict) && $success;
        }
        return $success;
    }


    /**
     * 获取缓存的字典
     * @param   string  $type 字典类型
     * @param   boolean $fullInfo 是否缓存完整值
     * @return  array 
     */
    public static function getCacheData($type, $fullInfo = false)
    {
        $data = Cache::remember('dict_' . $type, function () use ($type) {
            $data = Dict::where('type', $type)->order(['sort' => 'asc', 'id'])->select();
            if ($data->isEmpty()) return false;
            $dictData = [];
            foreach ($data->toArray() as $v) {
                $dictData[$v['value']] = $v;
            }
            return $dictData;
        });
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $val = array_pick('name,widget_type,color,status', $v);
                $data[$k] = $fullInfo ? $val : $v['name'];
            }
        }
        return $data;
    }
}
