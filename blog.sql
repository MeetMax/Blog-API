/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : blog

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2017-01-18 02:41:55
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for admin
-- ----------------------------
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of admin
-- ----------------------------
INSERT INTO `admin` VALUES ('1', '小马', '123456');
INSERT INTO `admin` VALUES ('2', 'admin', '1235555');
INSERT INTO `admin` VALUES ('3', '小红', '123456');

-- ----------------------------
-- Table structure for article
-- ----------------------------
DROP TABLE IF EXISTS `article`;
CREATE TABLE `article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(75) NOT NULL COMMENT '文章标题',
  `content` longtext NOT NULL COMMENT '文章主体',
  `cat_id` int(11) NOT NULL COMMENT '分类id',
  `user_id` int(11) NOT NULL COMMENT '作者id',
  `updated_at` int(11) NOT NULL COMMENT '更新时间',
  `visit` int(11) NOT NULL DEFAULT '0' COMMENT '访问量',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of article
-- ----------------------------
INSERT INTO `article` VALUES ('1', 'Vue2 模板中的图片地址如何使用 webpack 定义的别名?', 'webpack 的别名好处大家也都了解, 但是 vue 的模板中, 对图片地址使用别名时总出现问题, 很久时间的时间都没找到解决办法, 一度认为是 webpack 的原因...', '1', '1', '0', '0');
INSERT INTO `article` VALUES ('2', 'Vue2 SSR 的优化之旅', '自从 Vue2 出正式版后, 就开始了 SSR 之旅, 不过之前用的都是虚拟主机, 部署不了 SSR, 所以也只是在本地写着玩, 双 11 的时候, 买了个某云主机, 正式开始了 SSR 之旅, 然后过程并不顺利, 部署, 运行都没问题, 但是发现内存泄漏严重, 1核1G内存的主机根本负担不了, 没什么访问量的情况下, 几个小时的时间, 1G 内存就用光, 明显有很严重的内存泄漏, 在本地环境压测, rps(每秒请求数) 无限接近于 1, 在云服务器更是压测都完成不了, 于是开始了优化之旅', '1', '0', '0', '0');
INSERT INTO `article` VALUES ('3', 'React 的数据双向绑定', '对于用习惯了Vue的双向绑定后, 对React的双向绑定, 真心觉得蛋疼... Vue的双向绑定, 只需要在data中初始化, 然后用v-model绑定即可, 简单省事... 再来看看React的: ```javascript var NoLink = React.createClass({ getInitialState: function() { return { message1: \'Hello!\', message2: \'Hello!\' }; }, handleChange1: function(event) { this.setState({message1: event.target.value}); }, handleChange2: function(event) { ', '2', '2', '21312434', '0');
INSERT INTO `article` VALUES ('5', '标题修改', '内容', '2', '3', '1484663422', '0');

-- ----------------------------
-- Table structure for category
-- ----------------------------
DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(30) NOT NULL COMMENT '分类名字',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of category
-- ----------------------------
INSERT INTO `category` VALUES ('1', 'Vue');
INSERT INTO `category` VALUES ('4', 'PHP');
INSERT INTO `category` VALUES ('5', 'JAVA');
INSERT INTO `category` VALUES ('6', 'NodeJS');

-- ----------------------------
-- Table structure for comment
-- ----------------------------
DROP TABLE IF EXISTS `comment`;
CREATE TABLE `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT '评论者id',
  `article_id` int(11) NOT NULL COMMENT '评论的文章id',
  `created_at` int(11) NOT NULL COMMENT '评论时间',
  PRIMARY KEY (`id`),
  KEY `article_id` (`article_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of comment
-- ----------------------------
INSERT INTO `comment` VALUES ('3', '哈哈', '3', '1', '1484661453');
INSERT INTO `comment` VALUES ('4', '你好呀', '3', '1', '1484661628');

-- ----------------------------
-- Table structure for praise
-- ----------------------------
DROP TABLE IF EXISTS `praise`;
CREATE TABLE `praise` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `article_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of praise
-- ----------------------------
INSERT INTO `praise` VALUES ('4', '1', '3');

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `access_token` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `auth_key` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` enum('0','1') NOT NULL DEFAULT '0' COMMENT '是否是管理员',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('1', 'max', '', 'Rq7GbzMwy1M7VTMFa7y4iBEcGwROmLok_1484623790', '10', '1484623757', '1484626015', 'm49Hpq4jfFJ-O2XTBnGCwmRmeMdh-nwW', '499282083@qq.com', '0');
INSERT INTO `user` VALUES ('2', 'admin', '$2y$13$8xWhnKHmtg8uET3pSpJoie9rAgmqS6yE7u1QPzk0QyJeV8HV7gWnO', 'iK6jVWSfQ9Zltu7qrtsr5nu95oYk0g28_1484623845', '10', '1484623845', '1484629318', 'JCOvllhLms-NpOfgCoGuE-1ajIh4dKFa', '111@qq.com', '0');
INSERT INTO `user` VALUES ('3', 'meetmax', '$2y$13$n6mAM/mDzEhzzXSNNaTHMO4ZwrGo1tbBaE0RY.ICg2LmZatePbgvi', 'PGS7ixW6SiH-qXXx6xRDcLDH39Xqw154_1484631719', '10', '1484631719', '1484635825', 'AS67MxzcvKmMu2N4kbDNTywDcofob5rf', '123@qqq.com', '1');
