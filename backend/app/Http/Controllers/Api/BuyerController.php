<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class BuyerController extends Controller
{
    public function profile(Request $request)
    {
        return response()->json([
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nomor_telepon' => 'required|string|max:20',
        ]);

        $user = $request->user();
        $user->nama_lengkap = $request->nama_lengkap;
        $user->nomor_telepon = $request->nomor_telepon;
        $user->save();

        return response()->json([
            'message' => 'Profil berhasil diupdate',
            'user' => $user
        ]);
    }
}
