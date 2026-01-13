<?php

namespace Database\Seeders;

use App\Models\CompanyInfo;
use Illuminate\Database\Seeder;

class CompanyInfoSeeder extends Seeder
{
    /**
     * Data diambil dari website resmi bprsbabel.id
     * Sumber: https://bprsbabel.id
     */
    public function run(): void
    {
        CompanyInfo::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'PT. Bank Perekonomian Rakyat Syariah Bangka Belitung',
                'tagline' => 'Bank Daerah Dambaan Masyarakat Negeri Serumpun Sebalai',
                'address' => 'TJ TOWER, Jl. Kampung Melayu, Bukit Merapin 33172, Pangkalpinang, Kepulauan Bangka Belitung',
                'phone' => '0717-9103567',
                'fax' => null,
                'whatsapp' => '0717-9103567',
                'email' => 'customercare@bprsbabel.id',
                'email_contact' => 'bprsbangkabelitung@gmail.com',
                'website' => 'https://bprsbabel.id',
                'description' => 'PT. Bank Perekonomian Rakyat Syariah Bangka Belitung (BPRS Babel) adalah bank syariah daerah yang melayani masyarakat Provinsi Kepulauan Bangka Belitung dengan prinsip syariah. Bank ini berkomitmen untuk menggerakkan pemberdayaan ekonomi rakyat dan menyebarluaskan nilai-nilai Islam dalam bidang ekonomi dan dunia usaha.',
                'footer_description' => 'Melayani dengan prinsip syariah untuk kesejahteraan masyarakat Negeri Serumpun Sebalai. Terpercaya, amanah, dan profesional.',
                'vision' => 'Terwujudnya Bank Daerah Dambaan Masyarakat Negeri Serumpun Sebalai yang Terpercaya, Sehat dan Menguntungkan.',
                'mission' => "1. Menggerakkan pemberdayaan ekonomi rakyat dalam rangka turut serta berperan menuju masyarakat Provinsi Kepulauan Bangka Belitung yang maju, mandiri dan sejahtera.\n2. Menyebarluaskan nilai-nilai Islam dalam bidang ekonomi dan dunia usaha.\n3. Meningkatkan kualitas pelayanan di seluruh kantor.\n4. Meningkatkan kuantitas dan kualitas sumber daya insani menuju tenaga kerja yang profesional.\n5. Meningkatkan kerja sama dan bersinergi dengan pihak lain.",
                'history' => 'PT. Bank Perekonomian Rakyat Syariah Bangka Belitung (BPRS Babel) merupakan bank syariah daerah yang beroperasi di wilayah Provinsi Kepulauan Bangka Belitung. Bank ini didirikan untuk melayani kebutuhan perbankan syariah masyarakat dengan berbagai produk simpanan syariah, deposito syariah, dan pembiayaan syariah. BPRS Babel terus berkembang dengan membuka kantor cabang dan kantor kas di berbagai wilayah Bangka Belitung untuk menjangkau lebih banyak masyarakat.',
                'established_year' => 2008,
                'stat_years_experience' => 17,
                'stat_branch_offices' => 7,
                'stat_total_assets' => '150 Miliar',
                'stat_cash_offices' => 5,
                'stat_mobile_cash_offices' => 12,
                'facebook' => 'https://facebook.com/bprsbabel',
                'instagram' => 'https://instagram.com/bprsbabel',
                'twitter' => null,
                'youtube' => 'https://youtube.com/@bprsbabel',
                'linkedin' => null,
                'tiktok' => null,
                'ojk_license' => 'Terdaftar dan diawasi oleh OJK',
                'ojk_tagline' => 'Terdaftar dan diawasi oleh Otoritas Jasa Keuangan',
                'lps_tagline' => 'Bank merupakan peserta penjaminan LPS',
                'lps_guarantee_amount' => 'Rp 2.000.000.000',
                'meta_description' => 'PT. Bank Perekonomian Rakyat Syariah Bangka Belitung - Bank syariah terpercaya di Kepulauan Bangka Belitung dengan produk simpanan syariah, deposito syariah, dan pembiayaan syariah.',
                'meta_keywords' => 'BPRS Babel, Bank Syariah Bangka Belitung, Simpanan Syariah, Pembiayaan Syariah, Deposito Syariah, Bank Bangka Belitung, Bank Syariah Babel',
                'operational_hours' => [
                    'Senin - Jumat' => '08:00 - 16:00 WIB',
                    'Sabtu' => 'Tutup',
                    'Minggu' => 'Tutup'
                ]
            ]
        );

        CompanyInfo::clearCache();
    }
}
