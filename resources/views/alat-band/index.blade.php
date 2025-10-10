@extends('layouts.app')

@section('title', 'Data Alat Band')
@section('page-title', 'Manage Produk')
@section('page-description', 'Kelola data alat band')
@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-3xl font-bold text-gray-800">Data Alat Band</h2>
    <a href="{{ route('alat-band.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
        + Tambah Alat
    </a>
</div>

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <table class="min-w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Alat</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis</th> <!-- BARU -->
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stok</th> <!-- BARU -->
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga Sewa/Hari</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($alatBand as $index => $alat)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">{{ $loop->iteration }}</td>
                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $alat['nama_alat'] }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $alat['jenis'] ?? '-' }}</td> <!-- BARU -->
                <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $alat['stok'] ?? '0' }}</td> <!-- BARU -->
                <td class="px-6 py-4 whitespace-nowrap text-gray-500">Rp {{ number_format($alat['harga_sewa'], 0, ',', '.') }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    {{-- LOGIKA STATUS YANG DIPERBAIKI --}}
                    @if($alat['status'] == 'Tersedia')
                        <span class="px-3 py-1 text-sm font-medium text-green-800 bg-green-100 rounded-full">Tersedia</span>
                    @elseif($alat['status'] == 'Disewa')
                        <span class="px-3 py-1 text-sm font-medium text-red-800 bg-red-100 rounded-full">Disewa</span>
                    @elseif($alat['status'] == 'Dalam Perbaikan')
                        <span class="px-3 py-1 text-sm font-medium text-yellow-800 bg-yellow-100 rounded-full">Dalam Perbaikan</span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center space-x-4 text-sm">
                        <a href="{{ route('alat-band.show', $alat['id']) }}" class="text-blue-600 hover:text-blue-900">Detail</a>
                        <a href="{{ route('alat-band.edit', $alat['id']) }}" class="text-yellow-600 hover:text-yellow-900">Edit</a>
                        <form action="{{ route('alat-band.destroy', $alat['id']) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-4 text-center text-gray-500">Tidak ada data alat band.</td> <!-- Colspan diubah ke 7 -->
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection