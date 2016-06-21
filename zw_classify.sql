/*
Navicat MySQL Data Transfer

Source Server         : zzzzwu
Source Server Version : 50540
Source Host           : localhost:3306
Source Database       : xdzl

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2016-06-21 14:54:23
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for zw_classify
-- ----------------------------
DROP TABLE IF EXISTS `zw_classify`;
CREATE TABLE `zw_classify` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `termid` bigint(20) NOT NULL,
  `classifyname` longtext,
  `pageid` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=120 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for zw_classifypost
-- ----------------------------
DROP TABLE IF EXISTS `zw_classifypost`;
CREATE TABLE `zw_classifypost` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `postid` bigint(20) NOT NULL,
  `classifyid` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for zw_ipaccess
-- ----------------------------
DROP TABLE IF EXISTS `zw_ipaccess`;
CREATE TABLE `zw_ipaccess` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `IP` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `accesscount` bigint(20) DEFAULT NULL,
  `accesstime` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for zw_weiboshow
-- ----------------------------
DROP TABLE IF EXISTS `zw_weiboshow`;
CREATE TABLE `zw_weiboshow` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `itemid` bigint(20) NOT NULL,
  `weiboshow` longtext NOT NULL,
  `classname` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
