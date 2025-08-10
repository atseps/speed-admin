<?php

namespace core\base;

use think\Model;
use core\traits\{BaseModelTrait, TransTrait};
use core\ModelCollection;
use think\model\Collection;
abstract class BaseModel extends Model {
    use BaseModelTrait;
    use TransTrait;

    // 开启
    public const ENABLE = 1;
    // 禁用
    public const DISABLE = 2;


    /**
     * 重写 collection
     *
     * @param array|iterable $collection
     * @param string|null $resultSetType
     * @return CatchModelCollection|mixed
     */
    public function toCollection(iterable $collection = [],  $resultSetType = null):Collection
    {
        $resultSetType = $resultSetType ?: $this->resultSetType;

        if ($resultSetType && false !== strpos($resultSetType, '\\')) {
            $collection = new $resultSetType($collection);
        } else {
            $collection = new ModelCollection($collection);
        }

        return $collection;
    }



}
