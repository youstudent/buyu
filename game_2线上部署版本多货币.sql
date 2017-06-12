/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 50635
 Source Host           : 192.168.2.222
 Source Database       : game_2

 Target Server Type    : MySQL
 Target Server Version : 50635
 File Encoding         : utf-8

 Date: 05/02/2017 13:14:37 PM
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `g_agency`
-- ----------------------------
DROP TABLE IF EXISTS `g_agency`;
CREATE TABLE `g_agency` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) unsigned DEFAULT '0' COMMENT '上级代理的ID',
  `phone` varchar(12) DEFAULT NULL COMMENT '手机号码',
  `password` varchar(64) DEFAULT NULL COMMENT '代理商密码',
  `name` varchar(32) DEFAULT NULL COMMENT '代理商姓名',
  `reg_time` int(11) unsigned DEFAULT NULL COMMENT '注册时间',
  `gold` decimal(11,2) unsigned DEFAULT NULL COMMENT '剩余金币',
  `gold_all` int(11) unsigned DEFAULT NULL COMMENT '消费总金币',
  `identity` varchar(32) DEFAULT NULL COMMENT '身份证号码',
  `status` tinyint(3) unsigned DEFAULT '1' COMMENT '状态1:正常2:封停3:审核中4：审核未通过',
  `code` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '推荐码',
  `manage_id` int(11) DEFAULT NULL COMMENT '添加人id',
  `manage_name` varchar(32) DEFAULT NULL COMMENT '添加人姓名',
  `rebate` decimal(10,2) DEFAULT '0.00' COMMENT '返佣总计',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8 COMMENT='代理商表';

-- ----------------------------
--  Records of `g_agency`
-- ----------------------------
BEGIN;
INSERT INTO `g_agency` VALUES ('1', '0', '平台', '平台', '平台', null, null, null, null, '1', '0', null, null, '0.00'), ('30', '0', '15982707139', '123456789', '曹双', '1493092913', '0.00', '0', '513722199702046123', '1', '686616', '1', 'lrdouble', '0.00');
COMMIT;

-- ----------------------------
--  Table structure for `g_agency_deduct`
-- ----------------------------
DROP TABLE IF EXISTS `g_agency_deduct`;
CREATE TABLE `g_agency_deduct` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agency_id` int(11) unsigned DEFAULT NULL COMMENT '代理商ID',
  `name` varchar(32) DEFAULT NULL COMMENT '代理商姓名',
  `time` int(11) unsigned DEFAULT NULL COMMENT '添加时间',
  `gold` decimal(11,2) unsigned DEFAULT NULL COMMENT '扣除金额',
  `money` decimal(10,2) unsigned DEFAULT NULL COMMENT '收款人民币',
  `notes` text COMMENT '备注',
  `status` tinyint(3) unsigned DEFAULT NULL COMMENT '状态1:代审核2:已完成3:拒绝',
  `manage_id` int(11) DEFAULT NULL COMMENT '添加人id',
  `manage_name` varchar(32) DEFAULT NULL COMMENT '添加人姓名',
  `gold_config` varchar(32) DEFAULT NULL COMMENT '消费类型',
  PRIMARY KEY (`id`),
  KEY `agency_id` (`agency_id`),
  CONSTRAINT `g_agency_deduct_ibfk_1` FOREIGN KEY (`agency_id`) REFERENCES `g_agency` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='代理商购买记录表';

-- ----------------------------
--  Records of `g_agency_deduct`
-- ----------------------------
BEGIN;
INSERT INTO `g_agency_deduct` VALUES ('9', '30', '曹双', '1493096347', '9000.00', null, '0', '2', '1', 'lrdouble', '房卡'), ('10', '30', '曹双', '1493096431', '100000.00', null, '0', '2', '1', 'lrdouble', '房卡');
COMMIT;

-- ----------------------------
--  Table structure for `g_agency_gold`
-- ----------------------------
DROP TABLE IF EXISTS `g_agency_gold`;
CREATE TABLE `g_agency_gold` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `agency_id` int(11) unsigned NOT NULL COMMENT '用户ID',
  `gold_config` varchar(32) DEFAULT NULL COMMENT '充值类型',
  `gold` decimal(12,2) DEFAULT NULL COMMENT '充值金额',
  `sum_gold` decimal(10,2) DEFAULT NULL COMMENT '总计消费',
  PRIMARY KEY (`id`),
  KEY `users_id` (`agency_id`),
  KEY `gold_config` (`gold_config`),
  CONSTRAINT `g_agency_gold_ibfk_2` FOREIGN KEY (`gold_config`) REFERENCES `g_gold_config` (`name`),
  CONSTRAINT `g_agency_gold_ibfk_3` FOREIGN KEY (`agency_id`) REFERENCES `g_agency` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `g_agency_gold`
-- ----------------------------
BEGIN;
INSERT INTO `g_agency_gold` VALUES ('10', '30', '房卡', '1000.00', '1000.00'), ('11', '30', '金币', '10000.00', '10000.00');
COMMIT;

-- ----------------------------
--  Table structure for `g_agency_pay`
-- ----------------------------
DROP TABLE IF EXISTS `g_agency_pay`;
CREATE TABLE `g_agency_pay` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agency_id` int(11) unsigned DEFAULT NULL COMMENT '代理商ID',
  `name` varchar(32) DEFAULT NULL COMMENT '代理商姓名',
  `time` int(11) unsigned DEFAULT NULL COMMENT '添加时间',
  `gold` int(11) unsigned DEFAULT NULL COMMENT '充值金币数量',
  `money` decimal(10,2) unsigned DEFAULT NULL COMMENT '收款人民币',
  `notes` text COMMENT '备注',
  `status` tinyint(3) unsigned DEFAULT NULL COMMENT '状态1:代充值2:已完成3:拒绝',
  `manage_id` int(11) DEFAULT NULL COMMENT '添加人id',
  `manage_name` varchar(32) DEFAULT NULL COMMENT '添加人姓名',
  `gold_config` varchar(32) DEFAULT NULL COMMENT '充值类型',
  PRIMARY KEY (`id`),
  KEY `agency_id` (`agency_id`),
  CONSTRAINT `g_agency_pay_ibfk_1` FOREIGN KEY (`agency_id`) REFERENCES `g_agency` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='代理商购买记录表';

-- ----------------------------
--  Records of `g_agency_pay`
-- ----------------------------
BEGIN;
INSERT INTO `g_agency_pay` VALUES ('35', '30', '曹双', '1493092941', '10', '0.00', '', '2', '1', 'lrdouble', '房卡'), ('36', '30', '曹双', '1493096234', '10000', '0.00', '', '2', '1', 'lrdouble', '房卡'), ('37', '30', '曹双', '1493096249', '10000', '0.00', '', '2', '1', 'lrdouble', '金币'), ('38', '30', '曹双', '1493096391', '100000', '0.00', '', '2', '1', 'lrdouble', '房卡');
COMMIT;

-- ----------------------------
--  Table structure for `g_game_exploits`
-- ----------------------------
DROP TABLE IF EXISTS `g_game_exploits`;
CREATE TABLE `g_game_exploits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '玩家用户ID',
  `game_id` int(11) DEFAULT NULL COMMENT '游戏数据库ID',
  `nickname` varchar(32) DEFAULT NULL COMMENT '玩家昵称',
  `time` int(11) unsigned DEFAULT '0' COMMENT '充值时间',
  `gold` int(11) DEFAULT '0' COMMENT '获得的积分',
  `game_class` varchar(32) DEFAULT NULL COMMENT '游戏类型',
  `status` tinyint(3) unsigned DEFAULT '1' COMMENT '状态1:成功 0:失败',
  `notes` varchar(255) DEFAULT NULL COMMENT '战绩详情',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `game_class` (`game_class`),
  CONSTRAINT `g_game_exploits_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `g_users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='玩家战绩表';

-- ----------------------------
--  Records of `g_game_exploits`
-- ----------------------------
BEGIN;
INSERT INTO `g_game_exploits` VALUES ('1', '52', '6543211', 'lrdouble', '1493094941', '0', '家庭麻将', '1', '四人麻将胜利'), ('2', '58', '50002', 'abc', '1493106002', '1', '宜宾麻将', '1', '赢'), ('3', '58', '50002', 'abc', '1493106003', '1', '宜宾麻将', '1', '赢');
COMMIT;

-- ----------------------------
--  Table structure for `g_gold_config`
-- ----------------------------
DROP TABLE IF EXISTS `g_gold_config`;
CREATE TABLE `g_gold_config` (
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '支付类型',
  `type` int(11) DEFAULT NULL COMMENT '1:数值2:时间',
  `num_code` int(11) DEFAULT NULL COMMENT 'APICode使用',
  `en_code` varchar(32) DEFAULT NULL COMMENT 'APICODE使用',
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户支付类型表';

-- ----------------------------
--  Records of `g_gold_config`
-- ----------------------------
BEGIN;
INSERT INTO `g_gold_config` VALUES ('房卡', '1', '101', 'fk'), ('金币', '1', '102', 'gold');
COMMIT;

-- ----------------------------
--  Table structure for `g_manage`
-- ----------------------------
DROP TABLE IF EXISTS `g_manage`;
CREATE TABLE `g_manage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) DEFAULT NULL COMMENT '管理员姓名',
  `password` varchar(64) DEFAULT NULL COMMENT '管理员密码',
  `phone` varchar(12) DEFAULT NULL COMMENT '管理员手机号',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='管理员表';

