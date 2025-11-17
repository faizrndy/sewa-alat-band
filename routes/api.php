<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\AlatBand;
use App\Models\Review;
use App\Models\Faq;
use App\Http\Controllers\AlatBandController;
use App\Http\Controllers\Api\AuthController;

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
