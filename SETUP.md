# 🚀 Setup Guide for SmartBudget

This guide will help you set up SmartBudget on your local machine after cloning from GitHub.

## 📋 Prerequisites

- **PHP 7.4+** installed
- **MySQL/MariaDB** installed
- **Apache** web server (XAMPP recommended)
- **Git** for cloning the repository

## 🔧 Step-by-Step Setup

### 1️⃣ Clone the Repository

```bash
cd c:\xampp\htdocs\
git clone <your-github-repo-url> Relationnel
cd Relationnel
```

### 2️⃣ Configure Database Connection

1. **Copy the example database config:**
   ```bash
   copy db.example.php db.php
   ```

2. **Edit `db.php`** and update with your database credentials:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'smartbudget');
   define('DB_USER', 'root');          // Your MySQL username
   define('DB_PASS', '');              // Your MySQL password
   ```

### 3️⃣ Create the Database

**Option A: Using phpMyAdmin**
1. Open http://localhost/phpmyadmin
2. Click "New" to create a database
3. Name it `smartbudget`
4. Set collation to `utf8mb4_unicode_ci`
5. Click on the database, then "Import"
6. Select `database_schema.sql` from the project folder
7. Click "Go"

**Option B: Using Command Line**
```bash
# Login to MySQL
mysql -u root -p

# Create database
CREATE DATABASE smartbudget CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Exit MySQL
exit

# Import schema
mysql -u root -p smartbudget < database_schema.sql
```

### 4️⃣ Configure Application Paths (Optional)

The application should auto-detect paths. If you have issues:

1. **Copy the example config:**
   ```bash
   copy config.example.php config.local.php
   ```

2. **Edit `config.local.php`** and set your base path:
   ```php
   define('BASE_PATH', '/Relationnel/public/');
   ```

### 5️⃣ Configure Apache .htaccess (If Needed)

If your project is in a different location, edit `public/.htaccess`:

```apache
RewriteBase /Relationnel/public/
```

Change `/Relationnel/public/` to match your actual path.

### 6️⃣ Start XAMPP

1. Open XAMPP Control Panel
2. Start **Apache**
3. Start **MySQL**

### 7️⃣ Access the Application

Open your browser and navigate to:
```
http://localhost/Relationnel/public/
```

## ✅ Verify Installation

1. You should see the SmartBudget homepage
2. Click "Créer un compte" to register
3. Fill in the form and create an account
4. Login with your credentials
5. You should see the dashboard

## 🐛 Troubleshooting

### CSS Not Loading

**Problem:** Styles are not applied, page looks plain.

**Solutions:**
1. Check that `public/css/style.css` exists
2. Clear browser cache (Ctrl+F5)
3. Check browser console for 404 errors
4. Verify `BASE_PATH` in config.php matches your setup

### Login/Register Buttons Don't Work

**Problem:** Clicking buttons does nothing or shows errors.

**Solutions:**

1. **Check Database Connection:**
   - Open `db.php` and verify credentials
   - Test connection by accessing http://localhost/phpmyadmin

2. **Check Apache mod_rewrite:**
   - Open `c:\xampp\apache\conf\httpd.conf`
   - Find line: `#LoadModule rewrite_module modules/mod_rewrite.so`
   - Remove the `#` to uncomment it
   - Restart Apache

3. **Check PHP Errors:**
   - Add to top of `public/login.php`:
     ```php
     ini_set('display_errors', 1);
     error_reporting(E_ALL);
     ```
   - Reload page and check for error messages

4. **Verify Database Schema:**
   - Make sure `database_schema.sql` was imported correctly
   - Check that `users` table exists in phpMyAdmin

### Page Not Found (404)

**Problem:** All pages show 404 errors.

**Solutions:**
1. Verify `.htaccess` file exists in `public/` directory
2. Check `RewriteBase` in `.htaccess` matches your path
3. Ensure Apache `mod_rewrite` is enabled (see above)
4. Check `AllowOverride All` in Apache config

### Database Connection Error

**Problem:** "Database connection error" message.

**Solutions:**
1. Verify MySQL is running in XAMPP
2. Check database name is exactly `smartbudget`
3. Verify username/password in `db.php`
4. Try connecting via phpMyAdmin with same credentials

## 📁 Important Files

- `db.php` - **Your database credentials** (not in Git)
- `config.local.php` - **Your local config** (optional, not in Git)
- `public/.htaccess` - Apache rewrite rules
- `database_schema.sql` - Database structure

## 🔒 Security Notes

- **Never commit `db.php`** to Git (it's in `.gitignore`)
- **Never commit `config.local.php`** to Git
- Keep your database password secure
- Use strong passwords for user accounts

## 💡 Tips

- Use **Chrome DevTools** (F12) to debug CSS and JavaScript issues
- Check **Apache error logs** at `c:\xampp\apache\logs\error.log`
- Check **PHP error logs** at `c:\xampp\php\logs\php_error_log`
- Clear browser cache when CSS doesn't update

## 🆘 Still Having Issues?

1. Check all paths are correct
2. Verify XAMPP services are running
3. Check file permissions
4. Review Apache/PHP error logs
5. Make sure you imported the database schema

## 📞 Getting Help

If you're still stuck:
1. Check the error logs
2. Note the exact error message
3. Check which step failed
4. Review the troubleshooting section above

---

**Happy budgeting! 💰**
