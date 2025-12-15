<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    // Guarded dihapus, pakai fillable saja
    protected $fillable = [
        'kode_transaksi', 'user_id', 'nama', 'telepon', 'alamat',
        'deskripsi_lokasi', 'lat', 'lon', 'jarak_km',
        'tgl_mulai', 'tgl_selesai', 'lama_hari', 
        'metode_pengiriman', 'tarif_antar', 'total_sewa', 'total_bayar',
        'identitas', 'bukti_bayar', 'snap_token', 'status'
    ];

    // INI WAJIB ADA untuk mengatasi 'RpNaN'
    protected $casts = [
        'total_bayar' => 'integer',
        'total_sewa'  => 'integer',
        'tarif_antar' => 'integer',
        'user_id'     => 'integer',
    ];

    public function items()
    {
        return $this->hasMany(TransaksiItem::class, 'transaksi_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}