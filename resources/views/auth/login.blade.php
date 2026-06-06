@extends('layouts.app')

@section('title', 'Login - SIMINES')

@section('content')
<div class="container my-5 pt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-lg" style="border-radius: 30px; overflow: hidden;">
                <div class="card-body p-5">
                    <div class="text-center mb-5">
                        <div class="bg-light text-primary d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 70px; height: 70px;">
                            <i class="bi bi-water fs-1"></i>
                        </div>
                        <h3 class="fw-bold mb-0">Selamat Datang Kembali!</h3>
                        <p class="text-muted">Masuk untuk menikmati kesegaran minuman es favoritmu.</p>
                    </div>

                    @if(session('success')) <div class="alert alert-success rounded-4 mb-4 small">{{ session('success') }}</div> @endif
                    @if(session('error')) <div class="alert alert-danger rounded-4 mb-4 small">{{ session('error') }}</div> @endif
                    @if ($errors->any())
                        <div class="alert alert-danger rounded-4 mb-4 small">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label small fw-bold">Email atau Nomor HP</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0" style="border-radius: 12px 0 0 12px;"><i class="bi bi-person"></i></span>
                                <input type="text" name="login" class="form-control border-start-0" placeholder="nama@email.com atau 08..." required style="border-radius: 0 12px 12px 0; padding: 12px;">
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label small fw-bold mb-0">Password</label>
                                <a href="{{ route('password.request') }}" class="text-primary text-decoration-none extra-small fw-bold" style="font-size: 0.7rem;">Lupa Password?</a>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0" style="border-radius: 12px 0 0 12px;"><i class="bi bi-lock"></i></span>
                                <input type="password" name="password" class="form-control border-start-0" placeholder="••••••••" required style="border-radius: 0 12px 12px 0; padding: 12px;">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow mt-2">MASUK KE AKUN</button>
                    </form>
                    
                    <p class="mt-4 text-center text-muted small">
                        Belum punya akun? <a href="{{ route('register') }}" class="fw-bold text-decoration-none text-primary">Daftar Sekarang</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
