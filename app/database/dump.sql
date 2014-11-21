# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.5.38)
# Database: bb_management
# Generation Time: 2014-09-23 09:06:32 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table access_log
# ------------------------------------------------------------

DROP TABLE IF EXISTS `access_log`;

CREATE TABLE `access_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `key_fob_id` int(11) NOT NULL,
  `response` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `service` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `access_log` WRITE;
/*!40000 ALTER TABLE `access_log` DISABLE KEYS */;


/*!40000 ALTER TABLE `access_log` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table audit_log
# ------------------------------------------------------------

DROP TABLE IF EXISTS `audit_log`;

CREATE TABLE `audit_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `audit_log` WRITE;
/*!40000 ALTER TABLE `audit_log` DISABLE KEYS */;


/*!40000 ALTER TABLE `audit_log` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table inductions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `inductions`;

CREATE TABLE `inductions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `paid` tinyint(1) NOT NULL,
  `payment_id` int(10) NOT NULL,
  `trained` datetime NULL,
  `trainer_user_id` int(10) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `is_trainer` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `inductions` WRITE;
/*!40000 ALTER TABLE `inductions` DISABLE KEYS */;

INSERT INTO `inductions` (`id`, `user_id`, `key`, `paid`, `payment_id`, `trained`, `trainer_user_id`, `active`, `is_trainer`, `created_at`, `updated_at`)
VALUES
	(4,1,'laser',1,2523,'2014-08-17 02:55:06',0,0,1,'2014-08-17 02:55:04','2014-08-17 02:58:33'),
	(7,1,'welder',1,2580,'2014-09-01 14:46:23',0,0,1,'2014-09-01 14:46:20','2014-09-01 14:46:59'),
	(8,1,'lathe',1,1,NULL,0,0,0,'2014-10-03 21:04:55','2014-10-03 21:04:55');

/*!40000 ALTER TABLE `inductions` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table key_fobs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `key_fobs`;

CREATE TABLE `key_fobs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `key_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  `lost` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `key_fobs` WRITE;
/*!40000 ALTER TABLE `key_fobs` DISABLE KEYS */;

INSERT INTO `key_fobs` (`id`, `user_id`, `key_id`, `active`, `lost`, `created_at`, `updated_at`)
VALUES
	(1,1,'ABCDEF123456',1,0,'2014-08-19 14:03:35','2014-08-19 14:12:36'),
	(2,2,'123456ABCDEF',1,0,'2014-08-19 14:19:33','2014-08-19 14:19:35');

/*!40000 ALTER TABLE `key_fobs` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table migrations
# ------------------------------------------------------------

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;

INSERT INTO `migrations` (`migration`, `batch`)
VALUES
	('2014_07_22_191612_create_users_table',1),
	('2014_08_05_094421_create_inductions_table',1),
	('2014_08_05_120642_create_payments_table',2),
	('2014_08_07_175517_create_password_reminders_table',3),
	('2014_08_09_163708_create_key_fob_table',4),
	('2014_08_19_191220_create_access_log_table',5),
	('2014_08_27_212452_create_audit_log_table',6),
	('2014_09_01_142734_create_storage_box_table',7),
	('2014_09_08_181113_create_roles_table',8),
	('2014_09_08_181153_create_role_user_table',8);

/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table password_reminders
# ------------------------------------------------------------

DROP TABLE IF EXISTS `password_reminders`;

CREATE TABLE `password_reminders` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  KEY `password_reminders_email_index` (`email`),
  KEY `password_reminders_token_index` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table payments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `payments`;

CREATE TABLE `payments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `reason` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `source` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `source_id` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_id` int(11) NOT NULL,
  `amount` double(10,2) NOT NULL,
  `fee` double(10,2) NOT NULL,
  `amount_minus_fee` double(10,2) NOT NULL,
  `status` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `payments_status_index` (`status`),
  KEY `payments_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table role_user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `role_user`;

CREATE TABLE `role_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `role_user_role_id_index` (`role_id`),
  KEY `role_user_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `role_user` WRITE;
/*!40000 ALTER TABLE `role_user` DISABLE KEYS */;

