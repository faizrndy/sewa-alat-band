<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlatBandController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\AuthController;

// 🔹 Website utama (tanpa login)
Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/katalog', [LandingController::class, 'katalog'])->name('katalog');
Route::get('/kategori/{kategori}', [LandingController::class, 'byKategori'])->name('kategori');
Route::get('/product/{id}', [LandingController::class, 'show'])->name('product.detail');

// 🔹 Route login/logout
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 🔒 Dashboard (hanya bisa diakses setelah login)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AlatBandController::class, 'dashboard'])->name('dashboard');
    Route::resource('alat-band', AlatBandController::class);
    Route::resource('riwayat', RiwayatController::class);
    Route::get('/riwayat/nota/{id}', [RiwayatController::class, 'showNota'])->name('riwayat.nota');
});
