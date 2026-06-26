<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CspReportController;

// CSP Violation Report (must be before other routes)
Route::post('/api/csp-report', [CspReportController::class, 'report'])->name('csp.report');

// Storage Serve Route (CRITICAL FOR FILES)
Route::get('/storage/{path}', [\App\Http\Controllers\StorageServeController::class, 'serve'])
    ->where('path', '.*')
    ->name('storage.serve');

// Secret Cache Clearing Route
Route::get('/clear-all-caches/{token}', function ($token) {
    $expectedToken = config('app.secret_cache_token');

    if ($token !== $expectedToken) {
        abort(403, 'Not allowed');
    }

    $results = [];

    try {
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        $results[] = '✅ cache:clear OK';
    } catch (\Exception $e) {
        $results[] = '❌ cache:clear error: ' . $e->getMessage();
    }

    try {
        if (class_exists('\Spatie\ResponseCache\Facades\ResponseCache')) {
            \Spatie\ResponseCache\Facades\ResponseCache::clear();
            $results[] = '✅ responsecache:clear OK';
        }
    } catch (\Exception $e) {
        $results[] = '❌ responsecache:clear error: ' . $e->getMessage();
    }

    try {
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        $results[] = '✅ view:clear OK';
    } catch (\Exception $e) {
        $results[] = '❌ view:clear error: ' . $e->getMessage();
    }

    try {
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        $results[] = '✅ config:clear OK';
    } catch (\Exception $e) {
        $results[] = '❌ config:clear error: ' . $e->getMessage();
    }

    try {
        \Illuminate\Support\Facades\Artisan::call('route:clear');
        $results[] = '✅ route:clear OK';
    } catch (\Exception $e) {
        $results[] = '❌ route:clear error: ' . $e->getMessage();
    }

    try {
        // Clear report caches
        foreach (['keuangan_publikasi', 'tata_kelola', 'tahunan', 'tahunan_berkelanjutan'] as $type) {
            \Illuminate\Support\Facades\Cache::forget("report_years_{$type}");
        }
        \Illuminate\Support\Facades\Cache::flush();
        $results[] = '✅ All report caches cleared';
    } catch (\Exception $e) {
        $results[] = '❌ Report cache error: ' . $e->getMessage();
    }

    return response()->json([
        'status' => 'done',
        'results' => $results
    ]);
})->middleware('throttle:5,1');



// Home Route
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Authentication Routes (Using Breeze)
Route::get('/login', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])->name('logout')->middleware('auth');

// Admin Authentication Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [App\Http\Controllers\Auth\AdminLoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
    Route::post('/login', [App\Http\Controllers\Auth\AdminLoginController::class, 'login'])->middleware('guest');
    Route::post('/logout', [App\Http\Controllers\Auth\AdminLoginController::class, 'logout'])->name('logout')->middleware('auth');
});

