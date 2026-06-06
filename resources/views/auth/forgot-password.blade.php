@extends('layouts.app')

@section('title', 'Lupa Password - SIMINES')

@section('content')
<div class="container my-5 pt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-lg rounded-4 p-5">
                <div class="text-center mb-5">
                    <h3 class="fw-bold">Lupa Kata Sandi?</h3>
                    <p class="text-muted">Jangan khawatir! Masukkan email Anda dan kami akan mengirimkan instruksi pemulihan.</p>
                </div>

                @if(session('success'))
                    <div class="alert alert-success rounded-4 mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('password.email') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label small fw-bold">Alamat Email</label>
                        <input type="email" name="email" class="form-control rounded-pill px-4 py-3" placeholder="nama@email.com" required autofocus>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary py-3 rounded-pill fw-bold shadow">KIRIM LINK PEMULIHAN</button>
                        <a href="{{ route('login') }}" class="btn btn-link text-muted text-decoration-none small mt-2">Kembali ke Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
