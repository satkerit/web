<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use Illuminate\Http\Request;

class AuctionController extends Controller
{
    public function index(Request $request)
    {
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
        $sortBy = $request->get('sort_by', 'auction_date');
        $sortOrder = $request->get('sort_order', 'asc');
        
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
        $featuredAuctions = Auction::published()
                                  ->featured()
                                  ->limit(5)
                                  ->get();

        // Get upcoming auctions
        $upcomingAuctions = Auction::published()
                                  ->upcoming()
                                  ->limit(5)
                                  ->get();

        $auctions = $query->paginate(12)->withQueryString();

        // Get filter options
        $assetTypes = Auction::published()
                            ->select('asset_type')
                            ->distinct()
                            ->pluck('asset_type')
                            ->mapWithKeys(function($type) {
                                return [$type => Auction::$assetTypes[$type] ?? $type];
                            });

        $cities = Auction::published()
                        ->whereNotNull('city')
                        ->select('city')
                        ->distinct()
                        ->orderBy('city')
                        ->pluck('city');

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

    public function expressInterest(Request $request, Auction $auction)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'message' => 'nullable|string|max:1000'
        ]);

        // Increment interest count
        $auction->incrementInterestCount();

        // Here you can save the interest to database or send email
        // For now, just return success message

        return back()->with('success', 'Terima kasih atas ketertarikan Anda. Tim kami akan segera menghubungi Anda.');
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