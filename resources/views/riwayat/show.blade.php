@extends('layouts.app')

@section('title', 'Detail Transaksi')
@section('page-title', 'Detail Transaksi')
@section('page-description', 'Informasi lengkap penyewaan')

@section('content')
<div class="max-w-4xl">

    <div class="bg-white shadow rounded-xl p-8">

        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Kode: {{ $trx->kode_transaksi }}</h2>

            <span class="
                px-4 py-1 rounded-full font-semibold text-sm
                @if($trx->status=='pending') bg-yellow-100 text-yellow-700
                @elseif($trx->status=='success') bg-green-100 text-green-700
                @else bg-red-100 text-red-700 @endif
            ">
                {{ strtoupper($trx->status) }}
            </span>
        </div>

        {{-- DATA PENYEWA --}}
        <h3 class="text-lg font-semibold mb-3">🧍 Data Penyewa</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">

            <div>
                <p class="text-gray-500 text-sm">Nama Penyewa</p>
                <p class="text-lg font-medium">{{ $trx->nama }}</p>
            </div>

            <div>
                <p class="text-gray-500 text-sm">Nomor Telepon</p>
                <p class="text-lg">{{ $trx->telepon }}</p>
                <a href="https://wa.me/62{{ ltrim($trx->telepon, '0') }}" target="_blank"
                   class="text-green-600 underline text-sm">Chat Penyewa via WA</a>
            </div>

            <div class="md:col-span-2">
                <p class="text-gray-500 text-sm">Alamat</p>
                <p class="text-gray-800">{{ $trx->alamat }}</p>
            </div>

            <div class="md:col-span-2">
                <p class="text-gray-500 text-sm">Deskripsi Rumah</p>
                <p class="text-gray-800">{{ $trx->deskripsi_lokasi }}</p>
            </div>

            <div>
                <p class="text-gray-500 text-sm">Latitude</p>
                <p>{{ $trx->lat }}</p>
            </div>

            <div>
                <p class="text-gray-500 text-sm">Longitude</p>
                <p>{{ $trx->lon }}</p>
            </div>

            <div>
                <p class="text-gray-500 text-sm">Jarak ke Toko</p>
                <p class="font-semibold">{{ number_format($trx->jarak_km,1) }} km</p>
            </div>

        </div>


        {{-- DATA PENGIRIMAN --}}
        <h3 class="text-lg font-semibold mb-3">🚚 Pengiriman</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
            <div>
                <p class="text-gray-500 text-sm">Metode Pengiriman</p>
                <p class="font-semibold">{{ strtoupper($trx->metode_pengiriman) }}</p>
            </div>

            <div>
                <p class="text-gray-500 text-sm">Tarif Antar</p>
                <p class="text-blue-600 font-semibold">
                    Rp {{ number_format($trx->tarif_antar, 0, ',', '.') }}
                </p>
            </div>
        </div>


        {{-- ITEM SEWA --}}
        <h3 class="text-lg font-semibold mb-3">🎸 Detail Alat Disewa</h3>

        @foreach($trx->items as $item)
        <div class="bg-slate-50 p-4 mt-2 rounded-xl border">
            <p class="text-lg font-medium">{{ $item->nama_alat }}</p>

            <p class="text-sm text-gray-600">
                {{ $item->tanggal_mulai }} → {{ $item->tanggal_selesai }}
            </p>

            <p class="text-sm text-gray-600">
                {{ $item->jumlah }} alat × Rp {{ number_format($item->harga_sewa, 0, ',', '.') }}
            </p>

            <p class="text-sm font-bold text-green-700">
                Subtotal: Rp {{ number_format($item->subtotal, 0, ',', '.') }}
            </p>
        </div>
        @endforeach


        {{-- TOTAL --}}
        <div class="mt-6 p-4 bg-gray-100 rounded-xl border">
            <p class="text-gray-700">Total Sewa:</p>
            <p class="text-xl font-bold text-green-700">
                Rp {{ number_format($trx->total_sewa, 0, ',', '.') }}
            </p>

            <p class="mt-2 text-gray-700">Total Bayar:</p>
            <p class="text-2xl font-extrabold text-blue-600">
                Rp {{ number_format($trx->total_bayar, 0, ',', '.') }}
            </p>
        </div>


        {{-- UPDATE STATUS --}}
        <hr class="my-8">

        <h3 class="text-lg font-semibold mb-3">🔧 Update Status Pemesanan</h3>

        <form action="{{ route('riwayat.updateStatus', $trx->id) }}" method="POST" class="flex gap-3">
            @csrf
            <select name="status" class="border px-4 py-2 rounded-lg">
                <option value="pending"  {{ $trx->status=='pending' ? 'selected' : '' }}>Pending</option>
                <option value="success"  {{ $trx->status=='success' ? 'selected' : '' }}>Success</option>
                <option value="failed"   {{ $trx->status=='failed' ? 'selected' : '' }}>Failed</option>
            </select>

            <button type="submit"
                class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                Update

            </button>
        </form>


        {{-- BOTTOM BUTTON --}}
        <div class="flex justify-between mt-10">
            <a href="{{ route('riwayat.index') }}"
               class="px-6 py-2 bg-gray-300 hover:bg-gray-400 rounded-lg">
                Kembali
            </a>
        </div>

    </div>
</div>
@endsection
