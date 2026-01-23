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
        // Clear cache untuk memastikan data fresh (temporary untuk debugging)
        \Cache::forget('kas_keliling_schedules');
        
        // Ambil jadwal 5 hari terdekat dengan relasi kas keliling
        $today = now()->startOfDay();
        $endDate = now()->addDays(4)->endOfDay();
        
        // Log untuk debugging
        \Log::info('Kas Keliling Query', [
            'today' => $today->toDateString(),
            'endDate' => $endDate->toDateString(),
            'timezone' => config('app.timezone'),
        ]);
        
        // Query yang lebih robust untuk compatibility antara local dan production
        $schedules = \App\Models\KasKelilingSchedule::with('kasKeliling')
            ->where('is_active', true)
            ->whereBetween('schedule_date', [
                $today->toDateString(), 
                $endDate->toDateString()
            ])
            ->whereHas('kasKeliling', function($query) {
                $query->where('is_active', true);
            })
            ->orderBy('schedule_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();
        
        // Log hasil query
        \Log::info('Kas Keliling Results', [
            'count' => $schedules->count(),
            'schedules' => $schedules->pluck('schedule_date', 'id')->toArray(),
        ]);
        
        // Group by date
        $schedulesByDate = $schedules->groupBy(function($schedule) {
            // Ensure we get just the date part
            return \Carbon\Carbon::parse($schedule->schedule_date)->format('Y-m-d');
        });

        return view('frontend.pages.products.kas-keliling', [
            'schedulesByDate' => $schedulesByDate,
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
