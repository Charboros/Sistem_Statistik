<?php

namespace App\Http\Controllers;

use App\Models\RincianLayananPerKecamatan;

class ViewerController extends Controller
{
    protected $dashboardService;

    /**
     * Inject DashboardService (Clean Code: Dependency Injection)
     */
    public function __construct(\App\Services\DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Tampilkan halaman depan (publik)
     */
    public function index()
    {
        // Clean Code: Controller menjadi sangat tipis (Thin Controller).
        // Seluruh logika perhitungan dan caching dipindahkan ke DashboardService (Fat Service).
        $data = $this->dashboardService->getViewerData();

        return view('welcome', $data);
    }
}
