<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Nota Penyewaan - {{ $trx->kode_transaksi }}</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            padding: 25px;
            font-size: 14px;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
        }

        .logo {
            width: 110px;
            margin-bottom: 8px;
        }

        .title {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .subtitle {
            margin: 0;
            font-size: 13px;
        }

        .box {
            border: 1px solid #cfcfcf;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 22px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 7px;
        }

        th {
            background: #f3f3f3;
            font-weight: bold;
        }

        .footer {
            margin-top: 35px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }

    </style>
</head>

<body>

    <!-- HEADER -->
    <div class="header">
        <img
            src="{{ public_path('images/logo.png') }}"
            alt="Logo Toko"
            class="logo"
        >

        <div class="title">Kratak FC - Nota Penyewaan</div>
        <div class="subtitle">{{ $trx->kode_transaksi }}</div>
    </div>

    <!-- DATA PENYEWA -->
    <div class="box">
        <h3>Data Penyewa</h3>

        <p><strong>Nama:</strong> {{ $trx->nama }}</p>
        <p><strong>Telepon:</strong> {{ $trx->telepon }}</p>
        <p><strong>Alamat:</strong> {{ $trx->alamat }}</p>
    </div>

    <!-- DETAIL PENYEWAAN -->
    <div class="box">
        <h3>Detail Penyewaan</h3>

        <table>
            <thead>
                <tr>
                    <th>Nama Alat</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($trx->items as $item)
                <tr>
                    <td>{{ $item->nama_alat }}</td>
                    <td>{{ $item->tanggal_mulai }}</td>
                    <td>{{ $item->tanggal_selesai }}</td>
                    <td>{{ $item->jumlah }}</td>
                    <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <h3 style="margin-top: 18px;">
            Total Bayar: <strong>Rp {{ number_format($trx->total_bayar, 0, ',', '.') }}</strong>
        </h3>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        Dicetak pada: {{ date('d M Y H:i') }}
    </div>

</body>
</html>
