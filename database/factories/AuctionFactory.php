<?php

namespace Database\Factories;

use App\Models\Auction;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AuctionFactory extends Factory
{
    protected $model = Auction::class;

    public function definition(): array
    {
        $assetTypes = array_keys(Auction::$assetTypes);
        $certificateTypes = array_keys(Auction::$certificateTypes);
        $auctionTypes = array_keys(Auction::$auctionTypes);
        
        $assetType = $this->faker->randomElement($assetTypes);
        $limitPrice = $this->faker->numberBetween(100000000, 2000000000); // 100jt - 2M
        $estimatedPrice = $limitPrice * $this->faker->randomFloat(2, 1.1, 1.5); // 10-50% lebih tinggi
        
        $auctionDate = $this->faker->dateTimeBetween('+1 week', '+3 months');
        $registrationStart = (clone $auctionDate)->modify('-2 weeks');
        $registrationEnd = (clone $auctionDate)->modify('-3 days');
        $viewingStart = (clone $auctionDate)->modify('-1 week');
        $viewingEnd = (clone $auctionDate)->modify('-1 day');
        
        $cities = ['Pangkalpinang', 'Sungailiat', 'Toboali', 'Koba', 'Muntok', 'Belinyu'];
        $city = $this->faker->randomElement($cities);
        
        return [
            // Basic Information
            'title' => $this->generateTitle($assetType, $city),
            'description' => $this->generateDescription($assetType),
            'auction_number' => 'LEL/' . date('Y') . '/' . $this->faker->unique()->numberBetween(1000, 9999),
            'object_number' => 'OBJ-' . date('Y') . '-' . $this->faker->unique()->numberBetween(100, 999),
            
            // Asset Information
            'asset_type' => $assetType,
            'asset_category' => $this->generateAssetCategory($assetType),
            'asset_description' => $this->generateAssetDescription($assetType),
            
            // Certificate Information
            'certificate_type' => $assetType === 'kendaraan' ? 'BPKB' : $this->faker->randomElement($certificateTypes),
            'certificate_number' => $this->faker->numerify('####/####/####'),
            'certificate_date' => $this->faker->dateTimeBetween('-10 years', '-1 year'),
            'certificate_issued_by' => $assetType === 'kendaraan' ? 'Polda Babel' : 'BPN Kabupaten ' . $city,
            
            // Property Details (for real estate)
            'land_area' => in_array($assetType, ['tanah', 'rumah', 'ruko', 'gedung', 'pabrik']) 
                ? $this->faker->numberBetween(100, 1000) : null,
            'building_area' => in_array($assetType, ['rumah', 'ruko', 'gedung', 'pabrik']) 
                ? $this->faker->numberBetween(80, 500) : null,
            'building_condition' => in_array($assetType, ['rumah', 'ruko', 'gedung', 'pabrik']) 
                ? $this->faker->randomElement(['Baik', 'Cukup Baik', 'Perlu Renovasi']) : null,
            'floors' => in_array($assetType, ['rumah', 'ruko', 'gedung']) 
                ? $this->faker->numberBetween(1, 3) : null,
            'bedrooms' => $assetType === 'rumah' ? $this->faker->numberBetween(2, 5) : null,
            'bathrooms' => $assetType === 'rumah' ? $this->faker->numberBetween(1, 3) : null,
            'parking_spaces' => in_array($assetType, ['rumah', 'ruko', 'gedung']) 
                ? $this->faker->numberBetween(1, 4) : null,
            'year_built' => in_array($assetType, ['rumah', 'ruko', 'gedung', 'pabrik']) 
                ? $this->faker->numberBetween(1990, 2020) : null,
            
            // Location Details
            'address' => $this->generateAddress($city),
            'village' => $this->generateVillage($city),
            'district' => $this->generateDistrict($city),
            'city' => $city,
            'province' => 'Kepulauan Bangka Belitung',
            'postal_code' => $this->faker->numerify('#####'),
            'latitude' => $this->faker->latitude(-3.5, -1.5),
            'longitude' => $this->faker->longitude(105.5, 108.5),
            
            // Debtor Information
            'debtor_name' => $this->faker->name(),
            'debtor_id_number' => $this->faker->numerify('################'),
            'debtor_address' => $this->faker->address(),
            
            // Auction Information
            'auction_type' => $this->faker->randomElement($auctionTypes),
            'auction_method' => 'lelang_terbuka',
            'auction_date' => $auctionDate,
            'auction_time' => $this->faker->time('H:i:s', '14:00:00'),
            'auction_location' => 'Kantor BPRS Babel',
            'auction_address' => 'Jl. Jenderal Sudirman No. 1, Pangkalpinang',
            
            // Registration
            'registration_start' => $registrationStart,
            'registration_end' => $registrationEnd,
            'registration_requirements' => $this->generateRegistrationRequirements(),
            'registration_procedure' => $this->generateRegistrationProcedure(),
            
            // Pricing
            'limit_price' => $limitPrice,
            'estimated_price' => $estimatedPrice,
            'deposit_percentage' => $this->faker->randomElement([10, 15, 20, 25]),
            'increment_amount' => $this->faker->randomElement([1000000, 5000000, 10000000]),
            
            // Bank Information
            'bank_name' => 'Bank Mandiri',
            'bank_branch' => 'Pangkalpinang',
            'account_number' => $this->faker->numerify('##########'),
            'account_holder' => 'BPRS Babel',
            
            // Legal Information
            'creditor_name' => 'BPRS Babel',
            'creditor_address' => 'Jl. Jenderal Sudirman No. 1, Pangkalpinang',
            'legal_basis' => 'Undang-Undang No. 4 Tahun 1996 tentang Hak Tanggungan',
            'debt_amount' => $limitPrice * $this->faker->randomFloat(2, 0.8, 1.2),
            
            // Viewing Information
            'viewing_start' => $viewingStart,
            'viewing_end' => $viewingEnd,
            'viewing_schedule' => $this->generateViewingSchedule(),
            'viewing_contact' => $this->faker->name() . ' - ' . $this->faker->phoneNumber(),
            'viewing_notes' => 'Harap konfirmasi terlebih dahulu sebelum datang',
            
            // Terms & Conditions
            'terms_conditions' => $this->generateTermsConditions(),
            'payment_terms' => 'Pelunasan maksimal 30 hari setelah lelang',
            'payment_deadline_days' => 30,
            'delivery_terms' => 'Penyerahan objek setelah pelunasan dan penyelesaian administrasi',
            
            // Organizer Information
            'organizer_name' => 'BPRS Babel',
            'organizer_type' => 'Bank Pembiayaan Rakyat Syariah',
            'organizer_address' => 'Jl. Jenderal Sudirman No. 1, Pangkalpinang',
            'organizer_phone' => '0717-123456',
            'organizer_email' => 'info@bprsbabel.co.id',
            'organizer_website' => 'https://bprsbabel.co.id',
            
            // Contact Information
            'contact_person' => $this->faker->name(),
            'contact_position' => $this->faker->randomElement(['Manager Operasional', 'Staff Legal', 'Account Officer']),
            'contact_phone' => $this->faker->phoneNumber(),
            'contact_email' => $this->faker->email(),
            'contact_whatsapp' => $this->faker->phoneNumber(),
            'contact_office_hours' => 'Senin - Jumat: 08:00 - 16:00 WIB',
            
            // Additional Information
            'facilities' => $this->generateFacilities($assetType),
            'nearby_facilities' => $this->generateNearbyFacilities(),
            'transportation_access' => $this->generateTransportationAccess(),
            'investment_potential' => $this->generateInvestmentPotential($assetType),
            
            // SEO & Meta
            'meta_title' => null,
            'meta_description' => null,
            'meta_keywords' => null,
            
            // Status & Publishing
            'status' => $this->faker->randomElement(['published', 'registration_open', 'auction_scheduled']),
            'published_at' => now()->subDays($this->faker->numberBetween(1, 30)),
            'is_featured' => $this->faker->boolean(20), // 20% chance
            'is_urgent' => $this->faker->boolean(10), // 10% chance
            'sort_order' => $this->faker->numberBetween(0, 100),
            
            // Tracking
            'view_count' => $this->faker->numberBetween(0, 500),
            'interest_count' => $this->faker->numberBetween(0, 50),
            'download_count' => $this->faker->numberBetween(0, 100),
        ];
    }

    private function generateTitle(string $assetType, string $city): string
    {
        $titles = [
            'tanah' => [
                "Tanah Strategis di {$city}",
                "Kavling Siap Bangun {$city}",
                "Tanah Komersial {$city}",
            ],
            'rumah' => [
                "Rumah Tinggal {$city}",
                "Rumah Minimalis {$city}",
                "Rumah Siap Huni {$city}",
            ],
            'ruko' => [
                "Ruko Strategis {$city}",
                "Ruko 2 Lantai {$city}",
                "Ruko Pinggir Jalan {$city}",
            ],
            'kendaraan' => [
                "Kendaraan Bermotor",
                "Mobil Bekas",
                "Motor Bekas",
            ]
        ];

        $typeTitle = $titles[$assetType] ?? ["Aset {$city}"];
        return $this->faker->randomElement($typeTitle);
    }

    private function generateDescription(string $assetType): string
    {
        $descriptions = [
            'tanah' => 'Tanah dengan lokasi strategis, cocok untuk investasi atau pembangunan. Akses jalan baik dan dekat dengan fasilitas umum.',
            'rumah' => 'Rumah tinggal dengan kondisi terawat, lingkungan nyaman dan aman. Dilengkapi dengan fasilitas lengkap.',
            'ruko' => 'Ruko dengan lokasi strategis untuk usaha, akses mudah dan ramai dilalui. Cocok untuk berbagai jenis bisnis.',
            'kendaraan' => 'Kendaraan bermotor dengan kondisi terawat, surat-surat lengkap dan pajak hidup.'
        ];

        return $descriptions[$assetType] ?? 'Aset dengan kondisi baik dan lokasi strategis.';
    }

    private function generateAssetCategory(string $assetType): string
    {
        $categories = [
            'tanah' => ['Tanah Kavling', 'Tanah Komersial', 'Tanah Pertanian'],
            'rumah' => ['Rumah Type 36', 'Rumah Type 45', 'Rumah Type 60'],
            'ruko' => ['Ruko 2 Lantai', 'Ruko 3 Lantai', 'Ruko Corner'],
            'kendaraan' => ['Mobil', 'Motor', 'Truk']
        ];

        $typeCategories = $categories[$assetType] ?? ['Standar'];
        return $this->faker->randomElement($typeCategories);
    }

    private function generateAssetDescription(string $assetType): string
    {
        return "Deskripsi detail mengenai kondisi, spesifikasi, dan keunggulan aset ini. Informasi lengkap tersedia saat viewing.";
    }

    private function generateAddress(string $city): string
    {
        $streets = [
            'Jl. Soekarno Hatta', 'Jl. Jenderal Sudirman', 'Jl. Ahmad Yani',
            'Jl. Diponegoro', 'Jl. Gajah Mada', 'Jl. Veteran'
        ];
        
        return $this->faker->randomElement($streets) . ' No. ' . $this->faker->numberBetween(1, 200);
    }

    private function generateVillage(string $city): string
    {
        $villages = [
            'Pangkalpinang' => ['Gabek I', 'Gabek II', 'Bukit Intan', 'Tamansari'],
            'Sungailiat' => ['Sungailiat', 'Pemali', 'Bakam', 'Jelutung'],
        ];

        $cityVillages = $villages[$city] ?? ['Kelurahan Utama'];
        return $this->faker->randomElement($cityVillages);
    }

    private function generateDistrict(string $city): string
    {
        $districts = [
            'Pangkalpinang' => ['Pangkalpinang Kota', 'Pangkalpinang Barat', 'Pangkalpinang Timur'],
            'Sungailiat' => ['Sungailiat', 'Pemali', 'Bakam'],
        ];

        $cityDistricts = $districts[$city] ?? ['Kecamatan Utama'];
        return $this->faker->randomElement($cityDistricts);
    }

    private function generateRegistrationRequirements(): string
    {
        return "1. KTP/Identitas yang masih berlaku\n2. NPWP\n3. Surat keterangan domisili\n4. Bukti kemampuan finansial\n5. Uang jaminan sesuai ketentuan";
    }

    private function generateRegistrationProcedure(): string
    {
        return "1. Mengisi formulir pendaftaran\n2. Melengkapi dokumen persyaratan\n3. Menyetor uang jaminan\n4. Mendapat kartu peserta lelang\n5. Mengikuti penjelasan lelang";
    }

    private function generateViewingSchedule(): string
    {
        return "Senin - Jumat: 09:00 - 15:00 WIB\nSabtu: 09:00 - 12:00 WIB\nMinggu: Libur";
    }

    private function generateTermsConditions(): string
    {
        return "1. Peserta lelang wajib memenuhi persyaratan yang ditentukan\n2. Keputusan pemenang lelang bersifat final\n3. Pelunasan maksimal 30 hari setelah lelang\n4. Objek lelang dijual dalam kondisi apa adanya\n5. Segala biaya administrasi ditanggung pembeli";
    }

    private function generateFacilities(string $assetType): ?string
    {
        $facilities = [
            'rumah' => 'Listrik PLN, Air PDAM, Telepon, Internet',
            'ruko' => 'Listrik PLN 3 Phase, Air PDAM, Telepon, Internet, AC',
            'gedung' => 'Listrik PLN, Air PDAM, Telepon, Internet, Lift, AC Central',
        ];

        return $facilities[$assetType] ?? null;
    }

    private function generateNearbyFacilities(): string
    {
        return "Sekolah, Rumah Sakit, Pasar, Bank, Masjid, Pusat Perbelanjaan";
    }

    private function generateTransportationAccess(): string
    {
        return "Akses mudah dengan kendaraan pribadi dan transportasi umum. Dekat dengan jalan utama dan terminal.";
    }

    private function generateInvestmentPotential(string $assetType): string
    {
        $potentials = [
            'tanah' => 'Potensi kenaikan nilai tinggi karena lokasi strategis dan perkembangan daerah yang pesat.',
            'rumah' => 'Cocok untuk investasi properti atau tempat tinggal dengan nilai yang stabil.',
            'ruko' => 'Potensi bisnis tinggi dengan lokasi strategis dan akses mudah untuk pelanggan.',
        ];

        return $potentials[$assetType] ?? 'Aset dengan potensi investasi yang baik.';
    }

    // State methods
    public function sold(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'sold',
            'winning_bid' => $attributes['limit_price'] * $this->faker->randomFloat(2, 1.0, 1.3),
            'winner_name' => $this->faker->name(),
            'winner_phone' => $this->faker->phoneNumber(),
            'sold_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'total_bidders' => $this->faker->numberBetween(3, 15),
            'total_bids' => $this->faker->numberBetween(10, 50),
        ]);
    }

    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
            'featured_until' => $this->faker->dateTimeBetween('+1 week', '+1 month'),
        ]);
    }

    public function urgent(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_urgent' => true,
            'auction_date' => $this->faker->dateTimeBetween('+3 days', '+1 week'),
        ]);
    }
}