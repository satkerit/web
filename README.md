# Website Company Profile Bank Perekonomian Rakyat Syariah

Website company profile yang dibangun dengan Laravel 12 dan Tailwind CSS untuk Bank Perekonomian Rakyat Syariah. Website ini menampilkan informasi lengkap tentang perusahaan, produk dan layanan, serta berbagai fitur yang diperlukan untuk website perbankan syariah.

## 🚀 Fitur Utama

### Frontend

-   **Responsive Design** - Tampilan yang optimal di semua perangkat
-   **Modern UI/UX** - Desain yang interaktif dan sesuai tren terkini
-   **Navigasi yang Jelas** - Struktur menu yang mudah dipahami
-   **Kecepatan Optimal** - Optimasi performa untuk loading yang cepat
-   **Keamanan Terjamin** - Implementasi best practices keamanan web

### Halaman Frontend

-   **Home Page** dengan hero slider, produk unggulan, berita, dan lelang
-   **Pengurus**
    -   Dewan Komisaris (biografi, foto, jabatan)
    -   Dewan Direksi (biografi, foto, jabatan)
    -   Dewan Pengawas Syariah (biografi, foto, jabatan)
-   **Tentang Kami**
    -   Tentang Perusahaan (sejarah, visi & misi)
    -   Struktur Organisasi (bagan dengan foto dan jabatan)
    -   Kantor (informasi detail kantor beserta foto)
-   **Produk & Layanan**
    -   Simpanan Syariah (detail produk simpanan)
    -   Pembiayaan Syariah (detail produk pembiayaan)
    -   Kas Keliling (informasi layanan kas keliling)
-   **Lelang** (informasi aset/jaminan yang dilelang)
-   **Informasi Umum**
    -   Laporan Keuangan Publikasi
    -   Laporan Tata Kelola
    -   Laporan Tahunan
    -   Laporan Tahunan Berkelanjutan
    -   Download Logo
-   **Karir** (informasi lowongan pekerjaan)
-   **Hubungi Kami** (form kontak dan informasi)
-   **Whistleblowing System**

### Backend/Admin Features

-   **Manajemen Pengurus** (CRUD Dewan Komisaris, Direksi, Pengawas Syariah)
-   **Manajemen Informasi Perusahaan** (update data perusahaan, struktur organisasi, kantor)
-   **Manajemen Produk & Layanan** (CRUD produk simpanan dan pembiayaan)
-   **Manajemen Kas Keliling** (update informasi kas keliling)
-   **Manajemen Berita/Informasi** (CRUD artikel dan berita)
-   **Manajemen Laporan** (upload laporan keuangan, tata kelola, tahunan)
-   **Manajemen Lelang** (CRUD informasi lelang)
-   **Setting Perusahaan** (alamat, email, logo, telepon, dll)
-   **Manajemen Hero Slider** (gambar slideshow homepage)

## 🛠 Teknologi yang Digunakan

-   **Framework**: Laravel 12
-   **CSS Framework**: Tailwind CSS 4.0
-   **Database**: MySQL
-   **Server**: Apache
-   **JavaScript**: Alpine.js, Swiper.js
-   **PHP**: 8.2+

## 📋 Persyaratan Sistem

-   PHP >= 8.2
-   Composer
-   Node.js & NPM
-   MySQL
-   Apache/Nginx

## 🚀 Instalasi

1. **Clone Repository**

    ```bash
    git clone <repository-url>
    cd cms_baru
    ```

2. **Install Dependencies**

    ```bash
    composer install
    npm install
    ```

3. **Environment Setup**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4. **Database Configuration**
   Edit file `.env` dan sesuaikan konfigurasi database:

    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=cms_db_bprs
    DB_USERNAME=root
    DB_PASSWORD=
    ```

5. **Database Migration & Seeding**

    ```bash
    php artisan migrate
    php artisan db:seed
    ```

6. **Build Assets**

    ```bash
    npm run build
    ```

7. **Storage Link**

    ```bash
    php artisan storage:link
    ```

8. **Run Development Server**
    ```bash
    php artisan serve
    ```

## 📁 Struktur Database

### Tabel Utama

-   `company_infos` - Informasi perusahaan
-   `board_members` - Data pengurus (komisaris, direksi, pengawas syariah)
-   `products` - Produk simpanan dan pembiayaan syariah
-   `offices` - Informasi kantor
-   `news` - Berita dan informasi
-   `auctions` - Data lelang
-   `reports` - Laporan-laporan perusahaan
-   `hero_slides` - Gambar slider homepage
-   `kas_keliling` - Informasi kas keliling

## 🎨 Fitur UI/UX

-   **Responsive Design** - Mobile-first approach
-   **Modern Animations** - Smooth transitions dan hover effects
-   **Interactive Elements** - Dropdown menus, modals, sliders
-   **Professional Color Scheme** - Emerald green sebagai warna utama
-   **Typography** - Font Inter untuk keterbacaan optimal
-   **Loading Optimization** - Lazy loading dan image optimization

## 🔒 Keamanan

-   **CSRF Protection** - Laravel built-in CSRF protection
-   **Input Validation** - Server-side validation untuk semua input
-   **SQL Injection Prevention** - Eloquent ORM protection
-   **XSS Protection** - Output escaping dan sanitization
-   **File Upload Security** - Validasi tipe dan ukuran file

## 📱 Responsive Breakpoints

-   **Mobile**: < 768px
-   **Tablet**: 768px - 1024px
-   **Desktop**: > 1024px

## 🚀 Performance Optimization

-   **Asset Minification** - CSS dan JS terkompresi
-   **Image Optimization** - WebP format support
-   **Caching Strategy** - Database dan view caching
-   **CDN Ready** - Asset delivery optimization

## 📊 SEO Features

-   **Meta Tags** - Dynamic title dan description
-   **Structured Data** - Schema.org markup
-   **Sitemap** - XML sitemap generation
-   **Clean URLs** - SEO-friendly URL structure

## 🔧 Kustomisasi

### Mengubah Warna Tema

Edit file `resources/css/app.css` dan sesuaikan variabel warna:

```css
:root {
    --primary-color: #10b981; /* Emerald 600 */
    --primary-hover: #059669; /* Emerald 700 */
}
```

### Menambah Halaman Baru

1. Buat route di `routes/web.php`
2. Buat controller jika diperlukan
3. Buat view di `resources/views/`
4. Update navigasi di `resources/views/layouts/app.blade.php`

## 📝 Konten Dinamis

Semua konten website bersifat dinamis dan dapat dikelola melalui:

-   Database seeding untuk data awal
-   Admin panel (akan dikembangkan)
-   API endpoints untuk integrasi

## 🤝 Kontribusi

1. Fork repository
2. Buat feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

## 📄 Lisensi

Project ini menggunakan lisensi MIT. Lihat file `LICENSE` untuk detail.

## 📞 Support

Untuk pertanyaan atau dukungan teknis, silakan hubungi:

-   Email: developer@bprsyariah.co.id
-   Phone: (021) 1234-5678

## 🔄 Changelog

### Version 1.0.0 (2024-12-27)

-   Initial release
-   Complete frontend structure
-   Database schema implementation
-   Basic CRUD operations
-   Responsive design implementation
-   SEO optimization

---

**Bank Perekonomian Rakyat Syariah** - Solusi Keuangan Syariah Terpercaya
