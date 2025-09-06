-- Westmount Association Database - Updated Version
-- Generated: 2025-09-06
-- Includes: All new pages, association fee calculations, payment methods

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Database: westmount_association
-- --------------------------------------------------------

-- Table structure for table `cache`
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `cache_locks`
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `contributions`
CREATE TABLE `contributions` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `member_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(3) NOT NULL DEFAULT 'CAD',
  `status` enum('pending','paid','overdue','cancelled') NOT NULL DEFAULT 'pending',
  `due_date` date NOT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `contributions_member_id_foreign` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `failed_jobs`
CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `funds`
CREATE TABLE `funds` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `currency` varchar(3) NOT NULL DEFAULT 'CAD',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `jobs`
CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `jobs_batches`
CREATE TABLE `jobs_batches` (
  `id` varchar(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `member_types`
CREATE TABLE `member_types` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `adhesion_fee` decimal(10,2) NOT NULL DEFAULT 50.00,
  `death_contribution` decimal(10,2) NOT NULL DEFAULT 10.00,
  `min_age` int(11) DEFAULT NULL,
  `max_age` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `members`
CREATE TABLE `members` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `member_number` varchar(20) NOT NULL,
  `pin_code` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `birth_date` date NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `province` varchar(50) NOT NULL,
  `postal_code` varchar(10) NOT NULL,
  `country` varchar(100) NOT NULL,
  `canadian_status_proof` varchar(50) NOT NULL,
  `member_type_id` bigint(20) UNSIGNED NOT NULL,
  `organization_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sponsor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `members_member_number_unique` (`member_number`),
  UNIQUE KEY `members_email_unique` (`email`),
  KEY `members_member_type_id_foreign` (`member_type_id`),
  KEY `members_organization_id_foreign` (`organization_id`),
  KEY `members_sponsor_id_foreign` (`sponsor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `memberships`
CREATE TABLE `memberships` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `member_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('active','inactive','suspended','expired') NOT NULL DEFAULT 'active',
  `start_date` timestamp NOT NULL,
  `end_date` timestamp NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `amount_due` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `memberships_member_id_foreign` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `notifications`
CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `organizations`
CREATE TABLE `organizations` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `business_number` varchar(50) NOT NULL,
  `contact_person` varchar(255) NOT NULL,
  `contact_email` varchar(255) NOT NULL,
  `contact_phone` varchar(20) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `province` varchar(50) NOT NULL,
  `postal_code` varchar(10) NOT NULL,
  `country` varchar(100) NOT NULL,
  `member_count` int(11) NOT NULL DEFAULT 0,
  `total_fees` decimal(10,2) NOT NULL DEFAULT 0.00,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `organizations_business_number_unique` (`business_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `page_contents`
CREATE TABLE `page_contents` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `page` varchar(100) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` longtext,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `page_contents_page_unique` (`page`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `password_reset_tokens`
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `payments`
CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `member_id` bigint(20) UNSIGNED NOT NULL,
  `membership_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('adhesion','contribution','donation') NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(3) NOT NULL DEFAULT 'CAD',
  `status` enum('pending','completed','failed','refunded') NOT NULL DEFAULT 'pending',
  `payment_method` enum('stripe','paypal','bank_transfer','interac','check') NOT NULL DEFAULT 'bank_transfer',
  `stripe_payment_intent_id` varchar(255) DEFAULT NULL,
  `description` text,
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payments_member_id_foreign` (`member_id`),
  KEY `payments_membership_id_foreign` (`membership_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `sessions`
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `settings`
CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` text,
  `type` enum('text','number','boolean','json','textarea') NOT NULL DEFAULT 'text',
  `description` text,
  `group` varchar(100) DEFAULT 'general',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `sponsorships`
CREATE TABLE `sponsorships` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `sponsor_id` bigint(20) UNSIGNED NOT NULL,
  `sponsored_id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(20) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sponsorships_code_unique` (`code`),
  KEY `sponsorships_sponsor_id_foreign` (`sponsor_id`),
  KEY `sponsorships_sponsored_id_foreign` (`sponsored_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `users`
CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `member_types`
INSERT INTO `member_types` (`id`, `name`, `description`, `adhesion_fee`, `death_contribution`, `min_age`, `max_age`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'régulier', 'Membre régulier adulte (moins de 68 ans)', 50.00, 10.00, 18, 67, 1, '2025-09-06 07:00:00', '2025-09-06 07:00:00'),
(2, 'senior', 'Membre senior (68 ans et plus à l\'adhésion)', 50.00, 2.00, 68, NULL, 1, '2025-09-06 07:00:00', '2025-09-06 07:00:00'),
(3, 'junior', 'Membre junior (mineurs)', 50.00, 2.00, NULL, 17, 1, '2025-09-06 07:00:00', '2025-09-06 07:00:00'),
(4, 'association', 'Association ou organisme partenaire', 50.00, 10.00, NULL, NULL, 1, '2025-09-06 07:00:00', '2025-09-06 07:00:00');

-- Dumping data for table `funds`
INSERT INTO `funds` (`id`, `name`, `description`, `amount`, `currency`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Fonds de Solidarité', 'Fonds principal pour les contributions de décès', 0.00, 'CAD', 1, '2025-09-06 07:00:00', '2025-09-06 07:00:00'),
(2, 'Fonds d\'Urgence', 'Fonds pour les situations d\'urgence', 0.00, 'CAD', 1, '2025-09-06 07:00:00', '2025-09-06 07:00:00');

-- Dumping data for table `page_contents`
INSERT INTO `page_contents` (`id`, `page`, `title`, `content`, `meta_title`, `meta_description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'home', 'Accueil', '<h1>Bienvenue à l\'Association Westmount</h1><p>Une communauté d\'entraide et de solidarité qui soutient ses membres dans les moments difficiles.</p>', 'Association Westmount - Accueil', 'Rejoignez l\'Association Westmount, une communauté d\'entraide et de solidarité.', 1, '2025-09-06 07:00:00', '2025-09-06 07:00:00'),
(2, 'about', 'À propos', '<h1>À propos de nous</h1><p>L\'Association Westmount est une organisation à but non lucratif qui vise à soutenir ses membres.</p>', 'À propos - Association Westmount', 'Découvrez l\'histoire et la mission de l\'Association Westmount.', 1, '2025-09-06 07:00:00', '2025-09-06 07:00:00'),
(3, 'contact', 'Contact', '<h1>Contactez-nous</h1><p>Nous sommes là pour vous aider. Contactez-nous pour toute question.</p>', 'Contact - Association Westmount', 'Contactez l\'Association Westmount pour toute question ou information.', 1, '2025-09-06 07:00:00', '2025-09-06 07:00:00');

-- Dumping data for table `settings`
INSERT INTO `settings` (`id`, `key`, `value`, `type`, `description`, `group`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'bank_name', 'Association Westmount', 'text', 'Nom de la banque', 'bank', 1, 1, '2025-09-06 07:00:00', '2025-09-06 07:00:00'),
(2, 'bank_account', '1234567890', 'text', 'Numéro de compte bancaire', 'bank', 2, 1, '2025-09-06 07:00:00', '2025-09-06 07:00:00'),
(3, 'bank_transit', '00123', 'text', 'Numéro de transit bancaire', 'bank', 3, 1, '2025-09-06 07:00:00', '2025-09-06 07:00:00'),
(4, 'bank_swift', 'WESTCA1M', 'text', 'Code SWIFT de la banque', 'bank', 4, 1, '2025-09-06 07:00:00', '2025-09-06 07:00:00'),
(5, 'bank_address', '123 Rue Westmount, Montréal, QC H3Z 1A1', 'text', 'Adresse de la banque', 'bank', 5, 1, '2025-09-06 07:00:00', '2025-09-06 07:00:00'),
(6, 'payment_currency', 'CAD', 'text', 'Devise des paiements', 'bank', 6, 1, '2025-09-06 07:00:00', '2025-09-06 07:00:00'),
(7, 'bank_instructions', 'Veuillez inclure votre numéro de membre dans la référence du virement.', 'textarea', 'Instructions pour les virements bancaires', 'bank', 7, 1, '2025-09-06 07:00:00', '2025-09-06 07:00:00');

-- Dumping data for table `users`
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin Westmount', 'admin@westmount.ca', '2025-09-06 07:00:00', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-06 07:00:00', '2025-09-06 07:00:00');

-- Adding foreign key constraints
ALTER TABLE `contributions`
  ADD CONSTRAINT `contributions_member_id_foreign` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE;

ALTER TABLE `members`
  ADD CONSTRAINT `members_member_type_id_foreign` FOREIGN KEY (`member_type_id`) REFERENCES `member_types` (`id`),
  ADD CONSTRAINT `members_organization_id_foreign` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `members_sponsor_id_foreign` FOREIGN KEY (`sponsor_id`) REFERENCES `members` (`id`) ON DELETE SET NULL;

ALTER TABLE `memberships`
  ADD CONSTRAINT `memberships_member_id_foreign` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE;

ALTER TABLE `payments`
  ADD CONSTRAINT `payments_member_id_foreign` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_membership_id_foreign` FOREIGN KEY (`membership_id`) REFERENCES `memberships` (`id`) ON DELETE CASCADE;

ALTER TABLE `sponsorships`
  ADD CONSTRAINT `sponsorships_sponsor_id_foreign` FOREIGN KEY (`sponsor_id`) REFERENCES `members` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sponsorships_sponsored_id_foreign` FOREIGN KEY (`sponsored_id`) REFERENCES `members` (`id`) ON DELETE CASCADE;

COMMIT;

-- Database updated with:
-- ✅ New payment methods (Interac, removed cash)
-- ✅ Association fee calculation system
-- ✅ Dynamic page content management
-- ✅ Bank settings management
-- ✅ All new page content structure
-- ✅ Updated member types with correct contribution rates
-- ✅ Complete foreign key relationships
