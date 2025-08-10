<template>
  <a-card class="general-card" :header-style="{ paddingBottom: '14px' }">
    <template #title> 热门榜单 </template>
    <template #extra>
      <a>查看更多</a>
    </template>
    <a-table
      :dataSource="tableData"
      :pagination="false"
      :bordered="false"
      :columns="columns"
      style="margin-bottom: 20px"
      :scroll="{ x: '100%', y: '252px' }"
    >
    </a-table>
  </a-card>
</template>

<script lang="ts" setup>
const columns = ref([
  {
    title: "排名",
    dataIndex: "ranking"
  },
  {
    title: "名称",
    dataIndex: "author"
  },
  {
    title: "内容量",
    dataIndex: "contentCount",
    sorter: true
  },
  {
    title: "点击量",
    dataIndex: "clickCount",
    sorter: true
  }
]);
const tableData = ref<any>([
  { ranking: 1, author: "放问气", contentCount: "5145", clickCount: "8595" },
  { ranking: 2, author: "来观住", contentCount: "8043", clickCount: "2676" },
  { ranking: 3, author: "可完单", contentCount: "9176", clickCount: "5645" },
  { ranking: 4, author: "数电包", contentCount: "8830", clickCount: "8187" },
  { ranking: 5, author: "列明老", contentCount: "1683", clickCount: "2779" },
  { ranking: 6, author: "强当圆", contentCount: "6765", clickCount: "4234" },
  { ranking: 7, author: "得色基", contentCount: "5004", clickCount: "6017" }
]);
</script>

<style scoped lang="less">
.general-card {
  max-height: 425px;
}
</style>
