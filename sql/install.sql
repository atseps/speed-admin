

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for speed_auth_access
-- ----------------------------
DROP TABLE IF EXISTS `speed_auth_access`;
CREATE TABLE `speed_auth_access` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `role_id` int(10) NOT NULL DEFAULT '0' COMMENT '角色id',
  `menu_id` int(10) NOT NULL DEFAULT '0' COMMENT '菜单id',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5716 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='权限菜单关联表';

-- ----------------------------
-- Records of speed_auth_access
-- ----------------------------
BEGIN;
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5167, 3, 2);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5168, 3, 1);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5169, 3, 28);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5170, 3, 14);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5171, 3, 29);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5172, 3, 30);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5173, 3, 77);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5174, 3, 114);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5175, 3, 106);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5176, 3, 107);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5177, 3, 38);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5178, 3, 80);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5179, 3, 108);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5180, 3, 40);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5182, 3, 45);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5183, 3, 21);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5184, 3, 46);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5185, 3, 47);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5186, 3, 81);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5187, 3, 53);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5188, 3, 50);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5189, 3, 54);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5190, 3, 82);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5191, 3, 110);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5192, 3, 36);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5193, 3, 32);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5194, 3, 78);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5195, 3, 122);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5196, 3, 13);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5197, 3, 113);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5198, 3, 105);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5199, 3, 16);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5200, 3, 20);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5201, 3, 121);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5579, 1, 107);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5580, 1, 106);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5581, 1, 119);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5582, 1, 114);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5583, 1, 120);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5584, 1, 116);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5585, 1, 130);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5586, 1, 38);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5587, 1, 108);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5588, 1, 80);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5590, 1, 40);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5592, 1, 81);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5593, 1, 45);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5594, 1, 46);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5595, 1, 47);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5596, 1, 110);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5597, 1, 82);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5598, 1, 53);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5599, 1, 54);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5600, 1, 78);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5601, 1, 36);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5602, 1, 105);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5603, 1, 113);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5604, 1, 32);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5605, 1, 16);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5606, 1, 20);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5607, 1, 21);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5608, 1, 50);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5609, 1, 156);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5611, 1, 14);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5612, 1, 77);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5613, 1, 28);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5614, 1, 29);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5615, 1, 30);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5616, 1, 13);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5620, 1, 174);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5630, 1, 184);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5632, 1, 186);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5653, 1, 207);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5659, 1, 213);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5660, 1, 214);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5661, 1, 215);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5662, 1, 216);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5664, 1, 218);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5665, 1, 219);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5666, 1, 220);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5670, 1, 224);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5682, 1, 234);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5684, 1, 236);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5686, 2, 107);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5687, 2, 119);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5688, 2, 114);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5689, 2, 80);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5690, 2, 40);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5692, 2, 81);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5693, 2, 45);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5694, 2, 46);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5695, 2, 47);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5696, 2, 36);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5697, 2, 78);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5698, 2, 32);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5699, 2, 21);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5700, 2, 106);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5701, 2, 105);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5702, 2, 113);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5703, 2, 20);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5704, 2, 156);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5705, 2, 13);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5706, 5, 156);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5707, 5, 105);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5708, 5, 113);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5709, 5, 106);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5710, 5, 119);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5711, 5, 107);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5712, 5, 120);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5713, 5, 114);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5714, 5, 116);
INSERT INTO `speed_auth_access` (`id`, `role_id`, `menu_id`) VALUES (5715, 5, 13);
COMMIT;

-- ----------------------------
-- Table structure for speed_department
-- ----------------------------
DROP TABLE IF EXISTS `speed_department`;
CREATE TABLE `speed_department` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '部门名称',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '父级部门',
  `sort` smallint(5) NOT NULL DEFAULT '8' COMMENT '部门排序',
  `leader_id` int(11) NOT NULL DEFAULT '0' COMMENT '部门负责人',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='部门表';

-- ----------------------------
-- Records of speed_department
-- ----------------------------
BEGIN;
INSERT INTO `speed_department` (`id`, `name`, `parent_id`, `sort`, `leader_id`) VALUES (1, '人事部', 4, 8, 9);
INSERT INTO `speed_department` (`id`, `name`, `parent_id`, `sort`, `leader_id`) VALUES (2, '财务部', 4, 8, 22);
INSERT INTO `speed_department` (`id`, `name`, `parent_id`, `sort`, `leader_id`) VALUES (3, '技术部', 4, 8, 11);
INSERT INTO `speed_department` (`id`, `name`, `parent_id`, `sort`, `leader_id`) VALUES (4, '总公司', 0, 8, 18);
INSERT INTO `speed_department` (`id`, `name`, `parent_id`, `sort`, `leader_id`) VALUES (5, '市场部', 4, 8, 1);
INSERT INTO `speed_department` (`id`, `name`, `parent_id`, `sort`, `leader_id`) VALUES (7, '上海分公司', 0, 8, 1);
INSERT INTO `speed_department` (`id`, `name`, `parent_id`, `sort`, `leader_id`) VALUES (8, '广东分公司', 0, 8, 1);
INSERT INTO `speed_department` (`id`, `name`, `parent_id`, `sort`, `leader_id`) VALUES (13, '北京分公司', 0, 8, 0);
INSERT INTO `speed_department` (`id`, `name`, `parent_id`, `sort`, `leader_id`) VALUES (14, '人事部', 7, 8, 0);
INSERT INTO `speed_department` (`id`, `name`, `parent_id`, `sort`, `leader_id`) VALUES (15, '研发部', 4, 8, 0);
INSERT INTO `speed_department` (`id`, `name`, `parent_id`, `sort`, `leader_id`) VALUES (16, '技术部', 7, 8, 0);
INSERT INTO `speed_department` (`id`, `name`, `parent_id`, `sort`, `leader_id`) VALUES (17, '开发部', 8, 8, 0);
INSERT INTO `speed_department` (`id`, `name`, `parent_id`, `sort`, `leader_id`) VALUES (18, '人事部', 8, 8, 22);
INSERT INTO `speed_department` (`id`, `name`, `parent_id`, `sort`, `leader_id`) VALUES (19, '人事部', 13, 8, 20);
COMMIT;

