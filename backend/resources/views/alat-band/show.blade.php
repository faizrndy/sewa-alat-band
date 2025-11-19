@extends('layouts.app')

@section('title', 'Detail Alat Band')
@section('page-title', 'Detail Produk')
@section('page-description', 'Informasi lengkap alat band')
@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-gray-800">Detail Alat Band</h2>
        <a href="{{ route('alat-band.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded">
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Gambar Alat Band -->
            <div>
                @if($alat['gambar'])
                    <img src="{{ asset('storage/' . $alat['gambar']) }}"
                         alt="{{ $alat['nama_alat'] }}"
                         class="w-full h-auto rounded-lg shadow-md object-cover">
                @else
                    <div class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center">
                        <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                @endif
            </div>

            <!-- Informasi Detail -->
            <div class="space-y-4">
                <div>
                    <label class="block text-gray-500 text-sm mb-1">Nama Alat</label>
                    <p class="text-xl font-semibold">{{ $alat['nama_alat'] }}</p>
                </div>

                <div>
                    <label class="block text-gray-500 text-sm mb-1">Kategori</label>
                    <p class="text-lg">{{ $alat['kategori'] }}</p>
                </div>

                <div>
                    <label class="block text-gray-500 text-sm mb-1">Harga Sewa per Hari</label>
                    <p class="text-lg font-semibold text-green-600">Rp {{ number_format($alat['harga_sewa'], 0, ',', '.') }}</p>
                </div>

                <div>
                    <label class="block text-gray-500 text-sm mb-1">Status</label>
                    @if($alat['status'] == 'Tersedia')
                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-lg">Tersedia</span>
                    @else
                        <span class="px-3 py-1 bg-orange-100 text-orange-800 rounded-lg">Disewa</span>
                    @endif
                </div>

                <div>
                    <label class="block text-gray-500 text-sm mb-1">Ditambahkan pada</label>
                    <p class="text-gray-700">{{ $alat['created_at'] }}</p>
                </div>

                <div class="flex space-x-4 pt-4">
                    <a href="{{ route('alat-band.edit', $alat['id']) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2 rounded-lg">
                        Edit
                    </a>
                    <form action="{{ route('alat-band.destroy', $alat['id']) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection