<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Penyewaan #{{ $riwayat['id'] }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* CSS khusus untuk menyembunyikan tombol saat mencetak */
        @media print {
            body { -webkit-print-color-adjust: exact; }
            .no-print { display: none; }
        }
    </style>
</head>
<body class="bg-gray-100">

    <div class="container mx-auto max-w-3xl my-10">
        <div class="mb-4 flex justify-end gap-2 no-print">
            <a href="{{ route('riwayat.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg text-sm font-medium">
                &larr; Kembali ke Riwayat
            </a>
            <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                🖨️ Cetak Nota
            </button>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-8 md:p-12">
            <header class="flex justify-between items-start pb-6 border-b border-gray-200">
                <div>
                    <h1 class="text-4xl font-bold text-gray-800">INVOICE</h1>
                    <p class="text-gray-500">Sewa Alat Band Anda</p>
                </div>
                <div class="text-right">
                    <p class="text-xl font-semibold text-gray-700">Nota #{{ str_pad($riwayat['id'], 4, '0', STR_PAD_LEFT) }}</p>
                    <p class="text-sm text-gray-500">Tanggal: {{ \Carbon\Carbon::parse($riwayat['tanggal_sewa'])->isoFormat('D MMMM YYYY') }}</p>
                </div>
            </header>

            <section class="my-8 flex justify-between">
                <div>
                    <h2 class="text-sm font-semibold text-gray-600 uppercase mb-2">Ditagihkan Kepada:</h2>
                    <p class="text-lg font-bold text-gray-900">{{ $riwayat['nama_penyewa'] }}</p>
                    <p class="text-gray-600">{{ $riwayat['no_telepon'] }}</p>
                </div>
                <div class="text-right">
                     <h2 class="text-sm font-semibold text-gray-600 uppercase mb-2">Periode Sewa:</h2>
                     <p class="text-gray-800">{{ \Carbon\Carbon::parse($riwayat['tanggal_sewa'])->isoFormat('D MMM YYYY') }} - {{ \Carbon\Carbon::parse($riwayat['tanggal_kembali'])->isoFormat('D MMM YYYY') }}</p>
                     <p class="text-gray-600">{{ $riwayat['durasi'] }} hari</p>
                </div>
            </section>

            <section>
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deskripsi</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Harga/Hari</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Durasi</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-800">{{ $riwayat['nama_alat'] }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">Rp {{ number_format($riwayat['total_harga'] / $riwayat['durasi'], 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center">{{ $riwayat['durasi'] }} hari</td>
                            <td class="px-6 py-4 text-right font-semibold">Rp {{ number_format($riwayat['total_harga'], 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </section>

            <section class="mt-8 flex justify-end">
                <div class="w-full md:w-1/2 lg:w-1/3">
                    <div class="flex justify-between py-3">
                        <span class="font-medium text-gray-600">Subtotal</span>
                        <span class="text-gray-800">Rp {{ number_format($riwayat['total_harga'], 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between py-4 bg-gray-100 px-4 mt-2 rounded-lg">
                        <span class="font-bold text-xl text-gray-900">GRAND TOTAL</span>
                        <span class="font-bold text-xl text-gray-900">Rp {{ number_format($riwayat['total_harga'], 0, ',', '.') }}</span>
                    </div>
                </div>
            </section>

            <footer class="mt-12 pt-6 border-t border-gray-200 text-center text-sm text-gray-500">
                <p>Terima kasih telah menyewa alat musik di tempat kami.</p>
                <p>Pembayaran dapat dilakukan melalui transfer atau tunai.</p>
            </footer>
        </div>
    </div>
</body>
</html>