<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_transaksi',
        'nama',
        'telepon',
        'alamat',
        'deskripsi_lokasi',
        'lat',
        'lon',
        'jarak_km',
        'metode_pengiriman',
        'tarif_antar',
        'total_sewa',
        'total_bayar',
        'identitas',
        'bukti_bayar',
        'status',
    ];

    public function items()
    {
        return $this->hasMany(TransaksiItem::class);
    }
}
