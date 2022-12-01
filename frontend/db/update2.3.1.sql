ALTER TABLE `q_instance` ADD COLUMN `autoBackup` tinyint NULL DEFAULT 0 AFTER `dbSettings`;
ALTER TABLE `q_instance` ADD COLUMN `backupKeepDays` int unsigned NOT NULL DEFAULT 1 AFTER `autoBackup`;

-- DROP TABLE IF EXISTS `q_cron`;
CREATE TABLE IF NOT EXISTS `q_cron` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `m` varchar(20) NOT NULL,
  `h` varchar(20) NOT NULL,
  `dom` varchar(20) NOT NULL,
  `mon` varchar(20) NOT NULL,
  `dow` varchar(20) NOT NULL,
  `objectID` mediumint unsigned NOT NULL DEFAULT 0,
  `status` varchar(20) NOT NULL,
  `lastTime` datetime,
  PRIMARY KEY (`id`),
  KEY `lastTime` (`lastTime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
