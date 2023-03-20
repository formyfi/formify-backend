# ************************************************************
# Sequel Ace SQL dump
# Version 20046
#
# https://sequel-ace.com/
# https://github.com/Sequel-Ace/Sequel-Ace
#
# Host: 127.0.0.1 (MySQL 8.0.32)
# Database: inquisitform
# Generation Time: 2023-03-14 14:43:41 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
SET NAMES utf8mb4;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE='NO_AUTO_VALUE_ON_ZERO', SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table checklist_data
# ------------------------------------------------------------

DROP TABLE IF EXISTS `checklist_data`;

CREATE TABLE `checklist_data` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `checklist_vnum_record_id` int DEFAULT NULL,
  `form_data` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



# Dump of table checklist_vnum_record
# ------------------------------------------------------------

DROP TABLE IF EXISTS `checklist_vnum_record`;

CREATE TABLE `checklist_vnum_record` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `form_id` int DEFAULT NULL,
  `station_id` int DEFAULT NULL,
  `part_id` int DEFAULT NULL,
  `vnum_id` int DEFAULT NULL,
  `compliance_ind` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



# Dump of table form_mapping
# ------------------------------------------------------------

DROP TABLE IF EXISTS `form_mapping`;

CREATE TABLE `form_mapping` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `form_id` int DEFAULT NULL,
  `node_id` int DEFAULT NULL,
  `node_type` varchar(100) DEFAULT NULL,
  `parent_node_type` varchar(100) DEFAULT NULL,
  `metadata` text,
  `order` tinyint DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



# Dump of table forms
# ------------------------------------------------------------

DROP TABLE IF EXISTS `forms`;

CREATE TABLE `forms` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(200) DEFAULT NULL,
  `station_id` int DEFAULT NULL,
  `part_id` int DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



# Dump of table migrations
# ------------------------------------------------------------

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;

INSERT INTO `migrations` (`id`, `migration`, `batch`)
VALUES
	(1,'2019_12_14_000001_create_personal_access_tokens_table',1);

/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table organization
# ------------------------------------------------------------

DROP TABLE IF EXISTS `organization`;

CREATE TABLE `organization` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `org_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `org_description` varchar(200) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



# Dump of table part_vnumber
# ------------------------------------------------------------

DROP TABLE IF EXISTS `part_vnumber`;

CREATE TABLE `part_vnumber` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `v_num` int DEFAULT NULL,
  `part_id` int DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



# Dump of table parts
# ------------------------------------------------------------

DROP TABLE IF EXISTS `parts`;

CREATE TABLE `parts` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `org_id` tinyint DEFAULT NULL,
  `station_id` tinyint DEFAULT NULL,
  `image` blob,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



# Dump of table personal_access_tokens
# ------------------------------------------------------------

DROP TABLE IF EXISTS `personal_access_tokens`;

CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`)
VALUES
	(1,'App\\Models\\User',1,'API TOKEN','9c8e8b17e9b74a7d1b42d2835e41cc5f5b9494befb67f094b8f3791fbb82d8c1','[\"*\"]',NULL,NULL,'2023-03-12 00:16:03','2023-03-12 00:16:03'),
	(2,'App\\Models\\User',2,'API TOKEN','06800534162f9caa6e474ffe21f604682f58978a42c9f92eab42953025dbde13','[\"*\"]',NULL,NULL,'2023-03-12 00:16:59','2023-03-12 00:16:59'),
	(3,'App\\Models\\User',2,'API TOKEN','f027efe3a127f7750fb33d2fac0017be1d694c5af14ff133dafd9d3c187854c4','[\"*\"]',NULL,NULL,'2023-03-12 00:20:31','2023-03-12 00:20:31'),
	(4,'App\\Models\\User',2,'API TOKEN','4e9241edbdeb651641a42797286676ad6ff5ba77bfde25ba3fec54aea3ed801c','[\"*\"]',NULL,NULL,'2023-03-12 14:57:08','2023-03-12 14:57:08'),
	(5,'App\\Models\\User',2,'API TOKEN','acebbde2abc206504476a2f65e66441a7f4532738103e589df7dd8103735b349','[\"*\"]',NULL,NULL,'2023-03-12 15:09:35','2023-03-12 15:09:35'),
	(6,'App\\Models\\User',2,'API TOKEN','021f0a40319475859012ce446d14f2490a974f1e2d460dfb39b1969b583fbf60','[\"*\"]',NULL,NULL,'2023-03-12 15:11:59','2023-03-12 15:11:59'),
	(7,'App\\Models\\User',10,'API TOKEN','f6c0dca531972febdc1911e3c56c54d9f741feb7a37d904c4d36049abc29c22d','[\"*\"]',NULL,NULL,'2023-03-12 19:21:13','2023-03-12 19:21:13'),
	(8,'App\\Models\\User',13,'API TOKEN','a64b4a7246b57a665b712fef7a51d577f06dce85b73f7e2d67efc9c8a2d01864','[\"*\"]',NULL,NULL,'2023-03-12 20:02:27','2023-03-12 20:02:27'),
	(9,'App\\Models\\User',14,'API TOKEN','7b5b0107e7d9c80ae287fc52afad4a62d396a8ee0313279a6d50a52514df2936','[\"*\"]',NULL,NULL,'2023-03-12 20:02:52','2023-03-12 20:02:52'),
	(10,'App\\Models\\User',15,'API TOKEN','a4ba014b315290123768156e1778955466a966cac62021cfd3d23cc5289167ad','[\"*\"]',NULL,NULL,'2023-03-12 20:03:05','2023-03-12 20:03:05'),
	(11,'App\\Models\\User',2,'API TOKEN','d151ad0def4bc4b881e7d4d0374b51d86a515b209df6bacc91ade7e47feb12e8','[\"*\"]','2023-03-12 20:53:25',NULL,'2023-03-12 20:53:09','2023-03-12 20:53:25'),
	(12,'App\\Models\\User',2,'API TOKEN','8f52efb6ea11b3007939352c4a4594b29271d777dc6c806f6a3d948cdaa3a2fe','[\"*\"]','2023-03-13 00:07:43',NULL,'2023-03-13 00:06:17','2023-03-13 00:07:43'),
	(13,'App\\Models\\User',18,'API TOKEN','35de5100dced629000a40ea4fd5267093654907c00e731bd2d338b21b244fc0b','[\"*\"]',NULL,NULL,'2023-03-13 00:07:29','2023-03-13 00:07:29'),
	(14,'App\\Models\\User',19,'API TOKEN','21560a695e4573c7753485356082402758cc3c98c379aa874d413d1a5b16452b','[\"*\"]',NULL,NULL,'2023-03-13 00:07:43','2023-03-13 00:07:43'),
	(15,'App\\Models\\User',11,'API TOKEN','dee9d4f2cecd30210f30d6eee8acac2b574468caff9d57a3bbac1e3e61bae043','[\"*\"]','2023-03-13 00:37:53',NULL,'2023-03-13 00:24:14','2023-03-13 00:37:53');

