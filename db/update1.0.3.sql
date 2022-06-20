ALTER TABLE `q_instance` ADD COLUMN `appVersion` char(20) NOT NULL AFTER `appName`;
UPDATE `q_instance` SET `appVersion` = `version` where `id` > 0;
UPDATE `q_instance` SET `version` = '0.0.1' where `id` > 0;
