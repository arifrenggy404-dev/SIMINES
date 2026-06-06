<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan SIMINES</title>
    <style>
        body { font-family: sans-serif; font-size: 10pt; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #00d2ff; padding-bottom: 10px; }
        .title { font-size: 18pt; font-weight: bold; color: #3a7bd5; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .table th { background-color: #f2f2f2; }
        .total-box { text-align: right; font-size: 12pt; font-weight: bold; margin-top: 20px; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 8pt; color: #888; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">SIMINES - LAPORAN PENJUALAN</div>
        <div>Kesegaran Digital UMKM Waingapu, Sumba Timur</div>
        <div>Tanggal Cetak: {{ date('d F Y H:i') }}</div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th>Item</th>
                <th>Metode</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksi as $t)
            <tr>
                <td>#{{ $t->id_transaksi }}</td>
                <td>{{ $t->tanggal }}</td>
                <td>{{ $t->user->nama }}</td>
                <td>
                    @foreach($t->detailTransaksi as $detail)
                        - {{ $detail->produk->nama_produk }} ({{ $detail->jumlah }})<br>
                    @endforeach
                </td>
                <td>{{ $t->metode_pembayaran }}</td>
                <td>Rp {{ number_format($t->total_harga, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-box">
        TOTAL PENDAPATAN: Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
    </div>

    <div class="footer">
        &copy; 2026 SIMINES Kelompok 9 - Universitas Kristen Wira Wacana Sumba
    </div>
</body>
</html>
