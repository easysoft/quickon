DROP TABLE IF EXISTS `q_app`;
CREATE TABLE IF NOT EXISTS `q_app` (
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
