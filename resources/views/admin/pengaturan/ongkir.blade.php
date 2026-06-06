<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Ongkir - SIMINES Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <style>
        body { background-color: #f4f7f6; }
        .sidebar { min-width: 250px; max-width: 250px; background: #2c3e50; color: #fff; min-height: 100vh; }
        .sidebar .nav-link { color: rgba(255,255,255,0.7); margin: 5px 15px; }
        .sidebar .nav-link.active { color: #fff; background: rgba(255,255,255,0.1); }
        .main-content { width: 100%; padding: 20px; }
        .table-container { background: white; border-radius: 10px; padding: 20px; }
        #map { height: 400px; border-radius: 10px; border: 2px solid #ddd; }
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
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.users.index') }}"><i class="bi bi-people me-2"></i> Kelola User</a></li>
                <li class="nav-item"><a class="nav-link active" href="{{ route('admin.pengaturan.ongkir') }}"><i class="bi bi-geo-alt me-2"></i> Pengaturan Ongkir</a></li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">
            <h2 class="fw-bold mb-4">Pengaturan Ongkir & Lokasi Toko</h2>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            @endif

            <div class="row">
                <div class="col-lg-8">
                    <div class="table-container shadow-sm mb-4">
                        <h5 class="fw-bold mb-3">Tentukan Lokasi Toko (Titik Awal Pengantaran)</h5>
                        <p class="text-muted small">Geser penanda (pin) di bawah ini ke lokasi tepat toko Anda berdiri.</p>
                        <div id="map"></div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="table-container shadow-sm">
                        <h5 class="fw-bold mb-4">Parameter Operasional</h5>
                        <form action="{{ route('admin.pengaturan.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="toko_latitude" id="lat" value="{{ $pengaturan->toko_latitude }}">
                            <input type="hidden" name="toko_longitude" id="lng" value="{{ $pengaturan->toko_longitude }}">

                            <div class="mb-4 p-3 bg-light rounded-4 border">
                                <div class="form-check form-switch fs-5">
                                    <input class="form-check-input" type="checkbox" name="manual_tutup" id="manual_tutup" {{ $pengaturan->manual_tutup ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold text-danger" for="manual_tutup">Tutup Toko Manual</label>
                                </div>
                                <small class="text-muted d-block mt-1">Gunakan ini jika stok habis total atau toko libur mendadak.</small>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Jam Buka</label>
                                    <input type="time" name="jam_buka" class="form-control" value="{{ substr($pengaturan->jam_buka, 0, 5) }}" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Jam Tutup</label>
                                    <input type="time" name="jam_tutup" class="form-control" value="{{ substr($pengaturan->jam_tutup, 0, 5) }}" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="harga_per_km" class="form-label small fw-bold">Harga Per KM (Rp)</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" name="harga_per_km" id="harga_per_km" class="form-control" value="{{ $pengaturan->harga_per_km }}" required>
                                </div>
                                <small class="text-muted">Contoh: 2000</small>
                            </div>

                            <div class="mb-3">
                                <label for="gratis_ongkir_km" class="form-label small fw-bold">Jarak Gratis Ongkir (KM)</label>
                                <div class="input-group">
                                    <input type="number" name="gratis_ongkir_km" id="gratis_ongkir_km" class="form-control" value="{{ $pengaturan->gratis_ongkir_km }}" required>
                                    <span class="input-group-text">KM</span>
                                </div>
                                <small class="text-muted">Pelanggan dalam radius ini tidak dikenakan biaya.</small>
                            </div>

                            <div class="mb-4">
                                <label for="max_jarak_km" class="form-label small fw-bold text-danger">Batas Maksimal Pengiriman (KM)</label>
                                <div class="input-group">
                                    <input type="number" name="max_jarak_km" id="max_jarak_km" class="form-control" value="{{ $pengaturan->max_jarak_km }}" required>
                                    <span class="input-group-text">KM</span>
                                </div>
                                <small class="text-muted">Sistem akan menolak pesanan di luar radius ini.</small>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary py-2 fw-bold shadow-sm">SIMPAN PENGATURAN</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    // Koordinat awal (dari database)
    var initialLat = {{ $pengaturan->toko_latitude }};
    var initialLng = {{ $pengaturan->toko_longitude }};

    // Inisialisasi Map
    var map = L.map('map').setView([initialLat, initialLng], 15);

    // Tambah Tile Layer OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    // Tambah Marker yang bisa digeser
    var marker = L.marker([initialLat, initialLng], {
        draggable: true
    }).addTo(map);

    // Event saat marker digeser
    marker.on('dragend', function(e) {
        var lat = marker.getLatLng().lat;
        var lng = marker.getLatLng().lng;
        document.getElementById('lat').value = lat;
        document.getElementById('lng').value = lng;
    });

    // Event saat map diklik (pindah marker ke lokasi klik)
    map.on('click', function(e) {
        var lat = e.latlng.lat;
        var lng = e.latlng.lng;
        marker.setLatLng([lat, lng]);
        document.getElementById('lat').value = lat;
        document.getElementById('lng').value = lng;
    });
</script>
</body>
</html>
