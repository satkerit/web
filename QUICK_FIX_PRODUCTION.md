# 🚀 Quick Fix - Image Preview Production

## Masalah

Image preview pada pengaturan perusahaan tidak tampil di production.

## Solusi Cepat

### 1. Upload Files

Upload semua file project ke server production.

### 2. Jalankan Script Setup

```bash
chmod +x setup-production-storage.sh
./setup-production-storage.sh
```

### 3. Ikuti Instruksi

Script akan meminta path ke `public_html/dev`, contoh:

```
/home/user/public_html/dev
```

### 4. Selesai!

Script akan otomatis:

- ✅ Membuat symlink storage
- ✅ Update file .env
- ✅ Test setup
- ✅ Set permissions

## Manual (Jika Script Gagal)

```bash
# 1. Buat symlink
cd /home/user/public_html/dev
ln -s /home/user/laravel_project/storage/app/public storage

# 2. Update .env
echo "STORAGE_URL=https://yourdomain.com/dev/storage" >> .env
echo "STORAGE_PUBLIC_PATH=/home/user/public_html/dev/storage" >> .env

# 3. Clear cache
php artisan config:clear
```

## Test

1. Login admin panel
2. Buka "Info Perusahaan"
3. Upload logo
4. Pastikan preview tampil

## Bantuan

Jika masih bermasalah, lihat file:

- `SETUP_PRODUCTION_STORAGE.md` (panduan lengkap)
- `DEPLOYMENT_GUIDE.md` (deployment penuh)
