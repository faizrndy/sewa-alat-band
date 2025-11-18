<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class AdminTransaksiController extends Controller
{
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,success,failed',
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
