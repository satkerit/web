<?php

namespace App\Http\Controllers;

use App\Services\CacheService;
use App\Models\SiteSetting;

class HomeController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::getSettings();
        $heroSlides = CacheService::getHeroSlidesDynamic();

        // Preload first hero image for better performance
        $firstHeroImage = $heroSlides->first()?->image;

        return view('frontend.home', [
            'companyInfo' => CacheService::getCompanyInfo(),
            'heroSlides' => $heroSlides,
            'heroSliderDelay' => $settings->hero_slider_delay ?? 5000,
            'firstHeroImage' => $firstHeroImage,
            'products' => CacheService::getHomeProducts(6),
            'news' => CacheService::getHomeNews(3),
            'auctions' => CacheService::getHomeAuctions(3),
            'whyChooseUs' => CacheService::getWhyChooseUs(),
            'whyChooseUsSettings' => CacheService::getWhyChooseUsSettings(),
        ]);
    }
}
