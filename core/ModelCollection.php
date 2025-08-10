<?php

declare(strict_types=1);

namespace core;

use think\model\Collection;
use core\facade\{Tree, Excel};

class ModelCollection extends Collection
{

    /**
     * 转换为树结构
     *
     * @param int $pid
     * @param string $pidField
     * @param string $children
     * @return array
     */
    public function toTree(int $pid = 0, string $pidField = 'pid'): array
    {
        $pk = 'id';

        if ($this->count()) {
            $pk = $this->first()->getPk();
        }

        return Tree::init($this->toArray(), ['pk' => $pk, 'parentKey' => $pidField])->buildTree($pid);
    }


    /**
     * 导出数据
     *
     * @param $header
     * @param string $path
     * @param string $disk
     * @return mixed|string[]
     *@throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function export(string $fileName, $column, string $extension = 'csv'): array
    {

        return Excel::setExtension($extension)->export($fileName, $column, $this->toArray());
    }


    /**
     * 获取当前级别下的所有子级
     *
     * @param array $ids
     * @param string $parentFields
     * @param string $column
     * @return array
     */
    public function getAllChildrenIds(array $ids, string $parentFields = 'parent_id', string $column = 'id'): array
    {
        array_walk($ids, function (&$item) {
            $item = intval($item);
        });

        $childIds = $this->whereIn($parentFields, $ids)->column($column);

        if (!empty($childIds)) {
            $childIds = array_merge($childIds, $this->getAllChildrenIds($childIds));
        }

        return $childIds;
    }
}
