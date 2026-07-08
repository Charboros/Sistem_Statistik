<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RincianLayananPerKecamatan;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $raw = RincianLayananPerKecamatan::all();
        
        // Structure: $data['Adiwerna'] = ['kategori' => 'kecamatan', 'layanans' => ['Kartu Keluarga' => 10, ...], 'total' => 194]
        $locations = [];
        $layananTypes = [];
        
        foreach ($raw as $item) {
            $loc = $item->nama_kecamatan;
            $jenis = $item->jenis_layanan;
            
            if (!in_array($jenis, $layananTypes)) {
                $layananTypes[] = $jenis;
            }
            
            if (!isset($locations[$loc])) {
                $locations[$loc] = [
                    'kategori' => $item->kategori,
                    'layanans' => [],
                    'total' => 0
                ];
            }
            
            $locations[$loc]['layanans'][$jenis] = [
                'id' => $item->id,
                'jumlah' => $item->jumlah
            ];
            $locations[$loc]['total'] += $item->jumlah;
        }

        sort($layananTypes);

        // Totals for top cards
        $countKecamatan = $raw->where('kategori', 'kecamatan')->pluck('nama_kecamatan')->unique()->count();
        $totalKecamatan = $raw->where('kategori', 'kecamatan')->sum('jumlah');
        $totalMpp = $raw->where('kategori', 'mpp')->sum('jumlah');
        $totalDinas = $raw->where('kategori', 'dinas')->sum('jumlah');
        $totalKeseluruhan = $totalKecamatan + $totalMpp + $totalDinas;

        // Sort locations alphabetically by name, but put MPP and Dinas at the bottom
        uksort($locations, function($a, $b) use ($locations) {
            $katA = $locations[$a]['kategori'];
            $katB = $locations[$b]['kategori'];
            
            // Weights: kecamatan=1, mpp=2, dinas=3
            $wA = $katA == 'kecamatan' ? 1 : ($katA == 'mpp' ? 2 : 3);
            $wB = $katB == 'kecamatan' ? 1 : ($katB == 'mpp' ? 2 : 3);
            
            if ($wA != $wB) return $wA <=> $wB;
            return strcmp($a, $b);
        });

        $tanggalData = cache('tanggal_data', date('Y-m-d'));

        return view('admin.index', compact('countKecamatan', 'locations', 'layananTypes', 'totalKecamatan', 'totalMpp', 'totalDinas', 'totalKeseluruhan', 'tanggalData'));
    }

    public function store(Request $request)
    {
        // Simpan tanggal ke cache
        if ($request->has('tanggal_data')) {
            cache(['tanggal_data' => $request->input('tanggal_data')], now()->addYears(1));
        }

        // The data comes as: data[id] = jumlah
        $inputData = $request->input('data');
        
        if ($inputData) {
            \Illuminate\Support\Facades\DB::transaction(function () use ($inputData) {
                foreach ($inputData as $id => $jumlah) {
                    RincianLayananPerKecamatan::where('id', $id)
                        ->update(['jumlah' => (int) $jumlah]);
                }
            });
        }
        
        return redirect()->back()->with('success', 'Perubahan berhasil disimpan.');
    }

    public function addLayanan(Request $request)
    {
        $request->validate([
            'new_layanan' => 'required|string|max:255'
        ]);

        $newType = trim($request->input('new_layanan'));

        // Get locations that already have this service type
        $existingLocations = RincianLayananPerKecamatan::where('jenis_layanan', $newType)
            ->pluck('nama_kecamatan')
            ->toArray();

        // Get all unique locations and their categories, excluding those that already have this service
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

        if (!empty($insertData)) {
            RincianLayananPerKecamatan::insert($insertData);
        }

        return redirect()->back()->with('success', 'Jenis layanan "'.$newType.'" berhasil ditambahkan.');
    }

    public function deleteLayanan(Request $request)
    {
        $request->validate([
            'layanan_to_delete' => 'required|string'
        ]);

        $type = $request->input('layanan_to_delete');
        
        RincianLayananPerKecamatan::where('jenis_layanan', $type)->delete();

        return redirect()->back()->with('success', 'Jenis layanan "'.$type.'" berhasil dihapus dari semua lokasi.');
    }

    public function addLokasi(Request $request)
    {
        $request->validate([
            'new_lokasi' => 'required|string|max:255',
            'kategori' => 'required|in:kecamatan,mpp,dinas'
        ]);

        $newLokasi = trim($request->input('new_lokasi'));
        $kategori = $request->input('kategori');

        // Verify it doesn't already exist
        if (RincianLayananPerKecamatan::where('nama_kecamatan', $newLokasi)->exists()) {
            return redirect()->back()->with('error', 'Lokasi dengan nama "'.$newLokasi.'" sudah ada!');
        }

        // Get all unique service types
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

        // Jika belum ada layanan sama sekali, buat satu layanan dummy agar lokasi bisa tampil
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

        return redirect()->back()->with('success', 'Lokasi "'.$newLokasi.'" berhasil ditambahkan.');
    }

    public function updateLokasi(Request $request)
    {
        $request->validate([
            'old_lokasi' => 'required|string',
            'new_lokasi' => 'required|string|max:255'
        ]);

        $oldLokasi = $request->input('old_lokasi');
        $newLokasi = trim($request->input('new_lokasi'));

        if ($oldLokasi !== $newLokasi && RincianLayananPerKecamatan::where('nama_kecamatan', $newLokasi)->exists()) {
            return redirect()->back()->with('error', 'Lokasi dengan nama "'.$newLokasi.'" sudah ada!');
        }

        RincianLayananPerKecamatan::where('nama_kecamatan', $oldLokasi)
            ->update(['nama_kecamatan' => $newLokasi]);

        return redirect()->back()->with('success', 'Nama lokasi berhasil diubah menjadi "'.$newLokasi.'".');
    }

    public function deleteLokasi(Request $request)
    {
        $request->validate([
            'lokasi_to_delete' => 'required|string'
        ]);

        $lokasi = $request->input('lokasi_to_delete');
        
        RincianLayananPerKecamatan::where('nama_kecamatan', $lokasi)->delete();

        return redirect()->back()->with('success', 'Lokasi "'.$lokasi.'" dan seluruh datanya berhasil dihapus.');
    }
}
