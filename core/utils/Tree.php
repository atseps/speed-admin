<?php
namespace core\utils;

class Tree
{
    /**
     * 生成树型结构所需要的2维数组
     * @var array
     */
    private array $data = [];
    
    private string $parentKey = 'pid';
    private string $primaryKey = 'id';

    
    /**
     * 初始化方法
     * @param array $data 二维数组
     * @param array $options 配置参数
     * @return $this
     */
    public function init(array $data = [], array $options = []): self
    {
        $this->data = $data;
        
        $this->parentKey = $options['parentKey'] ?? $this->parentKey;
        $this->primaryKey = $options['pk'] ?? $this->primaryKey;
        
        return $this;
    }

    /**
     * 设置主键字段名
     * @param string $primaryKey
     * @return $this
     */
    public function setPrimaryKey(string $primaryKey): self
    {
        $this->primaryKey = $primaryKey;
        return $this;
    }

    /**
     * 设置父级关联字段名
     * @param string $parentKey
     * @return $this
     */
    public function setParentKey(string $parentKey): self
    {
        $this->parentKey = $parentKey;
        return $this;
    }


    /**
     * 获取直接子节点
     * @param int $parentId
     * @return array
     */
    public function getChildren(int $parentId): array
    {
        $children = [];
        
        foreach ($this->data as $item) {
            if (!isset($item[$this->primaryKey])) {
                continue;
            }
            
            if ((int)$item[$this->parentKey] === $parentId) {
                $children[$item[$this->primaryKey]] = $item;
            }
        }
        
        return $children;
    }

    /**
     * 获取所有子节点（递归）
     * @param int $parentId
     * @param bool $includeSelf 是否包含自身
     * @return array
     */
    public function getAllChildren(int $parentId, bool $includeSelf = false): array
    {
        $result = [];
        
        if ($includeSelf) {
            $self = $this->findById($parentId);
            if ($self) {
                $result[] = $self;
            }
        }
        
        $directChildren = $this->getChildren($parentId);
        foreach ($directChildren as $child) {
            $result[] = $child;
            $result = array_merge($result, $this->getAllChildren($child[$this->primaryKey]));
        }
        
        return $result;
    }

    /**
     * 获取所有子节点ID
     * @param int $parentId
     * @param bool $includeSelf
     * @return array
     */
    public function getAllChildrenIds(int $parentId, bool $includeSelf = false): array
    {
        return array_column($this->getAllChildren($parentId, $includeSelf), $this->primaryKey);
    }

    /**
     * 获取直接父节点
     * @param int $childId
     * @return array|null
     */
    public function getParent(int $childId): ?array
    {
        $child = $this->findById($childId);
        if (!$child || !isset($child[$this->parentKey])) {
            return null;
        }
        
        return $this->findById($child[$this->parentKey]);
    }

    /**
     * 获取所有父节点
     * @param int $childId
     * @param bool $includeSelf
     * @return array
     */
    public function getAllParents(int $childId, bool $includeSelf = false): array
    {
        $parents = [];
        $current = $this->findById($childId);
        
        if (!$current) {
            return [];
        }
        
        if ($includeSelf) {
            $parents[] = $current;
        }
        
        while ($current && isset($current[$this->parentKey])) {
            $parent = $this->findById($current[$this->parentKey]);
            if ($parent) {
                $parents[] = $parent;
                $current = $parent;
            } else {
                break;
            }
        }
        
        return $parents;
    }

    /**
     * 获取所有父节点ID
     * @param int $childId
     * @param bool $includeSelf
     * @return array
     */
    public function getAllParentIds(int $childId, bool $includeSelf = false): array
    {
        return array_column($this->getAllParents($childId, $includeSelf), $this->primaryKey);
    }

    /**
     * 构建树形结构
     * @param int $rootId 根节点ID
     * @return array
     */
    public function buildTree(int $rootId = 0): array
    {
        $tree = [];
        
        foreach ($this->getChildren($rootId) as $child) {
            $child['children'] = $this->buildTree($child[$this->primaryKey]);
            if (empty($child['children'])) {
                unset($child['children']);
            }
            $tree[] = $child;
        }
        
        return $tree;
    }

    /**
     * 将树形结构转换为平面列表
     * @param array $tree
     * @param string $childrenKey
     * @return array
     */
    public function treeToList(array $tree, string $childrenKey = 'children'): array
    {
        $list = [];
        
        foreach ($tree as $node) {
            $children = $node[$childrenKey] ?? [];
            unset($node[$childrenKey]);
            
            $list[] = $node;
            
            if (!empty($children)) {
                $list = array_merge($list, $this->treeToList($children, $childrenKey));
            }
        }
        
        return $list;
    }

    /**
     * 根据ID查找节点
     * @param int $id
     * @return array|null
     */
    private function findById(int $id): ?array
    {
        foreach ($this->data as $item) {
            if (isset($item[$this->primaryKey]) && (int)$item[$this->primaryKey] === $id) {
                return $item;
            }
        }
        return null;
    }
}
