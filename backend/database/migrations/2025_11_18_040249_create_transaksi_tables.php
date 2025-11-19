<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();

            // Data penyewa
            $table->string('nama');
            $table->string('telepon');
            $table->text('alamat');
            $table->text('deskripsi_lokasi')->nullable();

            // Lokasi + jarak
            $table->decimal('lat', 10, 7);
            $table->decimal('lon', 10, 7);
            $table->decimal('jarak_km', 10, 2);

            // Pengiriman
            $table->enum('metode_pengiriman', ['ambil', 'antar']);
            $table->integer('tarif_antar')->default(0);

            // Total
            $table->integer('total_sewa');
            $table->integer('total_bayar');

            // Upload files
            $table->string('identitas');
            $table->string('bukti_bayar');

            // Status transaksi
            $table->enum('status', ['pending', 'berhasil'])->default('pending');

            $table->timestamps();
        });

        Schema::create('transaksi_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_id')->constrained('transaksis')->onDelete('cascade');

            // Item detail
            $table->integer('alat_id');
            $table->string('nama_alat');
            $table->integer('harga_sewa');
            $table->integer('jumlah');

            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->integer('lama_hari');

            $table->integer('subtotal');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_items');
        Schema::dropIfExists('transaksis');
    }
};