// Public Routes
Route::middleware(['web', 'throttle:120,1'])->group(function () {
    // Product Routes
    Route::prefix('produk')->name('products.')->group(function () {
        Route::get('/simpanan-syariah', [App\Http\Controllers\ProductController::class, 'simpananSyariah'])->name('simpanan-syariah');
        Route::get('/pembiayaan-syariah', [App\Http\Controllers\ProductController::class, 'pembiayaanSyariah'])->name('pembiayaan-syariah');
        Route::get('/deposito-syariah', [App\Http\Controllers\ProductController::class, 'depositoSyariah'])->name('deposito-syariah');
        Route::get('/kas-keliling', [App\Http\Controllers\ProductController::class, 'kasKeliling'])->name('kas-keliling');
        Route::get('/{product:slug}', [App\Http\Controllers\ProductController::class, 'show'])->name('show');
    });

    // Brochure Library
    Route::get('/brosur-pembiayaan-syariah', [App\Http\Controllers\BrochureController::class, 'index'])->name('brochures.index');
    Route::get('/brosur-pembiayaan-syariah/{brochure}/download', [App\Http\Controllers\BrochureController::class, 'download'])->name('brochures.download');
    Route::get('/brosur-pembiayaan-syariah/{brochure}/preview', [App\Http\Controllers\BrochureController::class, 'preview'])->name('brochures.preview');
    Route::get('/brosur-pembiayaan-syariah/produk/{product}/download', [App\Http\Controllers\BrochureController::class, 'downloadProduct'])->name('brochures.download-product');
    Route::get('/brosur-pembiayaan-syariah/produk/{product}/preview', [App\Http\Controllers\BrochureController::class, 'previewProduct'])->name('brochures.preview-product');

    // Auction Routes
    Route::prefix('lelang')->name('auctions.')->group(function () {
        Route::get('/', [App\Http\Controllers\AuctionController::class, 'index'])->name('index');
        Route::get('/{auction:slug}', [App\Http\Controllers\AuctionController::class, 'show'])->name('show');
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

    // About Pages
    Route::prefix('tentang-kami')->name('about.')->group(function () {
        Route::get('/perusahaan', [App\Http\Controllers\AboutController::class, 'company'])->name('company');
        Route::get('/dewan-komisaris', [App\Http\Controllers\AboutController::class, 'komisaris'])->name('komisaris');
        Route::get('/dewan-direksi', [App\Http\Controllers\AboutController::class, 'direksi'])->name('direksi');
        Route::get('/pengawas-syariah', [App\Http\Controllers\AboutController::class, 'pengawasSyariah'])->name('pengawas-syariah');
        Route::get('/struktur-organisasi', [App\Http\Controllers\AboutController::class, 'struktur'])->name('struktur');
        Route::get('/kantor-cabang', [App\Http\Controllers\AboutController::class, 'offices'])->name('offices');
        Route::get('/kantor-cabang/{office:slug}', [App\Http\Controllers\AboutController::class, 'officeShow'])->name('offices.show');
    });

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

// Storage API Routes (lighter middleware for API access)
Route::prefix('admin/storage')->name('admin.storage.')->middleware(['auth', 'idle.timeout'])->group(function () {
    Route::get('/api/browse', [App\Http\Controllers\Admin\StorageController::class, 'apiBrowse'])->name('api.browse');
    Route::post('/upload-editor-image', [App\Http\Controllers\Admin\StorageController::class, 'uploadEditorImage'])->name('upload-editor-image');
});

// Admin Routes with DDoS Protection
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role', 'idle.timeout', 'menu.permission'])->group(function () {
    // Dashboard
    Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // Profile Management
    Route::get('/profile', [App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [App\Http\Controllers\Admin\ProfileController::class, 'updatePassword'])->name('profile.password');

    // News Management
    Route::delete('news/image/{newsImage}', [App\Http\Controllers\Admin\NewsController::class, 'deleteImage'])->name('news.delete-image');
    Route::resource('news', App\Http\Controllers\Admin\NewsController::class)
        ->except(['show'])
        ->middleware(['optimize.upload']);  // Add upload optimization for news

    // Products Management
    Route::resource('products', App\Http\Controllers\Admin\ProductController::class);

    // Brochures Management
    Route::resource('brochures', App\Http\Controllers\Admin\BrochureController::class)
        ->only(['index', 'create', 'store', 'destroy']);

    // Auctions Management
    Route::post('auctions/bulk-action', [App\Http\Controllers\Admin\AuctionController::class, 'bulkAction'])->name('auctions.bulk-action');
    Route::resource('auctions', App\Http\Controllers\Admin\AuctionController::class);

    // Reports Management
    Route::get('reports/clear-caches', [App\Http\Controllers\Admin\ReportController::class, 'clearAllCaches'])
        ->name('admin.reports.clear-caches');
    Route::resource('reports', App\Http\Controllers\Admin\ReportController::class)
        ->middleware(['optimize.upload']);

    // Hero Slides Management
    Route::get('hero-slides/settings', [App\Http\Controllers\Admin\HeroSlideController::class, 'settings'])->name('hero-slides.settings');
    Route::put('hero-slides/settings', [App\Http\Controllers\Admin\HeroSlideController::class, 'updateSettings'])->name('hero-slides.settings.update');
    Route::post('hero-slides/reorder', [App\Http\Controllers\Admin\HeroSlideController::class, 'reorder'])->name('hero-slides.reorder');
    Route::resource('hero-slides', App\Http\Controllers\Admin\HeroSlideController::class)
        ->except(['show']);

    // Site Settings Management
    Route::get('site-settings', [App\Http\Controllers\Admin\SiteSettingController::class, 'index'])->name('site-settings.index');
    Route::put('site-settings', [App\Http\Controllers\Admin\SiteSettingController::class, 'update'])->name('site-settings.update');
    Route::put('site-settings/hero-slide-limit', [App\Http\Controllers\Admin\SiteSettingController::class, 'updateHeroSlideLimit'])->name('site-settings.hero-slide-limit.update');

    // Why Choose Us Management
    Route::get('why-choose-us/settings', [App\Http\Controllers\Admin\WhyChooseUsController::class, 'editSettings'])->name('why-choose-us.settings');
    Route::put('why-choose-us/settings', [App\Http\Controllers\Admin\WhyChooseUsController::class, 'updateSettings'])->name('why-choose-us.settings.update');
    Route::resource('why-choose-us', App\Http\Controllers\Admin\WhyChooseUsController::class)
        ->except(['show'])
        ->parameters(['why-choose-us' => 'whyChooseUs']);

    // Offices Management
    Route::resource('offices', App\Http\Controllers\Admin\OfficeController::class)
        ->except(['show']);

    // Kas Keliling Management
    Route::get('kas-keliling/export', [App\Http\Controllers\Admin\KasKelilingController::class, 'export'])->name('kas-keliling.export');
    Route::post('kas-keliling/bulk-delete', [App\Http\Controllers\Admin\KasKelilingController::class, 'bulkDelete'])->name('kas-keliling.bulk-delete');
    Route::post('kas-keliling/bulk-status', [App\Http\Controllers\Admin\KasKelilingController::class, 'bulkUpdateStatus'])->name('kas-keliling.bulk-status');
    Route::resource('kas-keliling', App\Http\Controllers\Admin\KasKelilingController::class)
        ->except(['show']);

    // Careers Management
    Route::resource('careers', App\Http\Controllers\Admin\CareerController::class);

    // Company Info Management
    Route::get('company-info/edit', [App\Http\Controllers\Admin\CompanyInfoController::class, 'edit'])->name('company-info.edit');
    Route::put('company-info', [App\Http\Controllers\Admin\CompanyInfoController::class, 'update'])->name('company-info.update');

    // Board Members Management
    Route::resource('board-members', App\Http\Controllers\Admin\BoardMemberController::class)
        ->except(['show']);

    // Settings Management
    Route::get('settings/maintenance', [App\Http\Controllers\Admin\SettingController::class, 'maintenance'])->name('settings.maintenance');
    Route::put('settings/maintenance', [App\Http\Controllers\Admin\SettingController::class, 'updateMaintenance'])->name('settings.maintenance.update');
    Route::get('settings/security', [App\Http\Controllers\Admin\SecuritySettingController::class, 'index'])->name('settings.security');
    Route::put('settings/security', [App\Http\Controllers\Admin\SecuritySettingController::class, 'update'])->name('settings.security.update');
    Route::get('settings/blocked-ips', [App\Http\Controllers\Admin\SecuritySettingController::class, 'blockedIps'])->name('settings.blocked-ips');
    Route::delete('settings/blocked-ips/{blockedIp}', [App\Http\Controllers\Admin\SecuritySettingController::class, 'unblockIp'])->name('settings.blocked-ips.unblock');
    Route::post('settings/blocked-ips/block', [App\Http\Controllers\Admin\SecuritySettingController::class, 'blockIp'])->name('settings.blocked-ips.block');
    Route::post('settings/blocked-ips/clear-expired', [App\Http\Controllers\Admin\SecuritySettingController::class, 'clearExpiredBlocks'])->name('settings.blocked-ips.clear-expired');
    Route::get('settings/email', [App\Http\Controllers\Admin\EmailSettingController::class, 'index'])->name('settings.email');
    Route::put('settings/email', [App\Http\Controllers\Admin\EmailSettingController::class, 'update'])->name('settings.email.update');
    Route::post('settings/email/test', [App\Http\Controllers\Admin\EmailSettingController::class, 'sendTest'])->name('settings.email.test');

    // Complaint Settings
    Route::get('settings/complaint', [App\Http\Controllers\Admin\ComplaintSettingController::class, 'index'])->name('settings.complaint');
    Route::put('settings/complaint', [App\Http\Controllers\Admin\ComplaintSettingController::class, 'update'])->name('settings.complaint.update');

    // Financing Config Management
    Route::resource('financing-config', App\Http\Controllers\Admin\FinancingConfigController::class)
        ->except(['show']);

    // Customer Complaints Management
    Route::get('customer-complaints/print', [App\Http\Controllers\Admin\CustomerComplaintController::class, 'print'])->name('customer-complaints.print');
    Route::get('customer-complaints/{customerComplaint}/print', [App\Http\Controllers\Admin\CustomerComplaintController::class, 'printSingle'])->name('customer-complaints.print-single');
    Route::resource('customer-complaints', App\Http\Controllers\Admin\CustomerComplaintController::class)
        ->only(['index', 'show', 'update', 'destroy']);

    // Whistleblowing Management
    Route::resource('complaints', App\Http\Controllers\Admin\ComplaintController::class)
        ->only(['index', 'show', 'update', 'destroy']);

    // Database Backup Management
    Route::get('database-backup', [App\Http\Controllers\Admin\DatabaseBackupController::class, 'index'])->name('database-backup.index');
    Route::post('database-backup/create', [App\Http\Controllers\Admin\DatabaseBackupController::class, 'create'])->name('database-backup.create');
    Route::get('database-backup/download/{filename}', [App\Http\Controllers\Admin\DatabaseBackupController::class, 'download'])->name('database-backup.download');
    Route::delete('database-backup/delete/{filename}', [App\Http\Controllers\Admin\DatabaseBackupController::class, 'delete'])->name('database-backup.delete');

    // Storage Management
    Route::get('storage', [App\Http\Controllers\Admin\StorageController::class, 'index'])->name('storage.index');
    Route::post('storage/upload', [App\Http\Controllers\Admin\StorageController::class, 'upload'])->name('storage.upload');
    Route::delete('storage/delete', [App\Http\Controllers\Admin\StorageController::class, 'delete'])->name('storage.delete');
    Route::put('storage/rename', [App\Http\Controllers\Admin\StorageController::class, 'rename'])->name('storage.rename');
    Route::post('storage/create-folder', [App\Http\Controllers\Admin\StorageController::class, 'createFolder'])->name('storage.create-folder');

    // Audit Trails Management
    Route::get('audit-trails', [App\Http\Controllers\Admin\AuditTrailController::class, 'index'])->name('audit-trails.index');
    Route::get('audit-trails/{auditTrail}', [App\Http\Controllers\Admin\AuditTrailController::class, 'show'])->name('audit-trails.show');
    Route::post('audit-trails/clear', [App\Http\Controllers\Admin\AuditTrailController::class, 'clear'])->name('audit-trails.clear');
    Route::get('audit-trails/export', [App\Http\Controllers\Admin\AuditTrailController::class, 'export'])->name('audit-trails.export');

    // Visitor Statistics Management
    Route::get('visitor-stats', [App\Http\Controllers\Admin\VisitorStatController::class, 'index'])->name('visitor-stats.index');
    Route::get('visitor-stats/export', [App\Http\Controllers\Admin\VisitorStatController::class, 'export'])->name('visitor-stats.export');

    // Security Monitoring
    Route::get('security-monitor', [App\Http\Controllers\Admin\SecurityMonitorController::class, 'index'])->name('security-monitor');
    Route::post('security-monitor/block', [App\Http\Controllers\Admin\SecurityMonitorController::class, 'blockIp'])->name('security-monitor.block');
    Route::post('security-monitor/unblock/{blockedIp}', [App\Http\Controllers\Admin\SecurityMonitorController::class, 'unblockIp'])->name('security-monitor.unblock');
    Route::post('security-monitor/cleanup', [App\Http\Controllers\Admin\SecurityMonitorController::class, 'cleanup'])->name('security-monitor.cleanup');
    Route::post('security-monitor/clear-expired', [App\Http\Controllers\Admin\SecurityMonitorController::class, 'clearExpiredBlocks'])->name('security-monitor.clear-expired');

    // Role Management
    Route::resource('roles', App\Http\Controllers\Admin\RoleController::class);

    // User Management
    Route::resource('users', App\Http\Controllers\Admin\UserController::class)
        ->except(['show']);

    // Menu Permissions Management
    Route::get('menu-permissions', [App\Http\Controllers\Admin\MenuPermissionController::class, 'index'])->name('menu-permissions.index');
    Route::post('menu-permissions/update', [App\Http\Controllers\Admin\MenuPermissionController::class, 'update'])->name('menu-permissions.update');
    
    // Composer Update
    Route::get('composer-update', [App\Http\Controllers\Admin\ComposerUpdateController::class, 'index'])->name('composer-update.index');
    Route::post('composer-update/run', [App\Http\Controllers\Admin\ComposerUpdateController::class, 'runUpdate'])->name('composer-update.run');
});

// API Routes
Route::prefix('api')->group(function () {
    Route::get('prayer-times', [App\Http\Controllers\PrayerTimeController::class, 'getPrayerTimes']);
});

// Include authentication routes
require __DIR__ . '/auth.php';

// Fallback route for 404
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
