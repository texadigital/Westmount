-- Complete Westmount Association Database Export (Fixed)
-- Generated from local Laravel migrations
-- Database: westmount_association

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

-- Disable foreign key checks
SET FOREIGN_KEY_CHECKS = 0;

-- Table structure for table `users`
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `users`
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
('1','Administrateur Westmount','admin@westmount.ca','2025-09-05 11:47:41','$2y$12$Px6KZfWPRsjr8dJ0JxsX/uwFD4mfEElWC/bAK7aNfDiLRfPqoi/ha',NULL,'2025-09-05 11:47:41','2025-09-05 11:47:41');

-- Table structure for table `member_types`
DROP TABLE IF EXISTS `member_types`;
CREATE TABLE `member_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adhesion_fee` decimal(8,2) NOT NULL,
  `death_contribution` decimal(8,2) NOT NULL,
  `min_age` int DEFAULT NULL,
  `max_age` int DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `member_types`
INSERT INTO `member_types` (`id`, `name`, `description`, `adhesion_fee`, `death_contribution`, `min_age`, `max_age`, `is_active`, `created_at`, `updated_at`) VALUES
('1','régulier','Membre régulier adulte (moins de 68 ans)','50.00','10.00','18','67','1','2025-09-05 11:47:46','2025-09-05 11:47:46'),
('2','senior','Membre senior (68 ans et plus à l\'adhésion)','50.00','2.00','68',NULL,'1','2025-09-05 11:47:46','2025-09-05 11:47:46'),
('3','junior','Membre junior (mineurs)','50.00','2.00',NULL,'17','1','2025-09-05 11:47:46','2025-09-05 11:47:46'),
('4','association','Association ou organisme partenaire','50.00','10.00',NULL,NULL,'1','2025-09-05 11:47:46','2025-09-05 11:47:46');

-- Table structure for table `organizations`
DROP TABLE IF EXISTS `organizations`;
CREATE TABLE `organizations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `business_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_person` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `province` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `postal_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Canada',
  `member_count` int NOT NULL DEFAULT '0',
  `total_fees` decimal(10,2) NOT NULL DEFAULT '0.00',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `organizations_business_number_unique` (`business_number`),
  KEY `organizations_business_number_index` (`business_number`),
  KEY `organizations_is_active_index` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `funds`
