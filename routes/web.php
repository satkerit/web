<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;

// Public Routes with general rate limiting
Route::middleware(['throttle:120,1'])->group(function () {
    // Home
    Route::get('/', [HomeController::class, 'index'])->name('home');

    // About Routes
    Route::prefix('tentang-kami')->name('about.')->group(function () {
        Route::get('/perusahaan', [AboutController::class, 'company'])->name('company');
        Route::get('/dewan-komisaris', [AboutController::class, 'komisaris'])->name('komisaris');
        Route::get('/dewan-direksi', [AboutController::class, 'direksi'])->name('direksi');
        Route::get('/dewan-pengawas-syariah', [AboutController::class, 'pengawasSyariah'])->name('pengawas-syariah');
        Route::get('/struktur-organisasi', [AboutController::class, 'struktur'])->name('struktur');
        Route::get('/kantor', [AboutController::class, 'offices'])->name('offices');
        Route::get('/kantor/{office}', [AboutController::class, 'officeShow'])->name('offices.show');
    });

    // Product Routes
    Route::prefix('produk-layanan')->name('products.')->group(function () {
        Route::get('/simpanan-syariah', [ProductController::class, 'simpananSyariah'])->name('simpanan-syariah');
        Route::get('/pembiayaan-syariah', [ProductController::class, 'pembiayaanSyariah'])->name('pembiayaan-syariah');
        Route::get('/deposito-syariah', [ProductController::class, 'depositoSyariah'])->name('deposito-syariah');
        Route::get('/kas-keliling', [ProductController::class, 'kasKeliling'])->name('kas-keliling');
        Route::get('/detail/{slug}', [ProductController::class, 'show'])->name('show');
    });

    // Auction Routes
    Route::prefix('lelang')->name('auctions.')->group(function () {
        Route::get('/', [App\Http\Controllers\AuctionController::class, 'index'])->name('index');
        Route::get('/{slug}', [App\Http\Controllers\AuctionController::class, 'show'])->name('show');
    });

    // News Routes
    Route::prefix('berita')->name('news.')->group(function () {
        Route::get('/', [App\Http\Controllers\NewsController::class, 'index'])->name('index');
        Route::get('/{slug}', [App\Http\Controllers\NewsController::class, 'show'])->name('show');
    });

    // Report Routes
    Route::prefix('informasi-umum')->name('reports.')->group(function () {
        Route::get('/laporan-keuangan-publikasi', [ReportController::class, 'keuanganPublikasi'])->name('keuangan-publikasi');
        Route::get('/laporan-tata-kelola', [ReportController::class, 'tataKelola'])->name('tata-kelola');
        Route::get('/laporan-tahunan', [ReportController::class, 'tahunan'])->name('tahunan');
        Route::get('/laporan-tahunan-berkelanjutan', [ReportController::class, 'tahunanBerkelanjutan'])->name('tahunan-berkelanjutan');
        Route::get('/preview/{id}', [ReportController::class, 'preview'])->name('preview')->middleware('throttle:60,1');
        Route::get('/download/{id}', [ReportController::class, 'download'])->name('download')->middleware('throttle:30,1');
        Route::get('/hits/{id}', [ReportController::class, 'getHitCounts'])->name('hits');
    });

    // Careers
    Route::get('/karir', [App\Http\Controllers\CareerController::class, 'index'])->name('careers.index');
    Route::get('/karir/{career:slug}', [App\Http\Controllers\CareerController::class, 'show'])->name('careers.show');

    // Static Pages
    Route::view('/hubungi-kami', 'frontend.pages.contact')->name('contact');
    Route::view('/whistleblowing', 'frontend.pages.whistleblowing')->name('whistleblowing');
    Route::view('/pengaduan-nasabah', 'frontend.pages.pengaduan-nasabah')->name('pengaduan-nasabah');

    // Download Logo
    Route::get('/download-logo', [App\Http\Controllers\LogoDownloadController::class, 'index'])->name('download-logo');
    Route::get('/download-logo/{format}', [App\Http\Controllers\LogoDownloadController::class, 'download'])
        ->name('download-logo.download')
        ->middleware('throttle:10,1'); // Max 10 downloads per minute

    // Financing Simulation
    Route::view('/simulasi-pembiayaan', 'frontend.pages.financing-simulation')->name('financing-simulation');
});


