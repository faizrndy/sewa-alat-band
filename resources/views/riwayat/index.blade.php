@extends('layouts.app')

@section('title', 'Riwayat Penyewaan')
@section('page-title', 'Riwayat Penyewaan')
@section('page-description', 'Daftar semua transaksi penyewaan')

@section('content')
<div class="bg-white p-8 rounded-xl shadow">

    <h2 class="text-xl font-bold mb-4">📜 Semua Riwayat Penyewaan</h2>

    <table class="w-full border rounded-xl overflow-hidden">
        <thead class="bg-gray-100">
            <tr class="text-left text-sm">
                <th class="p-3 border">Kode</th>
                <th class="p-3 border">Nama Penyewa</th>
                <th class="p-3 border">Telepon</th>
                <th class="p-3 border">Total Bayar</th>
                <th class="p-3 border">Status</th>
                <th class="p-3 border">Aksi</th>
            </tr>
        </thead>

        <tbody>
            @foreach($transaksi as $t)
            <tr class="border-b text-sm">
                <td class="p-3 border font-semibold">{{ $t->kode_transaksi }}</td>
                <td class="p-3 border">{{ $t->nama }}</td>
                <td class="p-3 border">{{ $t->telepon }}</td>
                <td class="p-3 border text-green-600 font-bold">
                    Rp {{ number_format($t->total_bayar, 0, ',', '.') }}
                </td>
                <td class="p-3 border">
                    <span class="
                        px-3 py-1 rounded-full text-xs font-semibold
                        @if($t->status=='pending') bg-yellow-100 text-yellow-700
                        @elseif($t->status=='success') bg-green-100 text-green-700
                        @else bg-red-100 text-red-700 @endif
                    ">
                        {{ strtoupper($t->status) }}
                    </span>
                </td>
                <td class="px-4 py-3 flex gap-2">

                    <a href="{{ route('riwayat.show', $t->id) }}"
                       class="bg-blue-600 text-white px-4 py-1 rounded-lg hover:bg-blue-700 text-xs">
                        Detail
                    </a>

                    <a href="{{ route('riwayat.nota', $t->id) }}"
                       target="_blank"
                       class="bg-green-600 text-white px-4 py-1 rounded-lg hover:bg-green-700 text-xs">
                        Cetak Nota
                    </a>

                </td>

            </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection
