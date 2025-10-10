@extends('layouts.app')

@section('title', 'Edit Alat Band')
@section('page-title', 'Edit Produk')
@section('page-description', 'Ubah informasi alat band')
@section('content')
<div class="max-w-2xl mx-auto">
    <h2 class="text-3xl font-bold text-gray-800 mb-6">Edit Alat Band</h2>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('alat-band.update', $alat['id']) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Nama Alat -->
            <div class="mb-4">
                <label for="nama_alat" class="block text-gray-700 font-medium mb-2">Nama Alat</label>
                <input type="text" id="nama_alat" name="nama_alat" value="{{ old('nama_alat', $alat['nama_alat']) }}"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nama_alat') border-red-500 @enderror"
                    required>
                @error('nama_alat')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Kategori -->
            <div class="mb-4">
                <label for="kategori" class="block text-gray-700 font-medium mb-2">Kategori</label>
                <select id="kategori" name="kategori"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('kategori') border-red-500 @enderror"
                    required>
                    <option value="">Pilih Kategori</option>
                    <option value="Gitar" {{ old('kategori', $alat['kategori']) == 'Gitar' ? 'selected' : '' }}>Gitar</option>
                    <option value="Bass" {{ old('kategori', $alat['kategori']) == 'Bass' ? 'selected' : '' }}>Bass</option>
                    <option value="Drum" {{ old('kategori', $alat['kategori']) == 'Drum' ? 'selected' : '' }}>Drum</option>
                    <option value="Keyboard" {{ old('kategori', $alat['kategori']) == 'Keyboard' ? 'selected' : '' }}>Keyboard</option>
                    <option value="Amplifier" {{ old('kategori', $alat['kategori']) == 'Amplifier' ? 'selected' : '' }}>Amplifier</option>
                    <option value="Microphone" {{ old('kategori', $alat['kategori']) == 'Microphone' ? 'selected' : '' }}>Microphone</option>
                    <option value="Lainnya" {{ old('kategori', $alat['kategori']) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
                @error('kategori')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- BARU: Field Jenis -->
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

            <!-- BARU: Field Stok -->
            <div class="mb-4">
                <label for="stok" class="block text-gray-700 font-medium mb-2">Stok</label>
                <input type="number" id="stok" name="stok" value="{{ old('stok', $alat['stok'] ?? 1) }}"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('stok') border-red-500 @enderror"
                    required min="0">
                @error('stok')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Harga Sewa -->
            <div class="mb-4">
                <label for="harga_sewa" class="block text-gray-700 font-medium mb-2">Harga Sewa per Hari (Rp)</label>
                <input type="number" id="harga_sewa" name="harga_sewa" value="{{ old('harga_sewa', $alat['harga_sewa']) }}"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('harga_sewa') border-red-500 @enderror"
                    required min="0">
                @error('harga_sewa')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status (DIPERBARUI) -->
            <div class="mb-6">
                <label for="status" class="block text-gray-700 font-medium mb-2">Status</label>
                <select id="status" name="status"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @enderror"
                    required>
                    <option value="Tersedia" {{ old('status', $alat['status']) == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="Disewa" {{ old('status', $alat['status']) == 'Disewa' ? 'selected' : '' }}>Disewa</option>
                    <option value="Dalam Perbaikan" {{ old('status', $alat['status']) == 'Dalam Perbaikan' ? 'selected' : '' }}>Dalam Perbaikan</option>
                </select>
                @error('status')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex space-x-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                    Update
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

    const jenisMapping = {
        'Gitar': ['Gitar Elektrik Stratocaster', 'Gitar Elektrik Telecaster', 'Gitar Elektrik Les Paul', 'Gitar Akustik Dreadnought', 'Gitar Akustik Jumbo', 'Gitar Akustik-Elektrik'],
        'Bass': ['Bass Elektrik 4-Senar (Jazz Bass)', 'Bass Elektrik 4-Senar (Precision Bass)', 'Bass Elektrik 5-Senar', 'Bass Akustik'],
        'Drum': ['Drum Akustik Set (Standard)', 'Drum Elektrik Set', 'Cajon', 'Cymbal Set (Hi-hat, Crash, Ride)'],
        'Keyboard': ['Piano Digital (88 Keys)', 'Synthesizer', 'Workstation Keyboard', 'MIDI Controller'],
        'Amplifier': ['Ampli Gitar Combo', 'Ampli Gitar Head Cabinet', 'Ampli Bass Combo', 'Ampli Bass Head Cabinet'],
        'Microphone': ['Mic Vokal (Kabel)', 'Mic Instrumen (Kabel)', 'Mic Wireless (Handheld)', 'Mic Wireless (Clip-On)'],
        'Lainnya': ['Sound System (Speaker Aktif + Mixer)', 'Monitor Panggung', 'Stand Mic', 'Stand Keyboard', 'Stand Gitar', 'Kabel Jack & XLR', 'DI Box']
    };

    // Ambil nilai 'jenis' yang sekarang dari data controller
    const currentJenis = "{{ old('jenis', $alat['jenis'] ?? '') }}";

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
                // Pilih opsi yang sesuai dengan data yang ada
                if (jenis === currentJenis) {
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

    // Panggil fungsi ini saat halaman dimuat agar dropdown 'jenis' langsung terisi
    updateJenisOptions();
});
</script>
@endpush