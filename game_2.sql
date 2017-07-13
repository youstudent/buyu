/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : game_2

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2017-07-13 15:56:34
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for g_agency
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
  `detail` text COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 COMMENT='代理商表';

-- ----------------------------
-- Records of g_agency
-- ----------------------------
INSERT INTO `g_agency` VALUES ('1', '0', '平台', '平台', '平台', null, null, null, null, '1', '0', null, null, '0.00', null);
INSERT INTO `g_agency` VALUES ('30', '0', '15982707139', '123456789', '曹双', '1493092913', '0.00', '0', '513722199702046126', '2', '686616', '1', 'lrdouble', '0.00', '4444');
INSERT INTO `g_agency` VALUES ('31', '0', '13219890986', '199519', '刘玉', '1497259494', '100.00', '0', '510322199508223818', '1', '243426', '1', 'lrdouble', '0.00', null);
INSERT INTO `g_agency` VALUES ('32', '0', '13219890984', '545252', '510322199508223818', '1497259570', '0.00', '0', '510322199508223818', '1', '710894', '1', 'lrdouble', '0.00', '沙发斯蒂芬');

-- ----------------------------
-- Table structure for g_agency_deduct
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
  `type` int(10) DEFAULT NULL COMMENT '1: 扣除代理   2: 扣除玩家的',
  `phone` int(13) DEFAULT NULL COMMENT '电话',
  PRIMARY KEY (`id`),
  KEY `agency_id` (`agency_id`),
  CONSTRAINT `g_agency_deduct_ibfk_1` FOREIGN KEY (`agency_id`) REFERENCES `g_agency` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='代理商购买记录表';

-- ----------------------------
-- Records of g_agency_deduct
-- ----------------------------
INSERT INTO `g_agency_deduct` VALUES ('9', '30', '曹双', '1493096347', '9000.00', null, '0', '2', '1', 'lrdouble', '房卡', '1', '1321989098');
INSERT INTO `g_agency_deduct` VALUES ('10', '30', '曹双2', '1494096431', '100000.00', null, '0', '2', '1', 'lrdouble', '房卡', '2', null);

-- ----------------------------
-- Table structure for g_agency_gold
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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of g_agency_gold
-- ----------------------------
INSERT INTO `g_agency_gold` VALUES ('10', '30', '房卡', '1000.00', '997.00');
INSERT INTO `g_agency_gold` VALUES ('11', '30', '金币', '10001.00', '10001.00');
INSERT INTO `g_agency_gold` VALUES ('12', '31', '房卡', '598.00', '598.00');
INSERT INTO `g_agency_gold` VALUES ('13', '31', '金币', '0.00', '0.00');
INSERT INTO `g_agency_gold` VALUES ('14', '32', '房卡', '0.00', '0.00');
INSERT INTO `g_agency_gold` VALUES ('15', '32', '金币', '0.00', '0.00');

-- ----------------------------
-- Table structure for g_agency_pay
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
  `type` varchar(255) DEFAULT NULL COMMENT '充值/扣除',
  PRIMARY KEY (`id`),
  KEY `agency_id` (`agency_id`),
  CONSTRAINT `g_agency_pay_ibfk_1` FOREIGN KEY (`agency_id`) REFERENCES `g_agency` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='代理商购买记录表';

-- ----------------------------
-- Records of g_agency_pay
-- ----------------------------

-- ----------------------------
-- Table structure for g_battery
-- ----------------------------
DROP TABLE IF EXISTS `g_battery`;
CREATE TABLE `g_battery` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '炮台设置',
  `name` varchar(20) DEFAULT NULL,
  `multiple` int(10) DEFAULT NULL COMMENT '倍数',
  `number` int(10) DEFAULT NULL COMMENT '数量',
  `give_gold_num` int(10) DEFAULT NULL,
  `updated_at` int(14) DEFAULT NULL,
  `manage_id` int(2) DEFAULT NULL COMMENT '修改人ID',
  `manage_name` varchar(20) DEFAULT NULL COMMENT '修改人名字',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of g_battery
-- ----------------------------
INSERT INTO `g_battery` VALUES ('1', '加龙炮', '200', '500', '100', '1499245879', '1', 'lrdouble');

-- ----------------------------
-- Table structure for g_chat
-- ----------------------------
DROP TABLE IF EXISTS `g_chat`;
CREATE TABLE `g_chat` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '聊天',
  `content` text COMMENT '聊天内容',
  `status` int(11) DEFAULT NULL COMMENT '状态 1:显示   2:隐藏',
  `reg_time` int(14) DEFAULT NULL COMMENT '添加时间',
  `manage_name` varchar(32) DEFAULT NULL COMMENT '添加人',
  `manage_id` int(2) DEFAULT NULL COMMENT '添加人ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of g_chat
-- ----------------------------
INSERT INTO `g_chat` VALUES ('1', '你的鱼打的也太烂了吧!!', '1', null, null, null);
INSERT INTO `g_chat` VALUES ('3', '舒服舒服', '2', '1499163506', null, null);
INSERT INTO `g_chat` VALUES ('4', '这是新的内容', '1', '1499168475', 'lrdouble', '1');
INSERT INTO `g_chat` VALUES ('5', '放松放松', '1', '1499223123', 'lrdouble', '1');

-- ----------------------------
-- Table structure for g_currency_pay
-- ----------------------------
DROP TABLE IF EXISTS `g_currency_pay`;
CREATE TABLE `g_currency_pay` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '充值商城 设置',
  `type` int(2) DEFAULT NULL COMMENT '类型  1:金币  2:钻石',
  `give_num` int(10) DEFAULT NULL COMMENT '赠送数量',
  `number` int(10) DEFAULT NULL COMMENT '数量',
  `money` int(10) DEFAULT NULL COMMENT '人民币',
  `manage_id` int(2) DEFAULT NULL COMMENT '操作人ID',
  `manage_name` varchar(20) DEFAULT NULL COMMENT '操作人名字',
  `created_at` int(14) DEFAULT NULL COMMENT '创建时间',
  `updated_at` int(14) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of g_currency_pay
-- ----------------------------
INSERT INTO `g_currency_pay` VALUES ('1', '1', '100', '11', '111', '1', 'lrdouble', null, '1499654903');
INSERT INTO `g_currency_pay` VALUES ('6', '1', '100', '2500', '250', '1', 'lrdouble', '1499412994', '1499654914');
INSERT INTO `g_currency_pay` VALUES ('3', '1', '50', '2500', '250', '1', 'lrdouble', '1499412186', null);
INSERT INTO `g_currency_pay` VALUES ('4', '2', '1', '100', '100', '1', 'lrdouble', '1499412374', null);
INSERT INTO `g_currency_pay` VALUES ('14', '2', '120', '120', '120', '1', 'lrdouble', '1499414604', null);
INSERT INTO `g_currency_pay` VALUES ('9', '1', '11', '11', '11', '1', 'lrdouble', '1499413465', null);
INSERT INTO `g_currency_pay` VALUES ('12', '2', '11', '11', '11', '1', 'lrdouble', '1499413859', null);
INSERT INTO `g_currency_pay` VALUES ('20', '1', '111', '111', '111', '1', 'lrdouble', '1499417018', null);
INSERT INTO `g_currency_pay` VALUES ('21', '1', '22', '22', '22', '1', 'lrdouble', '1499417388', null);

-- ----------------------------
-- Table structure for g_day
-- ----------------------------
DROP TABLE IF EXISTS `g_day`;
CREATE TABLE `g_day` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '每日签到',
  `type` int(2) DEFAULT NULL COMMENT '模式  1:一次性使用   2:固定使用奖励数值',
  `give_type` varchar(255) DEFAULT NULL COMMENT '赠送类型',
  `day` varchar(20) DEFAULT NULL COMMENT '签到天数',
  `gold_num` int(10) DEFAULT NULL COMMENT '金币数量',
  `jewel_num` int(10) DEFAULT NULL COMMENT '钻石数量',
  `salvo_num` int(10) DEFAULT NULL COMMENT '礼炮',
  `manage_name` varchar(20) DEFAULT NULL COMMENT '操作人',
  `manage_id` int(2) DEFAULT NULL COMMENT '操作人ID',
  `updated_at` int(14) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of g_day
-- ----------------------------
INSERT INTO `g_day` VALUES ('1', '1', '123', '第一天', '1', '111', '111', 'lrdouble', '1', '1499396713');
INSERT INTO `g_day` VALUES ('2', '1', '3', '第二天', null, null, '1', 'lrdouble', '1', '1499396552');
INSERT INTO `g_day` VALUES ('3', '1', '2', '第三天', null, '111', null, 'lrdouble', '1', '1499396570');
INSERT INTO `g_day` VALUES ('4', '1', '1', '第四天', '10', null, null, 'lrdouble', '1', '1499396579');
INSERT INTO `g_day` VALUES ('5', '1', '13', '第五天', '1111', null, '1', 'lrdouble', '1', '1499396638');
INSERT INTO `g_day` VALUES ('6', '1', '2', '第六天', null, '200', null, 'lrdouble', '1', '1499397017');
INSERT INTO `g_day` VALUES ('7', '1', '123', '第七天', '1000', '500', '100', 'lrdouble', '1', '1499396999');
INSERT INTO `g_day` VALUES ('8', '2', '1', '第一天', '22', null, null, 'lrdouble', '1', '1499397407');
INSERT INTO `g_day` VALUES ('9', '2', '1', '第二天', '99', null, null, 'lrdouble', '1', '1499396048');
INSERT INTO `g_day` VALUES ('10', '2', '1', '第三天', '100', null, null, null, null, null);
INSERT INTO `g_day` VALUES ('11', '2', '1', '第四天', '100', null, null, null, null, null);
INSERT INTO `g_day` VALUES ('12', '2', '1', '第五天', '100', null, null, null, null, null);
INSERT INTO `g_day` VALUES ('13', '2', '1', '第六天', null, null, null, 'lrdouble', '1', '1499397199');
INSERT INTO `g_day` VALUES ('14', '2', '1', '第七天', null, null, null, 'lrdouble', '1', '1499396731');

