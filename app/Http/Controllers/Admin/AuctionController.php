<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auction\StoreAuctionRequest;
use App\Http\Requests\Admin\Auction\UpdateAuctionRequest;
use App\Models\Auction;
use App\Services\CacheService;
use App\Services\ImageCompressionService;
use App\Traits\AuthorizesAdminActions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AuctionController extends Controller
{
    use AuthorizesAdminActions;

    public function index(Request $request)
    {
        $this->authorizeView('auctions.view');

        $query = Auction::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('auction_number', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('asset_type')) {
            $query->where('asset_type', $request->asset_type);
        }

        if ($request->filled('city')) {
            $query->where('city', 'like', "%{$request->city}%");
        }

        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $auctions = $query->paginate(15)->withQueryString();

        return view('admin.auctions.index', compact('auctions'));
    }

    public function create()
    {
        $this->authorizeCreate('auctions.create');
        return view('admin.auctions.create');
    }

    public function store(StoreAuctionRequest $request)
    {
        $this->authorizeCreate('auctions.create');

        $validated = $request->validated();

        if ($request->hasFile('images')) {
            $validated['images'] = $this->uploadAuctionImages($request->file('images'));
        }

        if ($validated['status'] !== 'draft') {
            $validated['published_at'] = now();
        }

        Auction::create($validated);

        CacheService::clearAuctionCache();

        return redirect()->route('admin.auctions.index')
            ->with('success', 'Lelang berhasil dibuat.');
    }

    public function show(Auction $auction)
    {
        $this->authorizeView('auctions.view');
        return view('admin.auctions.show', compact('auction'));
    }

    public function edit(Auction $auction)
    {
        $this->authorizeEdit('auctions.edit');
        return view('admin.auctions.edit', compact('auction'));
    }

    public function update(UpdateAuctionRequest $request, Auction $auction)
    {
        $this->authorizeEdit('auctions.edit');

        $validated = $request->validated();

        $currentImages = $auction->images ?? [];
        $hasImageUpdates = false;

        if ($request->has('delete_images')) {
            $imagesToDelete = $request->delete_images;
            $currentImages = array_values(array_filter($currentImages, function ($img) use ($imagesToDelete) {
                if (in_array($img, $imagesToDelete)) {
                    Storage::disk('public')->delete($img);
                    return false;
                }
                return true;
            }));
            $hasImageUpdates = true;
        }

        if ($request->hasFile('images')) {
            $currentImages = array_merge($currentImages, $this->uploadAuctionImages($request->file('images')));
            $hasImageUpdates = true;
        }

        if ($hasImageUpdates) {
            $validated['images'] = $currentImages;
        }

        if ($auction->status === 'draft' && $validated['status'] !== 'draft' && !$auction->published_at) {
            $validated['published_at'] = now();
        }

        $auction->update($validated);

        CacheService::clearAuctionCache();

        return redirect()->route('admin.auctions.index')
            ->with('success', 'Lelang berhasil diperbarui.');
    }

    public function destroy(Auction $auction)
    {
        $this->authorizeDelete('auctions.delete');

        $this->deleteAuctionImages($auction->images);

        $auction->delete();

        CacheService::clearAuctionCache();

        return redirect()->route('admin.auctions.index')
            ->with('success', 'Lelang berhasil dihapus.');
    }

    public function bulkAction(Request $request)
    {
        $this->authorizeEdit('auctions.edit');

        $request->validate([
            'action' => 'required|in:delete,publish,unpublish,feature,unfeature',
            'selected_ids' => 'required|array|min:1',
            'selected_ids.*' => 'exists:auctions,id',
        ]);

        $auctions = Auction::whereIn('id', $request->selected_ids);

        switch ($request->action) {
            case 'delete':
                $this->authorizeDelete('auctions.delete');
                $count = $auctions->count();

                foreach ($auctions->get() as $auction) {
                    $this->deleteAuctionImages($auction->images);
                }

                $auctions->delete();
                CacheService::clearAuctionCache();
                return back()->with('success', "{$count} lelang berhasil dihapus.");

            case 'publish':
                $auctions->update(['status' => 'published', 'published_at' => now()]);
                CacheService::clearAuctionCache();
                return back()->with('success', 'Lelang terpilih berhasil dipublikasi.');

            case 'unpublish':
                $auctions->update(['status' => 'draft']);
                CacheService::clearAuctionCache();
                return back()->with('success', 'Lelang terpilih berhasil di-unpublish.');

            case 'feature':
                $auctions->update(['is_featured' => true, 'featured_until' => now()->addDays(30)]);
                return back()->with('success', 'Lelang terpilih berhasil di-feature.');

            case 'unfeature':
                $auctions->update(['is_featured' => false, 'featured_until' => null]);
                return back()->with('success', 'Lelang terpilih berhasil di-unfeature.');
        }

        return back();
    }

    private function uploadAuctionImages(array $files): array
    {
        $images = [];
        foreach ($files as $image) {
            $filename = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('auctions', $filename, 'public');

            try {
                ImageCompressionService::compressForWeb($path, 80, 1200);
            } catch (\Exception $e) {
                \Log::error('Failed to optimize auction image: ' . $e->getMessage());
            }

            $images[] = $path;
        }
        return $images;
    }

    private function deleteAuctionImages(?array $images): void
    {
        if (empty($images)) {
            return;
        }
        foreach ($images as $image) {
            Storage::disk('public')->delete($image);
        }
    }
}