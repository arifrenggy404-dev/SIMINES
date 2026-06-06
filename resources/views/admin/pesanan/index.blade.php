<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pesanan - SIMINES Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f7f6; }
        .sidebar { min-width: 250px; max-width: 250px; background: #2c3e50; color: #fff; min-height: 100vh; }
        .sidebar .nav-link { color: rgba(255,255,255,0.7); margin: 5px 15px; }
        .sidebar .nav-link.active { color: #fff; background: rgba(255,255,255,0.1); }
        .main-content { width: 100%; padding: 20px; }
        .table-container { background: white; border-radius: 10px; padding: 20px; }
    </style>
</head>
<body>

<div class="d-flex">
    <!-- Sidebar -->
    <nav class="sidebar shadow">
        <div class="p-3 text-center bg-dark">
            <h4 class="mb-0 fw-bold text-primary">SIMINES</h4>
        </div>
        <div class="mt-4">
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.produk.index') }}"><i class="bi bi-box me-2"></i> Kelola Produk</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.kategori.index') }}"><i class="bi bi-tags me-2"></i> Kelola Kategori</a></li>
                <li class="nav-item"><a class="nav-link active" href="{{ route('admin.pesanan.index') }}"><i class="bi bi-cart me-2"></i> Kelola Pesanan</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.voucher.index') }}"><i class="bi bi-ticket-perforated me-2"></i> Kelola Voucher</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.users.index') }}"><i class="bi bi-people me-2"></i> Kelola User</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.pengaturan.ongkir') }}"><i class="bi bi-geo-alt me-2"></i> Pengaturan Ongkir</a></li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">
            <h2 class="fw-bold mb-4">Kelola Pesanan</h2>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            @endif

            <div class="table-container shadow-sm">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Pelanggan</th>
                                <th>Item</th>
                                <th>Metode</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaksi as $t)
                            <tr>
                                <td class="fw-bold">#{{ $t->id_transaksi }}</td>
                                <td>
                                    {{ $t->user->nama }}<br>
                                    <small class="text-muted">{{ $t->user->no_hp }}</small><br>
                                    @if($t->is_reservasi)
                                        <span class="badge bg-warning text-dark small mt-1">
                                            <i class="bi bi-calendar-event"></i> RESERVASI: {{ date('d/m H:i', strtotime($t->jadwal_pengambilan)) }}
                                        </span><br>
                                    @endif
                                    <small class="text-info" style="font-size: 0.7rem;"><i class="bi bi-geo-alt"></i> {{ $t->alamat_pengiriman }}</small>
                                </td>
                                <td>
                                    @foreach($t->detailTransaksi as $detail)
                                        {{ $detail->produk->nama_produk }} ({{ $detail->jumlah }})<br>
                                    @endforeach
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $t->metode_pembayaran }}</span><br>
                                    <small class="text-muted">{{ number_format($t->jarak_km, 2) }} KM</small>
                                </td>
                                <td>Rp {{ number_format($t->total_harga, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge {{ $t->status == 'Paid' ? 'bg-success' : ($t->status == 'Pending' ? 'bg-warning text-dark' : 'bg-danger') }} rounded-pill px-3">
                                        {{ $t->status }}
                                    </span>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Update Status
                                        </button>
                                        <ul class="dropdown-menu shadow border-0">
                                            <li>
                                                <form action="{{ route('admin.pesanan.update', $t->id_transaksi) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="status" value="Diproses">
                                                    <button type="submit" class="dropdown-item py-2"><i class="bi bi-gear-fill me-2 text-primary"></i> Pembuatan Produk</button>
                                                </form>
                                            </li>
                                            <li>
                                                <form action="{{ route('admin.pesanan.update', $t->id_transaksi) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="status" value="Dikirim">
                                                    <button type="submit" class="dropdown-item py-2"><i class="bi bi-truck me-2 text-info"></i> Pengiriman</button>
                                                </form>
                                            </li>
                                            <li>
                                                <form action="{{ route('admin.pesanan.update', $t->id_transaksi) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="status" value="Selesai">
                                                    <button type="submit" class="dropdown-item py-2"><i class="bi bi-check-circle-fill me-2 text-success"></i> Telah Selesai</button>
                                                </form>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a href="{{ route('transaksi.invoice', $t->id_transaksi) }}" class="dropdown-item py-2"><i class="bi bi-receipt me-2"></i> Cetak Nota</a>
                                            </li>
                                            <li>
                                                <form action="{{ route('admin.pesanan.destroy', $t->id_transaksi) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item py-2 text-danger" onclick="return confirm('Hapus pesanan ini?')"><i class="bi bi-trash me-2"></i> Hapus Data</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
