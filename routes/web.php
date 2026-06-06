<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\UlasanController;

// Public Routes (Bisa diakses tanpa login)
Route::get('/', [ProdukController::class, 'index'])->name('produk.index');
Route::get('/produk/{id}', [ProdukController::class, 'show'])->name('produk.show');
Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang.index');
Route::post('/keranjang/tambah', [KeranjangController::class, 'tambah'])->name('keranjang.tambah');
Route::post('/keranjang/hapus/massal', [KeranjangController::class, 'hapusMassal'])->name('keranjang.hapus.massal');
Route::post('/keranjang/hapus/{id}', [KeranjangController::class, 'hapus'])->name('keranjang.hapus');
Route::post('/voucher/cek', [VoucherController::class, 'cek'])->name('voucher.cek');
Route::post('/voucher/hapus', [VoucherController::class, 'hapus'])->name('voucher.hapus');

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/otp', [AuthController::class, 'showOtp'])->name('otp.verify');
Route::post('/otp', [AuthController::class, 'verifyOtp']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes (Hanya untuk yang sudah login & OTP verified)
Route::middleware(['auth'])->group(function () {
    Route::post('/ulasan/simpan', [UlasanController::class, 'store'])->name('ulasan.store');
    Route::get('/profil', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profil/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/riwayat', [TransaksiController::class, 'riwayat'])->name('transaksi.riwayat');
    Route::get('/transaksi/invoice/{id}', [TransaksiController::class, 'invoice'])->name('transaksi.invoice');
    Route::post('/pesan', [TransaksiController::class, 'store'])->name('transaksi.store');
    Route::get('/pembayaran/{id}', [TransaksiController::class, 'showPembayaran'])->name('pembayaran');
    Route::post('/bayar/{id}', [TransaksiController::class, 'bayar'])->name('bayar');

    // Wishlist
    Route::get('/wishlist', [\App\Http\Controllers\WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle', [\App\Http\Controllers\WishlistController::class, 'toggle'])->name('wishlist.toggle');
    
    // Admin Dashboard & Management (Hanya Admin)
    Route::middleware(['admin'])->group(function () {
        Route::get('/admin', [TransaksiController::class, 'dashboardAdmin'])->name('admin.dashboard');

        // Admin Kelola Produk (CRUD)
        Route::get('/admin/produk', [ProdukController::class, 'adminIndex'])->name('admin.produk.index');
        Route::get('/admin/produk/tambah', [ProdukController::class, 'create'])->name('admin.produk.create');
        Route::post('/admin/produk/simpan', [ProdukController::class, 'store'])->name('admin.produk.store');
        Route::get('/admin/produk/edit/{id}', [ProdukController::class, 'edit'])->name('admin.produk.edit');
        Route::post('/admin/produk/update/{id}', [ProdukController::class, 'update'])->name('admin.produk.update');
        Route::post('/admin/produk/hapus/{id}', [ProdukController::class, 'destroy'])->name('admin.produk.destroy');

        // Admin Kelola Pesanan
        Route::get('/admin/pesanan', [TransaksiController::class, 'indexAdmin'])->name('admin.pesanan.index');
        Route::get('/admin/laporan/cetak', [TransaksiController::class, 'cetakLaporan'])->name('admin.laporan.cetak');
        Route::post('/admin/pesanan/update/{id}', [TransaksiController::class, 'updateStatus'])->name('admin.pesanan.update');
        Route::post('/admin/pesanan/hapus/{id}', [TransaksiController::class, 'destroy'])->name('admin.pesanan.destroy');

        // Admin Kelola Voucher
        Route::get('/admin/voucher', [VoucherController::class, 'index'])->name('admin.voucher.index');
        Route::get('/admin/voucher/tambah', [VoucherController::class, 'create'])->name('admin.voucher.create');
        Route::post('/admin/voucher/simpan', [VoucherController::class, 'store'])->name('admin.voucher.store');
        Route::get('/admin/voucher/edit/{id}', [VoucherController::class, 'edit'])->name('admin.voucher.edit');
        Route::post('/admin/voucher/update/{id}', [VoucherController::class, 'update'])->name('admin.voucher.update');
        Route::post('/admin/voucher/hapus/{id}', [VoucherController::class, 'destroy'])->name('admin.voucher.destroy');

        // Admin Kelola Kategori
        Route::get('/admin/kategori', [\App\Http\Controllers\KategoriController::class, 'index'])->name('admin.kategori.index');
        Route::get('/admin/kategori/tambah', [\App\Http\Controllers\KategoriController::class, 'create'])->name('admin.kategori.create');
        Route::post('/admin/kategori/simpan', [\App\Http\Controllers\KategoriController::class, 'store'])->name('admin.kategori.store');
        Route::get('/admin/kategori/edit/{id}', [\App\Http\Controllers\KategoriController::class, 'edit'])->name('admin.kategori.edit');
        Route::post('/admin/kategori/update/{id}', [\App\Http\Controllers\KategoriController::class, 'update'])->name('admin.kategori.update');
        Route::post('/admin/kategori/hapus/{id}', [\App\Http\Controllers\KategoriController::class, 'destroy'])->name('admin.kategori.destroy');

        // Admin Kelola Banner
        Route::get('/admin/banner', [\App\Http\Controllers\BannerController::class, 'index'])->name('admin.banner.index');
        Route::get('/admin/banner/tambah', [\App\Http\Controllers\BannerController::class, 'create'])->name('admin.banner.create');
        Route::post('/admin/banner/simpan', [\App\Http\Controllers\BannerController::class, 'store'])->name('admin.banner.store');
        Route::get('/admin/banner/edit/{id}', [\App\Http\Controllers\BannerController::class, 'edit'])->name('admin.banner.edit');
        Route::post('/admin/banner/update/{id}', [\App\Http\Controllers\BannerController::class, 'update'])->name('admin.banner.update');
        Route::post('/admin/banner/hapus/{id}', [\App\Http\Controllers\BannerController::class, 'destroy'])->name('admin.banner.destroy');

        // Admin Kelola User
        Route::get('/admin/users', [TransaksiController::class, 'usersAdmin'])->name('admin.users.index');

        // Admin Pengaturan Ongkir & Lokasi Toko
        Route::get('/admin/pengaturan-ongkir', [\App\Http\Controllers\PengaturanController::class, 'index'])->name('admin.pengaturan.ongkir');
        Route::post('/admin/pengaturan-ongkir/update', [\App\Http\Controllers\PengaturanController::class, 'update'])->name('admin.pengaturan.update');
    });
});