-- ----------------------------
-- Table structure for speed_dict
-- ----------------------------
DROP TABLE IF EXISTS `speed_dict`;
CREATE TABLE `speed_dict` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(30) NOT NULL DEFAULT '' COMMENT '字典类型',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '中文名称',
  `value` varchar(30) NOT NULL DEFAULT '' COMMENT '字典属性值',
  `sort` smallint(5) NOT NULL DEFAULT '8' COMMENT '排序',
  `note` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `color` varchar(10) NOT NULL DEFAULT '' COMMENT '字典组件颜色',
  `widget_type` varchar(10) NOT NULL DEFAULT '' COMMENT '字典组件类型',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除，0未删除，1已删除',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态，1启用，2禁用',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=562 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='字典表';

-- ----------------------------
-- Records of speed_dict
-- ----------------------------
BEGIN;
INSERT INTO `speed_dict` (`id`, `type`, `name`, `value`, `sort`, `note`, `color`, `widget_type`, `is_delete`, `status`) VALUES (75, 'dict_type', '字典类型', 'dict_type', 8, '', '', '', 0, 1);
INSERT INTO `speed_dict` (`id`, `type`, `name`, `value`, `sort`, `note`, `color`, `widget_type`, `is_delete`, `status`) VALUES (250, 'dict_type', '菜单类型', 'menu_type', 8, '', '', 'tag', 0, 1);
INSERT INTO `speed_dict` (`id`, `type`, `name`, `value`, `sort`, `note`, `color`, `widget_type`, `is_delete`, `status`) VALUES (251, 'menu_type', '目录', '0', 2, '', 'green', 'tag', 0, 1);
INSERT INTO `speed_dict` (`id`, `type`, `name`, `value`, `sort`, `note`, `color`, `widget_type`, `is_delete`, `status`) VALUES (252, 'menu_type', '菜单', '1', 1, '', 'blue', 'tag', 0, 1);
INSERT INTO `speed_dict` (`id`, `type`, `name`, `value`, `sort`, `note`, `color`, `widget_type`, `is_delete`, `status`) VALUES (253, 'menu_type', '权限', '2', 3, '', '', 'tag', 0, 1);
INSERT INTO `speed_dict` (`id`, `type`, `name`, `value`, `sort`, `note`, `color`, `widget_type`, `is_delete`, `status`) VALUES (429, 'gender', '未知', '0', 3, '', '', 'text', 0, 1);
INSERT INTO `speed_dict` (`id`, `type`, `name`, `value`, `sort`, `note`, `color`, `widget_type`, `is_delete`, `status`) VALUES (431, 'gender', '男', '1', 1, '', '', 'text', 0, 1);
INSERT INTO `speed_dict` (`id`, `type`, `name`, `value`, `sort`, `note`, `color`, `widget_type`, `is_delete`, `status`) VALUES (432, 'gender', '女', '2', 2, '', '', 'text', 0, 1);
INSERT INTO `speed_dict` (`id`, `type`, `name`, `value`, `sort`, `note`, `color`, `widget_type`, `is_delete`, `status`) VALUES (489, 'dict_type', '设备类型', 'device_type', 8, '', '', 'text', 0, 1);
INSERT INTO `speed_dict` (`id`, `type`, `name`, `value`, `sort`, `note`, `color`, `widget_type`, `is_delete`, `status`) VALUES (490, 'dict_type', '设备级别', 'device_level', 8, '', '', 'tag', 0, 1);
INSERT INTO `speed_dict` (`id`, `type`, `name`, `value`, `sort`, `note`, `color`, `widget_type`, `is_delete`, `status`) VALUES (495, 'dict_type', '岗位', 'job_post', 8, '', '', 'text', 0, 1);
INSERT INTO `speed_dict` (`id`, `type`, `name`, `value`, `sort`, `note`, `color`, `widget_type`, `is_delete`, `status`) VALUES (496, 'dict_type', '职称', 'job_title', 8, '', '', 'text', 0, 1);
INSERT INTO `speed_dict` (`id`, `type`, `name`, `value`, `sort`, `note`, `color`, `widget_type`, `is_delete`, `status`) VALUES (498, 'dict_type', '性别', 'gender', 8, '', '', 'text', 0, 1);
INSERT INTO `speed_dict` (`id`, `type`, `name`, `value`, `sort`, `note`, `color`, `widget_type`, `is_delete`, `status`) VALUES (526, 'device_type', '一般设备', '3', 2, '', '', 'text', 0, 1);
INSERT INTO `speed_dict` (`id`, `type`, `name`, `value`, `sort`, `note`, `color`, `widget_type`, `is_delete`, `status`) VALUES (528, 'device_type', '生产设备', '5', 3, '', '', 'text', 0, 1);
INSERT INTO `speed_dict` (`id`, `type`, `name`, `value`, `sort`, `note`, `color`, `widget_type`, `is_delete`, `status`) VALUES (529, 'device_type', '重要设备', '6', 1, '', '', 'text', 1, 1);
INSERT INTO `speed_dict` (`id`, `type`, `name`, `value`, `sort`, `note`, `color`, `widget_type`, `is_delete`, `status`) VALUES (531, 'device_level', '一级', '1', 1, '', '', 'tag', 0, 1);
INSERT INTO `speed_dict` (`id`, `type`, `name`, `value`, `sort`, `note`, `color`, `widget_type`, `is_delete`, `status`) VALUES (532, 'device_level', '二级', '2', 2, '', '', 'tag', 0, 1);
INSERT INTO `speed_dict` (`id`, `type`, `name`, `value`, `sort`, `note`, `color`, `widget_type`, `is_delete`, `status`) VALUES (535, 'device_level', '三级', '6', 5, '', '', 'tag', 0, 1);
INSERT INTO `speed_dict` (`id`, `type`, `name`, `value`, `sort`, `note`, `color`, `widget_type`, `is_delete`, `status`) VALUES (537, 'job_post', '技术岗', '2', 2, '', '', 'text', 0, 1);
INSERT INTO `speed_dict` (`id`, `type`, `name`, `value`, `sort`, `note`, `color`, `widget_type`, `is_delete`, `status`) VALUES (538, 'job_post', '普通岗', '3', 1, '', '', 'text', 0, 1);
INSERT INTO `speed_dict` (`id`, `type`, `name`, `value`, `sort`, `note`, `color`, `widget_type`, `is_delete`, `status`) VALUES (539, 'job_post', '管理岗', '4', 3, '', '', 'text', 0, 1);
INSERT INTO `speed_dict` (`id`, `type`, `name`, `value`, `sort`, `note`, `color`, `widget_type`, `is_delete`, `status`) VALUES (541, 'job_title', '初级', '1', 4, '', '', 'text', 0, 1);
INSERT INTO `speed_dict` (`id`, `type`, `name`, `value`, `sort`, `note`, `color`, `widget_type`, `is_delete`, `status`) VALUES (542, 'job_title', '中级', '2', 2, '', '', 'text', 0, 1);
INSERT INTO `speed_dict` (`id`, `type`, `name`, `value`, `sort`, `note`, `color`, `widget_type`, `is_delete`, `status`) VALUES (543, 'job_title', '副高级', '3', 3, '', '', 'text', 0, 1);
INSERT INTO `speed_dict` (`id`, `type`, `name`, `value`, `sort`, `note`, `color`, `widget_type`, `is_delete`, `status`) VALUES (544, 'job_title', '正高级', '4', 1, '', '', 'text', 0, 1);
COMMIT;

