<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Transaksi;
use App\Models\TransaksiItem;
use App\Models\AlatBand;

// Import Library Midtrans
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class MidtransController extends Controller
{
    /**
     * Konfigurasi awal Midtrans
     */
    public function __construct()
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = (bool) env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * 1. Membuat Snap Token untuk Frontend (Dengan Cek Stok)
     */
    public function createTransaction(Request $request)
    {
        // Validasi Input dari Frontend
        $request->validate([
            'order_id' => 'required|string',
            'gross_amount' => 'required|numeric|min:1',
            'customer_name' => 'required|string',
            'customer_email' => 'required|email',
            'customer_phone' => 'required|string',
            
            // Data wajib untuk cek ketersediaan
            'alat_id' => 'required|exists:alat_bands,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'jumlah' => 'required|integer|min:1', 
        ]);

        // --- MULAI LOGIKA CEK KETERSEDIAAN ---
        $tersedia = $this->isAlatAvailable(
            $request->alat_id,
            $request->tanggal_mulai,
            $request->tanggal_selesai,
            $request->jumlah
        );

        // Jika tidak tersedia, kirim Error 400 (Bad Request)
        if (!$tersedia) {
            return response()->json([
                'success' => false,
                'message' => 'Mohon maaf, stok alat tidak mencukupi pada tanggal yang dipilih. Silakan pilih tanggal lain.'
            ], 400);
        }
        // --- SELESAI LOGIKA CEK ---

        // Parameter Midtrans
        $params = [
            "transaction_details" => [
                "order_id" => $request->order_id,
                "gross_amount" => (int) $request->gross_amount,
            ],
            "customer_details" => [
                'first_name' => $request->customer_name,
                'email' => $request->customer_email,
                'phone' => $request->customer_phone,
            ],
            // Expire dalam 1 jam
            "custom_expiry" => [
                "start_time" => date("Y-m-d H:i:s O"),
                "unit" => "hour",
                "duration" => 1
            ]
        ];

        try {
            // Generate Snap Token
            $snapToken = Snap::getSnapToken($params);

            return response()->json([
                'success' => true,
                'snap_token' => $snapToken
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * 2. Webhook / Callback Midtrans
     */
    public function callback(Request $request)
    {
        try {
            $notif = $request->all();
            Log::info('Midtrans Notification:', $notif);

            $serverKey = config('midtrans.server_key') ?? env('MIDTRANS_SERVER_KEY');
            $signatureKey = hash('sha512', $notif['order_id'] . $notif['status_code'] . $notif['gross_amount'] . $serverKey);
            
            if ($signatureKey != $notif['signature_key']) {
                return response()->json(['message' => 'Invalid signature'], 403);
            }

            $transaksi = Transaksi::where('kode_transaksi', $notif['order_id'])->first();

            if (!$transaksi) {
                return response()->json(['message' => 'Transaction not found'], 404);
            }

            $transactionStatus = $notif['transaction_status'];
            $type = $notif['payment_type'];
            $fraud = $notif['fraud_status'];
            $newStatus = $transaksi->status;

            if ($transactionStatus == 'capture') {
                if ($type == 'credit_card') {
                    $newStatus = ($fraud == 'challenge') ? 'pending' : 'success';
                }
            } else if ($transactionStatus == 'settlement') {
                $newStatus = 'success';
            } else if ($transactionStatus == 'pending') {
                $newStatus = 'pending';
            } else if ($transactionStatus == 'deny') {
                $newStatus = 'failed';
            } else if ($transactionStatus == 'expire') {
                $newStatus = 'expired';
            } else if ($transactionStatus == 'cancel') {
                $newStatus = 'cancelled';
            }

            if ($newStatus != $transaksi->status) {
                $transaksi->status = $newStatus;
                $transaksi->save();

                if (in_array($newStatus, ['failed', 'expired', 'cancelled'])) {
                    $this->restoreStock($transaksi->id);
                }
            }

            return response()->json(['message' => 'Notification processed']);

        } catch (\Exception $e) {
            Log::error("Midtrans Callback Error: " . $e->getMessage());
            return response()->json(['message' => 'Error processing notification'], 500);
        }
    }

    /**
     * 3. Helper: Restore Stock (Dipanggil saat Transaksi Gagal)
     */
    private function restoreStock($transaksiId)
    {
        $items = TransaksiItem::where('transaksi_id', $transaksiId)->get();

        foreach ($items as $item) {
            $alat = AlatBand::find($item->alat_id);
            if ($alat) {
                $alat->stok += $item->jumlah;
                if ($alat->stok > 0) {
                    $alat->status = 'Tersedia';
                }
                $alat->save();
            }
        }
    }

    /**
     * 4. Helper: Cek Ketersediaan Alat (Dipanggil saat createTransaction)
     */
    private function isAlatAvailable($alatId, $tglMulai, $tglSelesai, $jumlahDiminta)
    {
        // Ambil data alat
        $alat = AlatBand::find($alatId);
        if (!$alat) return false;

        $stokTotal = $alat->stok;

        // Hitung stok yang sedang terpakai di rentang tanggal tersebut
        $stokTerpakai = TransaksiItem::where('alat_id', $alatId)
            ->whereHas('transaksi', function($query) {
                // Hanya hitung transaksi yang aktif (pending/success)
                $query->whereIn('status', ['pending', 'success', 'settlement']);
            })
            ->where(function($query) use ($tglMulai, $tglSelesai) {
                // Logika Bentrok Tanggal (Overlap)
                $query->where(function($q) use ($tglMulai, $tglSelesai) {
                    $q->whereBetween('tanggal_mulai', [$tglMulai, $tglSelesai])
                      ->orWhereBetween('tanggal_selesai', [$tglMulai, $tglSelesai]);
                })
                ->orWhere(function($q) use ($tglMulai, $tglSelesai) {
                    $q->where('tanggal_mulai', '<=', $tglMulai)
                      ->where('tanggal_selesai', '>=', $tglSelesai);
                });
            })
            ->sum('jumlah');

        // Cek apakah sisa stok cukup
        $sisaStok = $stokTotal - $stokTerpakai;

        return $sisaStok >= $jumlahDiminta;
    }
}