@extends('layouts.app')

@section('title', $produk->nama_produk . ' - SIMINES')

@section('styles')
    <style>
        .product-container { margin-bottom: 60px; }
        .product-image-card { border: none; border-radius: 30px; overflow: hidden; box-shadow: 0 15px 35px rgba(0,0,0,0.05); background: white; }
        .product-image-card img { width: 100%; height: 500px; object-fit: cover; }
        .product-info-card { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); border: 2px solid white; border-radius: 30px; padding: 40px; height: 100%; }
        .badge-stok { background: var(--primary-color); color: white; padding: 8px 20px; border-radius: 50px; font-weight: 600; font-size: 0.8rem; }
        .price-text { font-size: 2.5rem; font-weight: 800; color: var(--accent-color); margin: 20px 0; }
        .qty-input { border-radius: 15px; border: 2px solid #eee; padding: 10px 20px; width: 100px; text-align: center; font-weight: 700; }
        .btn-add-cart { background: linear-gradient(45deg, var(--primary-color), var(--accent-color)); border: none; color: white; padding: 15px 40px; border-radius: 20px; font-weight: 700; box-shadow: 0 10px 20px rgba(0, 210, 255, 0.3); transition: 0.3s; }
        .btn-add-cart:hover { transform: translateY(-5px); box-shadow: 0 15px 25px rgba(0, 210, 255, 0.4); color: white; }
        .card-produk { border: 2px solid white; border-radius: 20px; overflow: hidden; background: white; transition: 0.3s; }
        .card-produk:hover { transform: translateY(-10px); border-color: var(--primary-color); }
    </style>
@endsection

@section('content')
<div class="container product-container">
    <div class="row g-5">
        <div class="col-lg-6">
            <div class="product-image-card">
                @if($produk->foto)
                    <img src="{{ asset('storage/' . $produk->foto) }}" alt="{{ $produk->nama_produk }}">
                @else
                    <img src="https://images.unsplash.com/photo-1547595628-c61a29f496f0?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Placeholder">
                @endif
            </div>
        </div>
        <div class="col-lg-6">
            <div class="product-info-card">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        @if($produk->id_kategori)
                            <span class="badge bg-primary rounded-pill px-3 py-2 small mb-2 d-inline-block">{{ $produk->kategori->nama_kategori }}</span>
                        @endif
                        <span class="badge-stok d-inline-block ms-2">
                            <i class="bi bi-box-seam me-2"></i> Tersedia: {{ $produk->stok }} Porsi
                        </span>
                    </div>
                    @auth
                        @if(auth()->user()->peran !== 'admin')
                            <button class="btn btn-light rounded-circle shadow-sm border-0" 
                                    style="width: 45px; height: 45px; padding: 0;"
                                    onclick="toggleWish(this, {{ $produk->id_produk }})">
                                <i class="bi bi-heart{{ auth()->user()->wishlist->contains('id_produk', $produk->id_produk) ? '-fill text-danger' : '' }} fs-4"></i>
                            </button>
                        @endif
                    @endauth
                </div>
                
                <h1 class="display-5 fw-bold mb-2">{{ $produk->nama_produk }}</h1>
                <p class="text-muted">
                    <span class="text-warning me-2">
                        @php $avg = $produk->averageRating(); @endphp
                        @for($i=1; $i<=5; $i++)
                            <i class="bi bi-star{{ $i <= $avg ? '-fill' : '' }}"></i>
                        @endfor
                    </span>
                    <span class="small">({{ $produk->ulasan->count() }} Penilaian)</span>
                </p>
                
                <div class="price-text">Rp {{ number_format($produk->harga, 0, ',', '.') }}</div>
                
                <div class="mb-4">
                    <h6 class="fw-bold mb-2">Deskripsi Produk</h6>
                    <p class="text-muted lh-lg">
                        {{ $produk->deskripsi ?? 'Nikmati kesegaran istimewa dari ' . $produk->nama_produk . '. Dibuat dengan bahan-bahan pilihan berkualitas tinggi untuk menemani hari Anda di cuaca panas Sumba.' }}
                    </p>
                </div>

                <hr class="my-4 opacity-10">

                <form action="{{ route('keranjang.tambah') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_produk" value="{{ $produk->id_produk }}">
                    
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <label class="small fw-bold d-block mb-2">Jumlah</label>
                            <input type="number" name="jumlah" class="qty-input" value="1" min="1" max="{{ $produk->stok }}" required>
                        </div>
                        <div class="col">
                            <label class="d-block mb-2 text-white">.</label>
                            @if(!auth()->check())
                                <button type="button" class="btn btn-add-cart w-100" data-bs-toggle="modal" data-bs-target="#modalAuthPrompt">
                                    <i class="bi bi-cart-plus me-2"></i> Tambahkan ke Keranjang
                                </button>
                            @elseif(auth()->user()->peran !== 'admin')
                                <button type="submit" class="btn btn-add-cart w-100">
                                    <i class="bi bi-cart-plus me-2"></i> Tambahkan ke Keranjang
                                </button>
                            @else
                                <button type="button" class="btn btn-secondary w-100 py-3 rounded-pill disabled">Admin Mode</button>
                            @endif
                        </div>
                    </div>
                </form>

                <div class="mt-5 p-3 rounded-4 bg-white bg-opacity-50 border border-white">
                    <div class="d-flex align-items-center small text-muted">
                        <i class="bi bi-truck me-3 fs-4 text-primary"></i>
                        <span>Pengiriman cepat mulai dari Rp 5.000 (Jarak Dekat)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rekomendasi Produk -->
    <div class="mt-5 pt-5 border-top">
        <div class="row">
            <!-- Bagian Ulasan -->
            <div class="col-lg-7">
                <h3 class="fw-bold mb-4"><i class="bi bi-chat-left-text me-2 text-primary"></i> Penilaian Produk</h3>
                
                @auth
                    @if(auth()->user()->peran !== 'admin')
                        <div class="card border-0 shadow-sm rounded-4 mb-5 bg-white">
                            <div class="card-body p-4">
                                <h6 class="fw-bold mb-3">Tulis Ulasan Anda</h6>
                                <form action="{{ route('ulasan.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id_produk" value="{{ $produk->id_produk }}">
                                    <div class="mb-3">
                                        <label class="form-label small text-muted">Bintang (1-5)</label>
                                        <select name="bintang" class="form-select border-0 bg-light rounded-3" required>
                                            <option value="5">⭐⭐⭐⭐ (Luar Biasa)</option>
                                            <option value="4">⭐⭐⭐ (Enak)</option>
                                            <option value="3">⭐⭐ (Biasa Saja)</option>
                                            <option value="2">⭐ (Kurang)</option>
                                            <option value="1">❌ (Sangat Kurang)</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <textarea name="komentar" class="form-control border-0 bg-light rounded-3" rows="3" placeholder="Ceritakan kesegaran minuman ini..."></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4 fw-bold">Kirim Ulasan</button>
                                </form>
                            </div>
                        </div>
                    @endif
                @endauth

                <div class="ulasan-list">
                    @forelse($produk->ulasan as $u)
                        <div class="d-flex mb-4 p-3 bg-white rounded-4 shadow-sm border border-light">
                            <div class="flex-shrink-0">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                    {{ strtoupper(substr($u->user->nama, 0, 1)) }}
                                </div>
                            </div>
                            <div class="ms-3 flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h6 class="fw-bold mb-0 small">{{ $u->user->nama }}</h6>
                                    <small class="text-muted" style="font-size: 0.7rem;">{{ $u->created_at->diffForHumans() }}</small>
                                </div>
                                <div class="text-warning mb-2" style="font-size: 0.75rem;">
                                    @for($i=1; $i<=5; $i++)
                                        <i class="bi bi-star{{ $i <= $u->bintang ? '-fill' : '' }}"></i>
                                    @endfor
                                </div>
                                <p class="mb-0 text-muted small lh-base">{{ $u->komentar }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5 bg-white rounded-4 border border-dashed">
                            <i class="bi bi-chat-square-dots text-muted display-4 opacity-25"></i>
                            <p class="mt-3 text-muted mb-0 small">Belum ada ulasan untuk produk ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Bagian Rekomendasi -->
            <div class="col-lg-5">
                <h3 class="fw-bold mb-4"><i class="bi bi-stars me-2 text-warning"></i> Untuk Kamu</h3>
                <div class="row g-3">
                    @foreach($rekomendasi as $item)
                        <div class="col-12">
                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                                <div class="row g-0">
                                    <div class="col-4">
                                        @if($item->foto)
                                            <img src="{{ asset('storage/' . $item->foto) }}" class="img-fluid h-100 object-fit-cover" alt="{{ $item->nama_produk }}">
                                        @else
                                            <img src="https://images.unsplash.com/photo-1547595628-c61a29f496f0?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" class="img-fluid h-100 object-fit-cover" alt="Placeholder">
                                        @endif
                                    </div>
                                    <div class="col-8">
                                        <div class="card-body p-3">
                                            <h6 class="fw-bold mb-1 small">{{ $item->nama_produk }}</h6>
                                            <p class="text-primary fw-bold mb-2 small">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                                            <a href="{{ route('produk.show', $item->id_produk) }}" class="btn btn-outline-primary btn-xs py-1 px-3 rounded-pill" style="font-size: 0.7rem;">Lihat</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
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
