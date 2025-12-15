<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Cek apakah user sudah login & punya role 'admin'
        if ($request->user() && $request->user()->role === 'admin') {
            return $next($request); // Silakan lewat
        }

        // 2. Jika bukan admin, tolak akses (403 Forbidden)
        return response()->json([
            'success' => false,
            'message' => 'Akses Ditolak! Anda bukan Admin.'
        ], 403);
    }
}