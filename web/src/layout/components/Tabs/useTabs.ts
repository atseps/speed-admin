interface Tabs {
  title: string;
  path: string;
  icon: string;
  query?: Recordable;
}

interface RouteParams {
  path: string;
  query?: Recordable;
}

export function useTabs() {
  const route = useRoute();
  const router = useRouter();
  const active = ref<string>("");
  const list = ref<Array<Tabs>>([]);

  //添加
  const create = (tab: Tabs) => {
    const has = list.value.find((item: Tabs) => item.path === tab.path);
    if (!has) list.value.push(tab);
    active.value = tab.path;
  };

  //监听路由变化
  watch(
    () => route.path,
    path => {
      const title = route.meta.title as string;
      const icon = route.meta.icon as string;
      const query: Recordable = route.query;
      create({ title, path, icon, query });
    },
    { immediate: true }
  );

  //导航
  const navigation = (next: RouteParams) => {
    if (route.path !== next.path) {
      router.push(next);
    }
  };

  //关闭标签页
  const close = (path: string) => {
    const index = list.value.findIndex(item => item.path == path);
    list.value = list.value.filter(item => item.path !== path);
    if (route.path === path && list.value.length) {
      if (index >= 1) {
        const routes = list.value[index - 1];
        navigation({ path: routes.path, query: routes.query });
      } else {
        const routes = list.value[index];
        navigation({ path: routes.path, query: routes.query });
      }
    }
  };

  //关闭当前标签页
  const closeCurrent = () => {
    const currentPath = route.path;
    if (list.value.length > 1) {
      close(currentPath);
    }
  };

  //关闭其他
  const closeOther = () => {
    const title = route.meta.title as string;
    const icon = route.meta.icon as string;
    list.value = [{ title, path: active.value, icon }];
  };

  //关闭所有
  const closeAll = () => {
    const title = route.meta.title as string;
    const icon = route.meta.icon as string;
    list.value = [{ title, path: active.value, icon }];
  };

  return {
    active,
    list,
    create,
    navigation,
    close,
    closeAll,
    closeOther,
    closeCurrent
  };
}
