# Association Westmount - Application de Gestion

## Description

Application web de gestion pour l'Association d'entraide et de solidarité Westmount, développée avec Laravel 11 et Filament 3. Cette application permet la gestion complète des membres, des adhésions, des paiements et des fonds de l'association.

## Fonctionnalités Principales

### 🏢 Gestion des Membres
- **Types de membres** : Régulier, Senior, Junior, Association
- **Profils complets** : Informations personnelles, adresse, statut canadien
- **Système de parrainage** : Gestion des parrainages et filleuls
- **Numéros uniques** : Attribution automatique de numéros de membre
- **Codes PIN sécurisés** : Authentification par numéro + PIN

### 💰 Gestion Financière
- **Paiements Stripe** : Intégration sécurisée pour les cotisations
- **Contributions décès** : Gestion automatique des contributions
- **Fonds multiples** : Général, prestation de décès, urgence
- **Suivi des retards** : Calcul automatique des montants dus
- **Rapports financiers** : Statistiques en temps réel

### 📊 Tableau de Bord Administrateur
- **Statistiques en temps réel** : Membres, paiements, fonds
- **Alertes automatiques** : Membres en retard, paiements manquants
- **Interface intuitive** : Panel d'administration Filament
- **Gestion complète** : CRUD pour tous les modules

### 🔐 Sécurité
- **Authentification sécurisée** : Laravel Breeze + Sanctum
- **Chiffrement des données** : PIN et informations sensibles
- **Permissions granulaires** : Système de rôles et permissions
- **Audit trail** : Logs des actions importantes

## Technologies Utilisées

- **Backend** : Laravel 11 (PHP 8.2+)
- **Frontend Admin** : Filament 3.x
- **Base de données** : MySQL 8.0
- **Paiements** : Stripe
- **Authentification** : Laravel Breeze + Sanctum
- **Permissions** : Spatie Laravel Permission

## Installation

### Prérequis
- PHP 8.2 ou supérieur
- Composer
- MySQL 8.0
- Node.js (pour les assets)

### Étapes d'Installation

1. **Cloner le projet**
   ```bash
   git clone [URL_DU_REPO]
   cd westmount-association
   ```

2. **Installer les dépendances PHP**
   ```bash
   composer install
   ```

3. **Installer les dépendances Node.js**
   ```bash
   npm install
   npm run build
   ```

4. **Configurer l'environnement**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configurer la base de données**
   - Créer une base de données MySQL
   - Modifier le fichier `.env` avec les informations de connexion
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=westmount_association
   DB_USERNAME=root
   DB_PASSWORD=votre_mot_de_passe
   ```

6. **Exécuter les migrations et seeders**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

7. **Configurer Stripe (optionnel)**
   ```env
   STRIPE_KEY=votre_cle_publique_stripe
   STRIPE_SECRET=votre_cle_secrete_stripe
   STRIPE_WEBHOOK_SECRET=votre_webhook_secret_stripe
   ```

8. **Démarrer le serveur**
   ```bash
   php artisan serve
   ```

## Accès à l'Application

### Panel d'Administration
- **URL** : `http://localhost:8000/admin`
- **Email** : `admin@westmount.ca`
- **Mot de passe** : `password123`

### Interface Membre
- **URL** : `http://localhost:8000`
- **Inscription** : Utiliser un code de parrainage
- **Connexion** : Numéro de membre + Code PIN

## Structure de la Base de Données

### Tables Principales
- `member_types` - Types de membres (régulier, senior, junior, association)
- `members` - Informations des membres
- `memberships` - Adhésions et statuts
- `payments` - Historique des paiements
- `contributions` - Contributions décès
- `sponsorships` - Système de parrainage
- `organizations` - Associations partenaires
- `funds` - Gestion des fonds

## Fonctionnalités par Module

### 📋 Types de Membres
- **Frais d'adhésion** : 50$ pour tous les types
- **Contributions décès** :
  - Régulier : 10$
  - Senior : 2$
  - Junior : 2$
  - Association : 10$

### 👥 Gestion des Membres
- **Numéro unique** : Génération automatique (format WM + 6 chiffres)
- **Code PIN** : 4-6 chiffres pour la connexion
- **Validation d'âge** : Contribution selon le type de membre décédé.
- **Parrainage** : Système de codes uniques

### 💳 Système de Paiements
- **Intégration Stripe** : Paiements sécurisés
- **Types de paiements** : Adhésion, contribution, pénalité, renouvellement
- **Suivi des retards** : Calcul automatique des jours de retard
- **Notifications** : Alertes pour les paiements manquants

### 🏦 Gestion des Fonds
- **Fonds général** : Opérations courantes
- **Fonds de décès** : Prestations aux familles
- **Fonds d'urgence** : Situations critiques
- **Rapports** : Bilans en temps réel

## Développement

### Commandes Utiles
```bash
# Créer une migration
php artisan make:migration nom_de_la_migration

# Créer un modèle
php artisan make:model NomDuModele

# Créer une ressource Filament
php artisan make:filament-resource NomDuModele

# Créer un seeder
php artisan make:seeder NomDuSeeder

# Vider le cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## Sécurité

### Bonnes Pratiques Implémentées
- **Chiffrement des PIN** : Hachage bcrypt
- **Validation stricte** : Toutes les entrées utilisateur
- **Protection CSRF** : Tokens automatiques
- **Rate limiting** : Protection contre les attaques
- **Logs d'audit** : Traçabilité des actions

### Recommandations de Déploiement
- **HTTPS obligatoire** : Certificat SSL
- **Variables d'environnement** : Pas de données sensibles en dur
- **Sauvegardes régulières** : Base de données et fichiers
- **Monitoring** : Surveillance des performances et erreurs

## Support et Maintenance

### Contact
- **Développeur** : [Votre nom]
- **Email** : [votre.email@example.com]
- **Association** : Association Westmount

### Maintenance
- **Mises à jour** : Laravel et packages
- **Sauvegardes** : Quotidiennes
- **Monitoring** : Surveillance continue
- **Support** : Documentation et formation

## Licence

Ce projet est développé pour l'Association Westmount. Tous droits réservés.

---

**Version** : 1.0.0  
**Dernière mise à jour** : Septembre 2025  
**Statut** : Production Ready