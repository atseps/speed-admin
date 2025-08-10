import enquireJs from "enquire.js";
import { useAppStore } from "@/store/modules/app";
export const DEVICE_TYPE = {
  DESKTOP: "desktop", //移动端
  TABLET: "tablet", //平板端
  MOBILE: "mobile" //桌面端
};

const deviceEnquire = function (callback: Function) {
  const matchDesktop = {
    match: () => {
      callback && callback(DEVICE_TYPE.DESKTOP);
    }
  };

  const matchLablet = {
    match: () => {
      callback && callback(DEVICE_TYPE.TABLET);
    }
  };

  const matchMobile = {
    match: () => {
      callback && callback(DEVICE_TYPE.MOBILE);
    }
  };

  // screen and (max-width: 1087.99px)
  enquireJs
    .register("screen and (max-width: 576px)", matchMobile) //移动端
    .register("screen and (min-width: 576px) and (max-width: 1199px)", matchLablet) //平板端
    .register("screen and (min-width: 1200px)", matchDesktop); //桌面端
};

export default function useDeviceEnquire() {
  const store = useAppStore();
  deviceEnquire((deviceType: string) => {
    switch (deviceType) {
      case DEVICE_TYPE.DESKTOP:
        store.changeSetting("device", "desktop");
        store.changeSetting("sidebarOpened", true);
        break;
      case DEVICE_TYPE.TABLET:
        store.changeSetting("device", "tablet");
        break;
      case DEVICE_TYPE.MOBILE:
      default:
        store.changeSetting("device", "mobile");
        store.changeSetting("sidebarOpened", true);
        break;
    }
  });
}
