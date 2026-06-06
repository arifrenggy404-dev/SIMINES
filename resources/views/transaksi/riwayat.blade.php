@extends('layouts.app')

@section('title', 'Riwayat Pesanan - SIMINES')

@section('content')
<div class="container my-5">
    <h2 class="fw-bold mb-4"><i class="bi bi-clock-history text-primary"></i> Riwayat Pesanan Anda</h2>

    @if($transaksi->count() > 0)
        @foreach($transaksi as $t)
            <div class="card shadow-sm border-0 mb-4 rounded-4 overflow-hidden">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom border-light">
                    <div>
                        <span class="text-muted small">No. Pesanan: <strong>#{{ $t->id_transaksi }}</strong></span>
                        <span class="ms-3 text-muted small"><i class="bi bi-calendar3 me-1"></i> {{ date('d M Y, H:i', strtotime($t->tanggal)) }}</span>
                    </div>
                    @php
                        $color = ['Pending'=>'warning','Paid'=>'primary','Diproses'=>'info','Dikirim'=>'dark','Selesai'=>'success','Failed'=>'danger'][$t->status] ?? 'secondary';
                        $steps = [
                            ['label' => 'PESAN', 'status' => 'Pending', 'time' => $t->tanggal],
                            ['label' => 'DIBAYAR', 'status' => 'Paid', 'time' => $t->updated_at],
                            ['label' => 'DIPROSES', 'status' => 'Diproses', 'time' => $t->diproses_at],
                            ['label' => 'DIKIRIM', 'status' => 'Dikirim', 'time' => $t->dikirim_at],
                            ['label' => 'SELESAI', 'status' => 'Selesai', 'time' => $t->selesai_at],
                        ];
                        
                        // Tentukan langkah saat ini berdasarkan status
                        $statusToIndex = ['Pending' => 0, 'Paid' => 1, 'Diproses' => 2, 'Dikirim' => 3, 'Selesai' => 4, 'Failed' => -1];
                        $currentStepIndex = $statusToIndex[$t->status] ?? -1;
                    @endphp
                    <span class="badge bg-{{ $color }} rounded-pill px-3 py-2">
                        {{ $t->status }}
                    </span>
                    @if($t->is_reservasi)
                        <span class="badge bg-warning text-dark rounded-pill px-3 py-2 ms-2">
                            <i class="bi bi-calendar-check me-1"></i> RESERVASI: {{ date('d M, H:i', strtotime($t->jadwal_pengambilan)) }}
                        </span>
                    @endif
                </div>
                <div class="card-body p-4">
                    <!-- Fitur 4: Visual Tracking ala Shopee/Tokopedia -->
                    <div class="mb-5 px-3">
                        <div class="d-flex justify-content-between position-relative mb-2">
                            <div class="position-absolute top-50 start-0 translate-middle-y w-100" style="height: 3px; background-color: #e9ecef; z-index: 1;"></div>
                            <div class="position-absolute top-50 start-0 translate-middle-y" style="height: 3px; background-color: #00d2ff; z-index: 2; width: {{ ($currentStepIndex >= 0) ? ($currentStepIndex / (count($steps)-1) * 100) : 0 }}%; transition: width 0.8s ease-in-out;"></div>
                            
                            @foreach($steps as $index => $step)
                                <div class="text-center" style="z-index: 3; width: 80px;">
                                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center border-4 border-white shadow-sm mb-2" 
                                         style="width: 35px; height: 35px; background-color: {{ ($currentStepIndex >= $index) ? '#00d2ff' : '#e9ecef' }}; color: white; transition: 0.3s;">
                                        @if($currentStepIndex >= $index)
                                            <i class="bi bi-check-lg fs-6"></i>
                                        @else
                                            <span style="font-size: 0.7rem;">{{ $index + 1 }}</span>
                                        @endif
                                    </div>
                                    <p class="mb-0 fw-bold {{ ($currentStepIndex >= $index) ? 'text-primary' : 'text-muted' }}" style="font-size: 0.65rem;">{{ $step['label'] }}</p>
                                    @if($step['time'] && $currentStepIndex >= $index)
                                        <p class="text-muted extra-small mb-0" style="font-size: 0.55rem; line-height: 1;">{{ date('H:i', strtotime($step['time'])) }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="row align-items-center">
                        <div class="col-md-7">
                            <h6 class="fw-bold mb-3">Item Pesanan:</h6>
                            <ul class="list-unstyled mb-0">
                                @foreach($t->detailTransaksi as $detail)
                                    <li class="mb-2 d-flex justify-content-between align-items-center bg-light p-2 rounded-3">
                                        <div>
                                            <i class="bi bi-check2-circle text-success me-2"></i>
                                            <span class="fw-600">{{ $detail->produk->nama_produk }}</span> 
                                            <small class="text-muted ms-1">x{{ $detail->jumlah }}</small>
                                            @if($t->status === 'Selesai')
                                                <button class="btn btn-sm btn-link text-primary p-0 ms-2 text-decoration-none small" data-bs-toggle="modal" data-bs-target="#modalReview-{{ $detail->id_produk }}">
                                                    <i class="bi bi-star-fill me-1"></i> Beri Ulasan
                                                </button>
                                                
                                                <!-- Modal Review -->
                                                <div class="modal fade" id="modalReview-{{ $detail->id_produk }}" tabindex="-1">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content border-0 rounded-4">
                                                            <div class="modal-header border-0 pb-0">
                                                                <h5 class="modal-title fw-bold">Ulas Produk</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <form action="{{ route('ulasan.store') }}" method="POST">
                                                                @csrf
                                                                <div class="modal-body pt-3">
                                                                    <input type="hidden" name="id_produk" value="{{ $detail->id_produk }}">
                                                                    <div class="text-center mb-4">
                                                                        <h6 class="fw-bold text-primary">{{ $detail->produk->nama_produk }}</h6>
                                                                        <div class="rating-input mt-3 fs-3 text-warning">
                                                                            @for($i=1; $i<=5; $i++)
                                                                                <input type="radio" class="btn-check" name="bintang" id="star-{{ $detail->id_produk }}-{{ $i }}" value="{{ $i }}" required>
                                                                                <label class="bi bi-star me-1" for="star-{{ $detail->id_produk }}-{{ $i }}" style="cursor: pointer;"></label>
                                                                            @endfor
                                                                        </div>
                                                                        <small class="text-muted">Klik salah satu bintang</small>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label small fw-bold">Komentar (Opsional)</label>
                                                                        <textarea name="komentar" class="form-control" rows="3" placeholder="Bagaimana kesegaran es ini?"></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer border-0">
                                                                    <button type="submit" class="btn btn-primary w-100 py-2 rounded-pill fw-bold">KIRIM ULASAN</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <span class="text-dark fw-bold">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="mt-3 ps-2">
                                <small class="text-muted d-block"><i class="bi bi-truck me-1"></i> Ongkos Kirim ({{ number_format($t->jarak_km, 2) }} KM): Rp {{ number_format($t->biaya_ongkir, 0, ',', '.') }}</small>
                                @if($t->kode_voucher)
                                    <small class="text-success d-block mt-1 fw-bold"><i class="bi bi-ticket-perforated me-1"></i> Voucher: - Rp {{ number_format($t->potongan_voucher, 0, ',', '.') }}</small>
                                @endif
                                <small class="text-muted d-block mt-1"><i class="bi bi-geo-alt me-1"></i> Alamat: {{ $t->alamat_pengiriman }}</small>
                            </div>
                        </div>
                        <div class="col-md-5 border-start text-center py-3">
                            <p class="mb-1 text-muted small uppercase">Total Pembayaran</p>
                            <h3 class="fw-bold text-primary mb-3">Rp {{ number_format($t->total_harga, 0, ',', '.') }}</h3>
                            <div class="d-flex flex-column gap-2 align-items-center">
                                @if($t->status == 'Pending')
                                    <a href="{{ route('pembayaran', $t->id_transaksi) }}" class="btn btn-warning rounded-pill px-4 fw-bold shadow-sm w-100">BAYAR SEKARANG</a>
                                @elseif($t->status == 'Dikirim')
                                    <form action="{{ route('admin.pesanan.update', $t->id_transaksi) }}" method="POST" class="w-100">
                                        @csrf
                                        <input type="hidden" name="status" value="Selesai">
                                        <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm w-100">PESANAN DITERIMA</button>
                                    </form>
                                @endif
                                <a href="{{ route('transaksi.invoice', $t->id_transaksi) }}" class="btn btn-outline-primary rounded-pill px-4 fw-bold btn-sm w-100">LIHAT NOTA</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="bi bi-bag-x display-1 text-muted opacity-25"></i>
            </div>
            <h4 class="mt-4 text-muted fw-bold">Anda belum memiliki riwayat pesanan.</h4>
            <p class="text-muted">Minuman es favorit Anda sedang menunggu untuk dipesan!</p>
            <a href="{{ route('produk.index') }}" class="btn btn-primary mt-3 px-5 py-3 rounded-pill shadow fw-bold">MULAI BELANJA</a>
        </div>
    @endif
</div>
    <style>
        .extra-small { font-size: 0.75rem; }
        .fw-600 { font-weight: 600; }
        .rating-input .bi-star { color: #ccc; cursor: pointer; transition: 0.2s; }
        .rating-input .btn-check:checked + .bi-star,
        .rating-input .bi-star:hover,
        .rating-input .bi-star:hover ~ .bi-star { color: #ffc107 !important; }
        .rating-input .bi-star:before { content: "\F588"; }
        .rating-input .btn-check:checked + .bi-star:before { content: "\F586"; }
    </style>
@endsection
