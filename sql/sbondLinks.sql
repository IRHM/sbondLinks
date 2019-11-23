-- Dumping database structure for sbondlinks
CREATE DATABASE IF NOT EXISTS `sbondlinks`
USE `sbondlinks`;

-- Dumping structure for table sbondlinks.links
CREATE TABLE IF NOT EXISTS `links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `link_key` varchar(50) NOT NULL DEFAULT '0',
  `link` varchar(1000) NOT NULL DEFAULT '0',
  `time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expire` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=128 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping structure for event sbondlinks.remove_links
-- Edit username 'myPC'
DELIMITER //
CREATE DEFINER=`MyPC`@`192.168.%` EVENT `remove_links` ON SCHEDULE EVERY 1 SECOND STARTS '2019-09-18 01:53:21' ON COMPLETION PRESERVE ENABLE DO DELETE FROM links WHERE (time < (NOW() - INTERVAL 1 DAY) AND EXPIRE = 'A Day') or 
(time < (NOW() - INTERVAL 1 WEEK) AND EXPIRE = 'A Week') or
(time < (NOW() - INTERVAL 1 MONTH) AND EXPIRE = 'A Month')//
DELIMITER ;
