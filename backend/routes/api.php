<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use App\Models\Review;
use App\Models\Faq;

// --- DAFTAR CONTROLLER ---
use App\Http\Controllers\AlatBandController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TransaksiController;
use App\Http\Controllers\Api\MidtransController;
use App\Http\Controllers\Api\AdminTransaksiController;

// [PENTING] Import Controller Dashboard yang baru dibuat
use App\Http\Controllers\Api\Admin\DashboardController; 

/*
|--------------------------------------------------------------------------
| PUBLIC API (Bisa diakses tanpa login)
|--------------------------------------------------------------------------
*/

// Cek Ketersediaan Alat (Untuk Booking)
Route::post('/alat-band/check-availability', [TransaksiController::class, 'checkAvailability']);

// 1. ALAT BAND
Route::get('/alat-band', [AlatBandController::class, 'apiIndex']);
Route::get('/alat-band/{id}', [AlatBandController::class, 'apiShow']);

// 2. INFO UMUM
Route::get('/reviews', function () {
    return response()->json(Review::latest()->get());
});
Route::get('/faqs', fn() => Faq::all());
Route::get('/ping', fn() => response()->json(['message' => 'API aktif!']));

// 3. AUTHENTICATION (Umum untuk Admin & Buyer)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// 4. MIDTRANS INTEGRATION
// Callback dari Midtrans (Webhook)
Route::post('/midtrans/callback', [MidtransController::class, 'callback']);
// Endpoint untuk membuat transaksi ke Midtrans
Route::post('/midtrans/create-transaction', [MidtransController::class, 'createTransaction']);

// 5. TEST WHATSAPP (Opsional)
Route::get('/test-wa', function () {
    $res = Http::withHeaders([
        'Authorization' => env('FONNTE_API_KEY'),
    ])->post('https://api.fonnte.com/send', [
        'target' => env('ADMIN_WA'),
        'message' => 'Test WA dari Laravel — sistem kamu bekerja! 👌🔥',
    ]);
    return $res->json();
});

/*
|--------------------------------------------------------------------------
| PROTECTED API (Harus Login / Butuh Token)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    // === MODULE TRANSAKSI (Customer) ===
    Route::post('/transaksi', [TransaksiController::class, 'store']);
    Route::get('/riwayat/{telepon}', [TransaksiController::class, 'riwayat']);

    // === MODULE USER PROFILE ===
    Route::get('/buyer/profile', [AuthController::class, 'profile']);
    Route::put('/buyer/update', [AuthController::class, 'updateProfile']);
    Route::post('/buyer/logout', [AuthController::class, 'logout']);

    // === MODULE ADMIN ===

    // 1. Dashboard (INI YANG BARU DITAMBAHKAN)
    Route::get('/admin/dashboard', [DashboardController::class, 'index']);

    // 2. Kelola Transaksi
    Route::get('/admin/transaksi', [AdminTransaksiController::class, 'index']);
    Route::patch('/admin/transaksi/{id}/status', [AdminTransaksiController::class, 'updateStatus']);

    // 3. Kelola Alat Band (CRUD)
    Route::get('/admin/alat-band', [AlatBandController::class, 'apiIndex']); // List/Get all
    Route::post('/admin/alat-band', [AlatBandController::class, 'store']); // Tambah
    Route::post('/admin/alat-band/{id}', [AlatBandController::class, 'update']); // Update
    Route::delete('/admin/alat-band/{id}', [AlatBandController::class, 'destroy']); // Hapus
});