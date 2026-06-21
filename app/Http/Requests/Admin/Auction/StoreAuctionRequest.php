<?php

namespace App\Http\Requests\Admin\Auction;

use App\Models\Auction;
use App\Rules\MinimumImages;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAuctionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
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
            'auction_date' => 'nullable|date',
            'auction_time' => 'nullable|date_format:H:i',
            'auction_location' => 'required|string|max:255',
            'auction_address' => 'nullable|string',
            'registration_start' => 'nullable|date',
            'registration_end' => 'nullable|date|after:registration_start',
            'registration_requirements' => 'nullable|string',
            'registration_procedure' => 'nullable|string',
            'limit_price' => 'nullable|numeric|min:0',
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
            'legal_basis' => 'nullable|string',
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
            'contact_person' => 'nullable|string|max:255',
            'contact_position' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email|max:255',
            'contact_whatsapp' => 'nullable|string|max:20',
            'contact_office_hours' => 'nullable|string',
            'contacts' => 'nullable|array',
            'contacts.*.name' => 'nullable|string|max:255',
            'contacts.*.phone' => 'nullable|string|max:20',
            'facilities' => 'nullable|string',
            'nearby_facilities' => 'nullable|string',
            'transportation_access' => 'nullable|string',
            'investment_potential' => 'nullable|string',
            'market_analysis' => 'nullable|string',
            'risk_factors' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'status' => ['required', Rule::in(array_keys(Auction::$statusLabels))],
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
        ];
    }

    public function messages(): array
    {
        return [
            'images.required' => 'Gambar aset wajib diupload.',
            'images.min' => 'Minimal 3 gambar aset diperlukan untuk lelang.',
            'images.*.required' => 'Setiap file gambar wajib diisi.',
            'images.*.image' => 'File harus berupa gambar.',
            'images.*.mimes' => 'Format gambar harus JPEG, PNG, JPG, atau WebP.',
            'images.*.max' => 'Ukuran gambar maksimal 5MB.',
        ];
    }
}