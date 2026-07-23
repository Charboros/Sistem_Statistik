<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\RincianLayananPerKecamatan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_requires_authentication(): void
    {
        $response = $this->get('/admin/dashboard');

        // Should redirect to login
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_admin_dashboard_can_be_accessed_by_authenticated_user(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Dinas Dukcapil');
    }

    public function test_admin_can_add_new_lokasi(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/admin/dashboard/lokasi', [
            'new_lokasi' => 'Pangkah Baru',
            'kategori' => 'kecamatan'
        ]);

        $response->assertStatus(302);
        
        $this->assertDatabaseHas('rincian_layanan_per_kecamatans', [
            'nama_kecamatan' => 'Pangkah Baru',
            'kategori' => 'kecamatan'
        ]);
    }
}
