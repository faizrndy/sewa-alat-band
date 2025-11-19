<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AlatBand;

class AlatBandSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama_alat' => 'Gitar Akustik Yamaha F310',
                'kategori' => 'Gitar',
                'stok' => 3,
                'harga_sewa' => 30000,
                'deskripsi' => 'Gitar akustik cocok untuk pemula maupun profesional.',
                'gambar' => 'images/alat-band/gitar1.jpg',
                'status' => 'Tersedia',
            ],
            [
                'nama_alat' => 'Gitar Listrik Ibanez GRG',
                'kategori' => 'Gitar',
                'stok' => 2,
                'harga_sewa' => 45000,
                'deskripsi' => 'Gitar listrik dengan suara punchy dan tajam.',
                'gambar' => 'images/alat-band/gitar2.jpg',
                'status' => 'Tersedia',
            ],
            [
                'nama_alat' => 'Bass Fender Jazz Bass',
                'kategori' => 'Bass',
                'stok' => 1,
                'harga_sewa' => 50000,
                'deskripsi' => 'Bass elektrik dengan karakter warm dan deep.',
                'gambar' => 'images/alat-band/bass1.jpg',
                'status' => 'Tersedia',
            ],
            [
                'nama_alat' => 'Bass Akustik Cort SJB5F',
                'kategori' => 'Bass',
                'stok' => 2,
                'harga_sewa' => 40000,
                'deskripsi' => 'Bass akustik dengan resonansi kuat.',
                'gambar' => 'images/alat-band/bass2.jpg',
                'status' => 'Tersedia',
            ],
            [
                'nama_alat' => 'Drum Set Yamaha Stage Custom',
                'kategori' => 'Drum',
                'stok' => 1,
                'harga_sewa' => 120000,
                'deskripsi' => 'Drum set lengkap dengan cymbal.',
                'gambar' => 'images/alat-band/drum1.jpg',
                'status' => 'Tersedia',
            ],
            [
                'nama_alat' => 'Cajon Meinl Percussion',
                'kategori' => 'Drum',
                'stok' => 4,
                'harga_sewa' => 25000,
                'deskripsi' => 'Cajon akustik cocok untuk pertunjukan kecil.',
                'gambar' => 'images/alat-band/cajon1.jpg',
                'status' => 'Tersedia',
            ],
            [
                'nama_alat' => 'Keyboard Yamaha PSR-E373',
                'kategori' => 'Keyboard',
                'stok' => 2,
                'harga_sewa' => 60000,
                'deskripsi' => 'Keyboard 61-key dengan fitur lengkap.',
                'gambar' => 'images/alat-band/keyboard1.jpg',
                'status' => 'Tersedia',
            ],
            [
                'nama_alat' => 'Piano Digital Roland FP-30',
                'kategori' => 'Keyboard',
                'stok' => 1,
                'harga_sewa' => 150000,
                'deskripsi' => 'Piano digital dengan weighted keys.',
                'gambar' => 'images/alat-band/piano1.jpg',
                'status' => 'Tersedia',
            ],
            [
                'nama_alat' => 'Mikrofon Shure SM58',
                'kategori' => 'Mikrofon',
                'stok' => 5,
                'harga_sewa' => 20000,
                'deskripsi' => 'Mikrofon vokal dengan kualitas terbaik.',
                'gambar' => 'images/alat-band/mic1.jpg',
                'status' => 'Tersedia',
            ],
            [
                'nama_alat' => 'Mikrofon Wireless Sennheiser',
                'kategori' => 'Mikrofon',
                'stok' => 3,
                'harga_sewa' => 50000,
                'deskripsi' => 'Mikrofon wireless ideal untuk acara outdoor.',
                'gambar' => 'images/alat-band/mic2.jpg',
                'status' => 'Tersedia',
            ],
        ];

        foreach ($data as $item) {
            AlatBand::create($item);
        }
    }
}
