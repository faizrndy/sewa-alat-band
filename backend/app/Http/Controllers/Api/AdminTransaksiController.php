<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class AdminTransaksiController extends Controller
{
    public function index()
    {
        // Ambil semua data transaksi
        // 'with(\'items\')' => biar data barang yang disewa ikut terambil
        // 'latest()' => urutkan dari yang paling baru
        $transaksi = Transaksi::with('items')->latest()->get();

        return response()->json($transaksi);
    }

    /**
     * 2. UPDATE STATUS TRANSAKSI
     */
    public function updateStatus(Request $request, $id)
    {
        // Validasi input (Sesuaikan dengan enum di database)
        // Saya tambahkan 'settlement', 'expired', 'cancelled' jaga-jaga kalau dari Midtrans masuk
        $request->validate([
            'status' => 'required|in:pending,success,failed,settlement,expired,cancelled',
        ]);

        $trx = Transaksi::findOrFail($id);
        $trx->status = $request->status;
        $trx->save();

        return response()->json([
            'success' => true,
            'message' => 'Status transaksi berhasil diupdate',
            'data'    => $trx,
        ]);
    }
}
