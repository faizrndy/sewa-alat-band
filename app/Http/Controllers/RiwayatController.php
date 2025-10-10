<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $semuaRiwayat = session('riwayat', $this->getDefaultData());
        $filterBulan = $request->input('bulan');
        $filterTahun = $request->input('tahun');
        $riwayatFiltered = collect($semuaRiwayat)->filter(function ($item) use ($filterBulan, $filterTahun) {
            if (!$filterBulan && !$filterTahun) {
                return true;
            }
            $tanggalSewa = Carbon::parse($item['tanggal_sewa']);
            $cocokBulan = true;
            $cocokTahun = true;
            if ($filterBulan) {
                $cocokBulan = $tanggalSewa->month == $filterBulan;
            }
            if ($filterTahun) {
                $cocokTahun = $tanggalSewa->year == $filterTahun;
            }
            return $cocokBulan && $cocokTahun;
        });
        $daftarTahun = range(date('Y'), date('Y') - 4);
        return view('riwayat.index', [
            'riwayat' => $riwayatFiltered,
            'daftarTahun' => $daftarTahun,
        ]);
    }

    public function create()
    {
        $alatBand = session('alat_band', []);
        $alatTersedia = array_filter($alatBand, fn($alat) => $alat['status'] == 'Tersedia');
        return view('riwayat.create', ['alatBand' => $alatTersedia]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'alat_id' => 'required',
            'nama_penyewa' => 'required|string|max:255',
            'no_telepon' => 'required|string',
            'tanggal_sewa' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_sewa',
        ]);
        $riwayat = session('riwayat', []);
        $alatBand = session('alat_band', []);
        $newId = empty($riwayat) ? 1 : max(array_keys($riwayat)) + 1;
        $tanggalSewa = new \DateTime($request->tanggal_sewa);
        $tanggalKembali = new \DateTime($request->tanggal_kembali);
        $durasi = $tanggalKembali->diff($tanggalSewa)->days + 1;
        $alat = $alatBand[$request->alat_id];
        $totalHarga = $alat['harga_sewa'] * $durasi;
        $riwayat[$newId] = [
            'id' => $newId,
            'alat_id' => $request->alat_id,
            'nama_alat' => $alat['nama_alat'],
            'nama_penyewa' => $request->nama_penyewa,
            'no_telepon' => $request->no_telepon,
            'tanggal_sewa' => $request->tanggal_sewa,
            'tanggal_kembali' => $request->tanggal_kembali,
            'durasi' => $durasi,
            'total_harga' => $totalHarga,
            'status' => 'Aktif',
            'created_at' => now()->format('Y-m-d H:i:s'),
        ];
        $alatBand[$request->alat_id]['status'] = 'Disewa';
        session(['riwayat' => $riwayat]);
        session(['alat_band' => $alatBand]);
        return redirect()->route('riwayat.index')->with('success', 'Penyewaan berhasil ditambahkan!');
    }

    public function show(string $id)
    {
        $semuaRiwayat = session('riwayat', []);
        // **PERBAIKAN 1:** Gunakan cara pencarian yang aman
        $data = collect($semuaRiwayat)->firstWhere('id', $id);

        if (!$data) {
            return redirect()->route('riwayat.index')->with('error', 'Data tidak ditemukan!');
        }
        return view('riwayat.show', compact('data'));
    }

    public function showNota($id)
    {
        $semuaRiwayat = session('riwayat', []);
        // **PERBAIKAN 2:** Pastikan ini juga menggunakan cara yang aman
        $riwayat = collect($semuaRiwayat)->firstWhere('id', $id);

        if (!$riwayat) {
            return redirect()->route('riwayat.index')->with('error', 'Data riwayat untuk nota tidak ditemukan!');
        }

        return view('riwayat.nota', compact('riwayat'));
    }


    public function destroy(string $id)
    {
        $riwayat = session('riwayat', []);
        $alatBand = session('alat_band', []);

        // **PERBAIKAN 3:** Gunakan cara yang aman untuk mencari item yang akan dihapus
        $itemToDelete = collect($riwayat)->firstWhere('id', $id);

        if (!$itemToDelete) {
             return redirect()->route('riwayat.index')->with('error', 'Data tidak ditemukan!');
        }

        // Dapatkan key/posisi array dari item yang akan dihapus
        $keyToDelete = null;
        foreach($riwayat as $key => $item){
            if($item['id'] == $id){
                $keyToDelete = $key;
                break;
            }
        }

        if ($keyToDelete !== null) {
            // Kembalikan status alat menjadi Tersedia
            $alatId = $riwayat[$keyToDelete]['alat_id'];
            if (isset($alatBand[$alatId])) {
                $alatBand[$alatId]['status'] = 'Tersedia';
            }
            // Hapus item dari array riwayat
            unset($riwayat[$keyToDelete]);
        }

        session(['riwayat' => $riwayat]);
        session(['alat_band' => $alatBand]);
        return redirect()->route('riwayat.index')->with('success', 'Riwayat berhasil dihapus!');
    }

    private function getDefaultData()
    {
        return [
            1 => [ 'id' => 1, 'alat_id' => 2, 'nama_alat' => 'Drum Set Pearl', 'nama_penyewa' => 'John Doe', 'no_telepon' => '081234567890', 'tanggal_sewa' => '2025-10-05', 'tanggal_kembali' => '2025-10-10', 'durasi' => 6, 'total_harga' => 1800000, 'status' => 'Aktif', 'created_at' => '2025-10-05 10:00:00'],
            2 => [ 'id' => 2, 'alat_id' => 1, 'nama_alat' => 'Gitar Elektrik Fender', 'nama_penyewa' => 'Jane Smith', 'no_telepon' => '081234567891', 'tanggal_sewa' => '2025-09-15', 'tanggal_kembali' => '2025-09-17', 'durasi' => 3, 'total_harga' => 450000, 'status' => 'Selesai', 'created_at' => '2025-09-15 11:00:00'],
            3 => [ 'id' => 3, 'alat_id' => 3, 'nama_alat' => 'Bass Ibanez', 'nama_penyewa' => 'Peter Jones', 'no_telepon' => '081234567892', 'tanggal_sewa' => '2024-12-20', 'tanggal_kembali' => '2024-12-22', 'durasi' => 3, 'total_harga' => 360000, 'status' => 'Selesai', 'created_at' => '2024-12-20 12:00:00'],
        ];
    }
}