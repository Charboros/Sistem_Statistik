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
                RincianLayananPerKecamatan::create([
                    'nama_kecamatan' => $kecamatan,
                    'kategori' => 'kecamatan',
                    'jenis_layanan' => $layanan,
                    'jumlah' => rand(0, 60)
                ]);
            }
        }

        // MPP
        foreach ($layananTypes as $layanan) {
            RincianLayananPerKecamatan::create([
                'nama_kecamatan' => 'MPP',
                'kategori' => 'mpp',
                'jenis_layanan' => $layanan,
                'jumlah' => rand(0, 20)
            ]);
        }

        // Dinas Dukcapil
        foreach ($layananTypes as $layanan) {
            RincianLayananPerKecamatan::create([
                'nama_kecamatan' => 'Dinas Dukcapil',
                'kategori' => 'dinas',
                'jenis_layanan' => $layanan,
                'jumlah' => rand(10, 250)
            ]);
        }
    }
}
