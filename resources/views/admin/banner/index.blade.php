<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Banner - SIMINES Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f7f6; }
        .sidebar { min-width: 250px; max-width: 250px; background: #2c3e50; color: #fff; min-height: 100vh; }
        .sidebar .nav-link { color: rgba(255,255,255,0.7); margin: 5px 15px; }
        .sidebar .nav-link.active { color: #fff; background: rgba(255,255,255,0.1); }
        .main-content { width: 100%; padding: 20px; }
        .table-container { background: white; border-radius: 10px; padding: 20px; }
        .banner-preview { width: 200px; height: 80px; object-fit: cover; border-radius: 8px; }
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
                <li class="nav-item"><a class="nav-link active" href="{{ route('admin.banner.index') }}"><i class="bi bi-image me-2"></i> Kelola Banner</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.pesanan.index') }}"><i class="bi bi-cart me-2"></i> Kelola Pesanan</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.voucher.index') }}"><i class="bi bi-ticket-perforated me-2"></i> Kelola Voucher</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.users.index') }}"><i class="bi bi-people me-2"></i> Kelola Pelanggan</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.pengaturan.ongkir') }}"><i class="bi bi-geo-alt me-2"></i> Pengaturan Ongkir</a></li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold">Kelola Banner Promo</h2>
                <a href="{{ route('admin.banner.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Banner</a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            @endif

            <div class="table-container shadow-sm">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Urutan</th>
                                <th>Gambar</th>
                                <th>Judul / Subjudul</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($banners as $b)
                            <tr>
                                <td>{{ $b->urutan }}</td>
                                <td>
                                    <img src="{{ asset($b->gambar) }}" class="banner-preview shadow-sm">
                                </td>
                                <td>
                                    <strong>{{ $b->judul ?? '-' }}</strong><br>
                                    <small class="text-muted">{{ $b->subjudul ?? '-' }}</small>
                                </td>
                                <td>
                                    @if($b->is_active)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.banner.edit', $b->id_banner) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('admin.banner.destroy', $b->id_banner) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus banner ini?')"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                            @if($banners->isEmpty())
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Belum ada banner promo.</td>
                            </tr>
                            @endif
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