INSERT INTO `role_user` (`id`, `role_id`, `user_id`, `created_at`, `updated_at`)
VALUES
	(1,1,3,'2014-09-08 19:15:42','2014-09-08 19:15:42');

/*!40000 ALTER TABLE `role_user` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table roles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;

INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`)
VALUES
	(1,'admin','2014-09-08 19:15:42','2014-09-08 19:15:42');

/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table storage_boxes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `storage_boxes`;

CREATE TABLE `storage_boxes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `size` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `storage_boxes` WRITE;
/*!40000 ALTER TABLE `storage_boxes` DISABLE KEYS */;

INSERT INTO `storage_boxes` (`id`, `size`, `user_id`, `active`, `created_at`, `updated_at`)
VALUES
	(1,'19L',0,1,'0000-00-00 00:00:00','0000-00-00 00:00:00'),
	(2,'19L',0,1,'0000-00-00 00:00:00','0000-00-00 00:00:00');

/*!40000 ALTER TABLE `storage_boxes` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hash` varchar(50) COLLATE utf8_unicode_ci DEFAULT '',
  `given_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `family_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `import_match_string` varchar(50) COLLATE utf8_unicode_ci DEFAULT '',
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email_verified` tinyint(1) NOT NULL,
  `secondary_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `password` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `address_line_1` varchar(100) COLLATE utf8_unicode_ci DEFAULT '',
  `address_line_2` varchar(100) COLLATE utf8_unicode_ci DEFAULT '',
  `address_line_3` varchar(100) COLLATE utf8_unicode_ci DEFAULT '',
  `address_line_4` varchar(100) COLLATE utf8_unicode_ci DEFAULT '',
  `address_postcode` varchar(10) COLLATE utf8_unicode_ci DEFAULT '',
  `emergency_contact` varchar(250) COLLATE utf8_unicode_ci DEFAULT '',
  `notes` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `active` tinyint(1) NOT NULL,
  `founder` tinyint(1) NOT NULL,
  `director` tinyint(1) NOT NULL,
  `status` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `trusted` tinyint(1) NOT NULL,
  `key_holder` tinyint(1) NOT NULL,
  `key_deposit_payment_id` int(10) DEFAULT NULL,
  `storage_box_payment_id` int(10) DEFAULT NULL,
  `induction_completed` tinyint(1) NOT NULL,
  `payment_method` varchar(20) COLLATE utf8_unicode_ci DEFAULT '',
  `payment_day` int(5) NOT NULL,
  `monthly_subscription` int(5) DEFAULT NULL,
  `subscription_id` varchar(128) COLLATE utf8_unicode_ci DEFAULT '',
  `last_subscription_payment` date DEFAULT NULL,
  `subscription_expires` date DEFAULT NULL,
  `cash_balance` int(11) NOT NULL DEFAULT '0',
  `profile_private` tinyint(1) NOT NULL,
  `banned_date` date DEFAULT NULL,
  `banned_reason` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO `users` (`id`, `hash`, `given_name`, `family_name`, `import_match_string`, `email`, `email_verified`, `secondary_email`, `password`, `address_line_1`, `address_line_2`, `address_line_3`, `address_line_4`, `address_postcode`, `emergency_contact`, `notes`, `active`, `founder`, `director`, `status`, `trusted`, `key_holder`, `key_deposit_payment_id`, `storage_box_payment_id`, `induction_completed`, `payment_method`, `payment_day`, `monthly_subscription`, `subscription_id`, `last_subscription_payment`, `subscription_expires`, `banned_date`, `banned_reason`, `remember_token`, `created_at`, `updated_at`)
VALUES
	(1,'nR35e6L9p1WCrxxqi7ZWLP3pBxnBdT','Jon','Doe','','jondoe@example.com',1,'','$2y$10$eQ4MM5BO68zkBfTnPeTt0.SXqMfnXpOSrkYLPKK3Fc2bEIASoz5dm','Street','','Town','','PostCode','contact','',1,0,0,'active',1,1,2579,2582,1,'standing-order',30,10,'','2014-08-21','2014-10-01',NULL,'','L7dfKVtH1SbM2LFP0LcqTZJQbfZJLkWV7VgrTLxrPRrv8hXDfNwVWwpJ7VfI','2014-08-05 10:23:12','2014-09-16 13:55:56'),
	(2,'nR35e6L9p1WCrxxqi7ZWLP3pBxnBdT','Fred','Bloggs','','fredbloggs@example.com',1,'','$2y$10$eQ4MM5BO68zkBfTnPeTt0.SXqMfnXpOSrkYLPKK3Fc2bEIASoz5dm','Street','','Town','','PostCode','contact','',0,0,0,'left',1,1,NULL,NULL,1,'',30,20,'','2014-08-21','2014-10-01',NULL,'','L7dfKVtH1SbM2LFP0LcqTZJQbfZJLkWV7VgrTLxrPRrv8hXDfNwVWwpJ7VfI','2014-08-05 10:23:12','2014-09-16 13:55:56'),
	(3,'nR35e6L9p1WCrxxqi7ZWLP3pBxnBdT','Steve','Smith','','stevesmith@example.com',1,'','','Street','','Town','','PostCode','contact','',1,0,0,'active',0,0,NULL,NULL,0,'gocardless',1,10,'',NULL,'2014-10-01',NULL,'',NULL,'0000-00-00 00:00:00','0000-00-00 00:00:00');

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

# Dump of table profile_data
# ------------------------------------------------------------

DROP TABLE IF EXISTS `profile_data`;

CREATE TABLE `profile_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `twitter` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `facebook` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `google_plus` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `github` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `irc` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `website` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tagline` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `skills_array` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `profile_photo` tinyint(1) NOT NULL,
  `profile_photo_private` tinyint(1) NOT NULL,
  `new_profile_photo` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `profile_data` WRITE;
/*!40000 ALTER TABLE `profile_data` DISABLE KEYS */;

