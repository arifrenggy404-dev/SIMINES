<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class TransaksiController extends Controller
{
    /**
     * Cetak Laporan Penjualan (PDF)
     */
    public function cetakLaporan()
    {
        $transaksi = Transaksi::with(['user', 'detailTransaksi.produk'])
            ->whereIn('status', ['Paid', 'Diproses', 'Dikirim', 'Selesai'])
            ->orderBy('tanggal', 'desc')
            ->get();

        $totalPendapatan = $transaksi->sum('total_harga');
        
        $pdf = Pdf::loadView('admin.laporan.pdf', compact('transaksi', 'totalPendapatan'));
        return $pdf->download('Laporan-Penjualan-SIMINES-' . date('Y-m-d') . '.pdf');
    }

    public function store(Request $request)
    {
        // Cek apakah user adalah admin
        if (Auth::user()->peran === 'admin') {
            return back()->with('error', 'Admin tidak diperbolehkan melakukan pemesanan.');
        }

        $request->validate([
            'latitude' => 'required',
            'longitude' => 'required',
            'metode_pembayaran' => 'required|in:COD,E-Wallet',
            'alamat_pengiriman' => 'required|string',
            'selected_items' => 'required', // String JSON ID produk
            'is_reservasi' => 'nullable',
            'jadwal_pengambilan' => 'required_if:is_reservasi,1',
        ]);

        $allKeranjang = session()->get('keranjang', []);
        $selectedIds = json_decode($request->selected_items, true);
        
        if (empty($selectedIds)) {
            return redirect()->route('keranjang.index')->with('error', 'Pilih minimal satu produk untuk dibeli!');
        }

        // Filter keranjang hanya untuk item terpilih
        $keranjang = array_intersect_key($allKeranjang, array_flip($selectedIds));

        if (empty($keranjang)) {
            return redirect()->route('produk.index')->with('error', 'Keranjang belanja kosong atau produk tidak valid!');
        }

        // Ambil pengaturan ongkir
        $pengaturan = \App\Models\PengaturanOngkir::first();
        
        // Hitung Jarak Aktual (Haversine Formula)
        $lat1 = $pengaturan->toko_latitude;
        $lon1 = $pengaturan->toko_longitude;
        $lat2 = $request->latitude;
        $lon2 = $request->longitude;

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $jarakKm = $miles * 1.609344;

        // Validasi Batas Wilayah (Radius KM)
        if ($jarakKm > $pengaturan->max_jarak_km) {
            return back()->with('error', 'Maaf, lokasi Anda berada di luar jangkauan pengiriman kami (Maksimal ' . $pengaturan->max_jarak_km . ' KM).');
        }

        // Hitung Biaya Ongkir
        $biayaOngkir = 0;
        if ($jarakKm > $pengaturan->gratis_ongkir_km) {
            $biayaOngkir = ceil($jarakKm) * $pengaturan->harga_per_km;
        }
        
        // Fitur 1: Voucher Diskon ala Shopee
        $potonganVoucher = 0;
        $kodeVoucher = null;
        if (session()->has('voucher')) {
            $kodeVoucher = session('voucher')['kode'];
            $potonganVoucher = session('voucher')['potongan'];
        }

        DB::beginTransaction();
        try {
            $totalItem = 0;
            foreach($keranjang as $item) {
                $totalItem += $item['harga'] * $item['jumlah'];
            }

            $totalAkhir = ($totalItem + $biayaOngkir) - $potonganVoucher;
            if ($totalAkhir < 0) $totalAkhir = 0;

            // 1. Buat Transaksi (Header)
            $transaksi = Transaksi::create([
                'id_user' => Auth::id(), 
                'tanggal' => now(),
                'total_harga' => $totalAkhir,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'jarak_km' => $jarakKm,
                'biaya_ongkir' => $biayaOngkir,
                'kode_voucher' => $kodeVoucher,
                'potongan_voucher' => $potonganVoucher,
                'status' => 'Pending',
                'metode_pembayaran' => $request->metode_pembayaran,
                'alamat_pengiriman' => $request->alamat_pengiriman,
                'is_reservasi' => $request->is_reservasi ? true : false,
                'jadwal_pengambilan' => $request->jadwal_pengambilan,
            ]);

            // 2. Buat Detail Transaksi
            foreach($keranjang as $id_produk => $item) {
                $produk = Produk::findOrFail($id_produk);
                if ($produk->stok < $item['jumlah']) throw new \Exception("Stok " . $produk->nama_produk . " habis!");

                DetailTransaksi::create([
                    'id_transaksi' => $transaksi->id_transaksi,
                    'id_produk' => $id_produk,
                    'jumlah' => $item['jumlah'],
                    'subtotal' => $item['harga'] * $item['jumlah'],
                ]);
                $produk->decrement('stok', $item['jumlah']);

                // Hapus item yang sudah dibeli dari keranjang session
                unset($allKeranjang[$id_produk]);
            }

            // Simpan sisa keranjang kembali ke session
            session()->put('keranjang', $allKeranjang);
            session()->forget('voucher');
            DB::commit();

            if ($request->metode_pembayaran === 'E-Wallet') {
                return redirect()->route('pembayaran', $transaksi->id_transaksi);
            }

            return redirect()->route('transaksi.riwayat')->with('success', 'Pesanan Anda berhasil dibuat (COD)! Silakan pantau proses pembuatan produk di bawah ini.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Kesalahan: ' . $e->getMessage());
        }
    }

    public function showPembayaran($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        return view('transaksi.pembayaran', compact('transaksi'));
    }

    public function bayar(Request $request, $id)
    {
        $transaksi = Transaksi::findOrFail($id);
        // Simulasi verifikasi payment gateway sesuai dokumen (Bab V 5.4)
        $transaksi->update(['status' => 'Paid']);
        return redirect()->route('transaksi.riwayat')->with('success', 'Pembayaran E-Wallet berhasil! Admin akan memproses pesanan.');
    }

    /**
     * Menampilkan riwayat pesanan pelanggan (Bab VII 7.1)
     */
    public function riwayat()
    {
        $transaksi = Transaksi::where('id_user', Auth::id())
            ->with('detailTransaksi.produk')
            ->orderBy('tanggal', 'desc')
            ->get();
        return view('transaksi.riwayat', compact('transaksi'));
    }

    /**
     * Menampilkan daftar pesanan untuk admin
     */
    public function indexAdmin()
    {
        $transaksi = Transaksi::with(['user', 'detailTransaksi.produk'])->orderBy('tanggal', 'desc')->get();
        return view('admin.pesanan.index', compact('transaksi'));
    }

    /**
     * Menampilkan Invoice / Nota Transaksi (Bab VII 7.1)
     */
    public function invoice($id)
    {
        $transaksi = Transaksi::with(['user', 'detailTransaksi.produk'])->findOrFail($id);
        
        // Pastikan hanya pemilik transaksi atau admin yang bisa melihat
        if (Auth::user()->peran !== 'admin' && $transaksi->id_user !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        return view('transaksi.invoice', compact('transaksi'));
    }

    /**
     * Dashboard Admin dengan Laporan Keuntungan & Evaluasi Bisnis
     */
    public function dashboardAdmin()
    {
        $transaksi = Transaksi::with(['user', 'detailTransaksi.produk'])->orderBy('tanggal', 'desc')->get();
        
        // 1. Laporan Keuntungan Ongkir (Bab II 2.2.2)
        $transaksiLunas = $transaksi->where('status', '!=', 'Pending')->where('status', '!=', 'Failed');
        $totalPendapatanOngkir = $transaksiLunas->sum('biaya_ongkir');
        
        // Estimasi operasional (misal: Rp 1000 per KM untuk bensin)
        $totalJarakRiil = $transaksiLunas->sum('jarak_km');
        $totalOperasional = $totalJarakRiil * 1000;
        $keuntunganBersihOngkir = $totalPendapatanOngkir - $totalOperasional;

        // 2. Laporan Keuntungan Produk (Bab II 2.2)
        // Hitung selisih harga jual dan biaya produksi dari semua detail transaksi yang sudah lunas
        $profitProduk = 0;
        foreach($transaksiLunas as $t) {
            foreach($t->detailTransaksi as $detail) {
                $margin = $detail->subtotal - ($detail->produk->harga_modal * $detail->jumlah);
                $profitProduk += $margin;
            }
        }

        // 3. Evaluasi Stok (Bab I 1.1.3)
        $produkMenipis = Produk::where('stok', '<', 10)->get();

        // 4. Produk Terlaris
        $terlaris = DetailTransaksi::select('id_produk', DB::raw('SUM(jumlah) as total_terjual'))
            ->groupBy('id_produk')
            ->orderBy('total_terjual', 'desc')
            ->with('produk')
            ->take(5)
            ->get();

        // 5. Data Grafik (7 hari terakhir)
        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $chartLabels[] = date('d M', strtotime($date));
            $chartData[] = Transaksi::whereIn('status', ['Paid', 'Diproses', 'Dikirim', 'Selesai'])
                ->whereDate('tanggal', $date)
                ->sum('total_harga');
        }

        return view('admin.dashboard', compact(
            'transaksi', 
            'totalPendapatanOngkir', 
            'totalOperasional', 
            'keuntunganBersihOngkir',
            'profitProduk',
            'produkMenipis',
            'terlaris',
            'chartLabels',
            'chartData'
        ));
    }

    /**
     * Menampilkan daftar pelanggan
     */
    public function usersAdmin()
    {
        $users = \App\Models\User::where('peran', 'pelanggan')->orderBy('created_at', 'desc')->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Memperbarui status pesanan
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Diproses,Dikirim,Selesai,Failed',
        ]);

        $transaksi = Transaksi::findOrFail($id);
        $updateData = ['status' => $request->status];

        // Logika Otomatisasi (Bab VII 7.1)
        // Jika admin menyelesaikan pesanan, otomatis anggap lunas (untuk COD atau kelalaian sistem)
        if ($request->status === 'Selesai') {
            $updateData['selesai_at'] = now();
            // Jika status sebelumnya masih Pending/Diproses/Dikirim, pastikan record status akhir adalah Selesai
            // dan secara implisit uang sudah diterima.
        }

        if ($request->status === 'Diproses' && !$transaksi->diproses_at) {
            $updateData['diproses_at'] = now();
        } elseif ($request->status === 'Dikirim' && !$transaksi->dikirim_at) {
            $updateData['dikirim_at'] = now();
        }

        $transaksi->update($updateData);

        return redirect()->route('admin.pesanan.index')->with('success', 'Status pesanan berhasil diperbarui!');
    }

    /**
     * Hapus pesanan
     */
    public function destroy($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->delete();

        return redirect()->route('admin.pesanan.index')->with('success', 'Pesanan berhasil dihapus!');
    }
}
