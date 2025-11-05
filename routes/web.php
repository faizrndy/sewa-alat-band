<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlatBandController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\LandingController;

Route::get('/', [LandingController::class, 'index'])->name('home');

Route::get('/katalog', [LandingController::class, 'katalog'])->name('katalog');
Route::get('/kategori/{kategori}', [LandingController::class, 'byKategori'])->name('kategori');
Route::get('/product/{id}', [LandingController::class, 'show'])->name('product.detail');

Route::get('/dashboard', [AlatBandController::class, 'dashboard'])->name('dashboard');
Route::resource('alat-band', AlatBandController::class);
Route::resource('riwayat', RiwayatController::class);
Route::get('/riwayat/nota/{id}', [RiwayatController::class, 'showNota'])->name('riwayat.nota');