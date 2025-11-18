<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlatBandController;
use App\Http\Controllers\Admin\RiwayatController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\FaqController;

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

        // ✅ CETAK NOTA – sudah benar diarahkan ke showNota()
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
