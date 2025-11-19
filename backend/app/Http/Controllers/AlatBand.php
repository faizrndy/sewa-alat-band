<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlatBand extends Model
{
    protected $table = 'alat_bands';

    protected $fillable = [
        'nama_alat',
        'kategori',
        'stok',
        'harga_sewa',
        'deskripsi',
        'gambar',
        'status',
    ];

    protected $casts = [
        'harga_sewa' => 'decimal:2',
    ];
}