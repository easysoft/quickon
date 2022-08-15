DROP TABLE IF EXISTS `q_space`;
CREATE TABLE `q_space` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `k8space` char(64) NOT NULL,
  `owner` char(30) NOT NULL,
  `default` tinyint(1) NOT NULL DEFAULT 0,
  `createdAt` datetime NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `q_instance`;
CREATE TABLE IF NOT EXISTS `q_instance` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `space` mediumint(8) unsigned NOT NULL,
  `name` char(50),
  `appID` mediumint(8) unsigned NOT NULL,
  `appName` char(50) NOT NULL,
  `appVersion` char(20) NOT NULL,
  `chart` char(50) NOT NULL,
  `logo` varchar(255),
  `version` char(50) NOT NULL,
  `desc` text,
  `introduction` varchar(500),
  `source` char(20) NOT NULL,
  `channel` char(20),
  `k8name` char(64) NOT NULL,
  `status` char(20) NOT NULL,
  `pinned` enum('0', '1') NOT NULL DEFAULT '0',
  `domain` char(255) NOT NULL,
  `dbSettings` text,
  `env` text,
  `createdBy` char(30) NOT NULL,
  `createdAt` datetime NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `space` (`space`),
  KEY `k8name` (`k8name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `q_action`;
CREATE TABLE `q_action` (
  `id` mediumint unsigned NOT NULL AUTO_INCREMENT,
  `objectType` varchar(30) NOT NULL DEFAULT '',
  `objectID` mediumint unsigned NOT NULL DEFAULT '0',
  `actor` varchar(100) NOT NULL DEFAULT '',
  `action` varchar(80) NOT NULL DEFAULT '',
  `date` datetime NOT NULL,
  `comment` text NOT NULL,
  `extra` text,
  `read` enum('0','1') NOT NULL DEFAULT '0',
  `vision` varchar(10) NOT NULL DEFAULT 'rnd',
  `efforted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `date` (`date`),
  KEY `actor` (`actor`),
  KEY `action` (`action`),
  KEY `objectID` (`objectID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

DROP TABLE IF EXISTS `q_company`;
CREATE TABLE `q_company` (
  `id` mediumint unsigned NOT NULL AUTO_INCREMENT,
  `name` char(120) DEFAULT NULL,
  `phone` char(20) DEFAULT NULL,
  `fax` char(20) DEFAULT NULL,
  `address` char(120) DEFAULT NULL,
  `zipcode` char(10) DEFAULT NULL,
  `website` char(120) DEFAULT NULL,
  `backyard` char(120) DEFAULT NULL,
  `guest` enum('1','0') NOT NULL DEFAULT '0',
  `admins` char(255) DEFAULT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

DROP TABLE IF EXISTS `q_config`;
CREATE TABLE `q_config` (
  `id` mediumint unsigned NOT NULL AUTO_INCREMENT,
  `owner` char(30) NOT NULL DEFAULT '',
  `module` varchar(30) NOT NULL,
  `section` char(30) NOT NULL DEFAULT '',
  `key` char(30) NOT NULL DEFAULT '',
  `value` longtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique` (`owner`,`module`,`section`,`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

DROP TABLE IF EXISTS `q_file`;
CREATE TABLE `q_file` (
  `id` mediumint unsigned NOT NULL AUTO_INCREMENT,
  `pathname` char(100) NOT NULL,
  `title` char(255) NOT NULL,
  `extension` char(30) NOT NULL,
  `size` int unsigned NOT NULL DEFAULT '0',
  `objectType` char(30) NOT NULL,
  `objectID` mediumint NOT NULL,
  `addedBy` char(30) NOT NULL DEFAULT '',
  `addedDate` datetime NOT NULL,
  `downloads` mediumint unsigned NOT NULL DEFAULT '0',
  `extra` varchar(255) NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `objectType` (`objectType`),
  KEY `objectID` (`objectID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

DROP TABLE IF EXISTS `q_lang`;
CREATE TABLE `q_lang` (
  `id` mediumint unsigned NOT NULL AUTO_INCREMENT,
  `lang` varchar(30) NOT NULL,
  `module` varchar(30) NOT NULL,
  `section` varchar(30) NOT NULL,
  `key` varchar(60) NOT NULL,
  `value` text NOT NULL,
  `system` enum('0','1') NOT NULL DEFAULT '1',
  `vision` varchar(10) NOT NULL DEFAULT 'rnd',
  PRIMARY KEY (`id`),
  UNIQUE KEY `lang` (`lang`,`module`,`section`,`key`,`vision`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

DROP TABLE IF EXISTS `q_user`;
CREATE TABLE `q_user` (
  `id` mediumint unsigned NOT NULL AUTO_INCREMENT,
  `company` mediumint unsigned NOT NULL,
  `type` char(30) NOT NULL DEFAULT 'inside',
  `dept` mediumint unsigned NOT NULL DEFAULT '0',
  `account` char(30) NOT NULL DEFAULT '',
  `password` char(32) NOT NULL DEFAULT '',
  `role` char(10) NOT NULL DEFAULT '',
  `realname` varchar(100) NOT NULL DEFAULT '',
  `pinyin` varchar(255) NOT NULL DEFAULT '',
  `nickname` char(60) NOT NULL DEFAULT '',
  `commiter` varchar(100) NOT NULL,
  `avatar` text NOT NULL,
  `birthday` date NOT NULL DEFAULT '1970-01-01',
  `gender` enum('f','m') NOT NULL DEFAULT 'f',
  `email` char(90) NOT NULL DEFAULT '',
  `skype` char(90) NOT NULL DEFAULT '',
  `qq` char(20) NOT NULL DEFAULT '',
  `mobile` char(11) NOT NULL DEFAULT '',
  `phone` char(20) NOT NULL DEFAULT '',
  `weixin` varchar(90) NOT NULL DEFAULT '',
  `dingding` varchar(90) NOT NULL DEFAULT '',
  `slack` varchar(90) NOT NULL DEFAULT '',
  `whatsapp` varchar(90) NOT NULL DEFAULT '',
  `address` char(120) NOT NULL DEFAULT '',
  `zipcode` char(10) NOT NULL DEFAULT '',
  `nature` text NOT NULL,
  `analysis` text NOT NULL,
  `strategy` text NOT NULL,
  `join` date NOT NULL DEFAULT '1970-01-01',
  `visits` mediumint unsigned NOT NULL DEFAULT '0',
  `visions` varchar(20) NOT NULL,
  `ip` char(15) NOT NULL DEFAULT '',
  `last` int unsigned NOT NULL DEFAULT '0',
  `fails` tinyint NOT NULL DEFAULT '0',
  `locked` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `feedback` enum('0','1') NOT NULL DEFAULT '0',
  `ranzhi` char(30) NOT NULL DEFAULT '',
  `ldap` char(30) NOT NULL,
  `score` int NOT NULL DEFAULT '0',
  `scoreLevel` int NOT NULL DEFAULT '0',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  `clientStatus` enum('online','away','busy','offline','meeting') NOT NULL DEFAULT 'offline',
  `clientLang` varchar(10) NOT NULL DEFAULT 'zh-cn',
  PRIMARY KEY (`id`),
  UNIQUE KEY `account` (`account`),
  KEY `dept` (`dept`),
  KEY `email` (`email`),
  KEY `commiter` (`commiter`),
  KEY `deleted` (`deleted`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

DROP TABLE IF EXISTS `q_usergroup`;
CREATE TABLE `q_usergroup` (
  `account` char(30) NOT NULL DEFAULT '',
  `group` mediumint unsigned NOT NULL DEFAULT '0',
  UNIQUE KEY `account` (`account`,`group`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;


DROP TABLE IF EXISTS `q_group`;
CREATE TABLE `q_group` (
  `id` mediumint unsigned NOT NULL AUTO_INCREMENT,
  `vision` varchar(10) NOT NULL DEFAULT 'rnd',
  `name` char(30) NOT NULL,
  `role` char(30) NOT NULL DEFAULT '',
  `desc` char(255) NOT NULL DEFAULT '',
  `acl` text,
  `developer` enum('0','1') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;


DROP TABLE IF EXISTS `q_grouppriv`;
CREATE TABLE `q_grouppriv` (
  `group` mediumint unsigned NOT NULL DEFAULT '0',
  `module` char(30) NOT NULL DEFAULT '',
  `method` char(30) NOT NULL DEFAULT '',
  UNIQUE KEY `group` (`group`,`module`,`method`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

DROP TABLE IF EXISTS `q_navinstance`;
CREATE TABLE IF NOT EXISTS `q_navinstance` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(50),
  `domain` char(255) NOT NULL,
  `logo` varchar(255),
  `desc` text,
  `pinned` enum('0', '1') NOT NULL DEFAULT '0',
  `createdBy` char(30) NOT NULL,
  `createdAt` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

REPLACE INTO `q_company` ( `name`, `admins`) VALUES ('', '');
REPLACE INTO `q_config` (`owner`, `module`, `section`, `key`, `value`) VALUES ('system', 'common', 'global', 'version', '1.1.1');
