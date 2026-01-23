# ✅ Deployment Checklist - Kas Keliling & Security Settings

## 📋 Pre-Deployment Checklist

### 1. Backup Database

- [ ] Backup database menggunakan phpMyAdmin atau command line
- [ ] Simpan backup di lokasi aman
- [ ] Verifikasi backup dapat di-restore

```bash
# Via command line
mysqldump -u username -p database_name > backup_$(date +%Y%m%d_%H%M%S).sql

# Via phpMyAdmin
# Export > Custom > Save as file
```

### 2. Verifikasi File Lokal

- [ ] Semua file sudah di-commit ke Git
- [ ] Tidak ada error di local environment
- [ ] Test kas keliling admin (CRUD)
- [ ] Test kas keliling frontend (tampilan)
- [ ] Test security settings admin
- [ ] Test blocked IPs management

### 3. Persiapan SQL Files

- [ ] File `database/sql/REBUILD_KAS_KELILING_COMPLETE.sql` tersedia
- [ ] File `database/sql/init_security_settings.sql` tersedia
- [ ] Review isi SQL files

## 🚀 Deployment Steps

### Step 1: Commit & Push ke GitHub

```bash
# Check status
git status

# Add all files
git add .

# Commit
git commit -m "Complete rebuild: Kas Keliling simplified + Security Settings"

# Push
git push origin main
```

**Verifikasi:**

- [ ] Commit berhasil
- [ ] Push berhasil
- [ ] File terlihat di GitHub repository

### Step 2: Jalankan SQL di Production Database

**Via phpMyAdmin:**

1. [ ] Login ke phpMyAdmin di hosting
2. [ ] Pilih database yang benar
3. [ ] Klik tab "SQL"
4. [ ] Copy-paste isi `database/sql/REBUILD_KAS_KELILING_COMPLETE.sql`
5. [ ] Klik "Go" / "Jalankan"
6. [ ] Verifikasi: Lihat pesan "Rebuild completed successfully!"
7. [ ] Verifikasi: Cek tabel `kas_keliling_schedules` ada dan berisi data

**Atau via MySQL Command:**

```bash
mysql -u username -p database_name < database/sql/REBUILD_KAS_KELILING_COMPLETE.sql
```

**Verifikasi:**

- [ ] Tabel `kas_keliling_schedules` berhasil dibuat
- [ ] Sample data berhasil di-insert
- [ ] Tidak ada error

### Step 3: Initialize Security Settings (Opsional)

**Hanya jika tabel `security_settings` kosong:**

1. [ ] Cek apakah tabel `security_settings` kosong
2. [ ] Jika kosong, jalankan `database/sql/init_security_settings.sql`
3. [ ] Verifikasi: Tabel `security_settings` berisi 1 row dengan default values

```sql
-- Cek apakah kosong
SELECT COUNT(*) FROM security_settings;

-- Jika kosong (count = 0), jalankan init_security_settings.sql
```

**Verifikasi:**

- [ ] Tabel `security_settings` berisi data
- [ ] Default values sudah benar

### Step 4: Pull Code di Production

```bash
# Masuk ke directory production
cd /path/to/public_html

# Pull latest code
git pull origin main
```

**Verifikasi:**

- [ ] Pull berhasil
- [ ] Tidak ada conflict
- [ ] File baru sudah ada di server

### Step 5: Clear All Caches

```bash
# Clear Laravel caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan optimize

# Jika ada OPcache
php artisan opcache:clear
```

**Verifikasi:**

- [ ] Semua cache berhasil di-clear
- [ ] Tidak ada error

### Step 6: Set Permissions (Jika Perlu)

```bash
# Set permissions untuk storage dan cache
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/

# Set ownership (sesuaikan dengan user server)
chown -R www-data:www-data storage/
chown -R www-data:www-data bootstrap/cache/
```

**Verifikasi:**

- [ ] Permissions sudah benar
- [ ] Tidak ada permission error

## 🧪 Post-Deployment Testing

### Test Kas Keliling

**Admin Panel:**

1. [ ] Login ke admin: `https://your-domain.com/admin`
2. [ ] Buka menu "Kas Keliling"
3. [ ] Verifikasi: List jadwal tampil
4. [ ] Test: Tambah jadwal baru
5. [ ] Test: Edit jadwal
6. [ ] Test: Hapus jadwal
7. [ ] Test: Filter & search

**Frontend:**

1. [ ] Buka: `https://your-domain.com/produk-layanan/kas-keliling`
2. [ ] Verifikasi: Jadwal 5 hari tampil
3. [ ] Verifikasi: Badge "HARI INI" dan "BESOK" muncul
4. [ ] Verifikasi: Detail jadwal lengkap (waktu, lokasi, PIC, fasilitas)
5. [ ] Test: Responsive di mobile

### Test Security Settings

**Admin Panel:**

1. [ ] Buka: `https://your-domain.com/admin/settings/security`
2. [ ] Verifikasi: Form settings tampil
3. [ ] Test: Update rate limiting
4. [ ] Test: Update IP blocking
5. [ ] Test: Add IP to whitelist
6. [ ] Test: Add IP to blacklist
7. [ ] Test: Save settings
8. [ ] Verifikasi: Settings tersimpan

**Blocked IPs Management:**

