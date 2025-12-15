<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    protected $guarded = ['id']; // Cara cepat: Izinkan semua kolom kecuali ID

    protected $fillable = [
        'kode_transaksi', 'user_id', 'nama', 'telepon', 'alamat',
        'deskripsi_lokasi', 'lat', 'lon', 'jarak_km',
        'tgl_mulai', 'tgl_selesai', 'lama_hari', // <--- WAJIB ADA INI
        'metode_pengiriman', 'tarif_antar', 'total_sewa', 'total_bayar',
        'identitas', 'bukti_bayar', 'snap_token', 'status'
    ];

    public function items()
    {
        return $this->hasMany(TransaksiItem::class, 'transaksi_id');
    }
}
