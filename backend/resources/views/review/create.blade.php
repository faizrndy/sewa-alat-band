@extends('layouts.app')

@section('title', 'Tambah Review')
@section('page-title', 'Tambah Review Pelanggan')
@section('page-description', 'Upload screenshot testimoni pelanggan')

@section('content')
<div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-md">
    <form action="{{ route('review.upload') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-4">
            <label class="block font-medium mb-1">Nama Pelanggan</label>
            <input type="text" name="nama_pelanggan" class="w-full border rounded-lg px-4 py-2" required>
        </div>

        <div class="mb-4">
            <label class="block font-medium mb-1">Screenshot Chat</label>
            <input type="file" name="gambar" accept="image/*" class="w-full border rounded-lg px-4 py-2" required>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Simpan Review
        </button>
    </form>
</div>
@endsection
