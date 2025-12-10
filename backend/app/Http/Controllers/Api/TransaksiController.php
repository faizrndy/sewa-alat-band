<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\TransaksiItem;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TransaksiController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'nama' => 'required|string|max:255',
            'telepon' => 'required|string',
            'alamat' => 'required|string',
            'metode_pengiriman' => 'required|in:ambil,antar',
            'total_bayar' => 'required|numeric',
            'items' => 'required', // JSON String
            'identitas' => 'required|file|mimes:jpg,jpeg,png,pdf|max:4096',
            
            // 🔥 VALIDASI TANGGAL WAJIB ADA
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date',
        ]);

        DB::beginTransaction(); // Pakai Transaction biar aman

        try {
            // 2. Upload Identitas
            $identitasPath = null;
            if ($request->hasFile('identitas')) {
                $file = $request->file('identitas');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('identitas'), $filename);
                $identitasPath = 'identitas/' . $filename;
            }

            // 3. Generate Kode TRX
            $kodeTransaksi = 'TRX-' . strtoupper(substr(uniqid(), -6));

            // 4. Hitung Lama Sewa (Backend Side Calculation)
            $start = new \DateTime($request->tgl_mulai);
            $end = new \DateTime($request->tgl_selesai);
            $diff = $start->diff($end);
            $lamaHari = $diff->days;
            if ($lamaHari < 1) $lamaHari = 1; // Minimal 1 hari

            // 5. SIMPAN KE DATABASE (BAGIAN PENTING)
            $transaksi = Transaksi::create([
                'kode_transaksi' => $kodeTransaksi,
                'nama' => $request->nama,
                'telepon' => $request->telepon,
                'alamat' => $request->alamat ?? '-',
                'deskripsi_lokasi' => $request->deskripsi_lokasi ?? '-',
                
                // 🔥 INI YANG KEMARIN LUPA DISIMPAN
                'tgl_mulai' => $request->tgl_mulai,     
                'tgl_selesai' => $request->tgl_selesai, 
                'lama_hari' => $lamaHari,               

                'lat' => $request->lat ?? 0,
                'lon' => $request->lon ?? 0,
                'jarak_km' => $request->jarak_km ?? 0,
                'metode_pengiriman' => $request->metode_pengiriman,
                'tarif_antar' => $request->tarif_antar ?? 0,
                'total_sewa' => $request->total_sewa,
                'total_bayar' => $request->total_bayar,
                'identitas' => $identitasPath,
                'status' => 'pending', // Default Pending
            ]);

            // 6. Simpan Detail Barang
            $items = json_decode($request->items, true);
            foreach ($items as $item) {
                // Pastikan qty terisi
                $qty = isset($item['qty']) ? $item['qty'] : (isset($item['jumlah']) ? $item['jumlah'] : 1);
                
                $subtotal = $item['harga_sewa'] * $qty * $lamaHari;

                \App\Models\TransaksiItem::create([
                    'transaksi_id' => $transaksi->id,
                    'alat_id' => $item['id'],
                    'nama_alat' => $item['nama_alat'],
                    'harga_sewa' => $item['harga_sewa'],
                    'jumlah' => $qty,
                    'subtotal' => $subtotal,
                    // Simpan tanggal di detail juga (opsional, tapi bagus buat history)
                    'tanggal_mulai' => $request->tgl_mulai,
                    'tanggal_selesai' => $request->tgl_selesai,
                    'lama_hari' => $lamaHari,
                ]);
            }

            // 7. Config Midtrans & Snap Token
            Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            Config::$isProduction = (bool) env('MIDTRANS_IS_PRODUCTION', false);
            Config::$isSanitized = true;
            Config::$is3ds = true;

            $midtransParams = [
                "transaction_details" => [
                    "order_id" => $kodeTransaksi,
                    "gross_amount" => (int) $transaksi->total_bayar,
                ],
                "customer_details" => [
                    "first_name" => $transaksi->nama,
                    "phone" => $transaksi->telepon,
                ],
            ];

            $snapToken = Snap::getSnapToken($midtransParams);
            $transaksi->update(['snap_token' => $snapToken]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil',
                'snap_token' => $snapToken,
                'kode_transaksi' => $kodeTransaksi
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Gagal Transaksi: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function riwayat($telepon)
    {
        $transaksi = Transaksi::where('telepon', $telepon)
            ->orderBy('created_at', 'DESC')
            ->with('items') // Pastikan relasi di model Transaksi namanya 'items' (hasMany TransaksiItem)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $transaksi,
        ]);
    }
}