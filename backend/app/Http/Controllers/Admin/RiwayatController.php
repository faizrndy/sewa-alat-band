<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class RiwayatController extends Controller
{
    /**
     * Lihat Semua Data Riwayat
     */
    public function index()
    {
        // Ambil data terbaru dengan item-nya
        $transaksi = Transaksi::with('items')->latest()->get();
        return response()->json($transaksi);
    }

    /**
     * Detail Satu Transaksi
     */
    public function show($id)
    {
        $trx = Transaksi::with('items')->find($id);

        if (!$trx) {
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        }

        return response()->json($trx);
    }

    /**
     * Update Status Transaksi
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,success,failed,settlement,expired,cancelled'
        ]);

        $trx = Transaksi::findOrFail($id);
        $trx->status = $request->status;
        $trx->save();

        return response()->json([
            'success' => true,
            'message' => 'Status transaksi berhasil diperbarui!',
            'data' => $trx
        ]);
    }

    /**
     * Download Nota PDF
     */
    public function cetakPDF($id)
    {
        $trx = Transaksi::with('items')->findOrFail($id);
        
        $pdf = Pdf::loadView('riwayat.pdf', compact('trx'))->setPaper('A4', 'portrait');

        return $pdf->download('Nota-'.$trx->kode_transaksi.'.pdf');
    }
}