-- ----------------------------
-- Table structure for g_emeer
-- ----------------------------
DROP TABLE IF EXISTS `g_emeer`;
CREATE TABLE `g_emeer` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '族长表',
  `game_id` int(11) DEFAULT NULL COMMENT '族长ID',
  `phone` int(14) DEFAULT NULL COMMENT '手机',
  `nickname` varchar(20) DEFAULT NULL COMMENT '昵称',
  `reg_time` int(14) DEFAULT NULL COMMENT '注册时间',
  `gold_total` varchar(20) DEFAULT NULL COMMENT '总房卡充值数量',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of g_emeer
-- ----------------------------

-- ----------------------------
-- Table structure for g_experience
-- ----------------------------
DROP TABLE IF EXISTS `g_experience`;
CREATE TABLE `g_experience` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '经验等级赠送金币',
  `grade` int(10) DEFAULT NULL COMMENT '经验等级',
  `type` int(10) DEFAULT NULL COMMENT '类型  1:金币  2:钻石',
  `number` int(10) DEFAULT NULL COMMENT '数量',
  `manage_id` int(2) DEFAULT NULL COMMENT '赠送人ID',
  `manage_name` varchar(20) DEFAULT NULL COMMENT '操作人',
  `created_at` int(14) DEFAULT NULL COMMENT '添加时间',
  `updated_at` int(14) DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of g_experience
-- ----------------------------
INSERT INTO `g_experience` VALUES ('4', '2', '1', '2011', '1', 'lrdouble', '1499422696', '1499422739');
INSERT INTO `g_experience` VALUES ('6', '1', '1', '11', '1', 'lrdouble', '1499422750', null);
INSERT INTO `g_experience` VALUES ('7', '3', '2', '300', '1', 'lrdouble', '1499422760', '1499422860');

-- ----------------------------
-- Table structure for g_feedback
-- ----------------------------
DROP TABLE IF EXISTS `g_feedback`;
CREATE TABLE `g_feedback` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL COMMENT 'users ID',
  `game_id` int(11) DEFAULT NULL COMMENT '玩家ID',
  `name` varchar(30) DEFAULT NULL COMMENT '玩家名字',
  `content` text COMMENT '内容',
  `created_at` int(13) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of g_feedback
-- ----------------------------
INSERT INTO `g_feedback` VALUES ('1', '52', '10007', 'lrdouble', '这是反馈记录', '1497863620');
INSERT INTO `g_feedback` VALUES ('2', '64', '10006', '龙龙', '这是反馈记录', '1497863717');
INSERT INTO `g_feedback` VALUES ('3', '64', '10006', '龙龙', '这是反馈记录', '1497863728');
INSERT INTO `g_feedback` VALUES ('4', '64', '10006', '龙龙', '这是反馈记录', '1497863729');
INSERT INTO `g_feedback` VALUES ('5', '64', '10006', '龙龙', '这是反馈记录', '1497863861');
INSERT INTO `g_feedback` VALUES ('6', '64', '10006', '龙龙', '这是反馈记录', '1497865739');

-- ----------------------------
-- Table structure for g_game
-- ----------------------------
DROP TABLE IF EXISTS `g_game`;
CREATE TABLE `g_game` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `num` int(10) DEFAULT NULL COMMENT '每局收取的金币数量',
  `type` varchar(2) DEFAULT NULL COMMENT '类型:     1:匹配模式    2:房卡模式   3.机器人胜率  4:分享金币',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of g_game
-- ----------------------------
INSERT INTO `g_game` VALUES ('1', '90', '1');
INSERT INTO `g_game` VALUES ('2', '20', '2');
INSERT INTO `g_game` VALUES ('3', '10', '3');
INSERT INTO `g_game` VALUES ('4', '50', '4');

-- ----------------------------
-- Table structure for g_game_exploits
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
  `type` varchar(255) DEFAULT NULL COMMENT '模式',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `game_class` (`game_class`),
  CONSTRAINT `g_game_exploits_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `g_users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='玩家战绩表';

-- ----------------------------
-- Records of g_game_exploits
-- ----------------------------
INSERT INTO `g_game_exploits` VALUES ('1', '52', '6543211', 'lrdouble', '1493094941', '0', '家庭麻将', '1', '四人麻将胜利', '匹配');
INSERT INTO `g_game_exploits` VALUES ('2', '58', '50002', 'abc', '1493106002', '1', '宜宾麻将', '1', '赢', '房卡');
INSERT INTO `g_game_exploits` VALUES ('3', '58', '50002', 'abc', '1493106003', '1', '宜宾麻将', '1', '赢', '匹配');
INSERT INTO `g_game_exploits` VALUES ('4', '52', '6543211', 'lrdouble', '1497500041', '1000', '麻将(血战到底)', '1', '+100', '匹配');
INSERT INTO `g_game_exploits` VALUES ('5', '58', '50002', 'abc', '1497500070', '1000', '麻将(血战到底)', '1', '+100', '匹配');
INSERT INTO `g_game_exploits` VALUES ('6', '65', '1', '小强', '1497532122', '100', '家庭麻将', '1', '家庭麻将', '1');
INSERT INTO `g_game_exploits` VALUES ('7', '65', '1', '小强', '1497532566', '100', '家庭麻将', '1', '家庭麻将', '1');

-- ----------------------------
-- Table structure for g_get_gold
-- ----------------------------
DROP TABLE IF EXISTS `g_get_gold`;
CREATE TABLE `g_get_gold` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '领取金币设置',
  `lowest` int(11) DEFAULT NULL COMMENT '最低谷值',
  `type` int(2) DEFAULT NULL COMMENT '类型   1:金币 2:钻石',
  `number` int(11) DEFAULT NULL COMMENT '领取数量',
  `updated_at` int(14) DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of g_get_gold
-- ----------------------------
INSERT INTO `g_get_gold` VALUES ('1', '100', '1', '100', '1499320332');
INSERT INTO `g_get_gold` VALUES ('2', '99', '2', '99', '1499170612');

-- ----------------------------
-- Table structure for g_gold_config
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
-- Records of g_gold_config
-- ----------------------------
INSERT INTO `g_gold_config` VALUES ('房卡', '1', '101', 'fk');
INSERT INTO `g_gold_config` VALUES ('金币', '1', '102', 'gold');

-- ----------------------------
-- Table structure for g_goods
-- ----------------------------
DROP TABLE IF EXISTS `g_goods`;
CREATE TABLE `g_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `game_id` int(11) DEFAULT NULL COMMENT '玩家ID',
  `name` varchar(30) NOT NULL COMMENT '玩家名字',
  `phone` varchar(11) DEFAULT NULL COMMENT '手机号',
  `exchange` varchar(30) DEFAULT NULL COMMENT '兑换奖品',
  `status` int(2) DEFAULT NULL COMMENT '状态   未处理:1   已处理:2    3.拒绝',
  `created_at` int(14) DEFAULT NULL COMMENT '提交时间',
  `detail` varchar(255) DEFAULT NULL COMMENT '备注',
  `updated_at` int(12) DEFAULT NULL COMMENT '处理时间',
  `gold` int(20) DEFAULT NULL COMMENT '金币',
  `uid` int(11) DEFAULT NULL COMMENT '用户自增ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of g_goods
-- ----------------------------
INSERT INTO `g_goods` VALUES ('1', '6543211', '1', '155', '1', '1', '1493093974', '1', '1497581982', '99999', null);
INSERT INTO `g_goods` VALUES ('2', '6543211', '5', '55', '5', '2', '1493093974', '5', '1497594046', '5', null);
INSERT INTO `g_goods` VALUES ('3', '6543211', '55', '5', '5', '3', '1493093974', '5', '1497580011', '5', null);
INSERT INTO `g_goods` VALUES ('4', '6543211', '5', '5', '5', '1', '1493093974', '5', '1497582298', '5', null);
INSERT INTO `g_goods` VALUES ('5', '6543211', '', '55', '5', '1', '1493093974', '5', '1497582378', '5', null);
INSERT INTO `g_goods` VALUES ('6', '6543211', '爽肤水', '13219890986', '西游记', '1', '1497341707', '好的', '1497532353', '5', null);
INSERT INTO `g_goods` VALUES ('7', '6543211', '爽肤水', '13219890986', '西游记', '1', '1497341771', '好的', '1497582383', '5', null);
INSERT INTO `g_goods` VALUES ('8', '6543211', '爽肤水', '13219890986', '西游记', '1', '1497489609', '好的', '1497582387', '5', null);
INSERT INTO `g_goods` VALUES ('9', '6543211', '爽肤水', '13219890986', '西游记', '1', '1497489638', '好的', null, '5', null);
INSERT INTO `g_goods` VALUES ('10', '1', '100元充值卡', '13812345678', null, '1', '1497532684', null, null, null, null);
INSERT INTO `g_goods` VALUES ('11', '1', '100元充值卡', '13812345678', null, '1', '1497532697', null, null, null, null);
INSERT INTO `g_goods` VALUES ('12', '100006', '爽肤水', '13219890986', null, '1', '1497863203', null, null, '800', '64');

-- ----------------------------
-- Table structure for g_inlet_porting
-- ----------------------------
DROP TABLE IF EXISTS `g_inlet_porting`;
CREATE TABLE `g_inlet_porting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '设置入口',
  `name` varchar(20) DEFAULT NULL COMMENT '游戏名',
  `status` int(2) DEFAULT NULL COMMENT '状态   1:是  0 否',
  `manage_id` int(2) DEFAULT NULL COMMENT '修改人ID',
  `manage_name` varchar(20) DEFAULT NULL COMMENT '修改人',
  `updated_at` int(14) DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of g_inlet_porting
