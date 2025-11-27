<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // Tambahkan ini
use Illuminate\Support\Facades\Validator; // Tambahkan ini
use App\Models\User;

class AuthController extends Controller
{
    // ===========================
    // REGISTER (UMUM)
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

        $user = User::create([
            'name'           => $request->nama_lengkap, // Simpan ke kolom name juga
            'nama_lengkap'   => $request->nama_lengkap,
            'email'          => $request->email,
            'nomor_telepon'  => $request->nomor_telepon,
            'password'       => Hash::make($request->password),
            'role'           => 'buyer', // Default daftar sendiri = buyer
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Registrasi berhasil!',
            'user'    => $user,
            'token'   => $token,
        ], 201);
    }

    // ===========================
    // LOGIN (BISA ADMIN & BUYER)
    // ===========================
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Email atau password salah'
            ], 401);
        }

        // ✅ PENGECEKAN ROLE DIHAPUS
        // Sekarang Admin bisa login lewat sini. 
        // Frontend yang akan menentukan redirect ke /admin atau /home

        // Buat Token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil!',
            'token'   => $token,
            'user'    => $user // Data user (termasuk role) dikirim ke frontend
        ]);
    }

    // ===========================
    // GET PROFILE
    // ===========================
    public function profile(Request $request)
    {
        return response()->json([
            'user' => $request->user(),
        ]);
    }

    // ===========================
    // UPDATE PROFILE
    // ===========================
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'nama_lengkap'   => 'required|string|max:255',
            'nomor_telepon'  => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

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