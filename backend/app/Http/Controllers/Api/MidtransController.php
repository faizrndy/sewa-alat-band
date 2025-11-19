<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Midtrans\Notification;
use App\Models\Transaksi;

class MidtransController extends Controller
{
    public function callback(Request $request)
    {
        // Ambil notifikasi pembayaran dari Midtrans
        $notification = new Notification();

        $status = $notification->transaction_status;
        $type = $notification->payment_type;
        $orderId = $notification->order_id;
        $fraud = $notification->fraud_status ?? null;

        // Ambil transaksi dari DB
        $transaksi = Transaksi::where('order_id', $orderId)->first();

        if (!$transaksi) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan'
            ], 404);
        }

        /* ------------------- LOGIKA UPDATE STATUS ------------------- */

        if ($status == 'capture') {
            if ($type == 'credit_card') {
                if ($fraud == 'challenge') {
                    $transaksi->status = 'pending';
                } else {
                    $transaksi->status = 'paid';
                }
            }
        }

        else if ($status == 'settlement') {
            $transaksi->status = 'paid';
        }

        else if ($status == 'pending') {
            $transaksi->status = 'pending';
        }

        else if ($status == 'deny') {
            $transaksi->status = 'failed';
        }

        else if ($status == 'expire') {
            $transaksi->status = 'expired';
        }

        else if ($status == 'cancel') {
            $transaksi->status = 'cancelled';
        }

        // Simpan perubahan
        $transaksi->save();

        return response()->json([
            'success' => true,
            'message' => 'Callback processed successfully',
            'status' => $transaksi->status
        ]);
    }
}
