import Menu from "ant-design-vue/es/menu";
import { useRoute } from "vue-router";
import { httpReg } from "@/utils";
import { RouteRecord as RouteMenu } from "@/router/types"
import type { MenuTheme, MenuMode } from "ant-design-vue/es/menu";
import Icon from "@/components/Icon";
import type { MenuInfo } from 'ant-design-vue/es/menu/src/interface';

type Key = string | number;

const { Item: MenuItem, SubMenu } = Menu;

//渲染菜单
const renderMenu = (item: RouteMenu) => {
  if (item && !item.hidden) {
    const bool = item.children && !item.hideChildrenInMenu;
    return bool ? renderSubMenu(item) : renderMenuItem(item);
  }
  return null;
};

//渲染子菜单
const renderSubMenu = (item: RouteMenu) => {
  return (
    <SubMenu
      popupClassName="s-menu-popup"
      key={item.path}
      title={
        <span>
          {renderIcon(item.meta?.icon)}
          {renderTitle(item.meta?.title)}
        </span>
      }
    >
      {() => !item.hideChildrenInMenu && item.children?.map(cd => renderMenu(cd))}
    </SubMenu>
  );
};

//渲染菜单项
const renderMenuItem = (item: RouteMenu) => {
  const meta = Object.assign({}, item.meta);
  const hasRemoteUrl = httpReg(item.path);
  const CustomTag = "router-link";
  const props = { to: item.path };
  const attrs = hasRemoteUrl ? { href: item.path.slice(1), target: "_blank" } : {};
  const customTagProps = Object.assign(props, attrs);
  const Widget:any = resolveComponent(CustomTag);
  return (
    <MenuItem key={item.path} icon={renderIcon(meta.icon)}>
      {() => <Widget {...customTagProps}>{renderTitle(meta.title)}</Widget>}
    </MenuItem>
  );
};

//渲染图标
const renderIcon = (icon: string | undefined) => {
  return <Icon type={icon || "appstore-outlined"} />;
};

//渲染标题
const renderTitle = (title: string | undefined) => {
  return <span>{title}</span>;
};

const RouteMenus = defineComponent({
  name: "RouteMenus",
  props: {
    menus: {
      type: Array as PropType<RouteMenu[]>,
      required: true
    },
    theme: {
      type: String as PropType<MenuTheme>,
      default: "dark"
    },
    mode: {
      type: String as PropType<MenuMode>,
      default: "inline"
    },
    collapsed: {
      type: Boolean,
      default: false
    }
  },
  emits: ["select", "click"],
  setup(props, { emit }) {
    const state = reactive({
      openKeys: [] as Array<Key>,
      selectedKeys: [] as Array<Key>,
      cachedOpenKeys: [] as Array<Key>,
      cachedSelectedKeys: [] as Array<Key>
    });

    const rootSubmenuKeys = computed(() => {
      const keys: Array<Key> = [];
      props.menus.forEach(item => keys.push(item.path));
      return keys;
    });

    const route = useRoute();

    //菜单展开
    const onOpenChange = (openKeys: Key[]) => {
      if (!openKeys.length) return;

      const namePath = route.meta.namePath as Array<string>;
      // 在水平模式下时，不再执行后续
      if (props.mode === "horizontal") {
        state.openKeys = openKeys;
        return;
      }
      //点击菜单时才收缩
      const latestOpenKey = openKeys.find(key => !state.openKeys.includes(key)) as string;
      if (!rootSubmenuKeys.value.includes(latestOpenKey)) {
        state.openKeys = openKeys;
      } else {
        state.openKeys = latestOpenKey ? [latestOpenKey] : [];
      }

      //多级的打开的情况下，让激活的菜单父级都展开
      if (namePath && namePath.includes(latestOpenKey)) {
        state.openKeys = namePath.reverse();
      }
    };

    //激活菜单
    const activateMenu = () => {
      const routes = route.matched.concat();
      let { hidden, active_key }: { hidden?: boolean; active_key?: string } = route.meta;
      if (routes.length >= 2 && hidden) {
        routes.pop();
        state.selectedKeys = [routes[routes.length - 1].path];
      } else {
        state.selectedKeys = [(routes as Array<any>).pop().path];
      }
      if (active_key) {
        if (!active_key.startsWith("/")) {
          active_key = `/${active_key}`;
        }
        state.selectedKeys.push(active_key);
      }
      const openKeys: Array<Key> = [];
      if (props.mode === "inline") {
        routes.forEach(item => {
          item.path && openKeys.push(item.path);
        });
      }
      props.collapsed ? (state.cachedOpenKeys = openKeys) : (state.openKeys = openKeys);
    };

    //监听路由的变化
    watch(
      () => route.path,
      () => {
        activateMenu();
      },
      { immediate: true }
    );

    //监听菜单折叠
    watch(
      () => props.collapsed,
      val => {
        if (val) {
          state.cachedOpenKeys = state.openKeys.concat();
          state.openKeys = [];
        } else {
          state.openKeys = state.cachedOpenKeys;
        }
      }
    );

    //生命周期
    onMounted(() => activateMenu());

    return () => {
      const menuProps = reactive({
        mode: props.mode,
        theme: props.theme,
        openKeys: state.openKeys,
        selectedKeys: state.selectedKeys,
        onSelect: (menu: MenuInfo) => {
          emit("select", menu);
          if (!httpReg(menu.key as string)) {
            state.selectedKeys = [menu.key as string];
          }
        },
        onClick: (menu: MenuInfo) => {
          emit("click", menu);
        },
        onOpenChange: onOpenChange
      });

      const menuItems = props.menus.map(item => {
        if (item.hidden) return null;
        return renderMenu(item);
      });

      return (
        <Menu class="s-menu" {...menuProps}>
          {() => menuItems}
        </Menu>
      );
    };
  }
});

export default RouteMenus;
