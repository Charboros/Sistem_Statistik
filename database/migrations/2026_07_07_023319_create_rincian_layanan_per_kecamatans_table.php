<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rincian_layanan_per_kecamatans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kecamatan');
            $table->string('kategori')->default('kecamatan'); // kecamatan, mpp, dinas
            $table->string('jenis_layanan');
            $table->integer('jumlah')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rincian_layanan_per_kecamatans');
    }
};
