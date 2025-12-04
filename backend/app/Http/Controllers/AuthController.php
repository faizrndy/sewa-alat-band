<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
            'captcha'  => 'required'   // WAJIB!
        ]);

        // Validasi CAPTCHA ke Google API
        $response = Http::asForm()->post("https://www.google.com/recaptcha/api/siteverify", [
            'secret'   => env('RECAPTCHA_SECRET_KEY'),
            'response' => $request->captcha,
            'remoteip' => $request->ip(),
        ]);

        if (!$response->json('success')) {
            return response()->json([
                'message' => 'Captcha tidak valid!'
            ], 422);
        }

        // Cari user berdasarkan email
        $user = User::where('email', $request->email)->first();

        // Jika user tidak ditemukan atau password salah
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Email atau password salah.',
            ], 401);
        }

        // Buat token
        $token = $user->createToken('auth_token')->plainTextToken;

        // Berhasil
        return response()->json([
            'message' => 'Login berhasil!',
            'token'   => $token,
            'user'    => $user,
        ], 200);
    }
}
