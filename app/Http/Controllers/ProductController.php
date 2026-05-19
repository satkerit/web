<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\News;
use App\Models\Auction;
use Illuminate\Support\Str;
use App\Services\CacheService;
use App\Services\Seo\SeoMeta;

class ProductController extends Controller
{
    public function simpananSyariah()
    {
        SeoMeta::setTitle('Simpanan Syariah')
            ->setDescription('Produk simpanan dengan prinsip syariah yang amanah dan menguntungkan.');

        return view('frontend.pages.products.index', [
            'products' => CacheService::getProductsByType('simpanan_syariah'),
            'title' => 'Simpanan Syariah',
            'subtitle' => 'Produk simpanan dengan prinsip syariah',
        ]);
    }

    public function pembiayaanSyariah()
    {
        SeoMeta::setTitle('Pembiayaan Syariah')
            ->setDescription('Solusi pembiayaan syariah untuk kebutuhan pribadi, usaha, dan investasi Anda.');

        return view('frontend.pages.products.index', [
            'products' => CacheService::getProductsByType('pembiayaan_syariah'),
            'title' => 'Pembiayaan Syariah',
            'subtitle' => 'Produk pembiayaan dengan prinsip syariah',
        ]);
    }

    public function depositoSyariah()
    {
        SeoMeta::setTitle('Deposito Syariah')
            ->setDescription('Investasi berjangka dengan bagi hasil kompetitif sesuai prinsip syariah.');

        return view('frontend.pages.products.index', [
            'products' => CacheService::getProductsByType('deposito_syariah'),
            'title' => 'Deposito Syariah',
            'subtitle' => 'Produk deposito dengan akad syariah',
        ]);
    }

    public function kasKeliling()
    {
        SeoMeta::setTitle('Jadwal Kas Keliling')
            ->setDescription('Cek jadwal dan lokasi layanan Kas Keliling BPRS Bangka Belitung terdekat.');

        return view('frontend.pages.products.kas-keliling', [
            'schedulesByDate' => CacheService::getKasKelilingSchedules(),
            'companyInfo' => CacheService::getCompanyInfo(),
        ]);
    }

    public function show(string $slug)
    {
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // SEO Implementation
        SeoMeta::setTitle($product->name)
            ->setDescription($product->short_description ?? Str::limit(strip_tags($product->description), 160))
            ->setImage($product->getImageUrl())
            ->setType('product')
            ->setModifiedTime($product->updated_at)
            ->addSchema([
                '@context' => 'https://schema.org',
                '@type' => 'FinancialProduct',
                'name' => $product->name,
                'description' => $product->short_description,
                'image' => $product->getImageUrl(),
                'provider' => [
                    '@type' => 'Organization',
                    'name' => config('app.name'),
                    'url' => url('/')
                ]
            ]);

        return view('frontend.pages.products.show', compact('product'));
    }
}
