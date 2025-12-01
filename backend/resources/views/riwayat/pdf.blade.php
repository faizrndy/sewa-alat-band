<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Invoice #{{ $trx->kode_transaksi }} - Kratak FC</title>

    <style>
        @page {
            margin: 0px;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            background-color: #fff;
            font-size: 13px;
        }
        /* HEADER HITAM */
        .header {
            background-color: #111;
            color: #fff;
            padding: 30px 40px;
            display: table;
            width: 100%;
        }
        .header-logo {
            display: table-cell;
            vertical-align: middle;
            width: 50%;
        }
        .header-logo img {
            width: 120px; /* Sesuaikan ukuran logo */
        }
        .header-info {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
            width: 50%;
        }
        .header-info h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #e11d48; /* Warna Rose-600 */
        }
        .header-info p {
            margin: 5px 0 0;
            font-size: 12px;
            color: #bbb;
        }

        /* CONTENT */
        .content {
            padding: 40px;
        }

        /* INFO BOX (Penyewa & Invoice) */
        .info-grid {
            width: 100%;
            margin-bottom: 30px;
            display: table;
        }
        .info-col {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        .label {
            font-size: 10px;
            font-weight: bold;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }
        .value {
            font-size: 14px;
            font-weight: bold;
            color: #000;
            margin-bottom: 15px;
        }
        .value-address {
            font-size: 13px;
            line-height: 1.4;
            color: #444;
            max-width: 80%;
        }

        /* TABEL ITEM */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background-color: #f8f8f8;
            color: #111;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 12px 15px;
            text-align: left;
            border-bottom: 2px solid #e11d48; /* Garis aksen merah */
        }
        td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
            font-size: 13px;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        tr:nth-child(even) { background-color: #fafafa; }

        /* TOTAL SECTION */
        .total-section {
            width: 100%;
            margin-top: 20px;
            text-align: right;
        }
        .total-row {
            display: inline-block;
            min-width: 250px;
            padding: 5px 0;
        }
        .total-label {
            display: inline-block;
            width: 100px;
            color: #666;
            font-size: 12px;
        }
        .total-value {
            display: inline-block;
            width: 140px;
            font-weight: bold;
            font-size: 14px;
            color: #111;
        }
        .grand-total {
            margin-top: 10px;
            padding-top: 10px;
            border-top: 2px solid #111;
        }
        .grand-total .total-value {
            font-size: 18px;
            color: #e11d48; /* Merah */
        }

        /* FOOTER */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: #f8f8f8;
            padding: 20px 40px;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #eee;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            background-color: #eee;
            color: #555;
        }
        .status-success { background-color: #dcfce7; color: #166534; }
        .status-pending { background-color: #fef9c3; color: #854d0e; }
        .status-failed { background-color: #fee2e2; color: #991b1b; }

    </style>
</head>

<body>

    <div class="header">
        <div class="header-logo">
            <img src="{{ public_path('images/logo1.png') }}" alt="Logo Kratak FC">
        </div>
        <div class="header-info">
            <h1>INVOICE</h1>
            <p>#{{ $trx->kode_transaksi }}</p>
            <p style="margin-top: 5px;">{{ $trx->created_at->format('d F Y, H:i') }}</p>
            <div style="margin-top: 10px;">
                <span class="status-badge status-{{ $trx->status }}">
                    {{ $trx->status }}
                </span>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="info-grid">
            <div class="info-col">
                <div class="label">Penyewa</div>
                <div class="value">{{ $trx->nama }}</div>
                
                <div class="label">Kontak</div>
                <div class="value">{{ $trx->telepon }}</div>
            </div>

            <div class="info-col">
                <div class="label">Metode Pengiriman</div>
                <div class="value">
                    {{ ucfirst($trx->metode_pengiriman) }} 
                    @if($trx->metode_pengiriman == 'antar') (Kurir) @endif
                </div>

                @if($trx->metode_pengiriman == 'antar')
                <div class="label">Alamat Pengiriman</div>
                <div class="value-address">
                    {{ $trx->alamat }} <br>
                    <small style="color: #666;">({{ $trx->deskripsi_lokasi }})</small>
                </div>
                @endif
            </div>
        </div>

        <div class="label">Rincian Sewa</div>
        <table>
            <thead>
                <tr>
                    <th width="40%">Item / Alat</th>
                    <th width="25%">Tanggal Sewa</th>
                    <th class="text-center" width="10%">Qty</th>
                    <th class="text-right" width="25%">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($trx->items as $item)
                <tr>
                    <td>
                        <strong>{{ $item->nama_alat }}</strong><br>
                        <small style="color: #666;">Rp {{ number_format($item->harga_sewa, 0, ',', '.') }} / hari</small>
                    </td>
                    <td>
                        {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M') }} 
                        - 
                        {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d M Y') }}
                        <br>
                        <small>({{ $item->lama_hari }} Hari)</small>
                    </td>
                    <td class="text-center">{{ $item->jumlah }}</td>
                    <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-section">
            <div class="total-row">
                <span class="total-label">Subtotal</span>
                <span class="total-value">Rp {{ number_format($trx->total_sewa, 0, ',', '.') }}</span>
            </div>
            
            @if($trx->tarif_antar > 0)
            <div class="total-row">
                <span class="total-label">Biaya Antar</span>
                <span class="total-value">Rp {{ number_format($trx->tarif_antar, 0, ',', '.') }}</span>
            </div>
            @endif

            <div class="total-row grand-total">
                <span class="total-label">TOTAL BAYAR</span>
                <span class="total-value">Rp {{ number_format($trx->total_bayar, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <div class="footer">
        Terima kasih telah menyewa di Kratak FC. <br>
        Dokumen ini sah dan diproses secara otomatis oleh komputer.
    </div>

</body>
</html>