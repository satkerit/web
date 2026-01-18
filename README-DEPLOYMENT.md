# 📚 Dokumentasi Deployment - CMS BPRS

## 📖 Daftar Dokumentasi

1. **QUICK-START.md** - Panduan cepat setup development dan production
2. **DEPLOYMENT.md** - Panduan deployment umum
3. **DEPLOYMENT-PRODUCTION.md** - Panduan khusus production dengan root di luar public_html
4. **README.md** - Dokumentasi aplikasi utama

## 🎯 Pilih Panduan Sesuai Kebutuhan

### Untuk Development (Local)

👉 Baca: **QUICK-START.md** → Section "Development (Local)"

### Untuk Production - Struktur Normal

👉 Baca: **DEPLOYMENT.md**

### Untuk Production - Root di Luar public_html

👉 Baca: **DEPLOYMENT-PRODUCTION.md** ⭐ (Recommended untuk shared hosting)

## 🚀 Quick Commands

### Development

```bash
# Setup awal
composer install && npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
npm run dev

# Jalankan server
php artisan serve
```

### Production (Root di luar public_html)

```bash
# Setup otomatis
php artisan production:setup --public-path=/home/user/public_html

# Cek setup
php artisan production:setup --check

# Switch environment
php artisan env:switch production
```

## 🔧 Fitur Otomatis

### 1. Auto Storage Configuration

Sistem otomatis menyesuaikan storage URL berdasarkan `APP_ENV`:

- **Local**: `http://localhost/storage`
- **Production**: `https://yourdomain.com/storage`

### 2. Environment Switcher

```bash
php artisan env:switch local       # Switch ke development
php artisan env:switch production  # Switch ke production
```

### 3. Production Setup

```bash
php artisan production:setup --public-path=/path/to/public_html
```

Otomatis:

- Copy public files
- Update index.php paths
- Create storage symlink
- Set permissions
- Update .env

### 4. Setup Checker

```bash
php artisan production:setup --check
```

Menampilkan:

- Current paths
- Storage symlink status
- Permissions
- Environment variables

## 📁 Struktur Production

### Struktur Normal (Root = public_html)

```
public_html/
├── app/
├── bootstrap/
├── config/
├── storage/
├── vendor/
├── public/
│   └── index.php
└── .env
```

### Struktur Shared Hosting (Root di luar public_html)

```
/home/user/
├── laravel_root/          ← Root Laravel
│   ├── app/
│   ├── config/
│   ├── storage/
│   ├── vendor/
│   └── .env
└── public_html/           ← Document root
    ├── index.php          ← Modified
    ├── storage/           ← Symlink
    └── build/
```

## ⚙️ Konfigurasi .env

### Development

```env
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost
SESSION_SECURE_COOKIE=false
```

### Production

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
SESSION_SECURE_COOKIE=true

# Untuk root di luar public_html
STORAGE_PUBLIC_PATH=/home/user/laravel_root/storage/app/public
```

### Custom Storage URL (Optional)

```env
STORAGE_URL=https://cdn.yourdomain.com/storage
```

## 🔍 Troubleshooting

### Gambar tidak muncul

```bash
# Cek symlink
ls -la public_html/storage

# Recreate symlink
php artisan storage:link
# atau untuk production:
cd public_html
ln -s /home/user/laravel_root/storage/app/public storage

# Cek permissions
chmod -R 755 storage
```

### Storage URL salah

```bash
# Cek konfigurasi
php artisan production:setup --check

# Clear cache
php artisan config:clear
php artisan cache:clear
```

### Error 500

```bash
# Cek error log
tail -f storage/logs/laravel.log

# Set permissions
chmod -R 755 storage bootstrap/cache

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

## 📋 Checklist Deployment

### Pre-Deployment

- [ ] Backup database
- [ ] Export storage files
- [ ] Test di local
- [ ] Build assets: `npm run build`
- [ ] Update `.env.example`

### Deployment

- [ ] Upload files
- [ ] Run `php artisan production:setup`
- [ ] Edit `.env`
- [ ] Run `php artisan migrate --force`
- [ ] Run `php artisan config:cache`
- [ ] Test upload gambar

### Post-Deployment

- [ ] Test login
- [ ] Test semua fitur
- [ ] Monitor logs
- [ ] Check storage URL

## 🔐 Keamanan

### Production Checklist

- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false`
- [ ] `SESSION_SECURE_COOKIE=true`
- [ ] Strong database password
- [ ] HTTPS enabled
- [ ] Permissions: 755 untuk storage
- [ ] `.env` protected (chmod 600)

## 📞 Support

Jika mengalami masalah:

1. Cek dokumentasi yang sesuai
2. Jalankan `php artisan production:setup --check`
3. Cek error log: `storage/logs/laravel.log`
4. Hubungi tim development

## 🎓 Tips

1. **Selalu backup** sebelum deployment
2. **Test di staging** sebelum production
3. **Monitor logs** setelah deployment
4. **Gunakan command otomatis** untuk menghindari error manual
5. **Clear cache** setelah perubahan konfigurasi

---

**Dibuat dengan ❤️ untuk kemudahan deployment**
