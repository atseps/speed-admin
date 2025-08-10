import Icon from "@/components/Icon";
export default () => {
  return (props: Recordable) => {
    if (!props.expandable) {
      return <span style={{ marginRight: "20px" }}></span>;
    }
    return (
      <a
        style={{ color: "#00000094", marginRight: "8px" }}
        onClick={(event: Event) => {
          props.onExpand(props.record, event);
        }}
      >
        <Icon
          type="caret-right-outlined"
          style={{
            transition: "transform .2s ease-in-out",
            fontSize: "12px",
            transform: props.expanded ? "rotate(90deg)" : "rotate(0deg)"
          }}
        />
      </a>
    );
  };
};
