@extends('layouts.app')

@section('title', 'Pengaturan - SIMINES')

@section('styles')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <style>
        .settings-card { border: none; border-radius: 25px; background: var(--card-bg); box-shadow: 0 10px 40px rgba(0,0,0,0.05); overflow: hidden; }
        .nav-settings .nav-link { border: none; color: var(--text-muted); padding: 15px 25px; border-radius: 0; font-weight: 600; text-align: left; transition: 0.3s; border-left: 4px solid transparent; }
        .nav-settings .nav-link:hover { background: rgba(0, 210, 255, 0.05); color: var(--primary-color); }
        .nav-settings .nav-link.active { background: rgba(0, 210, 255, 0.1); color: var(--primary-color); border-left-color: var(--primary-color); }
        
        .form-control { border-radius: 12px; padding: 12px 18px; background: var(--light-bg); border-color: transparent; color: var(--text-main); }
        .form-control:focus { background: var(--light-bg); border-color: var(--primary-color); box-shadow: none; }
        
        #map-settings { height: 300px; border-radius: 15px; border: 2px solid var(--light-bg); margin-bottom: 15px; }
        
        .theme-switch { width: 50px; height: 26px; }
        
        .btn-save { border-radius: 12px; padding: 12px 30px; font-weight: 700; background: linear-gradient(45deg, #00d2ff, #3a7bd5); border: none; color: white; transition: 0.3s; }
        .btn-save:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0, 210, 255, 0.4); color: white; }

        /* Dark mode specific overrides for leaflet */
        body.dark-mode .leaflet-tile { filter: invert(100%) hue-rotate(180deg) brightness(95%) contrast(90%); }
    </style>
@endsection

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-lg-3">
            <div class="settings-card mb-4">
                <div class="p-4 text-center border-bottom border-light">
                    <img src="{{ $user->foto ? asset($user->foto) : 'https://ui-avatars.com/api/?name=' . urlencode($user->nama) . '&background=00d2ff&color=fff&size=100' }}" 
                         class="rounded-circle mb-3 shadow-sm border border-2 border-white" 
                         style="width: 70px; height: 70px; object-fit: cover;">
                    <h6 class="fw-bold mb-0 text-dark">{{ $user->nama }}</h6>
                    <small class="text-muted">{{ $user->email }}</small>
                </div>
                <div class="nav flex-column nav-settings" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <button class="nav-link active" id="tab-akun-link" data-bs-toggle="pill" data-bs-target="#tab-akun" type="button" role="tab"><i class="bi bi-person-gear me-2"></i> Profil & Lokasi</button>
                    <button class="nav-link" id="tab-keamanan-link" data-bs-toggle="pill" data-bs-target="#tab-keamanan" type="button" role="tab"><i class="bi bi-shield-lock me-2"></i> Keamanan</button>
                    <button class="nav-link" id="tab-tampilan-link" data-bs-toggle="pill" data-bs-target="#tab-tampilan" type="button" role="tab"><i class="bi bi-palette me-2"></i> Tampilan</button>
                    
                    <div class="mt-5 p-3 border-top border-light">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-link text-danger w-100 text-start text-decoration-none fw-bold small">
                                <i class="bi bi-box-arrow-right me-2"></i> Keluar Akun
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            @if(session('success'))
                <div class="alert alert-success rounded-4 border-0 shadow-sm mb-4">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                </div>
            @endif

            <div class="tab-content" id="v-pills-tabContent">
                <!-- TAB 1: AKUN & LOKASI -->
                <div class="tab-pane fade show active" id="tab-akun" role="tabpanel">
                    <div class="settings-card p-4 p-md-5">
                        <h4 class="fw-bold mb-4">Profil & Lokasi Utama</h4>
                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-4">
                                <!-- Foto Profil Section -->
                                <div class="col-12 mb-4 text-center">
                                    <div class="position-relative d-inline-block">
                                        <img id="preview-foto" src="{{ $user->foto ? asset($user->foto) : 'https://ui-avatars.com/api/?name=' . urlencode($user->nama) . '&background=00d2ff&color=fff&size=128' }}" 
                                             class="rounded-circle shadow-sm border border-4 border-white" 
                                             style="width: 120px; height: 120px; object-fit: cover;">
                                        <label for="foto-input" class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow" style="width: 35px; height: 35px; cursor: pointer; border: 3px solid white;">
                                            <i class="bi bi-camera-fill small"></i>
                                        </label>
                                        <input type="file" name="foto" id="foto-input" class="d-none" accept="image/*" onchange="previewImage(this)">
                                    </div>
                                    <p class="small text-muted mt-2 mb-0">Klik ikon kamera untuk ganti foto profil</p>
                                    @error('foto') <small class="text-danger d-block">{{ $message }}</small> @enderror
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Nama Lengkap</label>
                                        <input type="text" name="nama" class="form-control" value="{{ $user->nama }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Nomor HP (WhatsApp)</label>
                                        <input type="text" name="no_hp" class="form-control" value="{{ $user->no_hp }}" required>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label small fw-bold">Alamat Tertulis</label>
                                        <textarea name="alamat" id="alamat-settings" class="form-control" rows="5" required>{{ $user->alamat }}</textarea>
                                        <div id="loading-addr" class="text-primary mt-1 small d-none"><div class="spinner-border spinner-border-sm me-1"></div> Mencari alamat...</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-primary"><i class="bi bi-geo-alt-fill me-1"></i> Titik Map Pengiriman</label>
                                    <div id="map-settings"></div>
                                    <input type="hidden" name="latitude" id="lat-settings" value="{{ $user->latitude ?? '-9.6591' }}">
                                    <input type="hidden" name="longitude" id="lng-settings" value="{{ $user->longitude ?? '120.2633' }}">
                                    <p class="text-muted small" style="font-size: 0.7rem;">Geser pin merah untuk menentukan lokasi rumah Anda secara presisi agar kurir tidak bingung.</p>
                                </div>
                            </div>
                            <div class="text-end mt-4 pt-3 border-top border-light">
                                <button type="submit" class="btn btn-save">SIMPAN PERUBAHAN</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- TAB 2: KEAMANAN -->
                <div class="tab-pane fade" id="tab-keamanan" role="tabpanel">
                    <div class="settings-card p-4 p-md-5">
                        <h4 class="fw-bold mb-4">Keamanan Akun</h4>
                        <p class="text-muted mb-5">Ganti kata sandi Anda secara berkala untuk menjaga keamanan akun SIMINES.</p>
                        
                        <form action="{{ route('profile.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="nama" value="{{ $user->nama }}">
                            <input type="hidden" name="no_hp" value="{{ $user->no_hp }}">
                            <input type="hidden" name="alamat" value="{{ $user->alamat }}">
                            
                            <div class="row justify-content-center">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Password Baru</label>
                                        <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter">
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label small fw-bold">Ulangi Password Baru</label>
                                        <input type="password" name="password_confirmation" class="form-control" placeholder="Konfirmasi password">
                                    </div>
                                    <div class="d-grid mt-4">
                                        <button type="submit" class="btn btn-save">UPDATE PASSWORD</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- TAB 3: TAMPILAN -->
                <div class="tab-pane fade" id="tab-tampilan" role="tabpanel">
                    <div class="settings-card p-4 p-md-5">
                        <h4 class="fw-bold mb-4">Tampilan Aplikasi</h4>
                        
                        <div class="d-flex justify-content-between align-items-center p-4 bg-light rounded-4 mb-4" id="theme-container">
                            <div>
                                <h6 class="fw-bold mb-1">Mode Gelap (Dark Mode)</h6>
                                <p class="text-muted small mb-0">Mengurangi ketegangan mata di malam hari.</p>
                            </div>
                            <div class="form-check form-switch fs-4">
                                <input class="form-check-input theme-switch" type="checkbox" id="dark-mode-toggle" style="cursor: pointer;">
                            </div>
                        </div>

                        <div class="p-4 bg-light rounded-4">
                            <h6 class="fw-bold mb-2">Bahasa Utama</h6>
                            <select class="form-select form-control" disabled>
                                <option>Bahasa Indonesia</option>
                            </select>
                            <small class="text-muted mt-2 d-block">Bahasa lain akan tersedia segera.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    // --- PREVIEW FOTO ---
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-foto').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // --- DARK MODE LOGIC ---
    const darkToggle = document.getElementById('dark-mode-toggle');
    
    // Set initial state
    if (localStorage.getItem('theme') === 'dark') {
        darkToggle.checked = true;
    }

    darkToggle.addEventListener('change', function() {
        if (this.checked) {
            document.body.classList.add('dark-mode');
            localStorage.setItem('theme', 'dark');
        } else {
            document.body.classList.remove('dark-mode');
            localStorage.setItem('theme', 'light');
        }
    });

    // --- MAP LOGIC ---
    let mapSettings, markerSettings;
    
    function initMapSettings() {
        if (mapSettings) return;

        const initLat = parseFloat(document.getElementById('lat-settings').value);
        const initLng = parseFloat(document.getElementById('lng-settings').value);

        mapSettings = L.map('map-settings').setView([initLat, initLng], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OSM' }).addTo(mapSettings);

        markerSettings = L.marker([initLat, initLng], { draggable: true }).addTo(mapSettings);

        const updateData = (lat, lng) => {
            document.getElementById('lat-settings').value = lat;
            document.getElementById('lng-settings').value = lng;
            fetchAddr(lat, lng);
        };

        markerSettings.on('dragend', () => updateData(markerSettings.getLatLng().lat, markerSettings.getLatLng().lng));
        mapSettings.on('click', (e) => {
            markerSettings.setLatLng(e.latlng);
            updateData(e.latlng.lat, e.latlng.lng);
        });
    }

    function fetchAddr(lat, lng) {
        const loading = document.getElementById('loading-addr');
        loading.classList.remove('d-none');
        fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`)
            .then(res => res.json())
            .then(data => {
                if (data.display_name) document.getElementById('alamat-settings').value = data.display_name;
                loading.classList.add('d-none');
            }).catch(() => loading.classList.add('d-none'));
    }

    // Refresh map when tab is shown
    document.getElementById('tab-akun-link').addEventListener('shown.bs.tab', () => {
        if (mapSettings) mapSettings.invalidateSize();
    });

    document.addEventListener('DOMContentLoaded', () => {
        initMapSettings();
    });
</script>
@endsection
