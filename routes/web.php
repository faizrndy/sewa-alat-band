<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlatBandController;
use App\Http\Controllers\RiwayatController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Di sini Anda bisa mendaftarkan semua rute untuk aplikasi web Anda.
|
*/

// Mengarahkan halaman utama langsung ke dashboard
Route::get('/', function () {
    return redirect('/dashboard');
});

// Route untuk halaman Dashboard
Route::get('/dashboard', [AlatBandController::class, 'dashboard'])->name('dashboard');

// Route resource untuk mengelola semua CRUD Alat Band
Route::resource('alat-band', AlatBandController::class);

// Route resource untuk mengelola CRUD Riwayat
Route::resource('riwayat', RiwayatController::class);

// BARU: Route spesifik untuk menampilkan nota penyewaan
Route::get('/riwayat/nota/{id}', [RiwayatController::class, 'showNota'])->name('riwayat.nota');