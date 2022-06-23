CREATE TABLE IF NOT EXISTS `q_entry` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `account` varchar(30) NOT NULL DEFAULT '',
  `code` varchar(20) NOT NULL,
  `key` varchar(32) NOT NULL,
  `freePasswd` enum('0','1') NOT NULL DEFAULT '0',
  `ip` varchar(100) NOT NULL,
  `desc` mediumtext NOT NULL,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `calledTime` int(10) unsigned NOT NULL DEFAULT '0',
  `editedBy` varchar(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  `deleted` enum('0', '1') NOT NULL DEFAULT '0',
  PRIMARY KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
