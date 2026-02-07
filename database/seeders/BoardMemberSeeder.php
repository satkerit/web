<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BoardMember;

class BoardMemberSeeder extends Seeder
{
    /**
     * Data diambil dari website resmi bprsbabel.id
     * Sumber: https://bprsbabel.id/manajemen/
     */
    public function run(): void
    {
        // Dewan Komisaris
        BoardMember::updateOrCreate(
            ['name' => 'Drs. Sugianto, M.Si.'],
            [
                'position' => 'Komisaris',
                'type' => 'komisaris',
                'biography' => 'Drs. Sugianto, M.Si., merupakan pria kelahiran Lampur, 11 September 1964. Mengawali karir profesionalnya sebagai Guru selama periode 1989-2006. Selain itu pernah menjabat sebagai Kepala Dinas Pendidikan Kabupaten Bangka Tengah hingga menjadi Sekretaris Daerah Kabupaten Bangka Tengah. Sejak tahun 2018 hingga saat ini, beliau masih dipercaya menjabat sebagai Komisaris PT. BPRS Bangka Belitung.',
                'education' => [
                    'S2 - Magister Sains',
                    'S1 - Sarjana Pendidikan'
                ],
                'experience' => [
                    'Komisaris PT. BPRS Bangka Belitung (2018 - sekarang)',
                    'Sekretaris Daerah Kabupaten Bangka Tengah',
                    'Kepala Dinas Pendidikan Kabupaten Bangka Tengah',
                    'Guru (1989 - 2006)'
                ],
                'order_position' => 1
            ]
        );

        // Dewan Direksi
        BoardMember::updateOrCreate(
            ['name' => 'Chairul Ichwan, S.E., CIRBD'],
            [
                'position' => 'Direktur Utama',
                'type' => 'direksi',
                'biography' => 'Pria kelahiran Pangkalpinang, 10 Juni 1982 merupakan seorang putra terbaik daerah Provinsi Kepulauan Bangka Belitung yang mengenyam pendidikan formal di SD Muhammadiyah Pangkalpinang, SMP Muhammadiyah Pangkalpinang, SMA Muhammadiyah Pangkalpinang dan merupakan alumni Fakultas Ekonomi & Bisnis/Akuntansi Universitas Pendidikan Indonesia Bandung tahun 2007. Selama berkarir di dunia kerja pernah bergabung di PT Bank Muamalat Indonesia (2007-2016) dengan jabatan terakhir Head of Financing Kantor Cabang Utama Pangkalpinang. Selanjutnya pada 2017-2018 bergabung di PT. BPRS Bangka Belitung sebagai Kasubdiv Funding & Lending serta pernah menjabat sebagai Kasubdiv Remedial & AYDA. Pada periode 2018-2019 diberikan kepercayaan menjadi Direktur di PT. BPR Kapital Mandiri. Selanjutnya pada tahun 2020 hingga saat ini masih dipercaya menjadi Direktur Utama di PT. BPRS Bangka Belitung.',
                'education' => [
                    'S1 Akuntansi - Fakultas Ekonomi & Bisnis, Universitas Pendidikan Indonesia Bandung (2007)',
                    'SMA Muhammadiyah Pangkalpinang',
                    'SMP Muhammadiyah Pangkalpinang',
                    'SD Muhammadiyah Pangkalpinang'
                ],
                'experience' => [
                    'Direktur Utama PT. BPRS Bangka Belitung (2020 - sekarang)',
                    'Direktur PT. BPR Kapital Mandiri (2018 - 2019)',
                    'Kasubdiv Remedial & AYDA PT. BPRS Bangka Belitung',
                    'Kasubdiv Funding & Lending PT. BPRS Bangka Belitung (2017 - 2018)',
                    'Head of Financing KC Utama Pangkalpinang, PT Bank Muamalat Indonesia (2007 - 2016)'
                ],
                'order_position' => 1
            ]
        );

        BoardMember::updateOrCreate(
            ['name' => 'Hendra Dharma, S.E.'],
            [
                'position' => 'Direktur Marketing & Bisnis',
                'type' => 'direksi',
                'biography' => 'Pria kelahiran Pangkalpinang, 30 April 1980. Merupakan seorang putra daerah Provinsi Kepulauan Bangka Belitung. Mulai bergabung di PT. BPRS Bangka Belitung sejak tahun 2006 hingga saat ini beliau masih dipercaya menjabat sebagai Direktur di PT. BPRS Bangka Belitung.',
                'education' => [
                    'S1 Ekonomi'
                ],
                'experience' => [
                    'Direktur Marketing & Bisnis PT. BPRS Bangka Belitung (sekarang)',
                    'Bergabung di PT. BPRS Bangka Belitung sejak 2006'
                ],
                'order_position' => 2
            ]
        );

        // Dewan Pengawas Syariah
        BoardMember::updateOrCreate(
            ['name' => 'H. Syaipul Zohri'],
            [
                'position' => 'Ketua Dewan Pengawas Syariah',
                'type' => 'pengawas_syariah',
                'biography' => 'Dewan Pengawas Syariah PT. BPRS Bangka Belitung sejak 2009. Tercatat sebagai PNS Kementerian Agama dan aktif di berbagai organisasi Islam serta pernah menjabat sebagai Ketua BAZNAS Kabupaten Bangka. Beliau juga memimpin MUI Kabupaten Bangka.',
                'education' => [
                    'Pendidikan Agama Islam'
                ],
                'experience' => [
                    'Dewan Pengawas Syariah PT. BPRS Bangka Belitung (2009 - sekarang)',
                    'Ketua MUI Kabupaten Bangka',
                    'Ketua BAZNAS Kabupaten Bangka',
                    'PNS Kementerian Agama'
                ],
                'order_position' => 1
            ]
        );

        BoardMember::updateOrCreate(
            ['name' => 'H. Hasyim Syachroni'],
            [
                'position' => 'Anggota Dewan Pengawas Syariah',
                'type' => 'pengawas_syariah',
                'biography' => 'Dewan Pengawas Syariah PT. BPRS Bangka Belitung sejak 2009. Tercatat sebagai Ketua MUI dan BAZNAS Kabupaten Bangka Tengah.',
                'education' => [
                    'Pendidikan Agama Islam'
                ],
                'experience' => [
                    'Dewan Pengawas Syariah PT. BPRS Bangka Belitung (2009 - sekarang)',
                    'Ketua MUI Kabupaten Bangka Tengah',
                    'Ketua BAZNAS Kabupaten Bangka Tengah'
                ],
                'order_position' => 2
            ]
        );
    }
}