-- ----------------------------
-- Table structure for speed_file
-- ----------------------------
DROP TABLE IF EXISTS `speed_file`;
CREATE TABLE `speed_file` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL COMMENT '文件地址',
  `mime_type` varchar(100) NOT NULL COMMENT 'mime类型',
  `file_ext` varchar(30) NOT NULL COMMENT '文件扩展名',
  `file_size` int(11) NOT NULL DEFAULT '0' COMMENT '文件大小',
  `filename` varchar(255) NOT NULL COMMENT '文件名称',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '上传时间',
  `user_id` varchar(30) NOT NULL DEFAULT '' COMMENT '上传用户id',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='文件表';

-- ----------------------------
-- Table structure for speed_generate_field
-- ----------------------------
DROP TABLE IF EXISTS `speed_generate_field`;
CREATE TABLE `speed_generate_field` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `table_id` int(11) NOT NULL DEFAULT '0' COMMENT '表id',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '字段名称',
  `comment` varchar(300) NOT NULL DEFAULT '' COMMENT '字段描述',
  `type` varchar(100) NOT NULL DEFAULT '' COMMENT '字段类型',
  `is_required` tinyint(1) DEFAULT '0' COMMENT '是否必填 0-非必填 1-必填',
  `is_pk` tinyint(1) DEFAULT '0' COMMENT '是否为主键 0-不是 1-是',
  `is_insert` tinyint(1) DEFAULT '0' COMMENT '是否为插入字段 0-不是 1-是',
  `is_list` tinyint(1) DEFAULT '0' COMMENT '是否为列表字段 0-不是 1-是',
  `is_search` tinyint(1) DEFAULT '0' COMMENT '是否为查询字段 0-不是 1-是',
  `search_type` varchar(100) DEFAULT '=' COMMENT '查询类型',
  `show_type` varchar(100) DEFAULT 'input' COMMENT '显示类型',
  `dict_type` varchar(100) DEFAULT '' COMMENT '字典类型',
  `create_time` int(10) NOT NULL COMMENT '创建时间',
  `update_time` int(10) DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=262 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='代码生成表字段信息表';


-- ----------------------------
-- Table structure for speed_generate_table
-- ----------------------------
DROP TABLE IF EXISTS `speed_generate_table`;
CREATE TABLE `speed_generate_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `table_name` varchar(200) NOT NULL DEFAULT '' COMMENT '表名称',
  `table_comment` varchar(300) NOT NULL DEFAULT '' COMMENT '表描述',
  `generate_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '生成方式  0-压缩包下载 1-生成到模块',
  `module_name` varchar(100) DEFAULT '' COMMENT '模块名',
  `class_dir` varchar(100) DEFAULT '' COMMENT '类目录名',
  `create__userid` int(11) DEFAULT '0' COMMENT '创建人id',
  `menu_name` varchar(30) DEFAULT NULL COMMENT '菜单名称',
  `menu_type` tinyint(1) DEFAULT NULL COMMENT '生成菜单类型。0自动构建，1手动添加',
  `menu_pid` int(11) DEFAULT NULL COMMENT '上级菜单id',
  `delete_type` tinyint(1) NOT NULL COMMENT '删除类型，0物理删除，1软删除',
  `create_time` int(10) NOT NULL COMMENT '创建时间',
  `update_time` int(10) DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='代码生成表信息表';


-- ----------------------------
-- Table structure for speed_login_log
-- ----------------------------
DROP TABLE IF EXISTS `speed_login_log`;
CREATE TABLE `speed_login_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `account` varchar(30) NOT NULL DEFAULT '' COMMENT '登录账号',
  `login_ip` varchar(20) NOT NULL DEFAULT '' COMMENT '登录ip',
  `browser` varchar(20) NOT NULL DEFAULT '' COMMENT '浏览器',
  `os` varchar(20) NOT NULL DEFAULT '' COMMENT '操作系统',
  `login_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '登录时间',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='登录日志表';

