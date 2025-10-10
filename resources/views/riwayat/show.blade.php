@extends('layouts.app')

@section('title', 'Detail Penyewaan')
@section('page-title', 'Detail Penyewaan')
@section('page-description', 'Informasi lengkap transaksi penyewaan')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white rounded-lg shadow-md p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-gray-500 text-sm mb-1">Nama Alat</label>
                <p class="text-xl font-semibold text-gray-900">{{ $data['nama_alat'] }}</p>
            </div>

            <div>
                <label class="block text-gray-500 text-sm mb-1">Status</label>
                <span class="inline-block px-3 py-1 bg-orange-100 text-orange-800 rounded-full text-sm font-medium">
                    {{ $data['status'] }}
                </span>
            </div>

            <div>
                <label class="block text-gray-500 text-sm mb-1">Nama Penyewa</label>
                <p class="text-lg font-medium text-gray-900">{{ $data['nama_penyewa'] }}</p>
            </div>

            <div>
                <label class="block text-gray-500 text-sm mb-1">No. Telepon</label>
                <p class="text-lg text-gray-900">{{ $data['no_telepon'] }}</p>
            </div>

            <div>
                <label class="block text-gray-500 text-sm mb-1">Tanggal Sewa</label>
                <p class="text-lg text-gray-900">{{ date('d F Y', strtotime($data['tanggal_sewa'])) }}</p>
            </div>

            <div>
                <label class="block text-gray-500 text-sm mb-1">Tanggal Kembali</label>
                <p class="text-lg text-gray-900">{{ date('d F Y', strtotime($data['tanggal_kembali'])) }}</p>
            </div>

            <div>
                <label class="block text-gray-500 text-sm mb-1">Durasi Sewa</label>
                <p class="text-lg font-semibold text-blue-600">{{ $data['durasi'] }} Hari</p>
            </div>

            <div>
                <label class="block text-gray-500 text-sm mb-1">Total Biaya</label>
                <p class="text-xl font-bold text-green-600">Rp {{ number_format($data['total_harga'], 0, ',', '.') }}</p>
            </div>

            <div class="md:col-span-2">
                <label class="block text-gray-500 text-sm mb-1">Dibuat Pada</label>
                <p class="text-gray-700">{{ $data['created_at'] }}</p>
            </div>
        </div>

        <div class="flex space-x-4 mt-8 pt-6 border-t">
            <a href="{{ route('riwayat.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg">
                Kembali
            </a>
            <form action="{{ route('riwayat.destroy', $data['id']) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')" class="ml-auto">
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