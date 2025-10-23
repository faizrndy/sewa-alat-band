<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AlatBandController extends Controller
{
    public function dashboard()
    {
        $alatBand = session('alat_band', []);
        $totalAlat = count($alatBand);
        $totalTersedia = count(array_filter($alatBand, fn($alat) => $alat['status'] == 'Tersedia'));
        $totalDisewa = count(array_filter($alatBand, fn($alat) => $alat['status'] == 'Disewa'));
        $totalPerbaikan = count(array_filter($alatBand, fn($alat) => $alat['status'] == 'Dalam Perbaikan'));

        return view('dashboard', compact('totalAlat', 'totalTersedia', 'totalDisewa', 'totalPerbaikan'));
    }

    public function index()
    {
        if (!session()->has('alat_band')) {
            session(['alat_band' => $this->getDefaultData()]);
        }
        $alatBand = session('alat_band');
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

        $alatBand = session('alat_band', []);
        $newId = empty($alatBand) ? 1 : max(array_keys($alatBand)) + 1;

        // Handle file upload
        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            // Buat folder jika belum ada
            if (!file_exists(public_path('images/alat-band'))) {
                mkdir(public_path('images/alat-band'), 0755, true);
            }

            $file->move(public_path('images/alat-band'), $filename);
            $gambarPath = 'images/alat-band/' . $filename;
        }

        $alatBand[$newId] = [
            'id' => $newId,
            'nama_alat' => $request->nama_alat,
            'kategori' => $request->kategori,
            'stok' => $request->stok,
            'harga_sewa' => $request->harga_sewa,
            'gambar' => $gambarPath,
            'status' => $request->status,
            'created_at' => now()->format('Y-m-d H:i:s'),
        ];

        session(['alat_band' => $alatBand]);

        return redirect()->route('alat-band.index')->with('success', 'Alat band berhasil ditambahkan!');
    }

    public function show(string $id)
    {
        $alatBand = session('alat_band', []);

        if (!isset($alatBand[$id])) {
            return redirect()->route('alat-band.index')->with('error', 'Data tidak ditemukan!');
        }

        $alat = $alatBand[$id];
        return view('alat-band.show', compact('alat'));
    }

    public function edit(string $id)
    {
        $alatBand = session('alat_band', []);

        if (!isset($alatBand[$id])) {
            return redirect()->route('alat-band.index')->with('error', 'Data tidak ditemukan!');
        }

        $alat = $alatBand[$id];
        return view('alat-band.edit', compact('alat'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_alat' => 'required|string|max:255',
            'kategori' => 'required|string',
            'stok' => 'required|integer|min:0',
            'harga_sewa' => 'required|numeric|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:Tersedia,Disewa,Dalam Perbaikan',
        ]);

        $alatBand = session('alat_band', []);

        if (!isset($alatBand[$id])) {
            return redirect()->route('alat-band.index')->with('error', 'Data tidak ditemukan!');
        }

        // Handle file upload
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if (!empty($alatBand[$id]['gambar']) && file_exists(public_path($alatBand[$id]['gambar']))) {
                unlink(public_path($alatBand[$id]['gambar']));
            }

            $file = $request->file('gambar');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            // Buat folder jika belum ada
            if (!file_exists(public_path('images/alat-band'))) {
                mkdir(public_path('images/alat-band'), 0755, true);
            }

            $file->move(public_path('images/alat-band'), $filename);
            $alatBand[$id]['gambar'] = 'images/alat-band/' . $filename;
        }

        $alatBand[$id]['nama_alat'] = $request->nama_alat;
        $alatBand[$id]['kategori'] = $request->kategori;
        $alatBand[$id]['stok'] = $request->stok;
        $alatBand[$id]['harga_sewa'] = $request->harga_sewa;
        $alatBand[$id]['status'] = $request->status;

        session(['alat_band' => $alatBand]);

        return redirect()->route('alat-band.index')->with('success', 'Alat band berhasil diupdate!');
    }

    public function destroy(string $id)
    {
        $alatBand = session('alat_band', []);

        if (!isset($alatBand[$id])) {
            return redirect()->route('alat-band.index')->with('error', 'Data tidak ditemukan!');
        }

        // Hapus gambar jika ada
        if (!empty($alatBand[$id]['gambar']) && file_exists(public_path($alatBand[$id]['gambar']))) {
            unlink(public_path($alatBand[$id]['gambar']));
        }

        unset($alatBand[$id]);
        session(['alat_band' => $alatBand]);

        return redirect()->route('alat-band.index')->with('success', 'Alat band berhasil dihapus!');
    }

    private function getDefaultData()
    {
        return [
            1 => [
                'id' => 1,
                'nama_alat' => 'Gitar Elektrik Fender',
                'kategori' => 'Gitar',
                'stok' => 5,
                'harga_sewa' => 150000,
                'gambar' => null,
                'status' => 'Tersedia',
                'created_at' => now()->format('Y-m-d H:i:s'),
            ],
            2 => [
                'id' => 2,
                'nama_alat' => 'Drum Set Pearl',
                'kategori' => 'Drum',
                'stok' => 2,
                'harga_sewa' => 300000,
                'gambar' => null,
                'status' => 'Disewa',
                'created_at' => now()->format('Y-m-d H:i:s'),
            ],
            3 => [
                'id' => 3,
                'nama_alat' => 'Bass Ibanez',
                'kategori' => 'Bass',
                'stok' => 3,
                'harga_sewa' => 120000,
                'gambar' => null,
                'status' => 'Dalam Perbaikan',
                'created_at' => now()->format('Y-m-d H:i:s'),
            ],
        ];
    }
}