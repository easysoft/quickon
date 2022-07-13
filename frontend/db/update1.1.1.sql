ALTER TABLE `q_instance` ADD COLUMN `introduction` varchar(500) NOT NULL AFTER `desc`;
ALTER TABLE `q_instance` ADD COLUMN `channel` char(20) NOT NULL AFTER `source`;

UPDATE `q_instance` SET `introduction` = `desc` WHERE id>0;
