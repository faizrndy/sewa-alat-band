<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\AlatBand;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Data Card Atas
        $totalAlat = AlatBand::count();
        $pendapatan = Transaksi::whereIn('status', ['success', 'settlement'])->sum('total_bayar');
        $perluDiproses = Transaksi::where('status', 'pending')->count();
        $transaksiTerbaru = Transaksi::with('items')->latest()->take(5)->get();

        // 2. DATA GRAFIK
        // Mengelompokkan pendapatan berdasarkan bulan di tahun ini
        $grafikRaw = Transaksi::selectRaw('MONTH(created_at) as bulan, SUM(total_bayar) as total')
            ->whereYear('created_at', date('Y'))
            ->whereIn('status', ['success', 'settlement'])
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        // Format data biar frontend tinggal pakai (Array 12 bulan)
        $grafikPendapatan = array_fill(0, 12, 0); // Siapkan array [0,0,0...] 12 biji
        
        foreach ($grafikRaw as $data) {
            // Index array mulai dari 0, sedangkan bulan 1-12. Jadi dikurang 1.
            $grafikPendapatan[$data->bulan - 1] = (int) $data->total;
        }

        return response()->json([
            'total_alat' => $totalAlat,
            'pendapatan' => $pendapatan,
            'perlu_diproses' => $perluDiproses,
            'transaksi_terbaru' => $transaksiTerbaru,
            'grafik_per_bulan' => $grafikPendapatan 
        ]);
    }
}