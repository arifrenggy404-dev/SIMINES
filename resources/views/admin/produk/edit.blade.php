<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - SIMINES Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f7f6; }
        .sidebar { min-width: 250px; max-width: 250px; background: #2c3e50; color: #fff; min-height: 100vh; }
        .sidebar .nav-link { color: rgba(255,255,255,0.7); margin: 5px 15px; }
        .sidebar .nav-link.active { color: #fff; background: rgba(255,255,255,0.1); }
        .main-content { width: 100%; padding: 20px; }
        .form-container { background: white; border-radius: 10px; padding: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
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
                <li class="nav-item"><a class="nav-link active" href="{{ route('admin.produk.index') }}"><i class="bi bi-box me-2"></i> Kelola Produk</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.kategori.index') }}"><i class="bi bi-tags me-2"></i> Kelola Kategori</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.pesanan.index') }}"><i class="bi bi-cart me-2"></i> Kelola Pesanan</a></li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">
            <div class="mb-4">
                <a href="{{ route('admin.produk.index') }}" class="btn btn-outline-secondary btn-sm mb-2"><i class="bi bi-arrow-left"></i> Kembali</a>
                <h2 class="fw-bold">Edit Produk</h2>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="form-container">
                        <form action="{{ route('admin.produk.update', $produk->id_produk) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Nama Produk</label>
                                    <input type="text" name="nama_produk" class="form-control" value="{{ old('nama_produk', $produk->nama_produk) }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Kategori</label>
                                    <select name="id_kategori" class="form-select">
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach($kategori as $k)
                                            <option value="{{ $k->id_kategori }}" {{ $produk->id_kategori == $k->id_kategori ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3 text-center">
                                <p class="small text-muted mb-2">Foto Saat Ini:</p>
                                <img src="{{ asset('storage/' . $produk->foto) }}" class="rounded shadow-sm mb-3" style="width: 100px; height: 100px; object-fit: cover;">
                                <input type="file" name="foto" class="form-control" accept="image/*">
                                <small class="text-muted">Kosongkan jika tidak ingin mengubah foto</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">Harga Modal (Rp)</label>
                                    <input type="number" name="harga_modal" class="form-control" value="{{ old('harga_modal', $produk->harga_modal) }}" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">Harga Jual (Rp)</label>
                                    <input type="number" name="harga" class="form-control" value="{{ old('harga', $produk->harga) }}" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">Stok</label>
                                    <input type="number" name="stok" class="form-control" value="{{ old('stok', $produk->stok) }}" required>
                                </div>
                            </div>

                            <hr class="my-4">
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary py-2 fw-bold">UPDATE PRODUK</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
