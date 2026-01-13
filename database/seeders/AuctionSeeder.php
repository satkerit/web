<?php

namespace Database\Seeders;

use App\Models\Auction;
use Illuminate\Database\Seeder;

class AuctionSeeder extends Seeder
{
    public function run(): void
    {
        $auctions = [
            [
                'title' => 'Lelang Rumah Tinggal 2 Lantai di Perumahan Griya Asri',
                'object_number' => 'OBJ-2024-001',
                'description' => "Rumah tinggal 2 lantai dalam kondisi baik dan terawat. Lokasi strategis di perumahan elit dengan keamanan 24 jam.\n\nSpesifikasi:\n- 4 Kamar Tidur\n- 3 Kamar Mandi\n- Ruang Tamu & Keluarga\n- Dapur + Pantry\n- Carport 2 Mobil\n- Taman Depan & Belakang\n- Listrik 2200 Watt\n- Air PDAM\n- Internet Ready\n\nFasilitas Perumahan:\n- Keamanan 24 Jam\n- Taman Bermain Anak\n- Jogging Track\n- Dekat Sekolah & Mall",
                'asset_type' => 'rumah',
                'certificate_type' => 'SHM',
                'certificate_number' => '12345/Sukamaju',
                'land_area' => 150,
                'building_area' => 120,
                'debtor_name' => 'Tn. A***d S***o',
                'location' => 'Perumahan Griya Asri Blok C No. 15, Kel. Sukamaju, Kec. Cilandak, Jakarta Selatan',
                'starting_price' => 850000000,
                'estimated_price' => 1200000000,
                'auction_date' => now()->addDays(14)->setTime(10, 0),
                'registration_deadline' => now()->addDays(12)->setTime(16, 0),
                'auction_type' => 'eksekusi',
                'auction_location' => 'Kantor KPKNL Jakarta IV, Jl. Prapatan No. 10, Jakarta Pusat',
                'deposit_amount' => 170000000,
                'deposit_percentage' => 20,
                'bank_name' => 'Bank Syariah Indonesia',
                'bank_account' => '7123456789',
                'account_holder' => 'KPKNL Jakarta IV QQ Lelang',
                'terms_conditions' => "1. Peserta lelang wajib menyetorkan uang jaminan paling lambat 1 hari sebelum pelaksanaan lelang.\n2. Uang jaminan yang tidak menang akan dikembalikan paling lambat 3 hari kerja setelah lelang.\n3. Pemenang lelang wajib melunasi sisa pembayaran dalam waktu 5 hari kerja.\n4. Biaya-biaya yang menjadi tanggungan pembeli: BPHTB, biaya balik nama, dan biaya lelang 2.5%.\n5. Objek lelang dijual dalam kondisi apa adanya (as is).\n6. Pengosongan objek lelang menjadi tanggung jawab pemenang lelang.",
                'viewing_schedule' => "Jadwal Open House:\n- Sabtu, " . now()->addDays(7)->format('d F Y') . " pukul 09:00 - 12:00 WIB\n- Minggu, " . now()->addDays(8)->format('d F Y') . " pukul 09:00 - 12:00 WIB\n\nHubungi contact person untuk konfirmasi kehadiran.",
                'kpknl_office' => 'KPKNL Jakarta IV',
                'status' => 'upcoming',
                'contact_person' => 'Bapak Ahmad',
                'contact_phone' => '081234567890',
            ],
            [
                'title' => 'Lelang Ruko 3 Lantai di Kawasan Bisnis Sudirman',
                'object_number' => 'OBJ-2024-002',
                'description' => "Ruko strategis 3 lantai di kawasan bisnis premium Sudirman. Cocok untuk kantor, showroom, atau usaha retail.\n\nSpesifikasi:\n- Lantai 1: Area komersial/showroom\n- Lantai 2: Ruang kantor\n- Lantai 3: Ruang meeting & pantry\n- Toilet di setiap lantai\n- Listrik 5500 Watt\n- Air Tanah + PDAM\n- Parkir depan 2 mobil\n\nKeunggulan Lokasi:\n- Jalan utama 2 arah\n- Dekat halte TransJakarta\n- Akses tol dalam kota\n- Dikelilingi perkantoran",
                'asset_type' => 'ruko',
                'certificate_type' => 'SHGB',
                'certificate_number' => '567/Sudirman',
                'land_area' => 75,
                'building_area' => 225,
                'debtor_name' => 'PT. M***a J***a',
                'location' => 'Jl. Jend. Sudirman Kav. 52-53, Senayan, Kebayoran Baru, Jakarta Selatan',
                'starting_price' => 3500000000,
                'estimated_price' => 5000000000,
                'auction_date' => now()->addDays(21)->setTime(13, 0),
                'registration_deadline' => now()->addDays(19)->setTime(16, 0),
                'auction_type' => 'eksekusi',
                'auction_location' => 'Online melalui lelang.go.id',
                'deposit_amount' => 700000000,
                'deposit_percentage' => 20,
                'bank_name' => 'Bank Syariah Indonesia',
                'bank_account' => '7123456790',
                'account_holder' => 'KPKNL Jakarta IV QQ Lelang',
                'terms_conditions' => "1. Lelang dilaksanakan secara online melalui aplikasi lelang.go.id\n2. Peserta wajib registrasi di lelang.go.id minimal 3 hari sebelum lelang\n3. Uang jaminan ditransfer ke rekening virtual account yang diberikan sistem\n4. Pemenang wajib melunasi dalam 5 hari kerja\n5. Biaya pembeli: BPHTB, AJB, Balik Nama, dan Bea Lelang 2.5%",
                'viewing_schedule' => "Viewing dapat dilakukan setiap hari kerja (Senin-Jumat) pukul 10:00-15:00 WIB dengan perjanjian terlebih dahulu.",
                'kpknl_office' => 'KPKNL Jakarta IV',
                'status' => 'upcoming',
                'contact_person' => 'Ibu Sari',
                'contact_phone' => '081234567891',
            ],
            [
                'title' => 'Lelang Tanah Kavling Siap Bangun di BSD City',
                'object_number' => 'OBJ-2024-003',
                'description' => "Tanah kavling premium di kawasan BSD City, siap bangun dengan IMB tersedia.\n\nSpesifikasi:\n- Bentuk tanah persegi\n- Kontur tanah datar\n- Lebar depan 12 meter\n- Akses jalan 8 meter\n- Listrik & air tersedia\n- IMB untuk rumah tinggal\n\nLingkungan:\n- Perumahan established\n- Dekat AEON Mall BSD\n- Dekat sekolah internasional\n- Akses tol BSD",
                'asset_type' => 'tanah',
                'certificate_type' => 'SHM',
                'certificate_number' => '789/Serpong',
                'land_area' => 200,
                'building_area' => null,
                'debtor_name' => 'Ny. L***a W***i',
                'location' => 'Cluster Nusa Loka, BSD City, Serpong, Tangerang Selatan',
                'starting_price' => 1200000000,
                'estimated_price' => 1600000000,
                'auction_date' => now()->addDays(10)->setTime(10, 0),
                'registration_deadline' => now()->addDays(8)->setTime(16, 0),
                'auction_type' => 'eksekusi',
                'auction_location' => 'Kantor KPKNL Tangerang, Jl. Perintis Kemerdekaan No. 1',
                'deposit_amount' => 240000000,
                'deposit_percentage' => 20,
                'bank_name' => 'Bank Syariah Indonesia',
                'bank_account' => '7123456792',
                'account_holder' => 'KPKNL Tangerang QQ Lelang',
                'terms_conditions' => "1. Peserta lelang wajib menyetorkan uang jaminan sebelum lelang\n2. Objek dijual apa adanya (as is where is)\n3. Pemenang melunasi dalam 5 hari kerja\n4. Segala biaya perpajakan ditanggung pembeli",
                'viewing_schedule' => "Lokasi dapat dikunjungi setiap hari. Untuk pendampingan petugas, hubungi contact person.",
                'kpknl_office' => 'KPKNL Tangerang',
                'status' => 'upcoming',
                'contact_person' => 'Bapak Dedi',
                'contact_phone' => '081234567892',
            ],
        ];

        foreach ($auctions as $auction) {
            Auction::create($auction);
        }
    }
}
