<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Auction;
use App\Models\Product;
use App\Models\Career;
use App\Models\Office;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index()
    {
        $urls = [];

        // Static Pages
        $staticRoutes = [
            'dashboard' => 1.0, // Home
            'about.company' => 0.8,
            'about.struktur' => 0.8,
            'about.komisaris' => 0.8,
            'about.direksi' => 0.8,
            'about.pengawas-syariah' => 0.8,
            'contact' => 0.8,
            'news.index' => 0.8,
            'auctions.index' => 0.8,
            'products.simpanan-syariah' => 0.8,
            'products.pembiayaan-syariah' => 0.8,
            'products.deposito-syariah' => 0.8,
            'products.kas-keliling' => 0.8,
            'brochures.index' => 0.7,
            'careers.index' => 0.7,
            'pengaduan-nasabah' => 0.6,
            'whistleblowing' => 0.6,
            'financing-simulation' => 0.7,
        ];

        foreach ($staticRoutes as $route => $priority) {
            $urls[] = [
                'loc' => route($route),
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => $priority,
            ];
        }

        // Dynamic Pages: News
        $news = News::published()->latest()->get();
        foreach ($news as $item) {
            $urls[] = [
                'loc' => route('news.show', $item->slug),
                'lastmod' => $item->updated_at->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => 0.9,
            ];
        }

        // Dynamic Pages: Auctions
        $auctions = Auction::active()->latest()->get();
        foreach ($auctions as $item) {
            $urls[] = [
                'loc' => route('auctions.show', $item->slug),
                'lastmod' => $item->updated_at->toAtomString(),
                'changefreq' => 'daily',
                'priority' => 0.9,
            ];
        }

        // Dynamic Pages: Products
        $products = Product::active()->latest()->get();
        foreach ($products as $item) {
            $urls[] = [
                'loc' => route('products.show', $item->slug),
                'lastmod' => $item->updated_at->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => 0.9,
            ];
        }
        
        // Dynamic Pages: Careers
        $careers = Career::available()->latest()->get();
        foreach ($careers as $item) {
            $urls[] = [
                'loc' => route('careers.show', $item->slug),
                'lastmod' => $item->updated_at->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => 0.7,
            ];
        }

        // Dynamic Pages: Offices
        $offices = Office::active()->get(); // Assuming all offices are public
        foreach ($offices as $item) {
            $urls[] = [
                'loc' => route('about.offices.show', $item->slug),
                'lastmod' => $item->updated_at->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => 0.7,
            ];
        }

        return response()->view('sitemap', compact('urls'))
            ->header('Content-Type', 'text/xml');
    }
}
