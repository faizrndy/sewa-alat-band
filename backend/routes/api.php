<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

use App\Models\Review;
use App\Models\Faq;

// CONTROLLERS
use App\Http\Controllers\AlatBandController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TransaksiController;
use App\Http\Controllers\Api\MidtransController;
use App\Http\Controllers\Api\AdminTransaksiController;
use App\Http\Controllers\Api\Admin\DashboardController;

/*
|--------------------------------------------------------------------------
| PUBLIC API
|--------------------------------------------------------------------------
*/

// Booking Availability
Route::post('/alat-band/check-availability', [TransaksiController::class, 'checkAvailability']);

// Alat Band
Route::get('/alat-band', [AlatBandController::class, 'apiIndex']);
Route::get('/alat-band/{id}', [AlatBandController::class, 'apiShow']);

// Review & FAQ
Route::get('/reviews', fn() => Review::latest()->get());
Route::get('/faqs', fn() => Faq::all());

Route::get('/ping', fn() => response()->json(['message' => 'API aktif!']));

/*
|--------------------------------------------------------------------------
| AUTH (REGISTER + OTP + LOGIN)
|--------------------------------------------------------------------------
*/

// 1️⃣ Kirim OTP ke email saat register
Route::post('/register/send-otp', [AuthController::class, 'sendOtp']);

// REGISTER + OTP
Route::post('/register', [AuthController::class, 'register']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/login', [AuthController::class, 'login']);


/*
|--------------------------------------------------------------------------
| MIDTRANS
|--------------------------------------------------------------------------
*/
Route::post('/midtrans/callback', [MidtransController::class, 'callback']);
Route::post('/midtrans/create-transaction', [MidtransController::class, 'createTransaction']);

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (auth:sanctum)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    // Transaksi
    Route::post('/transaksi', [TransaksiController::class, 'store']);
    Route::get('/riwayat/{telepon}', [TransaksiController::class, 'riwayat']);

    // Profile
    Route::get('/buyer/profile', [AuthController::class, 'profile']);
    Route::put('/buyer/update', [AuthController::class, 'updateProfile']);
    Route::post('/buyer/logout', [AuthController::class, 'logout']);

    // Admin Dashboard
    Route::get('/admin/dashboard', [DashboardController::class, 'index']);

    // Admin Transaksi
    Route::get('/admin/transaksi', [AdminTransaksiController::class, 'index']);
    Route::patch('/admin/transaksi/{id}/status', [AdminTransaksiController::class, 'updateStatus']);

    // CRUD Alat Band
    Route::post('/alat-band', [AlatBandController::class, 'store']);
    Route::post('/alat-band/{id}', [AlatBandController::class, 'update']);
    Route::delete('/alat-band/{id}', [AlatBandController::class, 'destroy']);
});
