@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-description', 'Ringkasan data sewa alat band')

@section('content')
{{-- Grid diubah agar responsif untuk 4 item --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium mb-1">Total Alat</p>
                <h3 class="text-3xl font-bold text-gray-800">{{ $totalAlat }}</h3>
                <p class="text-gray-400 text-xs mt-1">Semua produk</p>
            </div>
            <div class="bg-blue-100 rounded-full p-4">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium mb-1">Tersedia</p>
                <h3 class="text-3xl font-bold text-green-600">{{ $totalTersedia }}</h3>
                <p class="text-gray-400 text-xs mt-1">Siap disewakan</p>
            </div>
            <div class="bg-green-100 rounded-full p-4">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-orange-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium mb-1">Sedang Disewa</p>
                <h3 class="text-3xl font-bold text-orange-600">{{ $totalDisewa }}</h3>
                <p class="text-gray-400 text-xs mt-1">Dalam penyewaan</p>
            </div>
            <div class="bg-orange-100 rounded-full p-4">
                <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium mb-1">Dalam Perbaikan</p>
                <h3 class="text-3xl font-bold text-yellow-600">{{ $totalPerbaikan }}</h3>
                <p class="text-gray-400 text-xs mt-1">Sedang diperbaiki</p>
            </div>
            <div class="bg-yellow-100 rounded-full p-4">
                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow-md p-6 mb-8">
    <h3 class="text-xl font-bold mb-4 text-gray-800">Aksi Cepat</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('alat-band.create') }}" class="group border-2 border-blue-500 hover:bg-blue-500 text-blue-600 hover:text-white rounded-lg p-4 flex items-center transition duration-200">
            <div class="bg-blue-100 group-hover:bg-white group-hover:text-blue-600 rounded-full p-3 mr-4 transition duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
            </div>
            <div>
                <p class="font-semibold">Tambah Produk</p>
                <p class="text-sm opacity-75">Input alat band baru</p>
            </div>
        </a>

        <a href="{{ route('alat-band.index') }}" class="group border-2 border-green-500 hover:bg-green-500 text-green-600 hover:text-white rounded-lg p-4 flex items-center transition duration-200">
            <div class="bg-green-100 group-hover:bg-white group-hover:text-green-600 rounded-full p-3 mr-4 transition duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
            <div>
                <p class="font-semibold">Kelola Produk</p>
                <p class="text-sm opacity-75">Edit & hapus data</p>
            </div>
        </a>

        <a href="{{ route('riwayat.index') }}" class="group border-2 border-purple-500 hover:bg-purple-500 text-purple-600 hover:text-white rounded-lg p-4 flex items-center transition duration-200">
            <div class="bg-purple-100 group-hover:bg-white group-hover:text-purple-600 rounded-full p-3 mr-4 transition duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
            </div>
            <div>
                <p class="font-semibold">Lihat Riwayat</p>
                <p class="text-sm opacity-75">Data penyewaan</p>
            </div>
        </a>
    </div>
</div>

<div class="bg-white rounded-lg shadow-md p-6">
    <h3 class="text-xl font-bold mb-4 text-gray-800">Aktivitas Terbaru</h3>
    <div class="space-y-3">
        <div class="flex items-center p-3 bg-blue-50 rounded-lg">
            <div class="w-2 h-2 bg-blue-500 rounded-full mr-3"></div>
            <p class="text-gray-700">Sistem siap digunakan</p>
            <span class="ml-auto text-sm text-gray-500">Hari ini</span>
        </div>
        <div class="flex items-center p-3 bg-green-50 rounded-lg">
            <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
            <p class="text-gray-700">{{ $totalAlat }} produk di inventory</p> <span class="ml-auto text-sm text-gray-500">Update</span>
        </div>
    </div>
</div>
@endsection