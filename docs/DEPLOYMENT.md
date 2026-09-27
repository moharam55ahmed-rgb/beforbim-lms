# Beforbim Production & Shared Hosting Deployment Guide

This guide details best practices for deploying the Beforbim LMS to production environments, including standard Cloud VPS (Ubuntu/Nginx) and cPanel/DirectAdmin Shared Hosting environments.

---

## 1. Server Environment Requirements

- **PHP:** 8.3 or higher
- **Extensions:** `BCMath`, `Ctype`, `Fileinfo`, `JSON`, `Mbstring`, `OpenSSL`, `PDO`, `PDO_MySQL`, `Tokenizer`, `XML`, `cURL`, `GD`
- **Database:** MySQL 8.0+ or MariaDB 10.6+ (InnoDB engine required)
- **Web Server:** Nginx (recommended) or Apache 2.4+ with `mod_rewrite`
- **Disk Storage:** SSD recommended for BIM model attachments and course video caching.

---

## 2. Directory Structure on Shared Hosting (cPanel / DirectAdmin)

To secure core Laravel files and keep `.env`, `storage`, and `vendor` outside the publicly accessible web root:

1. **Place application files above the web root:**
   - App directory: `/home/username/beforbim_app`
2. **Point the public web root (`public_html` or subdomain) to `public/`:**
   - Option A: Point your cPanel domain document root directly to `/home/username/beforbim_app/public` (Recommended).
   - Option B: If the document root cannot be changed, move the contents of `public/` into `/home/username/public_html`, and update `index.php`:
     ```php
     require __DIR__.'/../beforbim_app/vendor/autoload.php';
     $app = require_once __DIR__.'/../beforbim_app/bootstrap/app.php';
     ```

---

## 3. Environment Setup & Security

1. Upload files excluding `node_modules` and `.git`.
2. Configure your production `.env` file:
   ```ini
   APP_NAME=Beforbim
   APP_ENV=production
   APP_KEY=base64:YOUR_GENERATED_APP_KEY_HERE
   APP_DEBUG=false
   APP_URL=https://beforbim.com

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_production_db
   DB_USERNAME=your_production_user
   DB_PASSWORD="your_strong_password"

   SESSION_DRIVER=database
   SESSION_LIFETIME=120
   SESSION_ENCRYPT=true
   SESSION_SECURE_COOKIE=true

   FILESYSTEM_DISK=public
   QUEUE_CONNECTION=database
   CACHE_STORE=database

   BEFORBIM_MAX_CONCURRENT_DEVICES=1
   ```
3. Set file permissions:
   - Directories: `755`
   - Files: `644`
   - `storage/` and `bootstrap/cache/`: `775` (writable by web server user)

---

## 4. Database Migrations & Seeding

Run via SSH terminal (or cPanel Terminal / Cron Job):

```bash
# Run migrations with production guard
php artisan migrate --force

# (Optional: Only on first deployment if initial seeds required)
# php artisan db:seed --force
```

---

## 5. Storage Symlink Creation

Public files (avatars, course thumbnails, public brochures) require the storage symlink:

```bash
php artisan storage:link
```

*Note for shared hosting without SSH symlink capability:*
Create a small one-time PHP script or cron job:
```php
symlink('/home/username/beforbim_app/storage/app/public', '/home/username/public_html/storage');
```

---

## 6. Performance & Cache Optimization

Before putting the platform live, compile and optimize routes, config, and views:

```bash
# 1. Cache configuration
php artisan config:cache

# 2. Cache routes
php artisan route:cache

# 3. Cache compiled Blade views
php artisan view:cache

# 4. Cache event discovery
php artisan event:cache

# 5. Optimize Composer autoloader
composer install --optimize-autoloader --no-dev
```

To clear caches during updates:
```bash
php artisan optimize:clear
```

---

## 7. Background Queue & Cron Configuration

### Cron Job (Artisan Scheduler)
Add a cron job running every minute:
```bash
* * * * * cd /home/username/beforbim_app && php artisan schedule:run >> /dev/null 2>&1
```

### Queue Worker (Database Queue)
For background video processing, emails, and audit log batching:
- **Supervisor (VPS):**
  ```ini
  [program:beforbim-worker]
  process_name=%(program_name)s_%(process_num)02d
  command=php /home/username/beforbim_app/artisan queue:work --sleep=3 --tries=3 --max-time=3600
  autostart=true
  autorestart=true
  user=www-data
  numprocs=2
  redirect_stderr=true
  stdout_logfile=/home/username/beforbim_app/storage/logs/worker.log
  ```
- **Shared Hosting Alternative:**
  Schedule `queue:work --stop-when-empty` every 5 minutes in cPanel Cron.

---

## 8. Post-Deployment Verification Checklist

- [ ] SSL certificate active (HTTPS forced).
- [ ] `APP_DEBUG=false` confirmed in `.env`.
- [ ] MySQL database connected with all 52 tables present.
- [ ] User registration, login, and single-device session check functioning.
- [ ] File uploads (course assets, profile images) save to `storage/app/public` and load correctly via `/storage/...`.
- [ ] Cache files generated in `bootstrap/cache/`.
