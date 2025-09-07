# Guide de Déploiement - Association Westmount

## Déploiement sur Hostinger

### Prérequis
- Compte Hostinger avec accès cPanel
- Base de données MySQL créée
- Domaine configuré

### Étapes de Déploiement

1. **Préparer les fichiers**
   ```bash
   # Nettoyer le codebase
   php cleanup_for_github.php
   
   # Compiler les assets
   npm run build
   ```

2. **Uploader les fichiers**
   - Uploader tous les fichiers dans le dossier `public_html`
   - S'assurer que les permissions sont correctes :
     - Dossiers : 755
     - Fichiers : 644
     - `storage/` et `bootstrap/cache/` : 755

3. **Configurer l'environnement**
   - Créer le fichier `.env` avec les bonnes configurations
   - Configurer la base de données
   - Générer la clé d'application

4. **Configurer la base de données**
   ```bash
   php artisan migrate --force
   php artisan db:seed --force
   ```

5. **Optimiser pour la production**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

### Configuration .env pour Production

```env
APP_NAME="Association Westmount"
APP_ENV=production
APP_KEY=base64:your-generated-key
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password

# Stripe Configuration
STRIPE_KEY=your_stripe_public_key
STRIPE_SECRET=your_stripe_secret_key
STRIPE_WEBHOOK_SECRET=your_webhook_secret

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@your-domain.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### Vérifications Post-Déploiement

1. **Tester l'accès admin**
   - URL : `https://your-domain.com/admin`
   - Email : `admin@westmount.ca`
   - Mot de passe : `password123`

2. **Tester l'inscription membre**
   - URL : `https://your-domain.com/register`
   - Utiliser un code de parrainage

3. **Vérifier les fonctionnalités**
   - Gestion des membres
   - Système de paiements
   - Notifications

### Maintenance

1. **Sauvegardes régulières**
   - Base de données quotidienne
   - Fichiers hebdomadaire

2. **Monitoring**
   - Vérifier les logs d'erreur
   - Surveiller les performances
   - Tester les fonctionnalités critiques

3. **Mises à jour**
   - Laravel et packages
   - Dépendances Node.js
   - Certificats SSL

### Dépannage

1. **Erreurs 500**
   - Vérifier les permissions
   - Vérifier le fichier .env
   - Vider le cache

2. **Problèmes de base de données**
   - Vérifier les connexions
   - Exécuter les migrations
   - Vérifier les seeders

3. **Problèmes de paiements**
   - Vérifier la configuration Stripe
   - Tester les webhooks
   - Vérifier les logs

### Support

Pour toute question ou problème :
- Consulter les logs : `storage/logs/laravel.log`
- Vérifier la configuration
- Contacter le support technique
