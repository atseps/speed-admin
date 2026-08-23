import { nextTick, onBeforeUnmount, readonly, shallowRef } from "vue";
import type { Ref } from "vue";
import { message } from "ant-design-vue";
import Sortable, { type MoveEvent, type Options, type SortableEvent } from "sortablejs";
import { updateSort as updateSortApi } from "@/api/system/menu";
import { treeEach } from "@/utils";

type MenuKey = string | number;

interface MenuSortNode {
  id: MenuKey;
  sort?: number;
  children?: MenuSortNode[];
}

interface AntTableComponentRef {
  $el?: HTMLElement;
}

export interface MenuTableRef {
  tableElRef?: AntTableComponentRef;
}

interface DragNodeInfo {
  node: MenuSortNode;
  siblings: MenuSortNode[];
  parent: DragNodeInfo | null;
}

interface DragTarget {
  source: DragNodeInfo;
  target: DragNodeInfo;
  insertAfter: boolean;
}

interface UseMenuTreeDragSortOptions {
  tableRef: Ref<MenuTableRef | null>;
  getDataSource: () => MenuSortNode[];
  refresh: () => void;
}

interface SpillSortableOptions extends Options {
  revertOnSpill?: boolean;
  onSpill?: (event: SortableEvent) => void;
}

interface SortSnapshot {
  siblings: MenuSortNode[];
  nodes: MenuSortNode[];
  sorts: Array<number | undefined>;
}

/**
 * 管理菜单树表格的同层拖拽排序。
 *
 * @param options 表格引用、数据读取方法和失败刷新方法
 * @returns 拖拽状态与重新绑定方法
 */