-- ----------------------------
INSERT INTO `g_inlet_porting` VALUES ('1', '刺激游戏', '1', '1', 'lrdouble', '1499658202');
INSERT INTO `g_inlet_porting` VALUES ('2', '宝石场', '0', '1', 'lrdouble', '1499243935');

-- ----------------------------
-- Table structure for g_mail
-- ----------------------------
DROP TABLE IF EXISTS `g_mail`;
CREATE TABLE `g_mail` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '邮件设置',
  `title` varchar(20) DEFAULT NULL COMMENT '标题',
  `content` text COMMENT '邮件内容',
  `status` int(2) DEFAULT NULL,
  `type` int(2) DEFAULT NULL COMMENT '类型',
  `number` int(10) DEFAULT NULL COMMENT '数量',
  `yes_no` int(2) DEFAULT NULL COMMENT '是否有奖励',
  `manage_name` varchar(20) DEFAULT NULL COMMENT '添加人',
  `manage_id` int(2) DEFAULT NULL COMMENT '添加人ID',
  `created_at` int(14) DEFAULT NULL COMMENT '发布时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of g_mail
-- ----------------------------
INSERT INTO `g_mail` VALUES ('1', '系统维护', '有序系统出现问题,所以需要维护..所以赔偿大家金币500', '1', '1', '500', '1', 'lrdouble', '1', '1499228246');
INSERT INTO `g_mail` VALUES ('2', '通知', '祝你们玩高兴', '1', '0', null, '0', 'lrdouble', '1', '1499228277');
INSERT INTO `g_mail` VALUES ('3', '发顺丰', '舒服舒服', '1', '2', '10', '1', 'lrdouble', '1', '1499320842');

-- ----------------------------
-- Table structure for g_manage
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
-- Records of g_manage
-- ----------------------------
INSERT INTO `g_manage` VALUES ('1', 'lrdouble', 'e10adc3949ba59abbe56e057f20f883e', null);

