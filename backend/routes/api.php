<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use App\Models\AlatBand;
use App\Models\Review;
use App\Models\Faq;
use App\Http\Controllers\AlatBandController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TransaksiController;
use App\Http\Controllers\Api\MidtransController;
use App\Http\Controllers\Api\AdminTransaksiController;



/*
|--------------------------------------------------------------------------
| PUBLIC API
|--------------------------------------------------------------------------
| API yang bisa diakses tanpa login
*/

// ALAT BAND (public)
Route::get('/alat-band', [AlatBandController::class, 'apiIndex']);
Route::get('/alat-band/{id}', [AlatBandController::class, 'apiShow']);

// REVIEWS (public)
Route::get('/reviews', function () {
    return response()->json(Review::latest()->get());
});

// FAQ (public)
Route::get('/faqs', fn() => Faq::all());

// Cek koneksi API
Route::get('/ping', fn() => response()->json(['message' => 'API aktif!']));


/*
|--------------------------------------------------------------------------
| BUYER AUTH API
|--------------------------------------------------------------------------
| API register & login buyer (tanpa token)
*/

// REGISTER buyer
Route::post('/register', [AuthController::class, 'registerBuyer']);

// LOGIN buyer
Route::post('/login', [AuthController::class, 'loginBuyer']);


Route::post('/transaksi', [TransaksiController::class, 'store']);
Route::get('/riwayat/{telepon}', [TransaksiController::class, 'riwayat']);


Route::get('/test-wa', function () {

    $res = Http::withHeaders([
        'Authorization' => env('FONNTE_API_KEY'),
    ])->post('https://api.fonnte.com/send', [
        'target' => env('ADMIN_WA'),
        'message' => 'Test WA dari Laravel — sistem kamu bekerja! 👌🔥',
    ]);

    return $res->json();
});

Route::post('/midtrans/callback', [MidtransController::class, 'callback']);

Route::patch('/admin/transaksi/{id}/status', [AdminTransaksiController::class, 'updateStatus']);
Route::get('/riwayat/{telepon}', [TransaksiController::class, 'riwayat']);

/*
|--------------------------------------------------------------------------
| BUYER PROTECTED API
|--------------------------------------------------------------------------
| API yang butuh token Sanctum
*/

Route::middleware('auth:sanctum')->group(function () {

    // PROFILE buyer
    Route::get('/buyer/profile', [AuthController::class, 'profile']);

    Route::put('/buyer/update', [AuthController::class, 'updateProfile']);

    // LOGOUT buyer
    Route::post('/buyer/logout', [AuthController::class, 'logout']);
});
