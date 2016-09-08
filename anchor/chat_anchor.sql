/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50711
Source Host           : localhost:3306
Source Database       : yang

Target Server Type    : MYSQL
Target Server Version : 50711
File Encoding         : 65001

Date: 2016-08-31 18:03:47
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for chat_anchor
-- ----------------------------
DROP TABLE IF EXISTS `chat_anchor`;
CREATE TABLE `chat_anchor` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键，自增',
  `uid` varchar(45) NOT NULL COMMENT '登录账号',
  `pwd` varchar(45) NOT NULL DEFAULT '' COMMENT '登录密码',
  `name` varchar(45) NOT NULL DEFAULT '' COMMENT '姓名',
  `photo` varchar(256) NOT NULL DEFAULT '' COMMENT '头像',
  `introduction` text COMMENT '简介',
  `is_online` tinyint(4) DEFAULT NULL COMMENT '是否上线',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of chat_anchor
-- ----------------------------
INSERT INTO `chat_anchor` VALUES ('30', '123123', '4297f44b13955235245b2497399d7a93', '123123', 'image/2016/08/31/4990787de11666d9d94edbc718ddf071.jpg', '<p>dsadsa</p>', '0');
INSERT INTO `chat_anchor` VALUES ('31', '456456', '017cbb2b827ceca07a4620a4', '456456', 'image/2016/08/31/f6b358a3380c260f96ad30c14d77eeba.png', '<p>456456</p>', '0');