INSERT INTO `profile_data` (`id`, `user_id`, `twitter`, `facebook`, `google_plus`, `github`, `irc`, `website`, `tagline`, `description`, `skills_array`, `created_at`, `updated_at`)
VALUES
	(1,1,'TwitterHandle',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2014-09-23 22:22:08','2014-09-23 22:22:08'),
	(3,3,'ArthurGuy','ArthurGuy','ArthurGuy','ArthurGuy','','','Snappy tagline goes here','Description text here','[\"midi\",\"3dprinting\",\"arduino\",\"coding\",\"electronics\",\"laser-cutter\",\"welding\",\"wood-work\"]','2014-09-23 22:22:08','2014-09-27 12:53:45'),
	(4,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2014-09-23 22:27:04','2014-09-23 22:27:04');

/*!40000 ALTER TABLE `profile_data` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table equipment_log
# ------------------------------------------------------------

DROP TABLE IF EXISTS `equipment_log`;

CREATE TABLE `equipment_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `key_fob_id` int(11) NOT NULL,
  `device` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  `started` datetime DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  `finished` datetime DEFAULT NULL,
  `notes` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- Create syntax for TABLE 'proposal_votes'
CREATE TABLE `proposal_votes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `proposal_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `vote` int(11) DEFAULT NULL,
  `abstain` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'proposals'
CREATE TABLE `proposals` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `votes_cast` int(11) NOT NULL,
  `votes_for` int(11) NOT NULL,
  `votes_against` int(11) NOT NULL,
  `abstentions` int(11) NOT NULL,
  `quorum` tinyint(1) NOT NULL,
  `result` int(11) NOT NULL,
  `processed` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `proposals` (`id`, `title`, `description`, `user_id`, `start_date`, `end_date`, `votes_cast`, `votes_for`, `votes_against`, `abstentions`, `quorum`, `result`, `processed`, `created_at`, `updated_at`)
VALUES
	(1, 'Proposal 1', 'This is the text for a test proposal', 1, '2014-10-10', '2014-10-13', 0, 0, 0, 0, 0, 0, 0, '2014-10-20 00:00:00', '2014-10-20 00:00:00');


/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