1. [ ] Buka: `https://your-domain.com/admin/settings/blocked-ips`
2. [ ] Test: Block IP manual
3. [ ] Test: Unblock IP
4. [ ] Test: Clear expired blocks
5. [ ] Verifikasi: Statistics tampil

**Test API:**

1. [ ] Buka: `https://your-domain.com/admin/settings/security/test`
2. [ ] Verifikasi: JSON response dengan status "success"
3. [ ] Verifikasi: Current IP tampil
4. [ ] Verifikasi: Settings values benar

## 🔍 Troubleshooting

### Kas Keliling Tidak Tampil di Frontend

**Cek Database:**

```sql
-- Cek apakah tabel ada
SHOW TABLES LIKE 'kas_keliling_schedules';

-- Cek apakah ada data
SELECT * FROM kas_keliling_schedules;

-- Cek apakah ada jadwal aktif
SELECT * FROM kas_keliling_schedules WHERE is_active = 1;
```

**Cek Log:**

```bash
tail -f storage/logs/laravel.log
```

**Clear Cache:**

```bash
php artisan cache:clear
php artisan view:clear
```

### Security Settings Tidak Bekerja

**Cek Database:**

```sql
-- Cek apakah tabel ada
SHOW TABLES LIKE 'security_settings';

-- Cek apakah ada data
SELECT * FROM security_settings;

-- Jika kosong, jalankan init_security_settings.sql
```

**Cek Routes:**

```bash
php artisan route:list | grep security
```

**Clear Cache:**

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

### Permission Errors

```bash
# Fix storage permissions
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/

# Fix ownership
chown -R www-data:www-data storage/
chown -R www-data:www-data bootstrap/cache/
```

### 500 Internal Server Error

**Cek Log:**

```bash
tail -f storage/logs/laravel.log
```

**Common Issues:**

- [ ] .env file tidak ada atau salah
- [ ] Database credentials salah
- [ ] Permissions tidak benar
- [ ] Cache corrupt (clear cache)
- [ ] Composer dependencies tidak ter-install

## 📊 Verification Checklist

### Database

- [ ] Tabel `kas_keliling_schedules` ada
- [ ] Tabel `security_settings` ada dan berisi data
- [ ] Tabel `blocked_ips` ada
- [ ] Sample data kas keliling ada

### Files

- [ ] Controller `KasKelilingController.php` ada
- [ ] Controller `SecuritySettingController.php` ada
- [ ] Model `KasKelilingSchedule.php` ada
- [ ] Model `SecuritySetting.php` ada
- [ ] Model `BlockedIp.php` ada
- [ ] Views kas keliling ada (admin & frontend)
- [ ] Views security settings ada

### Routes

- [ ] Routes kas keliling terdaftar
- [ ] Routes security settings terdaftar
- [ ] Routes blocked IPs terdaftar

### Functionality

- [ ] Kas keliling CRUD berfungsi
- [ ] Kas keliling frontend tampil
- [ ] Security settings berfungsi
- [ ] Blocked IPs management berfungsi
- [ ] Test API berfungsi

## 📝 Notes

### Menu & Access Rights

- Menu "Kas Keliling" sudah ada di `admin_menus` table
- Hak akses sudah dikonfigurasi di `admin_menu_permissions`
- Tidak perlu update menu atau permissions

### Security

- Semua input sudah protected dari SQL injection (Eloquent ORM)
- CSRF protection aktif
- XSS protection via Blade escaping
- Input validation lengkap
- IP validation menggunakan `filter_var()`

### Performance

- Cache digunakan untuk security settings
- Cache auto-clear saat update
- Indexes sudah ditambahkan di database

## 🎯 Success Criteria

Deployment dianggap berhasil jika:

1. ✅ Kas Keliling Admin:
    - CRUD berfungsi normal
    - Filter & search bekerja
    - Validation bekerja
    - Audit trail tercatat

2. ✅ Kas Keliling Frontend:
    - Jadwal 5 hari tampil
    - Badge "HARI INI" dan "BESOK" muncul
    - Detail lengkap tampil
    - Responsive di mobile

3. ✅ Security Settings:
    - Form settings berfungsi
    - Update settings berhasil
    - Whitelist/Blacklist bekerja
    - Statistics tampil

4. ✅ Blocked IPs:
    - List tampil
    - Block IP manual berfungsi
    - Unblock berfungsi
    - Clear expired berfungsi

5. ✅ No Errors:
    - Tidak ada error di log
    - Tidak ada 500 error
    - Tidak ada permission error

## 📞 Support

Jika ada masalah:

1. **Cek Log:**

    ```bash
    tail -f storage/logs/laravel.log
    ```

2. **Cek Database:**
    - Pastikan tabel ada
    - Pastikan data ada
    - Pastikan struktur benar

3. **Clear Cache:**

    ```bash
    php artisan cache:clear
    php artisan config:clear
    php artisan route:clear
    php artisan view:clear
    ```

4. **Contact:**
    - Hubungi tim development
    - Sertakan screenshot error
    - Sertakan log file

## 📚 Documentation

- `COMPLETE_REBUILD_SUMMARY.md` - Overview lengkap
- `REBUILD_KAS_KELILING_GUIDE.md` - Panduan kas keliling
- `SECURITY_SETTINGS_GUIDE.md` - Panduan security settings
- `DEPLOYMENT_CHECKLIST.md` - File ini

---

**Last Updated:** {{ date('Y-m-d H:i:s') }}
**Version:** 1.0.0
**Status:** Ready for Production
