<?php

namespace App\Http\Controllers;

use App\Models\AlatBand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class AlatBandController extends Controller
{
    // ==========================================
    // 🟢 HALAMAN WEB ADMIN (BLADE VIEW) - TETAP SAMA
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

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/alat-band'), $filename);
            $data['gambar'] = 'images/alat-band/' . $filename;
        }

        $alat = AlatBand::create($data);

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json(['message' => 'Alat berhasil ditambahkan', 'data' => $alat], 201);
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
            'gambar'     => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status'     => 'required|in:Tersedia,Disewa,Dalam Perbaikan',
        ]);

        $data = $request->except(['gambar']);

        if ($request->hasFile('gambar')) {
            if (!empty($alat->gambar) && file_exists(public_path($alat->gambar))) {
                unlink(public_path($alat->gambar));
            }

            $file = $request->file('gambar');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/alat-band'), $filename);
            $data['gambar'] = 'images/alat-band/' . $filename;
        }

        $alat->update($data);

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json(['message' => 'Alat berhasil diupdate', 'data' => $alat]);
        }

        return redirect()->route('alat-band.index')->with('success', 'Alat band berhasil diupdate!');
    }

    public function destroy($id)
    {
        $alat = AlatBand::findOrFail($id);
        if (!empty($alat->gambar) && file_exists(public_path($alat->gambar))) {
            unlink(public_path($alat->gambar));
        }
        $alat->delete();

        if (request()->wantsJson() || request()->is('api/*')) {
            return response()->json(['message' => 'Alat berhasil dihapus']);
        }
        return redirect()->route('alat-band.index')->with('success', 'Alat band berhasil dihapus!');
    }


    // ==========================================
    // 🟢 API ENDPOINTS (MODIFIKASI UNTUK REVISI)
    // ==========================================

    /**
     * Cari Alat Available (REVISI DOSEN: Filter Tanggal Dulu)
     * Endpoint: GET /api/alat-check
     */
public function searchAvailable(Request $request)
    {
        // 1. Validasi Input Tanggal
        $request->validate([
            'tgl_mulai' => 'required|date|after_or_equal:today',
            'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
        ]);

        $start = $request->tgl_mulai;
        $end = $request->tgl_selesai;

        // 2. Mulai Query Alat
        $query = AlatBand::query();

        // 🔥 TAMBAHAN LOGIC FILTER (INI YANG KURANG KEMARIN) 🔥
        if ($request->has('search') && $request->search != '') {
            $query->where('nama_alat', 'like', '%' . $request->search . '%');
        }

        if ($request->has('kategori') && $request->kategori != '' && $request->kategori != 'Semua Kategori') {
            $query->where('kategori', $request->kategori);
        }

        // 3. Logic Hitung Stok (Seperti Sebelumnya)
        $alatBand = $query->withSum(['transaksiItems as sedang_disewa' => function($q) use ($start, $end) {
            $q->whereHas('transaksi', function($trx) use ($start, $end) {
                // Filter status sewa aktif
                $trx->whereIn('status', ['pending', 'paid', 'success', 'settlement', 'capture', 'sewa_berjalan'])
                    // Filter irisan tanggal
                    ->where(function($sub) use ($start, $end) {
                        $sub->whereBetween('tgl_mulai', [$start, $end])
                            ->orWhereBetween('tgl_selesai', [$start, $end])
                            ->orWhere(function($deep) use ($start, $end) {
                                $deep->where('tgl_mulai', '<=', $start)
                                     ->where('tgl_selesai', '>=', $end);
                            });
                    });
            });
        }], 'jumlah')
        ->orderBy('created_at', 'desc')
        ->get();

        // 4. Mapping Data & Sisa Stok
        $availableAlats = $alatBand->map(function ($item) {
            $stokTerpakai = (int) $item->sedang_disewa;
            $sisaStok = $item->stok - $stokTerpakai;

            return [
                'id' => $item->id,
                'nama_alat' => $item->nama_alat,
                'kategori' => $item->kategori,
                'harga_sewa' => (int) $item->harga_sewa,
                'gambar' => asset($item->gambar),
                'stok_total' => $item->stok,
                'stok_tersedia' => $sisaStok > 0 ? $sisaStok : 0,
                'is_available' => $sisaStok > 0
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $availableAlats
        ]);
    }

    // Endpoint lama (API Index biasa) tetap disimpan buat jaga-jaga
    public function apiIndex(Request $request)
    {
        $query = AlatBand::query();
        if ($request->kategori && $request->kategori != 'Semua Kategori') {
            $query->where('kategori', $request->kategori);
        }
        if ($request->search) {
            $query->where('nama_alat', 'like', '%' . $request->search . '%');
        }

        $alatBand = $query->orderBy('created_at', 'desc')->get();
        $alatBand->transform(function ($item) {
            $item->gambar = asset($item->gambar);
            $item->harga_sewa = (int) $item->harga_sewa;
            return $item;
        });

        return response()->json($alatBand);
    }

    public function apiShow($id)
    {
        $alat = AlatBand::find($id);
        if (!$alat) return response()->json(['message' => 'Alat tidak ditemukan'], 404);

        return response()->json([
            'id' => $alat->id,
            'nama_alat' => $alat->nama_alat,
            'kategori' => $alat->kategori,
            'harga_sewa' => (int) $alat->harga_sewa,
            'status' => $alat->status,
            'gambar' => asset($alat->gambar),
            'deskripsi' => $alat->deskripsi,
            'stok' => (int) $alat->stok,
            'created_at' => $alat->created_at->format('d-m-Y H:i')
        ]);
    }
}