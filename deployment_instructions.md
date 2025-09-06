# Westmount Association - Deployment Instructions

## 🚀 Manual Upload Method - Step by Step

### **Step 1: Download the Code**
1. Go to: https://github.com/texadigital/Westmount
2. Click the green "Code" button
3. Select "Download ZIP"
4. Extract the ZIP file to your computer

### **Step 2: Prepare Files for Upload**

#### **Files to Upload (Select these from the extracted folder):**
- `app/` (entire folder)
- `bootstrap/` (entire folder)
- `config/` (entire folder)
- `database/` (entire folder)
- `public/` (entire folder)
- `resources/` (entire folder)
- `routes/` (entire folder)
- `storage/` (entire folder)
- `vendor/` (entire folder)
- `artisan`
- `composer.json`
- `composer.lock`
- `package.json`
- `package-lock.json`
- `tailwind.config.js`
- `vite.config.js`
- `webpack.mix.js` (if exists)

#### **Files to EXCLUDE (Do NOT upload these):**
- `.env` (keep your existing one)
- `.env.example`
- `.git/`
- `node_modules/`
- `storage/logs/` (keep existing logs)
- `storage/framework/cache/`
- `storage/framework/sessions/`
- `storage/framework/views/`

### **Step 3: Upload to Hostinger**

1. **Login to Hostinger Control Panel**
   - Go to https://hpanel.hostinger.com
   - Login with your credentials

2. **Open File Manager**
   - Click on "File Manager" in the main dashboard
   - Navigate to your domain's folder (usually `public_html`)

3. **Backup Current Files (IMPORTANT!)**
   - Select all current files
   - Right-click → "Compress" → Create a backup ZIP
   - Name it: `backup_before_update_$(date).zip`

4. **Upload New Files**
   - Select the files/folders from Step 2
   - Upload them to your domain folder
   - **Overwrite existing files when prompted**

### **Step 4: Set File Permissions**

1. **In File Manager, set these permissions:**
   - `storage/` folder: 755
   - `bootstrap/cache/` folder: 755
   - `public/` folder: 755
   - All files in `storage/` and `bootstrap/cache/`: 644

2. **To set permissions:**
   - Right-click on folder → "Permissions"
   - Set to 755 for folders, 644 for files

### **Step 5: Update Environment File**

1. **Edit your `.env` file:**
   - Open `.env` in File Manager
   - Make sure these settings are correct:
   ```
   APP_NAME="Association Westmount"
   APP_ENV=production
   APP_KEY=base64:your-existing-key
   APP_DEBUG=false
   APP_URL=https://your-domain.com
   
   DB_CONNECTION=mysql
   DB_HOST=localhost
   DB_PORT=3306
   DB_DATABASE=your-database-name
   DB_USERNAME=your-database-username
   DB_PASSWORD=your-database-password
   ```

### **Step 6: Run Database Migrations**

1. **Open Terminal/SSH in Hostinger:**
   - Go to "Advanced" → "Terminal"
   - Or use SSH if available

2. **Navigate to your project:**
   ```bash
   cd public_html
   ```

3. **Run these commands:**
   ```bash
   php artisan migrate --force
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

### **Step 7: Test Your Website**

1. **Visit your website:**
   - Go to https://your-domain.com
   - Check if the homepage loads

2. **Test new pages:**
   - https://your-domain.com/services
   - https://your-domain.com/contributions-deces
   - https://your-domain.com/parrainage
   - https://your-domain.com/gestion-en-ligne
   - https://your-domain.com/support-technique
   - https://your-domain.com/faq

3. **Test admin dashboard:**
   - https://your-domain.com/admin
   - Login with your admin credentials
   - Check if Page Contents section works

### **Step 8: Troubleshooting**

#### **If you get 500 errors:**
1. Check file permissions (Step 4)
2. Check `.env` file settings (Step 5)
3. Clear cache: `php artisan cache:clear`

#### **If pages don't load:**
1. Check if all files were uploaded correctly
2. Verify database connection in `.env`
3. Run migrations: `php artisan migrate --force`

#### **If admin doesn't work:**
1. Check if `storage/` folder is writable
2. Clear all caches: `php artisan optimize:clear`

### **Step 9: Final Verification**

✅ **Checklist:**
- [ ] Homepage loads correctly
- [ ] All 6 new pages are accessible
- [ ] Navigation menu works (desktop & mobile)
- [ ] Admin dashboard loads
- [ ] Can login to admin
- [ ] Page Contents section works
- [ ] Member registration works
- [ ] Member login works

### **Support:**
If you encounter any issues, check the error logs in:
- `storage/logs/laravel.log`
- Hostinger error logs in control panel

---

**Note:** This deployment includes all the new dynamic pages and the association fee calculation system we built today.
