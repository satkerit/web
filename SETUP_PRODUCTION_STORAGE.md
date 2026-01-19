# Setup Production Storage - Perbaikan Image Preview

## Masalah

Image preview pada pengaturan perusahaan tidak tampil di production karena:

1. Project Laravel berada di luar `public_html`
2. Storage symlink tidak ter-setup dengan benar
3. URL storage tidak sesuai dengan struktur folder production

## Struktur Folder Production

```
/home/user/
├── laravel_project/          # Project Laravel (di luar public_html)
│   ├── app/
│   ├── storage/
│   │   └── app/
│   │       └── public/       # File storage sebenarnya
│   └── ...
└── public_html/
    └── dev/                  # Folder public Laravel
        ├── index.php
        ├── storage/          # Symlink ke storage Laravel (HARUS DIBUAT)
        └── ...
```

## Solusi yang Diterapkan

### 1. **StorageHelper Class**

File: `app/Helpers/StorageHelper.php`

- Helper untuk generate URL storage yang benar
- Mendukung environment production dan development
- Fungsi tambahan: cek file exists, size, last modified

### 2. **Production Storage Config**

File: `config/storage-production.php`

- Konfigurasi khusus untuk production
- Path dan URL storage yang dapat dikustomisasi

### 3. **StorageServiceProvider**

File: `app/Providers/StorageServiceProvider.php`

- Service provider untuk konfigurasi storage production
- Otomatis mengatur URL storage berdasarkan environment

### 4. **Artisan Command**

File: `app/Console/Commands/SetupProductionStorage.php`

- Command untuk setup storage production secara otomatis
- Membuat symlink dan update konfigurasi

### 5. **Update Components**

- `resources/views/components/admin/image-picker.blade.php`
- `resources/views/layouts/admin.blade.php`
- `resources/views/components/frontend-layout.blade.php`

## Cara Setup di Production

### Metode 1: Menggunakan Artisan Command (Recommended)

1. **Upload semua file ke server**

2. **Jalankan command setup:**

```bash
php artisan storage:setup-production --public-path=/home/user/public_html/dev
```

3. **Command akan otomatis:**
    - Membuat symlink storage
    - Update file .env
    - Test setup

### Metode 2: Manual Setup

1. **Buat symlink storage:**

```bash
# Masuk ke folder public_html/dev
cd /home/user/public_html/dev

# Buat symlink ke storage Laravel
ln -s /home/user/laravel_project/storage/app/public storage
```

2. **Update file .env:**

```env
# Tambahkan di akhir file .env
STORAGE_URL=https://yourdomain.com/dev/storage
STORAGE_PUBLIC_PATH=/home/user/public_html/dev/storage
```

3. **Clear cache:**

```bash
php artisan config:clear
php artisan cache:clear
```

## Verifikasi Setup

### 1. **Cek Symlink**

```bash
ls -la /home/user/public_html/dev/storage
# Harus menunjukkan symlink ke storage Laravel
```

### 2. **Test Upload File**

- Login ke admin panel
- Buka "Info Perusahaan"
- Upload logo atau favicon
- Pastikan preview image tampil

### 3. **Cek URL Storage**

- Buka browser developer tools
- Periksa URL image preview
- Harus mengarah ke: `https://yourdomain.com/dev/storage/...`

## Troubleshooting

### Image Preview Tidak Tampil

1. **Cek symlink:**

```bash
ls -la /home/user/public_html/dev/
```

2. **Cek permissions:**

```bash
chmod 755 /home/user/public_html/dev/storage
chmod -R 755 /home/user/laravel_project/storage/app/public
```

3. **Cek .env configuration:**

```env
STORAGE_URL=https://yourdomain.com/dev/storage
```

### Error 404 pada Storage Files

1. **Pastikan symlink benar:**

```bash
readlink /home/user/public_html/dev/storage
# Harus menunjukkan path ke storage Laravel
```

2. **Cek web server configuration (Apache/Nginx)**
    - Pastikan symlink diizinkan
    - Cek directive `FollowSymLinks`

### Permission Denied

```bash
# Set ownership yang benar
chown -R user:user /home/user/laravel_project/storage
chown -R user:user /home/user/public_html/dev

# Set permissions
chmod -R 755 /home/user/laravel_project/storage
chmod 755 /home/user/public_html/dev/storage
```

## File yang Dimodifikasi

### File Baru:

- `app/Helpers/StorageHelper.php`
- `config/storage-production.php`
- `app/Providers/StorageServiceProvider.php`
- `app/Console/Commands/SetupProductionStorage.php`

### File yang Diupdate:

- `resources/views/components/admin/image-picker.blade.php`
- `resources/views/layouts/admin.blade.php`
- `resources/views/components/frontend-layout.blade.php`

## Testing Checklist

- [ ] Symlink storage dibuat dengan benar
- [ ] File .env ter-update dengan STORAGE_URL
- [ ] Image preview tampil di admin panel
- [ ] Logo/favicon tampil di website
- [ ] Upload file baru berfungsi
- [ ] File lama masih dapat diakses
- [ ] URL storage mengarah ke domain yang benar

## Maintenance

### Backup Storage

```bash
# Backup folder storage
tar -czf storage_backup_$(date +%Y%m%d).tar.gz /home/user/laravel_project/storage/app/public
```

### Monitor Storage Usage

```bash
# Cek ukuran storage
du -sh /home/user/laravel_project/storage/app/public
```

### Clean Old Files

```bash
# Hapus file temporary yang lama (opsional)
find /home/user/laravel_project/storage/app/public -name "*.tmp" -mtime +7 -delete
```
