<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Auction;
use App\Rules\MinimumImages;
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

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('auction_number', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by asset type
        if ($request->filled('asset_type')) {
            $query->where('asset_type', $request->asset_type);
        }

        // Filter by city
        if ($request->filled('city')) {
            $query->where('city', 'like', "%{$request->city}%");
        }

        // Sort
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

    public function store(Request $request)
    {
        $this->authorizeCreate('auctions.create');

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'auction_number' => 'required|string|unique:auctions,auction_number',
            'object_number' => 'nullable|string',
            'asset_type' => 'required|in:tanah,rumah,ruko,apartemen,gedung,pabrik,kendaraan,mesin,lainnya',
            'asset_category' => 'nullable|string|max:255',
            'asset_description' => 'nullable|string',
            'certificate_type' => 'nullable|in:SHM,SHGB,SHP,AJB,PPJB,Girik,BPKB,Lainnya',
            'certificate_number' => 'nullable|string|max:255',
            'certificate_date' => 'nullable|date',
            'certificate_issued_by' => 'nullable|string|max:255',
            'land_area' => 'nullable|numeric|min:0',
            'building_area' => 'nullable|numeric|min:0',
            'building_condition' => 'nullable|string|max:255',
            'floors' => 'nullable|integer|min:0',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'parking_spaces' => 'nullable|integer|min:0',
            'year_built' => 'nullable|integer|min:1900|max:' . date('Y'),
            'address' => 'required|string',
            'village' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:10',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'debtor_name' => 'nullable|string|max:255',
            'debtor_id_number' => 'nullable|string|max:20',
            'debtor_address' => 'nullable|string',
            'auction_type' => 'required|in:eksekusi_hak_tanggungan,eksekusi_fidusia,eksekusi_hipotik,non_eksekusi_wajib,non_eksekusi_sukarela',
            'auction_method' => 'nullable|string|max:255',
            'auction_date' => 'required|date|after:today',
            'auction_time' => 'nullable|date_format:H:i',
            'auction_location' => 'required|string|max:255',
            'auction_address' => 'nullable|string',
            'registration_start' => 'nullable|date',
            'registration_end' => 'nullable|date|after:registration_start',
            'registration_requirements' => 'nullable|string',
            'registration_procedure' => 'nullable|string',
            'limit_price' => 'required|numeric|min:0',
            'estimated_price' => 'nullable|numeric|min:0',
            'deposit_amount' => 'nullable|numeric|min:0',
            'deposit_percentage' => 'nullable|numeric|min:0|max:100',
            'increment_amount' => 'nullable|numeric|min:0',
            'bank_name' => 'nullable|string|max:255',
            'bank_branch' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:50',
            'account_holder' => 'nullable|string|max:255',
            'swift_code' => 'nullable|string|max:20',
            'creditor_name' => 'nullable|string|max:255',
            'creditor_address' => 'nullable|string',
            'legal_basis' => 'nullable|string|max:255',
            'court_decision' => 'nullable|string|max:255',
            'court_decision_date' => 'nullable|date',
            'debt_amount' => 'nullable|numeric|min:0',
            'encumbrance_details' => 'nullable|string',
            'viewing_start' => 'nullable|date',
            'viewing_end' => 'nullable|date|after:viewing_start',
            'viewing_schedule' => 'nullable|string',
            'viewing_contact' => 'nullable|string',
            'viewing_notes' => 'nullable|string',
            'terms_conditions' => 'nullable|string',
            'special_conditions' => 'nullable|string',
            'payment_terms' => 'nullable|string',
            'payment_deadline_days' => 'nullable|integer|min:1|max:365',
            'delivery_terms' => 'nullable|string',
            'organizer_name' => 'nullable|string|max:255',
            'organizer_type' => 'nullable|string|max:255',
            'organizer_address' => 'nullable|string',
            'organizer_phone' => 'nullable|string|max:20',
            'organizer_email' => 'nullable|email|max:255',
            'organizer_website' => 'nullable|url|max:255',
            'contact_person' => 'required|string|max:255',
            'contact_position' => 'nullable|string|max:255',
            'contact_phone' => 'required|string|max:20',
            'contact_email' => 'nullable|email|max:255',
            'contact_whatsapp' => 'nullable|string|max:20',
            'contact_office_hours' => 'nullable|string',
            'facilities' => 'nullable|string',
            'nearby_facilities' => 'nullable|string',
            'transportation_access' => 'nullable|string',
            'investment_potential' => 'nullable|string',
            'market_analysis' => 'nullable|string',
            'risk_factors' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'status' => 'required|in:draft,published,registration_open,registration_closed,auction_scheduled,auction_ongoing,auction_completed,sold,unsold,cancelled,postponed',
            'is_featured' => 'boolean',
            'featured_until' => 'nullable|date',
            'is_urgent' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'winning_bid' => 'nullable|numeric|min:0',
            'winner_name' => 'nullable|string|max:255',
            'winner_phone' => 'nullable|string|max:20',
            'sold_at' => 'nullable|date',
            'images' => ['required', 'array', new MinimumImages(3)],
            'images.*' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ], [
            'images.required' => 'Gambar aset wajib diupload.',
            'images.min' => 'Minimal 3 gambar aset diperlukan untuk lelang.',
            'images.*.required' => 'Setiap file gambar wajib diisi.',
            'images.*.image' => 'File harus berupa gambar.',
            'images.*.mimes' => 'Format gambar harus JPEG, PNG, JPG, atau WebP.',
            'images.*.max' => 'Ukuran gambar maksimal 5MB.',
        ]);

        // Handle image uploads
        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $filename = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('auctions', $filename, 'public');
                $images[] = $path;
            }
            $validated['images'] = $images;
        }

        // Set published_at if status is not draft
        if ($validated['status'] !== 'draft') {
            $validated['published_at'] = now();
        }

        $auction = Auction::create($validated);

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

    public function update(Request $request, Auction $auction)
    {
        $this->authorizeEdit('auctions.edit');

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'auction_number' => 'required|string|unique:auctions,auction_number,' . $auction->id,
            'object_number' => 'nullable|string',
            'asset_type' => 'required|in:tanah,rumah,ruko,apartemen,gedung,pabrik,kendaraan,mesin,lainnya',
            'asset_category' => 'nullable|string|max:255',
            'asset_description' => 'nullable|string',
            'certificate_type' => 'nullable|in:SHM,SHGB,SHP,AJB,PPJB,Girik,BPKB,Lainnya',
            'certificate_number' => 'nullable|string|max:255',
            'certificate_date' => 'nullable|date',
            'certificate_issued_by' => 'nullable|string|max:255',
            'land_area' => 'nullable|numeric|min:0',
            'building_area' => 'nullable|numeric|min:0',
            'building_condition' => 'nullable|string|max:255',
            'floors' => 'nullable|integer|min:0',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'parking_spaces' => 'nullable|integer|min:0',
            'year_built' => 'nullable|integer|min:1900|max:' . date('Y'),
            'address' => 'required|string',
            'village' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:10',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'debtor_name' => 'nullable|string|max:255',
            'debtor_id_number' => 'nullable|string|max:20',
            'debtor_address' => 'nullable|string',
            'auction_type' => 'required|in:eksekusi_hak_tanggungan,eksekusi_fidusia,eksekusi_hipotik,non_eksekusi_wajib,non_eksekusi_sukarela',
            'auction_method' => 'nullable|string|max:255',
            'auction_date' => 'required|date',
            'auction_time' => 'nullable|date_format:H:i',
            'auction_location' => 'required|string|max:255',
            'auction_address' => 'nullable|string',
            'registration_start' => 'nullable|date',
            'registration_end' => 'nullable|date|after:registration_start',
            'registration_requirements' => 'nullable|string',
            'registration_procedure' => 'nullable|string',
            'limit_price' => 'required|numeric|min:0',
            'estimated_price' => 'nullable|numeric|min:0',
            'deposit_amount' => 'nullable|numeric|min:0',
            'deposit_percentage' => 'nullable|numeric|min:0|max:100',
            'increment_amount' => 'nullable|numeric|min:0',
            'bank_name' => 'nullable|string|max:255',
            'bank_branch' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:50',
            'account_holder' => 'nullable|string|max:255',
            'swift_code' => 'nullable|string|max:20',
            'creditor_name' => 'nullable|string|max:255',
            'creditor_address' => 'nullable|string',
            'legal_basis' => 'nullable|string|max:255',
            'court_decision' => 'nullable|string|max:255',
            'court_decision_date' => 'nullable|date',
            'debt_amount' => 'nullable|numeric|min:0',
            'encumbrance_details' => 'nullable|string',
            'viewing_start' => 'nullable|date',
            'viewing_end' => 'nullable|date|after:viewing_start',
            'viewing_schedule' => 'nullable|string',
            'viewing_contact' => 'nullable|string',
            'viewing_notes' => 'nullable|string',
            'terms_conditions' => 'nullable|string',
            'special_conditions' => 'nullable|string',
            'payment_terms' => 'nullable|string',
            'payment_deadline_days' => 'nullable|integer|min:1|max:365',
            'delivery_terms' => 'nullable|string',
            'organizer_name' => 'nullable|string|max:255',
            'organizer_type' => 'nullable|string|max:255',
            'organizer_address' => 'nullable|string',
            'organizer_phone' => 'nullable|string|max:20',
            'organizer_email' => 'nullable|email|max:255',
            'organizer_website' => 'nullable|url|max:255',
            'contact_person' => 'required|string|max:255',
            'contact_position' => 'nullable|string|max:255',
            'contact_phone' => 'required|string|max:20',
            'contact_email' => 'nullable|email|max:255',
            'contact_whatsapp' => 'nullable|string|max:20',
            'contact_office_hours' => 'nullable|string',
            'facilities' => 'nullable|string',
            'nearby_facilities' => 'nullable|string',
            'transportation_access' => 'nullable|string',
            'investment_potential' => 'nullable|string',
            'market_analysis' => 'nullable|string',
            'risk_factors' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'status' => 'required|in:draft,published,registration_open,registration_closed,auction_scheduled,auction_ongoing,auction_completed,sold,unsold,cancelled,postponed',
            'is_featured' => 'boolean',
            'featured_until' => 'nullable|date',
            'is_urgent' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'winning_bid' => 'nullable|numeric|min:0',
            'winner_name' => 'nullable|string|max:255',
            'winner_phone' => 'nullable|string|max:20',
            'sold_at' => 'nullable|date',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        // Handle image uploads
        if ($request->hasFile('images')) {
            // Delete old images
            if ($auction->images) {
                foreach ($auction->images as $oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }

            $images = [];
            foreach ($request->file('images') as $image) {
                $filename = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('auctions', $filename, 'public');
                $images[] = $path;
            }
            $validated['images'] = $images;
        }

        // Set published_at if status changed from draft to published
        if ($auction->status === 'draft' && $validated['status'] !== 'draft' && !$auction->published_at) {
            $validated['published_at'] = now();
        }

        $auction->update($validated);

        return redirect()->route('admin.auctions.index')
                        ->with('success', 'Lelang berhasil diperbarui.');
    }

    public function destroy(Auction $auction)
    {
        $this->authorizeDelete('auctions.delete');

        // Delete images
        if ($auction->images) {
            foreach ($auction->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $auction->delete();

        return redirect()->route('admin.auctions.index')
                        ->with('success', 'Lelang berhasil dihapus.');
    }

    public function bulkAction(Request $request)
    {
        $this->authorizeEdit('auctions.edit');

        $request->validate([
            'action' => 'required|in:delete,publish,unpublish,feature,unfeature',
            'selected_ids' => 'required|array|min:1',
            'selected_ids.*' => 'exists:auctions,id'
        ]);

        $auctions = Auction::whereIn('id', $request->selected_ids);

        switch ($request->action) {
            case 'delete':
                $this->authorizeDelete('auctions.delete');
                $count = $auctions->count();
                
                // Delete images for each auction
                foreach ($auctions->get() as $auction) {
                    if ($auction->images) {
                        foreach ($auction->images as $image) {
                            Storage::disk('public')->delete($image);
                        }
                    }
                }
                
                $auctions->delete();
                return back()->with('success', "{$count} lelang berhasil dihapus.");

            case 'publish':
                $auctions->update([
                    'status' => 'published',
                    'published_at' => now()
                ]);
                return back()->with('success', 'Lelang terpilih berhasil dipublikasi.');

            case 'unpublish':
                $auctions->update(['status' => 'draft']);
                return back()->with('success', 'Lelang terpilih berhasil di-unpublish.');

            case 'feature':
                $auctions->update([
                    'is_featured' => true,
                    'featured_until' => now()->addDays(30)
                ]);
                return back()->with('success', 'Lelang terpilih berhasil di-feature.');

            case 'unfeature':
                $auctions->update([
                    'is_featured' => false,
                    'featured_until' => null
                ]);
                return back()->with('success', 'Lelang terpilih berhasil di-unfeature.');
        }

        return back();
    }
}