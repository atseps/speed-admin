const response = (data: any) => {
  const res = {
    data,
    status: "ok",
    msg: "请求成功",
    code: 20000
  };
  return Promise.resolve(res);
};

const natural = (m: any, n: any) => {
  return Math.ceil(Math.random() * (n - m + 1) + m - 1);
};

export const queryPublicOpinionAnalysis = (params?: any) => {
  const { quota = "visitors" } = params;
  if (["visitors", "comment"].includes(quota)) {
    const year = new Date().getFullYear();
    const getLineData = (name: number) => {
      return new Array(12).fill(0).map((_item, index) => ({
        x: `${index + 1}月`,
        y: natural(0, 100),
        name: String(name)
      }));
    };
    return response({
      count: 5670,
      growth: 206.32,
      chartData: [...getLineData(year), ...getLineData(year - 1)]
    });
  }

  if (["published"].includes(quota)) {
    const year = new Date().getFullYear();
    const getLineData = (name: number) => {
      return new Array(12).fill(0).map((_item, index) => ({
        x: `${index + 1}日`,
        y: natural(20, 100),
        name: String(name)
      }));
    };
    return response({
      count: 5670,
      growth: 206,
      chartData: [...getLineData(year)]
    });
  }

  return response({
    count: 5670,
    growth: 206.32,
    chartData: [
      { name: "文本类", value: 25, itemStyle: { color: "#8D4EDA" } },
      { name: "图文类", value: 35, itemStyle: { color: "#165DFF" } },
      { name: "视频类", value: 40, itemStyle: { color: "#00B2FF" } }
    ]
  });
};

export const queryContentPeriodAnalysis = () => {
  const getLineData = (name: string) => {
    return {
      name,
      value: new Array(12).fill(0).map(() => natural(30, 90))
    };
  };
  return response({
    xAxis: new Array(12).fill(0).map((_item, index) => `${index * 2}:00`),
    data: [getLineData("纯文本"), getLineData("图文类"), getLineData("视频类")]
  });
};

export const queryContentPublish = () => {
  const generateLineData = (name: string) => {
    const result = {
      name,
      x: [] as string[],
      y: [] as number[]
    };
    new Array(12).fill(0).forEach((_item, index) => {
      result.x.push(`${index * 2}:00`);
      result.y.push(natural(1000, 3000));
    });
    return result;
  };
  return response([
    generateLineData("纯文本"),
    generateLineData("图文类"),
    generateLineData("视频类")
  ]);
};
