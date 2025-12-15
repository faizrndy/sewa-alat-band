<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaksi_id',
        'alat_id', // Sesuai database mas
        'nama_alat',
        'harga_sewa',
        'jumlah',
        'tanggal_mulai',
        'tanggal_selesai',
        'lama_hari',
        'subtotal',
    ];
    
    // Casting tambahan biar aman
    protected $casts = [
        'harga_sewa' => 'integer',
        'subtotal'   => 'integer',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id');
    }

    public function alat()
    {
        // PENTING: Parameter kedua harus 'alat_id' karena di database namanya alat_id
        return $this->belongsTo(AlatBand::class, 'alat_id');
    }
}