<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pelanggan - SIMINES Admin</title>
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
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.pesanan.index') }}"><i class="bi bi-cart me-2"></i> Kelola Pesanan</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.voucher.index') }}"><i class="bi bi-ticket-perforated me-2"></i> Kelola Voucher</a></li>
                <li class="nav-item"><a class="nav-link active" href="{{ route('admin.users.index') }}"><i class="bi bi-people me-2"></i> Kelola Pelanggan</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.pengaturan.ongkir') }}"><i class="bi bi-geo-alt me-2"></i> Pengaturan Ongkir</a></li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">
            <h2 class="fw-bold mb-4">Daftar Pelanggan Terdaftar</h2>

            <div class="table-container shadow-sm">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>No. HP</th>
                                <th>Alamat</th>
                                <th>Tgl Bergabung</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td class="fw-bold">{{ $user->nama }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->no_hp }}</td>
                                <td class="small">{{ $user->alamat }}</td>
                                <td>{{ $user->created_at->format('d M Y') }}</td>
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
