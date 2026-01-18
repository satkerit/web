<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use Illuminate\Http\Request;

class AuctionController extends Controller
{
    public function index(Request $request)
    {
        $query = Auction::query()
            ->select(['id', 'title', 'slug', 'location', 'starting_price', 'auction_date', 'status', 'asset_type', 'images', 'land_area', 'building_area', 'winning_bid']);

        // Search filter
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('location', 'like', '%' . $request->search . '%');
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Asset type filter
        if ($request->filled('type')) {
            $query->where('asset_type', $request->type);
        }

        $auctions = $query->orderBy('auction_date', 'desc')->paginate(12)->withQueryString();

        return view('frontend.pages.auctions.index', compact('auctions'));
    }

    public function show(string $slug)
    {
        $auction = Auction::where('slug', $slug)->firstOrFail();
        return view('frontend.pages.auctions.show', compact('auction'));
    }
}
