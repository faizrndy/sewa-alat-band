<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log; // Untuk logging error

class AuthController extends Controller
{
    // ===========================
    // REGISTER (KIRIM OTP)
    // ===========================
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap'   => 'required|string|max:255',
            'email'          => 'required|email|unique:users,email',
            'nomor_telepon'  => 'required|string|max:20',
            'password'       => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        // Generate OTP
        $otp = rand(100000, 999999);

        // Gunakan try-catch untuk database transaction jika perlu, tapi create biasa sudah cukup
        $user = User::create([
            'name'           => $request->nama_lengkap, // Mapping ke 'name' bawaan Laravel
            // 'nama_lengkap' => $request->nama_lengkap, // Hapus jika tidak ada kolom ini di DB
            'email'          => $request->email,
            'nomor_telepon'  => $request->nomor_telepon,
            'password'       => Hash::make($request->password),
            'role'           => 'buyer',
            'otp'            => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(5),
            'is_verified'    => false,
        ]);

        // Kirim OTP ke email dengan Error Handling
        try {
            Mail::raw("Kode OTP kamu adalah: $otp (berlaku 5 menit)", function ($msg) use ($user) {
                $msg->to($user->email)->subject("Kode OTP Verifikasi Akun");
            });
        } catch (\Exception $e) {
            Log::error("Gagal kirim email OTP: " . $e->getMessage());
            return response()->json([
                'message' => 'Registrasi berhasil, tapi gagal mengirim OTP. Silakan hubungi admin atau coba login untuk resend.',
                'email'   => $user->email, // Tetap return email agar frontend bisa handle
            ], 201); // Tetap 201 karena user terbuat
        }

        return response()->json([
            'message' => 'Registrasi berhasil! OTP telah dikirim ke email.',
            'email'   => $user->email,
        ], 201);
    }


    // ===========================
    // VERIFIKASI OTP
    // ===========================
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp'   => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        // Convert ke integer agar perbandingan sama
        $incomingOtp = (int) $request->otp;

        // Pastikan user->otp tidak null sebelum dicast
        if (!$user->otp || (int)$user->otp !== $incomingOtp) {
            return response()->json(['message' => 'OTP salah'], 422);
        }

        if (Carbon::now()->greaterThan($user->otp_expires_at)) {
            return response()->json(['message' => 'OTP kadaluarsa. Silakan minta kirim ulang.'], 422);
        }

        $user->update([
            'is_verified' => true,
            'otp' => null,
            'otp_expires_at' => null
        ]);

        return response()->json(['message' => 'Verifikasi OTP berhasil! Silakan login.']);
    }

    // ===========================
    // LOGIN (PAKAI CAPTCHA)
    // ===========================
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            // 'g-recaptcha-response' => 'required', // Boleh di-uncomment jika frontend sudah siap
        ]);

        // ======================================
        // LOGIC RECAPTCHA (Opsional: Matikan saat local dev jika sering gagal)
        // ======================================
        if ($request->has('g-recaptcha-response') && config('services.recaptcha.secret')) {
            $response = Http::asForm()->post(
                'https://www.google.com/recaptcha/api/siteverify',
                [
                    'secret' => config('services.recaptcha.secret'),
                    'response' => $request->input('g-recaptcha-response'),
                    'remoteip' => $request->ip(),
                ]
            );

            if (!($response['success'] ?? false)) {
                return response()->json(['message' => 'Captcha tidak valid!'], 422);
            }
        }
        // ======================================

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Email atau password salah'], 401);
        }

        if (! $user->is_verified) {
            return response()->json([
                'message' => 'Akun belum diverifikasi. Silakan cek email untuk OTP.',
                'require_otp' => true // Flag untuk frontend redirect ke halaman OTP
            ], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil!',
            'token' => $token,
            'user' => $user,
        ]);
    }

    // ===========================
    // LOGOUT
    // ===========================
    public function logout(Request $request)
    {
        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();
            return response()->json(['message' => 'Logout berhasil']);
        }
        
        return response()->json(['message' => 'User tidak ditemukan atau sudah logout'], 401);
    }
}