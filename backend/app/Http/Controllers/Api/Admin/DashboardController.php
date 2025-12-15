<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\AlatBand;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. CARD ATAS
        $totalAlat = AlatBand::count();
        $pendapatan = Transaksi::whereIn('status', ['success', 'settlement'])->sum('total_bayar');
        $perluDiproses = Transaksi::where('status', 'pending')->count();

        // 2. TABEL TRANSAKSI TERBARU (BAGIAN PENTING)
        // Perhatikan bedanya: Kita pakai array [] untuk memanggil 'user' dan 'items.alat'
        $transaksiTerbaru = Transaksi::with(['user', 'items.alat'])
            ->latest()
            ->take(5)
            ->get();

        // 3. GRAFIK
        $grafikRaw = Transaksi::selectRaw('MONTH(created_at) as bulan, SUM(total_bayar) as total')
            ->whereYear('created_at', date('Y'))
            ->whereIn('status', ['success', 'settlement'])
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $grafikPendapatan = array_fill(0, 12, 0); 
        
        foreach ($grafikRaw as $data) {
            $grafikPendapatan[$data->bulan - 1] = (int) $data->total;
        }

        return response()->json([
            'total_alat' => $totalAlat,
            'pendapatan' => (int) $pendapatan,
            'perlu_diproses' => $perluDiproses,
            'transaksi_terbaru' => $transaksiTerbaru,
            'grafik_per_bulan' => $grafikPendapatan 
        ]);
    }
}