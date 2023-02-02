ALTER TABLE `q_instance` ADD COLUMN `autoRestore` tinyint NULL DEFAULT 0 AFTER `backupKeepDays`;
