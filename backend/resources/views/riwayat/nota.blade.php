<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nota Penyewaan | {{ $trx->kode_transaksi }}</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 30px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header img { width: 120px; margin-bottom: 10px; }
        .box { border: 1px solid #ddd; padding: 20px; margin-top: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table td, table th {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .right { text-align: right; }
    </style>
</head>
<body>

    <!-- 🔥 LOGO TAMPIL DI SINI -->
    <div style="text-align: center; margin-bottom: 20px">

        <img src="{{ asset('images/logo.png') }}"
             alt="Logo Toko"
             style="width: 120px; display:block; margin:0 auto 10px auto;">

        <h2 style="margin: 0; font-size: 22px;">Kratak FC - Nota Penyewaan</h2>

        <p style="margin: 2px 0 0 0; font-size: 14px;">
            {{ $trx->kode_transaksi }}
        </p>

    </div>


    <div class="box">
        <h3>Data Penyewa</h3>
        <p><strong>Nama:</strong> {{ $trx->nama }}</p>
        <p><strong>Telepon:</strong> {{ $trx->telepon }}</p>
        <p><strong>Alamat:</strong> {{ $trx->alamat }}</p>
    </div>

    <div class="box">
        <h3>Detail Penyewaan</h3>
        <table>
            <tr>
                <th>Alat</th>
                <th>Tanggal</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Subtotal</th>
            </tr>

            @foreach($trx->items as $item)
            <tr>
                <td>{{ $item->nama_alat }}</td>
                <td>{{ $item->tanggal_mulai }} → {{ $item->tanggal_selesai }}</td>
                <td>{{ $item->jumlah }}</td>
                <td>Rp {{ number_format($item->harga_sewa, 0, ',', '.') }}</td>
                <td class="right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </table>

        <h3 class="right" style="margin-top: 15px;">
            Total Bayar:
            <strong>Rp {{ number_format($trx->total_bayar, 0, ',', '.') }}</strong>
        </h3>
    </div>

    <div style="text-align: center; margin-top: 40px;">
        <a href="{{ route('riwayat.pdf', $trx->id) }}"
           style="background: #dc2626; color: white; padding: 12px 24px;
           border-radius: 8px; text-decoration: none; font-size: 16px;"
           target="_blank">
            💾 Download PDF
        </a>
    </div>


</body>
</html>