DROP TABLE IF EXISTS `funds`;
CREATE TABLE `funds` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `current_balance` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_contributions` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_distributions` decimal(12,2) NOT NULL DEFAULT '0.00',
  `type` enum('general','death_benefit','emergency') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `funds_type_is_active_index` (`type`,`is_active`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `funds`
INSERT INTO `funds` (`id`, `name`, `description`, `current_balance`, `total_contributions`, `total_distributions`, `type`, `is_active`, `created_at`, `updated_at`) VALUES
('1','Fonds Général','Fonds principal de l\'association pour les opérations générales','0.00','0.00','0.00','general','1','2025-09-05 11:47:51','2025-09-05 11:47:51'),
('2','Fonds de Prestation de Décès','Fonds dédié aux prestations de décès pour les familles des membres','0.00','0.00','0.00','death_benefit','1','2025-09-05 11:47:51','2025-09-05 11:47:51'),
('3','Fonds d\'Urgence','Fonds d\'urgence pour les situations critiques','0.00','0.00','0.00','emergency','1','2025-09-05 11:47:51','2025-09-05 11:47:51');

-- Table structure for table `members`
DROP TABLE IF EXISTS `members`;
CREATE TABLE `members` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `member_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pin_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `birth_date` date NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `province` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `postal_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Canada',
  `canadian_status_proof` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `member_type_id` bigint unsigned NOT NULL,
  `organization_id` bigint unsigned DEFAULT NULL,
  `sponsor_id` bigint unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `phone_verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `members_member_number_unique` (`member_number`),
  UNIQUE KEY `members_email_unique` (`email`),
  KEY `members_member_type_id_foreign` (`member_type_id`),
  KEY `members_member_number_pin_code_index` (`member_number`,`pin_code`),
  KEY `members_organization_id_foreign` (`organization_id`),
  KEY `members_sponsor_id_foreign` (`sponsor_id`),
  CONSTRAINT `members_member_type_id_foreign` FOREIGN KEY (`member_type_id`) REFERENCES `member_types` (`id`) ON DELETE CASCADE,
  CONSTRAINT `members_organization_id_foreign` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL,
  CONSTRAINT `members_sponsor_id_foreign` FOREIGN KEY (`sponsor_id`) REFERENCES `members` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `memberships`
DROP TABLE IF EXISTS `memberships`;
CREATE TABLE `memberships` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `member_id` bigint unsigned NOT NULL,
  `status` enum('active','overdue','lapsed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `adhesion_fee_paid` decimal(8,2) NOT NULL DEFAULT '0.00',
  `total_contributions_paid` decimal(8,2) NOT NULL DEFAULT '0.00',
  `amount_due` decimal(8,2) NOT NULL DEFAULT '0.00',
  `last_payment_date` date DEFAULT NULL,
  `next_payment_due` date DEFAULT NULL,
  `overdue_days` int NOT NULL DEFAULT '0',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `memberships_member_id_status_index` (`member_id`,`status`),
  KEY `memberships_status_next_payment_due_index` (`status`,`next_payment_due`),
  CONSTRAINT `memberships_member_id_foreign` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `payments`
DROP TABLE IF EXISTS `payments`;
CREATE TABLE `payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `membership_id` bigint unsigned NOT NULL,
  `member_id` bigint unsigned NOT NULL,
  `type` enum('adhesion','contribution','penalty','renewal') COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(8,2) NOT NULL,
  `currency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'CAD',
  `status` enum('pending','completed','failed','refunded') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `stripe_payment_intent_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stripe_charge_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'stripe',
  `description` text COLLATE utf8mb4_unicode_ci,
  `metadata` json DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payments_member_id_status_index` (`member_id`,`status`),
  KEY `payments_membership_id_type_index` (`membership_id`,`type`),
  KEY `payments_stripe_payment_intent_id_index` (`stripe_payment_intent_id`),
  CONSTRAINT `payments_member_id_foreign` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payments_membership_id_foreign` FOREIGN KEY (`membership_id`) REFERENCES `memberships` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `contributions`
DROP TABLE IF EXISTS `contributions`;
CREATE TABLE `contributions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `member_id` bigint unsigned NOT NULL,
  `deceased_member_id` bigint unsigned NOT NULL,
  `amount` decimal(8,2) NOT NULL,
  `status` enum('pending','paid','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `due_date` date NOT NULL,
  `paid_date` date DEFAULT NULL,
  `payment_id` bigint unsigned DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `contributions_payment_id_foreign` (`payment_id`),
  KEY `contributions_member_id_status_index` (`member_id`,`status`),
  KEY `contributions_deceased_member_id_index` (`deceased_member_id`),
  CONSTRAINT `contributions_deceased_member_id_foreign` FOREIGN KEY (`deceased_member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE,
  CONSTRAINT `contributions_member_id_foreign` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE,
  CONSTRAINT `contributions_payment_id_foreign` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `sponsorships`
DROP TABLE IF EXISTS `sponsorships`;
CREATE TABLE `sponsorships` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sponsor_id` bigint unsigned NOT NULL,
  `sponsorship_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prospect_first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prospect_last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prospect_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prospect_phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','confirmed','completed','expired') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `confirmed_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sponsorships_sponsorship_code_unique` (`sponsorship_code`),
  KEY `sponsorships_sponsor_id_status_index` (`sponsor_id`,`status`),
  KEY `sponsorships_sponsorship_code_index` (`sponsorship_code`),
  CONSTRAINT `sponsorships_sponsor_id_foreign` FOREIGN KEY (`sponsor_id`) REFERENCES `members` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `notifications`
DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint unsigned NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `sessions`
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `cache`
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `jobs`
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `failed_jobs`
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `password_reset_tokens`
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `cache_locks`
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `job_batches`
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `migrations`
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `migrations`
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
('1','0001_01_01_000000_create_users_table','1'),
('2','0001_01_01_000001_create_cache_table','1'),
('3','0001_01_01_000002_create_jobs_table','1'),
('4','2025_08_26_221848_create_member_types_table','1'),
('5','2025_08_26_221852_create_members_table','1'),
('6','2025_08_26_221855_create_memberships_table','1'),
('7','2025_08_26_221900_create_payments_table','1'),
('8','2025_08_26_221903_create_contributions_table','1'),
('9','2025_08_26_221907_create_sponsorships_table','1'),
('10','2025_08_26_221911_create_organizations_table','1'),
('11','2025_08_26_221914_create_funds_table','1'),
('12','2025_08_26_222621_add_foreign_keys_after_all_tables','1'),
('13','2025_08_27_112816_create_notifications_table','1');

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;

COMMIT;
