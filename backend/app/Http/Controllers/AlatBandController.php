<?php

namespace App\Http\Controllers;

use App\Models\AlatBand;
use Illuminate\Http\Request;

class AlatBandController extends Controller
{
    // 🔹 DASHBOARD
    public function dashboard()
    {
        $totalAlat = AlatBand::count();
        $totalTersedia = AlatBand::where('status', 'Tersedia')->count();
        $totalDisewa = AlatBand::where('status', 'Disewa')->count();
        $totalPerbaikan = AlatBand::where('status', 'Dalam Perbaikan')->count();

        return view('dashboard', compact('totalAlat', 'totalTersedia', 'totalDisewa', 'totalPerbaikan'));
    }

    // 🔹 INDEX ADMIN
    public function index()
    {
        $alatBand = AlatBand::all();
        return view('alat-band.index', compact('alatBand'));
    }

    // 🔹 CREATE FORM
    public function create()
    {
        return view('alat-band.create');
    }

    // 🔹 STORE DATA
    public function store(Request $request)
    {
        $request->validate([
            'nama_alat'   => 'required|string|max:255',
            'kategori'    => 'required|string',
            'stok'        => 'required|integer|min:0',
            'harga_sewa'  => 'required|numeric|min:0',
            'deskripsi'   => 'nullable|string',
            'gambar'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status'      => 'required|in:Tersedia,Disewa,Dalam Perbaikan',
        ]);

        $data = $request->all();

        // Upload gambar jika ada
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            if (!file_exists(public_path('images/alat-band'))) {
                mkdir(public_path('images/alat-band'), 0755, true);
            }

            $file->move(public_path('images/alat-band'), $filename);
            $data['gambar'] = 'images/alat-band/' . $filename;
        }

        AlatBand::create($data);

        return redirect()->route('alat-band.index')->with('success', 'Alat band berhasil ditambahkan!');
    }

    // 🔹 SHOW DETAIL (VIEW ADMIN)
    public function show($id)
    {
        $alat = AlatBand::findOrFail($id);
        return view('alat-band.show', compact('alat'));
    }

    // 🔹 EDIT FORM
    public function edit($id)
    {
        $alat = AlatBand::findOrFail($id);
        return view('alat-band.edit', compact('alat'));
    }

    // 🔹 UPDATE DATA
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_alat'   => 'required|string|max:255',
            'kategori'    => 'required|string',
            'stok'        => 'required|integer|min:0',
            'harga_sewa'  => 'required|numeric|min:0',
            'deskripsi'   => 'nullable|string', // ✅ Tambahkan validasi deskripsi
            'gambar'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status'      => 'required|in:Tersedia,Disewa,Dalam Perbaikan',
        ]);

        $alat = AlatBand::findOrFail($id);
        $data = $request->all();

        // Upload gambar baru jika ada
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama
            if (!empty($alat->gambar) && file_exists(public_path($alat->gambar))) {
                unlink(public_path($alat->gambar));
            }

            $file = $request->file('gambar');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            if (!file_exists(public_path('images/alat-band'))) {
                mkdir(public_path('images/alat-band'), 0755, true);
            }

            $file->move(public_path('images/alat-band'), $filename);
            $data['gambar'] = 'images/alat-band/' . $filename;
        }

        $alat->update($data);

        return redirect()->route('alat-band.index')->with('success', 'Alat band berhasil diupdate!');
    }

    // 🔹 HAPUS DATA
    public function destroy($id)
    {
        $alat = AlatBand::findOrFail($id);

        if (!empty($alat->gambar) && file_exists(public_path($alat->gambar))) {
            unlink(public_path($alat->gambar));
        }

        $alat->delete();

        return redirect()->route('alat-band.index')->with('success', 'Alat band berhasil dihapus!');
    }

    // 🔹 API UNTUK FRONTEND VUE
    public function apiIndex()
    {
        $alatBand = AlatBand::select('id', 'nama_alat', 'kategori', 'harga_sewa', 'status', 'gambar', 'deskripsi') // ✅ tambahkan deskripsi
            ->orderBy('id', 'desc')
            ->get();

        return response()->json($alatBand);
    }

    // 🔹 API DETAIL SATU PRODUK
    public function apiShow($id)
    {
        $alat = AlatBand::findOrFail($id);

        return response()->json([
            'id' => $alat->id,
            'nama_alat' => $alat->nama_alat,
            'kategori' => $alat->kategori,
            'harga_sewa' => (int) $alat->harga_sewa,
            'status' => $alat->status,
            'gambar' => asset($alat->gambar),   //🔥 INI WAJIB
            'deskripsi' => $alat->deskripsi,
            'stok' => (int) $alat->stok,
            'created_at' => $alat->created_at->format('d-m-Y H:i')
        ]);
    }


}
