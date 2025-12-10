<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaksi_id',
        'alat_id',
        'nama_alat',
        'harga_sewa',
        'jumlah',
        'tanggal_mulai',
        'tanggal_selesai',
        'lama_hari',
        'subtotal',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id');
    }
}
