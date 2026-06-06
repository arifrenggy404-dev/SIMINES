@extends('layouts.app')

@section('title', 'Pembayaran E-Wallet - SIMINES')

@section('styles')
    <style>
        .payment-card { border: none; border-radius: 30px; box-shadow: 0 20px 50px rgba(0,0,0,0.1); overflow: hidden; }
        .ewallet-option { border: 2px solid #f8f9fa; border-radius: 20px; padding: 20px; transition: 0.3s; cursor: pointer; position: relative; }
        .ewallet-option:hover { border-color: var(--primary-color); background: #f0faff; }
        .form-check-input:checked + .ewallet-option { border-color: var(--primary-color); background: #f0faff; }
        .ewallet-logo { height: 35px; object-fit: contain; }
        .qr-mockup { width: 180px; height: 180px; background: #eee; margin: auto; border-radius: 20px; display: flex; align-items: center; justify-content: center; border: 2px dashed #ccc; }
    </style>
@endsection

@section('content')
<div class="container my-5 pt-4">
    <div class="row justify-content-center">
        <div class="col-lg-5">
            <div class="payment-card bg-white">
                <div class="bg-primary p-4 text-center text-white">
                    <h5 class="fw-bold mb-0">Selesaikan Pembayaran</h5>
                    <small class="opacity-75">ID Pesanan: #{{ $transaksi->id_transaksi }}</small>
                </div>
                <div class="p-5">
                    <div class="text-center mb-5">
                        <p class="text-muted mb-1">Total Bayar</p>
                        <h2 class="fw-bold text-dark">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</h2>
                    </div>

                    <div class="mb-5 text-center">
                        <h6 class="fw-bold mb-4 text-start"><i class="bi bi-qr-code-scan me-2 text-primary"></i> Pilih Metode E-Wallet:</h6>
                        
                        <div class="row g-3 mb-4">
                            <div class="col-6">
                                <input type="radio" class="btn-check" name="payment_method" id="dana" autocomplete="off" onchange="showQR('DANA')">
                                <label class="ewallet-option d-block shadow-sm h-100 p-3" for="dana">
                                    <div class="mb-2 d-flex align-items-center justify-content-center" style="height: 40px;">
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/7/72/Logo_danain.png/240px-Logo_danain.png" alt="DANA" class="ewallet-logo" style="max-width: 100%; max-height: 100%;" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=DANA&background=0088ff&color=fff&bold=true';">
                                    </div>
                                    <span class="d-block small fw-bold">DANA</span>
                                </label>
                            </div>
                            <div class="col-6">
                                <input type="radio" class="btn-check" name="payment_method" id="gopay" autocomplete="off" onchange="showQR('GoPay')">
                                <label class="ewallet-option d-block shadow-sm h-100 p-3" for="gopay">
                                    <div class="mb-2 d-flex align-items-center justify-content-center" style="height: 40px;">
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/86/Gopay_logo.svg/240px-Gopay_logo.svg.png" alt="GoPay" class="ewallet-logo" style="max-width: 100%; max-height: 100%;" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=GoPay&background=00aade&color=fff&bold=true';">
                                    </div>
                                    <span class="d-block small fw-bold">GoPay</span>
                                </label>
                            </div>
                        </div>

                        <div id="qr-section" class="d-none animate__animated animate__fadeIn">
                            <div class="qr-mockup mb-3">
                                <i class="bi bi-qr-code text-dark" style="font-size: 6rem;"></i>
                            </div>
                            <p class="mb-0 fw-bold text-primary">QRIS <span id="selected-ewallet"></span></p>
                            <small class="text-muted d-block mb-4">Silakan scan kode QR di atas menggunakan aplikasi <strong id="selected-ewallet-name"></strong> Anda.</small>
                        </div>
                    </div>

                    <form action="{{ route('bayar', $transaksi->id_transaksi) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-lg" id="btn-bayar" disabled>SAYA SUDAH BAYAR <i class="bi bi-check-circle ms-2"></i></button>
                    </form>

                    <div class="text-center mt-4">
                        <a href="{{ route('transaksi.riwayat') }}" class="text-muted text-decoration-none small">Bayar Nanti (Cek Riwayat)</a>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <p class="text-muted small"><i class="bi bi-shield-fill-check text-success me-1"></i> Pembayaran Aman & Terverifikasi Otomatis</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function showQR(name) {
        // Tampilkan Section QR
        const qrSection = document.getElementById('qr-section');
        qrSection.classList.remove('d-none');
        
        // Update Label Nama E-Wallet
        document.getElementById('selected-ewallet').innerText = name;
        document.getElementById('selected-ewallet-name').innerText = name;
        
        // Aktifkan Tombol Bayar
        document.getElementById('btn-bayar').disabled = false;
    }
</script>
@endsection
