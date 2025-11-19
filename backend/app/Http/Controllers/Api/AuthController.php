<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    // ===========================
    // REGISTER BUYER
    // ===========================
    public function registerBuyer(Request $request)
    {
        $request->validate([
            'nama_lengkap'   => 'required|string|max:255',
            'email'          => 'required|email|unique:users,email',
            'nomor_telepon'  => 'required|string|max:20',
            'password'       => 'required|min:6',
        ]);

        $user = User::create([
            'name'           => $request->nama_lengkap,
            'nama_lengkap'   => $request->nama_lengkap,
            'email'          => $request->email,
            'nomor_telepon'  => $request->nomor_telepon,
            'password'       => bcrypt($request->password),
            'role'           => 'buyer',
        ]);

        return response()->json([
            'message' => 'Registrasi berhasil!',
            'user'    => $user,
        ], 201);
    }

    // ===========================
    // LOGIN BUYER
    // ===========================
    public function loginBuyer(Request $request)
    {
        $request->validate([
            'email'     => 'required|email',
            'password'  => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Email atau password salah'
            ], 401);
        }

        $user = User::where('email', $request->email)->first();

        // Hanya buyer boleh login API ini
        if ($user->role !== 'buyer') {
            return response()->json([
                'message' => 'Akun ini bukan buyer'
            ], 403);
        }

        // Sanctum token
        $token = $user->createToken('buyer_token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil!',
            'token'   => $token,
            'user'    => $user
        ]);
    }

    // ===========================
    // GET PROFILE BUYER
    // ===========================
    public function profile(Request $request)
    {
        return response()->json([
            'user' => $request->user(),
        ]);
    }

    // ===========================
    // UPDATE PROFILE BUYER
    // ===========================
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'nama_lengkap'   => 'required|string|max:255',
            'nomor_telepon'  => 'required|string|max:20',
        ]);

        $user->update([
            'nama_lengkap'   => $request->nama_lengkap,
            'name'           => $request->nama_lengkap,
            'nomor_telepon'  => $request->nomor_telepon,
        ]);

        return response()->json([
            'message' => 'Profil berhasil diperbarui',
            'user'    => $user
        ]);
    }

    // ===========================
    // LOGOUT
    // ===========================
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil'
        ]);
    }
}
