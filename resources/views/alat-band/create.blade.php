@extends('layouts.app')

@section('title', 'Tambah Alat Band')

@section('page-title', 'Input Produk')
@section('page-description', 'Tambah alat band baru ke inventory')
@section('content')
<div class="max-w-2xl mx-auto">
    <h2 class="text-3xl font-bold text-gray-800 mb-6">Tambah Alat Band Baru</h2>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('alat-band.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Nama Alat --}}
            <div class="mb-4">
                <label for="nama_alat" class="block text-gray-700 font-medium mb-2">Nama Alat</label>
                <input type="text" id="nama_alat" name="nama_alat" value="{{ old('nama_alat') }}"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nama_alat') border-red-500 @enderror"
                    required>
                @error('nama_alat')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Kategori --}}
            <div class="mb-4">
                <label for="kategori" class="block text-gray-700 font-medium mb-2">Kategori</label>
                <select id="kategori" name="kategori"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('kategori') border-red-500 @enderror"
                    required>
                    <option value="">Pilih Kategori</option>
                    <option value="Gitar" {{ old('kategori') == 'Gitar' ? 'selected' : '' }}>Gitar</option>
                    <option value="Bass" {{ old('kategori') == 'Bass' ? 'selected' : '' }}>Bass</option>
                    <option value="Drum" {{ old('kategori') == 'Drum' ? 'selected' : '' }}>Drum</option>
                    <option value="Keyboard" {{ old('kategori') == 'Keyboard' ? 'selected' : '' }}>Keyboard</option>
                    <option value="Amplifier" {{ old('kategori') == 'Amplifier' ? 'selected' : '' }}>Amplifier</option>
                    <option value="Microphone" {{ old('kategori') == 'Microphone' ? 'selected' : '' }}>Microphone</option>
                    <option value="Lainnya" {{ old('kategori') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
                @error('kategori')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Stok --}}
            <div class="mb-4">
                <label for="stok" class="block text-gray-700 font-medium mb-2">Stok</label>
                <input type="number" id="stok" name="stok" value="{{ old('stok', 1) }}"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('stok') border-red-500 @enderror"
                    required min="0">
                @error('stok')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Harga Sewa --}}
            <div class="mb-4">
                <label for="harga_sewa" class="block text-gray-700 font-medium mb-2">Harga Sewa per Hari (Rp)</label>
                <input type="number" id="harga_sewa" name="harga_sewa" value="{{ old('harga_sewa') }}"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('harga_sewa') border-red-500 @enderror"
                    required min="0">
                @error('harga_sewa')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Deskripsi --}}
            <div class="mb-4">
                <label for="deskripsi" class="block text-gray-700 font-medium mb-2">Deskripsi Alat</label>
                <textarea id="deskripsi" name="deskripsi" rows="4"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('deskripsi') border-red-500 @enderror"
                    placeholder="Tuliskan detail alat, kondisi, atau fitur uniknya...">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Upload Gambar --}}
            <div class="mb-4">
                <label for="gambar" class="block text-gray-700 font-medium mb-2">Gambar</label>
                <div class="relative">
                    <input type="file" id="gambar" name="gambar" accept="image/*"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('gambar') border-red-500 @enderror"
                        onchange="previewImage(event)">
                </div>
                <small class="text-gray-600 mt-1 block">Format: JPG, PNG, GIF. Ukuran maksimal: 2MB</small>
                @error('gambar')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror

                <div id="preview-container" class="mt-4 hidden">
                    <p class="text-gray-700 font-medium mb-2">Pratinjau:</p>
                    <img id="preview-image" src="" alt="Preview" class="max-w-xs h-auto border rounded-lg">
                </div>
            </div>

            {{-- Status --}}
            <div class="mb-6">
                <label for="status" class="block text-gray-700 font-medium mb-2">Status</label>
                <select id="status" name="status"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @enderror"
                    required>
                    <option value="Tersedia" {{ old('status', 'Tersedia') == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="Disewa" {{ old('status') == 'Disewa' ? 'selected' : '' }}>Disewa</option>
                    <option value="Dalam Perbaikan" {{ old('status') == 'Dalam Perbaikan' ? 'selected' : '' }}>Dalam Perbaikan</option>
                </select>
                @error('status')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tombol --}}
            <div class="flex space-x-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                    Simpan
                </button>
                <a href="{{ route('alat-band.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Preview Gambar
function previewImage(event) {
    const file = event.target.files[0];
    const previewContainer = document.getElementById('preview-container');
    const previewImage = document.getElementById('preview-image');

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            previewContainer.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    } else {
        previewContainer.classList.add('hidden');
    }
}
</script>
@endpush
