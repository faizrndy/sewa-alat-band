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
        $totalPerbaikan = count(array_filter($alatBand, fn($alat) => $alat['status'] == 'Dalam Perbaikan')); // <-- BARIS BARU

        // TAMBAHKAN $totalPerbaikan ke compact()
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
            'jenis' => 'required|string',
            'stok' => 'required|integer|min:0',
            'harga_sewa' => 'required|numeric|min:0',
            'status' => 'required|in:Tersedia,Disewa,Dalam Perbaikan', // <-- UBAH BARIS INI
        ]);

        $alatBand = session('alat_band', []);

        $newId = empty($alatBand) ? 1 : max(array_keys($alatBand)) + 1;

        $alatBand[$newId] = [
            'id' => $newId,
            'nama_alat' => $request->nama_alat,
            'kategori' => $request->kategori,
            'jenis' => $request->jenis,
            'stok' => $request->stok,
            'harga_sewa' => $request->harga_sewa,
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
            'jenis' => 'required|string',
            'stok' => 'required|integer|min:0',
            'harga_sewa' => 'required|numeric|min:0',
            'status' => 'required|in:Tersedia,Disewa,Dalam Perbaikan', // <-- UBAH BARIS INI
        ]);

        $alatBand = session('alat_band', []);

        if (!isset($alatBand[$id])) {
            return redirect()->route('alat-band.index')->with('error', 'Data tidak ditemukan!');
        }

        $alatBand[$id]['nama_alat'] = $request->nama_alat;
        $alatBand[$id]['kategori'] = $request->kategori;
        $alatBand[$id]['jenis'] = $request->jenis;
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
                'jenis' => 'Gitar Elektrik Stratocaster',
                'stok' => 5,
                'harga_sewa' => 150000,
                'status' => 'Tersedia',
                'created_at' => now()->format('Y-m-d H:i:s'),
            ],
            2 => [
                'id' => 2,
                'nama_alat' => 'Drum Set Pearl',
                'kategori' => 'Drum',
                'jenis' => 'Drum Akustik Set',
                'stok' => 2,
                'harga_sewa' => 300000,
                'status' => 'Disewa',
                'created_at' => now()->format('Y-m-d H:i:s'),
            ],
            3 => [
                'id' => 3,
                'nama_alat' => 'Bass Ibanez',
                'kategori' => 'Bass',
                'jenis' => 'Bass Elektrik 4-Senar',
                'stok' => 3,
                'harga_sewa' => 120000,
                'status' => 'Dalam Perbaikan', // <-- UBAH UNTUK CONTOH
                'created_at' => now()->format('Y-m-d H:i:s'),
            ],
        ];
    }
}