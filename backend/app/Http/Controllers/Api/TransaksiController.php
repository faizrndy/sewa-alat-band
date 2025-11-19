<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\TransaksiItem;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;

class TransaksiController extends Controller
{
    /**
     * SIMPAN TRANSAKSI + GENERATE SNAP TOKEN
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama'               => 'required|string|max:255',
            'telepon'            => 'required|string|max:20',
            'alamat'             => 'required|string',
            'deskripsi_lokasi'   => 'nullable|string',

            'lat'                => 'required|numeric',
            'lon'                => 'required|numeric',
            'jarak_km'           => 'required|numeric',

            'metode_pengiriman'  => 'required|in:ambil,antar',
            'tarif_antar'        => 'required|integer',

            'total_sewa'         => 'required|integer',
            'total_bayar'        => 'required|integer',

            'identitas'          => 'required|file|mimes:jpg,jpeg,png,pdf|max:4096',

            'items'              => 'required',
        ]);

        // Decode items
        $items = json_decode($request->items, true);
        if (!$items || !is_array($items) || count($items) === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Items tidak valid'
            ], 422);
        }

        // Upload identitas
        $identitasPath = $request->file('identitas')->store('identitas', 'public');

        // Generate Kode Transaksi
        $kodeTransaksi = 'TRX-' . strtoupper(substr(uniqid(), -6));

        // Simpan transaksi induk
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
            'bukti_bayar'       => '',

            'status'            => 'pending', // awalnya pending
        ]);

        // Simpan item transaksi
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

        /**
         * PROSES MIDTRANS SNAP TOKEN
         */
        try {
            Config::$serverKey    = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production');
            Config::$isSanitized  = true;
            Config::$is3ds        = true;

            $midtransParams = [
                "transaction_details" => [
                    "order_id"     => $kodeTransaksi,
                    "gross_amount" => $transaksi->total_bayar,
                ],
                "customer_details" => [
                    "first_name" => $transaksi->nama,
                    "phone"      => $transaksi->telepon,
                    "billing_address" => [
                        "address" => $transaksi->alamat,
                    ],
                ],
                "enabled_payments" => ["gopay", "qris", "bank_transfer", "shopeepay"],
            ];

            $snapToken = Snap::getSnapToken($midtransParams);

        } catch (\Throwable $e) {

            \Log::error('Midtrans error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat Snap Token Midtrans',
                'error'   => $e->getMessage(),
            ], 500);
        }

        // Response sukses
        return response()->json([
            'success'        => true,
            'message'        => 'Transaksi berhasil dibuat!',
            'snap_token'     => $snapToken,
            'kode_transaksi' => $kodeTransaksi,
            'data'           => $transaksi->load('items'),
        ]);
    }

    /**
     * GET RIWAYAT PEMESANAN BERDASARKAN NOMOR TELEPON
     */
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
