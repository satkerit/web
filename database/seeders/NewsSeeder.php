<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\News;
use Carbon\Carbon;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        News::updateOrCreate(
            ['title' => 'BPR Syariah Raih Penghargaan Bank Terbaik 2024'],
            [
                'content' => '<p>Bank Perekonomian Rakyat Syariah dengan bangga mengumumkan pencapaian penghargaan sebagai "Bank Syariah Terbaik 2024" dalam kategori BPR Syariah dari Asosiasi Bank Syariah Indonesia.</p>
<p>Penghargaan ini diberikan berdasarkan penilaian komprehensif terhadap kinerja keuangan, inovasi produk, pelayanan nasabah, dan kontribusi terhadap pengembangan ekonomi syariah di Indonesia.</p>
<p>"Penghargaan ini merupakan hasil kerja keras seluruh tim dan kepercayaan nasabah yang telah mempercayakan kebutuhan keuangan syariah mereka kepada kami," ujar Direktur Utama BPR Syariah.</p>
<p>Sepanjang tahun 2024, BPR Syariah telah meluncurkan berbagai inovasi produk dan layanan, termasuk digitalisasi layanan perbankan dan ekspansi jaringan kas keliling untuk menjangkau masyarakat di daerah terpencil.</p>',
                'excerpt' => 'BPR Syariah meraih penghargaan Bank Syariah Terbaik 2024 berkat kinerja yang konsisten dan inovasi layanan yang berkelanjutan.',
                'is_published' => true,
                'published_at' => Carbon::now()->subDays(5),
                'author' => 'Tim Humas BPR Syariah'
            ]
        );

        News::updateOrCreate(
            ['title' => 'Peluncuran Produk Pembiayaan Mikro Syariah untuk UMKM'],
            [
                'content' => '<p>Dalam rangka mendukung pertumbuhan Usaha Mikro, Kecil, dan Menengah (UMKM), BPR Syariah meluncurkan produk pembiayaan mikro syariah dengan skema yang lebih fleksibel dan mudah diakses.</p>
<p>Produk ini dirancang khusus untuk memenuhi kebutuhan modal kerja UMKM dengan proses yang cepat dan persyaratan yang tidak memberatkan. Pembiayaan tersedia mulai dari Rp 5 juta hingga Rp 200 juta dengan jangka waktu hingga 3 tahun.</p>
<p>Keunggulan produk ini antara lain:</p>
<ul>
<li>Proses persetujuan maksimal 3 hari kerja</li>
<li>Margin yang kompetitif</li>
<li>Tanpa denda keterlambatan</li>
<li>Pendampingan usaha dari tim ahli</li>
</ul>
<p>Untuk informasi lebih lanjut, UMKM dapat mengunjungi kantor cabang terdekat atau menghubungi layanan customer service kami.</p>',
                'excerpt' => 'BPR Syariah meluncurkan produk pembiayaan mikro syariah untuk mendukung pertumbuhan UMKM dengan skema yang fleksibel.',
                'is_published' => true,
                'published_at' => Carbon::now()->subDays(10),
                'author' => 'Divisi Produk & Marketing'
            ]
        );

        News::updateOrCreate(
            ['title' => 'Ekspansi Layanan Kas Keliling ke 5 Kecamatan Baru'],
            [
                'content' => '<p>BPR Syariah terus berkomitmen untuk memberikan akses layanan perbankan syariah yang mudah dijangkau masyarakat. Sebagai wujud komitmen tersebut, kami memperluas jangkauan layanan kas keliling ke 5 kecamatan baru.</p>
<p>Kecamatan yang menjadi target ekspansi meliputi:</p>
<ul>
<li>Kecamatan Cibinong, Bogor</li>
<li>Kecamatan Ciputat, Tangerang Selatan</li>
<li>Kecamatan Bekasi Utara, Bekasi</li>
<li>Kecamatan Pancoran Mas, Depok</li>
<li>Kecamatan Kramat Jati, Jakarta Timur</li>
</ul>
<p>Layanan kas keliling ini menyediakan berbagai fasilitas perbankan seperti setoran tunai, penarikan tunai, pembayaran angsuran, dan pembukaan rekening baru. Jadwal kunjungan kas keliling dapat dilihat di website resmi atau menghubungi customer service.</p>
<p>"Dengan ekspansi ini, kami berharap dapat melayani lebih banyak masyarakat yang membutuhkan akses layanan perbankan syariah," kata Direktur Operasional BPR Syariah.</p>',
                'excerpt' => 'BPR Syariah memperluas jangkauan layanan kas keliling ke 5 kecamatan baru untuk memberikan akses yang lebih mudah bagi masyarakat.',
                'is_published' => true,
                'published_at' => Carbon::now()->subDays(15),
                'author' => 'Divisi Operasional'
            ]
        );

        News::updateOrCreate(
            ['title' => 'Sosialisasi Literasi Keuangan Syariah di Sekolah-Sekolah'],
            [
                'content' => '<p>Sebagai bagian dari program Corporate Social Responsibility (CSR), BPR Syariah mengadakan program sosialisasi literasi keuangan syariah di berbagai sekolah menengah atas di wilayah Jabodetabek.</p>
<p>Program ini bertujuan untuk meningkatkan pemahaman generasi muda tentang prinsip-prinsip keuangan syariah dan pentingnya mengelola keuangan dengan baik sejak dini.</p>
<p>Materi yang disampaikan meliputi:</p>
<ul>
<li>Pengenalan dasar ekonomi syariah</li>
<li>Perbedaan sistem keuangan konvensional dan syariah</li>
<li>Tips mengelola keuangan pribadi</li>
<li>Pentingnya menabung dan berinvestasi</li>
</ul>
<p>Hingga saat ini, program telah menjangkau lebih dari 2.000 siswa dari 15 sekolah. Antusiasme siswa sangat tinggi, terlihat dari banyaknya pertanyaan dan diskusi yang muncul selama sesi sosialisasi.</p>
<p>Program ini akan terus berlanjut dengan target menjangkau 50 sekolah pada tahun 2025.</p>',
                'excerpt' => 'BPR Syariah mengadakan program sosialisasi literasi keuangan syariah di sekolah-sekolah untuk meningkatkan pemahaman generasi muda.',
                'is_published' => true,
                'published_at' => Carbon::now()->subDays(20),
                'author' => 'Tim CSR'
            ]
        );

        News::updateOrCreate(
            ['title' => 'Kerjasama Strategis dengan Koperasi Syariah Nasional'],
            [
                'content' => '<p>BPR Syariah menandatangani nota kesepahaman (MoU) dengan Koperasi Syariah Nasional untuk mengembangkan ekosistem keuangan syariah yang lebih kuat dan terintegrasi.</p>
<p>Kerjasama ini mencakup beberapa area strategis:</p>
<ul>
<li>Pengembangan produk keuangan syariah bersama</li>
<li>Pelatihan dan pengembangan SDM</li>
<li>Sharing knowledge dan best practices</li>
<li>Program edukasi masyarakat tentang keuangan syariah</li>
</ul>

<p>Melalui kerjasama ini, diharapkan dapat tercipta sinergi yang saling menguntungkan dan memberikan manfaat yang lebih besar bagi masyarakat, khususnya dalam mengakses layanan keuangan syariah.</p>

<p>"Kerjasama ini sejalan dengan visi kami untuk menjadi bagian dari ekosistem keuangan syariah yang kuat di Indonesia," ungkap Direktur Utama BPR Syariah saat penandatanganan MoU.</p>',
                'excerpt' => 'BPR Syariah menjalin kerjasama strategis dengan Koperasi Syariah Nasional untuk memperkuat ekosistem keuangan syariah.',
                'is_published' => true,
                'published_at' => Carbon::now()->subDays(25),
                'author' => 'Divisi Kemitraan'
            ]
        );
    }
}
