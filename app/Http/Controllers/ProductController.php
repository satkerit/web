<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\News;
use App\Models\Auction;
use App\Services\CacheService;

class ProductController extends Controller
{
    public function simpananSyariah()
    {
        return view('frontend.pages.products.index', [
            'products' => CacheService::getProductsByType('simpanan_syariah'),
            'title' => 'Simpanan Syariah',
            'subtitle' => 'Produk simpanan dengan prinsip syariah',
        ]);
    }

    public function pembiayaanSyariah()
    {
        return view('frontend.pages.products.index', [
            'products' => CacheService::getProductsByType('pembiayaan_syariah'),
            'title' => 'Pembiayaan Syariah',
            'subtitle' => 'Produk pembiayaan dengan prinsip syariah',
        ]);
    }

    public function depositoSyariah()
    {
        return view('frontend.pages.products.index', [
            'products' => CacheService::getProductsByType('deposito_syariah'),
            'title' => 'Deposito Syariah',
            'subtitle' => 'Produk deposito dengan akad syariah',
        ]);
    }

    public function kasKeliling()
    {
        // Ambil jadwal 5 hari terdekat
        $today = now()->startOfDay();
        $endDate = now()->addDays(4)->endOfDay();
        
        $schedules = \App\Models\KasKelilingSchedule::active()
            ->whereBetween('schedule_date', [
                $today->toDateString(), 
                $endDate->toDateString()
            ])
            ->orderBy('schedule_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get()
            ->groupBy(function($schedule) {
                return $schedule->schedule_date->format('Y-m-d');
            });

        return view('frontend.pages.products.kas-keliling', [
            'schedulesByDate' => $schedules,
            'companyInfo' => CacheService::getCompanyInfo(),
        ]);
    }

    public function show(string $slug)
    {
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return view('frontend.pages.products.show', compact('product'));
    }
}
