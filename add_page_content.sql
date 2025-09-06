-- Add page content and settings for Westmount Association
-- Run this after creating the missing tables

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Insert page contents
INSERT INTO `page_contents` (`page`, `title`, `content`, `meta_title`, `meta_description`, `is_active`, `created_at`, `updated_at`) VALUES
('home', 'Accueil', '<h1>Bienvenue à l\'Association Westmount</h1><p>Une communauté d\'entraide et de solidarité qui soutient ses membres dans les moments difficiles.</p><h2>Nos Services</h2><ul><li>Gestion des adhésions</li><li>Contributions de décès</li><li>Système de parrainage</li><li>Gestion en ligne</li><li>Support technique</li></ul>', 'Association Westmount - Accueil', 'Rejoignez l\'Association Westmount, une communauté d\'entraide et de solidarité.', 1, NOW(), NOW()),
('about', 'À propos', '<h1>À propos de nous</h1><p>L\'Association Westmount est une organisation à but non lucratif qui vise à soutenir ses membres dans les moments difficiles. Nous offrons un système de solidarité basé sur les contributions mutuelles.</p><h2>Notre Mission</h2><p>Apporter un soutien financier et moral à nos membres en cas de décès d\'un proche.</p><h2>Notre Vision</h2><p>Créer une communauté solidaire où chaque membre peut compter sur le soutien des autres.</p>', 'À propos - Association Westmount', 'Découvrez l\'histoire et la mission de l\'Association Westmount.', 1, NOW(), NOW()),
('contact', 'Contact', '<h1>Contactez-nous</h1><p>Nous sommes là pour vous aider. Contactez-nous pour toute question concernant votre adhésion, les contributions, ou tout autre service.</p><h2>Informations de Contact</h2><p><strong>Email:</strong> info@westmount.ca</p><p><strong>Téléphone:</strong> +1 (514) 555-0123</p><p><strong>Adresse:</strong> 123 Rue Westmount, Montréal, QC H3Z 1A1</p><h2>Heures d\'Ouverture</h2><p>Lundi - Vendredi: 9h00 - 17h00</p>', 'Contact - Association Westmount', 'Contactez l\'Association Westmount pour toute question ou information.', 1, NOW(), NOW()),
('services', 'Services', '<h1>Nos Services</h1><p>Découvrez tous les services que nous offrons à nos membres.</p><h2>Gestion des Adhésions</h2><p>Inscription et renouvellement des adhésions avec un système en ligne sécurisé.</p><h2>Contributions et Paiements</h2><p>Système de gestion des contributions avec paiements sécurisés par virement bancaire et Interac.</p><h2>Support et Assistance</h2><p>Support technique complet et assistance pour tous nos membres.</p><h2>Gestion en Ligne</h2><p>Plateforme en ligne complète pour la gestion de votre compte membre.</p>', 'Services - Association Westmount', 'Découvrez tous les services de l\'Association Westmount.', 1, NOW(), NOW()),
('death_contributions', 'Contributions Décès', '<h1>Contributions Décès</h1><p>Solidarité et soutien en cas de décès d\'un membre.</p><h2>Comment ça fonctionne</h2><p>Chaque membre contribue mensuellement selon sa catégorie. Les contributions sont versées dans un fonds de solidarité. En cas de décès, la famille reçoit une aide financière.</p><h2>Tarifs des Contributions</h2><ul><li>Membres réguliers: 10$ CAD</li><li>Membres seniors: 2$ CAD</li><li>Membres juniors: 2$ CAD</li><li>Associations: 10$ CAD</li></ul>', 'Contributions Décès - Association Westmount', 'Informations sur les contributions de décès et la solidarité.', 1, NOW(), NOW()),
('sponsorship', 'Système de Parrainage', '<h1>Système de Parrainage</h1><p>Rejoignez notre communauté grâce au parrainage.</p><h2>Comment ça fonctionne</h2><p>Un membre existant peut demander un code de parrainage. Ce code est partagé avec la personne à parrainer. La nouvelle personne utilise le code lors de l\'inscription. Le parrain et le filleul bénéficient d\'avantages.</p><h2>Avantages du Parrainage</h2><ul><li>Réduction sur les frais d\'adhésion</li><li>Accès prioritaire aux événements</li><li>Support personnalisé</li><li>Réseau de contacts étendu</li><li>Programme de fidélité</li></ul>', 'Parrainage - Association Westmount', 'Découvrez notre système de parrainage et ses avantages.', 1, NOW(), NOW()),
('online_management', 'Gestion en Ligne', '<h1>Gestion en Ligne</h1><p>Gérez votre compte membre en toute simplicité.</p><h2>Fonctionnalités</h2><ul><li>Gestion du profil</li><li>Gestion des paiements</li><li>Documents</li><li>Tableau de bord personnel</li><li>Notifications intelligentes</li></ul><h2>Sécurité et Confidentialité</h2><p>Toutes les données sont protégées par un chiffrement SSL 256-bit avec un système d\'authentification robuste.</p>', 'Gestion en Ligne - Association Westmount', 'Plateforme de gestion en ligne pour les membres.', 1, NOW(), NOW()),
('technical_support', 'Support Technique', '<h1>Support Technique</h1><p>Nous sommes là pour vous aider.</p><h2>Nous Contacter</h2><p><strong>Email:</strong> support@westmount.ca</p><p><strong>Téléphone:</strong> +1 (514) 555-0123</p><p><strong>Heures d\'ouverture:</strong> Lundi - Vendredi: 9h00 - 17h00</p><h2>Problèmes Courants</h2><ul><li>Problème de connexion: Vérifiez votre email et mot de passe</li><li>Paiement en attente: Les virements peuvent prendre 1-3 jours</li><li>Documents manquants: Assurez-vous d\'avoir téléchargé tous les documents</li></ul>', 'Support Technique - Association Westmount', 'Obtenez de l\'aide technique pour votre compte membre.', 1, NOW(), NOW()),
('faq', 'FAQ', '<h1>Questions Fréquentes</h1><p>Trouvez les réponses à vos questions.</p><h2>Comment m\'inscrire à l\'association?</h2><p>Rendez-vous sur notre page d\'inscription, remplissez le formulaire avec vos informations personnelles, choisissez votre type de membre, et effectuez le paiement des frais d\'adhésion.</p><h2>Quels sont les frais d\'adhésion?</h2><p>Les frais d\'adhésion sont de 50$ CAD pour tous les types de membres. Les contributions mensuelles varient selon le type de membre.</p><h2>Comment effectuer les paiements?</h2><p>Nous acceptons les paiements par virement bancaire et Interac. Les informations bancaires sont disponibles dans votre espace membre.</p>', 'FAQ - Association Westmount', 'Questions fréquemment posées et leurs réponses.', 1, NOW(), NOW());

