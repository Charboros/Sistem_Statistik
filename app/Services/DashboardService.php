<?php

namespace App\Services;

use App\Models\RincianLayananPerKecamatan;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * Mengambil dan memformat data untuk ditampilkan di halaman dashboard admin.
     *
     * @return array
     */
    public function getDashboardData()
    {
        $raw = RincianLayananPerKecamatan::all();

        $locations = [];
        $layananTypes = [];

        foreach ($raw as $item) {
            $loc = $item->nama_kecamatan;
            $jenis = $item->jenis_layanan;

            if (! in_array($jenis, $layananTypes)) {
                $layananTypes[] = $jenis;
            }

            if (! isset($locations[$loc])) {
                $locations[$loc] = [
                    'kategori' => $item->kategori,
                    'layanans' => [],
                    'total' => 0,
                ];
            }

            $locations[$loc]['layanans'][$jenis] = [
                'id' => $item->id,
                'jumlah' => $item->jumlah,
            ];
            $locations[$loc]['total'] += $item->jumlah;
        }

        sort($layananTypes);

        $countKecamatan = $raw->where('kategori', 'kecamatan')->pluck('nama_kecamatan')->unique()->count();
        $totalKecamatan = $raw->where('kategori', 'kecamatan')->sum('jumlah');
        $totalMpp = $raw->where('kategori', 'mpp')->sum('jumlah');
        $totalDinas = $raw->where('kategori', 'dinas')->sum('jumlah');
        $totalKeseluruhan = $totalKecamatan + $totalMpp + $totalDinas;

        uksort($locations, function ($a, $b) use ($locations) {
            $katA = $locations[$a]['kategori'];
            $katB = $locations[$b]['kategori'];

            $wA = $katA == 'kecamatan' ? 1 : ($katA == 'mpp' ? 2 : 3);
            $wB = $katB == 'kecamatan' ? 1 : ($katB == 'mpp' ? 2 : 3);

            if ($wA != $wB) {
                return $wA <=> $wB;
            }

            return strcmp($a, $b);
        });

        $tanggalData = cache('tanggal_data', date('Y-m-d'));

        return compact(
            'countKecamatan', 
            'locations', 
            'layananTypes', 
            'totalKecamatan', 
            'totalMpp', 
            'totalDinas', 
            'totalKeseluruhan', 
            'tanggalData'
        );
    }

    /**
     * Menyimpan perubahan angka layanan
     *
     * @param string|null $tanggalData
     * @param array|null $inputData
     * @return void
     */
    public function updateData(?string $tanggalData, ?array $inputData)
    {
        if ($tanggalData) {
            cache(['tanggal_data' => $tanggalData], now()->addYears(1));
        }

        if ($inputData) {
            DB::transaction(function () use ($inputData) {
                foreach ($inputData as $id => $jumlah) {
                    RincianLayananPerKecamatan::where('id', $id)
                        ->update(['jumlah' => (int) $jumlah]);
                }
            });
        }
    }

    /**
     * Menambahkan jenis layanan baru
     *
     * @param string $newType
     * @return void
     */
    public function addLayanan(string $newType)
    {
        $existingLocations = RincianLayananPerKecamatan::where('jenis_layanan', $newType)
            ->pluck('nama_kecamatan')
            ->toArray();

        $locations = RincianLayananPerKecamatan::select('nama_kecamatan', 'kategori')
            ->whereNotIn('nama_kecamatan', $existingLocations)
            ->distinct()
            ->get();

        $insertData = [];
        $now = now();

        foreach ($locations as $loc) {
            $insertData[] = [
                'nama_kecamatan' => $loc->nama_kecamatan,
                'kategori' => $loc->kategori,
                'jenis_layanan' => $newType,
                'jumlah' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if (! empty($insertData)) {
            RincianLayananPerKecamatan::insert($insertData);
        }
    }

    /**
     * Menghapus jenis layanan
     *
     * @param string $type
     * @return void
     */
    public function deleteLayanan(string $type)
    {
        RincianLayananPerKecamatan::where('jenis_layanan', $type)->delete();
    }

    /**
     * Menambahkan lokasi baru
     *
     * @param string $newLokasi
     * @param string $kategori
     * @return bool false jika lokasi sudah ada, true jika sukses
     */
    public function addLokasi(string $newLokasi, string $kategori)
    {
        if (RincianLayananPerKecamatan::where('nama_kecamatan', $newLokasi)->exists()) {
            return false;
        }

        $layananTypes = RincianLayananPerKecamatan::select('jenis_layanan')->distinct()->pluck('jenis_layanan');

        $insertData = [];
        $now = now();

        foreach ($layananTypes as $jenis) {
            $insertData[] = [
                'nama_kecamatan' => $newLokasi,
                'kategori' => $kategori,
                'jenis_layanan' => $jenis,
                'jumlah' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if (empty($insertData)) {
            $insertData[] = [
                'nama_kecamatan' => $newLokasi,
                'kategori' => $kategori,
                'jenis_layanan' => 'Layanan Default',
                'jumlah' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        RincianLayananPerKecamatan::insert($insertData);
        return true;
    }

    /**
     * Memperbarui nama lokasi
     *
     * @param string $oldLokasi
     * @param string $newLokasi
     * @return bool false jika nama baru sudah digunakan, true jika sukses
     */
    public function updateLokasi(string $oldLokasi, string $newLokasi)
    {
        if ($oldLokasi !== $newLokasi && RincianLayananPerKecamatan::where('nama_kecamatan', $newLokasi)->exists()) {
            return false;
        }

        RincianLayananPerKecamatan::where('nama_kecamatan', $oldLokasi)
            ->update(['nama_kecamatan' => $newLokasi]);

        return true;
    }

    /**
     * Menghapus lokasi beserta datanya
     *
     * @param string $lokasi
     * @return void
     */
    public function deleteLokasi(string $lokasi)
    {
        RincianLayananPerKecamatan::where('nama_kecamatan', $lokasi)->delete();
    }
}
