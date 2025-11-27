<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\TransaksiItem;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Facades\Log;

class TransaksiController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'nama'              => 'required|string|max:255',
            'telepon'           => 'required|string|max:20',
            'alamat'            => 'required|string',
            'deskripsi_lokasi'  => 'nullable|string',
            'lat'               => 'required|numeric',
            'lon'               => 'required|numeric',
            'jarak_km'          => 'required|numeric',
            'metode_pengiriman' => 'required|in:ambil,antar',
            'tarif_antar'       => 'required|integer',
            'total_sewa'        => 'required|integer',
            'total_bayar'       => 'required|integer',
            'identitas'         => 'required|file|mimes:jpg,jpeg,png,pdf|max:4096',
            'items'             => 'required',
        ]);

        // 2. Decode items & Validasi
        $items = json_decode($request->items, true);
        if (!$items || !is_array($items) || count($items) === 0) {
            return response()->json(['success' => false, 'message' => 'List item tidak valid'], 422);
        }

        try {
            // 3. Upload identitas
            $identitasPath = $request->file('identitas')->store('identitas', 'public');

            // 4. Generate Kode Transaksi Unik
            $kodeTransaksi = 'TRX-' . strtoupper(substr(uniqid(), -6));

            // 5. Simpan transaksi ke Database
            $transaksi = Transaksi::create([
                'kode_transaksi'    => $kodeTransaksi,
                'nama'              => $request->nama,
                'telepon'           => $request->telepon,
                'alamat'            => $request->alamat,
                'deskripsi_lokasi'  => $request->deskripsi_lokasi,
                'lat'               => $request->lat,
                'lon'               => $request->lon,
                'jarak_km'          => $request->jarak_km,
                'metode_pengiriman' => $request->metode_pengiriman,
                'tarif_antar'       => $request->tarif_antar,
                'total_sewa'        => $request->total_sewa,
                'total_bayar'       => $request->total_bayar,
                'identitas'         => $identitasPath,
                'bukti_bayar'       => null,
                'status'            => 'pending',
            ]);

            // 6. Simpan detail item transaksi
            foreach ($items as $item) {
                $mulai   = new \DateTime($item['tanggalMulai']);
                $selesai = new \DateTime($item['tanggalSelesai']);
                $lama    = $mulai->diff($selesai)->days;
                if ($lama < 1) $lama = 1;

                $subtotal = $lama * $item['harga_sewa'] * $item['jumlah'];

                TransaksiItem::create([
                    'transaksi_id'    => $transaksi->id,
                    'alat_id'         => $item['id'],
                    'nama_alat'       => $item['nama_alat'],
                    'harga_sewa'      => $item['harga_sewa'],
                    'jumlah'          => $item['jumlah'],
                    'tanggal_mulai'   => $item['tanggalMulai'],
                    'tanggal_selesai' => $item['tanggalSelesai'],
                    'lama_hari'       => $lama,
                    'subtotal'        => $subtotal,
                ]);
            }

            // 7. KONFIGURASI MIDTRANS (OTOMATIS DARI .ENV)
            // Jangan di-hardcode string! Biarkan env yang mengurusnya.
            Config::$serverKey    = env('MIDTRANS_SERVER_KEY');
            Config::$isProduction = (bool) env('MIDTRANS_IS_PRODUCTION', false);
            Config::$isSanitized  = true;
            Config::$is3ds        = true;

            // Cek apakah key terbaca (Safety Check)
            if (empty(Config::$serverKey)) {
                throw new \Exception("Server Key Midtrans belum diisi di .env!");
            }

            // Parameter untuk Midtrans
            $midtransParams = [
                "transaction_details" => [
                    "order_id"     => $kodeTransaksi,
                    "gross_amount" => (int) $transaksi->total_bayar,
                ],
                "customer_details" => [
                    "first_name" => $transaksi->nama,
                    "phone"      => $transaksi->telepon,
                    "billing_address" => [
                        "address" => $transaksi->alamat,
                    ],
                ],
            ];

            // 8. Minta Snap Token ke Midtrans
            $snapToken = Snap::getSnapToken($midtransParams);

            // 9. UPDATE DATABASE: Simpan Snap Token
            $transaksi->update([
                'snap_token' => $snapToken
            ]);

            // 10. Response Sukses
            return response()->json([
                'success'        => true,
                'message'        => 'Transaksi berhasil dibuat!',
                'snap_token'     => $snapToken,
                'kode_transaksi' => $kodeTransaksi,
                'data'           => $transaksi->load('items'),
            ]);

        } catch (\Throwable $e) {
            // Log error biar gampang debugging
            Log::error('Error Transaksi Store: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses transaksi: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function riwayat($telepon)
    {
        $transaksi = Transaksi::where('telepon', $telepon)
            ->orderBy('created_at', 'DESC')
            ->with('items')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $transaksi,
        ]);
    }
}