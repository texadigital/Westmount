-- Westmount Association Database - UPDATE SCRIPT
-- This script updates existing database with new features
-- Run this if you already have the basic database structure

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Update existing tables and add new features
-- --------------------------------------------------------

-- Update member_types table with correct contribution rates
UPDATE `member_types` SET 
  `death_contribution` = 10.00 
WHERE `name` = 'régulier' AND `death_contribution` != 10.00;

UPDATE `member_types` SET 
  `death_contribution` = 2.00 
WHERE `name` IN ('senior', 'junior') AND `death_contribution` != 2.00;

UPDATE `member_types` SET 
  `death_contribution` = 10.00 
WHERE `name` = 'association' AND `death_contribution` != 10.00;

-- Update payments table to add new payment methods
ALTER TABLE `payments` 
MODIFY COLUMN `payment_method` enum('stripe','paypal','bank_transfer','interac','check') NOT NULL DEFAULT 'bank_transfer';

-- Create settings table if it doesn't exist
CREATE TABLE IF NOT EXISTS `settings` (
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

-- Insert bank settings (only if they don't exist)
INSERT IGNORE INTO `settings` (`key`, `value`, `type`, `description`, `group`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
('bank_name', 'Association Westmount', 'text', 'Nom de la banque', 'bank', 1, 1, NOW(), NOW()),
('bank_account', '1234567890', 'text', 'Numéro de compte bancaire', 'bank', 2, 1, NOW(), NOW()),
('bank_transit', '00123', 'text', 'Numéro de transit bancaire', 'bank', 3, 1, NOW(), NOW()),
('bank_swift', 'WESTCA1M', 'text', 'Code SWIFT de la banque', 'bank', 4, 1, NOW(), NOW()),
('bank_address', '123 Rue Westmount, Montréal, QC H3Z 1A1', 'text', 'Adresse de la banque', 'bank', 5, 1, NOW(), NOW()),
('payment_currency', 'CAD', 'text', 'Devise des paiements', 'bank', 6, 1, NOW(), NOW()),
('bank_instructions', 'Veuillez inclure votre numéro de membre dans la référence du virement.', 'textarea', 'Instructions pour les virements bancaires', 'bank', 7, 1, NOW(), NOW());

-- Update page_contents table with new pages
INSERT IGNORE INTO `page_contents` (`page`, `title`, `content`, `meta_title`, `meta_description`, `is_active`, `created_at`, `updated_at`) VALUES
('services', 'Services', '<h1>Nos Services</h1><p>Découvrez tous les services que nous offrons à nos membres.</p>', 'Services - Association Westmount', 'Découvrez tous les services de l\'Association Westmount.', 1, NOW(), NOW()),
('death_contributions', 'Contributions Décès', '<h1>Contributions Décès</h1><p>Solidarité et soutien en cas de décès d\'un membre.</p>', 'Contributions Décès - Association Westmount', 'Informations sur les contributions de décès et la solidarité.', 1, NOW(), NOW()),
('sponsorship', 'Système de Parrainage', '<h1>Système de Parrainage</h1><p>Rejoignez notre communauté grâce au parrainage.</p>', 'Parrainage - Association Westmount', 'Découvrez notre système de parrainage et ses avantages.', 1, NOW(), NOW()),
('online_management', 'Gestion en Ligne', '<h1>Gestion en Ligne</h1><p>Gérez votre compte membre en toute simplicité.</p>', 'Gestion en Ligne - Association Westmount', 'Plateforme de gestion en ligne pour les membres.', 1, NOW(), NOW()),
('technical_support', 'Support Technique', '<h1>Support Technique</h1><p>Nous sommes là pour vous aider.</p>', 'Support Technique - Association Westmount', 'Obtenez de l\'aide technique pour votre compte membre.', 1, NOW(), NOW()),
('faq', 'FAQ', '<h1>Questions Fréquentes</h1><p>Trouvez les réponses à vos questions.</p>', 'FAQ - Association Westmount', 'Questions fréquemment posées et leurs réponses.', 1, NOW(), NOW());

-- Ensure admin user exists
INSERT IGNORE INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin Westmount', 'admin@westmount.ca', NOW(), '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW());

-- Ensure funds exist
INSERT IGNORE INTO `funds` (`id`, `name`, `description`, `amount`, `currency`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Fonds de Solidarité', 'Fonds principal pour les contributions de décès', 0.00, 'CAD', 1, NOW(), NOW()),
(2, 'Fonds d\'Urgence', 'Fonds pour les situations d\'urgence', 0.00, 'CAD', 1, NOW(), NOW());

-- Update existing page contents if they exist
UPDATE `page_contents` SET 
  `content` = '<h1>Bienvenue à l\'Association Westmount</h1><p>Une communauté d\'entraide et de solidarité qui soutient ses membres dans les moments difficiles.</p><h2>Nos Services</h2><ul><li>Gestion des adhésions</li><li>Contributions de décès</li><li>Système de parrainage</li><li>Gestion en ligne</li><li>Support technique</li></ul>',
  `meta_title` = 'Association Westmount - Accueil',
  `meta_description` = 'Rejoignez l\'Association Westmount, une communauté d\'entraide et de solidarité.',
  `updated_at` = NOW()
WHERE `page` = 'home';

UPDATE `page_contents` SET 
  `content` = '<h1>À propos de nous</h1><p>L\'Association Westmount est une organisation à but non lucratif qui vise à soutenir ses membres dans les moments difficiles. Nous offrons un système de solidarité basé sur les contributions mutuelles.</p><h2>Notre Mission</h2><p>Apporter un soutien financier et moral à nos membres en cas de décès d\'un proche.</p><h2>Notre Vision</h2><p>Créer une communauté solidaire où chaque membre peut compter sur le soutien des autres.</p>',
  `meta_title` = 'À propos - Association Westmount',
  `meta_description` = 'Découvrez l\'histoire et la mission de l\'Association Westmount.',
  `updated_at` = NOW()
WHERE `page` = 'about';

UPDATE `page_contents` SET 
  `content` = '<h1>Contactez-nous</h1><p>Nous sommes là pour vous aider. Contactez-nous pour toute question concernant votre adhésion, les contributions, ou tout autre service.</p><h2>Informations de Contact</h2><p><strong>Email:</strong> info@westmount.ca</p><p><strong>Téléphone:</strong> +1 (514) 555-0123</p><p><strong>Adresse:</strong> 123 Rue Westmount, Montréal, QC H3Z 1A1</p><h2>Heures d\'Ouverture</h2><p>Lundi - Vendredi: 9h00 - 17h00</p>',
  `meta_title` = 'Contact - Association Westmount',
  `meta_description` = 'Contactez l\'Association Westmount pour toute question ou information.',
  `updated_at` = NOW()
WHERE `page` = 'contact';

COMMIT;

-- Update completed successfully!
-- New features added:
-- ✅ Updated payment methods (added Interac, removed cash)
-- ✅ Corrected member type contribution rates
-- ✅ Added bank settings management
-- ✅ Added new dynamic pages content
-- ✅ Enhanced existing page content
-- ✅ Ensured admin user and funds exist
