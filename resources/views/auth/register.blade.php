@extends('layouts.app')

@section('title', 'Daftar Akun - SIMINES')

@section('content')
<div class="container my-5 pt-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card border-0 shadow-lg" style="border-radius: 30px; overflow: hidden;">
                <div class="row g-0">
                    <div class="col-md-5 bg-primary d-none d-md-flex align-items-center justify-content-center text-center p-5 text-white">
                        <div>
                            <i class="bi bi-person-plus display-1 mb-4"></i>
                            <h4 class="fw-bold">Ayo Bergabung!</h4>
                            <p class="small opacity-75">Dapatkan kesegaran minuman es terbaik dengan harga terjangkau hanya di SIMINES.</p>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="card-body p-5">
                            <h3 class="fw-bold mb-4">Daftar Akun Baru</h3>

                            @if ($errors->any())
                                <div class="alert alert-danger rounded-4 mb-4 small">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('register') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label small fw-bold">Nama Lengkap</label>
                                        <input type="text" name="nama" class="form-control" placeholder="Nama Anda" required style="border-radius: 12px;">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label small fw-bold">Nomor HP</label>
                                        <input type="text" name="no_hp" class="form-control" placeholder="08..." required style="border-radius: 12px;">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Email</label>
                                    <input type="email" name="email" class="form-control" placeholder="nama@email.com" required style="border-radius: 12px;">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Alamat Pengiriman</label>
                                    <textarea name="alamat" class="form-control" rows="2" placeholder="Alamat lengkap..." required style="border-radius: 12px;"></textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label small fw-bold">Password</label>
                                        <input type="password" name="password" class="form-control" placeholder="••••••••" required style="border-radius: 12px;">
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label small fw-bold">Konfirmasi</label>
                                        <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••" required style="border-radius: 12px;">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-sm">DAFTAR SEKARANG</button>
                            </form>
                            
                            <p class="mt-4 text-center text-muted small">
                                Sudah punya akun? <a href="{{ route('login') }}" class="fw-bold text-decoration-none text-primary">Masuk di sini</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
