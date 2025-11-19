@extends('layouts.app')

@section('title', 'Tambah Penyewaan')
@section('page-title', 'Tambah Penyewaan Baru')
@section('page-description', 'Form input data penyewaan alat band')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white rounded-lg shadow-md p-8">
        <form action="{{ route('riwayat.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-medium mb-2">Pilih Alat Band</label>
                    <select name="alat_id"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('alat_id') border-red-500 @enderror"
                        required>
                        <option value="">-- Pilih Alat --</option>
                        @foreach($alatBand as $alat)
                            @if($alat['status'] == 'Tersedia')
                                <option value="{{ $alat['id'] }}" {{ old('alat_id') == $alat['id'] ? 'selected' : '' }}>
                                    {{ $alat['nama_alat'] }} - Rp {{ number_format($alat['harga_sewa'], 0, ',', '.') }}/hari
                                </option>
                            @endif
                        @endforeach
                    </select>
                    @error('alat_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Nama Penyewa</label>
                    <input type="text" name="nama_penyewa" value="{{ old('nama_penyewa') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nama_penyewa') border-red-500 @enderror"
                        placeholder="Masukkan nama lengkap"
                        required>
                    @error('nama_penyewa')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">No. Telepon</label>
                    <input type="text" name="no_telepon" value="{{ old('no_telepon') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('no_telepon') border-red-500 @enderror"
                        placeholder="08xxxxxxxxxx"
                        required>
                    @error('no_telepon')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Tanggal Sewa</label>
                    <input type="date" name="tanggal_sewa" value="{{ old('tanggal_sewa', date('Y-m-d')) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('tanggal_sewa') border-red-500 @enderror"
                        required>
                    @error('tanggal_sewa')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Tanggal Kembali</label>
                    <input type="date" name="tanggal_kembali" value="{{ old('tanggal_kembali') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('tanggal_kembali') border-red-500 @enderror"
                        required>
                    @error('tanggal_kembali')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex space-x-4 mt-8">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Simpan Penyewaan
                </button>
                <a href="{{ route('riwayat.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-8 py-3 rounded-lg">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection