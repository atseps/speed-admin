import { ref, Ref } from 'vue';

export interface TreeNode {
  id: string | number;
  children?: TreeNode[];
  [key: string]: any;
}

/**
 * 高亮搜索
 * @param searchField 要搜索的字段名，如 'title'、'name'
 */
export function useTreeSearch(searchField: string = 'title') {
  // 搜索关键词
  const searchText = ref<string>('');
  // 展开的行 keys
  const expandedRowKeys = ref<(string | number)[]>([]);

  /**
   * 高亮文本
   */
  const highlightText = (text: string, keyword: string): string => {
    if (!keyword || !text) return text;
    const escaped = keyword.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    const regex = new RegExp(`(${escaped})`, 'gi');
    return text.replace(
      regex,
      '<span style="color:#ff5500">$1</span>'
    );
  };

  /**
   * 递归收集匹配节点及其所有祖先 ID
   */
  const collectMatchedAndAncestors = (nodes: TreeNode[], keyword: string): Set<string | number> => {
    const ancestorSet = new Set<string | number>();
    const walk = (list: TreeNode[], path: (string | number)[]) => {
      for (const item of list) {
        const currentPath = [...path, item.id];
        if (
          keyword &&
          typeof item[searchField] === 'string' &&
          item[searchField].toLowerCase().includes(keyword.toLowerCase())
        ) {
          currentPath.forEach(id => ancestorSet.add(id));
        }
        if (item.children?.length) {
          walk(item.children, currentPath);
        }
      }
    };
    walk(nodes, []);
    return ancestorSet;
  };

  /**
   * 执行搜索
   */
  const doSearch = (keyword: string, treeData: Ref<TreeNode[]>) => {
    searchText.value = keyword;
    if (keyword.trim()) {
      const ancestors = collectMatchedAndAncestors(treeData.value, keyword);
      expandedRowKeys.value = Array.from(ancestors);
    } else {
      expandedRowKeys.value = []; // 搜索清空 → 折叠全部
    }
  };

  /**
   * 重置搜索
   */
  const resetSearch = () => {
    searchText.value = '';
    expandedRowKeys.value = [];
  };

  return {
    searchText,
    expandedRowKeys,
    highlightText,
    doSearch,
    resetSearch,
    collectMatchedAndAncestors
  };
}
