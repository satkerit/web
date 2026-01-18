# Panduan Deployment Production - CMS BPRS

## (Root Laravel di Luar public_html)

## 📁 Struktur Folder Production

```
/home/user/
├── laravel_root/              ← Root Laravel (di luar public_html)
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── resources/
│   ├── routes/
│   ├── storage/
│   ├── vendor/
│   ├── .env
│   └── artisan
└── public_html/               ← Document root web server
    ├── index.php              ← Modified untuk point ke laravel_root
    ├── storage/               ← Symlink ke laravel_root/storage/app/public
    ├── css/
    ├── js/
    └── build/
```

## 🚀 Setup Otomatis (Recommended)

### 1. Upload Files

Upload semua file Laravel ke folder `laravel_root` (di luar public_html):

```bash
# Via FTP/SFTP atau rsync
rsync -avz --exclude 'node_modules' --exclude '.git' ./ user@server:/home/user/laravel_root/
```

### 2. Jalankan Setup Command

```bash
cd /home/user/laravel_root
php artisan production:setup --public-path=/home/user/public_html
```

Command ini akan otomatis:

- ✓ Copy file dari `public/` ke `public_html/`
- ✓ Update path di `index.php`
- ✓ Buat symlink storage
- ✓ Set permissions
- ✓ Update `.env`

### 3. Konfigurasi .env

Edit `/home/user/laravel_root/.env`:

```env
APP_NAME="Bank Syariah Babel"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_production_database
DB_USERNAME=your_production_user
DB_PASSWORD=your_secure_password

SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax

FILESYSTEM_DISK=public

# Path ini sudah di-set otomatis oleh command
STORAGE_PUBLIC_PATH=/home/user/laravel_root/storage/app/public

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"
```

### 4. Setup Database

```bash
cd /home/user/laravel_root
php artisan migrate --force
php artisan db:seed --class=AdminSeeder
```

### 5. Optimize Application

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 6. Build Assets

```bash
npm install --production
npm run build
```

### 7. Set Permissions

```bash
chmod -R 755 /home/user/laravel_root/storage
chmod -R 755 /home/user/laravel_root/bootstrap/cache
```

## 🔧 Setup Manual

Jika command otomatis tidak bisa dijalankan:

### 1. Upload Files

Upload ke `/home/user/laravel_root/`

### 2. Copy Public Files

```bash
cp -r /home/user/laravel_root/public/* /home/user/public_html/
```

### 3. Edit index.php

Edit `/home/user/public_html/index.php`:

```php
<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Update path ke Laravel root
$laravelRoot = '/home/user/laravel_root';

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = $laravelRoot.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require $laravelRoot.'/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
$app = require_once $laravelRoot.'/bootstrap/app.php';

$app->handleRequest(Request::capture());
```

### 4. Buat Symlink Storage

```bash
cd /home/user/public_html
rm -rf storage  # Hapus jika sudah ada
ln -s /home/user/laravel_root/storage/app/public storage
```

Verifikasi symlink:

```bash
ls -la /home/user/public_html/storage
# Output: storage -> /home/user/laravel_root/storage/app/public
```

### 5. Set Permissions

```bash
chmod -R 755 /home/user/laravel_root/storage
chmod -R 755 /home/user/laravel_root/bootstrap/cache
chmod -R 755 /home/user/public_html
```

### 6. Konfigurasi .env

Copy dan edit:

```bash
cp /home/user/laravel_root/.env.example /home/user/laravel_root/.env
nano /home/user/laravel_root/.env
```

Isi sesuai contoh di atas.

### 7. Generate Key & Migrate

```bash
cd /home/user/laravel_root
php artisan key:generate
php artisan migrate --force
```

### 8. Optimize

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## ✅ Verifikasi Setup

### 1. Cek Struktur

```bash
# Cek symlink storage
ls -la /home/user/public_html/storage

# Cek permissions
ls -la /home/user/laravel_root/storage
ls -la /home/user/laravel_root/bootstrap/cache
```

### 2. Test Storage URL

```bash
cd /home/user/laravel_root
php artisan tinker
>>> config('filesystems.disks.public.url')
=> "https://yourdomain.com/storage"
```

### 3. Test Upload

- Login ke admin
- Upload logo di Info Perusahaan
- Cek apakah gambar muncul di frontend

### 4. Cek Command

```bash
php artisan production:setup --check
```

Output akan menampilkan konfigurasi saat ini.

## 🔍 Troubleshooting

### Gambar tidak muncul

**Cek symlink:**

```bash
ls -la /home/user/public_html/storage
```

Jika tidak ada atau salah, buat ulang:

```bash
cd /home/user/public_html
rm -rf storage
ln -s /home/user/laravel_root/storage/app/public storage
```

**Cek permissions:**

```bash
chmod -R 755 /home/user/laravel_root/storage
```

**Cek .env:**

```bash
cat /home/user/laravel_root/.env | grep APP_URL
cat /home/user/laravel_root/.env | grep STORAGE_PUBLIC_PATH
```

### Error 500

**Cek error log:**

```bash
tail -f /home/user/laravel_root/storage/logs/laravel.log
```

**Clear cache:**

```bash
cd /home/user/laravel_root
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

**Cek permissions:**

```bash
chmod -R 755 /home/user/laravel_root/storage
chmod -R 755 /home/user/laravel_root/bootstrap/cache
```

### Storage URL salah

**Update .env:**

```env
APP_URL=https://yourdomain.com
STORAGE_PUBLIC_PATH=/home/user/laravel_root/storage/app/public
```

**Clear config:**

```bash
php artisan config:clear
```

### Path tidak ditemukan

**Cek index.php:**

```bash
cat /home/user/public_html/index.php | grep laravel_root
```

Pastikan path mengarah ke `/home/user/laravel_root`

## 📋 Checklist Deployment

- [ ] Upload files ke `laravel_root`
- [ ] Jalankan `php artisan production:setup` atau setup manual
- [ ] Edit `.env` (APP_URL, DB, MAIL)
- [ ] Generate key: `php artisan key:generate`
- [ ] Migrate database: `php artisan migrate --force`
- [ ] Cek symlink storage
- [ ] Set permissions (755)
- [ ] Cache config: `php artisan config:cache`
- [ ] Build assets: `npm run build`
- [ ] Test upload gambar
- [ ] Test semua fitur
- [ ] Monitor error logs

## 🔐 Keamanan

### File Permissions

```bash
# Laravel root
chmod -R 755 /home/user/laravel_root
chmod -R 755 /home/user/laravel_root/storage
chmod -R 755 /home/user/laravel_root/bootstrap/cache

# Public HTML
chmod -R 755 /home/user/public_html

# .env harus protected
chmod 600 /home/user/laravel_root/.env
```

### .htaccess

Pastikan `/home/user/public_html/.htaccess` ada:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

### Disable Directory Listing

Tambahkan di `.htaccess`:

```apache
Options -Indexes
```

## 📞 Support

Untuk bantuan lebih lanjut:

- Lihat `DEPLOYMENT.md` untuk panduan umum
- Lihat `QUICK-START.md` untuk quick reference
- Check error logs: `/home/user/laravel_root/storage/logs/laravel.log`
