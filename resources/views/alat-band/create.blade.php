@extends('layouts.app')

@section('title', 'Tambah Alat Band')

@section('page-title', 'Input Produk')
@section('page-description', 'Tambah alat band baru ke inventory')
@section('content')
<div class="max-w-2xl mx-auto">
    <h2 class="text-3xl font-bold text-gray-800 mb-6">Tambah Alat Band Baru</h2>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('alat-band.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="nama_alat" class="block text-gray-700 font-medium mb-2">Nama Alat</label>
                <input type="text" id="nama_alat" name="nama_alat" value="{{ old('nama_alat') }}"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nama_alat') border-red-500 @enderror"
                    required>
                @error('nama_alat')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

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

            <div class="mb-4">
                <label for="jenis" class="block text-gray-700 font-medium mb-2">Jenis</label>
                <select id="jenis" name="jenis"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('jenis') border-red-500 @enderror"
                    required>
                    <option value="">Pilih Kategori Terlebih Dahulu</option>
                </select>
                @error('jenis')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="stok" class="block text-gray-700 font-medium mb-2">Stok</label>
                <input type="number" id="stok" name="stok" value="{{ old('stok', 1) }}"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('stok') border-red-500 @enderror"
                    required min="0">
                @error('stok')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="harga_sewa" class="block text-gray-700 font-medium mb-2">Harga Sewa per Hari (Rp)</label>
                <input type="number" id="harga_sewa" name="harga_sewa" value="{{ old('harga_sewa') }}"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('harga_sewa') border-red-500 @enderror"
                    required min="0">
                @error('harga_sewa')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="status" class="block text-gray-700 font-medium mb-2">Status</label>
                <select id="status" name="status"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @enderror"
                    required>
                    <option value="Tersedia" {{ old('status', 'Tersedia') == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="Disewa" {{ old('status') == 'Disewa' ? 'selected' : '' }}>Disewa</option>
                    <option value="Dalam Perbaikan" {{ old('status') == 'Dalam Perbaikan' ? 'selected' : '' }}>Dalam Perbaikan</option> </select>
                @error('status')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

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
document.addEventListener('DOMContentLoaded', function () {
    const kategoriSelect = document.getElementById('kategori');
    const jenisSelect = document.getElementById('jenis');

    // DATA BARU: Daftar jenis alat musik yang lebih lengkap
    const jenisMapping = {
        'Gitar': [
            'Gitar Elektrik',
            'Gitar Akustik',
        ],
        'Bass': [
            'Bass Elektrik 4-Senar (Jazz Bass)',
            'Bass Elektrik 4-Senar (Precision Bass)',
            'Bass Elektrik 5-Senar',
            'Bass Akustik'
        ],
        'Drum': [
            'Drum Akustik Set (Standard)',
            'Drum Elektrik Set',
        ],
        'Keyboard': [
            'Piano Digital (88 Keys)',
            'Synthesizer',
            'Workstation Keyboard',
            'MIDI Controller'
        ],
        'Amplifier': [
            'Ampli Gitar Combo',
            'Ampli Gitar Head Cabinet',
            'Ampli Bass Combo',
            'Ampli Bass Head Cabinet'
        ],
        'Microphone': [
            'Mic Vokal (Kabel)',
            'Mic Instrumen (Kabel)',
            'Mic Wireless (Handheld)',
            'Mic Wireless (Clip-On)'
        ],
        'Lainnya': [
            'Sound System (Speaker Aktif + Mixer)',
            'Monitor Panggung',
            'Stand Mic',
            'Stand Keyboard',
            'Stand Gitar',
            'Kabel Jack & XLR',
            'DI Box'
        ]
    };

    const oldJenis = "{{ old('jenis') }}";

    function updateJenisOptions() {
        const selectedKategori = kategoriSelect.value;

        jenisSelect.innerHTML = '';

        if (selectedKategori && jenisMapping[selectedKategori]) {
            let defaultOption = document.createElement('option');
            defaultOption.value = "";
            defaultOption.textContent = "Pilih Jenis";
            jenisSelect.appendChild(defaultOption);

            jenisMapping[selectedKategori].forEach(function(jenis) {
                const option = document.createElement('option');
                option.value = jenis;
                option.textContent = jenis;
                if (jenis === oldJenis) {
                    option.selected = true;
                }
                jenisSelect.appendChild(option);
            });
            jenisSelect.disabled = false;
        } else {
            let disabledOption = document.createElement('option');
            disabledOption.textContent = "Pilih Kategori Terlebih Dahulu";
            disabledOption.value = "";
            jenisSelect.appendChild(disabledOption);
            jenisSelect.disabled = true;
        }
    }

    kategoriSelect.addEventListener('change', updateJenisOptions);
    updateJenisOptions();
});
</script>
@endpush