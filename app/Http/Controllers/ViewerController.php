<?php

namespace App\Http\Controllers;

use App\Models\RincianLayananPerKecamatan;

class ViewerController extends Controller
{
    public function index()
    {
        $raw = RincianLayananPerKecamatan::all();

        // --- 1. Top Cards ---
        $totalKecamatan = $raw->where('kategori', 'kecamatan')->sum('jumlah');
        $totalMpp = $raw->where('kategori', 'mpp')->sum('jumlah');
        $totalDinas = $raw->where('kategori', 'dinas')->sum('jumlah');
        $totalKeseluruhan = $totalKecamatan + $totalMpp + $totalDinas;

        // --- 2. Chart & Rekap Table Data ---
        // Group by jenis_layanan
        $rekapData = [];
        $layananTypes = []; // Keep track of unique types

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
            '#3b82f6', '#10b981', '#f59e0b', '#ef4444',
            '#8b5cf6', '#06b6d4', '#f97316', '#64748b',
            '#ec4899', '#84cc16',
        ]; // Custom colors for chart

        // --- 3. Rincian Lengkap Data ---
        // Structure: $rincianData['Adiwerna'] = ['Kartu Keluarga' => 10, ..., 'total' => 194, 'kategori' => 'kecamatan']
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

            // Weights: kecamatan=1, mpp=2, dinas=3
            $wA = $katA == 'kecamatan' ? 1 : ($katA == 'mpp' ? 2 : 3);
            $wB = $katB == 'kecamatan' ? 1 : ($katB == 'mpp' ? 2 : 3);

            if ($wA != $wB) {
                return $wA <=> $wB;
            }

            return strcmp($a, $b);
        });

        // --- 4. Ranking Chart Data ---
        // Get totals per location for the horizontal bar chart, sort descending
        $rankingData = [];
        foreach ($rincianData as $loc => $data) {
            $rankingData[] = [
                'nama' => $loc,
                'kategori' => $data['kategori'],
                'total' => $data['total'],
            ];
        }
        usort($rankingData, fn ($a, $b) => $b['total'] <=> $a['total']); // Descending

        $countKecamatan = $raw->where('kategori', 'kecamatan')->pluck('nama_kecamatan')->unique()->count();
        $tanggalData = cache('tanggal_data', date('Y-m-d'));

        return view('welcome', compact(
            'countKecamatan', 'totalKecamatan', 'totalMpp', 'totalDinas', 'totalKeseluruhan',
            'rekapData', 'chartLabels', 'chartData', 'chartColors',
            'rincianData', 'layananTypes', 'rankingData', 'tanggalData'
        ));
    }
}