-- Insert bank settings
INSERT INTO `settings` (`key`, `value`, `type`, `description`, `group`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
('bank_name', 'Association Westmount', 'text', 'Nom de la banque', 'bank', 1, 1, NOW(), NOW()),
('bank_account', '1234567890', 'text', 'Numéro de compte bancaire', 'bank', 2, 1, NOW(), NOW()),
('bank_transit', '00123', 'text', 'Numéro de transit bancaire', 'bank', 3, 1, NOW(), NOW()),
('bank_swift', 'WESTCA1M', 'text', 'Code SWIFT de la banque', 'bank', 4, 1, NOW(), NOW()),
('bank_address', '123 Rue Westmount, Montréal, QC H3Z 1A1', 'text', 'Adresse de la banque', 'bank', 5, 1, NOW(), NOW()),
('payment_currency', 'CAD', 'text', 'Devise des paiements', 'bank', 6, 1, NOW(), NOW()),
('bank_instructions', 'Veuillez inclure votre numéro de membre dans la référence du virement.', 'textarea', 'Instructions pour les virements bancaires', 'bank', 7, 1, NOW(), NOW());

-- Insert funds
INSERT INTO `funds` (`name`, `description`, `amount`, `currency`, `is_active`, `created_at`, `updated_at`) VALUES
('Fonds de Solidarité', 'Fonds principal pour les contributions de décès', 0.00, 'CAD', 1, NOW(), NOW()),
('Fonds d\'Urgence', 'Fonds pour les situations d\'urgence', 0.00, 'CAD', 1, NOW(), NOW());

-- Ensure admin user exists
INSERT IGNORE INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin Westmount', 'admin@westmount.ca', NOW(), '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW());

COMMIT;
