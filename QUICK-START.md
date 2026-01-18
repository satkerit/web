# Quick Start Guide - CMS BPRS

## 🚀 Setup Cepat

### Development (Local)

```bash
# 1. Clone & Install
git clone <repository-url>
cd cms_baru
composer install
npm install

# 2. Setup Environment
copy .env.example .env
php artisan key:generate

# 3. Setup Database
# Edit .env sesuai database lokal Anda
php artisan migrate --seed

# 4. Setup Storage
php artisan storage:link

# 5. Build Assets
npm run dev
# atau untuk production build:
npm run build

# 6. Jalankan Server
php artisan serve
```

Akses: http://localhost:8000

### Production

**Untuk struktur production (root di luar public_html):**

```bash
# 1. Upload files ke laravel_root (di luar public_html)

# 2. Setup otomatis
cd /home/user/laravel_root
php artisan production:setup --public-path=/home/user/public_html

# 3. Edit .env
nano .env
# Update: APP_URL, DB_*, MAIL_*

# 4. Setup Database
php artisan key:generate
php artisan migrate --force

# 5. Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build
```

**Lihat panduan lengkap:** `DEPLOYMENT-PRODUCTION.md`

## 🔄 Switch Environment

### Otomatis (Recommended)

```bash
# Switch ke local
php artisan env:switch local

# Switch ke production
php artisan env:switch production
```

### Manual

Edit `.env`:

**Local:**

```env
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost
SESSION_SECURE_COOKIE=false
```

**Production:**

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
SESSION_SECURE_COOKIE=true
```

Kemudian clear cache:

```bash
php artisan config:clear
php artisan cache:clear
```

## 📁 Storage Configuration

### Otomatis (Tidak perlu setting!)

Storage URL akan otomatis disesuaikan:

- **Local**: `http://localhost/storage`
- **Production**: `https://yourdomain.com/storage`

### Custom Storage URL (Opsional)

Jika menggunakan CDN, tambahkan di `.env`:

```env
STORAGE_URL=https://cdn.yourdomain.com/storage
```

## 🔐 Default Login

**Admin:**

- Email: `admin@example.com`
- Password: `password`

**Super Admin:**

- Email: `superadmin@example.com`
- Password: `password`

⚠️ **Penting**: Ganti password setelah login pertama!

## 📝 Checklist Deployment

### Pre-Deployment

- [ ] Backup database development
- [ ] Export file storage (`storage/app/public`)
- [ ] Update `.env.example` jika ada perubahan
- [ ] Test di local environment
- [ ] Build production assets (`npm run build`)

### Deployment

- [ ] Upload files ke server
- [ ] Switch environment: `php artisan env:switch production`
- [ ] Update `.env` (APP_URL, DB, MAIL)
- [ ] Import database
- [ ] Upload file storage
- [ ] Set permissions (755 untuk storage)
- [ ] Run migrations: `php artisan migrate --force`
- [ ] Create storage link: `php artisan storage:link`
- [ ] Cache config: `php artisan config:cache`
- [ ] Cache routes: `php artisan route:cache`
- [ ] Cache views: `php artisan view:cache`

### Post-Deployment

- [ ] Test login admin
- [ ] Test upload gambar
- [ ] Test email notification
- [ ] Check storage URL (inspect gambar di browser)
- [ ] Test semua fitur utama
- [ ] Monitor error logs

## 🛠️ Troubleshooting

### Gambar tidak muncul

```bash
# 1. Cek symbolic link
ls -la public/storage

# 2. Recreate link
php artisan storage:link

# 3. Cek permissions
chmod -R 755 storage

# 4. Clear cache
php artisan config:clear
```

### Storage URL salah

```bash
# 1. Cek APP_URL di .env
cat .env | grep APP_URL

# 2. Clear config
php artisan config:clear

# 3. Test storage URL
php artisan tinker
>>> config('filesystems.disks.public.url')
```

### Error 500 setelah deploy

```bash
# 1. Cek permissions
chmod -R 755 storage bootstrap/cache

# 2. Clear all cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# 3. Cek error log
tail -f storage/logs/laravel.log
```

## 📞 Support

Untuk bantuan lebih lanjut, lihat:

- `DEPLOYMENT.md` - Panduan deployment lengkap
- `README.md` - Dokumentasi aplikasi
- `storage/logs/laravel.log` - Error logs

## 🎯 Tips

1. **Selalu backup** sebelum deployment
2. **Test di staging** sebelum production
3. **Monitor logs** setelah deployment
4. **Update dependencies** secara berkala
5. **Gunakan HTTPS** di production
