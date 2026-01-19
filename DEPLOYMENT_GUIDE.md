# Deployment Guide - Production Setup

## Quick Fix untuk Image Preview Issue

Jika Anda mengalami masalah image preview tidak tampil di production, ikuti langkah-langkah berikut:

### 🚀 Quick Setup (Recommended)

1. **Upload semua file ke server**

2. **Jalankan script setup otomatis:**

```bash
chmod +x setup-production-storage.sh
./setup-production-storage.sh
```

3. **Ikuti instruksi di layar**

### 📋 Manual Setup

Jika script otomatis tidak bisa dijalankan:

1. **Buat symlink storage:**

```bash
cd /home/user/public_html/dev
ln -s /home/user/laravel_project/storage/app/public storage
```

2. **Update .env file:**

```bash
# Tambahkan di akhir .env
echo "STORAGE_URL=https://yourdomain.com/dev/storage" >> .env
echo "STORAGE_PUBLIC_PATH=/home/user/public_html/dev/storage" >> .env
```

3. **Set permissions:**

```bash
chmod 755 /home/user/public_html/dev/storage
chmod -R 755 /home/user/laravel_project/storage/app/public
```

4. **Clear cache:**

```bash
php artisan config:clear
php artisan cache:clear
```

### ✅ Verifikasi

1. Login ke admin panel
2. Buka "Info Perusahaan"
3. Upload logo atau favicon
4. Pastikan preview image tampil

### 🔧 Troubleshooting

**Image masih tidak tampil?**

1. Cek symlink:

```bash
ls -la /home/user/public_html/dev/storage
```

2. Cek URL di browser developer tools
3. Pastikan domain di STORAGE_URL benar

**Need help?** Lihat file `SETUP_PRODUCTION_STORAGE.md` untuk panduan lengkap.

---

## Full Deployment Process

### 1. Server Requirements

- PHP 8.1+
- MySQL 5.7+ / MariaDB 10.3+
- Composer
- Node.js & NPM (untuk build assets)

### 2. Upload Files

Upload semua file project ke server, kecuali:

- `node_modules/`
- `.env` (buat baru)
- `storage/logs/`
- `bootstrap/cache/`

### 3. Setup Environment

```bash
# Copy environment file
cp .env.production.example .env

# Edit .env dengan konfigurasi yang benar
nano .env

# Generate app key
php artisan key:generate
```

### 4. Install Dependencies

```bash
# Install PHP dependencies
composer install --optimize-autoloader --no-dev

# Install Node dependencies dan build assets
npm install
npm run build
```

### 5. Database Setup

```bash
# Run migrations
php artisan migrate --force

# Seed database (optional)
php artisan db:seed --force
```

### 6. Storage Setup

```bash
# Setup storage untuk production
./setup-production-storage.sh

# Atau manual:
php artisan storage:setup-production --public-path=/path/to/public_html/dev
```

### 7. Optimize for Production

```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Cache events
php artisan event:cache
```

### 8. Set Permissions

```bash
# Set ownership
chown -R user:user /path/to/laravel/project

# Set permissions
chmod -R 755 /path/to/laravel/project
chmod -R 775 /path/to/laravel/project/storage
chmod -R 775 /path/to/laravel/project/bootstrap/cache
```

### 9. Web Server Configuration

#### Apache (.htaccess)

File sudah tersedia di `public/.htaccess`

#### Nginx

```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /home/user/public_html/dev;

    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

### 10. SSL Certificate

```bash
# Using Let's Encrypt
certbot --nginx -d yourdomain.com
```

### 11. Monitoring & Maintenance

#### Setup Cron Jobs

```bash
# Laravel scheduler
* * * * * cd /path/to/laravel && php artisan schedule:run >> /dev/null 2>&1

# Log rotation (optional)
0 0 * * * find /path/to/laravel/storage/logs -name "*.log" -mtime +30 -delete
```

#### Backup Script

```bash
#!/bin/bash
# backup.sh
DATE=$(date +%Y%m%d_%H%M%S)
mysqldump -u user -p database_name > backup_db_$DATE.sql
tar -czf backup_files_$DATE.tar.gz /path/to/laravel/storage/app/public
```

### 12. Security Checklist

- [ ] APP_DEBUG=false
- [ ] Strong APP_KEY generated
- [ ] Database credentials secure
- [ ] File permissions correct (755/775)
- [ ] SSL certificate installed
- [ ] Rate limiting configured
- [ ] Security headers configured
- [ ] Backup system in place

### 13. Performance Optimization

```bash
# Enable OPcache (php.ini)
opcache.enable=1
opcache.memory_consumption=128
opcache.max_accelerated_files=4000

# Enable compression (Apache)
# Add to .htaccess or virtual host
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/xml
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/rss+xml
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/x-javascript
</IfModule>
```

### 14. Post-Deployment Testing

- [ ] Website loads correctly
- [ ] Admin panel accessible
- [ ] Image upload/preview works
- [ ] Email notifications work
- [ ] Database connections stable
- [ ] SSL certificate valid
- [ ] Performance acceptable

---

## Common Issues & Solutions

### Issue: 500 Internal Server Error

**Solution:**

```bash
# Check error logs
tail -f /path/to/laravel/storage/logs/laravel.log

# Common fixes:
chmod -R 775 storage bootstrap/cache
php artisan config:clear
```

### Issue: Images not loading

**Solution:**

```bash
# Check storage symlink
ls -la public_html/dev/storage

# Recreate if needed
./setup-production-storage.sh
```

### Issue: CSS/JS not loading

**Solution:**

```bash
# Rebuild assets
npm run build

# Check file permissions
chmod -R 755 public_html/dev/build
```

### Issue: Database connection error

**Solution:**

- Check .env database credentials
- Verify database server is running
- Test connection manually

---

## Support

Jika mengalami masalah:

1. Cek log error: `storage/logs/laravel.log`
2. Cek web server error log
3. Verifikasi konfigurasi .env
4. Pastikan semua dependencies terinstall
5. Cek file permissions

Untuk masalah storage khusus, lihat: `SETUP_PRODUCTION_STORAGE.md`
