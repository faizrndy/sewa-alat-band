<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::latest()->get();
        return view('review.index', compact('reviews')); // ✅ ubah ke 'review.index'
    }

    public function create()
    {
        return view('review.create'); // ✅ ubah ke 'review.create'
    }

    public function upload(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $file = $request->file('gambar');
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        // buat folder public/images/review
        if (!file_exists(public_path('images/review'))) {
            mkdir(public_path('images/review'), 0755, true);
        }

        $file->move(public_path('images/review'), $filename);

        Review::create([
            'nama_pelanggan' => $request->nama_pelanggan,
            'gambar' => 'images/review/' . $filename,
        ]);

        return redirect()->route('review.index')->with('success', 'Review berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        if (file_exists(public_path($review->gambar))) {
            unlink(public_path($review->gambar));
        }
        $review->delete();

        return redirect()->route('review.index')->with('success', 'Review berhasil dihapus!');
    }
}
