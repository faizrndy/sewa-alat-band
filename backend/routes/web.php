<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlatBandController;
use App\Http\Controllers\Admin\RiwayatController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\FaqController;
// 👇 PENTING: Import Model Transaksi agar Jalur Tikus jalan
use App\Models\Transaksi;

/*
|--------------------------------------------------------------------------
| FIX UNTUK REQUEST API (LARAVEL 11 / 12)
|--------------------------------------------------------------------------
*/
if (request()->is('api/*')) {
    return;
}

/*
|--------------------------------------------------------------------------
| ROUTES WEB
|--------------------------------------------------------------------------
*/

// LOGIN
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| ADMIN AREA (HARUS LOGIN)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // DASHBOARD
    Route::get('/dashboard', [AlatBandController::class, 'dashboard'])->name('dashboard');

    // PRODUK CRUD
    Route::resource('alat-band', AlatBandController::class);

    /*
    |--------------------------------------------------------------------------
    | RIWAYAT PENYEWAAN
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->group(function () {

        Route::get('/riwayat', [RiwayatController::class, 'index'])
            ->name('riwayat.index');

        Route::get('/riwayat/{id}', [RiwayatController::class, 'show'])
            ->name('riwayat.show');

        // CETAK NOTA
        Route::get('/riwayat/nota/{id}', [RiwayatController::class, 'showNota'])
            ->name('riwayat.nota');

        Route::post('/riwayat/update-status/{id}', [RiwayatController::class, 'updateStatus'])
            ->name('riwayat.updateStatus');

        Route::get('/admin/riwayat/pdf/{id}', [RiwayatController::class, 'cetakPDF'])
            ->name('riwayat.pdf');

    });

    /*
    |--------------------------------------------------------------------------
    | REVIEW
    |--------------------------------------------------------------------------
    */
    Route::get('/review', [ReviewController::class, 'index'])->name('review.index');
    Route::get('/review/create', [ReviewController::class, 'create'])->name('review.create');
    Route::post('/review', [ReviewController::class, 'upload'])->name('review.upload');
    Route::delete('/review/{id}', [ReviewController::class, 'destroy'])->name('review.destroy');

    /*
    |--------------------------------------------------------------------------
    | FAQ
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->group(function () {
        Route::get('/faq', [FaqController::class, 'index'])->name('faq.index');
        Route::post('/faq', [FaqController::class, 'store'])->name('faq.store');
        Route::delete('/faq/{id}', [FaqController::class, 'destroy'])->name('faq.destroy');
    });
});

/*
|--------------------------------------------------------------------------
| ROOT REDIRECT LOGIN / DASHBOARD
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| 🔥 JALUR TIKUS (CHEAT ROUTE UNTUK TESTING)
|--------------------------------------------------------------------------
| Cara Pakai:
| 1. Lakukan Checkout di Web Customer -> Dapat Kode TRX (Misal: TRX-A1B2C3)
| 2. Buka Tab Baru Browser
| 3. Ketik: http://127.0.0.1:8000/debug/lunas/TRX-A1B2C3
| 4. Enter -> Status otomatis berubah jadi PAID
*/
Route::get('/debug/lunas/{kode}', function ($kode) {
    // 1. Cari Transaksi
    $trx = Transaksi::where('kode_transaksi', $kode)->first();

    if (!$trx) {
        return "<h1 style='color:red'>Transaksi $kode tidak ditemukan!</h1>";
    }

    // 2. Ubah status jadi SUCCESS (Jangan 'paid')
    $trx->update(['status' => 'success']);

    return "
    <div style='text-align:center; padding: 50px; font-family: sans-serif;'>
        <h1 style='color:green'>✅ BERHASIL!</h1>
        <h2>Transaksi <b style='color:blue'>$kode</b> sekarang statusnya <span style='background:green; color:white; padding:5px 10px; border-radius:5px;'>SUCCESS</span></h2>
        <p>Silakan kembali ke Admin Panel atau Riwayat Customer dan Refresh halaman.</p>
    </div>
    ";
});