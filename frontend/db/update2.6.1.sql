ALTER TABLE `q_instance` ADD COLUMN `autoRestore` tinyint NULL DEFAULT 0 AFTER `backupKeepDays`;
ALTER TABLE `q_solution` ADD `updatedDate` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `createdAt`;