-- ----------------------------
-- Table structure for g_money
-- ----------------------------
DROP TABLE IF EXISTS `g_money`;
CREATE TABLE `g_money` (
  `id` int(11) NOT NULL COMMENT '货币设置',
  `type` int(2) DEFAULT NULL COMMENT '类型:   1:金币   2:钻石   3:发布喇叭所需钻石',
  `number` int(10) DEFAULT NULL COMMENT '数量',
  `manage_name` varchar(20) DEFAULT NULL COMMENT '更新人',
  `manage_id` int(2) DEFAULT NULL COMMENT '更新人ID',
  `updated_at` int(14) DEFAULT NULL COMMENT '更新时间',
  `detail` text COMMENT '详情',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of g_money
-- ----------------------------
INSERT INTO `g_money` VALUES ('1', '1', '99', 'lrdouble', '1', '1499237781', '金币的设置2');
INSERT INTO `g_money` VALUES ('2', '2', '100', 'lrdouble', '1', '1499671509', '1个钻石兑换金币的比例');
INSERT INTO `g_money` VALUES ('3', '3', '100', '龙龙2', '2', '1499235701', '发布喇叭需要的钻石');
INSERT INTO `g_money` VALUES ('4', '4', '8', 'lrdouble', '1', '1499427055', '发布留言版需要的钻石');

-- ----------------------------
-- Table structure for g_notice
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
  `type` int(2) DEFAULT NULL COMMENT '类型 0:没有奖励   1:金币   2:数量',
  `number` int(10) DEFAULT NULL COMMENT '数量',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='公告通知数据库';

-- ----------------------------
-- Records of g_notice
-- ----------------------------
INSERT INTO `g_notice` VALUES ('6', '1', 'lrdouble', '份饭', '份饭', '1', '1499241067', '份饭', '房间滚动公告', '0', null);
INSERT INTO `g_notice` VALUES ('7', '1', 'lrdouble', '未来', '这是未来的计划', '1', '1499242063', '好好的', '首页公告', '2', '100000');

-- ----------------------------
-- Table structure for g_rebate
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
-- Records of g_rebate
-- ----------------------------

-- ----------------------------
-- Table structure for g_rebate_conf
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
-- Records of g_rebate_conf
-- ----------------------------
INSERT INTO `g_rebate_conf` VALUES ('1', '5', '3', '2', '100');

-- ----------------------------
-- Table structure for g_redeem_code
-- ----------------------------
DROP TABLE IF EXISTS `g_redeem_code`;
CREATE TABLE `g_redeem_code` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '兑换码',
  `type` int(2) DEFAULT NULL COMMENT '类型',
  `redeem_code` varchar(20) DEFAULT NULL COMMENT '兑换码',
  `name` varchar(20) DEFAULT NULL COMMENT '名称',
  `number` int(12) DEFAULT NULL COMMENT '生成兑换码数量',
  `description` text COMMENT '描述',
  `prize` varchar(100) DEFAULT NULL COMMENT '奖品内容',
  `add_type` int(2) DEFAULT NULL COMMENT '1:一次使用型 2: 无限制实用性',
  `created_at` int(14) DEFAULT NULL COMMENT '创建时间',
  `start_time` int(14) DEFAULT NULL COMMENT '开始时间',
  `end_time` int(14) DEFAULT NULL COMMENT '限制时间',
  `status` int(2) DEFAULT NULL COMMENT '状态  0:未使用  1:已使用',
  `scope_type` int(2) DEFAULT NULL COMMENT '1:普通用户  2:vip用户  3: 所有用户',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of g_redeem_code
-- ----------------------------
INSERT INTO `g_redeem_code` VALUES ('4', '2', 'GRV7HD26JZCM', '爽肤水', null, null, '{\"gold\":\"1\",\"diamond\":\"2\",\"fishGold\":\"3\",\"1\":\"4\",\"2\":\"5\",\"3\":\"6\",\"4\":\"7\",\"5\":\"8\",\"6\":\"9\"}', '1', '1499775419', '1499778971', '1599778971', '1', '3');
INSERT INTO `g_redeem_code` VALUES ('5', '2', 'CZA3TMTRQHN9', '爽肤水', null, null, '{\"gold\":\"1\",\"diamond\":\"2\",\"fishGold\":\"3\",\"1\":\"4\",\"2\":\"5\",\"3\":\"6\",\"4\":\"7\",\"5\":\"8\",\"6\":\"9\"}', '1', '1499775419', null, null, '0', '3');
INSERT INTO `g_redeem_code` VALUES ('6', '2', 'ZM7R0G72C87B', '爽肤水', null, null, '{\"gold\":\"1\",\"diamond\":\"2\",\"fishGold\":\"3\",\"1\":\"4\",\"2\":\"5\",\"3\":\"6\",\"4\":\"7\",\"5\":\"8\",\"6\":\"9\"}', '1', '1499775419', null, null, '0', '3');
INSERT INTO `g_redeem_code` VALUES ('7', '2', 'MCMNPHOW0LNM', '爽肤水', null, null, '{\"gold\":\"1\",\"diamond\":\"2\",\"fishGold\":\"3\",\"1\":\"4\",\"2\":\"5\",\"3\":\"6\",\"4\":\"7\",\"5\":\"8\",\"6\":\"9\"}', '1', '1499775419', null, null, '0', '3');
INSERT INTO `g_redeem_code` VALUES ('8', '2', '4JTXOVA95ERB', '爽肤水', null, null, '{\"gold\":\"1\",\"diamond\":\"2\",\"fishGold\":\"3\",\"1\":\"4\",\"2\":\"5\",\"3\":\"6\",\"4\":\"7\",\"5\":\"8\",\"6\":\"9\"}', '1', '1499775419', null, null, '0', '3');
INSERT INTO `g_redeem_code` VALUES ('9', '2', '5PMMUK1RAN2X', '爽肤水', null, null, '{\"gold\":\"1\",\"diamond\":\"2\",\"fishGold\":\"3\",\"1\":\"4\",\"2\":\"5\",\"3\":\"6\",\"4\":\"7\",\"5\":\"8\",\"6\":\"9\"}', '1', '1499775419', null, null, '0', '3');
INSERT INTO `g_redeem_code` VALUES ('10', '2', '6WFJRCIUGLL4', '爽肤水', null, null, '{\"gold\":\"1\",\"diamond\":\"2\",\"fishGold\":\"3\",\"1\":\"4\",\"2\":\"5\",\"3\":\"6\",\"4\":\"7\",\"5\":\"8\",\"6\":\"9\"}', '1', '1499775419', null, null, '0', '3');
INSERT INTO `g_redeem_code` VALUES ('11', '2', '9YXDSB89FEO4', '爽肤水', null, null, '{\"gold\":\"1\",\"diamond\":\"2\",\"fishGold\":\"3\",\"1\":\"4\",\"2\":\"5\",\"3\":\"6\",\"4\":\"7\",\"5\":\"8\",\"6\":\"9\"}', '1', '1499775419', null, null, '0', '3');
INSERT INTO `g_redeem_code` VALUES ('12', '2', 'TA1PD2AJKUYM', '爽肤水', null, null, '{\"gold\":\"1\",\"diamond\":\"2\",\"fishGold\":\"3\",\"1\":\"4\",\"2\":\"5\",\"3\":\"6\",\"4\":\"7\",\"5\":\"8\",\"6\":\"9\"}', '1', '1499775419', '1499778971', '1599778971', '0', '3');
INSERT INTO `g_redeem_code` VALUES ('16', '2', 'V6TQ2RQUWKOF', 'vip', null, null, '{\"gold\":\"999\",\"diamond\":\"999\",\"fishGold\":\"99\",\"1\":\"99\",\"2\":\"99\",\"3\":\"99\",\"4\":\"99\",\"5\":\"99\"}', '2', '1499778971', '1499778971', '1499779971', '2', '2');

-- ----------------------------
-- Table structure for g_redeem_record
-- ----------------------------
DROP TABLE IF EXISTS `g_redeem_record`;
CREATE TABLE `g_redeem_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '兑换记录',
  `uid` int(2) DEFAULT NULL COMMENT '用户自增ID',
  `game_id` varchar(14) DEFAULT NULL COMMENT '玩家ID',
  `nickname` varchar(20) DEFAULT NULL COMMENT '玩家昵称',
  `redeem_code` varchar(255) DEFAULT NULL COMMENT '兑换码',
  `status` int(2) DEFAULT NULL COMMENT '0:失败  1:成功',
  `created_at` int(14) DEFAULT NULL COMMENT '兑换时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of g_redeem_record
-- ----------------------------
INSERT INTO `g_redeem_record` VALUES ('1', '1', '288427', '龙龙', 'V6TQ2RQUWKOF', '1', '1499775419');
INSERT INTO `g_redeem_record` VALUES ('2', '2', '3544344', '小龙', 'V6TQ2RQUWKOQ', '0', '1499775419');

-- ----------------------------
-- Table structure for g_shop
-- ----------------------------
DROP TABLE IF EXISTS `g_shop`;
CREATE TABLE `g_shop` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '道具设置',
  `name` varchar(20) DEFAULT NULL COMMENT '道具名称',
  `number` int(10) DEFAULT NULL COMMENT '道具数量',
  `jewel_number` int(10) DEFAULT NULL COMMENT '所需要的钻石',
  `created_at` int(14) DEFAULT NULL COMMENT '添加时间',
  `updated_at` int(14) DEFAULT NULL COMMENT '修改时间',
  `type` int(2) DEFAULT NULL COMMENT '类型:   1.神灯   2.锁定   3.冻结   4.核弹  5.狂暴  6.黑洞',
  `order_number` int(2) DEFAULT NULL COMMENT '序号',
  `level` int(2) DEFAULT NULL COMMENT '购买等级',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of g_shop
-- ----------------------------
INSERT INTO `g_shop` VALUES ('1', '神灯', '0', '120', null, '1499763465', '1', '1', '1');
INSERT INTO `g_shop` VALUES ('2', '锁定', '1', '100', null, '1499219644', '2', '2', '2');
INSERT INTO `g_shop` VALUES ('3', '冻结', '1', '200', null, '1499235701', '3', '3', null);
INSERT INTO `g_shop` VALUES ('4', '核弹', '10', '300', null, null, '4', '4', null);
INSERT INTO `g_shop` VALUES ('5', '狂暴', '10', '100', null, '1499406802', '5', '5', '1');
INSERT INTO `g_shop` VALUES ('6', '黑洞', '0', '0', null, '1499235712', '6', '6', null);

-- ----------------------------
-- Table structure for g_sign_board
-- ----------------------------
DROP TABLE IF EXISTS `g_sign_board`;
CREATE TABLE `g_sign_board` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '捕鱼任务 奖励设置',
  `type` int(2) DEFAULT NULL COMMENT '类型',
  `number` int(10) DEFAULT NULL COMMENT '赠送数量',
  `manage_id` int(2) DEFAULT NULL COMMENT '修改人ID',
  `manage_name` varchar(20) DEFAULT NULL COMMENT '修改人',
  `updated_at` int(14) DEFAULT NULL COMMENT '修改时间',
  `detail` text COMMENT '说明',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of g_sign_board
-- ----------------------------
INSERT INTO `g_sign_board` VALUES ('1', '3', '1000', '1', 'lrdouble', '1499331187', '这是完成完成每日任务所领取的奖励');

-- ----------------------------
-- Table structure for g_touch
-- ----------------------------
DROP TABLE IF EXISTS `g_touch`;
CREATE TABLE `g_touch` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '联系客户表',
  `phone` varchar(20) DEFAULT NULL COMMENT '客户电话号码',
  `qq_number` int(12) DEFAULT NULL COMMENT 'QQ号码',
  `manage_name` varchar(20) DEFAULT NULL COMMENT '修改人',
  `manage_id` int(2) DEFAULT NULL COMMENT '修改人ID',
  `hkmovie` varchar(20) DEFAULT NULL COMMENT '公众号',
  `status` int(2) DEFAULT NULL COMMENT '状态  0:禁用   1:开启',
  `updated_at` int(14) DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of g_touch
-- ----------------------------
INSERT INTO `g_touch` VALUES ('1', '400-150-5886', '214748364', 'lrdouble', '1', 'love9749607', '1', '1499321272');

-- ----------------------------
-- Table structure for g_type
-- ----------------------------
DROP TABLE IF EXISTS `g_type`;
CREATE TABLE `g_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '赠送范围',
  `name` varchar(255) DEFAULT NULL COMMENT '赠送范围    普通   vip用户    所有用户',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of g_type
-- ----------------------------

-- ----------------------------
-- Table structure for g_users
-- ----------------------------
DROP TABLE IF EXISTS `g_users`;
CREATE TABLE `g_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` int(11) DEFAULT NULL COMMENT '游戏数据的ＩＤ',
  `nickname` varchar(32) DEFAULT NULL COMMENT '玩家昵称',
  `gold` int(10) unsigned DEFAULT NULL COMMENT '金币数量',
  `status` tinyint(3) unsigned DEFAULT '1' COMMENT '状态 1:启用中 0:已封停 2:黑名单',
  `gem` int(10) DEFAULT NULL COMMENT '宝石',
  `jewel` int(10) DEFAULT NULL COMMENT '钻石',
  `reg_time` int(11) unsigned DEFAULT NULL COMMENT '注册时间',
  `unset_time` int(11) DEFAULT NULL COMMENT '解封时间',
  `grade` int(11) DEFAULT NULL COMMENT '等级',
  PRIMARY KEY (`id`),
  UNIQUE KEY `111` (`game_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8 COMMENT='玩家表';

-- ----------------------------
-- Records of g_users
-- ----------------------------
INSERT INTO `g_users` VALUES ('52', '6543211', 'lrdouble', '50', '2', '1', null, '1493093974', '1493093974', '10');
INSERT INTO `g_users` VALUES ('53', '6543212', 'lrdouble', '20', '1', '10', null, '1493094267', null, null);
INSERT INTO `g_users` VALUES ('54', '6543213', 'lrdouble', '2000', '1', '10', null, '1493094294', null, null);
INSERT INTO `g_users` VALUES ('55', '6543214', 'lrdouble', null, '1', '10', null, '1493094331', null, '10');
INSERT INTO `g_users` VALUES ('56', '6543215', 'lrdouble', '30', '0', '10', null, '1493094343', null, null);
INSERT INTO `g_users` VALUES ('57', '123321', 'abc', '1', '0', null, null, '1493100167', null, '1');
INSERT INTO `g_users` VALUES ('58', '50002', 'abc', '1', '2', null, null, '1493104021', null, '2');
INSERT INTO `g_users` VALUES ('59', '50003', 'ri', '1000', '1', null, null, '1493105166', null, null);
INSERT INTO `g_users` VALUES ('60', '6543219', '琪琪', '99', '1', null, null, '1497501909', null, '58');
INSERT INTO `g_users` VALUES ('61', '65432100', '琪琪', '99', '1', null, null, '1497502105', null, '8');
INSERT INTO `g_users` VALUES ('62', '65432106', '琪琪', '99', '2', null, null, '1497505303', null, '8');
INSERT INTO `g_users` VALUES ('63', '654321068', '琪琪', '0', '1', null, null, '1497505619', null, null);
INSERT INTO `g_users` VALUES ('64', '10006', '龙龙', '100', '1', null, null, '1497505721', null, null);
INSERT INTO `g_users` VALUES ('65', '1', '小强', '100', '1', null, null, '1497531610', null, null);
INSERT INTO `g_users` VALUES ('66', '2', '小强', '100', '0', null, null, '1497531737', null, null);

-- ----------------------------
-- Table structure for g_users_gold
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
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of g_users_gold
-- ----------------------------
INSERT INTO `g_users_gold` VALUES ('4', '52', '房卡', '201.00', '221.00');
INSERT INTO `g_users_gold` VALUES ('5', '52', '金币', '100.00', '231.00');
INSERT INTO `g_users_gold` VALUES ('6', '53', '房卡', '0.00', '0.00');
INSERT INTO `g_users_gold` VALUES ('7', '53', '金币', '0.00', '0.00');
INSERT INTO `g_users_gold` VALUES ('8', '54', '房卡', '0.00', '0.00');
INSERT INTO `g_users_gold` VALUES ('9', '54', '金币', '0.00', '0.00');
INSERT INTO `g_users_gold` VALUES ('10', '55', '房卡', '20.00', '20.00');
INSERT INTO `g_users_gold` VALUES ('11', '55', '金币', '20.00', '20.00');
INSERT INTO `g_users_gold` VALUES ('12', '56', '房卡', '20.00', '20.00');
INSERT INTO `g_users_gold` VALUES ('13', '56', '金币', '30.00', '30.00');
INSERT INTO `g_users_gold` VALUES ('14', '57', '房卡', '120.00', '120.00');
INSERT INTO `g_users_gold` VALUES ('15', '57', '金币', '11.00', '11.00');
INSERT INTO `g_users_gold` VALUES ('16', '58', '房卡', '120.00', '120.00');
INSERT INTO `g_users_gold` VALUES ('17', '58', '金币', '1.00', '1.00');
INSERT INTO `g_users_gold` VALUES ('18', '59', '房卡', '100.00', '100.00');
INSERT INTO `g_users_gold` VALUES ('19', '59', '金币', '1010.00', '1010.00');
INSERT INTO `g_users_gold` VALUES ('20', '60', '房卡', '0.00', '0.00');
INSERT INTO `g_users_gold` VALUES ('21', '60', '金币', '99.00', '99.00');
INSERT INTO `g_users_gold` VALUES ('22', '61', '房卡', '0.00', '0.00');
INSERT INTO `g_users_gold` VALUES ('23', '61', '金币', '99.00', '99.00');
INSERT INTO `g_users_gold` VALUES ('24', '62', '房卡', '0.00', '0.00');
INSERT INTO `g_users_gold` VALUES ('25', '62', '金币', '99.00', '99.00');
INSERT INTO `g_users_gold` VALUES ('26', '63', '房卡', '100.00', '100.00');
INSERT INTO `g_users_gold` VALUES ('27', '63', '金币', '0.00', '0.00');
INSERT INTO `g_users_gold` VALUES ('28', '64', '房卡', '350.00', '350.00');
INSERT INTO `g_users_gold` VALUES ('29', '64', '金币', '500.00', '500.00');
INSERT INTO `g_users_gold` VALUES ('30', '65', '房卡', '40.00', '40.00');
INSERT INTO `g_users_gold` VALUES ('31', '65', '金币', '100.00', '100.00');
INSERT INTO `g_users_gold` VALUES ('32', '66', '房卡', '39.00', '40.00');
INSERT INTO `g_users_gold` VALUES ('33', '66', '金币', '100.00', '100.00');

-- ----------------------------
-- Table structure for g_user_out
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
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8 COMMENT='玩家消费表';

-- ----------------------------
-- Records of g_user_out
-- ----------------------------
INSERT INTO `g_user_out` VALUES ('27', '52', '6543211', 'lrdouble', '1493094676', '2', '家庭麻将', '四人麻将开房费用', '');
INSERT INTO `g_user_out` VALUES ('28', '52', '6543211', 'lrdouble', '1493094722', '2', '家庭麻将', '四人麻将开房费用', '');
INSERT INTO `g_user_out` VALUES ('29', '52', '6543211', 'lrdouble', '1493094767', '2', '家庭麻将', '四人麻将开房费用', '');
INSERT INTO `g_user_out` VALUES ('30', '52', '6543211', 'lrdouble', '1493094808', '2', '家庭麻将', '四人麻将开房费用', '房卡');
INSERT INTO `g_user_out` VALUES ('31', '52', '6543211', 'lrdouble', '1493094825', '2', '家庭麻将', '四人麻将开房费用', '金币');
INSERT INTO `g_user_out` VALUES ('32', '52', '6543211', 'lrdouble', '1493094839', '8', '家庭麻将', '四人麻将开房费用', '金币');
INSERT INTO `g_user_out` VALUES ('33', '52', '6543211', 'lrdouble', '1493094846', '10', '家庭麻将', '四人麻将开房费用', '金币');
INSERT INTO `g_user_out` VALUES ('34', '52', '6543211', 'lrdouble', '1497500988', '10', '麻将(血战到底)', '消费详情', '房卡');
INSERT INTO `g_user_out` VALUES ('35', '66', '2', '小强', '1497532031', '1', '家庭麻将', '四人麻将开房费用', '房卡');

-- ----------------------------
-- Table structure for g_user_pay
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
  `detail` varchar(255) DEFAULT NULL COMMENT '备注',
  `type` varchar(10) DEFAULT NULL COMMENT '类型    充值   扣除',
  `order` varchar(255) DEFAULT NULL COMMENT '订单号',
  PRIMARY KEY (`id`),
  KEY `agency_id` (`agency_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `g_user_pay_ibfk_1` FOREIGN KEY (`agency_id`) REFERENCES `g_agency` (`id`),
  CONSTRAINT `g_user_pay_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `g_users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=200 DEFAULT CHARSET=utf8 COMMENT='玩家充值表';

-- ----------------------------
-- Records of g_user_pay
-- ----------------------------
INSERT INTO `g_user_pay` VALUES ('66', '1', '平台', '52', '6543211', 'lrdouble', '1493094212', '10', '10.00', '1', '房卡', '11', '1', null);
INSERT INTO `g_user_pay` VALUES ('67', '1', '平台', '52', '6543211', 'lrdouble', '1493094228', '10', '10.00', '1', '金币', '1', '1', null);
INSERT INTO `g_user_pay` VALUES ('68', '1', '平台', '52', '6543211', 'lrdouble', '1493094237', '10', '10.00', '1', '金币', '1', null, null);
INSERT INTO `g_user_pay` VALUES ('69', '1', '平台', '52', '6543211', 'lrdouble', '1493094248', '10', '10.00', '1', '房卡', null, '1', null);
INSERT INTO `g_user_pay` VALUES ('70', '30', '曹双', '52', '6543211', 'lrdouble', '1493095278', '10', '10.00', '1', null, null, null, null);
INSERT INTO `g_user_pay` VALUES ('71', '30', '曹双', '52', '6543211', 'lrdouble', '1493095613', '10', '0.00', '1', null, null, null, null);
INSERT INTO `g_user_pay` VALUES ('72', '31', '曹双', '52', '6543211', 'lrdouble', '1497259494', '10', '0.00', '1', null, null, null, null);
INSERT INTO `g_user_pay` VALUES ('73', '31', '曹双', '52', '6543212', 'lrdouble', '1497259494', '10', '10.00', '1', '房卡', null, null, null);
INSERT INTO `g_user_pay` VALUES ('74', '1', '平台', '52', '6543211', 'lrdouble', '1493103774', '10', '10.00', '1', '金币', null, null, null);
INSERT INTO `g_user_pay` VALUES ('75', '1', '平台', '52', '6543211', 'lrdouble', '1493103774', '10', '10.00', '1', '金币', null, '1', null);
INSERT INTO `g_user_pay` VALUES ('76', '1', '平台', '52', '6543211', 'lrdouble', '1493103774', '10', '10.00', '1', '金币', null, null, null);
INSERT INTO `g_user_pay` VALUES ('77', '1', '平台', '52', '6543211', 'lrdouble', '1493103774', '10', '10.00', '1', '金币', null, null, null);
INSERT INTO `g_user_pay` VALUES ('78', '1', '平台', '52', '6543211', 'lrdouble', '1493103775', '10', '10.00', '1', '金币', null, null, null);
INSERT INTO `g_user_pay` VALUES ('79', '1', '平台', '52', '6543211', 'lrdouble', '1493103775', '10', '10.00', '1', '金币', null, null, null);
INSERT INTO `g_user_pay` VALUES ('80', '1', '平台', '52', '6543211', 'lrdouble', '1493103775', '10', '10.00', '1', '金币', null, null, null);
INSERT INTO `g_user_pay` VALUES ('81', '1', '平台', '52', '6543211', 'lrdouble', '1493103775', '10', '10.00', '1', '金币', null, null, null);
INSERT INTO `g_user_pay` VALUES ('82', '1', '平台', '52', '6543211', 'lrdouble', '1493103775', '10', '10.00', '1', '金币', null, '1', null);
INSERT INTO `g_user_pay` VALUES ('83', '1', '平台', '52', '6543211', 'lrdouble', '1493103776', '10', '10.00', '1', '金币', null, null, null);
INSERT INTO `g_user_pay` VALUES ('84', '1', '平台', '57', '123321', 'abc', '1493103787', '10', '10.00', '1', '金币', null, null, null);
INSERT INTO `g_user_pay` VALUES ('85', '1', '平台', '57', '123321', 'abc', '1493103876', '10', '0.00', '1', '房卡', null, null, null);
INSERT INTO `g_user_pay` VALUES ('86', '1', '平台', '57', '123321', 'abc', '1493103897', '10', '0.00', '1', '房卡', null, '1', null);
INSERT INTO `g_user_pay` VALUES ('87', '1', '平台', '58', '50002', 'abc', '1493104216', '10', '10.00', '1', '房卡', null, null, null);
INSERT INTO `g_user_pay` VALUES ('88', '1', '平台', '58', '50002', 'abc', '1493104787', '10', '0.00', '1', '房卡', null, null, null);
INSERT INTO `g_user_pay` VALUES ('89', '31', '平台', '59', '50003', 'ri', '1497259494', '10', '0.00', '1', '金币', '11', '1', null);
INSERT INTO `g_user_pay` VALUES ('90', '1', '平台', '52', '6543211', 'lrdouble', '1497356927', '10', '0.00', '1', '金币', '111', null, null);
INSERT INTO `g_user_pay` VALUES ('91', '1', '平台', '52', '6543211', 'lrdouble', '1497356944', '10', '0.00', '1', '金币', '111', null, null);
INSERT INTO `g_user_pay` VALUES ('92', '1', '平台', '52', '6543211', 'lrdouble', '1497357068', '10', '0.00', '1', '房卡', '111', null, null);
INSERT INTO `g_user_pay` VALUES ('93', '1', '平台', '52', '6543211', 'lrdouble', '1497357328', '0', '0.00', '1', '房卡', '111', '1', null);
INSERT INTO `g_user_pay` VALUES ('94', '1', '平台', '52', '6543211', 'lrdouble', '1497419329', '1', '0.00', '1', '金币', '111', null, null);
INSERT INTO `g_user_pay` VALUES ('95', '1', '平台', '52', '6543211', 'lrdouble', '1497419340', '1', '0.00', '1', '金币', '111', '1', null);
INSERT INTO `g_user_pay` VALUES ('96', '1', '平台', '52', '6543211', 'lrdouble', '1497419356', '1', '0.00', '1', '房卡', '111', null, null);
INSERT INTO `g_user_pay` VALUES ('97', '1', '平台', '52', '6543211', 'lrdouble', '1497419978', '1', '0.00', '1', '房卡', '111', '1', null);
INSERT INTO `g_user_pay` VALUES ('98', '1', '平台', '52', '6543211', 'lrdouble', '1497420004', '0', '0.00', '1', '房卡', '111', '2', null);
INSERT INTO `g_user_pay` VALUES ('99', '1', '平台', '52', '6543211', 'lrdouble', '1497421758', '1', '0.00', '1', '房卡', '111', '1', null);
INSERT INTO `g_user_pay` VALUES ('100', '1', '平台', '64', '2147483647', '龙龙', '1497527765', '150', '0.00', '1', '房卡', '', '充值', null);
INSERT INTO `g_user_pay` VALUES ('101', '1', '平台', '64', '2147483647', '龙龙', '1497527871', '100', '0.00', '1', '房卡', '', '充值', null);
INSERT INTO `g_user_pay` VALUES ('102', '1', '平台', '64', '2147483647', '龙龙', '1497527903', '0', '0.00', '1', '金币', '', '充值', null);
INSERT INTO `g_user_pay` VALUES ('103', '1', '平台', '64', '2147483647', '龙龙', '1497527919', '0', '0.00', '1', '房卡', '', '充值', null);
INSERT INTO `g_user_pay` VALUES ('104', null, null, '64', '2147483647', null, '1493095633', '100', null, '1', null, null, null, null);
INSERT INTO `g_user_pay` VALUES ('105', null, '客户端充值', '64', '2147483647', '龙龙', '1493095633', '100', null, '1', '金币', null, '充值', null);
INSERT INTO `g_user_pay` VALUES ('106', null, '客户端', '64', '2147483647', '龙龙', '1493095633', '100', null, '1', '金币', null, '充值', null);
INSERT INTO `g_user_pay` VALUES ('107', null, '客户端', '64', '2147483647', '龙龙', '1493095633', '100', '120.00', '1', '金币', null, '充值', null);
INSERT INTO `g_user_pay` VALUES ('108', null, '客户端', '64', '2147483647', '龙龙', '1493095633', '100', '120.00', '1', '金币', null, '充值', null);
INSERT INTO `g_user_pay` VALUES ('109', null, '客户端', '64', '2147483647', '龙龙', '1493095633', '100', '120.00', '1', '金币', null, '充值', null);
INSERT INTO `g_user_pay` VALUES ('110', '1', '平台', '52', '6543211', 'lrdouble', '1497582504', '0', '0.00', '1', '房卡', '111', '扣除', null);
INSERT INTO `g_user_pay` VALUES ('111', '1', '平台', '52', '6543211', 'lrdouble', '1497582517', '0', '0.00', '1', '房卡', '111', '扣除', null);
INSERT INTO `g_user_pay` VALUES ('112', '1', '平台', '52', '6543211', 'lrdouble', '1497582541', '0', '0.00', '1', '金币', '111', '扣除', null);
INSERT INTO `g_user_pay` VALUES ('113', '1', '平台', '52', '6543211', 'lrdouble', '1497582554', '0', '0.00', '1', '金币', '111', '扣除', null);
INSERT INTO `g_user_pay` VALUES ('114', '1', '平台', '52', '6543211', 'lrdouble', '1497582962', '10', '0.00', '1', '房卡', '111', '充值', null);
INSERT INTO `g_user_pay` VALUES ('115', '1', '平台', '52', '6543211', 'lrdouble', '1497583386', '10', '0.00', '1', '房卡', '111', '扣除', null);
INSERT INTO `g_user_pay` VALUES ('116', '1', '平台', '52', '6543211', 'lrdouble', '1497583412', '10', '0.00', '1', '房卡', '111', '充值', null);
INSERT INTO `g_user_pay` VALUES ('117', '1', '平台', '52', '6543211', 'lrdouble', '1497584141', '100', '0.00', '1', '房卡', '111', '扣除', null);
INSERT INTO `g_user_pay` VALUES ('118', '1', '平台', '52', '6543211', 'lrdouble', '1497584200', '100', '0.00', '1', '房卡', '111', '扣除', null);
INSERT INTO `g_user_pay` VALUES ('119', '1', '平台', '52', '6543211', 'lrdouble', '1497584246', '10', '0.00', '1', '房卡', '111', '充值', null);
INSERT INTO `g_user_pay` VALUES ('120', '1', '平台', '52', '6543211', 'lrdouble', '1497584278', '188', '0.00', '1', '房卡', '111', '充值', null);
INSERT INTO `g_user_pay` VALUES ('121', '1', '平台', '52', '6543211', 'lrdouble', '1497584289', '4', '0.00', '1', '金币', '111', '充值', null);
INSERT INTO `g_user_pay` VALUES ('122', '1', '平台', '52', '6543211', 'lrdouble', '1497584312', '10', '0.00', '1', '房卡', '111', '扣除', null);
INSERT INTO `g_user_pay` VALUES ('123', '1', '平台', '52', '6543211', 'lrdouble', '1497584322', '10', '0.00', '1', '金币', '111', '扣除', null);
INSERT INTO `g_user_pay` VALUES ('124', '1', '平台', '52', '6543211', 'lrdouble', '1497584581', '100', '0.00', '1', '房卡', '111', '扣除', null);
INSERT INTO `g_user_pay` VALUES ('125', '1', '平台', '52', '6543211', 'lrdouble', '1497584649', '100', '0.00', '1', '房卡', '111', '扣除', null);
INSERT INTO `g_user_pay` VALUES ('126', '1', '平台', '52', '6543211', 'lrdouble', '1497584968', '100', '0.00', '1', '金币', '111', '扣除', null);
INSERT INTO `g_user_pay` VALUES ('127', '1', '平台', '52', '6543211', 'lrdouble', '1497585005', '9999', '0.00', '1', '金币', '111', '扣除', null);
INSERT INTO `g_user_pay` VALUES ('128', '1', '平台', '52', '6543211', 'lrdouble', '1497585051', '2222', '0.00', '1', '房卡', '111', '扣除', null);
INSERT INTO `g_user_pay` VALUES ('129', '1', '平台', '52', '6543211', 'lrdouble', '1497593789', '1', '0.00', '1', '房卡', '给你充的', '充值', null);
INSERT INTO `g_user_pay` VALUES ('130', '1', '平台', '52', '6543211', 'lrdouble', '1497593831', '1', '0.00', '1', '金币', '金币', '充值', null);
INSERT INTO `g_user_pay` VALUES ('131', '1', '平台', '52', '6543211', 'lrdouble', '1497593832', '1', '0.00', '1', '金币', '金币', '充值', null);
INSERT INTO `g_user_pay` VALUES ('132', '1', '平台', '52', '6543211', 'lrdouble', '1497593875', '1', '0.00', '1', '房卡', '负数1', '扣除', null);
INSERT INTO `g_user_pay` VALUES ('133', '1', '平台', '52', '6543211', 'lrdouble', '1497593890', '2', '0.00', '1', '金币', '金币', '扣除', null);
INSERT INTO `g_user_pay` VALUES ('134', '1', '平台', '52', '6543211', 'lrdouble', '1497594013', '20000', '0.00', '1', '金币', '111', '充值', null);
INSERT INTO `g_user_pay` VALUES ('135', '1', '平台', '52', '6543211', 'lrdouble', '1497594363', '999999', '0.00', '1', '房卡', '111', '扣除', null);
INSERT INTO `g_user_pay` VALUES ('136', '1', '平台', '52', '6543211', 'lrdouble', '1497594376', '0', '0.00', '1', '房卡', '111', '充值', null);
INSERT INTO `g_user_pay` VALUES ('137', '1', '平台', '52', '6543211', 'lrdouble', '1497594582', '0', '0.00', '1', '房卡', '111', '充值', null);
INSERT INTO `g_user_pay` VALUES ('138', '1', '平台', '52', '6543211', 'lrdouble', '1497594617', '0', '0.00', '1', '房卡', '111', '充值', null);
INSERT INTO `g_user_pay` VALUES ('139', '1', '平台', '52', '6543211', 'lrdouble', '1497594763', '3', '0.00', '1', '房卡', '111', '充值', null);
INSERT INTO `g_user_pay` VALUES ('140', '1', '平台', '52', '6543211', 'lrdouble', '1497594806', '1002428', '0.00', '1', '房卡', '111', '充值', null);
INSERT INTO `g_user_pay` VALUES ('141', '1', '平台', '52', '6543211', 'lrdouble', '1497594999', '999999', '0.00', '1', '金币', '111', '充值', null);
INSERT INTO `g_user_pay` VALUES ('142', '1', '平台', '52', '6543211', 'lrdouble', '1497595012', '10', '0.00', '1', '房卡', '111', '扣除', null);
INSERT INTO `g_user_pay` VALUES ('143', '1', '平台', '52', '6543211', 'lrdouble', '1497604185', '10', '0.00', '1', '房卡', '111', '充值', null);
INSERT INTO `g_user_pay` VALUES ('144', '1', '平台', '52', '6543211', 'lrdouble', '1497604205', '10', '0.00', '1', '房卡', '111', '扣除', null);
INSERT INTO `g_user_pay` VALUES ('145', '1', '平台', '52', '6543211', 'lrdouble', '1497604205', '10', '0.00', '1', '房卡', '111', '扣除', null);
INSERT INTO `g_user_pay` VALUES ('146', '1', '平台', '52', '6543211', 'lrdouble', '1497604213', '10', '0.00', '1', '房卡', '111', '扣除', null);
INSERT INTO `g_user_pay` VALUES ('147', '1', '平台', '52', '6543211', 'lrdouble', '1497604330', '30', '0.00', '1', '房卡', '111', '充值', null);
INSERT INTO `g_user_pay` VALUES ('148', '1', '平台', '52', '6543211', 'lrdouble', '1497604488', '100', '0.00', '1', '房卡', '111', '扣除', null);
INSERT INTO `g_user_pay` VALUES ('149', '1', '平台', '52', '6543211', 'lrdouble', '1497604554', '100', '0.00', '1', '房卡', '111', '充值', null);
INSERT INTO `g_user_pay` VALUES ('150', '1', '平台', '52', '6543211', 'lrdouble', '1497604581', '100', '0.00', '1', '房卡', '111', '扣除', null);
INSERT INTO `g_user_pay` VALUES ('151', '1', '平台', '52', '6543211', 'lrdouble', '1497604609', '100', '0.00', '1', '房卡', '111', '充值', null);
INSERT INTO `g_user_pay` VALUES ('152', '1', '平台', '52', '6543211', 'lrdouble', '1497604624', '0', '0.00', '1', '房卡', '111', '充值', null);
INSERT INTO `g_user_pay` VALUES ('153', '1', '平台', '52', '6543211', 'lrdouble', '1497604732', '0', '0.00', '1', '房卡', '111', '充值', null);
INSERT INTO `g_user_pay` VALUES ('154', '1', '平台', '52', '6543211', 'lrdouble', '1497604801', '1', '0.00', '1', '房卡', '111', '充值', null);
INSERT INTO `g_user_pay` VALUES ('155', '1', '平台', '52', '6543211', 'lrdouble', '1497604869', '10', '0.00', '1', '房卡', '111', '充值', null);
INSERT INTO `g_user_pay` VALUES ('156', '1', '平台', '52', '6543211', 'lrdouble', '1497604877', '10', '0.00', '1', '金币', '111', '充值', null);
INSERT INTO `g_user_pay` VALUES ('157', '1', '平台', '52', '6543211', 'lrdouble', '1497605026', '1', '0.00', '1', '房卡', '111', '充值', null);
INSERT INTO `g_user_pay` VALUES ('158', '1', '平台', '52', '6543211', 'lrdouble', '1497605063', '1', '0.00', '1', '房卡', '111', '扣除', null);
INSERT INTO `g_user_pay` VALUES ('159', '1', '平台', '52', '6543211', 'lrdouble', '1497605063', '1', '0.00', '1', '房卡', '111', '扣除', null);
INSERT INTO `g_user_pay` VALUES ('160', '1', '平台', '52', '6543211', 'lrdouble', '1497605064', '1', '0.00', '1', '房卡', '111', '扣除', null);
INSERT INTO `g_user_pay` VALUES ('161', '1', '平台', '52', '6543211', 'lrdouble', '1497605064', '1', '0.00', '1', '房卡', '111', '扣除', null);
INSERT INTO `g_user_pay` VALUES ('162', '1', '平台', '52', '6543211', 'lrdouble', '1497605064', '1', '0.00', '1', '房卡', '111', '扣除', null);
INSERT INTO `g_user_pay` VALUES ('163', '1', '平台', '52', '6543211', 'lrdouble', '1497605065', '1', '0.00', '1', '房卡', '111', '扣除', null);
INSERT INTO `g_user_pay` VALUES ('164', '1', '平台', '52', '6543211', 'lrdouble', '1497605065', '1', '0.00', '1', '房卡', '111', '扣除', null);
INSERT INTO `g_user_pay` VALUES ('165', '1', '平台', '52', '6543211', 'lrdouble', '1497605065', '1', '0.00', '1', '房卡', '111', '扣除', null);
INSERT INTO `g_user_pay` VALUES ('166', '1', '平台', '52', '6543211', 'lrdouble', '1497605066', '1', '0.00', '1', '房卡', '111', '扣除', null);
INSERT INTO `g_user_pay` VALUES ('167', '1', '平台', '52', '6543211', 'lrdouble', '1497605077', '1', '0.00', '1', '房卡', '111', '扣除', null);
INSERT INTO `g_user_pay` VALUES ('168', '1', '平台', '52', '6543211', 'lrdouble', '1497605086', '3', '0.00', '1', '房卡', '111', '扣除', null);
INSERT INTO `g_user_pay` VALUES ('169', '1', '平台', '52', '6543211', 'lrdouble', '1497605112', '1', '0.00', '1', '房卡', '111', '充值', null);
INSERT INTO `g_user_pay` VALUES ('170', '1', '平台', '52', '6543211', 'lrdouble', '1497605140', '1', '0.00', '1', '金币', '111', '充值', null);
INSERT INTO `g_user_pay` VALUES ('171', '1', '平台', '52', '6543211', 'lrdouble', '1497605281', '10', '0.00', '1', '房卡', '111', '充值', null);
INSERT INTO `g_user_pay` VALUES ('172', '1', '平台', '52', '6543211', 'lrdouble', '1497605295', '10', '0.00', '1', '房卡', '111', '充值', null);
INSERT INTO `g_user_pay` VALUES ('173', '1', '平台', '52', '6543211', 'lrdouble', '1497605346', '20', '0.00', '1', '房卡', '111', '扣除', null);
INSERT INTO `g_user_pay` VALUES ('174', '1', '平台', '52', '6543211', 'lrdouble', '1497605390', '1', '0.00', '1', '金币', '111', '扣除', null);
INSERT INTO `g_user_pay` VALUES ('175', '31', '刘玉', '52', '6543211', 'lrdouble', '1497606153', '100', '0.00', '1', '房卡', null, '充值', null);
INSERT INTO `g_user_pay` VALUES ('176', '31', '刘玉', '52', '6543211', 'lrdouble', '1497606280', '1', '0.00', '1', '房卡', null, '充值', null);
INSERT INTO `g_user_pay` VALUES ('177', '1', '平台', '52', '6543211', 'lrdouble', '1497606309', '5', '0.00', '1', '金币', '111', '扣除', null);
INSERT INTO `g_user_pay` VALUES ('178', '1', '平台', '52', '6543211', 'lrdouble', '1497606598', '1', '0.00', '1', '金币', '111', '扣除', null);
INSERT INTO `g_user_pay` VALUES ('179', '1', '平台', '52', '6543211', 'lrdouble', '1497606639', '1', '0.00', '1', '房卡', '111', '充值', null);
INSERT INTO `g_user_pay` VALUES ('180', '1', '平台', '52', '6543211', 'lrdouble', '1497606672', '1', '0.00', '1', '房卡', '111', '充值', null);
INSERT INTO `g_user_pay` VALUES ('181', '1', '平台', '52', '6543211', 'lrdouble', '1497606698', '10', '0.00', '1', '房卡', '111', '扣除', null);
INSERT INTO `g_user_pay` VALUES ('182', '1', '平台', '52', '6543211', 'lrdouble', '1497606713', '93', '0.00', '1', '房卡', '111', '扣除', null);
INSERT INTO `g_user_pay` VALUES ('183', '1', '平台', '52', '6543211', 'lrdouble', '1497606726', '1', '0.00', '1', '房卡', '111', '扣除', null);
INSERT INTO `g_user_pay` VALUES ('184', '1', '平台', '52', '6543211', 'lrdouble', '1497606740', '2', '0.00', '1', '房卡', '111', '扣除', null);
INSERT INTO `g_user_pay` VALUES ('185', '1', '平台', '52', '6543211', 'lrdouble', '1497606775', '3', '0.00', '1', '房卡', '111', '充值', null);
INSERT INTO `g_user_pay` VALUES ('186', '1', '平台', '52', '6543211', 'lrdouble', '1497606797', '1009889', '0.00', '1', '金币', '111', '扣除', null);
INSERT INTO `g_user_pay` VALUES ('187', '1', '平台', '52', '6543211', 'lrdouble', '1497606808', '1', '0.00', '1', '金币', '111', '扣除', null);
INSERT INTO `g_user_pay` VALUES ('188', '1', '平台', '52', '6543211', 'lrdouble', '1497606842', '1', '0.00', '1', '房卡', '111', '扣除', null);
INSERT INTO `g_user_pay` VALUES ('189', '1', '平台', '52', '6543211', 'lrdouble', '1497606877', '1', '0.00', '1', '房卡', '111', '充值', null);
INSERT INTO `g_user_pay` VALUES ('190', '1', '平台', '52', '6543211', 'lrdouble', '1497606888', '1', '0.00', '1', '金币', '111', '充值', null);
INSERT INTO `g_user_pay` VALUES ('191', '1', '平台', '52', '6543211', 'lrdouble', '1497853492', '100', '0.00', '1', '房卡', '111', '充值', null);
INSERT INTO `g_user_pay` VALUES ('192', '1', '平台', '52', '6543211', 'lrdouble', '1497853523', '99', '0.00', '1', '房卡', '111', '扣除', null);
INSERT INTO `g_user_pay` VALUES ('193', '1', '平台', '52', '6543211', 'lrdouble', '1497853551', '1', '0.00', '1', '房卡', '111', '扣除', null);
INSERT INTO `g_user_pay` VALUES ('194', '1', '平台', '52', '6543211', 'lrdouble', '1497853580', '100', '0.00', '1', '金币', '111', '充值', null);
INSERT INTO `g_user_pay` VALUES ('195', '1', '平台', '52', '6543211', 'lrdouble', '1497853590', '100', '0.00', '1', '金币', '111', '扣除', null);
INSERT INTO `g_user_pay` VALUES ('196', '1', '平台', '52', '6543211', 'lrdouble', '1497856577', '100', '0.00', '1', '房卡', '111', '充值', null);
INSERT INTO `g_user_pay` VALUES ('197', null, '客户端', '52', '6543211', 'lrdouble', '20000', '100', '2000.00', '1', '金币', null, '充值', '45454545sdfsfsfs');
INSERT INTO `g_user_pay` VALUES ('198', '1', '平台', '52', '6543211', 'lrdouble', '1497927518', '100', '0.00', '1', '房卡', '111', '充值', null);
INSERT INTO `g_user_pay` VALUES ('199', '1', '平台', '52', '6543211', 'lrdouble', '1498448656', '1', '0.00', '1', '房卡', '111', '充值', null);

-- ----------------------------
-- Table structure for g_vip_benefit
-- ----------------------------
DROP TABLE IF EXISTS `g_vip_benefit`;
CREATE TABLE `g_vip_benefit` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'vip每日福利',
  `type` int(2) DEFAULT NULL COMMENT '类型',
  `number` int(10) NOT NULL COMMENT '领取数量',
  `grade` varchar(20) DEFAULT NULL COMMENT 'vip等级',
  `manage_name` varchar(20) DEFAULT NULL COMMENT '修改人名字',
  `manage_id` int(2) DEFAULT NULL COMMENT '修改人ID',
  `updated_at` int(14) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of g_vip_benefit
-- ----------------------------
INSERT INTO `g_vip_benefit` VALUES ('1', '1', '100', 'vip1', '龙龙', '1', '1493094212');
INSERT INTO `g_vip_benefit` VALUES ('2', '2', '200', 'vip2', '龙龙', '1', '1493094212');
INSERT INTO `g_vip_benefit` VALUES ('3', '3', '300', 'vip3', '龙龙', '1', '1493094212');
INSERT INTO `g_vip_benefit` VALUES ('4', '4', '400', 'vip4', '龙龙', '1', '1493094212');
INSERT INTO `g_vip_benefit` VALUES ('5', '5', '500', 'vip5', '龙龙', '1', '1493094212');
INSERT INTO `g_vip_benefit` VALUES ('6', '6', '800', 'vip6', '龙龙', '1', '1493094212');
INSERT INTO `g_vip_benefit` VALUES ('7', '7', '1000', 'vip7', '龙龙', '1', '1493094212');
INSERT INTO `g_vip_benefit` VALUES ('8', '8', '1200', 'vip8', '龙龙', '1', '1493094212');
INSERT INTO `g_vip_benefit` VALUES ('9', '9', '1400', 'vip9', '龙龙', '1', '1493094212');
INSERT INTO `g_vip_benefit` VALUES ('10', '10', '1234567890', 'vip10', 'lrdouble', '1', '1499320013');

-- ----------------------------
-- Table structure for g_vip_update
-- ----------------------------
DROP TABLE IF EXISTS `g_vip_update`;
CREATE TABLE `g_vip_update` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'vip等级升级管理',
  `type` int(2) DEFAULT NULL COMMENT '类型  1:  0-1级  2: 1-2级   3: 2-3级 4: 3-4级 5: 4-5级 6: 5-6级   7: 6-7级   8: 8-9级 9: 9-10级        ',
  `number` int(10) DEFAULT NULL COMMENT '所需钻石数量',
  `give_gold_num` int(10) DEFAULT NULL COMMENT '赠送金币数量',
  `manage_name` varchar(20) DEFAULT NULL COMMENT '修改人',
  `manage_id` int(2) DEFAULT NULL COMMENT '修改人ID',
  `updated_at` int(14) DEFAULT NULL COMMENT '修改时间',
  `grade` varchar(20) DEFAULT NULL COMMENT '等级',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of g_vip_update
-- ----------------------------
INSERT INTO `g_vip_update` VALUES ('1', '1', '100', '100', 'lrdouble', '1', '1499313598', '0-1级');
INSERT INTO `g_vip_update` VALUES ('2', '2', '100', '100', '龙龙', '1', null, '1-2级');
INSERT INTO `g_vip_update` VALUES ('3', '3', '1000', '100', '龙龙', '1', null, '2-3级');
INSERT INTO `g_vip_update` VALUES ('4', '4', '444', '444', '龙龙', '1', null, '3-4级');
INSERT INTO `g_vip_update` VALUES ('5', '5', '100', '200', '龙龙', '1', null, '4-5级');
INSERT INTO `g_vip_update` VALUES ('6', '6', '666', '666', '龙龙', '1', null, '5-6级');
INSERT INTO `g_vip_update` VALUES ('7', '7', '777', '777', '龙龙', '1', null, '6-7级');
INSERT INTO `g_vip_update` VALUES ('8', '8', '888', '888', '龙', '1', null, '7-8级');
INSERT INTO `g_vip_update` VALUES ('9', '9', '9999', '999', '99', '1', null, '8-9级');
INSERT INTO `g_vip_update` VALUES ('10', '10', '101001', '0', 'lrdouble', '1', '1499313512', '9-10级');

-- ----------------------------
-- Table structure for g_withdraw
-- ----------------------------
DROP TABLE IF EXISTS `g_withdraw`;
CREATE TABLE `g_withdraw` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '族长提现',
  `game_id` int(11) DEFAULT NULL COMMENT '族长ID',
  `phone` int(11) DEFAULT NULL COMMENT '手机号码',
  `nickname` varchar(20) DEFAULT NULL COMMENT '族长名字',
  `gold` int(20) DEFAULT NULL COMMENT '提现金额',
  `status` int(2) DEFAULT '0' COMMENT '状态:   0:申请中  1:提现完成   2.提现拒绝',
  `bank_card` varchar(30) DEFAULT NULL COMMENT '银行卡号',
  `bank_name` varchar(20) DEFAULT NULL COMMENT '银行卡用户名',
  `bank_opening` varchar(20) DEFAULT NULL COMMENT '银行卡  开户行',
  `reg_time` int(14) DEFAULT NULL COMMENT '申请时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of g_withdraw
-- ----------------------------
INSERT INTO `g_withdraw` VALUES ('1', '510321', '1321989098', '是否', '1000', '2', '6212265448587875585855', '刘雨欣', '中国工商银行', '1497341707');
INSERT INTO `g_withdraw` VALUES ('2', '510320', '52525', '是否', '1000', '2', '6212265448587875585855', '刘雨欣', '中国工商银行', '1497341707');
