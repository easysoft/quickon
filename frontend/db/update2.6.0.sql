ALTER TABLE `q_instance` ADD COLUMN `solution` mediumint(8) unsigned NOT NULL AFTER `space`;

-- DROP TABLE IF EXISTS `q_solution`;
CREATE TABLE IF NOT EXISTS `q_solution` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(50),
  `appID` mediumint(8) unsigned NOT NULL,
  `appName` char(50) NOT NULL,
  `appVersion` char(20) NOT NULL,
  `version` char(50) NOT NULL,
  `chart` char(50) NOT NULL,
  `cover` varchar(255),
  `desc` text,
  `introduction` varchar(500),
  `source` char(20) NOT NULL,
  `channel` char(20),
  `components` text,
  `status` char(20) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `createdBy` char(30) NOT NULL,
  `createdAt` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
