<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class RiwayatController extends Controller
{
    public function index()
    {
        $transaksi = Transaksi::latest()->get();
        return view('riwayat.index', compact('transaksi'));
    }

    public function show($id)
    {
        $trx = Transaksi::with('items')->findOrFail($id);
        return view('riwayat.show', compact('trx'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,success,failed'
        ]);

        $trx = Transaksi::findOrFail($id);
        $trx->status = $request->status;
        $trx->save();

        return redirect()
            ->route('riwayat.show', $id)
            ->with('success', 'Status transaksi berhasil diperbarui!');
    }

    /**
     * CETAK NOTA
     */
    public function showNota($id)
    {
        $trx = Transaksi::with('items')->findOrFail($id);
        return view('riwayat.nota', compact('trx'));
    }


public function cetakPDF($id)
{
    $trx = Transaksi::with('items')->findOrFail($id);

    $pdf = Pdf::loadView('riwayat.pdf', compact('trx'))->setPaper('A4', 'portrait');

    return $pdf->download('Nota-'.$trx->kode_transaksi.'.pdf');
}
}
