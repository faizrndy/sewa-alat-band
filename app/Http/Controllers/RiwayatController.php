<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index()
    {
        $transaksi = Transaksi::latest()->get();
        return view('admin.riwayat.index', compact('transaksi'));
    }

    public function show($id)
    {
        $trx = Transaksi::with('items')->findOrFail($id);
        return view('admin.riwayat.show', compact('trx'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,success,failed'
        ]);

        $trx = Transaksi::findOrFail($id);
        $trx->status = $request->status;
        $trx->save();

        return back()->with('success', 'Status transaksi berhasil diperbarui.');
    }
}
