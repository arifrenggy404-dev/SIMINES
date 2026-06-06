@extends('layouts.app')

@section('title', 'Daftar Keinginan - SIMINES')

@section('styles')
    <style>
        .wishlist-card { border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.03); background: white; overflow: hidden; }
        .product-img-wish { width: 80px; height: 80px; object-fit: cover; border-radius: 15px; }
    </style>
@endsection

@section('content')
<div class="container my-5">
    <div class="d-flex align-items-center mb-5">
        <div class="bg-danger text-white rounded-4 p-3 me-3 shadow-sm">
            <i class="bi bi-heart-fill fs-3"></i>
        </div>
        <div>
            <h2 class="fw-bold mb-0">Daftar Keinginan</h2>
            <p class="text-muted mb-0">Minuman favorit yang ingin Anda beli nanti</p>
        </div>
    </div>

    @if($wishlist->count() > 0)
        <div class="row g-4">
            @foreach($wishlist as $item)
                <div class="col-md-6">
                    <div class="wishlist-card p-3 d-flex align-items-center">
                        <img src="{{ asset('storage/' . $item->produk->foto) }}" class="product-img-wish me-3">
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-1">{{ $item->produk->nama_produk }}</h6>
                            <p class="text-primary fw-bold mb-0">Rp {{ number_format($item->produk->harga, 0, ',', '.') }}</p>
                        </div>
                        <div class="d-flex gap-2">
                            <form action="{{ route('keranjang.tambah') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id_produk" value="{{ $item->produk->id_produk }}">
                                <input type="hidden" name="jumlah" value="1">
                                <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3">
                                    <i class="bi bi-cart-plus me-1"></i> Beli
                                </button>
                            </form>
                            <button class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="toggleWishlist({{ $item->produk->id_produk }}, this)">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-heart text-muted opacity-25 display-1 mb-4"></i>
            <h4 class="fw-bold text-dark">Daftar keinginan masih kosong</h4>
            <p class="text-muted mb-4">Mungkin ada es segar yang ingin Anda simpan dulu?</p>
            <a href="{{ route('produk.index') }}" class="btn btn-primary rounded-pill px-5">LIHAT MENU</a>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    function toggleWishlist(id, btn) {
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
            if (data.status === 'removed') {
                btn.closest('.col-md-6').remove();
                if (document.querySelectorAll('.wishlist-card').length === 0) {
                    location.reload();
                }
            }
        });
    }
</script>
@endsection
