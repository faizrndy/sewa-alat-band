@extends('layouts.app')

@section('title', 'Riwayat Penyewaan')
@section('page-title', 'Riwayat Penyewaan')
@section('page-description', 'Daftar semua transaksi penyewaan alat band')

@section('content')

<div class="bg-white rounded-lg shadow-md p-4 mb-6">
    <form action="{{ route('riwayat.index') }}" method="GET" class="flex flex-col md:flex-row md:items-center gap-4">
        <h3 class="text-lg font-semibold text-gray-800 shrink-0">Filter Riwayat:</h3>

        {{-- Dropdown Bulan --}}
        <div class="w-full md:w-auto">
            <select name="bulan" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">Semua Bulan</option>
                @for ($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($i)->isoFormat('MMMM') }}
                    </option>
                @endfor
            </select>
        </div>

        {{-- Dropdown Tahun --}}
        <div class="w-full md:w-auto">
            <select name="tahun" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">Semua Tahun</option>
                @foreach ($daftarTahun as $tahun)
                    <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
                @endforeach
            </select>
        </div>

        {{-- Tombol Filter --}}
        <div class="flex items-center gap-2">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
                Terapkan
            </button>
            <a href="{{ route('riwayat.index') }}" class="text-gray-600 hover:text-gray-800 px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-50">Reset</a>
        </div>
    </form>
</div>


<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alat</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penyewa</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Sewa</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durasi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($riwayat as $item)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $loop->iteration }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="font-medium text-gray-900">{{ $item['nama_alat'] }}</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item['nama_penyewa'] }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::parse($item['tanggal_sewa'])->isoFormat('D MMM YYYY') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item['durasi'] }} hari</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-800">Rp {{ number_format($item['total_harga'], 0, ',', '.') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($item['status'] == 'Aktif')
                            <span class="px-2.5 py-1 text-xs font-medium bg-orange-100 text-orange-800 rounded-full">{{ $item['status'] }}</span>
                        @else
                            <span class="px-2.5 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">{{ $item['status'] }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('riwayat.nota', $item['id']) }}" class="text-blue-600 hover:text-blue-900">Nota</a>
                            <a href="{{ route('riwayat.show', $item['id']) }}" class="text-gray-600 hover:text-gray-900">Detail</a>
                            <form action="{{ route('riwayat.destroy', $item['id']) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus riwayat ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="px-6 py-8 text-center text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="mt-2 font-medium">Tidak Ada Data Riwayat</p>
                        <p class="text-sm text-gray-400">Coba ubah atau reset filter Anda.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection