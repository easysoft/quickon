ALTER TABLE `q_instance` ADD `pinned` enum('0', '1') NOT NULL DEFAULT '0' AFTER `status`;