/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table stations
# ------------------------------------------------------------

DROP TABLE IF EXISTS `stations`;

CREATE TABLE `stations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `org_id` int DEFAULT NULL,
  `active` tinyint(1) DEFAULT '1',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



# Dump of table user_station
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_station`;

CREATE TABLE `user_station` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `station_id` int DEFAULT NULL,
  `primary_ind` tinyint DEFAULT '1',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(20) DEFAULT NULL,
  `last_name` varchar(20) DEFAULT NULL,
  `user_name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `org_id` int DEFAULT NULL,
  `email_id` varchar(200) DEFAULT NULL,
  `user_type` int DEFAULT '2',
  `active` tinyint(1) DEFAULT '1',
  `phone_number` varchar(200) DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO `users` (`id`, `first_name`, `last_name`, `user_name`, `password`, `org_id`, `email_id`, `user_type`, `active`, `phone_number`, `gender`, `dob`, `updated_at`, `created_at`)
VALUES
	(10,'Yash','Sanathara','Yash@akshay.com','$2y$10$sbFfqwHN13c9ZH2mfl9E0OTHHLCbhZGZsVmLKy/4mEqxOiw4.tqZW',8,NULL,1,1,NULL,NULL,NULL,'2023-03-12 20:23:37','2023-03-12 20:23:37'),
	(11,'Akshay','Patel','aki@akshay.com','$2y$10$UWX080rYIAsIZyt2dC3yT.QY8aSWh8yjd3IrlDiH9T/XY8GbZyEhy',8,NULL,1,1,NULL,NULL,NULL,'2023-03-12 20:37:53','2023-03-12 20:23:44'),
	(12,'Yash','Sanathara','bhrugu@akshay.com','$2y$10$xumnSnNjYSHF.iq6UkR7R.sqgMTO6B0UnvG/zXdbgZrGx8bPFcht2',8,NULL,1,1,NULL,NULL,NULL,'2023-03-12 20:23:50','2023-03-12 20:23:50'),
	(13,'Yash','Sanathara','kaushal@akshay.com','$2y$10$iHDB5CyK4QcFmmpWGIDleOYc1BqYHhevpSN59Je1UTpGuflhqxIyq',8,NULL,1,1,NULL,NULL,NULL,'2023-03-12 20:23:58','2023-03-12 20:23:58'),
	(14,'Yash','Sanathara','kwisha@akshay.com','$2y$10$d8GIR6TibZ0hStJvseuzCOsIJTeElrp/g.AIBFo64keoef3stZhQW',8,NULL,1,1,NULL,NULL,NULL,'2023-03-12 20:24:06','2023-03-12 20:24:06');

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table workflow
# ------------------------------------------------------------

DROP TABLE IF EXISTS `workflow`;

CREATE TABLE `workflow` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `part_id` int DEFAULT NULL,
  `name` varchar(20) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



# Dump of table workflow_mapping
# ------------------------------------------------------------

DROP TABLE IF EXISTS `workflow_mapping`;

CREATE TABLE `workflow_mapping` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `workflow_id` int DEFAULT NULL,
  `workflow_node_id` varchar(200) DEFAULT NULL,
  `parent_type` varchar(20) DEFAULT NULL,
  `content_type` varchar(20) DEFAULT NULL,
  `metadata` text,
  `order` tinyint(1) DEFAULT '1',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;