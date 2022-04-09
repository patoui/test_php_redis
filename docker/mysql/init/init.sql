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
