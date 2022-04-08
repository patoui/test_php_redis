CREATE DATABASE IF NOT EXISTS `db`;

USE mysql;
CREATE USER 'admin'@'%' IDENTIFIED BY 'strong-password';
GRANT ALL ON *.* TO 'admin'@'%';
FLUSH PRIVILEGES;

USE `db`;

CREATE TABLE IF NOT EXISTS `events` (
  `event_id` BINARY(16) NOT NULL,
  `aggregate_root_id` BINARY(16) NOT NULL,
  `version` int(20) unsigned NULL,
  `payload` varchar(16001) NOT NULL,
  PRIMARY KEY (`event_id`),
  KEY (`aggregate_root_id`),
  KEY `reconstitution` (`aggregate_root_id`, `version` ASC)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `users` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `uuid` CHAR(36) UNIQUE NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) UNIQUE NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `accounts` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `balance` BIGINT NOT NULL DEFAULT 0,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE=InnoDB;
