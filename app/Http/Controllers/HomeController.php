<?php

namespace App\Http\Controllers;

use App\Services\CacheService;

class HomeController extends Controller
{
    public function index()
    {
        return view('frontend.home', [
            'companyInfo' => CacheService::getCompanyInfo(),
            'heroSlides' => CacheService::getHeroSlides(5),
            'products' => CacheService::getHomeProducts(6),
            'news' => CacheService::getHomeNews(3),
            'auctions' => CacheService::getHomeAuctions(3),
        ]);
    }
}