export function useMenuTreeDragSort({
  tableRef,
  getDataSource,
  refresh
}: UseMenuTreeDragSortOptions) {
  const isDragging = shallowRef(false);
  let tableBody: HTMLElement | null = null;
  let sortableInstance: ReturnType<typeof Sortable.create> | null = null;
  let dragNodeMap = new Map<string, DragNodeInfo>();
  let dragTarget: DragTarget | null = null;
  let isSaving = false;
  let disposed = false;

  /**
   * 获取当前菜单表格的 tbody 元素。
   *
   * @returns 当前表格 tbody，不存在时返回 null
   */
  function getTableBody(): HTMLElement | null {
    return (
      tableRef.value?.tableElRef?.$el?.querySelector<HTMLElement>(".ant-table-tbody") ?? null
    );
  }

  /**
   * 生成节点、兄弟数组和父节点的索引。
   *
   * @param nodes 当前层级节点
   * @param parent 当前层级的父节点信息
   * @param map 节点索引
   * @returns 完整节点索引
   */
  function buildDragNodeMap(
    nodes: MenuSortNode[],
    parent: DragNodeInfo | null = null,
    map = new Map<string, DragNodeInfo>()
  ): Map<string, DragNodeInfo> {
    for (const node of nodes) {
      const nodeInfo: DragNodeInfo = { node, siblings: nodes, parent };
      map.set(String(node.id), nodeInfo);
      if (node.children?.length) {
        buildDragNodeMap(node.children, nodeInfo, map);
      }
    }
    return map;
  }

  /**
   * 更新拖动目标的前后插入提示线。
   *
   * @param targetNode 目标同级节点，为空时清除提示
   * @param insertAfter 是否插入目标节点之后
   * @returns 无返回值
   */
  function updateDragIndicator(targetNode?: MenuSortNode, insertAfter = false): void {
    tableBody
      ?.querySelector(".menu-drop-before, .menu-drop-after")
      ?.classList.remove("menu-drop-before", "menu-drop-after");
    if (!targetNode || !tableBody) return;

    const rows = Array.from(
      tableBody.querySelectorAll<HTMLElement>("tr.ant-table-row")
    );
    const branchIds = new Set<string>();
    treeEach([targetNode], (node: MenuSortNode) => branchIds.add(String(node.id)));
    const branchRows = rows.filter(
      row => row.dataset.rowKey && branchIds.has(row.dataset.rowKey)
    );
    // 插入分支之后时，提示线需要位于最后一个已展开后代的底部。
    const indicatorRow = insertAfter
      ? (branchRows[branchRows.length - 1] ?? null)
      : (branchRows.find(row => row.dataset.rowKey === String(targetNode.id)) ?? null);
    indicatorRow?.classList.add(insertAfter ? "menu-drop-after" : "menu-drop-before");
  }

  /**
   * 清除当前拖动目标及提示线。
   *
   * @returns 无返回值
   */
  function clearDragTarget(): void {
    dragTarget = null;
    updateDragIndicator();
  }

  /**
   * 初始化本次拖动所需的树节点索引。
   *
   * @returns 无返回值
   */
  function handleDragStart(): void {
    isDragging.value = true;
    clearDragTarget();
    dragNodeMap = buildDragNodeMap(getDataSource());
  }

  /**
   * 解析当前悬停行对应的同层分支目标。
   *
   * @param evt Sortable 移动事件
   * @returns 始终返回 false，由 Vue 数据负责实际排序
   */
  function handleDragMove(evt: MoveEvent): false {
    const draggedId = (evt.dragged as HTMLElement).dataset.rowKey;
    const relatedId = (evt.related as HTMLElement).dataset.rowKey;
    const sourceInfo = draggedId ? dragNodeMap.get(draggedId) : undefined;
    const relatedInfo = relatedId ? dragNodeMap.get(relatedId) : undefined;
    let targetInfo = relatedInfo;

    // 后代行逐级提升到与源节点共享兄弟数组的分支根节点。
    while (sourceInfo && targetInfo && targetInfo.siblings !== sourceInfo.siblings) {
      targetInfo = targetInfo.parent ?? undefined;
    }

    if (!sourceInfo || !relatedInfo || !targetInfo || targetInfo === sourceInfo) {
      clearDragTarget();
      return false;
    }

    const sourceIndex = sourceInfo.siblings.indexOf(sourceInfo.node);
    const targetIndex = sourceInfo.siblings.indexOf(targetInfo.node);
    if (sourceIndex === -1 || targetIndex === -1) {
      clearDragTarget();
      return false;
    }

    const insertAfter =
      relatedInfo === targetInfo ? Boolean(evt.willInsertAfter) : sourceIndex < targetIndex;
    if (dragTarget?.target === targetInfo && dragTarget.insertAfter === insertAfter) {
      return false;
    }

    dragTarget = {
      source: sourceInfo,
      target: targetInfo,
      insertAfter
    };
    updateDragIndicator(targetInfo.node, insertAfter);
    return false;
  }

  /**
   * 处理拖出表格后的取消操作。
   *
   * @returns 无返回值
   */
  function handleDragSpill(): void {
    isDragging.value = false;
    clearDragTarget();
  }

  /**
   * 恢复保存前的兄弟节点顺序与排序值。
   *
   * @param snapshot 保存前的数据快照
   * @returns 无返回值
   */
  function rollbackSort(snapshot: SortSnapshot): void {
    snapshot.nodes.forEach((node, index) => {
      node.sort = snapshot.sorts[index];
    });
    snapshot.siblings.splice(0, snapshot.siblings.length, ...snapshot.nodes);
  }

  /**
   * 保存同层菜单排序。
   *
   * @param updates 菜单排序请求数据
   * @param snapshot 保存失败时使用的数据快照
   * @returns 保存完成后的 Promise
   */
  async function persistSort(
    updates: Array<{ id: MenuKey; sort: number }>,
    snapshot: SortSnapshot
  ): Promise<void> {
    isSaving = true;
    try {
      sortableInstance?.option("disabled", true);
      const response = await updateSortApi(updates);
      if (disposed) return;
      if (response.code !== 1) {
        throw new Error(response.msg || "排序更新失败");
      }
      message.success("排序更新成功");
    } catch {
      if (disposed) return;
      rollbackSort(snapshot);
      message.error("排序更新失败");
      refresh();
    } finally {
      isSaving = false;
      // 保存期间即使表格重新绑定，新实例也要在请求结束后统一恢复。
      if (!disposed) {
        sortableInstance?.option("disabled", false);
      }
    }
  }

  /**
   * 处理同层菜单拖动结束后的本地排序。
   *
   * @returns 无返回值
   */
  function handleDragEnd(): void {
    isDragging.value = false;
    const target = dragTarget;
    clearDragTarget();
    if (!target) return;

    const siblings = target.source.siblings;
    const orderedNodes = [...siblings];
    const sourceIndex = orderedNodes.indexOf(target.source.node);
    if (sourceIndex === -1) return;

    const [sourceNode] = orderedNodes.splice(sourceIndex, 1);
    const targetIndex = orderedNodes.indexOf(target.target.node);
    if (!sourceNode || targetIndex === -1) return;

    orderedNodes.splice(targetIndex + (target.insertAfter ? 1 : 0), 0, sourceNode);
    if (orderedNodes.every((node, index) => node === siblings[index])) return;

    const snapshot: SortSnapshot = {
      siblings,
      nodes: [...siblings],
      sorts: siblings.map(node => node.sort)
    };
    const updates = orderedNodes.map((node, index) => {
      const sort = index + 1;
      node.sort = sort;
      return { id: node.id, sort };
    });
    // 原地替换兄弟数组，保持表格和菜单表单共享同一树引用。
    siblings.splice(0, siblings.length, ...orderedNodes);

    void persistSort(updates, snapshot);
  }

  /**
   * 销毁当前 Sortable 实例并清理拖拽状态。
   *
   * @returns 无返回值
   */
  function destroySortable(): void {
    clearDragTarget();
    sortableInstance?.destroy();
    sortableInstance = null;
    tableBody = null;
    dragNodeMap.clear();
    isDragging.value = false;
  }

  /**
   * 为指定 tbody 创建菜单树拖拽实例。
   *
   * @param tbody 当前菜单表格 tbody
   * @returns 无返回值
   */
  function createSortable(tbody: HTMLElement): void {
    const options: SpillSortableOptions = {
      disabled: isSaving,
      draggable: "tr.ant-table-row",
      handle: ".move-icon",
      forceFallback: true,
      fallbackOnBody: true,
      fallbackTolerance: 3,
      fallbackClass: "sortable-drag-fallback",
      chosenClass: "menu-drag-chosen",
      revertOnSpill: true,
      onStart: handleDragStart,
      onMove: handleDragMove,
      onSpill: handleDragSpill,
      onEnd: handleDragEnd
    };
    sortableInstance = Sortable.create(tbody, options);
  }

  /**
   * 在表格完成渲染后重新绑定拖拽实例。
   *
   * @returns 重新绑定完成后的 Promise
   */
  async function rebind(): Promise<void> {
    if (disposed) return;
    try {
      // 先销毁旧实例，避免数据刷新到下一 Tick 期间仍可触发旧拖拽。
      destroySortable();
      await nextTick();
      if (disposed) return;
      // 多次并发绑定会依次恢复，此处再次销毁前一次刚创建的实例。
      destroySortable();

      const tbody = getTableBody();
      if (!tbody) {
        console.warn("[菜单排序] 未找到表格主体，拖拽已禁用");
        return;
      }
      tableBody = tbody;
      createSortable(tbody);
    } catch (error) {
      destroySortable();
      console.error("[菜单排序] 拖拽初始化失败", error);
    }
  }

  /**
   * 标记组合式函数已卸载并释放拖拽实例。
   *
   * @returns 无返回值
   */
  function dispose(): void {
    disposed = true;
    destroySortable();
  }

  onBeforeUnmount(dispose);

  return {
    isDragging: readonly(isDragging),
    rebind
  };
}
