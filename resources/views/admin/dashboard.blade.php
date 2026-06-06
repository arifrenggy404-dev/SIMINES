<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SIMINES</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f7f6; }
        .sidebar { min-width: 250px; max-width: 250px; background: #2c3e50; color: #fff; min-height: 100vh; transition: all 0.3s; }
        .sidebar .nav-link { color: rgba(255,255,255,0.7); border-radius: 5px; margin: 5px 15px; transition: 0.3s; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background: rgba(255,255,255,0.1); }
        .sidebar .sidebar-header { padding: 20px; background: #1a252f; text-align: center; }
        .main-content { width: 100%; padding: 20px; }
        .card { border: none; border-radius: 10px; box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); }
        .table-container { background: white; border-radius: 10px; padding: 20px; }
    </style>
</head>
<body>

<div class="d-flex">
    <!-- Sidebar -->
    <nav class="sidebar shadow">
        <div class="sidebar-header">
            <h4 class="mb-0 fw-bold text-primary">SIMINES</h4>
            <small class="text-muted">Admin Panel</small>
        </div>
        <div class="mt-4">
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.produk.index') }}"><i class="bi bi-box me-2"></i> Kelola Produk</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.kategori.index') }}"><i class="bi bi-tags me-2"></i> Kelola Kategori</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.banner.index') }}"><i class="bi bi-image me-2"></i> Kelola Banner</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.pesanan.index') }}"><i class="bi bi-cart me-2"></i> Kelola Pesanan</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.voucher.index') }}"><i class="bi bi-ticket-perforated me-2"></i> Kelola Voucher</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.users.index') }}"><i class="bi bi-people me-2"></i> Kelola Pelanggan</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.pengaturan.ongkir') }}"><i class="bi bi-geo-alt me-2"></i> Pengaturan Ongkir</a></li>
                <li class="nav-item mt-4">
                    <hr class="mx-3 border-secondary">
                </li>
                <li class="nav-item mt-2">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="nav-link border-0 bg-transparent w-100 text-start text-danger">
                            <i class="bi bi-box-arrow-right me-2"></i> Keluar
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>
<!-- Main Content -->
<div class="main-content">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Admin Dashboard</h2>
            <a href="{{ route('admin.laporan.cetak') }}" class="btn btn-danger shadow-sm rounded-pill px-4 fw-bold">
                <i class="bi bi-file-earmark-pdf me-2"></i> CETAK LAPORAN PDF
            </a>
        </div>
                <div class="text-muted">Admin: <strong>{{ auth()->user()->nama }}</strong></div>
            </div>

            <!-- Stats Overview (Bab II 2.2.2) -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card p-3 bg-primary text-white h-100 shadow-sm">
                        <small>Pendapatan Ongkir</small>
                        <h3 class="mb-0 fw-bold">Rp {{ number_format($totalPendapatanOngkir, 0, ',', '.') }}</h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p-3 bg-danger text-white h-100 shadow-sm">
                        <small>Operasional (Bensin)</small>
                        <h3 class="mb-0 fw-bold">Rp {{ number_format($totalOperasional, 0, ',', '.') }}</h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p-3 bg-success text-white h-100 shadow-sm">
                        <small>Profit Bersih Ongkir</small>
                        <h3 class="mb-0 fw-bold">Rp {{ number_format($keuntunganBersihOngkir, 0, ',', '.') }}</h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p-3 bg-info text-white h-100 shadow-sm">
                        <small>Stok Menipis (<10)</small>
                        <h3 class="mb-0 fw-bold">{{ $produkMenipis->count() }} Item</h3>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <!-- Low Stock Alert -->
                <div class="col-lg-6">
                    <div class="table-container shadow-sm h-100">
                        <h5 class="fw-bold mb-3 text-danger"><i class="bi bi-exclamation-triangle me-2"></i> Peringatan Stok</h5>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead><tr><th>Nama Produk</th><th>Sisa Stok</th></tr></thead>
                                <tbody>
                                    @forelse($produkMenipis as $p)
                                    <tr><td>{{ $p->nama_produk }}</td><td class="text-danger fw-bold">{{ $p->stok }}</td></tr>
                                    @empty
                                    <tr><td colspan="2" class="text-muted">Semua stok aman.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Best Seller -->
                <div class="col-lg-6">
                    <div class="table-container shadow-sm h-100">
                        <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-star-fill me-2"></i> Menu Terlaris</h5>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead><tr><th>Nama Produk</th><th>Terjual</th></tr></thead>
                                <tbody>
                                    @forelse($terlaris as $item)
                                    <tr><td>{{ $item->produk->nama_produk }}</td><td class="fw-bold text-success">{{ $item->total_terjual }} Cup</td></tr>
                                    @empty
                                    <tr><td colspan="2" class="text-muted">Belum ada data penjualan.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profit Penjualan Produk (Bab II 2.2) -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card p-4 border-start border-primary border-4 shadow-sm">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Keuntungan Penjualan Minuman (Margin Bersih Produk)</h6>
                                <h2 class="fw-bold mb-0 text-primary">Rp {{ number_format($profitProduk, 0, ',', '.') }}</h2>
                                <small class="text-muted small italic">*Dihitung dari (Harga Jual - Biaya Produksi) per item dari pesanan lunas</small>
                            </div>
                            <i class="bi bi-graph-up-arrow fs-1 text-primary opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tren Penjualan (Chart.js) -->
            <div class="card shadow-sm p-4 mb-4 border-0 rounded-4">
                <h5 class="fw-bold mb-4">Tren Penjualan (7 Hari Terakhir)</h5>
                <canvas id="salesChart" style="max-height: 250px;"></canvas>
            </div>

            <!-- Recent Orders -->
            <div class="table-container shadow-sm">
                <h5 class="mb-3 fw-bold">Transaksi Terbaru</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr><th>ID</th><th>Pelanggan</th><th>Total</th><th>Status</th><th>Aksi</th></tr>
                        </thead>
                        <tbody>
                            @foreach($transaksi->take(10) as $t)
                            <tr>
                                <td class="fw-bold">#{{ $t->id_transaksi }}</td>
                                <td>{{ $t->user->nama }}</td>
                                <td>Rp {{ number_format($t->total_harga, 0, ',', '.') }}</td>
                                <td>
                                    @php
                                        $color = ['Pending'=>'warning','Paid'=>'primary','Diproses'=>'info','Selesai'=>'success','Failed'=>'danger'][$t->status] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $color }} rounded-pill px-3">{{ $t->status }}</span>
                                </td>
                                <td><a href="{{ route('admin.pesanan.index') }}" class="btn btn-sm btn-outline-dark">Detail</a></td>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('salesChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Total Penjualan (Rp)',
                    data: @json($chartData),
                    borderColor: '#00d2ff',
                    backgroundColor: 'rgba(0, 210, 255, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#00d2ff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        grid: { color: '#f1f5f9' },
                        ticks: { callback: (val) => 'Rp ' + new Intl.NumberFormat('id-ID').format(val) }
                    },
                    x: { grid: { display: false } }
                }
            }
        });
    });
</script>
</body>
</html>
