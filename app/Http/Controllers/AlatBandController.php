<?php

namespace App\Http\Controllers;

use App\Models\AlatBand;
use Illuminate\Http\Request;

class AlatBandController extends Controller
{
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
        $alatBand = AlatBand::all();
        return view('alat-band.index', compact('alatBand'));
    }

    public function create()
    {
        return view('alat-band.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_alat' => 'required|string|max:255',
            'kategori' => 'required|string',
            'stok' => 'required|integer|min:0',
            'harga_sewa' => 'required|numeric|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:Tersedia,Disewa,Dalam Perbaikan',
        ]);

        $data = $request->all();

        // Handle file upload
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            // Buat folder jika belum ada
            if (!file_exists(public_path('images/alat-band'))) {
                mkdir(public_path('images/alat-band'), 0755, true);
            }

            $file->move(public_path('images/alat-band'), $filename);
            $data['gambar'] = 'images/alat-band/' . $filename;
        }

        AlatBand::create($data);

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
        $request->validate([
            'nama_alat' => 'required|string|max:255',
            'kategori' => 'required|string',
            'stok' => 'required|integer|min:0',
            'harga_sewa' => 'required|numeric|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:Tersedia,Disewa,Dalam Perbaikan',
        ]);

        $alat = AlatBand::findOrFail($id);
        $data = $request->all();

        // Handle file upload
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if (!empty($alat->gambar) && file_exists(public_path($alat->gambar))) {
                unlink(public_path($alat->gambar));
            }

            $file = $request->file('gambar');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            // Buat folder jika belum ada
            if (!file_exists(public_path('images/alat-band'))) {
                mkdir(public_path('images/alat-band'), 0755, true);
            }

            $file->move(public_path('images/alat-band'), $filename);
            $data['gambar'] = 'images/alat-band/' . $filename;
        }

        $alat->update($data);

        return redirect()->route('alat-band.index')->with('success', 'Alat band berhasil diupdate!');
    }

    public function destroy($id)
    {
        $alat = AlatBand::findOrFail($id);

        // Hapus gambar jika ada
        if (!empty($alat->gambar) && file_exists(public_path($alat->gambar))) {
            unlink(public_path($alat->gambar));
        }

        $alat->delete();

        return redirect()->route('alat-band.index')->with('success', 'Alat band berhasil dihapus!');
    }
}