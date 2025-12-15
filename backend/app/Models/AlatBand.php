<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlatBand extends Model
{
    use HasFactory;

    // Pastikan ini sesuai dengan nama tabel di database
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

    /**
     * Relasi ke Detail Transaksi (Item)
     * PENTING: Fungsi ini wajib ada agar Controller bisa menghitung stok terpakai.
     */
    public function transaksiItems()
    {
        // Satu Alat bisa ada di banyak Transaksi Item
        return $this->hasMany(TransaksiItem::class, 'alat_id');
    }
}