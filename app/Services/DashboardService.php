<?php

namespace App\Services;

use App\Models\RincianLayananPerKecamatan;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * Mengambil dan memformat data untuk ditampilkan di halaman depan (publik).
     * Data di-cache selama 1 jam untuk performa.
     *
     * @return array
     */
    public function getViewerData()
    {
        $data = \Illuminate\Support\Facades\Cache::remember('viewer_dashboard_data', 3600, function () {
            $raw = RincianLayananPerKecamatan::all();

            // --- 1. Top Cards ---
            $totalKecamatan = $raw->where('kategori', 'kecamatan')->sum('jumlah');
            $totalMpp = $raw->where('kategori', 'mpp')->sum('jumlah');
            $totalDinas = $raw->where('kategori', 'dinas')->sum('jumlah');
            $totalKeseluruhan = $totalKecamatan + $totalMpp + $totalDinas;

            // --- 2. Chart & Rekap Table Data ---
            $rekapData = [];
            $layananTypes = [];

            foreach ($raw as $item) {
                $jenis = $item->jenis_layanan;
                $kat = $item->kategori;
                $jumlah = $item->jumlah;

                if (! in_array($jenis, $layananTypes)) {
                    $layananTypes[] = $jenis;
                }

                if (! isset($rekapData[$jenis])) {
                    $rekapData[$jenis] = [
                        'kecamatan' => 0,
                        'mpp' => 0,
                        'dinas' => 0,
                        'total' => 0,
                    ];
                }

                $rekapData[$jenis][$kat] += $jumlah;
                $rekapData[$jenis]['total'] += $jumlah;
            }

            sort($layananTypes);
            ksort($rekapData);

            // Arrays for Chart.js
            $chartLabels = array_keys($rekapData);
            $chartData = array_column($rekapData, 'total');
            $chartColors = [
                '#3b82f6', '#ef4444', '#10b981', '#f59e0b', '#8b5cf6',
                '#84cc16', '#ec4899', '#06b6d4', '#f97316', '#64748b',
                '#d946ef', '#eab308', '#14b8a6', '#f43f5e', '#0ea5e9',
                '#a855f7', '#22c55e', '#737373', '#6366f1', '#b45309',
            ];

            // --- 3. Rincian Lengkap Data ---
            $rincianData = [];
            foreach ($raw as $item) {
                $loc = $item->nama_kecamatan;
                $jenis = $item->jenis_layanan;

                if (! isset($rincianData[$loc])) {
                    $rincianData[$loc] = [
                        'kategori' => $item->kategori,
                        'layanans' => [],
                        'total' => 0,
                    ];
                }

                $rincianData[$loc]['layanans'][$jenis] = $item->jumlah;
                $rincianData[$loc]['total'] += $item->jumlah;
            }

            // Sort rincianData alphabetically by location name, but put MPP and Dinas at the bottom
            uksort($rincianData, function ($a, $b) use ($rincianData) {
                $katA = $rincianData[$a]['kategori'];
                $katB = $rincianData[$b]['kategori'];

                $wA = $katA == 'kecamatan' ? 1 : ($katA == 'mpp' ? 2 : 3);
                $wB = $katB == 'kecamatan' ? 1 : ($katB == 'mpp' ? 2 : 3);

                if ($wA != $wB) {
                    return $wA <=> $wB;
                }

                return strcmp($a, $b);
            });

            // --- 4. Ranking Chart Data ---
            $rankingData = [];
            foreach ($rincianData as $loc => $data) {
                $rankingData[] = [
                    'nama' => $loc,
                    'kategori' => $data['kategori'],
                    'total' => $data['total'],
                ];
            }
            usort($rankingData, fn ($a, $b) => $b['total'] <=> $a['total']);

            $countKecamatan = $raw->where('kategori', 'kecamatan')->pluck('nama_kecamatan')->unique()->count();

            return compact(
                'countKecamatan', 'totalKecamatan', 'totalMpp', 'totalDinas', 'totalKeseluruhan',
                'rekapData', 'chartLabels', 'chartData', 'chartColors',
                'rincianData', 'layananTypes', 'rankingData'
            );
        });

        $data['tanggalData'] = cache('tanggal_data', date('Y-m-d'));

        return $data;
    }

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
     * Clear frontend cache
     */
    private function clearDashboardCache()
    {
        \Illuminate\Support\Facades\Cache::forget('viewer_dashboard_data');
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
        
        $this->clearDashboardCache();
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
            $this->clearDashboardCache();
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
        $this->clearDashboardCache();
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
        $this->clearDashboardCache();
        
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

        $this->clearDashboardCache();

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
        $this->clearDashboardCache();
    }
}
