<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlatBandController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\FaqController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Semua route web aplikasi diatur di sini:
| Login, Logout, Dashboard, CRUD Alat Band, Review, FAQ, dan Riwayat.
|
*/

// 🔹 Login & Logout
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 🔒 Grup route hanya untuk user yang sudah login
Route::middleware('auth')->group(function () {

    // 🏠 Dashboard utama
    Route::get('/dashboard', [AlatBandController::class, 'dashboard'])->name('dashboard');

    // 🎸 CRUD Alat Band
    Route::resource('alat-band', AlatBandController::class);

    // 📜 Riwayat Penyewaan
    Route::resource('riwayat', RiwayatController::class);
    Route::get('/riwayat/nota/{id}', [RiwayatController::class, 'showNota'])->name('riwayat.nota');

    // 💬 Review Pelanggan
    Route::get('/review', [ReviewController::class, 'index'])->name('review.index');
    Route::get('/review/create', [ReviewController::class, 'create'])->name('review.create');
    Route::post('/review', [ReviewController::class, 'upload'])->name('review.upload');
    Route::delete('/review/{id}', [ReviewController::class, 'destroy'])->name('review.destroy');

    // ❓ FAQ (Pertanyaan Umum)
    Route::get('/admin/faq', [FaqController::class, 'index'])->name('faq.index');
    Route::post('/admin/faq', [FaqController::class, 'store'])->name('faq.store');
    Route::delete('/admin/faq/{id}', [FaqController::class, 'destroy'])->name('faq.destroy');

    Route::resource('faq', \App\Http\Controllers\FaqController::class);

});

// 🔹 Redirect root (/) ke dashboard atau login
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});
