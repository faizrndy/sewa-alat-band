<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alat_bands', function (Blueprint $table) {
            $table->id();
            $table->string('nama_alat');
            $table->string('kategori');
            $table->integer('stok')->default(0);
            $table->decimal('harga_sewa', 12, 2);
            $table->string('gambar')->nullable();
            $table->enum('status', ['Tersedia', 'Disewa', 'Dalam Perbaikan'])->default('Tersedia');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alat_bands');
    }
};