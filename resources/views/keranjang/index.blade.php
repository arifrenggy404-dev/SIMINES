@extends('layouts.app')

@section('title', 'Keranjang Belanja - SIMINES')

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <style>
        body { background-color: #f8fbff; }
        .cart-card { border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.03); background: white; }
        .sticky-summary { position: sticky; top: 100px; }
        #map { height: 250px; border-radius: 12px; border: 1px solid #dee2e6; }
        .table thead th { border-top: none; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 1px; color: #8898aa; padding: 1.5rem 1rem; }
        .cart-item-row:hover { background-color: #fcfdfe; }
        .summary-item { display: flex; justify-content: space-between; margin-bottom: 1rem; font-size: 0.95rem; }
        .total-price { font-size: 1.5rem; font-weight: 800; color: var(--primary-color); }
        .badge-voucher { background-color: #e6fffa; color: #2d3748; border: 1px dashed #38b2ac; padding: 8px 15px; border-radius: 10px; width: 100%; }
        .btn-checkout-custom { border-radius: 15px; padding: 1rem; font-weight: 700; transition: 0.3s; background: linear-gradient(45deg, #00d2ff, #3a7bd5); border: none; color: white; }
        .btn-checkout-custom:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0, 210, 255, 0.3); color: white; }
        .btn-checkout-custom:disabled { background: #cbd5e0; transform: none; box-shadow: none; }
        
        /* New Address Selection Styles */
        .address-mode-card { border: 2px solid #f1f5f9; border-radius: 15px; padding: 15px; cursor: pointer; transition: 0.3s; position: relative; }
        .address-mode-card:hover { border-color: #cbd5e0; background-color: #f8fafc; }
        .mode-input:checked + .address-mode-card { border-color: var(--primary-color); background-color: #f0faff; }
        .mode-input:checked + .address-mode-card::after { content: '\F272'; font-family: 'bootstrap-icons'; position: absolute; top: 10px; right: 15px; color: var(--primary-color); font-size: 1.2rem; }
    </style>
@endsection

@section('content')
<div class="container my-5">
    <div class="d-flex align-items-center mb-5">
        <div class="bg-primary text-white rounded-4 p-3 me-3 shadow-sm">
            <i class="bi bi-cart3 fs-3"></i>
        </div>
        <div>
            <h2 class="fw-bold mb-0">Keranjang Belanja</h2>
            <p class="text-muted mb-0">Kelola pesanan minuman segar Anda</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(count($keranjang) > 0)
        <div class="row g-4">
            <!-- LIST ITEM -->
            <div class="col-lg-8">
                <div class="cart-card mb-4">
                    <div class="p-4 border-bottom border-light d-flex justify-content-between align-items-center">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="select-all" checked>
                            <label class="form-check-label fw-bold" for="select-all">Pilih Semua ({{ count($keranjang) }})</label>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-danger rounded-3 px-3" onclick="deleteSelectedItems()">
                            <i class="bi bi-trash3 me-1"></i> Hapus
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4"></th>
                                    <th>Produk</th>
                                    <th class="text-end">Harga</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-end pe-4">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($keranjang as $id => $item)
                                    <tr class="cart-item-row" data-id="{{ $id }}" data-price="{{ $item['harga'] }}" data-qty="{{ $item['jumlah'] }}">
                                        <td class="ps-4">
                                            <input class="form-check-input item-checkbox" type="checkbox" value="{{ $id }}" checked onchange="calculateSubtotal()">
                                        </td>
                                        <td>
                                            <span class="fw-bold text-dark d-block">{{ $item['nama_produk'] }}</span>
                                            <small class="text-muted">Item ID: #{{ $id }}</small>
                                        </td>
                                        <td class="text-end">Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-light text-dark border px-3 py-2 rounded-3">{{ $item['jumlah'] }}</span>
                                        </td>
                                        <td class="text-end pe-4 fw-bold text-primary">Rp {{ number_format($item['harga'] * $item['jumlah'], 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ALAMAT PENGIRIMAN -->
                <div class="cart-card p-4">
                    <h5 class="fw-bold mb-4 text-dark"><i class="bi bi-geo-alt-fill text-danger me-2"></i> Pilih Alamat Pengiriman</h5>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="w-100">
                                <input type="radio" name="address_mode" value="profile" class="btn-check mode-input" id="mode-profile" checked onchange="toggleAddressMode('profile')">
                                <div class="address-mode-card h-100">
                                    <h6 class="fw-bold"><i class="bi bi-person-badge me-2"></i>Alamat Profil</h6>
                                    <p class="small text-muted mb-0" id="profile-addr-text">{{ auth()->user()->alamat }}</p>
                                </div>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="w-100">
                                <input type="radio" name="address_mode" value="new" class="btn-check mode-input" id="mode-new" onchange="toggleAddressMode('new')">
                                <div class="address-mode-card h-100">
                                    <h6 class="fw-bold"><i class="bi bi-pin-map-fill me-2 text-danger"></i>Titik Lokasi Baru</h6>
                                    <p class="small text-muted mb-0">Tentukan lokasi berbeda menggunakan peta interaktif.</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div id="new-address-section" class="d-none">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div id="map" class="mb-2 shadow-sm"></div>
                                <small class="text-muted d-block" style="font-size: 0.7rem;">*Klik pada peta atau geser pin merah untuk menentukan lokasi pengantaran.</small>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Alamat Baru (Otomatis)</label>
                                    <textarea name="alamat_baru" id="alamat_pengiriman" form="checkout-form" class="form-control border-light bg-light" rows="5" placeholder="Pindahkan pin di peta untuk mengisi alamat ini..."></textarea>
                                    <div id="loading-address" class="text-primary mt-1 small d-none" style="font-size: 0.7rem;">
                                        <div class="spinner-border spinner-border-sm me-1" role="status"></div> Mencari alamat di peta...
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RINGKASAN -->
            <div class="col-lg-4">
                <div class="sticky-summary">
                    <!-- STATUS TOKO & MODE PENGANTARAN -->
                    <div class="cart-card p-4 mb-4">
                        <h6 class="fw-bold mb-3"><i class="bi bi-clock-history me-2 text-primary"></i> Waktu Pengantaran</h6>
                        
                        @if(!$pengaturan->isOpen())
                        <div class="alert alert-danger rounded-3 border-0 small mb-3">
                            <i class="bi bi-door-closed-fill me-2"></i> Toko sedang tutup. <br>
                            <small>Silakan gunakan fitur <b>Reservasi</b>.</small>
                        </div>
                        @endif

                        <div class="d-grid gap-2">
                            <input type="radio" class="btn-check" name="delivery_mode" id="delivery-now" onchange="toggleReservationMode(false)" {{ $pengaturan->isOpen() ? 'checked' : 'disabled' }}>
                            <label class="btn btn-outline-primary text-start p-3 rounded-3 small fw-bold" for="delivery-now">
                                <i class="bi bi-lightning-charge-fill me-2"></i> Langsung Antar (Sekarang)
                            </label>

                            <input type="radio" class="btn-check" name="delivery_mode" id="delivery-later" onchange="toggleReservationMode(true)" {{ !$pengaturan->isOpen() ? 'checked' : '' }}>
                            <label class="btn btn-outline-warning text-start p-3 rounded-3 small fw-bold text-dark" for="delivery-later">
                                <i class="bi bi-calendar-event-fill me-2"></i> Reservasi (Jadwalkan)
                            </label>
                        </div>
                    </div>

                    <!-- RINCIAN HARGA -->
                    <div class="cart-card p-4 mb-4">
                        <h6 class="fw-bold mb-4">Rincian Pembayaran</h6>
                        <div class="summary-item">
                            <span class="text-muted">Total Harga (<span id="count-selected">0</span> item)</span>
                            <span id="display-total-produk">Rp 0</span>
                        </div>
                        <div class="summary-item">
                            <span class="text-muted">Ongkos Kirim (<span id="display-jarak">0.00 KM</span>)</span>
                            <span id="display-ongkir">Rp 0</span>
                        </div>
                        @if(session()->has('voucher'))
                            <div class="summary-item text-success">
                                <span>Diskon Voucher</span>
                                <span>- Rp {{ number_format(session('voucher')['potongan'], 0, ',', '.') }}</span>
                            </div>
                        @endif
                        <hr class="my-4 opacity-5">
                        <div class="summary-item align-items-end">
                            <span class="fw-bold text-dark">Total Pembayaran</span>
                            <span class="total-price" id="display-grand-total">Rp 0</span>
                        </div>
                    </div>

                    <!-- FORM FINAL -->
                    <form action="{{ route('transaksi.store') }}" method="POST" id="checkout-form">
                        @csrf
                        <input type="hidden" name="selected_items" id="selected-items-input">
                        <input type="hidden" name="latitude" id="customer_lat">
                        <input type="hidden" name="longitude" id="customer_lng">
                        <input type="hidden" name="alamat_pengiriman" id="final_alamat">
                        <input type="hidden" name="is_reservasi" id="is_reservasi_input" value="0">
                        
                        <div id="reservation-date-section" class="cart-card p-4 mb-4 d-none border-start border-warning border-4">
                            <label class="form-label small fw-bold mb-2">Jadwal Pengantaran</label>
                            <input type="datetime-local" name="jadwal_pengambilan" id="jadwal_input" class="form-control" min="{{ date('Y-m-d\TH:i') }}">
                            <small class="text-muted extra-small mt-2 d-block">*Hanya dilayani pada jam operasional toko.</small>
                        </div>

                        <div class="cart-card p-4 mb-4">
                            <label class="form-label small fw-bold mb-3">Metode Pembayaran</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="metode_pembayaran" id="pay-cod" value="COD" checked>
                                    <label class="btn btn-outline-light text-dark border-light w-100 py-3 rounded-4 small fw-bold shadow-sm" for="pay-cod">
                                        <i class="bi bi-cash d-block mb-1 fs-4 text-success"></i> COD
                                    </label>
                                </div>
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="metode_pembayaran" id="pay-wallet" value="E-Wallet">
                                    <label class="btn btn-outline-light text-dark border-light w-100 py-3 rounded-4 small fw-bold shadow-sm" for="pay-wallet">
                                        <i class="bi bi-wallet2 d-block mb-1 fs-4 text-primary"></i> E-Wallet
                                    </label>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-checkout-custom w-100 shadow" id="btn-checkout">
                            PROSES PESANAN <i class="bi bi-arrow-right ms-2"></i>
                        </button>
                    </form>
                    
                    <div class="text-center mt-3">
                        <a href="{{ route('produk.index') }}" class="text-muted small text-decoration-none">
                            <i class="bi bi-chevron-left"></i> Lanjut Belanja
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="cart-card p-5 text-center my-5">
            <div class="mb-4">
                <i class="bi bi-cart-x text-muted opacity-25" style="font-size: 8rem;"></i>
            </div>
            <h4 class="fw-bold text-dark">Wah, keranjang Anda masih kosong!</h4>
            <p class="text-muted mb-4">Minuman segar kami menunggumu di menu utama.</p>
            <a href="{{ route('produk.index') }}" class="btn btn-primary px-5 py-3 rounded-pill fw-bold shadow">MULAI BELANJA</a>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    // --- DATA DASAR ---
    const storeLat = parseFloat("{{ $pengaturan->toko_latitude ?? -9.6591 }}");
    const storeLng = parseFloat("{{ $pengaturan->toko_longitude ?? 120.2633 }}");
    const costPerKm = parseInt("{{ $pengaturan->harga_per_km ?? 2000 }}");
    const freeKm = parseInt("{{ $pengaturan->gratis_ongkir_km ?? 1 }}");
    const maxKm = parseInt("{{ $pengaturan->max_jarak_km ?? 10 }}");
    const voucherPotongan = parseInt("{{ session()->get('voucher')['potongan'] ?? 0 }}");

    const profileLat = parseFloat("{{ auth()->user()->latitude ?? $pengaturan->toko_latitude }}");
    const profileLng = parseFloat("{{ auth()->user()->longitude ?? $pengaturan->toko_longitude }}");
    const profileAddr = "{{ auth()->user()->alamat }}";

    // --- STATE ---
    let currentOngkir = 0;
    let currentTotalProduk = 0;
    let map, marker;
    let activeMode = 'profile';

    // --- DOM HELPER ---
    const get = (id) => document.getElementById(id);

    // --- LOGIKA ALAMAT ---
    function toggleAddressMode(mode) {
        activeMode = mode;
        const mapSection = get('new-address-section');
        const finalLat = get('customer_lat');
        const finalLng = get('customer_lng');
        const finalAddr = get('final_alamat');

        if (mode === 'profile') {
            mapSection.classList.add('d-none');
            finalLat.value = profileLat;
            finalLng.value = profileLng;
            finalAddr.value = profileAddr;
            updateOngkir(profileLat, profileLng);
        } else {
            mapSection.classList.remove('d-none');
            // Inisialisasi peta jika belum ada
            if (!map) initMap();
            // Trigger update dari marker saat ini
            const pos = marker.getLatLng();
            updateOngkir(pos.lat, pos.lng);
            syncNewAddress();
        }
    }

    function initMap() {
        map = L.map('map').setView([profileLat, profileLng], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OSM' }).addTo(map);

        const storeIcon = L.icon({ iconUrl: 'https://cdn-icons-png.flaticon.com/512/619/619153.png', iconSize: [30, 30] });
        L.marker([storeLat, storeLng], {icon: storeIcon}).addTo(map).bindPopup("SIMINES Waingapu");

        marker = L.marker([profileLat, profileLng], { draggable: true }).addTo(map);

        marker.on('dragend', function() {
            const pos = marker.getLatLng();
            updateOngkir(pos.lat, pos.lng);
            reverseGeocode(pos.lat, pos.lng);
        });

        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            updateOngkir(e.latlng.lat, e.latlng.lng);
            reverseGeocode(e.latlng.lat, e.latlng.lng);
        });
    }

    function reverseGeocode(lat, lng) {
        const loading = get('loading-address');
        const inputVisual = get('alamat_pengiriman');
        const inputFinal = get('final_alamat');
        
        if (loading) loading.classList.remove('d-none');

        fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`)
            .then(res => res.json())
            .then(data => {
                if (data && data.display_name) {
                    const fullAddress = data.display_name;
                    if (inputVisual) inputVisual.value = fullAddress;
                    if (activeMode === 'new' && inputFinal) inputFinal.value = fullAddress;
                }
                if (loading) loading.classList.add('d-none');
            })
            .catch(() => { if (loading) loading.classList.add('d-none'); });
    }

    function syncNewAddress() {
        if (activeMode === 'new') {
            const pos = marker.getLatLng();
            if (get('customer_lat')) get('customer_lat').value = pos.lat;
            if (get('customer_lng')) get('customer_lng').value = pos.lng;
            if (get('final_alamat')) get('final_alamat').value = get('alamat_pengiriman').value;
        }
    }

    // Pantau manual typing di alamat baru
    get('alamat_pengiriman').addEventListener('input', syncNewAddress);

    // --- KALKULASI ---
    function calculateDistance(lat1, lon1, lat2, lng2) {
        const R = 6371;
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lng2 - lon1) * Math.PI / 180;
        const a = Math.sin(dLat/2) * Math.sin(dLat/2) + Math.cos(lat1*Math.PI/180) * Math.cos(lat2*Math.PI/180) * Math.sin(dLon/2) * Math.sin(dLon/2);
        return R * (2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a)));
    }

    function updateOngkir(lat, lng) {
        const distance = calculateDistance(storeLat, storeLng, lat, lng);
        const jarakText = get('display-jarak');
        const ongkirText = get('display-ongkir');
        const btnCheckout = get('btn-checkout');

        if (jarakText) {
            jarakText.innerText = distance.toFixed(2) + " KM";
            jarakText.className = (distance > maxKm) ? "fw-bold text-danger" : "fw-bold text-dark";
        }
        
        if (distance > maxKm) {
            currentOngkir = 0;
            if (ongkirText) {
                ongkirText.innerText = "DILUAR RADIUS";
                ongkirText.style.color = "#dc3545";
            }
            if (btnCheckout) {
                btnCheckout.disabled = true;
                btnCheckout.innerText = "JARAK TERLALU JAUH";
            }
        } else {
            currentOngkir = (distance > freeKm) ? Math.ceil(distance) * costPerKm : 0;
            if (ongkirText) {
                ongkirText.innerText = "Rp " + new Intl.NumberFormat('id-ID').format(currentOngkir);
                ongkirText.style.color = currentOngkir > 0 ? '#3a7bd5' : '#28a745';
            }
            if (btnCheckout) {
                const isReservasi = get('is_reservasi_input')?.value === "1";
                if (isStoreOpen || isReservasi) {
                    btnCheckout.disabled = false;
                    btnCheckout.innerText = isReservasi ? "PESAN RESERVASI" : "PROSES PESANAN";
                }
            }
        }
        updateGrandTotal();
    }

    function calculateSubtotal() {
        let total = 0;
        let selectedIds = [];
        document.querySelectorAll('.item-checkbox:checked').forEach(cb => {
            const row = cb.closest('.cart-item-row');
            total += parseFloat(row.dataset.price) * parseInt(row.dataset.qty);
            selectedIds.push(cb.value);
        });

        currentTotalProduk = total;
        get('display-total-produk').innerText = "Rp " + new Intl.NumberFormat('id-ID').format(total);
        get('selected-items-input').value = JSON.stringify(selectedIds);
        get('count-selected').innerText = selectedIds.length;
        get('btn-checkout').disabled = selectedIds.length === 0;
        updateGrandTotal();
    }

    function updateGrandTotal() {
        const grand = Math.max(0, (currentTotalProduk + currentOngkir) - voucherPotongan);
        get('display-grand-total').innerText = "Rp " + new Intl.NumberFormat('id-ID').format(grand);
    }

    // --- EVENT HANDLERS ---
    get('select-all').addEventListener('change', function() {
        document.querySelectorAll('.item-checkbox').forEach(cb => cb.checked = this.checked);
        calculateSubtotal();
    });

    function deleteSelectedItems() {
        let ids = [];
        document.querySelectorAll('.item-checkbox:checked').forEach(cb => ids.push(cb.value));
        if (ids.length === 0) return alert("Pilih produk dulu!");
        if (confirm("Hapus terpilih?")) {
            const form = document.createElement('form');
            form.method = 'POST'; form.action = "{{ route('keranjang.hapus.massal') }}";
            const csrf = document.createElement('input'); csrf.type = 'hidden'; csrf.name = '_token'; csrf.value = "{{ csrf_token() }}"; form.appendChild(csrf);
            const idsInp = document.createElement('input'); idsInp.type = 'hidden'; idsInp.name = 'ids'; idsInp.value = JSON.stringify(ids); form.appendChild(idsInp);
            document.body.appendChild(form); form.submit();
        }
    }

    // --- RESERVATION LOGIC ---
    const isStoreOpen = {{ $pengaturan->isOpen() ? 'true' : 'false' }};
    
    function toggleReservationMode(enabled) {
        const dateSection = get('reservation-date-section');
        const isReservasiInp = get('is_reservasi_input');
        const jadwalInp = get('jadwal_input');

        if (enabled) {
            dateSection.classList.remove('d-none');
            isReservasiInp.value = "1";
            jadwalInp.required = true;
            btnCheckout.disabled = false;
        } else {
            dateSection.classList.add('d-none');
            isReservasiInp.value = "0";
            jadwalInp.required = false;
            // Jika toko tutup dan reservasi tidak aktif, jangan bisa checkout
            if (!isStoreOpen) btnCheckout.disabled = true;
        }
    }

    // --- START ---
    document.addEventListener('DOMContentLoaded', function() {
        calculateSubtotal();
        toggleAddressMode('profile'); // Default awal

        // Inisialisasi Mode Pengantaran
        if (!isStoreOpen) {
            toggleReservationMode(true);
        } else {
            toggleReservationMode(false);
        }
    });
</script>
@endsection
