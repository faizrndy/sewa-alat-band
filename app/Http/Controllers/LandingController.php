<?php

namespace App\Http\Controllers;

use App\Models\AlatBand;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    // Halaman utama landing page
    public function index()
    {
        $products = AlatBand::where('status', 'Tersedia')
                            ->latest()
                            ->take(8)
                            ->get();

        // Hardcode semua kategori yang tersedia
        $categories = collect(['Gitar', 'Bass', 'Drum', 'Keyboard', 'Amplifier', 'Microphone', 'Lainnya']);

        $featuredProducts = AlatBand::where('status', 'Tersedia')
                                    ->inRandomOrder()
                                    ->take(4)
                                    ->get();

        return view('welcome', compact('products', 'categories', 'featuredProducts'));
    }

    // Detail produk
    public function show($id)
    {
        $product = AlatBand::findOrFail($id);

        $relatedProducts = AlatBand::where('kategori', $product->kategori)
                                   ->where('id', '!=', $product->id)
                                   ->where('status', 'Tersedia')
                                   ->take(4)
                                   ->get();

        return view('product-detail', compact('product', 'relatedProducts'));
    }

    // Halaman katalog (semua produk)
    public function katalog(Request $request)
    {
        $query = AlatBand::query();

        // Filter berdasarkan kategori jika ada
        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('kategori', $request->kategori);
        }

        // Filter berdasarkan status jika ada
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Search berdasarkan nama
        if ($request->has('search') && $request->search != '') {
            $query->where('nama_alat', 'like', '%' . $request->search . '%');
        }

        $products = $query->latest()->paginate(12);

        // Hardcode semua kategori
        $categories = collect(['Gitar', 'Bass', 'Drum', 'Keyboard', 'Amplifier', 'Microphone', 'Lainnya']);

        return view('katalog', compact('products', 'categories'));
    }

    // Filter berdasarkan kategori
    public function byKategori($kategori)
    {
        $products = AlatBand::where('kategori', $kategori)
                            ->where('status', 'Tersedia')
                            ->latest()
                            ->paginate(12);

        // Hardcode semua kategori (sama seperti method lain)
        $categories = collect(['Gitar', 'Bass', 'Drum', 'Keyboard', 'Amplifier', 'Microphone', 'Lainnya']);

        return view('katalog', compact('products', 'categories', 'kategori'));
    }
}
