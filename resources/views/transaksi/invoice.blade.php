@extends('layouts.app')

@section('title', 'Nota Transaksi - SIMINES')

@section('styles')
    <style>
        body { background: #fff; font-family: 'Plus Jakarta Sans', sans-serif; }
        .invoice-box { padding: 40px; border: 1px solid #eee; box-shadow: 0 0 20px rgba(0, 0, 0, .05); max-width: 800px; margin: 30px auto; border-radius: 20px; }
        @media print {
            .no-print { display: none; }
            .invoice-box { border: none; box-shadow: none; padding: 0; margin: 0; }
            body { padding: 0; }
            .main-content { margin-top: 0 !important; }
        }
        .header-nota { border-bottom: 2px dashed #eee; padding-bottom: 20px; margin-bottom: 30px; }
    </style>
@endsection

@section('content')
<div class="container my-4 no-print">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm rounded-pill px-3"><i class="bi bi-arrow-left"></i> Kembali</a>
        <button onclick="window.print()" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm"><i class="bi bi-printer me-2"></i> Cetak Nota</button>
    </div>
</div>

<div class="invoice-box">
    <div class="header-nota text-center">
        <h2 class="fw-bold mb-0 text-primary">SIMINES</h2>
        <p class="mb-0 text-muted small">Sistem Informasi Minuman Es Berbasis Aplikasi</p>
        <small class="text-muted">Waingapu, Sumba Timur</small>
    </div>

    <div class="row mb-5">
        <div class="col-6">
            <h6 class="fw-bold text-dark mb-3">PENERIMA:</h6>
            <p class="mb-1 fw-bold">{{ $transaksi->user->nama }}</p>
            <p class="mb-1 text-muted small">{{ $transaksi->user->no_hp }}</p>
            <p class="mb-0 text-muted small" style="max-width: 250px;">{{ $transaksi->user->alamat }}</p>
        </div>
        <div class="col-6 text-end">
            <h6 class="fw-bold text-dark mb-3">INFO TRANSAKSI:</h6>
            <p class="mb-1 small">No. Nota: <strong>#{{ $transaksi->id_transaksi }}</strong></p>
            <p class="mb-1 small text-muted">Tanggal: {{ date('d/m/Y H:i', strtotime($transaksi->tanggal)) }}</p>
            <p class="mb-0">
                <span class="badge {{ $transaksi->status == 'Paid' ? 'bg-success' : 'bg-warning text-dark' }} rounded-pill px-3">
                    {{ $transaksi->status }}
                </span>
            </p>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-borderless">
            <thead class="border-bottom">
                <tr class="text-muted small">
                    <th class="ps-0 py-3">NAMA MINUMAN</th>
                    <th class="text-center py-3">QTY</th>
                    <th class="text-end py-3">HARGA</th>
                    <th class="text-end pe-0 py-3">SUBTOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaksi->detailTransaksi as $detail)
                <tr class="border-bottom border-light">
                    <td class="ps-0 py-3 fw-bold text-dark">{{ $detail->produk->nama_produk }}</td>
                    <td class="text-center py-3 text-muted">{{ $detail->jumlah }}</td>
                    <td class="text-end py-3 text-muted">Rp {{ number_format($detail->produk->harga, 0, ',', '.') }}</td>
                    <td class="text-end pe-0 py-3 fw-bold">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="row justify-content-end mt-4">
        <div class="col-lg-5 col-md-7">
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted small">Total Barang</span>
                <span class="fw-600">Rp {{ number_format($transaksi->total_harga - $transaksi->biaya_ongkir, 0, ',', '.') }}</span>
            </div>
            <div class="d-flex justify-content-between mb-3 pb-3 border-bottom border-light">
                <span class="text-muted small">Ongkos Kirim ({{ $transaksi->jarak }})</span>
                <span class="text-muted small">Rp {{ number_format($transaksi->biaya_ongkir, 0, ',', '.') }}</span>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <span class="fw-bold text-dark">TOTAL BAYAR</span>
                <h4 class="fw-bold text-primary mb-0">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</h4>
            </div>
        </div>
    </div>

    <div class="mt-5 pt-5 text-center border-top border-light">
        <p class="mb-1 fw-bold text-dark">Terima kasih atas pesanan Anda!</p>
        <p class="text-muted small mb-0">Minuman segar, haripun jadi bugar.</p>
    </div>
</div>
@endsection
