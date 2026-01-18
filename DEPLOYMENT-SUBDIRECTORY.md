# Panduan Deployment - Subdirectory Structure

## (public_html/dev/)

## 📁 Struktur Folder

### Development (Local)

```
project_laravel/
├── app/
├── config/
├── storage/
│   └── app/
│       └── public/          ← Storage files
├── public/                  ← Document root (dev)
│   ├── index.php
│   └── storage/             ← Symlink ke ../storage/app/public
└── .env
```

### Production

```
/home/user/
├── project_laravel/         ← Root Laravel (di luar public_html)
│   ├── app/
│   ├── config/
│   ├── storage/
│   │   └── app/
│   │       └── public/      ← Storage files
│   └── .env
└── public_html/
    └── dev/                 ← Document root (production)
        ├── index.php        ← Modified untuk point ke ../../project_laravel
        └── storage/         ← Symlink ke ../../project_laravel/storage/app/public
```

## 🚀 Setup Production (Otomatis)

### 1. Upload Files

Upload semua file Laravel ke `/home/user/project_laravel/`

### 2. Jalankan Setup Command

```bash
cd /home/user/project_laravel
php artisan production:setup \
    --public-path=/home/user/public_html \
    --subdir=dev
```

Command ini akan otomatis:

- ✓ Copy file dari `public/` ke `public_html/dev/`
- ✓ Update path di `index.php`
- ✓ Buat symlink storage
- ✓ Set permissions
- ✓ Update `.env` dengan `PRODUCTION_SUBDIR=dev`

### 3. Konfigurasi .env

Edit `/home/user/project_laravel/.env`:

```env
APP_NAME="Bank Syariah Babel"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database
DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Session
SESSION_SECURE_COOKIE=true

# Storage Configuration
FILESYSTEM_DISK=public
PRODUCTION_SUBDIR=dev
STORAGE_PUBLIC_PATH=/home/user/project_laravel/storage/app/public
```

**Penting:** `PRODUCTION_SUBDIR=dev` akan membuat storage URL menjadi `https://yourdomain.com/dev/storage`

### 4. Setup Database

```bash
cd /home/user/project_laravel
php artisan key:generate
php artisan migrate --force
```

### 5. Optimize

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

## 🔧 Setup Manual

### 1. Copy Public Files

```bash
mkdir -p /home/user/public_html/dev
cp -r /home/user/project_laravel/public/* /home/user/public_html/dev/
```

### 2. Edit index.php

Edit `/home/user/public_html/dev/index.php`:

```php
<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Path ke Laravel root (2 level up dari dev/)
$laravelRoot = __DIR__ . '/../../project_laravel';

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

### 3. Buat Symlink Storage

```bash
cd /home/user/public_html/dev
rm -rf storage  # Hapus jika sudah ada
ln -s ../../project_laravel/storage/app/public storage
```

Verifikasi:

```bash
ls -la /home/user/public_html/dev/storage
# Output: storage -> ../../project_laravel/storage/app/public
```

### 4. Set Permissions

```bash
chmod -R 755 /home/user/project_laravel/storage
chmod -R 755 /home/user/project_laravel/bootstrap/cache
```

### 5. Edit .env

```bash
nano /home/user/project_laravel/.env
```

Tambahkan:

```env
PRODUCTION_SUBDIR=dev
STORAGE_PUBLIC_PATH=/home/user/project_laravel/storage/app/public
```

## ✅ Verifikasi

### 1. Cek Symlink

```bash
ls -la /home/user/public_html/dev/storage
# Harus menunjuk ke: ../../project_laravel/storage/app/public
```

### 2. Cek Storage URL

```bash
cd /home/user/project_laravel
php artisan tinker
>>> config('filesystems.disks.public.url')
=> "https://yourdomain.com/dev/storage"
```

### 3. Test Upload

1. Login ke admin
2. Upload logo di Info Perusahaan
3. Inspect element pada gambar logo
4. URL harus: `https://yourdomain.com/dev/storage/company/xxx.png`

### 4. Cek Setup

```bash
php artisan production:setup --check
```

## 🔄 Switch Environment

### Development ke Production

**Development (.env):**

```env
APP_ENV=local
APP_URL=http://localhost
# Tidak perlu PRODUCTION_SUBDIR
```

**Production (.env):**

```env
APP_ENV=production
APP_URL=https://yourdomain.com
PRODUCTION_SUBDIR=dev
```

Setelah ubah `.env`:

```bash
php artisan config:clear
php artisan cache:clear
```

## 🔍 Troubleshooting

### Gambar tidak muncul

**1. Cek symlink:**

```bash
ls -la /home/user/public_html/dev/storage
```

Jika salah, buat ulang:

```bash
cd /home/user/public_html/dev
rm -rf storage
ln -s ../../project_laravel/storage/app/public storage
```

**2. Cek .env:**

```bash
cat /home/user/project_laravel/.env | grep PRODUCTION_SUBDIR
# Harus: PRODUCTION_SUBDIR=dev
```

**3. Clear cache:**

```bash
php artisan config:clear
php artisan cache:clear
```

### Storage URL salah

**Cek konfigurasi:**

```bash
php artisan tinker
>>> config('filesystems.disks.public.url')
```

Harus: `https://yourdomain.com/dev/storage`

Jika salah:

1. Pastikan `PRODUCTION_SUBDIR=dev` di `.env`
2. Run: `php artisan config:clear`

### Path tidak ditemukan

**Cek index.php:**

```bash
cat /home/user/public_html/dev/index.php | grep laravelRoot
```

Harus: `$laravelRoot = __DIR__ . '/../../project_laravel';`

## 📋 Checklist

- [ ] Upload files ke `/home/user/project_laravel/`
- [ ] Run `php artisan production:setup --public-path=/home/user/public_html --subdir=dev`
- [ ] Edit `.env` (APP_URL, DB, PRODUCTION_SUBDIR=dev)
- [ ] Run `php artisan key:generate`
- [ ] Run `php artisan migrate --force`
- [ ] Cek symlink: `ls -la public_html/dev/storage`
- [ ] Set permissions: `chmod -R 755 storage`
- [ ] Run `php artisan config:cache`
- [ ] Build assets: `npm run build`
- [ ] Test upload gambar
- [ ] Verify storage URL di browser

## 💡 Tips

1. **Subdirectory name** bisa apa saja (dev, app, cms, dll)
2. **Jangan lupa** set `PRODUCTION_SUBDIR` di `.env`
3. **Clear cache** setelah ubah `.env`
4. **Test storage URL** dengan inspect element gambar
5. **Backup** sebelum deployment

## 📞 Support

Jika ada masalah:

1. Run: `php artisan production:setup --check`
2. Cek error log: `tail -f storage/logs/laravel.log`
3. Verify symlink: `ls -la public_html/dev/storage`
