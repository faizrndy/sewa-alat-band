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
| 1. PUBLIC API (Open Access)
|--------------------------------------------------------------------------
| Route ini boleh diakses oleh siapa saja (Tamu/Guest).
| Tidak ada resiko keamanan fatal di sini karena hanya data bacaan (GET).
*/

// Cek Ketersediaan & Katalog
Route::post('/alat-band/check-availability', [TransaksiController::class, 'checkAvailability']);
Route::get('/alat-check', [AlatBandController::class, 'searchAvailable']);

Route::get('/alat-band', [AlatBandController::class, 'apiIndex']);
Route::get('/alat-band/{id}', [AlatBandController::class, 'apiShow']);
Route::get('/alat-band-public', [AlatBandController::class, 'apiIndex']);
Route::get('/alat-band-public/{id}', [AlatBandController::class, 'apiShow']);

// Info Umum
Route::get('/reviews', fn() => Review::latest()->get());
Route::get('/faqs', fn() => Faq::all());
Route::get('/ping', fn() => response()->json(['message' => 'API aktif!']));

/*
|--------------------------------------------------------------------------
| 2. AUTHENTICATION (Pintu Gerbang)
|--------------------------------------------------------------------------
*/

Route::post('/register/send-otp', [AuthController::class, 'sendOtp']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);

// Security: Rate Limiting (Mencegah Brute Force Attack)
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:5,1'); 

/*
|--------------------------------------------------------------------------
| 3. MIDTRANS CALLBACK (Wajib Public)
|--------------------------------------------------------------------------
| Webhook ini dipanggil oleh Server Midtrans, bukan User. 
| Jadi tidak boleh dikunci auth, tapi harus divalidasi signature-nya di Controller.
*/
Route::post('/midtrans/callback', [MidtransController::class, 'callback']);

/*
|--------------------------------------------------------------------------
| 4. PROTECTED ROUTES (RBAC IMPLEMENTATION)
|--------------------------------------------------------------------------
| Di sini penerapan keamanan RBAC dimulai.
| Lapis 1: auth:sanctum -> Harus Login (Punya Token)
*/
Route::middleware('auth:sanctum')->group(function () {

    // ====================================================
    // 👤 ROLE: AUTHENTICATED USER (CUSTOMER)
    // ====================================================
    
    // Perbaikan Keamanan: Create Transaction pindah ke sini!
    // Hanya user login yang boleh membuat tagihan pembayaran.
    Route::post('/midtrans/create-transaction', [MidtransController::class, 'createTransaction']);
    
    // Transaksi Database
    Route::post('/transaksi', [TransaksiController::class, 'store']);
    Route::get('/buyer/history', [TransaksiController::class, 'history']);
    Route::get('/riwayat/{telepon}', [TransaksiController::class, 'riwayat']);

    // Profile Management
    Route::get('/buyer/profile', [AuthController::class, 'profile']);
    Route::put('/buyer/update', [AuthController::class, 'updateProfile']);
    Route::post('/buyer/logout', [AuthController::class, 'logout']);


    // ====================================================
    // 🛡️ ROLE: ADMINISTRATOR (RBAC LEVEL 2)
    // ====================================================
    // Lapis 2: middleware('admin') -> Cek kolom 'role' di database
    // Jika user login tapi role != 'admin', akses ditolak (403 Forbidden).
    Route::middleware('admin')->group(function () {
        
        // Dashboard Statistik
        Route::get('/admin/dashboard', [DashboardController::class, 'index']);

        // Monitoring Transaksi
        Route::get('/admin/transaksi', [AdminTransaksiController::class, 'index']);
        Route::get('/admin/transaksi/{kode}', [AdminTransaksiController::class, 'show']); 
        Route::patch('/admin/transaksi/{id}/status', [AdminTransaksiController::class, 'updateStatus']);

        // Manajemen Aset (CRUD Alat)
        // Keamanan: Hanya admin yang berhak mengubah data master
        Route::post('/alat-band', [AlatBandController::class, 'store']);
        Route::post('/alat-band/{id}', [AlatBandController::class, 'update']); // Pakai POST untuk handle file upload
        Route::delete('/alat-band/{id}', [AlatBandController::class, 'destroy']);
    });

});