// Admin Routes with DDoS Protection
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role', 'admin.ddos', 'menu.permission'])->group(function () {
    // Dashboard
    Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // News Management
    Route::resource('news', App\Http\Controllers\Admin\NewsControllerSecure::class);
    Route::delete('news/image/{image}', [App\Http\Controllers\Admin\NewsControllerSecure::class, 'destroyImage'])->name('news.delete-image');

    // Products Management
    Route::resource('products', App\Http\Controllers\Admin\ProductController::class);

    // Auctions Management
    Route::resource('auctions', App\Http\Controllers\Admin\AuctionController::class);

    // Reports Management
    Route::resource('reports', App\Http\Controllers\Admin\ReportController::class);

    // Hero Slides Management
    Route::post('hero-slides/reorder', [App\Http\Controllers\Admin\HeroSlideController::class, 'reorder'])->name('hero-slides.reorder');
    Route::resource('hero-slides', App\Http\Controllers\Admin\HeroSlideController::class);

    // Why Choose Us Management
    Route::resource('why-choose-us', App\Http\Controllers\Admin\WhyChooseUsController::class);

    // Offices Management
    Route::resource('offices', App\Http\Controllers\Admin\OfficeController::class);

    // Careers Management
    Route::resource('careers', App\Http\Controllers\Admin\CareerController::class);

    // Board Members Management
    Route::resource('board-members', App\Http\Controllers\Admin\BoardMemberController::class);

    // Company Info
    Route::get('company-info', [App\Http\Controllers\Admin\CompanyInfoController::class, 'edit'])->name('company-info.edit');
    Route::put('company-info', [App\Http\Controllers\Admin\CompanyInfoController::class, 'update'])->name('company-info.update');

    // Complaints Management (Whistleblowing)
    Route::resource('complaints', App\Http\Controllers\Admin\ComplaintController::class)->only(['index', 'show', 'update', 'destroy']);

    // Customer Complaints Management (Pengaduan Nasabah)
    Route::resource('customer-complaints', App\Http\Controllers\Admin\CustomerComplaintController::class)->only(['index', 'show', 'update', 'destroy']);

    // Users Management (Super Admin only)
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);

    // Settings
    Route::get('settings/maintenance', [App\Http\Controllers\Admin\SettingController::class, 'maintenance'])->name('settings.maintenance');
    Route::put('settings/maintenance', [App\Http\Controllers\Admin\SettingController::class, 'updateMaintenance'])->name('settings.maintenance.update');

    // Email Settings
    Route::get('settings/email', [App\Http\Controllers\Admin\EmailSettingController::class, 'index'])->name('settings.email');
    Route::put('settings/email', [App\Http\Controllers\Admin\EmailSettingController::class, 'update'])->name('settings.email.update');
    Route::post('settings/email/test', [App\Http\Controllers\Admin\EmailSettingController::class, 'sendTest'])->name('settings.email.test');

    // Audit Trails / Log Aktivitas
    Route::get('audit-trails', [App\Http\Controllers\Admin\AuditTrailController::class, 'index'])->name('audit-trails.index');
    Route::get('audit-trails/{auditTrail}', [App\Http\Controllers\Admin\AuditTrailController::class, 'show'])->name('audit-trails.show');
    Route::post('audit-trails/clear', [App\Http\Controllers\Admin\AuditTrailController::class, 'clear'])->name('audit-trails.clear');

    // Visitor Statistics
    Route::get('visitor-stats', [App\Http\Controllers\Admin\VisitorStatController::class, 'index'])->name('visitor-stats.index');

    // Storage / File Manager
    Route::prefix('storage')->name('storage.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\StorageController::class, 'index'])->name('index');
        Route::post('/upload', [App\Http\Controllers\Admin\StorageController::class, 'upload'])->name('upload');
        Route::post('/create-folder', [App\Http\Controllers\Admin\StorageController::class, 'createFolder'])->name('create-folder');
        Route::delete('/delete', [App\Http\Controllers\Admin\StorageController::class, 'delete'])->name('delete');
        Route::get('/download', [App\Http\Controllers\Admin\StorageController::class, 'download'])->name('download');
        Route::put('/rename', [App\Http\Controllers\Admin\StorageController::class, 'rename'])->name('rename');
        Route::get('/api/browse', [App\Http\Controllers\Admin\StorageController::class, 'apiBrowse'])->name('api.browse');
    });

    // Database Backup Management
    Route::prefix('database-backup')->name('database-backup.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\DatabaseBackupController::class, 'index'])->name('index');
        Route::post('/create', [App\Http\Controllers\Admin\DatabaseBackupController::class, 'create'])->name('create');
        Route::get('/download/{filename}', [App\Http\Controllers\Admin\DatabaseBackupController::class, 'download'])->name('download');
        Route::delete('/delete/{filename}', [App\Http\Controllers\Admin\DatabaseBackupController::class, 'delete'])->name('delete');
        Route::post('/restore/{filename}', [App\Http\Controllers\Admin\DatabaseBackupController::class, 'restore'])->name('restore');
        Route::post('/cleanup', [App\Http\Controllers\Admin\DatabaseBackupController::class, 'cleanup'])->name('cleanup');
    });

    // Financing Config Management
    Route::prefix('financing-config')->name('financing-config.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\FinancingConfigController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Admin\FinancingConfigController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Admin\FinancingConfigController::class, 'store'])->name('store');
        Route::get('/{financingConfig}/edit', [App\Http\Controllers\Admin\FinancingConfigController::class, 'edit'])->name('edit');
        Route::put('/{financingConfig}', [App\Http\Controllers\Admin\FinancingConfigController::class, 'update'])->name('update');
        Route::delete('/{financingConfig}', [App\Http\Controllers\Admin\FinancingConfigController::class, 'destroy'])->name('destroy');
    });

    // Menu Permissions (Super Admin only)
    Route::prefix('menu-permissions')->name('menu-permissions.')->middleware('role:super_admin')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\MenuPermissionController::class, 'index'])->name('index');
        Route::put('/', [App\Http\Controllers\Admin\MenuPermissionController::class, 'update'])->name('update');
    });

    // Role Management (Super Admin only)
    Route::resource('roles', App\Http\Controllers\Admin\RoleController::class)->middleware('role:super_admin');

    // User Profile (All authenticated users)
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('edit');
        Route::put('/', [App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('update');
        Route::put('/password', [App\Http\Controllers\Admin\ProfileController::class, 'updatePassword'])->name('password');
    });
});

// Authentication Routes with strict rate limiting
require __DIR__ . '/auth.php';

// Test route for news form (temporary)
Route::get('/test-news-form', function () {
    try {
        return view('admin.news.form-redesign');
    } catch (Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ], 500);
    }
});

// Session Management Routes
Route::middleware(['auth'])->group(function () {
    Route::post('/extend-session', [App\Http\Controllers\SessionController::class, 'extend'])->name('session.extend');
    Route::get('/session-status', [App\Http\Controllers\SessionController::class, 'status'])->name('session.status');
});