-- ----------------------------
-- Table structure for speed_menu
-- ----------------------------
DROP TABLE IF EXISTS `speed_menu`;
CREATE TABLE `speed_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父id',
  `path` varchar(100) NOT NULL DEFAULT '' COMMENT '路由路径',
  `component` varchar(255) NOT NULL DEFAULT '' COMMENT '路由组件',
  `hidden` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否隐藏',
  `title` varchar(30) NOT NULL DEFAULT '' COMMENT '菜单名称',
  `icon` varchar(100) NOT NULL DEFAULT '' COMMENT '菜单图标',
  `rules` varchar(100) NOT NULL DEFAULT '' COMMENT '权限节点',
  `sort` smallint(5) NOT NULL DEFAULT '1' COMMENT '排序',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 目录，1菜单，2权限',
  `hide_children` tinyint(1) NOT NULL DEFAULT '0' COMMENT '隐藏子菜单，并且强制渲染为菜单项',
  `active_key` varchar(255) NOT NULL DEFAULT '' COMMENT '菜单高亮key',
  `open_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '打开方式 0组件，1内链，2外链',
  `link_url` varchar(500) NOT NULL DEFAULT '' COMMENT '内链地址',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=238 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='菜单表';

-- ----------------------------
-- Records of speed_menu
-- ----------------------------
BEGIN;
INSERT INTO `speed_menu` (`id`, `pid`, `path`, `component`, `hidden`, `title`, `icon`, `rules`, `sort`, `type`, `hide_children`, `active_key`, `open_type`, `link_url`) VALUES (13, 0, 'system', 'Layout', 0, '系统管理', 'setting-outlined', '', 5, 0, 0, '', 0, '');
INSERT INTO `speed_menu` (`id`, `pid`, `path`, `component`, `hidden`, `title`, `icon`, `rules`, `sort`, `type`, `hide_children`, `active_key`, `open_type`, `link_url`) VALUES (14, 13, 'menu', 'system/menu/index', 0, '菜单管理', 'menu-outlined', '', 1, 1, 0, '', 0, '');
INSERT INTO `speed_menu` (`id`, `pid`, `path`, `component`, `hidden`, `title`, `icon`, `rules`, `sort`, `type`, `hide_children`, `active_key`, `open_type`, `link_url`) VALUES (16, 13, 'department', 'system/department/index', 0, '部门管理', 'apartment-outlined', '', 2, 1, 0, '', 0, '');
INSERT INTO `speed_menu` (`id`, `pid`, `path`, `component`, `hidden`, `title`, `icon`, `rules`, `sort`, `type`, `hide_children`, `active_key`, `open_type`, `link_url`) VALUES (20, 13, 'user', 'system/user/index', 0, '用户管理', 'team-outlined', '', 3, 1, 0, '', 0, '');
INSERT INTO `speed_menu` (`id`, `pid`, `path`, `component`, `hidden`, `title`, `icon`, `rules`, `sort`, `type`, `hide_children`, `active_key`, `open_type`, `link_url`) VALUES (21, 13, 'role', 'system/role/index', 0, '角色管理', 'user-outlined', '', 4, 1, 0, '', 0, '');
INSERT INTO `speed_menu` (`id`, `pid`, `path`, `component`, `hidden`, `title`, `icon`, `rules`, `sort`, `type`, `hide_children`, `active_key`, `open_type`, `link_url`) VALUES (28, 14, '', '', 0, '添加菜单', '', 'system:menu:save', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` (`id`, `pid`, `path`, `component`, `hidden`, `title`, `icon`, `rules`, `sort`, `type`, `hide_children`, `active_key`, `open_type`, `link_url`) VALUES (29, 14, '', '', 0, '修改菜单', '', 'system:menu:update', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` (`id`, `pid`, `path`, `component`, `hidden`, `title`, `icon`, `rules`, `sort`, `type`, `hide_children`, `active_key`, `open_type`, `link_url`) VALUES (30, 14, '', '', 0, '删除菜单', '', 'system:menu:delete', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` (`id`, `pid`, `path`, `component`, `hidden`, `title`, `icon`, `rules`, `sort`, `type`, `hide_children`, `active_key`, `open_type`, `link_url`) VALUES (32, 13, 'auth/:id', 'system/role/auth', 1, '权限设置', 'insurance-outlined', '', 10, 1, 0, 'system/role', 0, '');
INSERT INTO `speed_menu` (`id`, `pid`, `path`, `component`, `hidden`, `title`, `icon`, `rules`, `sort`, `type`, `hide_children`, `active_key`, `open_type`, `link_url`) VALUES (36, 32, '', '', 1, '保存权限', '', 'system:authAccess:save', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` (`id`, `pid`, `path`, `component`, `hidden`, `title`, `icon`, `rules`, `sort`, `type`, `hide_children`, `active_key`, `open_type`, `link_url`) VALUES (38, 16, '', '', 0, '删除部门', '', 'system:department:delete', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` (`id`, `pid`, `path`, `component`, `hidden`, `title`, `icon`, `rules`, `sort`, `type`, `hide_children`, `active_key`, `open_type`, `link_url`) VALUES (40, 20, '', '', 0, '更新用户', '', 'system:user:update', 2, 2, 0, '', 0, '');
INSERT INTO `speed_menu` (`id`, `pid`, `path`, `component`, `hidden`, `title`, `icon`, `rules`, `sort`, `type`, `hide_children`, `active_key`, `open_type`, `link_url`) VALUES (45, 21, '', '', 0, '添加角色', '', 'system:role:save', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` (`id`, `pid`, `path`, `component`, `hidden`, `title`, `icon`, `rules`, `sort`, `type`, `hide_children`, `active_key`, `open_type`, `link_url`) VALUES (46, 21, '', '', 0, '修改角色', '', 'system:role:update', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` (`id`, `pid`, `path`, `component`, `hidden`, `title`, `icon`, `rules`, `sort`, `type`, `hide_children`, `active_key`, `open_type`, `link_url`) VALUES (47, 21, '', '', 0, '删除角色', '', 'system:role:delete', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` (`id`, `pid`, `path`, `component`, `hidden`, `title`, `icon`, `rules`, `sort`, `type`, `hide_children`, `active_key`, `open_type`, `link_url`) VALUES (50, 13, 'dict', 'system/dict/index', 0, '字典管理', 'deployment-unit-outlined', '', 5, 1, 0, '', 0, '');
INSERT INTO `speed_menu` (`id`, `pid`, `path`, `component`, `hidden`, `title`, `icon`, `rules`, `sort`, `type`, `hide_children`, `active_key`, `open_type`, `link_url`) VALUES (53, 50, '', '', 0, '修改字典', '', 'system:dict:update', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` (`id`, `pid`, `path`, `component`, `hidden`, `title`, `icon`, `rules`, `sort`, `type`, `hide_children`, `active_key`, `open_type`, `link_url`) VALUES (54, 50, '', '', 0, '删除字典', '', 'system:dict:delete', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` (`id`, `pid`, `path`, `component`, `hidden`, `title`, `icon`, `rules`, `sort`, `type`, `hide_children`, `active_key`, `open_type`, `link_url`) VALUES (77, 14, '', '', 0, '查看列表', '', 'system:menu:index', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` (`id`, `pid`, `path`, `component`, `hidden`, `title`, `icon`, `rules`, `sort`, `type`, `hide_children`, `active_key`, `open_type`, `link_url`) VALUES (78, 32, '', '', 1, '查看权限', '', 'system:authAccess:index', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` (`id`, `pid`, `path`, `component`, `hidden`, `title`, `icon`, `rules`, `sort`, `type`, `hide_children`, `active_key`, `open_type`, `link_url`) VALUES (80, 20, '', '', 0, '查看列表', '', 'system:user:index', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` (`id`, `pid`, `path`, `component`, `hidden`, `title`, `icon`, `rules`, `sort`, `type`, `hide_children`, `active_key`, `open_type`, `link_url`) VALUES (81, 21, '', '', 0, '查看列表', '', 'system:role:index', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` (`id`, `pid`, `path`, `component`, `hidden`, `title`, `icon`, `rules`, `sort`, `type`, `hide_children`, `active_key`, `open_type`, `link_url`) VALUES (82, 50, '', '', 0, '查看列表', '', 'system:dict:index', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` (`id`, `pid`, `path`, `component`, `hidden`, `title`, `icon`, `rules`, `sort`, `type`, `hide_children`, `active_key`, `open_type`, `link_url`) VALUES (105, 156, 'operate', 'system/logs/operate-log', 0, '操作日志', 'profile-outlined', '', 1, 1, 0, '', 0, '');
INSERT INTO `speed_menu` (`id`, `pid`, `path`, `component`, `hidden`, `title`, `icon`, `rules`, `sort`, `type`, `hide_children`, `active_key`, `open_type`, `link_url`) VALUES (106, 105, '', '', 0, '查看列表', '', 'system:operateLog:index', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` (`id`, `pid`, `path`, `component`, `hidden`, `title`, `icon`, `rules`, `sort`, `type`, `hide_children`, `active_key`, `open_type`, `link_url`) VALUES (107, 105, '', '', 0, '清空日志', '', 'system:operateLog:clear', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` (`id`, `pid`, `path`, `component`, `hidden`, `title`, `icon`, `rules`, `sort`, `type`, `hide_children`, `active_key`, `open_type`, `link_url`) VALUES (108, 20, '', '', 0, '修改状态', '', 'system:user:changeStatus', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` (`id`, `pid`, `path`, `component`, `hidden`, `title`, `icon`, `rules`, `sort`, `type`, `hide_children`, `active_key`, `open_type`, `link_url`) VALUES (110, 50, '', '', 0, '添加字典', '', 'system:dict:save', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` (`id`, `pid`, `path`, `component`, `hidden`, `title`, `icon`, `rules`, `sort`, `type`, `hide_children`, `active_key`, `open_type`, `link_url`) VALUES (113, 156, 'login', 'system/logs/login-log', 0, '登录日志', 'diff-outlined', '', 1, 1, 0, '', 0, '');
INSERT INTO `speed_menu` (`id`, `pid`, `path`, `component`, `hidden`, `title`, `icon`, `rules`, `sort`, `type`, `hide_children`, `active_key`, `open_type`, `link_url`) VALUES (114, 113, '', '', 0, '查看列表', '', 'system:loginLog:index', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` (`id`, `pid`, `path`, `component`, `hidden`, `title`, `icon`, `rules`, `sort`, `type`, `hide_children`, `active_key`, `open_type`, `link_url`) VALUES (116, 113, '', '', 0, '清空日志', '', 'system:loginLog:clear', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` (`id`, `pid`, `path`, `component`, `hidden`, `title`, `icon`, `rules`, `sort`, `type`, `hide_children`, `active_key`, `open_type`, `link_url`) VALUES (119, 105, '', '', 0, '删除日志', '', 'system:operateLog:delete', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` (`id`, `pid`, `path`, `component`, `hidden`, `title`, `icon`, `rules`, `sort`, `type`, `hide_children`, `active_key`, `open_type`, `link_url`) VALUES (120, 113, '', '', 0, '删除日志', '', 'system:loginLog:delete', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` (`id`, `pid`, `path`, `component`, `hidden`, `title`, `icon`, `rules`, `sort`, `type`, `hide_children`, `active_key`, `open_type`, `link_url`) VALUES (130, 16, '', '', 0, '添加部门', '', 'system:department:save', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` (`id`, `pid`, `path`, `component`, `hidden`, `title`, `icon`, `rules`, `sort`, `type`, `hide_children`, `active_key`, `open_type`, `link_url`) VALUES (156, 13, 'log', 'RouteView', 0, '日志管理', 'file-text-outlined', '', 1, 0, 0, '', 0, '');
INSERT INTO `speed_menu` (`id`, `pid`, `path`, `component`, `hidden`, `title`, `icon`, `rules`, `sort`, `type`, `hide_children`, `active_key`, `open_type`, `link_url`) VALUES (167, 165, 'filetype', 'system/login_log/index', 0, '附件类型', '', '', 1, 1, 0, '', 0, '');
INSERT INTO `speed_menu` (`id`, `pid`, `path`, `component`, `hidden`, `title`, `icon`, `rules`, `sort`, `type`, `hide_children`, `active_key`, `open_type`, `link_url`) VALUES (174, 213, 'https://www.antdv.com/docs/vue/introduce-cn/', 'Layout', 0, '外链', 'link-outlined', '', 1, 1, 0, '', 2, '');
INSERT INTO `speed_menu` (`id`, `pid`, `path`, `component`, `hidden`, `title`, `icon`, `rules`, `sort`, `type`, `hide_children`, `active_key`, `open_type`, `link_url`) VALUES (184, 13, 'generator', 'system/generator/index', 0, '代码生成器', 'snippets-outlined', '', 1, 1, 1, '', 0, '');
INSERT INTO `speed_menu` (`id`, `pid`, `path`, `component`, `hidden`, `title`, `icon`, `rules`, `sort`, `type`, `hide_children`, `active_key`, `open_type`, `link_url`) VALUES (186, 13, 'generator/:id', 'system/generator/edit', 1, '基础配置', '', '', 1, 1, 0, 'system/generator', 0, '');
INSERT INTO `speed_menu` (`id`, `pid`, `path`, `component`, `hidden`, `title`, `icon`, `rules`, `sort`, `type`, `hide_children`, `active_key`, `open_type`, `link_url`) VALUES (207, 213, 'link', 'Iframe', 0, '内链', 'paper-clip-outlined', '', 1, 1, 0, '', 1, 'https://www.antdv.com/');
INSERT INTO `speed_menu` (`id`, `pid`, `path`, `component`, `hidden`, `title`, `icon`, `rules`, `sort`, `type`, `hide_children`, `active_key`, `open_type`, `link_url`) VALUES (213, 214, 'link', 'Layout', 0, '链接', 'link-outlined', '', 8, 0, 0, '', 0, '');
INSERT INTO `speed_menu` (`id`, `pid`, `path`, `component`, `hidden`, `title`, `icon`, `rules`, `sort`, `type`, `hide_children`, `active_key`, `open_type`, `link_url`) VALUES (214, 0, 'widgets', 'Layout', 0, '组件示例', 'code-outlined', '', 6, 0, 0, '', 0, '');
INSERT INTO `speed_menu` (`id`, `pid`, `path`, `component`, `hidden`, `title`, `icon`, `rules`, `sort`, `type`, `hide_children`, `active_key`, `open_type`, `link_url`) VALUES (215, 214, 'table', 'widgets/table', 0, '表格', 'table-outlined', '', 1, 1, 0, '', 0, '');
INSERT INTO `speed_menu` (`id`, `pid`, `path`, `component`, `hidden`, `title`, `icon`, `rules`, `sort`, `type`, `hide_children`, `active_key`, `open_type`, `link_url`) VALUES (216, 214, 'modal', 'widgets/modal/index', 0, '弹窗', 'project-outlined', '', 2, 1, 0, '', 0, '');
INSERT INTO `speed_menu` (`id`, `pid`, `path`, `component`, `hidden`, `title`, `icon`, `rules`, `sort`, `type`, `hide_children`, `active_key`, `open_type`, `link_url`) VALUES (218, 214, 'upload', 'widgets/upload', 0, '上传', 'upload-outlined', '', 4, 1, 0, '', 0, '');
INSERT INTO `speed_menu` (`id`, `pid`, `path`, `component`, `hidden`, `title`, `icon`, `rules`, `sort`, `type`, `hide_children`, `active_key`, `open_type`, `link_url`) VALUES (219, 214, 'editor', 'widgets/editor', 0, '富文本编辑器', 'snippets-outlined', '', 5, 1, 0, '', 0, '');
INSERT INTO `speed_menu` (`id`, `pid`, `path`, `component`, `hidden`, `title`, `icon`, `rules`, `sort`, `type`, `hide_children`, `active_key`, `open_type`, `link_url`) VALUES (220, 214, 'table-select', 'widgets/table-select', 0, '表格选择器', '', '', 6, 1, 0, '', 0, '');
INSERT INTO `speed_menu` (`id`, `pid`, `path`, `component`, `hidden`, `title`, `icon`, `rules`, `sort`, `type`, `hide_children`, `active_key`, `open_type`, `link_url`) VALUES (224, 214, 'note-editor', 'widgets/note-editor', 0, '文本域编辑器', '', '', 7, 1, 0, '', 0, '');
INSERT INTO `speed_menu` (`id`, `pid`, `path`, `component`, `hidden`, `title`, `icon`, `rules`, `sort`, `type`, `hide_children`, `active_key`, `open_type`, `link_url`) VALUES (234, 214, 'drawer', 'widgets/drawer/index', 0, '抽屉', 'layout-outlined', '', 3, 1, 0, '', 0, '');
INSERT INTO `speed_menu` (`id`, `pid`, `path`, `component`, `hidden`, `title`, `icon`, `rules`, `sort`, `type`, `hide_children`, `active_key`, `open_type`, `link_url`) VALUES (236, 20, '', '', 0, '重置密码', '', 'system:user:resetPassword', 1, 2, 0, '', 0, '');
COMMIT;

-- ----------------------------
-- Table structure for speed_operate_log
-- ----------------------------
DROP TABLE IF EXISTS `speed_operate_log`;
CREATE TABLE `speed_operate_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `module` varchar(50) NOT NULL DEFAULT '' COMMENT '模块名称',
  `operate` varchar(20) NOT NULL DEFAULT '' COMMENT '操作模块',
  `route` varchar(100) NOT NULL DEFAULT '' COMMENT '路由',
  `params` varchar(1000) NOT NULL DEFAULT '' COMMENT '参数',
  `ip` varchar(20) NOT NULL DEFAULT '' COMMENT 'ip',
  `user_id` varchar(30) NOT NULL DEFAULT '' COMMENT '操作用户',
  `method` varchar(255) NOT NULL DEFAULT '' COMMENT '请求方法',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '登录时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='操作日志';

-- ----------------------------
-- Records of speed_operate_log
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for speed_role
-- ----------------------------
DROP TABLE IF EXISTS `speed_role`;
CREATE TABLE `speed_role` (
  `id` mediumint(11) NOT NULL AUTO_INCREMENT,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '名称',
  `note` varchar(100) NOT NULL DEFAULT '' COMMENT '备注',
  `role_key` varchar(30) NOT NULL DEFAULT '' COMMENT '权限标识',
  `data_range` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 全部数据 2 自定义数据 3 仅本人数据 4 部门数据 5 部门及以下数据',
  `delete_time` int(10) NOT NULL DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `role_key` (`role_key`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='角色表';

-- ----------------------------
-- Records of speed_role
-- ----------------------------
BEGIN;
INSERT INTO `speed_role` (`id`, `status`, `name`, `note`, `role_key`, `data_range`, `delete_time`) VALUES (1, 1, '超级管理员', '内置角色，不可维护', 'super_admin', 1, 0);
INSERT INTO `speed_role` (`id`, `status`, `name`, `note`, `role_key`, `data_range`, `delete_time`) VALUES (2, 1, '管理员', '业务权限', 'admin', 1, 0);
INSERT INTO `speed_role` (`id`, `status`, `name`, `note`, `role_key`, `data_range`, `delete_time`) VALUES (3, 1, '总经理', '', 'general', 1, 0);
INSERT INTO `speed_role` (`id`, `status`, `name`, `note`, `role_key`, `data_range`, `delete_time`) VALUES (5, 1, '部门经理', '', 'manager', 1, 0);
COMMIT;

-- ----------------------------
-- Table structure for speed_role_department
-- ----------------------------
DROP TABLE IF EXISTS `speed_role_department`;
CREATE TABLE `speed_role_department` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `dept_id` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC COMMENT='部门角色关联表';

-- ----------------------------
-- Records of speed_role_department
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for speed_user
-- ----------------------------
DROP TABLE IF EXISTS `speed_user`;
CREATE TABLE `speed_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL COMMENT '用户名',
  `password` varchar(255) NOT NULL COMMENT '密码',
  `realname` varchar(10) NOT NULL DEFAULT '' COMMENT '姓名',
  `pinyin` varchar(10) NOT NULL DEFAULT '' COMMENT '拼音',
  `phone` varchar(15) NOT NULL DEFAULT '' COMMENT '手机',
  `email` varchar(50) NOT NULL DEFAULT '' COMMENT '邮箱',
  `dept_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '部门id',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '1启用，2禁用',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '头像',
  `last_login_time` int(10) NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `last_login_ip` varchar(20) NOT NULL DEFAULT '' COMMENT '最后登录的IP',
  `is_admin` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否系统管理员，0否，1是',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `username` (`username`) USING BTREE COMMENT '用户名唯一'
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='用户表';

-- ----------------------------
-- Records of speed_user
-- ----------------------------
BEGIN;
INSERT INTO `speed_user` (`id`, `username`, `password`, `realname`, `pinyin`, `phone`, `email`, `dept_id`, `status`, `create_time`, `avatar`, `last_login_time`, `last_login_ip`, `is_admin`) VALUES (1, 'admin', '$2y$10$mH5jYh4WxS8HjqTN9Q1tu.SUyMMezQthe6.LDkZjPu7sABJTyprn6', 'super', '', '18899996666', '', 4, 1, 1748939339, '', 1750173840, '', 1);
INSERT INTO `speed_user` (`id`, `username`, `password`, `realname`, `pinyin`, `phone`, `email`, `dept_id`, `status`, `create_time`, `avatar`, `last_login_time`, `last_login_ip`, `is_admin`) VALUES (2, 'demo', '$2y$10$mH5jYh4WxS8HjqTN9Q1tu.SUyMMezQthe6.LDkZjPu7sABJTyprn6', 'demo', '', '', '', 4, 1, 1748939339, '', 1750173831, '', 0);
INSERT INTO `speed_user` (`id`, `username`, `password`, `realname`, `pinyin`, `phone`, `email`, `dept_id`, `status`, `create_time`, `avatar`, `last_login_time`, `last_login_ip`, `is_admin`) VALUES (9, 'test', '$2y$10$KZnWAJFpo/d4XXPLgzSuDOBv2Y7SQLcKwYbFZKQggCnawytQ4DSLK', '测试', 'cs', '', '', 15, 1, 1615864955, 'https://zos.alipayobjects.com/rmsportal/ODTLcjxAfvqbxHnVXCYX.png', 0, '', 0);
INSERT INTO `speed_user` (`id`, `username`, `password`, `realname`, `pinyin`, `phone`, `email`, `dept_id`, `status`, `create_time`, `avatar`, `last_login_time`, `last_login_ip`, `is_admin`) VALUES (10, 'test2', '$2y$10$89LxsExrBliqqCW/PrOBgOeubhHJUI5tmQkZJ2dBht8ltF/puI6a.', '测试2', 'cs', '', '', 16, 1, 1615865516, '', 0, '', 0);
INSERT INTO `speed_user` (`id`, `username`, `password`, `realname`, `pinyin`, `phone`, `email`, `dept_id`, `status`, `create_time`, `avatar`, `last_login_time`, `last_login_ip`, `is_admin`) VALUES (11, 'yunweu', '$2y$10$Zvbnq8TC7TavN/t/CriXC.g0d89XK4UaBiOYHOSctlwmjD2HLmdYu', '运维管理', 'ywgl', '', '', 17, 1, 1616059337, 'https://gw.alipayobjects.com/zos/antfincdn/XAosXuNZyF/BiazfanxmamNRoxxVxka.png', 0, '', 0);
INSERT INTO `speed_user` (`id`, `username`, `password`, `realname`, `pinyin`, `phone`, `email`, `dept_id`, `status`, `create_time`, `avatar`, `last_login_time`, `last_login_ip`, `is_admin`) VALUES (19, 'www', '$2y$10$SFlICzdZZMZy/2VxS4tMIODzQxcoYV40TkCbS48eTYwnvZEJJAd8u', '王五', 'ww', '', '', 7, 1, 1617182546, '', 0, '', 0);
INSERT INTO `speed_user` (`id`, `username`, `password`, `realname`, `pinyin`, `phone`, `email`, `dept_id`, `status`, `create_time`, `avatar`, `last_login_time`, `last_login_ip`, `is_admin`) VALUES (20, 'yang', '$2y$10$g1oVhKY1SXZmAl20SDo0xOAivYFaB4GbtDzzEjw..AcC0iNEQ/Yp2', '杨六', 'yl', '', '', 4, 1, 1645673069, '', 0, '', 0);
INSERT INTO `speed_user` (`id`, `username`, `password`, `realname`, `pinyin`, `phone`, `email`, `dept_id`, `status`, `create_time`, `avatar`, `last_login_time`, `last_login_ip`, `is_admin`) VALUES (21, 'lishi', '$2y$10$e9cdcQEFEb7k9sdixmwXnuO/GD1bRN8C1xw6b1nfbPjuXxEfvP2FS', '李四', 'ls', '', '', 4, 1, 1645673088, '', 0, '', 0);
INSERT INTO `speed_user` (`id`, `username`, `password`, `realname`, `pinyin`, `phone`, `email`, `dept_id`, `status`, `create_time`, `avatar`, `last_login_time`, `last_login_ip`, `is_admin`) VALUES (22, 'zhangs', '$2y$10$cl6yeliFDXHnfQ7accy4fOO3l7Jelcao9k3IdAka2hewcoZwiKAb2', '张三', 'zs', '', '', 8, 1, 1651917072, '', 0, '', 0);
INSERT INTO `speed_user` (`id`, `username`, `password`, `realname`, `pinyin`, `phone`, `email`, `dept_id`, `status`, `create_time`, `avatar`, `last_login_time`, `last_login_ip`, `is_admin`) VALUES (23, 'wang', '$2y$10$ec8Ln1cnCH0wOGkeWJnJm.68.eue7d/0c2oGWQ25yynxvkOHL6SLK', '王安', 'wa', '', '', 7, 1, 1748939339, '', 0, '', 0);
COMMIT;

-- ----------------------------
-- Table structure for speed_user_role
-- ----------------------------
DROP TABLE IF EXISTS `speed_user_role`;
CREATE TABLE `speed_user_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=160 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='用户角色关联表';

-- ----------------------------
-- Records of speed_user_role
-- ----------------------------
BEGIN;
INSERT INTO `speed_user_role` (`id`, `user_id`, `role_id`) VALUES (27, 14, 3);
INSERT INTO `speed_user_role` (`id`, `user_id`, `role_id`) VALUES (28, 13, 3);
INSERT INTO `speed_user_role` (`id`, `user_id`, `role_id`) VALUES (46, 12, 3);
INSERT INTO `speed_user_role` (`id`, `user_id`, `role_id`) VALUES (83, 15, 3);
INSERT INTO `speed_user_role` (`id`, `user_id`, `role_id`) VALUES (108, 1, 1);
INSERT INTO `speed_user_role` (`id`, `user_id`, `role_id`) VALUES (110, 2, 2);
INSERT INTO `speed_user_role` (`id`, `user_id`, `role_id`) VALUES (126, 19, 2);
INSERT INTO `speed_user_role` (`id`, `user_id`, `role_id`) VALUES (130, 24, 1);
INSERT INTO `speed_user_role` (`id`, `user_id`, `role_id`) VALUES (131, 24, 3);
INSERT INTO `speed_user_role` (`id`, `user_id`, `role_id`) VALUES (132, 18, 1);
INSERT INTO `speed_user_role` (`id`, `user_id`, `role_id`) VALUES (133, 18, 2);
INSERT INTO `speed_user_role` (`id`, `user_id`, `role_id`) VALUES (134, 18, 3);
INSERT INTO `speed_user_role` (`id`, `user_id`, `role_id`) VALUES (137, 22, 3);
INSERT INTO `speed_user_role` (`id`, `user_id`, `role_id`) VALUES (138, 23, 2);
INSERT INTO `speed_user_role` (`id`, `user_id`, `role_id`) VALUES (140, 10, 3);
INSERT INTO `speed_user_role` (`id`, `user_id`, `role_id`) VALUES (153, 11, 2);
INSERT INTO `speed_user_role` (`id`, `user_id`, `role_id`) VALUES (154, 11, 5);
INSERT INTO `speed_user_role` (`id`, `user_id`, `role_id`) VALUES (155, 9, 2);
INSERT INTO `speed_user_role` (`id`, `user_id`, `role_id`) VALUES (156, 20, 2);
INSERT INTO `speed_user_role` (`id`, `user_id`, `role_id`) VALUES (157, 20, 3);
INSERT INTO `speed_user_role` (`id`, `user_id`, `role_id`) VALUES (158, 21, 2);
INSERT INTO `speed_user_role` (`id`, `user_id`, `role_id`) VALUES (159, 21, 3);
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
