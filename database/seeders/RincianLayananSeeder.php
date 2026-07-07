<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RincianLayananPerKecamatan;

class RincianLayananSeeder extends Seeder
{
    public function run(): void
    {
        $kecamatans = [
            'Adiwerna', 'Balapulang', 'Bojong', 'Bumijawa', 'Dukuhturi', 
            'Dukuhwaru', 'Jatinegara', 'Kedungbanteng', 'Kramat', 'Lebaksiu', 
            'Margasari', 'Pagerbarang', 'Pangkah', 'Slawi', 'Suradadi', 
            'Talang', 'Tarub', 'Warureja'
        ];

        $layananTypes = [
            'Kartu Keluarga',
            'Surat Pindah',
            'Perekaman KTP-EL',
            'Pencetakan KTP-EL',
            'KIA',
            'IKD',
            'Akta Kelahiran',
            'Akta Kematian',
            'Akta Perkawinan',
            'Akta Perceraian'
        ];

        foreach ($kecamatans as $kecamatan) {
            foreach ($layananTypes as $layanan) {
                RincianLayananPerKecamatan::updateOrCreate(
                    [
                        'nama_kecamatan' => $kecamatan,
                        'jenis_layanan' => $layanan,
                    ],
                    [
                        'kategori' => 'kecamatan',
                        'jumlah' => rand(0, 60)
                    ]
                );
            }
        }

        // MPP
        foreach ($layananTypes as $layanan) {
            RincianLayananPerKecamatan::updateOrCreate(
                [
                    'nama_kecamatan' => 'MPP',
                    'jenis_layanan' => $layanan,
                ],
                [
                    'kategori' => 'mpp',
                    'jumlah' => rand(0, 20)
                ]
            );
        }

        // Dinas Dukcapil
        foreach ($layananTypes as $layanan) {
            RincianLayananPerKecamatan::updateOrCreate(
                [
                    'nama_kecamatan' => 'Dinas Dukcapil',
                    'jenis_layanan' => $layanan,
                ],
                [
                    'kategori' => 'dinas',
                    'jumlah' => rand(10, 250)
                ]
            );
        }
    }
}
