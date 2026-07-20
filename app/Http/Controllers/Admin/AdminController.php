<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index()
    {
        $data = $this->dashboardService->getDashboardData();

        return view('admin.index', $data);
    }

    public function store(Request $request)
    {
        $this->dashboardService->updateData(
            $request->input('tanggal_data'),
            $request->input('data')
        );

        return redirect()->back()->with('success', 'Perubahan berhasil disimpan.');
    }

    public function addLayanan(Request $request)
    {
        $request->validate([
            'new_layanan' => 'required|string|max:255',
        ]);

        $newType = trim($request->input('new_layanan'));
        $this->dashboardService->addLayanan($newType);

        return redirect()->back()->with('success', 'Jenis layanan "'.$newType.'" berhasil ditambahkan.');
    }

    public function deleteLayanan(Request $request)
    {
        $request->validate([
            'layanan_to_delete' => 'required|string',
        ]);

        $type = $request->input('layanan_to_delete');
        $this->dashboardService->deleteLayanan($type);

        return redirect()->back()->with('success', 'Jenis layanan "'.$type.'" berhasil dihapus dari semua lokasi.');
    }

    public function addLokasi(Request $request)
    {
        $request->validate([
            'new_lokasi' => 'required|string|max:255',
            'kategori' => 'required|in:kecamatan,mpp,dinas',
        ]);

        $newLokasi = trim($request->input('new_lokasi'));
        $kategori = $request->input('kategori');

        $success = $this->dashboardService->addLokasi($newLokasi, $kategori);

        if (!$success) {
            return redirect()->back()->with('error', 'Lokasi dengan nama "'.$newLokasi.'" sudah ada!');
        }

        return redirect()->back()->with('success', 'Lokasi "'.$newLokasi.'" berhasil ditambahkan.');
    }

    public function updateLokasi(Request $request)
    {
        $request->validate([
            'old_lokasi' => 'required|string',
            'new_lokasi' => 'required|string|max:255',
        ]);

        $oldLokasi = $request->input('old_lokasi');
        $newLokasi = trim($request->input('new_lokasi'));

        $success = $this->dashboardService->updateLokasi($oldLokasi, $newLokasi);

        if (!$success) {
            return redirect()->back()->with('error', 'Lokasi dengan nama "'.$newLokasi.'" sudah ada!');
        }

        return redirect()->back()->with('success', 'Nama lokasi berhasil diubah menjadi "'.$newLokasi.'".');
    }

    public function deleteLokasi(Request $request)
    {
        $request->validate([
            'lokasi_to_delete' => 'required|string',
        ]);

        $lokasi = $request->input('lokasi_to_delete');
        $this->dashboardService->deleteLokasi($lokasi);

        return redirect()->back()->with('success', 'Lokasi "'.$lokasi.'" dan seluruh datanya berhasil dihapus.');
    }
}
