# 🚀 Setup Guide - Pilih Sesuai Struktur Anda

## 📋 Identifikasi Struktur Anda

### Struktur 1: Development Normal

```
project_laravel/
└── public/              ← http://localhost/
    └── storage/         ← Symlink
```

**Gunakan:** Setup development biasa

### Struktur 2: Production dengan Subdirectory

```
/home/user/
├── project_laravel/     ← Root Laravel
└── public_html/
    └── dev/             ← https://yourdomain.com/dev/
        └── storage/     ← Symlink
```

**Gunakan:** `DEPLOYMENT-SUBDIRECTORY.md` ⭐

### Struktur 3: Production Normal

```
/home/user/
├── project_laravel/     ← Root Laravel
└── public_html/         ← https://yourdomain.com/
    └── storage/         ← Symlink
```

**Gunakan:** `DEPLOYMENT-PRODUCTION.md`

## ⚡ Quick Setup

### Development (Local)

```bash
# 1. Install
composer install && npm install

# 2. Setup
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link

# 3. Run
npm run dev
php artisan serve
```

### Production dengan Subdirectory (public_html/dev/)

```bash
# 1. Upload ke /home/user/project_laravel/

# 2. Setup otomatis
cd /home/user/project_laravel
php artisan production:setup \
    --public-path=/home/user/public_html \
    --subdir=dev

# 3. Edit .env
nano .env
```

Tambahkan di `.env`:

```env
APP_ENV=production
APP_URL=https://yourdomain.com
PRODUCTION_SUBDIR=dev
```

```bash
# 4. Finish
php artisan key:generate
php artisan migrate --force
php artisan config:cache
npm run build
```

## 🔧 Konfigurasi .env

### Development

```env
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost
```

### Production (Subdirectory)

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
PRODUCTION_SUBDIR=dev
STORAGE_PUBLIC_PATH=/home/user/project_laravel/storage/app/public
```

## ✅ Verifikasi

### Cek Storage URL

```bash
php artisan tinker
>>> config('filesystems.disks.public.url')
```

**Development:** `http://localhost/storage`
**Production (subdir):** `https://yourdomain.com/dev/storage`

### Cek Symlink

```bash
# Development
ls -la public/storage

# Production
ls -la public_html/dev/storage
```

### Test Upload

1. Login admin
2. Upload logo
3. Inspect element gambar
4. Cek URL gambar

## 🔍 Troubleshooting

### Gambar tidak muncul

```bash
# 1. Cek symlink
ls -la public_html/dev/storage

# 2. Recreate symlink
cd public_html/dev
ln -s ../../project_laravel/storage/app/public storage

# 3. Clear cache
php artisan config:clear
```

### Storage URL salah

```bash
# 1. Cek .env
cat .env | grep PRODUCTION_SUBDIR

# 2. Clear cache
php artisan config:clear
php artisan cache:clear
```

## 📚 Dokumentasi Lengkap

- `QUICK-START.md` - Quick reference
- `DEPLOYMENT-SUBDIRECTORY.md` - **Untuk struktur public_html/dev/**
- `DEPLOYMENT-PRODUCTION.md` - Untuk struktur normal
- `README-DEPLOYMENT.md` - Index semua dokumentasi

## 💡 Tips

1. **Selalu backup** sebelum deployment
2. **Test di local** sebelum upload
3. **Clear cache** setelah ubah `.env`
4. **Verify symlink** setelah setup
5. **Check storage URL** dengan inspect element

## 🎯 Command Penting

```bash
# Setup production
php artisan production:setup --public-path=/path --subdir=dev

# Cek setup
php artisan production:setup --check

# Switch environment
php artisan env:switch production

# Clear cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Optimize production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 📞 Butuh Bantuan?

1. Cek dokumentasi yang sesuai struktur Anda
2. Run: `php artisan production:setup --check`
3. Lihat error log: `storage/logs/laravel.log`
