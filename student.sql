/*
Navicat MySQL Data Transfer

Source Server         : right
Source Server Version : 50553
Source Host           : 10.1.1.100:3306
Source Database       : student

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2018-11-27 13:51:42
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for h2w_ad
-- ----------------------------
DROP TABLE IF EXISTS `h2w_ad`;
CREATE TABLE `h2w_ad` (
  `ad_id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '广告id',
  `ad_name` varchar(255) NOT NULL COMMENT '广告名称',
  `ad_content` text COMMENT '广告内容',
  `status` int(2) NOT NULL DEFAULT '1' COMMENT '状态，1显示，0不显示',
  PRIMARY KEY (`ad_id`),
  KEY `ad_name` (`ad_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of h2w_ad
-- ----------------------------

-- ----------------------------
-- Table structure for h2w_asset
-- ----------------------------
DROP TABLE IF EXISTS `h2w_asset`;
CREATE TABLE `h2w_asset` (
  `aid` bigint(20) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户 id',
  `key` varchar(50) NOT NULL COMMENT '资源 key',
  `filename` varchar(50) DEFAULT NULL COMMENT '文件名',
  `filesize` int(11) DEFAULT NULL COMMENT '文件大小,单位Byte',
  `filepath` varchar(200) NOT NULL COMMENT '文件路径，相对于 upload 目录，可以为 url',
  `uploadtime` int(11) NOT NULL COMMENT '上传时间',
  `status` int(2) NOT NULL DEFAULT '1' COMMENT '状态，1：可用，0：删除，不可用',
  `meta` text COMMENT '其它详细信息，JSON格式',
  `suffix` varchar(50) DEFAULT NULL COMMENT '文件后缀名，不包括点',
  `download_times` int(11) NOT NULL DEFAULT '0' COMMENT '下载次数',
  PRIMARY KEY (`aid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='资源表';

-- ----------------------------
-- Records of h2w_asset
-- ----------------------------

-- ----------------------------
-- Table structure for h2w_authentication
-- ----------------------------
DROP TABLE IF EXISTS `h2w_authentication`;
CREATE TABLE `h2w_authentication` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT '0' COMMENT '学校名称',
  `school_id` int(11) DEFAULT '0' COMMENT '认证学校',
  `student_img` varchar(255) DEFAULT '' COMMENT '学生证图片',
  `status` tinyint(2) DEFAULT '0' COMMENT '启用状态 : 0 认证中 1 通过  2未通过',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='学校';

-- ----------------------------
-- Records of h2w_authentication
-- ----------------------------
INSERT INTO `h2w_authentication` VALUES ('1', '1', '1', 'admin/20181127/5bfc90d037910.jpg', '1', '0');

-- ----------------------------
-- Table structure for h2w_auth_access
-- ----------------------------
DROP TABLE IF EXISTS `h2w_auth_access`;
CREATE TABLE `h2w_auth_access` (
  `role_id` mediumint(8) unsigned NOT NULL COMMENT '角色',
  `rule_name` varchar(255) NOT NULL COMMENT '规则唯一英文标识,全小写',
  `type` varchar(30) DEFAULT NULL COMMENT '权限规则分类，请加应用前缀,如admin_',
  KEY `role_id` (`role_id`),
  KEY `rule_name` (`rule_name`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='权限授权表';

-- ----------------------------
-- Records of h2w_auth_access
-- ----------------------------
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/link/index', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/ad/add_post', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/ad/add', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/ad/edit_post', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/ad/edit', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/ad/delete', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/ad/toggle', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/ad/index', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/plugin/update', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/plugin/uninstall', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/plugin/install', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/plugin/setting_post', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/plugin/setting', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/plugin/toggle', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/plugin/index', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/backup/import', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/backup/del_backup', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/backup/download', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/backup/index_post', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/backup/index', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/backup/restore', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/backup/default', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/extension/default', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'portal/adminpage/restore', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'portal/adminpage/clean', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'portal/adminpage/recyclebin', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'portal/adminpost/clean', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'portal/adminpost/restore', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'portal/adminpost/recyclebin', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/recycle/default', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'portal/adminpage/add_post', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'portal/adminpage/add', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'portal/adminpage/edit_post', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'portal/adminpage/edit', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'portal/adminpage/delete', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'portal/adminpage/listorders', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'portal/adminpage/index', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'portal/adminterm/add_post', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'portal/adminterm/add', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'portal/adminterm/edit_post', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'portal/adminterm/edit', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'portal/adminterm/delete', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'portal/adminterm/listorders', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'portal/adminterm/index', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'portal/adminpost/copy', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'portal/adminpost/add_post', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'portal/adminpost/add', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'portal/adminpost/edit_post', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'portal/adminpost/edit', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'portal/adminpost/delete', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'portal/adminpost/check', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'portal/adminpost/move', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'portal/adminpost/recommend', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'portal/adminpost/top', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'portal/adminpost/listorders', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'portal/adminpost/index', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'comment/commentadmin/check', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'comment/commentadmin/delete', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'comment/commentadmin/index', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'api/guestbookadmin/delete', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'api/guestbookadmin/index', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/content/default', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/link/listorders', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/link/toggle', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/link/delete', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/link/edit', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/link/edit_post', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/link/add', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/link/add_post', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'api/oauthadmin/setting', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'api/oauthadmin/setting_post', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/menu/default', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/navcat/default1', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/nav/index', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/nav/listorders', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/nav/delete', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/nav/edit', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/nav/edit_post', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/nav/add', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/nav/add_post', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/navcat/index', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/navcat/delete', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/navcat/edit', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/navcat/edit_post', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/navcat/add', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/navcat/add_post', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/menu/index', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/menu/add', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/menu/add_post', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/menu/listorders', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/menu/export_menu', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/menu/edit', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/menu/edit_post', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/menu/delete', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/menu/lists', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/menu/backup_menu', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/menu/export_menu_lang', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/menu/restore_menu', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/menu/getactions', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/setting/default', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/score/index', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/slide/index', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/slide/listorders', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/slide/toggle', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/slide/delete', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/slide/edit', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/slide/edit_post', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/slide/add', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/slide/add_post', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/slide/ban', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/slide/cancelban', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/slidecat/index', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/slidecat/delete', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/slidecat/edit', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/slidecat/edit_post', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/slidecat/add', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/slidecat/add_post', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/setting/userdefault', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/user/userinfo', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/user/userinfo_post', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/setting/password', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/setting/password_post', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/setting/site', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/setting/site_post', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/route/index', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/route/add', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/route/add_post', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/route/edit', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/route/edit_post', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/route/delete', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/route/ban', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/route/open', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/route/listorders', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/mailer/default', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/mailer/index', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/mailer/index_post', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/mailer/test', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/mailer/active', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/mailer/active_post', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/setting/clearcache', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/storage/index', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/storage/setting_post', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/setting/upload', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/setting/upload_post', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/filterkeyword/index', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/discuss/default', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/discuss/index', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/discuss/discuss_status', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/discuss/discuss_hot', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/discuss/info', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/discuss/comment', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/discuss/comment_delete', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/discuss/reply', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/discuss/reply_delete', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/discuss/category', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/discuss/cate_add', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/discuss/cate_add_post', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/discuss/cate_edit', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/discuss/cate_edit_post', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/discuss/cate_action', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/discuss/label', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/discuss/label_add', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/discuss/label_add_post', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/discuss/label_edit', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/discuss/label_edit_post', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/discuss/label_action', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'user/indexadmin/index', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'user/indexadmin/ban', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'user/indexadmin/cancelban', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'user/indexadmin/default3', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/rbac/index', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/rbac/member', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/rbac/authorize', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/rbac/authorize_post', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/rbac/roleedit', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/rbac/roleedit_post', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/rbac/roledelete', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/rbac/roleadd', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/rbac/roleadd_post', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/user/index', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/user/delete', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/user/edit', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/user/edit_post', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/user/add', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/user/add_post', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/user/ban', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/user/cancelban', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/authentication/index', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/gift/default', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/gift/index', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/orderlist/index', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/scripture/default', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/scripture/link', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/scripture/index', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('2', 'admin/school/index', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('3', 'admin/gift/default', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('3', 'admin/gift/index', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('3', 'admin/orderlist/index', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('3', 'admin/scripture/default', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('3', 'admin/scripture/link', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('3', 'admin/scripture/index', 'admin_url');
INSERT INTO `h2w_auth_access` VALUES ('3', 'admin/school/index', 'admin_url');

-- ----------------------------
-- Table structure for h2w_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `h2w_auth_rule`;
CREATE TABLE `h2w_auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '规则id,自增主键',
  `module` varchar(20) NOT NULL COMMENT '规则所属module',
  `type` varchar(30) NOT NULL DEFAULT '1' COMMENT '权限规则分类，请加应用前缀,如admin_',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '规则唯一英文标识,全小写',
  `param` varchar(255) DEFAULT NULL COMMENT '额外url参数',
  `title` varchar(20) NOT NULL DEFAULT '' COMMENT '规则中文描述',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否有效(0:无效,1:有效)',
  `condition` varchar(300) NOT NULL DEFAULT '' COMMENT '规则附加条件',
  PRIMARY KEY (`id`),
  KEY `module` (`module`,`status`,`type`)
) ENGINE=MyISAM AUTO_INCREMENT=234 DEFAULT CHARSET=utf8 COMMENT='权限规则表';

-- ----------------------------
-- Records of h2w_auth_rule
-- ----------------------------
INSERT INTO `h2w_auth_rule` VALUES ('54', 'Admin', 'admin_url', 'admin/slide/index', null, '轮播图管理', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('55', 'Admin', 'admin_url', 'admin/slide/listorders', null, '幻灯片排序', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('56', 'Admin', 'admin_url', 'admin/slide/toggle', null, '幻灯片显示切换', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('57', 'Admin', 'admin_url', 'admin/slide/delete', null, '删除幻灯片', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('58', 'Admin', 'admin_url', 'admin/slide/edit', null, '编辑幻灯片', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('59', 'Admin', 'admin_url', 'admin/slide/edit_post', null, '提交编辑', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('60', 'Admin', 'admin_url', 'admin/slide/add', null, '添加幻灯片', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('61', 'Admin', 'admin_url', 'admin/slide/add_post', null, '提交添加', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('85', 'Admin', 'admin_url', 'admin/menu/default', null, '菜单管理', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('100', 'Admin', 'admin_url', 'admin/menu/index', null, '后台菜单', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('101', 'Admin', 'admin_url', 'admin/menu/add', null, '添加菜单', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('102', 'Admin', 'admin_url', 'admin/menu/add_post', null, '提交添加', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('103', 'Admin', 'admin_url', 'admin/menu/listorders', null, '后台菜单排序', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('104', 'Admin', 'admin_url', 'admin/menu/export_menu', null, '菜单备份', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('105', 'Admin', 'admin_url', 'admin/menu/edit', null, '编辑菜单', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('106', 'Admin', 'admin_url', 'admin/menu/edit_post', null, '提交编辑', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('107', 'Admin', 'admin_url', 'admin/menu/delete', null, '删除菜单', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('108', 'Admin', 'admin_url', 'admin/menu/lists', null, '所有菜单', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('109', 'Admin', 'admin_url', 'admin/setting/default', null, '其他设置', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('110', 'Admin', 'admin_url', 'admin/setting/userdefault', null, '个人信息', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('113', 'Admin', 'admin_url', 'admin/setting/password', null, '修改密码', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('114', 'Admin', 'admin_url', 'admin/setting/password_post', null, '提交修改', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('131', 'Admin', 'admin_url', 'admin/setting/clearcache', null, '清除缓存', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('134', 'User', 'admin_url', 'user/indexadmin/index', null, '用户管理', '0', '');
INSERT INTO `h2w_auth_rule` VALUES ('135', 'User', 'admin_url', 'user/indexadmin/ban', null, '停用', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('136', 'User', 'admin_url', 'user/indexadmin/cancelban', null, '启用', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('139', 'User', 'admin_url', 'user/indexadmin/default3', null, '后台用户管理', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('140', 'Admin', 'admin_url', 'admin/rbac/index', null, '角色管理', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('142', 'Admin', 'admin_url', 'admin/rbac/authorize', null, '权限设置', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('143', 'Admin', 'admin_url', 'admin/rbac/authorize_post', null, '提交设置', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('144', 'Admin', 'admin_url', 'admin/rbac/roleedit', null, '编辑角色', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('145', 'Admin', 'admin_url', 'admin/rbac/roleedit_post', null, '提交编辑', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('146', 'Admin', 'admin_url', 'admin/rbac/roledelete', null, '删除角色', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('147', 'Admin', 'admin_url', 'admin/rbac/roleadd', null, '添加角色', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('148', 'Admin', 'admin_url', 'admin/rbac/roleadd_post', null, '提交添加', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('149', 'Admin', 'admin_url', 'admin/user/index', null, '后台用户管理', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('150', 'Admin', 'admin_url', 'admin/user/delete', null, '删除管理员', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('151', 'Admin', 'admin_url', 'admin/user/edit', null, '管理员编辑', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('152', 'Admin', 'admin_url', 'admin/user/edit_post', null, '编辑提交', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('153', 'Admin', 'admin_url', 'admin/user/add', null, '管理员添加', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('154', 'Admin', 'admin_url', 'admin/user/add_post', null, '添加提交', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('158', 'Admin', 'admin_url', 'admin/slide/ban', null, '禁用幻灯片', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('159', 'Admin', 'admin_url', 'admin/slide/cancelban', null, '启用幻灯片', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('160', 'Admin', 'admin_url', 'admin/user/ban', null, '禁用管理员', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('161', 'Admin', 'admin_url', 'admin/user/cancelban', null, '启用管理员', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('170', 'Admin', 'admin_url', 'admin/menu/backup_menu', null, '备份菜单', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('171', 'Admin', 'admin_url', 'admin/menu/export_menu_lang', null, '导出后台菜单多语言包', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('172', 'Admin', 'admin_url', 'admin/menu/restore_menu', null, '还原菜单', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('173', 'Admin', 'admin_url', 'admin/menu/getactions', null, '导入新菜单', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('174', 'Admin', 'admin_url', 'admin/authentication/index', null, '认证管理', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('175', 'admin', 'admin_url', 'admin/gift/default', null, '藏宝阁管理', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('176', 'Admin', 'admin_url', 'admin/scripture/default', null, '藏经阁管理', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('177', 'Admin', 'admin_url', 'admin/school/index', null, '学校管理', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('178', 'Admin', 'admin_url', 'admin/discuss/default', null, '讨论管理', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('179', 'Admin', 'admin_url', 'admin/discuss/index', null, '讨论管理', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('180', 'Admin', 'admin_url', 'admin/discuss/category', null, '分类管理', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('181', 'Admin', 'admin_url', 'admin/discuss/label', null, '标签管理', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('182', 'admin', 'admin_url', 'admin/gift/index', null, '商品管理', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('183', 'admin', 'admin_url', 'admin/orderlist/index', null, '发货管理', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('184', 'admin', 'admin_url', 'admin/scripture/link', null, '链接管理', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('185', 'Admin', 'admin_url', 'admin/scripture/index', null, '学霸笔记', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('186', 'Admin', 'admin_url', 'admin/filterkeyword/index', null, '关键词过滤', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('187', 'Admin', 'admin_url', 'admin/score/index', null, '积分设置', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('188', 'Admin', 'admin_url', 'admin/discuss/discuss_status', null, '停用/启用', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('189', 'Admin', 'admin_url', 'admin/discuss/discuss_hot', null, '设为/取消热门', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('190', 'Admin', 'admin_url', 'admin/discuss/info', null, '详情', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('191', 'Admin', 'admin_url', 'admin/discuss/comment', null, '评论信息', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('192', 'Admin', 'admin_url', 'admin/discuss/comment_delete', null, '删除评论', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('193', 'Admin', 'admin_url', 'admin/discuss/reply', null, '评论回复', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('194', 'Admin', 'admin_url', 'admin/discuss/reply_delete', null, '删除回复', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('195', 'Admin', 'admin_url', 'admin/discuss/cate_add', null, '添加分类', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('196', 'Admin', 'admin_url', 'admin/discuss/cate_add_post', null, '提交添加分类', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('197', 'Admin', 'admin_url', 'admin/discuss/cate_edit', null, '编辑分类', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('198', 'Admin', 'admin_url', 'admin/discuss/cate_edit_post', null, '提交编辑分类', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('199', 'Admin', 'admin_url', 'admin/discuss/cate_action', null, '显示/隐藏', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('200', 'Admin', 'admin_url', 'admin/discuss/label_add', null, '添加标签', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('201', 'Admin', 'admin_url', 'admin/discuss/label_add_post', null, '提交添加标签', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('202', 'Admin', 'admin_url', 'admin/discuss/label_edit', null, '编辑标签', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('203', 'Admin', 'admin_url', 'admin/discuss/label_edit_post', null, '提交编辑标签', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('204', 'Admin', 'admin_url', 'admin/discuss/label_action', null, '显示/隐藏', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('205', 'Admin', 'admin_url', 'admin/school/add', null, '新增学校', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('206', 'Admin', 'admin_url', 'admin/school/add_post', null, '提交新增学校', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('207', 'Admin', 'admin_url', 'admin/school/add_pro_post', null, '提交添加专业', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('208', 'Admin', 'admin_url', 'admin/school/edit', null, '编辑学校', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('209', 'Admin', 'admin_url', 'admin/school/edit_post', null, '提交编辑学校', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('210', 'Admin', 'admin_url', 'admin/school/ban', null, '停用学校', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('211', 'Admin', 'admin_url', 'admin/school/cancelban', null, '启用学校', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('212', 'Admin', 'admin_url', 'admin/school/ban_pro', null, '停用专业', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('213', 'Admin', 'admin_url', 'admin/school/cancelban_pro', null, '启用专业', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('214', 'Admin', 'admin_url', 'admin/scripture/add', null, '新增笔记', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('215', 'Admin', 'admin_url', 'admin/scripture/add_post', null, '新增笔记提交', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('216', 'Admin', 'admin_url', 'admin/scripture/edit', null, '笔记编辑', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('217', 'Admin', 'admin_url', 'admin/scripture/edit_post', null, '编辑提交', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('218', 'Admin', 'admin_url', 'admin/scripture/delete', null, '删除', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('219', 'Admin', 'admin_url', 'admin/scripture/down', null, '停用', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('220', 'Admin', 'admin_url', 'admin/scripture/up', null, '启用', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('221', 'Admin', 'admin_url', 'admin/scripture/link_post', null, '保存链接', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('222', 'Admin', 'admin_url', 'admin/gift/add', null, '新增商品', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('223', 'Admin', 'admin_url', 'admin/gift/add_post', null, '新增商品提交', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('224', 'Admin', 'admin_url', 'admin/gift/edit', null, '编辑商品', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('225', 'Admin', 'admin_url', 'admin/gift/edit_post', null, '编辑商品提交', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('226', 'Admin', 'admin_url', 'admin/gift/down', null, '下架', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('227', 'Admin', 'admin_url', 'admin/gift/up', null, '上架', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('228', 'Admin', 'admin_url', 'admin/orderlist/invoice', null, '待发货操作', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('229', 'Admin', 'admin_url', 'admin/authentication/edit', null, '详情', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('230', 'Admin', 'admin_url', 'admin/authentication/edit_post', null, '认证操作', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('231', 'Admin', 'admin_url', 'admin/score/editpost', null, '设置提交', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('232', 'Admin', 'admin_url', 'admin/filterkeyword/addpost', null, '新增关键词', '1', '');
INSERT INTO `h2w_auth_rule` VALUES ('233', 'Admin', 'admin_url', 'admin/filterkeyword/delete', null, '删除', '1', '');

-- ----------------------------
-- Table structure for h2w_category
-- ----------------------------
DROP TABLE IF EXISTS `h2w_category`;
CREATE TABLE `h2w_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '类型名称',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 1正常 0隐藏',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '分类类型',
  `listorder` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `create_time` int(10) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='分类表';

-- ----------------------------
-- Records of h2w_category
-- ----------------------------
INSERT INTO `h2w_category` VALUES ('1', '托尔斯泰', '1', '0', '1', '1542875819');

-- ----------------------------
-- Table structure for h2w_comment
-- ----------------------------
DROP TABLE IF EXISTS `h2w_comment`;
CREATE TABLE `h2w_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `discuss_id` int(11) NOT NULL COMMENT '讨论id',
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `content` varchar(500) NOT NULL COMMENT '评论内容',
  `like_num` int(11) NOT NULL DEFAULT '0' COMMENT '点赞量',
  `reply_num` int(11) NOT NULL DEFAULT '0' COMMENT '回复量',
  `listorder` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `create_time` int(10) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='评论表';

-- ----------------------------
-- Records of h2w_comment
-- ----------------------------
INSERT INTO `h2w_comment` VALUES ('1', '1', '1', '下手的撒的成', '0', '0', '0', '1542940131');
INSERT INTO `h2w_comment` VALUES ('2', '1', '2', '是都称得上VCD', '0', '0', '0', '1542940148');

-- ----------------------------
-- Table structure for h2w_comments
-- ----------------------------
DROP TABLE IF EXISTS `h2w_comments`;
CREATE TABLE `h2w_comments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_table` varchar(100) NOT NULL COMMENT '评论内容所在表，不带表前缀',
  `post_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '评论内容 id',
  `url` varchar(255) DEFAULT NULL COMMENT '原文地址',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '发表评论的用户id',
  `to_uid` int(11) NOT NULL DEFAULT '0' COMMENT '被评论的用户id',
  `full_name` varchar(50) DEFAULT NULL COMMENT '评论者昵称',
  `email` varchar(255) DEFAULT NULL COMMENT '评论者邮箱',
  `createtime` datetime NOT NULL DEFAULT '2000-01-01 00:00:00' COMMENT '评论时间',
  `content` text NOT NULL COMMENT '评论内容',
  `type` smallint(1) NOT NULL DEFAULT '1' COMMENT '评论类型；1实名评论',
  `parentid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '被回复的评论id',
  `path` varchar(500) DEFAULT NULL,
  `status` smallint(1) NOT NULL DEFAULT '1' COMMENT '状态，1已审核，0未审核',
  PRIMARY KEY (`id`),
  KEY `comment_post_ID` (`post_id`),
  KEY `comment_approved_date_gmt` (`status`),
  KEY `comment_parent` (`parentid`),
  KEY `table_id_status` (`post_table`,`post_id`,`status`),
  KEY `createtime` (`createtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='评论表';

-- ----------------------------
-- Records of h2w_comments
-- ----------------------------

-- ----------------------------
-- Table structure for h2w_common_action_log
-- ----------------------------
DROP TABLE IF EXISTS `h2w_common_action_log`;
CREATE TABLE `h2w_common_action_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` bigint(20) DEFAULT '0' COMMENT '用户id',
  `object` varchar(100) DEFAULT NULL COMMENT '访问对象的id,格式：不带前缀的表名+id;如posts1表示xx_posts表里id为1的记录',
  `action` varchar(50) DEFAULT NULL COMMENT '操作名称；格式规定为：应用名+控制器+操作名；也可自己定义格式只要不发生冲突且惟一；',
  `count` int(11) DEFAULT '0' COMMENT '访问次数',
  `last_time` int(11) DEFAULT '0' COMMENT '最后访问的时间戳',
  `ip` varchar(15) DEFAULT NULL COMMENT '访问者最后访问ip',
  PRIMARY KEY (`id`),
  KEY `user_object_action` (`user`,`object`,`action`),
  KEY `user_object_action_ip` (`user`,`object`,`action`,`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='访问记录表';

-- ----------------------------
-- Records of h2w_common_action_log
-- ----------------------------

-- ----------------------------
-- Table structure for h2w_discuss
-- ----------------------------
DROP TABLE IF EXISTS `h2w_discuss`;
CREATE TABLE `h2w_discuss` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '创建用户id',
  `school_id` int(11) NOT NULL COMMENT '学校id',
  `name` varchar(255) DEFAULT NULL COMMENT '讨论标题',
  `content` text COMMENT '讨论内容',
  `image` text COMMENT '图片',
  `category_id` int(11) NOT NULL COMMENT '分类id',
  `label` varchar(255) DEFAULT NULL COMMENT '标签',
  `hot` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否热门 1不是 2是',
  `click_num` int(11) NOT NULL DEFAULT '0' COMMENT '点击量',
  `comment_num` int(11) NOT NULL DEFAULT '0' COMMENT '评论量',
  `collect_num` int(11) NOT NULL DEFAULT '0' COMMENT '收藏量',
  `like_num` int(11) NOT NULL DEFAULT '0' COMMENT '评论点赞量',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 1正常 2停用',
  `create_time` int(10) NOT NULL COMMENT '创建时间',
  `update_time` int(10) NOT NULL COMMENT '最后更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='讨论表';

-- ----------------------------
-- Records of h2w_discuss
-- ----------------------------
INSERT INTO `h2w_discuss` VALUES ('1', '2', '1', '互动式', '次可适当是东方红惊恐地快乐的是否还记得', null, '1', '他说阿萨德', '1', '0', '0', '0', '0', '1', '1542939874', '1542939874');

-- ----------------------------
-- Table structure for h2w_filter_keyword
-- ----------------------------
DROP TABLE IF EXISTS `h2w_filter_keyword`;
CREATE TABLE `h2w_filter_keyword` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `keyword` varchar(255) DEFAULT '',
  `create_time` int(11) DEFAULT '0',
  `effect_area` varchar(255) DEFAULT NULL COMMENT '生效区域',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='过滤关键字';

-- ----------------------------
-- Records of h2w_filter_keyword
-- ----------------------------
INSERT INTO `h2w_filter_keyword` VALUES ('5', '习近平', '1542881695', '1,2,3');
INSERT INTO `h2w_filter_keyword` VALUES ('6', '黄继光', '1542881752', '1,2,3');
INSERT INTO `h2w_filter_keyword` VALUES ('7', 'admin', '1542881852', '1');
INSERT INTO `h2w_filter_keyword` VALUES ('8', '管理员', '1542881887', '2,3');

-- ----------------------------
-- Table structure for h2w_gift
-- ----------------------------
DROP TABLE IF EXISTS `h2w_gift`;
CREATE TABLE `h2w_gift` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gift_name` varchar(255) DEFAULT NULL COMMENT '礼品名称',
  `type` tinyint(3) DEFAULT '1' COMMENT '1积分   2赞助商品',
  `price` int(10) DEFAULT '0' COMMENT '单价',
  `surplus` int(11) DEFAULT '0' COMMENT '剩余库存',
  `cover_img` varchar(255) DEFAULT '' COMMENT '封面图',
  `product_intro` text COMMENT '商品介绍',
  `status` tinyint(3) DEFAULT '0' COMMENT '0已上架 1已下架',
  `create_time` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='礼品';

-- ----------------------------
-- Records of h2w_gift
-- ----------------------------
INSERT INTO `h2w_gift` VALUES ('1', '二次元抱枕', '1', '10', '11', 'admin/20181121/5bf510ecd7a00.png', '[{&quot;img&quot;:&quot;\\/data\\/upload\\/admin\\/20181121\\/5bf50dc878690.png&quot;,&quot;sort&quot;:&quot;1&quot;},{&quot;img&quot;:&quot;\\/data\\/upload\\/admin\\/20181121\\/5bf50dcac7c18.png&quot;,&quot;sort&quot;:&quot;2&quot;},{&quot;img&quot;:&quot;\\/data\\/upload\\/admin\\/20181121\\/5bf50dd4e1258.png&quot;,&quot;sort&quot;:&quot;3&quot;},{&quot;img&quot;:&quot;\\/data\\/upload\\/admin\\/20181121\\/5bf50dd7bb0f8.png&quot;,&quot;sort&quot;:&quot;4&quot;},{&quot;img&quot;:&quot;\\/data\\/upload\\/admin\\/20181121\\/5bf5100c0f6e0.png&quot;,&quot;sort&quot;:&quot;5&quot;},{&quot;img&quot;:&quot;\\/data\\/upload\\/admin\\/20181121\\/5bf510124e6b0.png&quot;,&quot;sort&quot;:&quot;6&quot;},{&quot;img&quot;:&quot;\\/data\\/upload\\/admin\\/20181121\\/5bf5101d10680.png&quot;,&quot;sort&quot;:&quot;7&quot;},{&quot;img&quot;:&quot;\\/data\\/upload\\/admin\\/20181121\\/5bf510d645df8.png&quot;,&quot;sort&quot;:&quot;8&quot;},{&quot;img&quot;:&quot;\\/data\\/upload\\/admin\\/20181121\\/5bf510d8e3580.png&quot;,&quot;sort&quot;:&quot;9&quot;},{&quot;img&quot;:&quot;\\/data\\/upload\\/admin\\/20181121\\/5bf510dc62ae8.png&quot;,&quot;sort&quot;:&quot;10&quot;}]', '1', '1522586580');
INSERT INTO `h2w_gift` VALUES ('2', '可口可乐铝瓶樱花版', '2', '15', '5', 'admin/20181121/5bf5142b0f2f8.png', '[{&quot;img&quot;:&quot;\\/data\\/upload\\/admin\\/20181121\\/5bf51438d4738.png&quot;,&quot;sort&quot;:&quot;1&quot;},{&quot;img&quot;:&quot;\\/data\\/upload\\/admin\\/20181121\\/5bf5143ada8e0.png&quot;,&quot;sort&quot;:&quot;1&quot;},{&quot;img&quot;:&quot;\\/data\\/upload\\/admin\\/20181121\\/5bf5143c849e0.png&quot;,&quot;sort&quot;:&quot;1&quot;},{&quot;img&quot;:&quot;\\/data\\/upload\\/admin\\/20181121\\/5bf5143e03b60.png&quot;,&quot;sort&quot;:&quot;1&quot;},{&quot;img&quot;:&quot;\\/data\\/upload\\/admin\\/20181121\\/5bf514416bf58.png&quot;,&quot;sort&quot;:&quot;5&quot;}]', '0', '1522586580');
INSERT INTO `h2w_gift` VALUES ('3', ' 阿萨德阿萨德', '1', '0', '0', 'admin/20181121/5bf5141219320.png', '[{&quot;img&quot;:&quot;\\/data\\/upload\\/admin\\/20181121\\/5bf513b0dd7c0.png&quot;,&quot;sort&quot;:&quot;1&quot;}]', '0', '1542787850');
INSERT INTO `h2w_gift` VALUES ('4', ' 新增商品', '1', '123', '0', 'admin/20181121/5bf5154dca328.png', '[{&quot;img&quot;:&quot;&quot;,&quot;sort&quot;:&quot;1&quot;}]', '1', '1542788434');
INSERT INTO `h2w_gift` VALUES ('5', 'ipad 2018', '1', '2599', '0', 'admin/20181127/5bfc90d037910.jpg', null, '0', '1543278802');

-- ----------------------------
-- Table structure for h2w_guestbook
-- ----------------------------
DROP TABLE IF EXISTS `h2w_guestbook`;
CREATE TABLE `h2w_guestbook` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(50) NOT NULL COMMENT '留言者姓名',
  `email` varchar(100) NOT NULL COMMENT '留言者邮箱',
  `title` varchar(255) DEFAULT NULL COMMENT '留言标题',
  `msg` text NOT NULL COMMENT '留言内容',
  `createtime` datetime NOT NULL COMMENT '留言时间',
  `status` smallint(2) NOT NULL DEFAULT '1' COMMENT '留言状态，1：正常，0：删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='留言表';

-- ----------------------------
-- Records of h2w_guestbook
-- ----------------------------

-- ----------------------------
-- Table structure for h2w_label
-- ----------------------------
DROP TABLE IF EXISTS `h2w_label`;
CREATE TABLE `h2w_label` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '标签名称',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 1正常 0隐藏',
  `listorder` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `create_time` int(10) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='标签表';

-- ----------------------------
-- Records of h2w_label
-- ----------------------------
INSERT INTO `h2w_label` VALUES ('1', '他说阿萨德', '1', '1', '1542875839');

-- ----------------------------
-- Table structure for h2w_link
-- ----------------------------
DROP TABLE IF EXISTS `h2w_link`;
CREATE TABLE `h2w_link` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of h2w_link
-- ----------------------------

-- ----------------------------
-- Table structure for h2w_links
-- ----------------------------
DROP TABLE IF EXISTS `h2w_links`;
CREATE TABLE `h2w_links` (
  `link_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `link_url` varchar(255) NOT NULL COMMENT '友情链接地址',
  `link_name` varchar(255) NOT NULL COMMENT '友情链接名称',
  `link_image` varchar(255) DEFAULT NULL COMMENT '友情链接图标',
  `link_target` varchar(25) NOT NULL DEFAULT '_blank' COMMENT '友情链接打开方式',
  `link_description` text NOT NULL COMMENT '友情链接描述',
  `link_status` int(2) NOT NULL DEFAULT '1' COMMENT '状态，1显示，0不显示',
  `link_rating` int(11) NOT NULL DEFAULT '0' COMMENT '友情链接评级',
  `link_rel` varchar(255) DEFAULT NULL COMMENT '链接与网站的关系',
  `listorder` int(10) NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`link_id`),
  KEY `link_visible` (`link_status`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='友情链接表';

-- ----------------------------
-- Records of h2w_links
-- ----------------------------
INSERT INTO `h2w_links` VALUES ('1', 'http://www.thinkcmf.com', 'ThinkCMF', '', '_blank', '', '1', '0', '', '0');

-- ----------------------------
-- Table structure for h2w_menu
-- ----------------------------
DROP TABLE IF EXISTS `h2w_menu`;
CREATE TABLE `h2w_menu` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `parentid` smallint(6) unsigned NOT NULL DEFAULT '0',
  `app` varchar(30) NOT NULL DEFAULT '' COMMENT '应用名称app',
  `model` varchar(30) NOT NULL DEFAULT '' COMMENT '控制器',
  `action` varchar(50) NOT NULL DEFAULT '' COMMENT '操作名称',
  `data` varchar(50) NOT NULL DEFAULT '' COMMENT '额外参数',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '菜单类型  1：权限认证+菜单；0：只作为菜单',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态，1显示，0不显示',
  `name` varchar(50) NOT NULL COMMENT '菜单名称',
  `icon` varchar(50) DEFAULT NULL COMMENT '菜单图标',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `listorder` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '排序ID',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `parentid` (`parentid`),
  KEY `model` (`model`)
) ENGINE=MyISAM AUTO_INCREMENT=247 DEFAULT CHARSET=utf8 COMMENT='后台菜单表';

-- ----------------------------
-- Records of h2w_menu
-- ----------------------------
INSERT INTO `h2w_menu` VALUES ('200', '109', 'Admin', 'Score', 'index', '', '1', '1', '积分设置', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('54', '109', 'Admin', 'Slide', 'index', '', '1', '1', '轮播图管理', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('55', '54', 'Admin', 'Slide', 'listorders', '', '1', '0', '幻灯片排序', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('56', '54', 'Admin', 'Slide', 'toggle', '', '1', '0', '幻灯片显示切换', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('57', '54', 'Admin', 'Slide', 'delete', '', '1', '0', '删除幻灯片', '', '', '1000');
INSERT INTO `h2w_menu` VALUES ('58', '54', 'Admin', 'Slide', 'edit', '', '1', '0', '编辑幻灯片', '', '', '1000');
INSERT INTO `h2w_menu` VALUES ('59', '58', 'Admin', 'Slide', 'edit_post', '', '1', '0', '提交编辑', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('60', '54', 'Admin', 'Slide', 'add', '', '1', '0', '添加幻灯片', '', '', '1000');
INSERT INTO `h2w_menu` VALUES ('61', '60', 'Admin', 'Slide', 'add_post', '', '1', '0', '提交添加', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('85', '0', 'Admin', 'Menu', 'default', '', '1', '0', '菜单管理', 'list', '', '20');
INSERT INTO `h2w_menu` VALUES ('100', '85', 'Admin', 'Menu', 'index', '', '1', '1', '后台菜单', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('101', '100', 'Admin', 'Menu', 'add', '', '1', '0', '添加菜单', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('102', '101', 'Admin', 'Menu', 'add_post', '', '1', '0', '提交添加', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('103', '100', 'Admin', 'Menu', 'listorders', '', '1', '0', '后台菜单排序', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('104', '100', 'Admin', 'Menu', 'export_menu', '', '1', '0', '菜单备份', '', '', '1000');
INSERT INTO `h2w_menu` VALUES ('105', '100', 'Admin', 'Menu', 'edit', '', '1', '0', '编辑菜单', '', '', '1000');
INSERT INTO `h2w_menu` VALUES ('106', '105', 'Admin', 'Menu', 'edit_post', '', '1', '0', '提交编辑', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('107', '100', 'Admin', 'Menu', 'delete', '', '1', '0', '删除菜单', '', '', '1000');
INSERT INTO `h2w_menu` VALUES ('108', '100', 'Admin', 'Menu', 'lists', '', '1', '0', '所有菜单', '', '', '1000');
INSERT INTO `h2w_menu` VALUES ('109', '0', 'Admin', 'Setting', 'default', '', '0', '1', '其他设置', 'cogs', '', '6');
INSERT INTO `h2w_menu` VALUES ('110', '109', 'Admin', 'Setting', 'userdefault', '', '0', '0', '个人信息', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('113', '110', 'Admin', 'Setting', 'password', '', '1', '0', '修改密码', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('114', '113', 'Admin', 'Setting', 'password_post', '', '1', '0', '提交修改', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('131', '109', 'Admin', 'Setting', 'clearcache', '', '1', '0', '清除缓存', '', '', '1');
INSERT INTO `h2w_menu` VALUES ('191', '0', 'Admin', 'Discuss', 'default', '', '1', '1', '讨论管理', 'weixin', '', '2');
INSERT INTO `h2w_menu` VALUES ('134', '0', 'User', 'Indexadmin', 'index', '', '1', '1', '用户管理', 'group', '', '0');
INSERT INTO `h2w_menu` VALUES ('135', '134', 'User', 'Indexadmin', 'ban', '', '1', '0', '停用', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('136', '134', 'User', 'Indexadmin', 'cancelban', '', '1', '0', '启用', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('192', '191', 'Admin', 'Discuss', 'index', '', '1', '1', '讨论管理', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('139', '0', 'User', 'Indexadmin', 'default3', '', '1', '1', '后台用户管理', 'user', '', '7');
INSERT INTO `h2w_menu` VALUES ('140', '139', 'Admin', 'Rbac', 'index', '', '1', '1', '角色管理', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('142', '140', 'Admin', 'Rbac', 'authorize', '', '1', '0', '权限设置', '', '', '1000');
INSERT INTO `h2w_menu` VALUES ('143', '142', 'Admin', 'Rbac', 'authorize_post', '', '1', '0', '提交设置', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('144', '140', 'Admin', 'Rbac', 'roleedit', '', '1', '0', '编辑角色', '', '', '1000');
INSERT INTO `h2w_menu` VALUES ('145', '144', 'Admin', 'Rbac', 'roleedit_post', '', '1', '0', '提交编辑', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('146', '140', 'Admin', 'Rbac', 'roledelete', '', '1', '0', '删除角色', '', '', '1000');
INSERT INTO `h2w_menu` VALUES ('147', '140', 'Admin', 'Rbac', 'roleadd', '', '1', '0', '添加角色', '', '', '1000');
INSERT INTO `h2w_menu` VALUES ('148', '147', 'Admin', 'Rbac', 'roleadd_post', '', '1', '0', '提交添加', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('149', '139', 'Admin', 'User', 'index', '', '1', '1', '后台用户管理', 'address-card ', '', '0');
INSERT INTO `h2w_menu` VALUES ('150', '149', 'Admin', 'User', 'delete', '', '1', '0', '删除管理员', '', '', '1000');
INSERT INTO `h2w_menu` VALUES ('151', '149', 'Admin', 'User', 'edit', '', '1', '0', '管理员编辑', '', '', '1000');
INSERT INTO `h2w_menu` VALUES ('152', '151', 'Admin', 'User', 'edit_post', '', '1', '0', '编辑提交', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('153', '149', 'Admin', 'User', 'add', '', '1', '0', '管理员添加', '', '', '1000');
INSERT INTO `h2w_menu` VALUES ('154', '153', 'Admin', 'User', 'add_post', '', '1', '0', '添加提交', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('158', '54', 'Admin', 'Slide', 'ban', '', '1', '0', '禁用幻灯片', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('159', '54', 'Admin', 'Slide', 'cancelban', '', '1', '0', '启用幻灯片', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('160', '149', 'Admin', 'User', 'ban', '', '1', '0', '禁用管理员', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('161', '149', 'Admin', 'User', 'cancelban', '', '1', '0', '启用管理员', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('174', '100', 'Admin', 'Menu', 'backup_menu', '', '1', '0', '备份菜单', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('175', '100', 'Admin', 'Menu', 'export_menu_lang', '', '1', '0', '导出后台菜单多语言包', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('176', '100', 'Admin', 'Menu', 'restore_menu', '', '1', '0', '还原菜单', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('177', '100', 'Admin', 'Menu', 'getactions', '', '1', '0', '导入新菜单', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('187', '0', 'Admin', 'Authentication', 'index', '', '1', '1', '认证管理', 'address-card-o', '', '5');
INSERT INTO `h2w_menu` VALUES ('188', '0', 'admin', 'Gift', 'default', '', '0', '1', '藏宝阁管理', 'gift', '', '4');
INSERT INTO `h2w_menu` VALUES ('189', '0', 'Admin', 'Scripture', 'default', '', '0', '1', '藏经阁管理', 'book', '', '3');
INSERT INTO `h2w_menu` VALUES ('190', '0', 'Admin', 'School', 'index', '', '1', '1', '学校管理', 'bank', '', '1');
INSERT INTO `h2w_menu` VALUES ('193', '191', 'Admin', 'Discuss', 'category', '', '1', '1', '分类管理', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('194', '191', 'Admin', 'Discuss', 'label', '', '1', '1', '标签管理', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('201', '192', 'Admin', 'Discuss', 'discuss_status', '', '1', '0', '停用/启用', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('195', '188', 'admin', 'gift', 'index', '', '1', '1', '商品管理', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('196', '188', 'admin', 'OrderList', 'index', '', '1', '1', '发货管理', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('197', '189', 'admin', 'Scripture', 'link', '', '1', '1', '链接管理', '', '', '2');
INSERT INTO `h2w_menu` VALUES ('198', '189', 'Admin', 'Scripture', 'index', '', '1', '1', '学霸笔记', '', '', '1');
INSERT INTO `h2w_menu` VALUES ('199', '109', 'Admin', 'FilterKeyword', 'index', '', '1', '1', '关键词过滤', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('202', '192', 'Admin', 'Discuss', 'discuss_hot', '', '1', '0', '设为/取消热门', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('203', '192', 'Admin', 'Discuss', 'info', '', '1', '0', '详情', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('204', '192', 'Admin', 'Discuss', 'comment', '', '1', '0', '评论信息', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('205', '192', 'Admin', 'Discuss', 'comment_delete', '', '1', '0', '删除评论', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('206', '192', 'Admin', 'Discuss', 'reply', '', '1', '0', '评论回复', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('207', '192', 'Admin', 'Discuss', 'reply_delete', '', '1', '0', '删除回复', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('208', '193', 'Admin', 'Discuss', 'cate_add', '', '1', '0', '添加分类', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('209', '208', 'Admin', 'Discuss', 'cate_add_post', '', '1', '0', '提交添加分类', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('210', '193', 'Admin', 'Discuss', 'cate_edit', '', '1', '0', '编辑分类', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('211', '210', 'Admin', 'Discuss', 'cate_edit_post', '', '1', '0', '提交编辑分类', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('212', '193', 'Admin', 'Discuss', 'cate_action', '', '1', '0', '显示/隐藏', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('213', '194', 'Admin', 'Discuss', 'label_add', '', '1', '0', '添加标签', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('214', '213', 'Admin', 'Discuss', 'label_add_post', '', '1', '0', '提交添加标签', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('215', '194', 'Admin', 'Discuss', 'label_edit', '', '1', '0', '编辑标签', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('216', '215', 'Admin', 'Discuss', 'label_edit_post', '', '1', '0', '提交编辑标签', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('217', '194', 'Admin', 'Discuss', 'label_action', '', '1', '0', '显示/隐藏', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('218', '190', 'Admin', 'School', 'add', '', '1', '0', '新增学校', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('219', '218', 'Admin', 'School', 'add_post', '', '1', '0', '提交新增学校', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('220', '218', 'Admin', 'School', 'add_pro_post', '', '1', '0', '提交添加专业', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('221', '190', 'Admin', 'School', 'edit', '', '1', '0', '编辑学校', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('222', '221', 'Admin', 'School', 'edit_post', '', '1', '0', '提交编辑学校', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('223', '190', 'Admin', 'School', 'ban', '', '1', '0', '停用学校', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('224', '223', 'Admin', 'School', 'cancelban', '', '1', '0', '启用学校', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('225', '190', 'Admin', 'School', 'ban_pro', '', '1', '0', '停用专业', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('226', '225', 'Admin', 'School', 'cancelban_pro', '', '1', '0', '启用专业', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('227', '198', 'Admin', 'Scripture', 'add', '', '1', '0', '新增笔记', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('228', '227', 'Admin', 'Scripture', 'add_post', '', '1', '0', '新增笔记提交', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('229', '198', 'Admin', 'Scripture', 'edit', '', '1', '0', '笔记编辑', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('230', '229', 'Admin', 'Scripture', 'edit_post', '', '1', '0', '编辑提交', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('231', '198', 'Admin', 'Scripture', 'delete', '', '1', '0', '删除', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('232', '198', 'Admin', 'Scripture', 'down', '', '1', '0', '停用', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('233', '232', 'Admin', 'Scripture', 'up', '', '1', '0', '启用', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('234', '197', 'Admin', 'Scripture', 'link_post', '', '1', '0', '保存链接', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('235', '195', 'Admin', 'Gift', 'add', '', '1', '0', '新增商品', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('236', '235', 'Admin', 'Gift', 'add_post', '', '1', '0', '新增商品提交', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('237', '195', 'Admin', 'Gift', 'edit', '', '1', '0', '编辑商品', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('238', '237', 'Admin', 'Gift', 'edit_post', '', '1', '0', '编辑商品提交', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('239', '195', 'Admin', 'Gift', 'down', '', '1', '0', '下架', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('240', '239', 'Admin', 'Gift', 'up', '', '1', '0', '上架', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('241', '196', 'Admin', 'OrderList', 'invoice', '', '1', '0', '待发货操作', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('242', '187', 'Admin', 'Authentication', 'edit', '', '1', '0', '详情', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('243', '242', 'Admin', 'Authentication', 'edit_post', '', '1', '0', '认证操作', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('244', '200', 'Admin', 'Score', 'editPost', '', '1', '0', '设置提交', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('245', '199', 'Admin', 'FilterKeyword', 'addPost', '', '1', '0', '新增关键词', '', '', '0');
INSERT INTO `h2w_menu` VALUES ('246', '199', 'Admin', 'FilterKeyword', 'delete', '', '1', '0', '删除', '', '', '0');

-- ----------------------------
-- Table structure for h2w_nav
-- ----------------------------
DROP TABLE IF EXISTS `h2w_nav`;
CREATE TABLE `h2w_nav` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL COMMENT '导航分类 id',
  `parentid` int(11) NOT NULL COMMENT '导航父 id',
  `label` varchar(255) NOT NULL COMMENT '导航标题',
  `target` varchar(50) DEFAULT NULL COMMENT '打开方式',
  `href` varchar(255) NOT NULL COMMENT '导航链接',
  `icon` varchar(255) NOT NULL COMMENT '导航图标',
  `status` int(2) NOT NULL DEFAULT '1' COMMENT '状态，1显示，0不显示',
  `listorder` int(6) DEFAULT '0' COMMENT '排序',
  `path` varchar(255) NOT NULL DEFAULT '0' COMMENT '层级关系',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='前台导航表';

-- ----------------------------
-- Records of h2w_nav
-- ----------------------------
INSERT INTO `h2w_nav` VALUES ('1', '1', '0', '首页', '', 'home', '', '1', '0', '0-1');
INSERT INTO `h2w_nav` VALUES ('2', '1', '0', '列表演示', '', 'a:2:{s:6:\"action\";s:17:\"Portal/List/index\";s:5:\"param\";a:1:{s:2:\"id\";s:1:\"1\";}}', '', '1', '0', '0-2');
INSERT INTO `h2w_nav` VALUES ('3', '1', '0', '瀑布流', '', 'a:2:{s:6:\"action\";s:17:\"Portal/List/index\";s:5:\"param\";a:1:{s:2:\"id\";s:1:\"2\";}}', '', '1', '0', '0-3');

-- ----------------------------
-- Table structure for h2w_nav_cat
-- ----------------------------
DROP TABLE IF EXISTS `h2w_nav_cat`;
CREATE TABLE `h2w_nav_cat` (
  `navcid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '导航分类名',
  `active` int(1) NOT NULL DEFAULT '1' COMMENT '是否为主菜单，1是，0不是',
  `remark` text COMMENT '备注',
  PRIMARY KEY (`navcid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='前台导航分类表';

-- ----------------------------
-- Records of h2w_nav_cat
-- ----------------------------
INSERT INTO `h2w_nav_cat` VALUES ('1', '主导航', '1', '主导航');

-- ----------------------------
-- Table structure for h2w_oauth_user
-- ----------------------------
DROP TABLE IF EXISTS `h2w_oauth_user`;
CREATE TABLE `h2w_oauth_user` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `from` varchar(20) NOT NULL COMMENT '用户来源key',
  `name` varchar(30) NOT NULL COMMENT '第三方昵称',
  `head_img` varchar(200) NOT NULL COMMENT '头像',
  `uid` int(20) NOT NULL COMMENT '关联的本站用户id',
  `create_time` datetime NOT NULL COMMENT '绑定时间',
  `last_login_time` datetime NOT NULL COMMENT '最后登录时间',
  `last_login_ip` varchar(16) NOT NULL COMMENT '最后登录ip',
  `login_times` int(6) NOT NULL COMMENT '登录次数',
  `status` tinyint(2) NOT NULL,
  `access_token` varchar(512) NOT NULL,
  `expires_date` int(11) NOT NULL COMMENT 'access_token过期时间',
  `openid` varchar(40) NOT NULL COMMENT '第三方用户id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='第三方用户表';

-- ----------------------------
-- Records of h2w_oauth_user
-- ----------------------------

-- ----------------------------
-- Table structure for h2w_options
-- ----------------------------
DROP TABLE IF EXISTS `h2w_options`;
CREATE TABLE `h2w_options` (
  `option_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `option_name` varchar(64) NOT NULL COMMENT '配置名',
  `option_value` longtext NOT NULL COMMENT '配置值',
  `autoload` int(2) NOT NULL DEFAULT '1' COMMENT '是否自动加载',
  PRIMARY KEY (`option_id`),
  UNIQUE KEY `option_name` (`option_name`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='全站配置表';

-- ----------------------------
-- Records of h2w_options
-- ----------------------------
INSERT INTO `h2w_options` VALUES ('1', 'member_email_active', '{\"title\":\"ThinkCMF\\u90ae\\u4ef6\\u6fc0\\u6d3b\\u901a\\u77e5.\",\"template\":\"<p>\\u672c\\u90ae\\u4ef6\\u6765\\u81ea<a href=\\\"http:\\/\\/www.thinkcmf.com\\\">ThinkCMF<\\/a><br\\/><br\\/>&nbsp; &nbsp;<strong>---------------<strong style=\\\"white-space: normal;\\\">---<\\/strong><\\/strong><br\\/>&nbsp; &nbsp;<strong>\\u5e10\\u53f7\\u6fc0\\u6d3b\\u8bf4\\u660e<\\/strong><br\\/>&nbsp; &nbsp;<strong>---------------<strong style=\\\"white-space: normal;\\\">---<\\/strong><\\/strong><br\\/><br\\/>&nbsp; &nbsp; \\u5c0a\\u656c\\u7684<span style=\\\"FONT-SIZE: 16px; FONT-FAMILY: Arial; COLOR: rgb(51,51,51); LINE-HEIGHT: 18px; BACKGROUND-COLOR: rgb(255,255,255)\\\">#username#\\uff0c\\u60a8\\u597d\\u3002<\\/span>\\u5982\\u679c\\u60a8\\u662fThinkCMF\\u7684\\u65b0\\u7528\\u6237\\uff0c\\u6216\\u5728\\u4fee\\u6539\\u60a8\\u7684\\u6ce8\\u518cEmail\\u65f6\\u4f7f\\u7528\\u4e86\\u672c\\u5730\\u5740\\uff0c\\u6211\\u4eec\\u9700\\u8981\\u5bf9\\u60a8\\u7684\\u5730\\u5740\\u6709\\u6548\\u6027\\u8fdb\\u884c\\u9a8c\\u8bc1\\u4ee5\\u907f\\u514d\\u5783\\u573e\\u90ae\\u4ef6\\u6216\\u5730\\u5740\\u88ab\\u6ee5\\u7528\\u3002<br\\/>&nbsp; &nbsp; \\u60a8\\u53ea\\u9700\\u70b9\\u51fb\\u4e0b\\u9762\\u7684\\u94fe\\u63a5\\u5373\\u53ef\\u6fc0\\u6d3b\\u60a8\\u7684\\u5e10\\u53f7\\uff1a<br\\/>&nbsp; &nbsp; <a title=\\\"\\\" href=\\\"http:\\/\\/#link#\\\" target=\\\"_self\\\">http:\\/\\/#link#<\\/a><br\\/>&nbsp; &nbsp; (\\u5982\\u679c\\u4e0a\\u9762\\u4e0d\\u662f\\u94fe\\u63a5\\u5f62\\u5f0f\\uff0c\\u8bf7\\u5c06\\u8be5\\u5730\\u5740\\u624b\\u5de5\\u7c98\\u8d34\\u5230\\u6d4f\\u89c8\\u5668\\u5730\\u5740\\u680f\\u518d\\u8bbf\\u95ee)<br\\/>&nbsp; &nbsp; \\u611f\\u8c22\\u60a8\\u7684\\u8bbf\\u95ee\\uff0c\\u795d\\u60a8\\u4f7f\\u7528\\u6109\\u5feb\\uff01<br\\/><br\\/>&nbsp; &nbsp; \\u6b64\\u81f4<br\\/>&nbsp; &nbsp; ThinkCMF \\u7ba1\\u7406\\u56e2\\u961f.<\\/p>\"}', '1');
INSERT INTO `h2w_options` VALUES ('6', 'site_options', '{\"site_name\":\"\\u5b66\\u751fAPP\\u540e\\u53f0\\u7ba1\\u7406\\u7cfb\\u7edf\",\"site_admin_url_password\":\"\",\"site_tpl\":\"simplebootx\",\"site_adminstyle\":\"flat\",\"site_icp\":\"\",\"site_admin_email\":\"123456@qq.com\",\"site_tongji\":\"\",\"site_copyright\":\"\",\"site_seo_title\":\"\\u5b66\\u751fAPP\\u540e\\u53f0\\u7ba1\\u7406\\u7cfb\\u7edf\",\"site_seo_keywords\":\"\\u5b66\\u751fAPP\\u540e\\u53f0\\u7ba1\\u7406\\u7cfb\\u7edf\",\"site_seo_description\":\"\\u5b66\\u751fAPP\\u540e\\u53f0\\u7ba1\\u7406\\u7cfb\\u7edf\",\"urlmode\":\"2\",\"html_suffix\":\"\",\"comment_time_interval\":\"60\"}', '1');
INSERT INTO `h2w_options` VALUES ('7', 'cmf_settings', '{\"banned_usernames\":\"\"}', '1');
INSERT INTO `h2w_options` VALUES ('8', 'cdn_settings', '{\"cdn_static_root\":\"\"}', '1');

-- ----------------------------
-- Table structure for h2w_order_list
-- ----------------------------
DROP TABLE IF EXISTS `h2w_order_list`;
CREATE TABLE `h2w_order_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gift_name` varchar(255) DEFAULT '' COMMENT '礼品名称',
  `number` int(11) DEFAULT '1',
  `user_name` varchar(255) DEFAULT '' COMMENT '收货人姓名',
  `address` varchar(255) DEFAULT '' COMMENT '收货地址',
  `mobile` varchar(11) DEFAULT '' COMMENT '联系电话',
  `user_id` int(11) DEFAULT '0' COMMENT '用户ID',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间',
  `status` tinyint(3) DEFAULT '0' COMMENT '0 未发货  1已发货  2已关闭',
  `remark` varchar(255) DEFAULT '' COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='订单记录';

-- ----------------------------
-- Records of h2w_order_list
-- ----------------------------
INSERT INTO `h2w_order_list` VALUES ('1', '二次元贷模式的', '1', '王小二', '地址', '13600000000', '0', '0', '1', '');

-- ----------------------------
-- Table structure for h2w_plugins
-- ----------------------------
DROP TABLE IF EXISTS `h2w_plugins`;
CREATE TABLE `h2w_plugins` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `name` varchar(50) NOT NULL COMMENT '插件名，英文',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '插件名称',
  `description` text COMMENT '插件描述',
  `type` tinyint(2) DEFAULT '0' COMMENT '插件类型, 1:网站；8;微信',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态；1开启；',
  `config` text COMMENT '插件配置',
  `hooks` varchar(255) DEFAULT NULL COMMENT '实现的钩子;以“，”分隔',
  `has_admin` tinyint(2) DEFAULT '0' COMMENT '插件是否有后台管理界面',
  `author` varchar(50) DEFAULT '' COMMENT '插件作者',
  `version` varchar(20) DEFAULT '' COMMENT '插件版本号',
  `createtime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '插件安装时间',
  `listorder` smallint(6) NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='插件表';

-- ----------------------------
-- Records of h2w_plugins
-- ----------------------------

-- ----------------------------
-- Table structure for h2w_posts
-- ----------------------------
DROP TABLE IF EXISTS `h2w_posts`;
CREATE TABLE `h2w_posts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_author` bigint(20) unsigned DEFAULT '0' COMMENT '发表者id',
  `post_keywords` varchar(150) NOT NULL COMMENT 'seo keywords',
  `post_source` varchar(150) DEFAULT NULL COMMENT '转载文章的来源',
  `post_date` datetime DEFAULT '2000-01-01 00:00:00' COMMENT 'post发布日期',
  `post_content` longtext COMMENT 'post内容',
  `post_title` text COMMENT 'post标题',
  `post_excerpt` text COMMENT 'post摘要',
  `post_status` int(2) DEFAULT '1' COMMENT 'post状态，1已审核，0未审核,3删除',
  `comment_status` int(2) DEFAULT '1' COMMENT '评论状态，1允许，0不允许',
  `post_modified` datetime DEFAULT '2000-01-01 00:00:00' COMMENT 'post更新时间，可在前台修改，显示给用户',
  `post_content_filtered` longtext,
  `post_parent` bigint(20) unsigned DEFAULT '0' COMMENT 'post的父级post id,表示post层级关系',
  `post_type` int(2) DEFAULT '1' COMMENT 'post类型，1文章,2页面',
  `post_mime_type` varchar(100) DEFAULT '',
  `comment_count` bigint(20) DEFAULT '0',
  `smeta` text COMMENT 'post的扩展字段，保存相关扩展属性，如缩略图；格式为json',
  `post_hits` int(11) DEFAULT '0' COMMENT 'post点击数，查看数',
  `post_like` int(11) DEFAULT '0' COMMENT 'post赞数',
  `istop` tinyint(1) NOT NULL DEFAULT '0' COMMENT '置顶 1置顶； 0不置顶',
  `recommended` tinyint(1) NOT NULL DEFAULT '0' COMMENT '推荐 1推荐 0不推荐',
  PRIMARY KEY (`id`),
  KEY `type_status_date` (`post_type`,`post_status`,`post_date`,`id`),
  KEY `post_parent` (`post_parent`),
  KEY `post_author` (`post_author`),
  KEY `post_date` (`post_date`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Portal文章表';

-- ----------------------------
-- Records of h2w_posts
-- ----------------------------

-- ----------------------------
-- Table structure for h2w_reply
-- ----------------------------
DROP TABLE IF EXISTS `h2w_reply`;
CREATE TABLE `h2w_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `discuss_id` int(11) NOT NULL COMMENT '讨论id',
  `comment_id` int(11) NOT NULL COMMENT '评论id',
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `content` varchar(500) NOT NULL COMMENT '回复内容',
  `listorder` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `create_time` int(10) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='回复表';

-- ----------------------------
-- Records of h2w_reply
-- ----------------------------

-- ----------------------------
-- Table structure for h2w_role
-- ----------------------------
DROP TABLE IF EXISTS `h2w_role`;
CREATE TABLE `h2w_role` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL COMMENT '角色名称',
  `pid` smallint(6) DEFAULT NULL COMMENT '父角色ID',
  `status` tinyint(1) unsigned DEFAULT NULL COMMENT '状态',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `listorder` int(3) NOT NULL DEFAULT '0' COMMENT '排序字段',
  PRIMARY KEY (`id`),
  KEY `parentId` (`pid`),
  KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='角色表';

-- ----------------------------
-- Records of h2w_role
-- ----------------------------
INSERT INTO `h2w_role` VALUES ('1', '超级管理员', '0', '1', '拥有网站最高管理员权限！', '1329633709', '1329633709', '0');
INSERT INTO `h2w_role` VALUES ('2', '后台权限', null, '1', ' 阿斯达撒大声地的', '1542941289', '1542944665', '0');
INSERT INTO `h2w_role` VALUES ('3', '用户', null, '1', '普通用户', '1542952893', '0', '0');

-- ----------------------------
-- Table structure for h2w_role_user
-- ----------------------------
DROP TABLE IF EXISTS `h2w_role_user`;
CREATE TABLE `h2w_role_user` (
  `role_id` int(11) unsigned DEFAULT '0' COMMENT '角色 id',
  `user_id` int(11) DEFAULT '0' COMMENT '用户id',
  KEY `group_id` (`role_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户角色对应表';

-- ----------------------------
-- Records of h2w_role_user
-- ----------------------------
INSERT INTO `h2w_role_user` VALUES ('2', '3');
INSERT INTO `h2w_role_user` VALUES ('2', '4');

-- ----------------------------
-- Table structure for h2w_route
-- ----------------------------
DROP TABLE IF EXISTS `h2w_route`;
CREATE TABLE `h2w_route` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '路由id',
  `full_url` varchar(255) DEFAULT NULL COMMENT '完整url， 如：portal/list/index?id=1',
  `url` varchar(255) DEFAULT NULL COMMENT '实际显示的url',
  `listorder` int(5) DEFAULT '0' COMMENT '排序，优先级，越小优先级越高',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态，1：启用 ;0：不启用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='url路由表';

-- ----------------------------
-- Records of h2w_route
-- ----------------------------

-- ----------------------------
-- Table structure for h2w_school
-- ----------------------------
DROP TABLE IF EXISTS `h2w_school`;
CREATE TABLE `h2w_school` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `school_name` varchar(255) DEFAULT NULL COMMENT '学校名称',
  `type` tinyint(1) DEFAULT '1' COMMENT '学校类型 1 大学;  2 高中',
  `address` varchar(333) DEFAULT '' COMMENT '所在地',
  `professional_type` varchar(255) DEFAULT '' COMMENT '专业类型',
  `status` tinyint(2) DEFAULT '0' COMMENT '启用状态 : 0 启用  1冻结',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='学校';

-- ----------------------------
-- Records of h2w_school
-- ----------------------------
INSERT INTO `h2w_school` VALUES ('1', '成都理工大学', '1', '成都市成华区二仙桥东三路1号', '高水平综合性大学', '1', '0');
INSERT INTO `h2w_school` VALUES ('2', '湖北省孝感高中', '2', '湖北省孝感市交通西路特1号', '', '0', '0');
INSERT INTO `h2w_school` VALUES ('3', '北京大学', '1', '北京市海淀区颐和园路5号', '综合性大学', '0', '0');
INSERT INTO `h2w_school` VALUES ('4', '重庆大学', '1', '重庆市沙坪坝区沙正街174号', '综合类大学', '0', '0');
INSERT INTO `h2w_school` VALUES ('5', '湖北省黄冈中学', '2', '湖北省黄冈市黄州区南湖路1号', '', '0', '0');
INSERT INTO `h2w_school` VALUES ('6', '清华大学', '1', '北京市海淀区清华大学', '综合性大学', '0', '0');
INSERT INTO `h2w_school` VALUES ('7', '黄冈中学', '2', '11111111111111', '', '0', '0');
INSERT INTO `h2w_school` VALUES ('8', '大景城', '1', '小阿斯顿撒', '南京', '0', '1543222212');
INSERT INTO `h2w_school` VALUES ('9', '重庆工商大学', '1', '杀价帮看下撒娇', '工商类', '0', '1543223494');
INSERT INTO `h2w_school` VALUES ('12', '重庆一中', '2', '小阿斯达克23', '', '1', '1543223904');

-- ----------------------------
-- Table structure for h2w_school_professional
-- ----------------------------
DROP TABLE IF EXISTS `h2w_school_professional`;
CREATE TABLE `h2w_school_professional` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pro_name` varchar(255) DEFAULT NULL COMMENT '专业名称',
  `status` tinyint(3) DEFAULT '0' COMMENT '0正常  1停用',
  `school_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='学校专业';

-- ----------------------------
-- Records of h2w_school_professional
-- ----------------------------
INSERT INTO `h2w_school_professional` VALUES ('1', '123123', '1', '1');
INSERT INTO `h2w_school_professional` VALUES ('2', '123123', '0', '1');
INSERT INTO `h2w_school_professional` VALUES ('3', '阿萨德', '0', '1');
INSERT INTO `h2w_school_professional` VALUES ('4', '123123123', '0', '1');
INSERT INTO `h2w_school_professional` VALUES ('5', '123123123', '0', '1');
INSERT INTO `h2w_school_professional` VALUES ('6', '123123', '0', '1');
INSERT INTO `h2w_school_professional` VALUES ('7', '阿萨德阿萨斯', '0', '6');
INSERT INTO `h2w_school_professional` VALUES ('10', '工程', '1', '3');
INSERT INTO `h2w_school_professional` VALUES ('8', '阿萨德啊', '0', '6');
INSERT INTO `h2w_school_professional` VALUES ('9', '阿萨德按时的', '0', '6');
INSERT INTO `h2w_school_professional` VALUES ('11', 'qwq', '0', '8');

-- ----------------------------
-- Table structure for h2w_scripture
-- ----------------------------
DROP TABLE IF EXISTS `h2w_scripture`;
CREATE TABLE `h2w_scripture` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `views` int(11) DEFAULT '0' COMMENT '点击',
  `collect` int(11) DEFAULT '0' COMMENT '收藏',
  `create_time` int(11) DEFAULT '0' COMMENT '发布时间',
  `content` text COMMENT '内容',
  `cover_img` varchar(255) DEFAULT '' COMMENT '封面图',
  `status` int(2) DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='藏金阁';

-- ----------------------------
-- Records of h2w_scripture
-- ----------------------------
INSERT INTO `h2w_scripture` VALUES ('2', '当幸福来敲门', '0', '0', '1542866268', '&lt;p&gt;《当幸福来敲门》是&lt;a target=&quot;_blank&quot; href=&quot;https://baike.baidu.com/item/%E5%A8%81%E5%B0%94%C2%B7%E5%8F%B2%E5%AF%86%E6%96%AF&quot; style=&quot;color: rgb(19, 110, 194); text-decoration-line: none;&quot;&gt;威尔·史密斯&lt;/a&gt;一次崭新的亮相，似乎暗示了他的某种转型心态。——《汉密尔顿报》&lt;/p&gt;&lt;p&gt;父子温馨的励志主题显然能够打动大部分观众的心，两位主演的表演由于显而易见的原因非常的真实、可信。 ——《USA周末》&lt;span class=&quot;sup--normal&quot; data-sup=&quot;5&quot; style=&quot;font-size: 12px; line-height: 0; position: relative; vertical-align: baseline; top: -0.5em; margin-left: 2px; color: rgb(51, 102, 204); cursor: pointer; padding: 0px 2px;&quot;&gt;&amp;nbsp;[5]&lt;/span&gt;&lt;a style=&quot;color: rgb(19, 110, 194); position: relative; top: -50px; font-size: 0px; line-height: 0;&quot; name=&quot;ref_[5]_5365528&quot;&gt;&lt;/a&gt;&amp;nbsp;&lt;/p&gt;&lt;p&gt;贫穷的生活让妻子落跑，只剩一个人近中年的男人带着儿子在大街上风餐露宿，为了让儿子有正常的上学环境，他卖血、他跑好几条街寻找流浪汉——那人抢走只能给他换几餐饭的医疗仪器，他跟一群刚毕业的孩子在同一起跑线争抢一个实习机会，但在孩子面前，他永远都是最顶天立地的爸爸，即便窘迫到要在地铁站过夜，他也要和孩子一起玩躲避恐龙的游戏，孩子也懂事得从未让他烦恼，反而成为他每次倒下又爬起的动力&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;', 'https://ss0.baidu.com/6ONWsjip0QIZ8tyhnq/it/u=1510629243,1341180955&amp;fm=58&amp;bpow=400&amp;bpoh=559', '0');
INSERT INTO `h2w_scripture` VALUES ('5', '优美散文集', '0', '0', '1542866268', '&lt;p&gt;&lt;img src=&quot;http://student.com/data/upload/ueditor/20181122/5bf6444698e18.png&quot; title=&quot;z.png&quot; style=&quot;white-space: normal;&quot;/&gt;&lt;img src=&quot;http://student.com/data/upload/ueditor/20181122/5bf644469c0e0.png&quot; title=&quot;y.png&quot; style=&quot;white-space: normal;&quot;/&gt;&lt;img src=&quot;http://student.com/data/upload/ueditor/20181122/5bf64446a70a8.png&quot; title=&quot;h.png&quot; style=&quot;white-space: normal;&quot;/&gt;&lt;img src=&quot;http://student.com/data/upload/ueditor/20181122/5bf644469fb78.png&quot; title=&quot;l.png&quot; style=&quot;white-space: normal;&quot;/&gt;&amp;nbsp;13123123123213&lt;/p&gt;', 'admin/20181122/5bf645597b188.png', '0');
INSERT INTO `h2w_scripture` VALUES ('6', '雨城古事', '0', '0', '1542867469', '&lt;p&gt;　　三访西班牙，最称心的一件事，便是我在进香客找(HotelPeregrino)的房间高跟八楼，西望全城，一片橘红&lt;br/&gt;&lt;br/&gt;　　色屋顶的尽处，正对着那千年古寺黑矗天际的双塔。白昼或是夜晚，晴日或是阴天，幢幢的塔影永远在那里，守着这小城虔敬的天空。尤其是深夜，满城的灯火巳经冷落，却依旧托出它高肃的轮廓，仍在那上面，护佑着梦里的千万信徒。下雨的日子它仍在天边，撑着比中世纪更低压的阴云，醃黯的魁伟依旧挺峭，只是隔雨看来，带了几分凄清。&lt;br/&gt;&lt;br/&gt;　　小城是多雨的，却下得间歇而飘忽，不像连绵不断的淫雨那样令人厌畏。旅游家凯因(RobertKane)的书里危言警告来游的人，务必要带雨伞、雨衣，还有——只要你的行李装得下——套鞋。”除了套鞋，我都带了，也都用了，而且绝对不止一次。有一次简直不够用，因为雨来得大而且急。偏偏那一次天恩就没有随身带伞，只好与我共撑。我虽然还穿了雨衣，裤子仍然湿透。&lt;br/&gt;&lt;br/&gt;　　后来就算晴天出门，也逼得天恩同时带伞。雨是没有一天不下，有时一天下好几场，忽而霏霏，忽而滂沱。一时雨气弥漫，满城都在薄薄的灰氛里，行人奔窜四散，留下广场的空旷。天恩和我也屡屡避进大教堂，或是人家的门下。只要不往身上淋，只要不带来水灾，雨，总是可喜的，像是天在安慰地，并为万物涤罪去污，还其清纯。八年来久居干旱的高雄，偶尔一场快雨，都令我惊喜而清爽。小城多雨，街上无尘，四野的树丛绿得分外滋润，人家的红顶白墙也更加醒眼了。&lt;br/&gt;&lt;br/&gt;　　伊比利亚半岛是一块干燥的高台地，但是在加利西亚(Galicia)这一带，却葱茏而多雨。在此地，问人昨天是晴是阴，答案很难确定，因为雨一定是下过了，但天也似乎一度放晴。雨霁的天穹蓝得不可思议，云罗飞得那样洁白、滑爽，害得原本庄重肃穆的大教堂尖顶，几乎都要乘风而起追云而去了。&lt;br/&gt;&lt;br/&gt;　　小城的晴天有一种透明而飘扬的快感，那是因为雨歇日出的关系。令我记忆深刻的，却是雨中的小城。总是从几点雨滴洒落在脸上开始，抬头看时，水墨渗漫的雨云已经压在广场的低空，连大教堂的尖顶也淹没在镨郁的雾氛里了。雨脚从远处扫射过来，溅起满地的白气蒸腾。雨伞丛生，像一片蠕蠕的黑蕈，我的头上也开了一朵。满巷的黑伞令人想起《瑟堡的雨伞》，凄清得祟人。那张法国片子究竟发生了什么，早就忘了，但是伞影下那海峡雨港的气氛，却挥之不去。雨，真是一种慢性的纠缠，温柔的萦扰。往事若是有雨，就更令人追怀。我甚至有一点迷信：我死的日子该会下雨，一场雨声，将我接去。&lt;br/&gt;&lt;br/&gt;　　我带去两班牙的，是一把小黑伞,可以折叠，伞柄还能缩骨，但一按开关•倏地弹汗，却为我遮挡了大西岸的满天风雨c因为这加利西亚的小城离海只有五六十公里，进香客只要一直朝西，不久就到了天涯海角，当地人称为“地之尽头”(Finisterre)。据说公元前二世纪，罗马兵抵达此地，西望海上日落，凛然而生虔敬的畏心。小城虽小，名气却很大，因为耶稣的使徒雅各，圣骸葬在此地。中世纪以来，迢迢一条朝圣之路，把无数虔敬的教徒带来此地，也带来了我，一位虔敬的非教徒。&lt;br/&gt;&lt;br/&gt;　　二&lt;br/&gt;&lt;br/&gt;　　小城名叫圣地牙哥，位于西班牙的西北角，人口不过七万五千，在中国人之间知者寥寥，但在天主教的世界，排名却仅在耶路撒冷和罗马之下，成为进香客奔赴的第三圣城。远从纽约、巴黎、法兰克福，一架架的班机把朝圣荇载来这里。但是在-千年前，虔敬的朝圣者却是镍着海扇徽帽，披着大氅，背着行囊，拄着牧杖，杖头挂着葫芦，远从法国边境，越过白巍巍的比利牛斯山，更沿着崁塔布连的横岭一路朝西，抵达这圣地牙哥之路(CaminodeSantiago)的终站。年复一年,万千的香客不畏辛苦，络绎于途，乔叟《康城故事》里的豪放女，那著名的巴斯城五嫁妇人，也在其列，只为了来这小城，向圣约翰之兄，耶稣的使徒大雅各(St.JamestheGreater)膜拜顶礼。&lt;br/&gt;&lt;br/&gt;　　圣雅各是西班牙的守护神，因为当年追随耶稣，被希律王杀害，用刀斩首，据说遗体被帆船运往西班牙，隔日便到。圣地牙哥西南的河港巴德隆(Padron，西班牙文“纪念碑”之意），还有一块巨石，迄今有人指点，说是当年之舟。另一传说则是当年载圣骸来此的，是一艘大理石船。一位武士见船入港，坐骑受惊，连人带马跃入海中。武士攀上大理石船，始免溺水，但衣上却附满了海扇壳。也就因此，扇形的贝壳成了圣雅各的象征，出现在本地一切的纪念品、旗帜或海报上。在我所住的“进香客栈”的外墙上，巨幅壁画就以香客的三大标志:牧杖、葫芦、海扇壳来构图。&lt;br/&gt;&lt;br/&gt;　　公元八一三年，隐士斐拉由（Pelayo)夜见星光灿烂，照耀原野，循光一路前行，竟在林中发现了圣雅各的古墓。他向国王阿芳索二世(AlfonsoI)及狄奥多米洛主教(BishopTeodomiro)陈述此事，国王便在墓地盖了一座教堂，主教也决定身后埋骨于此，其地乃称孔波斯泰(Compostela)，意即“星野”(CampodelaEstrella)。圣雅各既为西班牙之守护神，拉丁美洲乃有不少城市以他为名，最大的一座是智利的首都圣地牙哥，其他如古巴、阿根廷、多米尼加各国也都有此城。为了区别，就在后面再加名号，例如古巴那一座城就叫做SantiagodeCuba。因此西班牙西北隅的这座小城，全名是“星野的圣地牙哥&quot;（SantiagodeCompostela)。&lt;br/&gt;&lt;br/&gt;　　雅各之墓在此发现，消息渐渐传遍天主教的各国。信徒开始来此朝圣，先是来自加利西亚这一带，后来连法国的高僧、主教也远来膜拜，终于香火鼎盛，远客不绝于途，凭着炽热的虔敬,跋涉成一条有名的“圣地牙哥之路”,在伊比利亚半岛的北部，绵延六百公里，疲困的足印上覆盖着向往的足印，年复一年，走出了中世纪信仰的轨迹，欧洲团结的标记。&lt;br/&gt;&lt;br/&gt;　　古墓发现于八一三年的七月二十五日，每年此日遂定为圣雅各节，罗马教廷更规定，若此日适逢星期日，则该年成为“圣年”(AHoSanto)，香火尤盛。自一一八二年起，各地天主教徒齐来圣地牙哥庆祝圣年，已有将近千年的传统。二十世纪下半期以来，每逢圣年，香客更多达二百万人。一九九三年国际笔会在此召开年会，由加利西亚的笔会担任地主，也是为了配合圣年的庆典。&lt;br/&gt;&lt;br/&gt;　　三&lt;br/&gt;&lt;br/&gt;　　在圣雅各墓地上,早年所建的教堂不到两百年，即在公元九九七年，被入侵的回教徒领袖阿芒索(Amanzor)所毁，甚至寺钟也被运去科尔多瓦(Cordova)。一(_)七五年，在原址开始重建大教堂，结构改为当时流行的罗马风格。其后不断增建，到了十八世纪又加盖巴洛克格式的外壳，益形多彩多姿。正如伦敦的西敏寺，国家大典常在其中举行。早在公元一一一一年，阿芳索六世便在大教堂中加冕登基，成为加利西亚国王。&lt;br/&gt;&lt;br/&gt;　　在圣地牙哥城巍峨的众教堂中，这座古寺并非元老，而是第三;但因祭坛上方供着耶稣使徒的神龛，而主堂地下的墓穴里，有一只八十五公斤的银瓷，盛着圣雅各及其爱徒阿塔纳秀(Atanasio)与戴奥多洛(Teodoro)的遗骸，万千信徒攀山越水，正是为此而来，所以此寺不但尊耸本城，而且号召全西班牙，甚至在天主教的世界独拥一片天空。&lt;br/&gt;&lt;br/&gt;　　我游欧洲，从五十岁才开始，已经是老兴了，说不上是壮游。从此对新大陆的游兴大减,深感美国的浅近无趣。大凡旅游之趣，不出二途。外向者可以登高临远，探胜寻幽，赏造化之神奇:这方面美国、加拿大还是大有可观的。内向者可以向户内探索，神往于异国人文之源远流长，风格各具:博物馆、美术馆、旧址故居之类，最宜瞻仰。卢浮宫、大英博物馆等等,当然是文化游客必拜之地，我也不能例外。但更加令我低回而不忍去，一入便不能出的，却是巍峨深阒的大教堂。&lt;br/&gt;&lt;br/&gt;　　有-次在海外开会，和一位香港学者经过一座大教堂。我建议进去小坐，她不表兴趣，说，有什么好看，乂说她旅外多次，从未参观教堂。一位学者这么不好奇，且不说这么不虔敬了，令我十分惊讶。我既非名正言顺的任何教徒，也非理直气壮的无神论者，对于他人敬神的场所却总有几分敬意;若是建筑壮丽，香火穆肃，而信徒又匍匐专注，仪式又隆重认真，就更添一番感动，往往更是感愧，愧此身仍在教化之外，并且羡慕他人的信仰有皈依，灵魂有寄托。&lt;br/&gt;&lt;br/&gt;　　欧洲有名的大教堂•从英国的圣保罗、西敏寺到维也纳的圣司提芬，从法国的圣母院、沙特寺到科隆的双塔大教堂，只要有机会瞻仰，我从不错过。若一次意犹未足，过了几年，更携妻重访，共仰高标。我们深感，一座悠久而宏伟的大教堂，何止是宗教的圣殿，也是历史的证明，建筑的典范，帝王与高僧的冥寝,经卷与文献的守卫，名画与雕刻的珍藏。这一切，甚至比博物馆还要生动自然，因为一个民族真是这么生活过来的，带着希望与传说，恐惧与安慰。&lt;br/&gt;&lt;br/&gt;　　那么一整座庄严而磅礴的建筑，踏实而稳重地压在地上，却从厚笃笃的体积和吨位之中奋发上升，向高处努力拔峭，祓起棱角森然的钟楼与塔顶•将一座纤秀的卜字架，祷告一般举向青空。你走了进去,穿过圣徒和天使群守护的拱门。密实的高门在你背后闭拢，广场和市卢，鸽群和全世界都关在外面，阒不可闻了。里面是另-度空间和时间。你在保护色一般的阴影里，坐在长条椅上，正堂尽头，祭坛与神龛遥遥在望，虔敬的眼神顺着交错而对称的弧线上升，仰瞻拱形的穹顶。多么崇高的空间感啊，那是愿望的方向，只有颂歌的亢奋，大风琴的隆然，才能飞上去，飞啊，绕着那圆穹回荡。七彩的玻璃窗，那么缤纷地诉说着《圣经》的故事，衬着外面的天色，似真似幻。忽然阳光透了进来，彩窗一下子就烧艳了，晴光熊熊，像一声祷告刚邀得了天听。久伸颈项，累了的眼神收下来,落在一长排乳白色的烛光之上，一长排清纯的素烛，肃静地烘托着低缓的时间。对着此情此景，你感觉多安详啊多安定。于是闭上了倦目，你安心睡去。&lt;br/&gt;&lt;br/&gt;　　在欧洲旅行时，兴奋的心情常常苦了疲惫的双脚，歇脚的地方没有比一座大教堂更理想的了。不但来者不拒，而且那么恢宏而高的空间几乎为你所独有,任你选坐休憩，闭目沉思，更无黑袍或红衣的僧侣来干扰或逐客。这是气候不侵的空间，钟表不管的时间。整个中世纪不也就这么静静地、从容不迫地流去了么，然则冥坐一下午又有何妨？梦里不知身是客，忙而又盲,一晌贪赶。你是旅客,短暂的也是永久的，血肉之身的也是形而上的。现在你终于不忙了，似乎可以想一想灵魂的问题，而且似乎会有答案,在蔷薇窗与白烛之间，交瓣错弧的圆穹之下。欧游每在夏季。一进寺门，满街的燥热和喧嚣便摆脱了。里面是清凉世界，扑面的寒寂令人醒爽。坐久了，怎堪回去尘市、尘世。&lt;br/&gt;&lt;br/&gt;　　四&lt;br/&gt;&lt;br/&gt;　　国际笔会年会的第三天上午，六十九国的作家齐集，去瞻仰圣地牙哥的古教堂，并分坐于横堂(Transept)两端，参加了隆重的弥撒盛典。司祭白衣红袍，朱色的披肩上佩着V字形的白绶带，垂着勋章，正喃喃诵着经文，信徒们时或齐声合诵，时或侧耳恭聆。&lt;br/&gt;&lt;br/&gt;　　祭坛之后是别有洞天的神龛，在点点白烛和空际复蕊大吊灯的交映之下，翩飞的天使群簇拥着圣雅各的一身三相。一片耀金炫银的辉煌，正当其中央，头戴海扇冠、手持牧羊杖、杖头挂着葫芦，而披肩上闪着七彩宝石的，是圣雅各坐姿的石像，由十二世纪的玛窦大师(Mae-stroMateo)雕成。圣颜饱满庄严，胡髭连腮，坐镇在众目焦聚的正裳，其相为师表雅各(St.JamestheMaster)。&lt;br/&gt;&lt;br/&gt;　　龛窟深邃，幕顶高超，上面的俨然台榭，森然神祇，一层高于一层，光影之消长也层层加深。中层供的据说是香客雅各(St.jamesthePilgrim)，上层供的则是武士雅各(St.JamestheKnight)，卫于其侧的则是西班牙四位国王:阿芳索二世、拉米洛一世、费迪南五世、菲立普四世。至于四角飞翔的天使,据说是象征四大美德:谨慎、公正、强壮、中庸。尽管下面的灯火灿亮，上面的这一切生动与尊荣,从我低而且远的座位，也只能仿佛瞻仰了。&lt;br/&gt;&lt;br/&gt;　　颂歌忽然升起，领唱者深沉浑厚的嗓音回旋拔高，莨遥瓜瓣的穹顶，整个教堂崇伟的空间，任其尽情激荡。至其高潮，不由得聆者的心跳不被它提掖远扬，而顿觉人境若弃，神境可亲。每历此境，总令我悲喜交集，狂悦之中，深心感到久欠信仰的恨憾。原非无神论者，此刻被搜在颂歌的掌控，更无力自命为异教徒。&lt;br/&gt;&lt;br/&gt;　　歌声终于停了，众人落回座位。领罢圣体，捐罢奉献，以为仪式结束了，祭坛前忽然多了,\\位红衣僧侣，抬来一座银光耀目的香炉，高齐人胸，并有四条长链贯串周边的扣孔、汇于顶盖。司祭置香入炉后，他们把香炉系在空垂的粗索上，又向旁边的高石柱上解开长索的另一端。每人再以一条稍细的短索牵引长索，成辐射之势散立八方，便合力牵起索来。原来长索绕过穹顶的一个大滑轮，此刻一端斜斜操在八僧手中，另一端则垂直而下，吊着银炉。&lt;br/&gt;&lt;br/&gt;　　八僧通力牵索，身影蹲而复起，退而复进。我的目光循索而上，达于穹顶，太高了，看不出那滑轮有什么动静。另一端的银炉却抖了一下，摇晃了起来。不久就像钟摆，老成持重地来回摇摆。幅度渐摆渐开，弧势随之加猛，下面所有的仰脸也都跟着，目骇而口张。不由我不惴惴然，记起爱伦坡的故事《深渊与荡斧》。曳着腾腾的青烟f炉越荡越高，弧度也越大了。横堂偌大的空厅，任由冲动的一团银影，迅疾地呼呼来去,把异香播扬到四方。至其高潮，几乎要撞上对面的高窗，整座教堂都似乎随着它微晃，令人不安。有人压抑不住惊惶，低叫起&lt;br/&gt;&lt;br/&gt;　　终于，红衣诸僧慢了下来，任香炉自己恢复平静。一片欢喜赞叹声中，天恩说：&lt;br/&gt;&lt;br/&gt;　　“好在吊得够高。要是给撞到，岂不变成厂martyr?”&lt;br/&gt;&lt;br/&gt;　　大家笑起来。泰国的尼妲雅（NitayaMasavisut)却说：&lt;br/&gt;&lt;br/&gt;　　“恐怕martyr没做成，倒成了一团marshmallow!”&lt;br/&gt;&lt;br/&gt;　　“这仪式叫做荡香炉（Botafumeiro)，由来已久。”一位本地作家对我说，“古代的香客长途奔波而来，那时没有客栈投宿，只好将就挤在教堂里。为了净化空气，便用这香炉来播放清芬。”&lt;br/&gt;&lt;br/&gt;　　“倒是有趣的传统我笑道，“看来香炉不轻呢。”&lt;br/&gt;&lt;br/&gt;　　“对呀,五十八公斤。高度一点六米。否则哪用八个人来荡?”&lt;br/&gt;&lt;br/&gt;　　正说着，正龛的雅各雕像背后，人影晃处，一双手臂由里面伸出来,把像的颈抱住，然后又+见广。&lt;br/&gt;&lt;br/&gt;　　“那又是做什么?”我不禁纳罕。&lt;br/&gt;&lt;br/&gt;　　“那又是一个传统，”那加利西亚作家说，“从中世纪起，信徙们f辛万苦来到朝圣的终点,忏悔既毕，满心欣喜，不由自主就会学浪子回头•把西班牙人信仰之父热情地拥抱一f。从前圣雅各的头上没有这-盘红蓝宝镶边的光轮，香客就惯于把自己帽子脱下,暂a放在雅各头上，才便于行抱礼。”.&lt;br/&gt;&lt;br/&gt;　　过了一会，他又说还有一个传统值得一看，跟我来吧。”便带了天恩和我，穿过人群，走到大教堂前门内的柱廊，说这一排门柱叫做“光荣之门&quot;(PorticodelaGlorm)，上面所雕的两百位《圣经》人物，都是十二世纪雕刻大师玛窦所成，不但是这座罗马式大建筑的镇寺之宝，也是整个罗马式艺术的罕见杰作。&lt;br/&gt;&lt;br/&gt;　　石柱共为五根，均附有雕像，以斑岩刻成。居中的一根虽然较细，却是大师的主力所在，也是主题所托。最上面的半圆形拱壁，博大的气象中层次明确，序列井然。耶稣戴着王冠，跣足而坐，前臂平举，双掌向前张开，展示掌心光荣的伤痕。他的脸略向前倾，目光俯视，神情宁静之中似在沉思;长发与密须鬉茂相接，曲线起伏流畅，十分俊美。我仰瞻久之，感叹莫名。&lt;br/&gt;&lt;br/&gt;　　紧侍在耶稣身旁的，是马可、路加、约翰、马太四位传福音的使徒。在他左侧柱端展示手卷而立的，是摩西、以赛亚、但以理、耶利米四先知;相对而立于右侧柱端的，则为彼得、保罗、雅各、约翰四使徒。凡此皆为荦荦大者，其气象在严整之中各有殊胜。至于穿插其间，或坐或站、或大或小、或正或鼓、或俯或仰，环拱于耶稣四周、罗列于半圆弧上者，令人目眩颈酸、意夺神摇，不忍移目却又不能久仰，是上百的《圣经》人物。赞叹之余，令人恍若回到了中世纪，圣乐隐隐，不，回到了《旧约》的天地。&lt;br/&gt;&lt;br/&gt;　　耶稣坐像高三米，大于常人。在他脚底，左手扶着希腊字母T形长杖，右手展示“主遣我来”的经卷，须发并茂而头戴光轮，是圣雅各坐在主柱之顶。雅各的雕像较小，只及耶稣的三分之二。在雅各脚下则是一截所谓“基督柱”(ChristologicalColumn)，关系基督学(Christology)至巨。&lt;br/&gt;&lt;br/&gt;　　那是一根白斑岩镌成的石柱，八百年前大师玛窦在上面浮雕的繁富形象，把基督亦圣亦凡的家谱合为一体，以示基督的神性兼人性。柱冠所示乃基督的神性，其形为戴冕之父怀抱圣子，头顶是张翼的白鸽，象征圣灵。柱身则示基督的人性，但见一老者卧地，状若以赛亚，胸口生出一树，枝柯纵横之间人物隐现，可以指认者一为大卫王，手拂竖琴，一为所罗门王，手持权杖，皆为以色列之君。飘飏在树顶的，则是玛丽亚。&lt;br/&gt;&lt;br/&gt;　　那位加利西亚作家正为我们指点基督的种种，又一枇香客涌了进来，参加排队的人群。队排得又长，移得又慢，却轻声笑语而秩序井然。队首的人伸出右手，把五指插入柱上盘错的树根，然后弯腰俯首，用额头去贴靠柱基的雕像，状至虔诚。若是一家人，老老少少也都依次行礼。太小的婴孩，则由母亲抱着把小拳头探人树洞。白发的额头俯磕在柱础上，那样的姿态最令我动心。怀抱信仰、又有生动的仪式可以表达的人，总令人感动，而且羡慕我们的加利西亚朋友笑说：&lt;br/&gt;&lt;br/&gt;　　“这叫做圣徒敲头(SantodosCroques)。”&lt;br/&gt;&lt;br/&gt;　　“什么意思呢?”天恩一面对着行礼的母子照相，那妈妈报他一笑。&lt;br/&gt;&lt;br/&gt;　　“哦，那石像据说是玛窦的自雕像，跟他碰头，可以吸收他的灵感。用手探树根呢，伸进几根指头，就能领受几次神恩。”&lt;br/&gt;&lt;br/&gt;　　五&lt;br/&gt;&lt;br/&gt;　　我和天恩在那小城一连住了七天。只要不开会，两人就走遍城中的斜街窄巷，不是去小馆子吃海鲜饭(paella)、烤鲜奸（gambasalaplancha)，灌以红酒，便是去小店买一些银制的纪念品，例如用那香炉为饰的项链。但我们更常回到那古寺，在四方的奥勃拉兑洛广场徘徊，看持杖来去的真假香客。人来人往，那千年古寺永远矗遮在那里，雨呢总是下下歇歇，伞呢当然也张张收收。一切是那么天长地久，自然而然。&lt;br/&gt;&lt;br/&gt;　　我们很快就进入了情况，把圣雅各之城的一切，无论为圣为凡，都认为当然。街道当然叫rua，不叫road;生菜当然叫ensalada，不叫salad;至于圣雅各，当然不叫St.James而叫Santiago。连佛徒释子如天恩都习以为常了，何况是我呢？台湾太复远了•消息全无。我们蜕去了附身的时空4然，连表都重调过了…像两尾迷路的蠹负、钴游在黑厚而重的《圣经》里。&lt;br/&gt;&lt;br/&gt;　　气候十分凉爽，下雨就更冷了，早晚尤甚，只有摄氏十二度。从北冋归线以南来的，当然珍惜这夏天里的秋天。奇怪的是，街上常常下雨，户内却很收干，不觉潮湿。&lt;br/&gt;&lt;br/&gt;　　加利西亚语其实是西班牙语和葡萄牙语的表亲，对干略识Castellano与Cataldn，并去过巴西的天恩与我，不全陌生。当然不敢奢望如鱼得水，但两人凑合着相濡以沫，还是勉可应付。加以西班牙菜那么对胃，物价又那么便宜，乡人又那么和善可亲，不但夜行无惧，甚至街头也难见啸聚的少年。天恩天真地说：“再给我们两个月，就能吃遍西班牙菜，喝尽加利西亚酒，跟阿米哥们也能谈天说地了。”&lt;br/&gt;&lt;br/&gt;　　临行之晨，风雨凄凄。伊比利亚航空公司的小班机奋翅攀升，再回望时，七月的雨城，千年的古寺，都留在阴云下方了o&lt;br/&gt;&lt;br/&gt;　　一九九二年十一月&lt;br/&gt;&lt;br/&gt;\n \n &lt;/p&gt;', 'http://student.com/data/upload/admin/20181122/5bf6497a96af0.png', '0');
INSERT INTO `h2w_scripture` VALUES ('7', '林语堂简介', '0', '0', '1542868224', '&lt;p&gt;　　林语堂(1895．10．3-1976．3．26)福建龙溪人。原名和乐，后改玉堂，又改语堂。1912年入上海圣约翰大学，毕业后在清华大学任教。1919年秋赴美哈佛大学文学系。1922年获文学硕士学位。同年转赴德国入莱比锡大学，专攻语言学。1923年获博士学位后回国，任北京大学教授、北京女子师范大学教务长和英文系主任。1924年后为《语丝》主要撰稿人之一。1926年到厦门大学任文学院长。1927年任外交部秘书。1932年主编《论语》半月刊。1934年创办《人间世》，1935年创办《宇宙风》，提倡“以自我为中心，以闲适为格凋”的小品文。1935年后，在美国用英文写《吾国与吾民》、《京华烟云》、《风声鹤唳》等文化著作和长篇小说。&lt;br/&gt;&lt;br/&gt;　　1944年曾一度回国到重庆讲学。1945年赴新加坡筹建南洋大学，任校长。1952年在美国与人创办《天风》杂志。1966年定居台湾。1967年受聘为香港中文大学研究教授。1975年被推举为国际笔会副会长。1976年在香港逝世。&lt;/p&gt;&lt;p&gt;&lt;img alt=&quot;林语堂简介&quot; src=&quot;http://www.sanwenji.cn/uploads/allimg/18/1-1P4031910512E.jpg&quot; style=&quot;width: 450px; height: 381px;&quot;/&gt;&lt;/p&gt;&lt;p&gt;　　著作书目：&lt;br/&gt;&lt;br/&gt;　　《剪拂集》(杂文集)1928，北新&lt;br/&gt;&lt;br/&gt;　　《新的文评》(评论集)1930，北新&lt;br/&gt;&lt;br/&gt;　　《语言学论丛》1932，开明&lt;br/&gt;&lt;br/&gt;　　《欧风美语》(散文集)1933，人间&lt;br/&gt;&lt;br/&gt;　　《大荒集》(杂文集)1934，生活&lt;br/&gt;&lt;br/&gt;　　《我的话》(第1卷，杂文集，又名《行素集》)，1934，时代&lt;br/&gt;&lt;br/&gt;　　《我的话》(第2卷，杂文集，又名《拙荆集》)，1936，时代&lt;br/&gt;&lt;br/&gt;　　《林语堂幽默文选》1936．万象&lt;br/&gt;&lt;br/&gt;　　《生活的发见》1938，东京创元社&lt;br/&gt;&lt;br/&gt;　　《新生的中国》1939，林氏出版社&lt;br/&gt;&lt;br/&gt;　　《俚语集》(杂文集)1940，上海朔风书店&lt;br/&gt;&lt;br/&gt;　　《第一流》1941，上海地球出版社&lt;br/&gt;&lt;br/&gt;　　《语堂文存》1941，林氏出版社&lt;br/&gt;&lt;br/&gt;　　《中国圣人》1941，上海朔风书店&lt;br/&gt;&lt;br/&gt;　　《中国文化精神》1941，上海国风书店&lt;br/&gt;&lt;br/&gt;　　《讽颂集》蒋旗译，1941，国华编译社&lt;br/&gt;&lt;br/&gt;　　《爱与刺》1941，明日出版社&lt;br/&gt;&lt;br/&gt;　　《锦秀集》1941，上海朔风书店&lt;br/&gt;&lt;br/&gt;　　《生活的艺术》1941，上海西风社&lt;br/&gt;&lt;br/&gt;　　《有不斋文集》(杂文集)1941，人文书店&lt;br/&gt;&lt;br/&gt;　　《雅人雅事》(杂文集)1941，上海一流书店&lt;br/&gt;&lt;br/&gt;　　《语堂随笔》1941，上海人间出版社&lt;br/&gt;&lt;br/&gt;　　《拨荆集》(杂文集)1941，香港光华出版社&lt;br/&gt;&lt;br/&gt;　　《瞬息京华》(长篇小说，又名《京华烟云》)张振玉译，1940，上海若干出版社&lt;br/&gt;&lt;br/&gt;　　《文人画像》1947，上海金屋书店&lt;br/&gt;&lt;br/&gt;　　《啼笑皆非》1947(5版)，商务&lt;br/&gt;&lt;br/&gt;　　《林语堂散文集》1954，香港世界文摘出版社&lt;br/&gt;&lt;br/&gt;　　《无所不谈》(1一2集，杂文集)1969，文星书局；1—3合集，1974，开明&lt;br/&gt;&lt;br/&gt;　　《平心论高鄂》(杂文集)1966，文星书局&lt;br/&gt;&lt;br/&gt;　　《语堂文集》1978，开明&lt;br/&gt;&lt;br/&gt;　　《林语堂经典名著》(1—35卷)1986，台湾金兰文化出版社&lt;br/&gt;&lt;br/&gt;　　《文人剪影》(散文集)与人合集，1986，重庆人民出版社&lt;br/&gt;&lt;br/&gt;　　《中国人》(杂文集)1988，浙江人民&lt;br/&gt;&lt;br/&gt;　　《赖柏英》(长篇小说)1988，湖南文艺&lt;br/&gt;&lt;br/&gt;　　《人生的盛宴》(散文集)1988，湖南文艺&lt;br/&gt;&lt;br/&gt;&lt;br/&gt;　　&lt;/p&gt;', 'http://student.com/data/upload/admin/20181122/5bf64cefc4310.jpg', '1');

-- ----------------------------
-- Table structure for h2w_slide
-- ----------------------------
DROP TABLE IF EXISTS `h2w_slide`;
CREATE TABLE `h2w_slide` (
  `slide_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `slide_cid` int(11) NOT NULL COMMENT '幻灯片分类 id',
  `slide_name` varchar(255) NOT NULL COMMENT '幻灯片名称',
  `slide_pic` varchar(255) DEFAULT NULL COMMENT '幻灯片图片',
  `slide_url` varchar(255) DEFAULT NULL COMMENT '幻灯片链接',
  `slide_des` varchar(255) DEFAULT NULL COMMENT '幻灯片描述',
  `slide_content` text COMMENT '幻灯片内容',
  `slide_status` int(2) NOT NULL DEFAULT '1' COMMENT '状态，1显示，0不显示',
  `listorder` int(10) DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`slide_id`),
  KEY `slide_cid` (`slide_cid`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='幻灯片表';

-- ----------------------------
-- Records of h2w_slide
-- ----------------------------
INSERT INTO `h2w_slide` VALUES ('1', '0', '123123', 'admin/20181121/5bf4ed9e4ada8.png', '123213', '123123', '123123', '0', '0');
INSERT INTO `h2w_slide` VALUES ('2', '0', '阿萨德阿萨德阿萨德', 'admin/20181122/5bf65152a2030.png', 'http://123123123', '123123123213123123', null, '0', '0');
INSERT INTO `h2w_slide` VALUES ('3', '0', '百度', 'admin/20181127/5bfcb45f16da0.jpg', 'www.baidu.com', '', null, '1', '0');
INSERT INTO `h2w_slide` VALUES ('4', '0', 'tianmao', 'admin/20181127/5bfcb4e98fb38.png', '', '', null, '1', '0');
INSERT INTO `h2w_slide` VALUES ('5', '0', '京东', 'admin/20181127/5bfcb4f743878.jpg', '', '', null, '1', '0');

-- ----------------------------
-- Table structure for h2w_slide_cat
-- ----------------------------
DROP TABLE IF EXISTS `h2w_slide_cat`;
CREATE TABLE `h2w_slide_cat` (
  `cid` int(11) NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(255) NOT NULL COMMENT '幻灯片分类',
  `cat_idname` varchar(255) NOT NULL COMMENT '幻灯片分类标识',
  `cat_remark` text COMMENT '分类备注',
  `cat_status` int(2) NOT NULL DEFAULT '1' COMMENT '状态，1显示，0不显示',
  PRIMARY KEY (`cid`),
  KEY `cat_idname` (`cat_idname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='幻灯片分类表';

-- ----------------------------
-- Records of h2w_slide_cat
-- ----------------------------

-- ----------------------------
-- Table structure for h2w_terms
-- ----------------------------
DROP TABLE IF EXISTS `h2w_terms`;
CREATE TABLE `h2w_terms` (
  `term_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '分类id',
  `name` varchar(200) DEFAULT NULL COMMENT '分类名称',
  `slug` varchar(200) DEFAULT '',
  `taxonomy` varchar(32) DEFAULT NULL COMMENT '分类类型',
  `description` longtext COMMENT '分类描述',
  `parent` bigint(20) unsigned DEFAULT '0' COMMENT '分类父id',
  `count` bigint(20) DEFAULT '0' COMMENT '分类文章数',
  `path` varchar(500) DEFAULT NULL COMMENT '分类层级关系路径',
  `seo_title` varchar(500) DEFAULT NULL,
  `seo_keywords` varchar(500) DEFAULT NULL,
  `seo_description` varchar(500) DEFAULT NULL,
  `list_tpl` varchar(50) DEFAULT NULL COMMENT '分类列表模板',
  `one_tpl` varchar(50) DEFAULT NULL COMMENT '分类文章页模板',
  `listorder` int(5) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` int(2) NOT NULL DEFAULT '1' COMMENT '状态，1发布，0不发布',
  PRIMARY KEY (`term_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='Portal 文章分类表';

-- ----------------------------
-- Records of h2w_terms
-- ----------------------------
INSERT INTO `h2w_terms` VALUES ('1', '列表演示', '', 'article', '', '0', '0', '0-1', '', '', '', 'list', 'article', '0', '1');
INSERT INTO `h2w_terms` VALUES ('2', '瀑布流', '', 'article', '', '0', '0', '0-2', '', '', '', 'list_masonry', 'article', '0', '1');

-- ----------------------------
-- Table structure for h2w_term_relationships
-- ----------------------------
DROP TABLE IF EXISTS `h2w_term_relationships`;
CREATE TABLE `h2w_term_relationships` (
  `tid` bigint(20) NOT NULL AUTO_INCREMENT,
  `object_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'posts表里文章id',
  `term_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '分类id',
  `listorder` int(10) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` int(2) NOT NULL DEFAULT '1' COMMENT '状态，1发布，0不发布',
  PRIMARY KEY (`tid`),
  KEY `term_taxonomy_id` (`term_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Portal 文章分类对应表';

-- ----------------------------
-- Records of h2w_term_relationships
-- ----------------------------

-- ----------------------------
-- Table structure for h2w_users
-- ----------------------------
DROP TABLE IF EXISTS `h2w_users`;
CREATE TABLE `h2w_users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_login` varchar(60) NOT NULL DEFAULT '' COMMENT '用户名',
  `user_pass` varchar(64) NOT NULL DEFAULT '' COMMENT '登录密码；sp_password加密',
  `user_nicename` varchar(50) NOT NULL DEFAULT '' COMMENT '用户美名',
  `user_name` varchar(255) DEFAULT '' COMMENT '真实姓名',
  `user_email` varchar(100) NOT NULL DEFAULT '' COMMENT '登录邮箱',
  `user_url` varchar(100) NOT NULL DEFAULT '' COMMENT '用户个人网站',
  `avatar` varchar(255) DEFAULT NULL COMMENT '用户头像，相对于upload/avatar目录',
  `sex` smallint(1) DEFAULT '0' COMMENT '性别；0：保密，1：男；2：女',
  `birthday` date DEFAULT '2000-01-01' COMMENT '生日',
  `signature` varchar(255) DEFAULT NULL COMMENT '个性签名',
  `last_login_ip` varchar(16) DEFAULT NULL COMMENT '最后登录ip',
  `last_login_time` datetime NOT NULL DEFAULT '2000-01-01 00:00:00' COMMENT '最后登录时间',
  `create_time` datetime NOT NULL DEFAULT '2000-01-01 00:00:00' COMMENT '注册时间',
  `user_activation_key` varchar(60) NOT NULL DEFAULT '' COMMENT '激活码',
  `user_status` int(11) NOT NULL DEFAULT '1' COMMENT '用户状态 0：禁用； 1：正常 ；2：未验证',
  `score` int(11) NOT NULL DEFAULT '0' COMMENT '用户积分',
  `user_type` smallint(1) DEFAULT '1' COMMENT '用户类型，1:admin ;2:学生 3家长',
  `coin` int(11) NOT NULL DEFAULT '0' COMMENT '金币',
  `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '手机号',
  `school_id` int(11) DEFAULT NULL COMMENT '学校ID',
  `specialty_id` int(11) DEFAULT NULL,
  `verify` tinyint(1) NOT NULL DEFAULT '0' COMMENT '认证状态 0未认证 1已认证',
  `online_time` float(11,2) NOT NULL DEFAULT '0.00' COMMENT '累积在线时长',
  PRIMARY KEY (`id`),
  KEY `user_login_key` (`user_login`),
  KEY `user_nicename` (`user_nicename`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='用户表';

-- ----------------------------
-- Records of h2w_users
-- ----------------------------
INSERT INTO `h2w_users` VALUES ('1', 'admin', '###a8d4b4bcd77826ff84ca126241fc5be3', 'admin', '夏木', '123456@qq.com', '', null, '1', '2000-01-01', '', '127.0.0.1', '2018-11-27 08:26:04', '2018-11-19 04:55:56', '', '1', '0', '1', '0', '', null, null, '0', '0.00');
INSERT INTO `h2w_users` VALUES ('2', 'baomihua', '###a8d4b4bcd77826ff84ca126241fc5be3', '爆米花', '爆米花', 'baomihua@qq.com', '', null, '0', '2000-01-01', null, '127.0.0.1', '2018-11-23 09:17:39', '2018-11-23 00:00:00', '', '1', '0', '2', '0', '17766663333', '1', '1', '0', '20.00');
INSERT INTO `h2w_users` VALUES ('3', '爱的', '###1e11881b9d94c9663fc04c1c388d4f57', '', '123', '', '', null, '0', '2000-01-01', null, null, '2000-01-01 00:00:00', '2018-11-23 13:48:44', '', '1', '0', '1', '0', '13012', null, null, '0', '0.00');
INSERT INTO `h2w_users` VALUES ('4', 'admin2', '###1e11881b9d94c9663fc04c1c388d4f57', '', '晓宏', '', '', null, '0', '2000-01-01', null, null, '2000-01-01 00:00:00', '2018-11-23 14:13:42', '', '1', '0', '1', '0', '13500000000', null, null, '0', '0.00');

-- ----------------------------
-- Table structure for h2w_user_favorites
-- ----------------------------
DROP TABLE IF EXISTS `h2w_user_favorites`;
CREATE TABLE `h2w_user_favorites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) DEFAULT NULL COMMENT '用户 id',
  `title` varchar(255) DEFAULT NULL COMMENT '收藏内容的标题',
  `url` varchar(255) DEFAULT NULL COMMENT '收藏内容的原文地址，不带域名',
  `description` varchar(500) DEFAULT NULL COMMENT '收藏内容的描述',
  `table` varchar(50) DEFAULT NULL COMMENT '收藏实体以前所在表，不带前缀',
  `object_id` int(11) DEFAULT NULL COMMENT '收藏内容原来的主键id',
  `createtime` int(11) DEFAULT NULL COMMENT '收藏时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户收藏表';

-- ----------------------------
-- Records of h2w_user_favorites
-- ----------------------------
