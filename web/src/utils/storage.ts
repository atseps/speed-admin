import config from "@/config";

const cacheNamespace = config.cachePrefix || "localstorage";

const LocalStorage = {
  /**
   * 设置存储
   * @param {string} name
   * @param {*} value
   * @param {number|null} expire - 过期时间（秒）
   */
  set(name: string, value: any, expire: number | null = null): void {
    const stringifyValue = JSON.stringify({
      value,
      expire: expire !== null ? Date.now() + expire * 1000 : null
    });
    localStorage.setItem(`${cacheNamespace}${name}`, stringifyValue);
  },

  /**
   * 获取存储
   * @param {string} name
   * @param {*} def - 默认值
   * @returns {*}
   */
  get(name: string, def: any = null): any {
    try {
      const item = localStorage.getItem(`${cacheNamespace}${name}`);
      if (!item) return def;

      const data = JSON.parse(item);
      if (data.expire === null || data.expire >= Date.now()) {
        return data.value;
      }
      this.remove(name); // 自动清理过期数据
    } catch {
      return def;
    }
    return def;
  },

  /**
   * 移除指定存储
   * @param {string} name
   * @returns {boolean}
   */
  remove(name: string): boolean {
    localStorage.removeItem(`${cacheNamespace}${name}`);
    return true;
  },

  /**
   * 清空当前命名空间下的所有存储
   */
  clear(): void {
    const keysToRemove = [];
    for (let i = 0; i < localStorage.length; i++) {
      const key = localStorage.key(i);
      if (key?.startsWith(cacheNamespace)) {
        keysToRemove.push(key);
      }
    }
    keysToRemove.forEach(key => localStorage.removeItem(key));
  }
};

export default LocalStorage;