-- ----------------------------
--  Records of `g_manage`
-- ----------------------------
BEGIN;
INSERT INTO `g_manage` VALUES ('1', 'lrdouble', '25f9e794323b453885f5181f1b624d0b', null);
COMMIT;

-- ----------------------------
--  Table structure for `g_notice`
-- ----------------------------
DROP TABLE IF EXISTS `g_notice`;
CREATE TABLE `g_notice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `manage_id` int(11) DEFAULT NULL COMMENT '添加人id',
  `manage_name` varchar(32) DEFAULT NULL COMMENT '添加人姓名',
  `title` varchar(64) DEFAULT NULL COMMENT '通知标题',
  `content` text COMMENT '通知内容',
  `status` tinyint(3) unsigned DEFAULT NULL COMMENT '1显示 2隐藏',
  `time` int(11) unsigned DEFAULT NULL COMMENT '添加时间',
  `notes` varchar(255) DEFAULT NULL COMMENT '添加的备注',
  `location` varchar(255) DEFAULT NULL COMMENT '显示位置',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='公告通知数据库';

-- ----------------------------
--  Records of `g_notice`
-- ----------------------------
BEGIN;
INSERT INTO `g_notice` VALUES ('4', '1', 'lrdouble', '通知通知', '	欢迎来到XXX麻将。做代理请联系微信15982707139。买卡请联系1210783098。祝大家玩的愉快。', '1', '1492067668', '没备注', '首页公告'), ('5', '1', 'lrdouble', '首页滚动公告', '首页滚动公告首页滚动公告首页滚动公告首页滚动公告首页滚动公告', '1', '1493102442', '首页滚动公告', '首页滚动公告'), ('6', '1', 'lrdouble', '房间滚动公告', '房间滚动公告房间滚动公告房间滚动公告', '1', '1493102458', '房间滚动公告', '房间滚动公告');
COMMIT;

-- ----------------------------
--  Table structure for `g_rebate`
-- ----------------------------
DROP TABLE IF EXISTS `g_rebate`;
CREATE TABLE `g_rebate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agency_pay_id` int(11) NOT NULL DEFAULT '0' COMMENT '充值记录的ID',
  `agency_pay_name` varchar(32) DEFAULT NULL COMMENT '充值代理人名称',
  `agency_pay_user_id` int(11) NOT NULL DEFAULT '0' COMMENT '充值用户的ID',
  `agency_id` int(11) unsigned DEFAULT NULL COMMENT '受利益人ID',
  `agency_name` varchar(32) DEFAULT NULL COMMENT '受利益人',
  `gold_num` decimal(11,2) DEFAULT NULL COMMENT '返钻数量',
  `notes` varchar(255) DEFAULT NULL COMMENT '备注',
  `time` int(11) unsigned DEFAULT NULL COMMENT '操作时间',
  `rebate_conf` varchar(11) DEFAULT NULL COMMENT '返回佣金登记',
  `proportion` int(11) DEFAULT NULL COMMENT '返佣比例',
  PRIMARY KEY (`id`),
  KEY `rebate_conf` (`rebate_conf`),
  KEY `agency_id` (`agency_id`),
  CONSTRAINT `g_rebate_ibfk_2` FOREIGN KEY (`agency_id`) REFERENCES `g_agency` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='返利表';

-- ----------------------------
--  Table structure for `g_rebate_conf`
-- ----------------------------
DROP TABLE IF EXISTS `g_rebate_conf`;
CREATE TABLE `g_rebate_conf` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `one` int(11) unsigned NOT NULL COMMENT '返利比例',
  `two` int(11) NOT NULL,
  `three` int(11) NOT NULL,
  `sum` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='返利配置表';

-- ----------------------------
--  Records of `g_rebate_conf`
-- ----------------------------
BEGIN;
INSERT INTO `g_rebate_conf` VALUES ('1', '5', '3', '2', '100');
COMMIT;

-- ----------------------------
--  Table structure for `g_user_out`
-- ----------------------------
DROP TABLE IF EXISTS `g_user_out`;
CREATE TABLE `g_user_out` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '玩家用户ID',
  `game_id` int(11) unsigned DEFAULT NULL COMMENT '游戏数据库ID',
  `nickname` varchar(32) DEFAULT NULL COMMENT '玩家昵称',
  `time` int(11) unsigned DEFAULT '0' COMMENT '消费时间',
  `gold` int(11) unsigned DEFAULT NULL COMMENT '消费金币数量',
  `game_class` varchar(32) DEFAULT NULL COMMENT '消费类型',
  `notes` varchar(255) DEFAULT NULL COMMENT '消费详情',
  `gold_config` varchar(32) NOT NULL DEFAULT '' COMMENT '消费类型',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `game_class` (`game_class`),
  CONSTRAINT `g_user_out_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `g_users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8 COMMENT='玩家消费表';

-- ----------------------------
--  Records of `g_user_out`
-- ----------------------------
BEGIN;
INSERT INTO `g_user_out` VALUES ('27', '52', '6543211', 'lrdouble', '1493094676', '2', '家庭麻将', '四人麻将开房费用', ''), ('28', '52', '6543211', 'lrdouble', '1493094722', '2', '家庭麻将', '四人麻将开房费用', ''), ('29', '52', '6543211', 'lrdouble', '1493094767', '2', '家庭麻将', '四人麻将开房费用', ''), ('30', '52', '6543211', 'lrdouble', '1493094808', '2', '家庭麻将', '四人麻将开房费用', '房卡'), ('31', '52', '6543211', 'lrdouble', '1493094825', '2', '家庭麻将', '四人麻将开房费用', '金币'), ('32', '52', '6543211', 'lrdouble', '1493094839', '8', '家庭麻将', '四人麻将开房费用', '金币'), ('33', '52', '6543211', 'lrdouble', '1493094846', '10', '家庭麻将', '四人麻将开房费用', '金币');
COMMIT;

-- ----------------------------
--  Table structure for `g_user_pay`
-- ----------------------------
DROP TABLE IF EXISTS `g_user_pay`;
CREATE TABLE `g_user_pay` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agency_id` int(11) unsigned DEFAULT NULL COMMENT '代理商ID',
  `agency_name` varchar(32) DEFAULT NULL COMMENT '代理商名称',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '玩家用户ID',
  `game_id` int(11) DEFAULT NULL COMMENT '游戏数据库ID',
  `nickname` varchar(32) DEFAULT NULL COMMENT '玩家昵称',
  `time` int(11) unsigned DEFAULT '0' COMMENT '充值时间',
  `gold` int(11) unsigned DEFAULT NULL COMMENT '充值金币数量',
  `money` decimal(10,2) unsigned DEFAULT NULL COMMENT '收款人民币',
  `status` tinyint(3) unsigned DEFAULT '1' COMMENT '状态1:成功 0:失败',
  `gold_config` varchar(32) DEFAULT NULL COMMENT '充值类型',
  PRIMARY KEY (`id`),
  KEY `agency_id` (`agency_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `g_user_pay_ibfk_1` FOREIGN KEY (`agency_id`) REFERENCES `g_agency` (`id`),
  CONSTRAINT `g_user_pay_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `g_users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=utf8 COMMENT='玩家充值表';

-- ----------------------------
--  Records of `g_user_pay`
-- ----------------------------
BEGIN;
INSERT INTO `g_user_pay` VALUES ('66', '1', '平台', '52', '6543211', 'lrdouble', '1493094212', '10', '10.00', '1', '房卡'), ('67', '1', '平台', '52', '6543211', 'lrdouble', '1493094228', '10', '10.00', '1', '金币'), ('68', '1', '平台', '52', '6543211', 'lrdouble', '1493094237', '10', '10.00', '1', '金币'), ('69', '1', '平台', '52', '6543211', 'lrdouble', '1493094248', '10', '10.00', '1', '房卡'), ('70', '30', '曹双', '52', '6543211', 'lrdouble', '1493095278', '10', '10.00', '1', null), ('71', '30', '曹双', '52', '6543211', 'lrdouble', '1493095613', '10', '0.00', '1', null), ('72', '30', '曹双', '52', '6543211', 'lrdouble', '1493095633', '10', '0.00', '1', null), ('73', '30', '曹双', '52', '6543211', 'lrdouble', '1493095831', '10', '10.00', '1', '房卡'), ('74', '1', '平台', '52', '6543211', 'lrdouble', '1493103774', '10', '10.00', '1', '金币'), ('75', '1', '平台', '52', '6543211', 'lrdouble', '1493103774', '10', '10.00', '1', '金币'), ('76', '1', '平台', '52', '6543211', 'lrdouble', '1493103774', '10', '10.00', '1', '金币'), ('77', '1', '平台', '52', '6543211', 'lrdouble', '1493103774', '10', '10.00', '1', '金币'), ('78', '1', '平台', '52', '6543211', 'lrdouble', '1493103775', '10', '10.00', '1', '金币'), ('79', '1', '平台', '52', '6543211', 'lrdouble', '1493103775', '10', '10.00', '1', '金币'), ('80', '1', '平台', '52', '6543211', 'lrdouble', '1493103775', '10', '10.00', '1', '金币'), ('81', '1', '平台', '52', '6543211', 'lrdouble', '1493103775', '10', '10.00', '1', '金币'), ('82', '1', '平台', '52', '6543211', 'lrdouble', '1493103775', '10', '10.00', '1', '金币'), ('83', '1', '平台', '52', '6543211', 'lrdouble', '1493103776', '10', '10.00', '1', '金币'), ('84', '1', '平台', '57', '123321', 'abc', '1493103787', '10', '10.00', '1', '金币'), ('85', '1', '平台', '57', '123321', 'abc', '1493103876', '10', '0.00', '1', '房卡'), ('86', '1', '平台', '57', '123321', 'abc', '1493103897', '10', '0.00', '1', '房卡'), ('87', '1', '平台', '58', '50002', 'abc', '1493104216', '10', '10.00', '1', '房卡'), ('88', '1', '平台', '58', '50002', 'abc', '1493104787', '10', '0.00', '1', '房卡');
COMMIT;

-- ----------------------------
--  Table structure for `g_users`
-- ----------------------------
DROP TABLE IF EXISTS `g_users`;
CREATE TABLE `g_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` int(11) DEFAULT NULL COMMENT '游戏数据的ＩＤ',
  `nickname` varchar(32) DEFAULT NULL COMMENT '玩家昵称',
  `autograph` varchar(255) DEFAULT NULL COMMENT '个性签名',
  `phone` varchar(12) DEFAULT NULL COMMENT '手机号码',
  `gold` int(10) unsigned DEFAULT NULL COMMENT '剩余房卡数量',
  `gold_all` int(11) unsigned DEFAULT NULL COMMENT '总计消费房卡数量',
  `reg_time` int(11) unsigned DEFAULT NULL COMMENT '注册时间',
  `game_count` int(11) unsigned DEFAULT '0' COMMENT '游戏总局数',
  `head` varchar(255) DEFAULT NULL COMMENT '用户头像地址http://www.badiu.com/1.jpg',
  `status` tinyint(3) unsigned DEFAULT '1' COMMENT '状态 1:启用中 0:已封停',
  PRIMARY KEY (`id`),
  UNIQUE KEY `111` (`game_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8 COMMENT='玩家表';

-- ----------------------------
--  Records of `g_users`
-- ----------------------------
BEGIN;
INSERT INTO `g_users` VALUES ('52', '6543211', 'lrdouble', '没有个性无签名', '15982707139', '50', '50', '1493093974', '0', 'http://www.zcool.com.cn/img.html#src=http://img.zcool.cn/community/01620258f38ae4a8012049ef5eefd1.jpg', '1'), ('53', '6543212', 'lrdouble', '没有个性无签名', '15982707139', '20', '20', '1493094267', '0', 'http://www.zcool.com.cn/img.html#src=http://img.zcool.cn/community/01620258f38ae4a8012049ef5eefd1.jpg', '1'), ('54', '6543213', 'lrdouble', '没有个性无签名', '15982707139', '20', '20', '1493094294', '0', 'http://www.zcool.com.cn/img.html#src=http://img.zcool.cn/community/01620258f38ae4a8012049ef5eefd1.jpg', '1'), ('55', '6543214', 'lrdouble', '没有个性无签名', '15982707139', '20', '20', '1493094331', '0', 'http://www.zcool.com.cn/img.html#src=http://img.zcool.cn/community/01620258f38ae4a8012049ef5eefd1.jpg', '1'), ('56', '6543215', 'lrdouble', '没有个性无签名', '15982707139', '30', '30', '1493094343', '0', 'http://www.zcool.com.cn/img.html#src=http://img.zcool.cn/community/01620258f38ae4a8012049ef5eefd1.jpg', '1'), ('57', '123321', 'abc', null, null, '1', '1', '1493100167', '0', '\'\'', '1'), ('58', '50002', 'abc', null, null, '1', '1', '1493104021', '0', 'http://s-347513.gotocdn.com/resources/images/oxkBowqcM208hTm191ukvBIAEqkg.jpg', '1'), ('59', '50003', 'ri', null, null, '1000', '1000', '1493105166', '0', 'http://s-347513.gotocdn.com/resources/images/oxkBowkHOBVLOQZqD8TBcZ2w2vsA.jpg', '1');
COMMIT;

-- ----------------------------
--  Table structure for `g_users_gold`
-- ----------------------------
DROP TABLE IF EXISTS `g_users_gold`;
CREATE TABLE `g_users_gold` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `users_id` int(11) DEFAULT NULL COMMENT '用户ID',
  `gold_config` varchar(32) DEFAULT NULL COMMENT '充值类型',
  `gold` decimal(12,2) DEFAULT NULL COMMENT '充值金额',
  `sum_gold` decimal(10,2) DEFAULT NULL COMMENT '总计消费',
  PRIMARY KEY (`id`),
  KEY `users_id` (`users_id`),
  KEY `gold_config` (`gold_config`),
  KEY `gold_config_2` (`gold_config`),
  CONSTRAINT `g_users_gold_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `g_users` (`id`),
  CONSTRAINT `g_users_gold_ibfk_2` FOREIGN KEY (`gold_config`) REFERENCES `g_gold_config` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `g_users_gold`
-- ----------------------------
BEGIN;
INSERT INTO `g_users_gold` VALUES ('4', '52', '房卡', '20.00', '30.00'), ('5', '52', '金币', '100.00', '120.00'), ('6', '53', '房卡', '0.00', '0.00'), ('7', '53', '金币', '0.00', '0.00'), ('8', '54', '房卡', '0.00', '0.00'), ('9', '54', '金币', '0.00', '0.00'), ('10', '55', '房卡', '20.00', '20.00'), ('11', '55', '金币', '20.00', '20.00'), ('12', '56', '房卡', '20.00', '20.00'), ('13', '56', '金币', '30.00', '30.00'), ('14', '57', '房卡', '120.00', '120.00'), ('15', '57', '金币', '11.00', '11.00'), ('16', '58', '房卡', '120.00', '120.00'), ('17', '58', '金币', '1.00', '1.00'), ('18', '59', '房卡', '100.00', '100.00'), ('19', '59', '金币', '1000.00', '1000.00');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
