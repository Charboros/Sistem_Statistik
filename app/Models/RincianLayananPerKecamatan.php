<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RincianLayananPerKecamatan extends Model
{
    protected $fillable = [
        'nama_kecamatan',
        'kategori',
        'jenis_layanan',
        'jumlah',
    ];
}
