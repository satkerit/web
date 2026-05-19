<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use Illuminate\Http\Request;
use App\Services\Seo\SeoMeta;

class AuctionController extends Controller
{
    public function index(Request $request)
    {
        SeoMeta::setTitle('Lelang Agunan')
            ->setDescription('Temukan berbagai aset lelang berkualitas dari BPRS Bangka Belitung. Tanah, rumah, ruko, dan kendaraan dengan harga menarik.');

        $query = Auction::published();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('asset_category', 'like', "%{$search}%");
            });
        }

        // Filter by asset type
        if ($request->filled('asset_type')) {
            $query->where('asset_type', $request->asset_type);
        }

        // Filter by city
        if ($request->filled('city')) {
            $query->where('city', 'like', "%{$request->city}%");
        }

        // Filter by price range
        if ($request->filled('min_price')) {
            $query->where('limit_price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('limit_price', '<=', $request->max_price);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sort
        $validSortColumns = ['created_at', 'auction_date', 'limit_price', 'title', 'city'];
        $sortBy = $request->get('sort_by', 'auction_date');

        if (!in_array($sortBy, $validSortColumns) && $sortBy !== 'featured' && $sortBy !== 'price' && $sortBy !== 'date') {
            $sortBy = 'auction_date';
        }

        $sortOrder = strtolower($request->get('sort_order', 'asc')) === 'desc' ? 'desc' : 'asc';

        if ($sortBy === 'price') {
            $query->orderBy('limit_price', $sortOrder);
        } elseif ($sortBy === 'date') {
            $query->orderBy('auction_date', $sortOrder);
        } elseif ($sortBy === 'featured') {
            $query->orderBy('is_featured', 'desc')
                  ->orderBy('auction_date', 'asc');
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        // Get featured auctions for sidebar
        $featuredAuctions = \Illuminate\Support\Facades\Cache::remember('auctions_featured', 3600, function() {
            return Auction::published()
                          ->featured()
                          ->limit(5)
                          ->get();
        });

        // Get upcoming auctions
        $upcomingAuctions = \Illuminate\Support\Facades\Cache::remember('auctions_upcoming', 3600, function() {
            return Auction::published()
                          ->upcoming()
                          ->limit(5)
                          ->get();
        });

        $auctions = $query->paginate(12)->withQueryString();

        // Get filter options
        $assetTypes = \Illuminate\Support\Facades\Cache::remember('auctions_asset_types', 86400, function() {
            return Auction::published()
                        ->select('asset_type')
                        ->distinct()
                        ->pluck('asset_type')
                        ->mapWithKeys(function($type) {
                            return [$type => Auction::$assetTypes[$type] ?? $type];
                        });
        });

        $cities = \Illuminate\Support\Facades\Cache::remember('auctions_cities', 86400, function() {
            return Auction::published()
                        ->whereNotNull('city')
                        ->select('city')
                        ->distinct()
                        ->orderBy('city')
                        ->pluck('city');
        });

        return view('frontend.pages.auctions.index', compact(
            'auctions',
            'featuredAuctions',
            'upcomingAuctions',
            'assetTypes',
            'cities'
        ));
    }

    public function show(Auction $auction)
    {
        // Check if auction is published
        if ($auction->status === 'draft') {
            abort(404);
        }

        // Increment view count
        $auction->incrementViewCount();

        // SEO Implementation
        SeoMeta::setTitle($auction->title)
            ->setDescription($auction->meta_description ?? $auction->description)
            ->setKeywords($auction->meta_keywords)
            ->setImage($auction->main_image)
            ->setType('product')
            ->setPublishedTime($auction->published_at)
            ->setModifiedTime($auction->updated_at)
            ->addSchema([
                '@context' => 'https://schema.org',
                '@type' => 'Product',
                'name' => $auction->title,
                'description' => strip_tags($auction->description),
                'image' => $auction->main_image,
                'offers' => [
                    '@type' => 'Offer',
                    'url' => route('auctions.show', $auction->slug),
                    'priceCurrency' => 'IDR',
                    'price' => $auction->limit_price,
                    'availability' => $auction->status === 'sold' ? 'https://schema.org/SoldOut' : 'https://schema.org/InStock',
                    'validFrom' => $auction->auction_date ? $auction->auction_date->toIso8601String() : null
                ]
            ]);

        // Get related auctions (same asset type or city)
        $relatedAuctions = Auction::published()
                                 ->where('id', '!=', $auction->id)
                                 ->where(function($query) use ($auction) {
                                     $query->where('asset_type', $auction->asset_type)
                                           ->orWhere('city', $auction->city);
                                 })
                                 ->limit(6)
                                 ->get();

        // Get other auctions from same organizer
        $organizerAuctions = Auction::published()
                                   ->where('id', '!=', $auction->id)
                                   ->where('organizer_name', $auction->organizer_name)
                                   ->limit(4)
                                   ->get();

        return view('frontend.pages.auctions.show', compact(
            'auction',
            'relatedAuctions',
            'organizerAuctions'
        ));
    }

    public function downloadBrochure(Auction $auction)
    {
        // Check if auction is published
        if ($auction->status === 'draft') {
            abort(404);
        }

        // Increment download count
        $auction->incrementDownloadCount();

        // Generate PDF brochure (you can implement this later)
        // For now, redirect to show page
        return redirect()->route('auctions.show', $auction)
                        ->with('info', 'Fitur download brosur akan segera tersedia.');
    }


    public function search(Request $request)
    {
        $query = $request->get('q');

        if (empty($query)) {
            return redirect()->route('auctions.index');
        }

        $auctions = Auction::published()
                          ->where(function($q) use ($query) {
                              $q->where('title', 'like', "%{$query}%")
                                ->orWhere('address', 'like', "%{$query}%")
                                ->orWhere('city', 'like', "%{$query}%")
                                ->orWhere('asset_category', 'like', "%{$query}%")
                                ->orWhere('description', 'like', "%{$query}%");
                          })
                          ->orderBy('auction_date', 'asc')
                          ->paginate(12)
                          ->withQueryString();

        return view('frontend.pages.auctions.search', compact('auctions', 'query'));
    }

    public function calendar(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));

        $auctions = Auction::published()
                          ->whereYear('auction_date', $year)
                          ->whereMonth('auction_date', $month)
                          ->orderBy('auction_date', 'asc')
                          ->get()
                          ->groupBy(function($auction) {
                              return $auction->auction_date->format('Y-m-d');
                          });

        return view('frontend.pages.auctions.calendar', compact('auctions', 'year', 'month'));
    }

    public function byAssetType($assetType)
    {
        if (!array_key_exists($assetType, Auction::$assetTypes)) {
            abort(404);
        }

        $auctions = Auction::published()
                          ->where('asset_type', $assetType)
                          ->orderBy('auction_date', 'asc')
                          ->paginate(12);

        $assetTypeName = Auction::$assetTypes[$assetType];

        return view('frontend.pages.auctions.by-type', compact('auctions', 'assetType', 'assetTypeName'));
    }

    public function byCity($city)
    {
        $auctions = Auction::published()
                          ->where('city', 'like', "%{$city}%")
                          ->orderBy('auction_date', 'asc')
                          ->paginate(12);

        if ($auctions->isEmpty()) {
            abort(404);
        }

        return view('frontend.pages.auctions.by-city', compact('auctions', 'city'));
    }
}
