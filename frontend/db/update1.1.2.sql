CREATE TABLE `q_backup` (
  `id` mediumint unsigned NOT NULL AUTO_INCREMENT,
  `instance` mediumint(8) unsigned NOT NULL,
  `backupName` varchar(200) NOT NULL,
  `backupAt` datetime NOT NULL,
  `backupStatus` char(20) NOT NULL,
  `storageName` varchar(500) NOT NULL,
  `archives` text,
  `restoreName` varchar(200) NOT NULL,
  `restoreAt` datetime NOT NULL,
  `restoreStatus` char(20) NOT NULL,
  `restoreLogs` text,
  `createdBy` char(30) NOT NULL,
  `createdAt` datetime NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `instance` (`instance`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
