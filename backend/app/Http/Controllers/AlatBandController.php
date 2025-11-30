<?php

namespace App\Http\Controllers;

use App\Models\AlatBand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Gunakan Facade Storage agar lebih aman

class AlatBandController extends Controller
{
    // ==========================================
    // 🟢 HALAMAN WEB ADMIN (BLADE VIEW)
    // ==========================================

    public function dashboard()
    {
        $totalAlat = AlatBand::count();
        $totalTersedia = AlatBand::where('status', 'Tersedia')->count();
        $totalDisewa = AlatBand::where('status', 'Disewa')->count();
        $totalPerbaikan = AlatBand::where('status', 'Dalam Perbaikan')->count();

        return view('dashboard', compact('totalAlat', 'totalTersedia', 'totalDisewa', 'totalPerbaikan'));
    }

    public function index()
    {
        $alatBand = AlatBand::orderBy('created_at', 'desc')->get();
        return view('alat-band.index', compact('alatBand'));
    }

    public function create()
    {
        return view('alat-band.create');
    }

    public function store(Request $request)
    {
        // Validasi
        $request->validate([
            'nama_alat'  => 'required|string|max:255',
            'kategori'   => 'required|string',
            'stok'       => 'required|integer|min:0',
            'harga_sewa' => 'required|numeric|min:0',
            'deskripsi'  => 'nullable|string',
            'gambar'     => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status'     => 'required|in:Tersedia,Disewa,Dalam Perbaikan',
        ]);

        $data = $request->all();

        // Proses Upload Gambar
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            // Simpan ke folder public/images/alat-band
            $file->move(public_path('images/alat-band'), $filename);
            
            // Simpan path relatif ke database
            $data['gambar'] = 'images/alat-band/' . $filename;
        }

        AlatBand::create($data);

        // Jika request dari API (Vue Admin), kembalikan JSON
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Alat berhasil ditambahkan', 'data' => $data]);
        }

        return redirect()->route('alat-band.index')->with('success', 'Alat band berhasil ditambahkan!');
    }

    public function show($id)
    {
        $alat = AlatBand::findOrFail($id);
        return view('alat-band.show', compact('alat'));
    }

    public function edit($id)
    {
        $alat = AlatBand::findOrFail($id);
        return view('alat-band.edit', compact('alat'));
    }

    public function update(Request $request, $id)
    {
        $alat = AlatBand::findOrFail($id);

        $request->validate([
            'nama_alat'  => 'required|string|max:255',
            'kategori'   => 'required|string',
            'stok'       => 'required|integer|min:0',
            'harga_sewa' => 'required|numeric|min:0',
            'deskripsi'  => 'nullable|string',
            'gambar'     => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Nullable: tidak wajib upload ulang
            'status'     => 'required|in:Tersedia,Disewa,Dalam Perbaikan',
        ]);

        $data = $request->except(['gambar']); // Ambil semua data kecuali gambar dulu

        // Proses Upload Gambar Baru (Jika Ada)
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama fisik
            if (!empty($alat->gambar) && file_exists(public_path($alat->gambar))) {
                unlink(public_path($alat->gambar));
            }

            $file = $request->file('gambar');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            $file->move(public_path('images/alat-band'), $filename);
            
            // Masukkan path gambar baru ke data update
            $data['gambar'] = 'images/alat-band/' . $filename;
        }

        $alat->update($data);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Alat berhasil diupdate', 'data' => $alat]);
        }

        return redirect()->route('alat-band.index')->with('success', 'Alat band berhasil diupdate!');
    }

    public function destroy($id)
    {
        $alat = AlatBand::findOrFail($id);

        // Hapus file gambar jika ada
        if (!empty($alat->gambar) && file_exists(public_path($alat->gambar))) {
            unlink(public_path($alat->gambar));
        }

        $alat->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Alat berhasil dihapus']);
        }

        return redirect()->route('alat-band.index')->with('success', 'Alat band berhasil dihapus!');
    }


    // ==========================================
    // 🟢 API ENDPOINTS (UNTUK FRONTEND VUE)
    // ==========================================

    /**
     * API: Get Semua Alat (Untuk Katalog)
     * URL: GET /api/alat-band
     */
    public function apiIndex(Request $request)
    {
        $query = AlatBand::query();

        // Fitur Filter Kategori (Optional)
        if ($request->kategori && $request->kategori != 'Semua Kategori') {
            $query->where('kategori', $request->kategori);
        }

        // Fitur Search (Optional)
        if ($request->search) {
            $query->where('nama_alat', 'like', '%' . $request->search . '%');
        }

        $alatBand = $query->orderBy('created_at', 'desc')->get();

        // Modifikasi data agar URL gambar lengkap (http://localhost:8000/images/...)
        $alatBand->transform(function ($item) {
            $item->gambar = asset($item->gambar);
            $item->harga_sewa = (int) $item->harga_sewa; // Pastikan integer
            return $item;
        });

        return response()->json($alatBand);
    }

    /**
     * API: Get Detail Satu Alat
     * URL: GET /api/alat-band/{id}
     */
    public function apiShow($id)
    {
        $alat = AlatBand::find($id);

        if (!$alat) {
            return response()->json(['message' => 'Alat tidak ditemukan'], 404);
        }

        return response()->json([
            'id' => $alat->id,
            'nama_alat' => $alat->nama_alat,
            'kategori' => $alat->kategori,
            'harga_sewa' => (int) $alat->harga_sewa,
            'status' => $alat->status,
            'gambar' => asset($alat->gambar), // ✅ Generate URL lengkap gambar
            'deskripsi' => $alat->deskripsi,
            'stok' => (int) $alat->stok,
            'created_at' => $alat->created_at->format('d-m-Y H:i')
        ]);
    }
}