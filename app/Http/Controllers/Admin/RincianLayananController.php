<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RincianLayananPerKecamatan;
use App\Models\TotalLayanan;
use Illuminate\Http\Request;

class RincianLayananController extends Controller
{
    public function index()
    {
        $rincian = RincianLayananPerKecamatan::orderBy('nama_kecamatan')->get();
        return view('admin.rincian-layanan.index', compact('rincian'));
    }

    public function create()
    {
        $layananTypes = TotalLayanan::pluck('jenis_layanan')->toArray();
        return view('admin.rincian-layanan.create', compact('layananTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kecamatan' => 'required|string|max:255',
            'jenis_layanan' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:0',
        ]);
        
        RincianLayananPerKecamatan::create($validated);
        
        return redirect()->route('admin.rincian-layanan.index')->with('success', 'Data berhasil ditambahkan.');
    }

    public function edit(RincianLayananPerKecamatan $rincianLayanan)
    {
        $layananTypes = TotalLayanan::pluck('jenis_layanan')->toArray();
        return view('admin.rincian-layanan.edit', compact('rincianLayanan', 'layananTypes'));
    }

    public function update(Request $request, RincianLayananPerKecamatan $rincianLayanan)
    {
        $validated = $request->validate([
            'nama_kecamatan' => 'required|string|max:255',
            'jenis_layanan' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:0',
        ]);
        
        $rincianLayanan->update($validated);
        
        return redirect()->route('admin.rincian-layanan.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(RincianLayananPerKecamatan $rincianLayanan)
    {
        $rincianLayanan->delete();
        return redirect()->route('admin.rincian-layanan.index')->with('success', 'Data berhasil dihapus.');
    }
}
