@extends('layouts.app')

@section('title', 'Verifikasi OTP - SIMINES')

@section('content')
<div class="container mt-5 pt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card border-0 shadow-lg p-4" style="border-radius: 30px;">
                <div class="text-center mb-4">
                    <div class="bg-light text-primary d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-shield-lock fs-2"></i>
                    </div>
                    <h4 class="fw-bold">Verifikasi OTP</h4>
                    <p class="text-muted small">Masukkan kode keamanan yang baru saja dikirim</p>
                </div>
                
                @if(session('info'))
                    <div class="alert alert-info rounded-4 mb-4 small border-0 shadow-sm" style="background-color: #e3f2fd; color: #0d47a1;">
                        <i class="bi bi-chat-dots me-2"></i> <strong>Pesan Masuk:</strong><br>
                        {{ session('info') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger rounded-4 mb-3 small">{{ session('error') }}</div>
                @endif

                <form action="{{ route('otp.verify') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <input type="text" name="otp" class="form-control text-center fs-2 fw-bold text-primary" placeholder="0000" style="border-radius: 20px; height: 80px; border: 2px solid #eef9ff; background-color: #f8fbff;" required autofocus>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow">VERIFIKASI SEKARANG</button>
                </form>
                
                <div class="text-center mt-4">
                    <button class="btn btn-link text-muted text-decoration-none small">Kirim Ulang Kode</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
