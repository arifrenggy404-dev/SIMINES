<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Banner - SIMINES Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f7f6; }
        .sidebar { min-width: 250px; max-width: 250px; background: #2c3e50; color: #fff; min-height: 100vh; }
        .sidebar .nav-link { color: rgba(255,255,255,0.7); margin: 5px 15px; }
        .sidebar .nav-link.active { color: #fff; background: rgba(255,255,255,0.1); }
        .main-content { width: 100%; padding: 20px; }
        .form-container { background: white; border-radius: 15px; padding: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
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
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">
            <div class="mb-4">
                <a href="{{ route('admin.banner.index') }}" class="btn btn-outline-secondary btn-sm mb-2"><i class="bi bi-arrow-left"></i> Kembali</a>
                <h2 class="fw-bold">Unggah Banner Baru</h2>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-7">
                    <div class="form-container">
                        <form action="{{ route('admin.banner.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-4">
                                <label class="form-label fw-bold">Gambar Banner</label>
                                <input type="file" name="gambar" class="form-control" accept="image/*" required onchange="previewBanner(this)">
                                <small class="text-muted">Rekomendasi ukuran: 1200 x 400 pixel (Lanskap)</small>
                                <div class="mt-3 text-center d-none" id="preview-container">
                                    <img id="preview-img" src="#" class="img-fluid rounded shadow-sm" style="max-height: 200px;">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Judul Banner (Opsional)</label>
                                <input type="text" name="judul" class="form-control" placeholder="Contoh: Promo Spesial Weekend">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Subjudul / Deskripsi Singkat</label>
                                <input type="text" name="subjudul" class="form-control" placeholder="Contoh: Diskon 50% untuk semua varian boba">
                            </div>

                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label class="form-label fw-bold">Urutan Tampil</label>
                                    <input type="number" name="urutan" class="form-control" value="1">
                                </div>
                                <div class="col-6 mb-3 d-flex align-items-end pb-2">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" checked>
                                        <label class="form-check-label fw-bold" for="is_active">Aktifkan Sekarang</label>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary py-2 fw-bold shadow-sm">SIMPAN BANNER</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function previewBanner(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-img').src = e.target.result;
                document.getElementById('preview-container').classList.remove('d-none');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
</body>
</html>
