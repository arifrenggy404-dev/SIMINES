@extends('layouts.app')

@section('title', 'SIMINES - Kesegaran Digital UMKM')

@section('styles')
    <style>
        .hero-section {
            background: linear-gradient(135deg, #00d2ff 0%, #3a7bd5 100%), url('https://www.transparenttextures.com/patterns/cubes.png');
            padding: 140px 0 100px;
            color: white;
            border-radius: 0 0 60% 50% / 20px 20px 40px 40px;
            margin-bottom: -40px;
            position: relative;
        }

        .hero-section::after {
            content: '❄️';
            position: absolute;
            font-size: 5rem;
            bottom: 20px;
            right: 10%;
            opacity: 0.2;
        }

        .hero-title {
            font-weight: 900;
            font-size: 4rem;
            text-shadow: 2px 4px 10px rgba(0,0,0,0.1);
            line-height: 1.1;
            margin-bottom: 1.5rem;
        }

        .card-produk {
            border: 2px solid white;
            border-radius: 25px;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(5px);
            transition: all 0.4s ease;
            box-shadow: var(--card-shadow);
        }

        .card-produk:hover {
            transform: scale(1.05) rotate(1deg);
            border-color: var(--primary-color);
            box-shadow: 0 20px 40px rgba(0, 210, 255, 0.2);
        }

        .img-container {
            position: relative;
            height: 240px;
            overflow: hidden;
        }

        .img-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }

        .badge-stok {
            position: absolute;
            top: 15px;
            right: 15px;
            background: var(--primary-color);
            color: white;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 5px 12px;
            border-radius: 30px;
            box-shadow: 0 4px 10px rgba(0, 210, 255, 0.3);
        }

        .price-tag {
            background: linear-gradient(to right, #00d2ff, #3a7bd5);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-size: 1.4rem;
            font-weight: 800;
        }

        .btn-cart {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            background: linear-gradient(45deg, var(--primary-color), var(--accent-color));
            border: none;
            color: white;
            box-shadow: 0 5px 15px rgba(0, 210, 255, 0.4);
            transition: 0.3s;
        }

        .btn-cart:hover {
            background: linear-gradient(45deg, var(--accent-color), var(--primary-color));
            color: white;
            transform: scale(1.1);
        }

        .section-title {
            font-weight: 800;
            color: var(--accent-color);
            position: relative;
            display: inline-block;
            margin-bottom: 3rem;
            padding-bottom: 15px;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 5px;
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            border-radius: 10px;
        }

        .feature-box {
            padding: 30px;
            background: linear-gradient(180deg, #ffffff 0%, #f0faff 100%);
            border-radius: 20px;
            text-align: center;
            height: 100%;
            border: 1px solid rgba(0, 210, 255, 0.1);
            box-shadow: 0 5px 20px rgba(0,0,0,0.02);
        }

        .feature-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 20px;
            filter: drop-shadow(0 4px 6px rgba(0, 210, 255, 0.2));
        }

        .banner-section {
            padding-top: 20px;
        }
    </style>
@endsection

@section('content')
<div class="container-fluid px-0 banner-section">
    @if($banners->count() > 0)
    <section class="mb-5 px-md-4">
        <div id="bannerCarousel" class="carousel slide carousel-fade shadow-sm rounded-4 overflow-hidden" data-bs-ride="carousel" style="background-color: #f1f5f9;">
            <div class="carousel-indicators">
                @foreach($banners as $index => $b)
                    <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}"></button>
                @endforeach
            </div>
            <div class="carousel-inner">
                @foreach($banners as $index => $b)
                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}" data-bs-interval="5000">
                    <div class="bg-white">
                        <div class="d-flex align-items-center justify-content-center" style="height: 350px; overflow: hidden; background-color: #f8fafc;">
                            <img src="{{ asset($b->gambar) }}" class="d-block" style="max-height: 100%; max-width: 100%; width: auto; object-fit: contain;" alt="{{ $b->judul }}">
                        </div>
                        @if($b->judul || $b->subjudul)
                        <div class="p-3 text-center border-top">
                            <h5 class="fw-bold mb-1 text-dark">{{ $b->judul }}</h5>
                            <p class="text-muted small mb-0">{{ $b->subjudul }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon bg-dark rounded-circle" style="width: 30px; height: 30px;"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon bg-dark rounded-circle" style="width: 30px; height: 30px;"></span>
            </button>
        </div>
    </section>
    @else
    <header class="hero-section">
        <div class="container text-center">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2 mb-3 fw-bold shadow-sm">#1 Pelepas Dahaga di Waingapu</span>
                    <h1 class="hero-title">Haus Melanda? <br> Segarkan dengan SIMINES!</h1>
                    <p class="lead mb-5 opacity-75 fw-500">Nikmati sensasi dingin es kekinian dengan bahan premium dan harga merakyat. Siap usir panas matahari Sumba dalam sekejap!</p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="#menu" class="btn btn-warning btn-lg rounded-pill px-5 py-3 fw-bold shadow">PESAN SEKARANG</a>
                        <a href="#tentang" class="btn btn-outline-light btn-lg rounded-pill px-5 py-3 fw-bold">KENAPA KAMI?</a>
                    </div>
                </div>
            </div>
        </div>
    </header>
    @endif
</div>

<div class="container">
    <section class="mb-5 pb-5" id="tentang">
    <div class="row g-4">
        <div class="col-md-4">
            <div class="feature-box border-bottom border-primary border-4">
                <i class="bi bi-snow2 feature-icon text-info"></i>
                <h5 class="fw-bold">Es Kristal Higienis</h5>
                <p class="text-muted small">Kami hanya menggunakan es kristal dari air mineral murni, menjamin kesegaran yang sehat bagi Anda.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="feature-box border-bottom border-warning border-4">
                <i class="bi bi-flower1 feature-icon text-warning"></i>
                <h5 class="fw-bold">Bahan Alami & Segar</h5>
                <p class="text-muted small">Buah-buahan pilihan dan gula asli tanpa pemanis buatan. Rasa otentik yang tak terlupakan.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="feature-box border-bottom border-success border-4">
                <i class="bi bi-bicycle feature-icon text-success"></i>
                <h5 class="fw-bold">Antar Sampai Pintu</h5>
                <p class="text-muted small">Tak perlu keluar rumah! Driver kami siap mengantar pesanan Anda selagi masih dalam kondisi dingin maksimal.</p>
            </div>
        </div>
    </div>
</section>

@if($vouchers->count() > 0)
<section class="container mb-5">
    <div class="bg-primary bg-gradient rounded-4 p-4 p-md-5 text-white shadow-lg position-relative overflow-hidden">
        <div class="row align-items-center position-relative" style="z-index: 2;">
            <div class="col-md-7 mb-4 mb-md-0">
                <h2 class="fw-bold mb-2">Hemat Lebih Banyak! 💸</h2>
                <p class="lead mb-0 opacity-75">Gunakan kode voucher di bawah ini saat checkout untuk mendapatkan potongan harga spesial.</p>
            </div>
            <div class="col-md-5">
                <div class="row g-2">
                    @foreach($vouchers->take(2) as $v)
                    <div class="col-6">
                        <div class="bg-white rounded-3 p-3 text-center shadow-sm">
                            <small class="text-muted d-block mb-1" style="font-size: 0.6rem;">KODE: <strong>{{ $v->kode }}</strong></small>
                            <h5 class="text-primary fw-bold mb-0">Rp {{ number_format($v->potongan_harga / 1000, 0) }}rb</h5>
                            <small class="text-dark extra-small" style="font-size: 0.5rem;">Min. Blj: {{ number_format($v->minimal_belanja / 1000, 0) }}rb</small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <!-- Decorative Circle -->
        <div class="position-absolute top-0 end-0 translate-middle bg-white opacity-10 rounded-circle" style="width: 300px; height: 300px;"></div>
    </div>
</section>
@endif

<div class="container my-5 pt-5" id="menu">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4" role="alert">
            <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="text-center">
        <h2 class="section-title">Menu Minuman Terlaris</h2>
    </div>

    <!-- Fitur: Pencarian & Filter Lanjutan -->
    <div class="card border-0 shadow-sm rounded-4 mb-5" style="background: rgba(255,255,255,0.7); backdrop-filter: blur(10px);">
        <div class="card-body p-4">
            <form action="{{ route('produk.index') }}" method="GET" class="row g-3">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-0 text-muted ps-3"><i class="bi bi-search"></i></span>
                        <input type="text" name="cari" class="form-control border-0 bg-white" placeholder="Cari es favoritmu..." value="{{ request('cari') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="kategori" class="form-select border-0 shadow-none bg-white">
                        <option value="">Semua Kategori</option>
                        @foreach($kategori as $k)
                            <option value="{{ $k->id_kategori }}" {{ request('kategori') == $k->id_kategori ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="urutkan" class="form-select border-0 shadow-none bg-white">
                        <option value="">Urutkan</option>
                        <option value="termurah" {{ request('urutkan') == 'termurah' ? 'selected' : '' }}>Harga Termurah</option>
                        <option value="termahal" {{ request('urutkan') == 'termahal' ? 'selected' : '' }}>Harga Termahal</option>
                        <option value="terbaru" {{ request('urutkan') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100 rounded-3 fw-bold">FILTER</button>
                </div>
            </form>
        </div>
    </div>
    
    <div class="row g-4 justify-content-center">
        @foreach($produk as $item)
            <div class="col-sm-6 col-lg-3">
                <div class="card card-produk h-100 position-relative border-0 shadow-sm">
                    <a href="{{ route('produk.show', $item->id_produk) }}" class="stretched-link"></a>
                    <div class="img-container">
                        <span class="badge-stok"><i class="bi bi-box-seam me-1"></i> {{ $item->stok }}</span>
                        
                        @auth
                            @if(auth()->user()->peran !== 'admin')
                                <button class="btn btn-sm btn-light rounded-circle shadow-sm position-absolute top-0 end-0 m-2 border-0" 
                                        style="z-index: 5; width: 32px; height: 32px; padding: 0;"
                                        onclick="event.preventDefault(); toggleWish(this, {{ $item->id_produk }})">
                                    <i class="bi bi-heart{{ auth()->user()->wishlist->contains('id_produk', $item->id_produk) ? '-fill text-danger' : '' }}"></i>
                                </button>
                            @endif
                        @endauth

                        @if($item->id_kategori)
                            <span class="position-absolute top-0 start-0 m-2 badge bg-primary small" style="z-index: 2;">{{ $item->kategori->nama_kategori }}</span>
                        @endif
                        @if($item->foto)
                            <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama_produk }}">
                        @else
                            <img src="https://images.unsplash.com/photo-1547595628-c61a29f496f0?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Placeholder">
                        @endif
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <h6 class="fw-bold mb-1">{{ $item->nama_produk }}</h6>
                            <div class="text-warning small" style="position: relative; z-index: 2;">
                                <i class="bi bi-star-fill"></i>
                                <span class="text-muted fw-bold ms-1">{{ number_format($item->averageRating(), 1) }}</span>
                            </div>
                        </div>
                        <p class="text-muted small mb-3 lh-sm" style="height: 40px; overflow: hidden;">{{ Str::limit($item->deskripsi, 50) }}</p>
                        
                        <div class="d-flex justify-content-between align-items-center mt-auto" style="position: relative; z-index: 2;">
                            <div>
                                <small class="text-muted d-block" style="font-size: 0.7rem;">Harga</small>
                                <span class="price-tag">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
                            </div>
                            
                            @guest
                                <button type="button" class="btn btn-primary btn-cart shadow-sm" data-bs-toggle="modal" data-bs-target="#modalAuthPrompt">
                                    <i class="bi bi-plus-lg"></i>
                                </button>
                            @else
                                @if(auth()->user()->peran !== 'admin')
                                    <form action="{{ route('keranjang.tambah') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id_produk" value="{{ $item->id_produk }}">
                                        <input type="hidden" name="jumlah" value="1">
                                        <button type="submit" class="btn btn-primary btn-cart shadow-sm">
                                            <i class="bi bi-plus-lg"></i>
                                        </button>
                                    </form>
                                @else
                                    <span class="badge bg-light text-muted rounded-pill">Admin</span>
                                @endif
                            @endguest
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Modal Login Prompt -->
    <div class="modal fade" id="modalAuthPrompt" tabindex="-1" aria-hidden="true" style="z-index: 9999;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg text-dark" style="border-radius: 25px;">
                <div class="modal-body p-5 text-center">
                    <div class="mb-4">
                        <i class="bi bi-person-lock text-primary display-1"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Wah, Segarnya Sebentar Lagi!</h4>
                    <p class="text-muted mb-4">Silakan masuk ke akun Anda terlebih dahulu untuk bisa menambahkan minuman favorit ke keranjang atau melakukan pemesanan.</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('login') }}" class="btn btn-primary py-3 rounded-pill fw-bold shadow">MASUK SEKARANG</a>
                        <a href="{{ route('register') }}" class="btn btn-outline-primary py-3 rounded-pill fw-bold">DAFTAR AKUN BARU</a>
                        <button type="button" class="btn btn-link text-muted text-decoration-none mt-2" data-bs-dismiss="modal">Nanti Saja</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function toggleWish(btn, id) {
        const icon = btn.querySelector('i');
        fetch("{{ route('wishlist.toggle') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ id_produk: id })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'added') {
                icon.classList.remove('bi-heart');
                icon.classList.add('bi-heart-fill', 'text-danger');
            } else {
                icon.classList.remove('bi-heart-fill', 'text-danger');
                icon.classList.add('bi-heart');
            }
        });
    }
</script>
@endsection
