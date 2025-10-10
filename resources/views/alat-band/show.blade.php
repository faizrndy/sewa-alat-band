@extends('layouts.app')

@section('title', 'Detail Alat Band')
@section('page-title', 'Detail Produk')
@section('page-description', 'Informasi lengkap alat band')
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-gray-800">Detail Alat Band</h2>
        <a href="{{ route('alat-band.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded">
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="mb-4">
            <label class="block text-gray-500 text-sm mb-1">Nama Alat</label>
            <p class="text-xl font-semibold">{{ $alat['nama_alat'] }}</p>
        </div>

        <div class="mb-4">
            <label class="block text-gray-500 text-sm mb-1">Kategori</label>
            <p class="text-lg">{{ $alat['kategori'] }}</p>
        </div>

        <div class="mb-4">
            <label class="block text-gray-500 text-sm mb-1">Harga Sewa per Hari</label>
            <p class="text-lg font-semibold text-green-600">Rp {{ number_format($alat['harga_sewa'], 0, ',', '.') }}</p>
        </div>

        <div class="mb-4">
            <label class="block text-gray-500 text-sm mb-1">Status</label>
            @if($alat['status'] == 'Tersedia')
                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-lg">Tersedia</span>
            @else
                <span class="px-3 py-1 bg-orange-100 text-orange-800 rounded-lg">Disewa</span>
            @endif
        </div>

        <div class="mb-6">
            <label class="block text-gray-500 text-sm mb-1">Ditambahkan pada</label>
            <p class="text-gray-700">{{ $alat['created_at'] }}</p>
        </div>

        <div class="flex space-x-4">
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
@endsection