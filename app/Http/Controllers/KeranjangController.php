<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KeranjangController extends Controller
{
    /**
     * Menampilkan isi keranjang
     */
    public function index()
    {
        $keranjang = session()->get('keranjang', []);
        $total = 0;
        foreach($keranjang as $item) {
            $total += $item['harga'] * $item['jumlah'];
        }
        
        $pengaturan = \App\Models\PengaturanOngkir::first();
        
        return view('keranjang.index', compact('keranjang', 'total', 'pengaturan'));
    }

    /**
     * Menambah produk ke keranjang
     */
    public function tambah(Request $request)
    {
        // Jika belum login, jangan proses tapi biarkan frontend modal bekerja
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Pastikan bukan admin yang pesan
        if (Auth::user()->peran === 'admin') {
            return back()->with('error', 'Admin tidak diperbolehkan memesan.');
        }

        $produk = Produk::findOrFail($request->id_produk);
        $keranjang = session()->get('keranjang', []);

        // Jika produk sudah ada di keranjang, tambah jumlahnya
        if(isset($keranjang[$request->id_produk])) {
            $keranjang[$request->id_produk]['jumlah'] += $request->jumlah;
        } else {
            // Jika belum ada, tambahkan baru
            $keranjang[$request->id_produk] = [
                "nama_produk" => $produk->nama_produk,
                "jumlah" => $request->jumlah,
                "harga" => $produk->harga,
            ];
        }

        session()->put('keranjang', $keranjang);
        return redirect()->route('keranjang.index')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    /**
     * Menghapus beberapa produk sekaligus dari keranjang
     */
    public function hapusMassal(Request $request)
    {
        $ids = json_decode($request->ids, true);
        $keranjang = session()->get('keranjang', []);
        
        if ($ids) {
            foreach ($ids as $id) {
                if (isset($keranjang[$id])) unset($keranjang[$id]);
            }
            session()->put('keranjang', $keranjang);
            return back()->with('success', count($ids) . ' produk dihapus dari keranjang.');
        }
        
        return back();
    }

    /**
     * Menghapus item dari keranjang
     */
    public function hapus($id)
    {
        $keranjang = session()->get('keranjang');
        if(isset($keranjang[$id])) {
            unset($keranjang[$id]);
            session()->put('keranjang', $keranjang);
        }
        return back()->with('success', 'Produk dihapus dari keranjang.');
    }
}
