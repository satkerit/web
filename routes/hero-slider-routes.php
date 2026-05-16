<?php

use App\Http\Controllers\HeroSliderController;
use Illuminate\Support\Facades\Route;

// Hero Slider Routes
Route::prefix('admin/hero-slider')->middleware(['auth', 'role', 'idle.timeout'])->group(function () {

    // Upload hero image
    Route::post('/upload', [HeroSliderController::class, 'upload'])
        ->name('hero-slider.upload');

    // Preview responsive images
    Route::post('/preview', [HeroSliderController::class, 'preview'])
        ->name('hero-slider.preview');

    // Get size recommendations
    Route::get('/size-recommendations', [HeroSliderController::class, 'getSizeRecommendations'])
        ->name('hero-slider.size-recommendations');
});

// Public routes untuk menampilkan hero slider
Route::get('/hero-slider-demo', function () {
    // Contoh data slides
    $slides = [
        [
            'images' => [
                'desktop_large' => ['webp' => 'hero-images/slide1_desktop_large.webp', 'jpg' => 'hero-images/slide1_desktop_large.jpg'],
                'desktop_medium' => ['webp' => 'hero-images/slide1_desktop_medium.webp', 'jpg' => 'hero-images/slide1_desktop_medium.jpg'],
                'desktop_small' => ['webp' => 'hero-images/slide1_desktop_small.webp', 'jpg' => 'hero-images/slide1_desktop_small.jpg'],
                'tablet' => ['webp' => 'hero-images/slide1_tablet.webp', 'jpg' => 'hero-images/slide1_tablet.jpg'],
                'mobile_large' => ['webp' => 'hero-images/slide1_mobile_large.webp', 'jpg' => 'hero-images/slide1_mobile_large.jpg'],
                'mobile_small' => ['webp' => 'hero-images/slide1_mobile_small.webp', 'jpg' => 'hero-images/slide1_mobile_small.jpg'],
            ],
            'title' => 'Selamat Datang di Website Kami',
            'subtitle' => 'Temukan produk dan layanan terbaik untuk kebutuhan Anda',
            'cta_text' => 'Jelajahi Sekarang',
            'cta_url' => '/products'
        ],
        [
            'images' => [
                'desktop_large' => ['webp' => 'hero-images/slide2_desktop_large.webp', 'jpg' => 'hero-images/slide2_desktop_large.jpg'],
                'desktop_medium' => ['webp' => 'hero-images/slide2_desktop_medium.webp', 'jpg' => 'hero-images/slide2_desktop_medium.jpg'],
                'desktop_small' => ['webp' => 'hero-images/slide2_desktop_small.webp', 'jpg' => 'hero-images/slide2_desktop_small.jpg'],
                'tablet' => ['webp' => 'hero-images/slide2_tablet.webp', 'jpg' => 'hero-images/slide2_tablet.jpg'],
                'mobile_large' => ['webp' => 'hero-images/slide2_mobile_large.webp', 'jpg' => 'hero-images/slide2_mobile_large.jpg'],
                'mobile_small' => ['webp' => 'hero-images/slide2_mobile_small.webp', 'jpg' => 'hero-images/slide2_mobile_small.jpg'],
            ],
            'title' => 'Kualitas Terjamin',
            'subtitle' => 'Produk berkualitas tinggi dengan harga terjangkau',
            'cta_text' => 'Lihat Katalog',
            'cta_url' => '/catalog'
        ]
    ];

    return view('hero-slider-demo', compact('slides'));
})->name('hero-slider.demo');
