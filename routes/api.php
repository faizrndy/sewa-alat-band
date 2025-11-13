<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\AlatBand;
use App\Models\Review;
use App\Models\Faq;
use App\Http\Controllers\AlatBandController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Di file ini kita mendefinisikan semua route API untuk aplikasi.
| Frontend (Vue) akan mengambil data dari sini.
|
*/

Route::get('/alat-band', function () {
    return AlatBand::all();
});

Route::get('/alat-band', [AlatBandController::class, 'apiIndex']);
Route::get('/alat-band/{id}', [AlatBandController::class, 'apiShow']);

Route::get('/reviews', function () {
    return response()->json(Review::latest()->get());
});

Route::get('/faqs', function () {
    return Faq::all();
});

// Cek koneksi API
Route::get('/ping', function () {
    return response()->json(['message' => 'API aktif!']);
});


