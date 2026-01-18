# Panduan Deployment - CMS BPRS

## Konfigurasi Otomatis Storage

Sistem ini sudah dikonfigurasi untuk **otomatis menyesuaikan path storage** berdasarkan environment (`APP_ENV`) tanpa perlu setting manual.

### Cara Kerja

1. **Local/Development** (`APP_ENV=local`)
    - Storage URL: `http://localhost/storage`
    - Path: `storage/app/public`
2. **Production** (`APP_ENV=production`)
    - Storage URL: `https://yourdomain.com/storage`
    - Path: `storage/app/public`
    - HTTPS dipaksa otomatis jika `APP_URL` menggunakan `https://`

### Setup untuk Development (Local)

1. Copy `.env.example` ke `.env`:

    ```bash
    copy .env.example .env
    ```

2. Edit `.env`:

    ```env
    APP_ENV=local
    APP_DEBUG=true
    APP_URL=http://localhost

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=cms_db_bprs
    DB_USERNAME=root
    DB_PASSWORD=
    ```

3. Generate application key:

    ```bash
    php artisan key:generate
    ```

4. Jalankan migration:

    ```bash
    php artisan migrate --seed
    ```

5. Buat symbolic link storage:

    ```bash
    php artisan storage:link
    ```

6. Build assets:
    ```bash
    npm install
    npm run build
    ```

### Setup untuk Production

1. Upload semua file ke server

2. Edit `.env` di server:

    ```env
    APP_ENV=production
    APP_DEBUG=false
    APP_URL=https://yourdomain.com

    DB_CONNECTION=mysql
    DB_HOST=your_production_host
    DB_DATABASE=your_production_database
    DB_USERNAME=your_production_user
    DB_PASSWORD=your_secure_password

    SESSION_SECURE_COOKIE=true
    ```

3. Set permissions:

    ```bash
    chmod -R 755 storage bootstrap/cache
    chown -R www-data:www-data storage bootstrap/cache
    ```

4. Generate application key:

    ```bash
    php artisan key:generate
    ```

5. Jalankan migration:

    ```bash
    php artisan migrate --force
    ```

6. Buat symbolic link storage:

    ```bash
    php artisan storage:link
    ```

7. Optimize aplikasi:

    ```bash
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    ```

8. Build assets production:
    ```bash
    npm install
    npm run build
    ```

### Custom Storage URL (Opsional)

Jika Anda menggunakan CDN atau custom storage path, tambahkan di `.env`:

```env
STORAGE_URL=https://cdn.yourdomain.com/storage
```

### Troubleshooting

#### Gambar tidak muncul setelah deploy

1. Pastikan symbolic link sudah dibuat:

    ```bash
    php artisan storage:link
    ```

2. Cek permissions folder storage:

    ```bash
    chmod -R 755 storage
    ```

3. Clear cache:

    ```bash
    php artisan cache:clear
    php artisan config:clear
    php artisan view:clear
    ```

4. Pastikan `APP_URL` di `.env` sesuai dengan domain Anda

#### Storage URL salah

1. Cek `APP_URL` di `.env`
2. Clear config cache:
    ```bash
    php artisan config:clear
    ```

### Migrasi dari Development ke Production

1. **Backup database development**:

    ```bash
    php artisan db:backup
    ```

2. **Export database**:

    ```bash
    mysqldump -u root -p cms_db_bprs > backup.sql
    ```

3. **Upload file storage**:
    - Copy folder `storage/app/public` ke server production

4. **Import database di production**:

    ```bash
    mysql -u username -p production_database < backup.sql
    ```

5. **Update `.env` production**:

    ```env
    APP_ENV=production
    APP_URL=https://yourdomain.com
    ```

6. **Clear cache**:
    ```bash
    php artisan config:clear
    php artisan cache:clear
    ```

### Keamanan Production

Pastikan setting berikut di `.env` production:

```env
APP_ENV=production
APP_DEBUG=false
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax
```

### Monitoring

Untuk monitoring storage:

```bash
# Cek ukuran storage
du -sh storage/app/public

# Cek jumlah file
find storage/app/public -type f | wc -l
```

## Support

Jika ada masalah, hubungi tim development.
