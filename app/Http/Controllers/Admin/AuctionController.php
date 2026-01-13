<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Auction;
use App\Traits\AuthorizesAdminActions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AuctionController extends Controller
{
    use AuthorizesAdminActions;

    public function index(Request $request)
    {
        $this->authorizeView('auctions.view');

        $query = Auction::latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('object_number', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('asset_type')) {
            $query->where('asset_type', $request->asset_type);
        }

        $auctions = $query->paginate(15)->withQueryString();

        return view('admin.auctions.index', compact('auctions'));
    }

    public function create()
    {
        $this->authorizeCreate('auctions.create');

        return view('admin.auctions.form');
    }

    public function store(Request $request)
    {
        $this->authorizeCreate('auctions.create');
        $validated = $this->validateAuction($request);

        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('auctions', 'public');
            }
            $validated['images'] = $images;
        }

        if ($request->hasFile('documents')) {
            $documents = [];
            foreach ($request->file('documents') as $doc) {
                $documents[] = [
                    'path' => $doc->store('auctions/documents', 'public'),
                    'name' => $doc->getClientOriginalName(),
                ];
            }
            $validated['documents'] = $documents;
        }

        Auction::create($validated);

        return redirect()->route('admin.auctions.index')->with('success', 'Lelang berhasil ditambahkan.');
    }

    public function edit(Auction $auction)
    {
        $this->authorizeEdit('auctions.edit');

        return view('admin.auctions.form', compact('auction'));
    }

    public function update(Request $request, Auction $auction)
    {
        $this->authorizeEdit('auctions.edit');
        $validated = $this->validateAuction($request);

        if ($request->hasFile('images')) {
            // Delete old images
            if ($auction->images) {
                foreach ($auction->images as $image) {
                    Storage::disk('public')->delete($image);
                }
            }
            $images = [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('auctions', 'public');
            }
            $validated['images'] = $images;
        }

        if ($request->hasFile('documents')) {
            // Delete old documents
            if ($auction->documents) {
                foreach ($auction->documents as $doc) {
                    Storage::disk('public')->delete($doc['path']);
                }
            }
            $documents = [];
            foreach ($request->file('documents') as $doc) {
                $documents[] = [
                    'path' => $doc->store('auctions/documents', 'public'),
                    'name' => $doc->getClientOriginalName(),
                ];
            }
            $validated['documents'] = $documents;
        }

        $auction->update($validated);

        return redirect()->route('admin.auctions.index')->with('success', 'Lelang berhasil diperbarui.');
    }

    public function destroy(Auction $auction)
    {
        $this->authorizeDelete('auctions.delete');

        if ($auction->images) {
            foreach ($auction->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        if ($auction->documents) {
            foreach ($auction->documents as $doc) {
                Storage::disk('public')->delete($doc['path']);
            }
        }

        $auction->delete();

        return redirect()->route('admin.auctions.index')->with('success', 'Lelang berhasil dihapus.');
    }

    private function validateAuction(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'object_number' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'asset_type' => 'required|string',
            'certificate_type' => 'nullable|string',
            'certificate_number' => 'nullable|string|max:100',
            'land_area' => 'nullable|numeric|min:0',
            'building_area' => 'nullable|numeric|min:0',
            'debtor_name' => 'nullable|string|max:255',
            'location' => 'required|string',
            'starting_price' => 'required|numeric|min:0',
            'estimated_price' => 'nullable|numeric|min:0',
            'auction_date' => 'required|date',
            'registration_deadline' => 'nullable|date',
            'auction_type' => 'required|string',
            'auction_location' => 'nullable|string',
            'deposit_amount' => 'nullable|numeric|min:0',
            'deposit_percentage' => 'nullable|numeric|min:0|max:100',
            'bank_account' => 'nullable|string|max:100',
            'bank_name' => 'nullable|string|max:100',
            'account_holder' => 'nullable|string|max:255',
            'terms_conditions' => 'nullable|string',
            'viewing_schedule' => 'nullable|string',
            'kpknl_office' => 'nullable|string|max:255',
            'risalah_number' => 'nullable|string|max:100',
            'status' => 'required|in:upcoming,ongoing,closed,sold,cancelled',
            'winning_bid' => 'nullable|numeric|min:0',
            'winner_name' => 'nullable|string|max:255',
            'sold_at' => 'nullable|date',
            'contact_person' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:50',
            'meta_description' => 'nullable|string|max:255',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'documents.*' => 'nullable|file|mimes:pdf|max:10240',
        ]);
    }
}
