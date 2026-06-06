<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    /**
     * Menampilkan daftar voucher untuk admin
     */
    public function index()
    {
        $vouchers = Voucher::all();
        return view('admin.voucher.index', compact('vouchers'));
    }

    /**
     * Form tambah voucher
     */
    public function create()
    {
        return view('admin.voucher.create');
    }

    /**
     * Simpan voucher baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|unique:vouchers,kode|max:255',
            'potongan_harga' => 'required|integer|min:0',
            'minimal_belanja' => 'required|integer|min:0',
        ]);

        Voucher::create($request->all());

        return redirect()->route('admin.voucher.index')->with('success', 'Voucher berhasil ditambahkan!');
    }

    /**
     * Form edit voucher
     */
    public function edit($id)
    {
        $voucher = Voucher::findOrFail($id);
        return view('admin.voucher.edit', compact('voucher'));
    }

    /**
     * Update voucher
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'kode' => 'required|string|max:255|unique:vouchers,kode,' . $id . ',id_voucher',
            'potongan_harga' => 'required|integer|min:0',
            'minimal_belanja' => 'required|integer|min:0',
        ]);

        $voucher = Voucher::findOrFail($id);
        $voucher->update($request->all());

        return redirect()->route('admin.voucher.index')->with('success', 'Voucher berhasil diperbarui!');
    }

    /**
     * Hapus voucher
     */
    public function destroy($id)
    {
        $voucher = Voucher::findOrFail($id);
        $voucher->delete();

        return redirect()->route('admin.voucher.index')->with('success', 'Voucher berhasil dihapus!');
    }

    public function cek(Request $request)
    {
        $voucher = Voucher::where('kode', $request->kode)->first();

        if (!$voucher) {
            return back()->with('error', 'Kode voucher tidak valid!');
        }

        $totalBelanja = 0;
        $keranjang = session()->get('keranjang', []);
        foreach($keranjang as $item) {
            $totalBelanja += $item['harga'] * $item['jumlah'];
        }

        if ($totalBelanja < $voucher->minimal_belanja) {
            return back()->with('error', 'Minimal belanja belum tercapai (Min: Rp ' . number_format($voucher->minimal_belanja, 0, ',', '.') . ')');
        }

        session()->put('voucher', [
            'kode' => $voucher->kode,
            'potongan' => $voucher->potongan_harga
        ]);

        return back()->with('success', 'Voucher berhasil dipasang! Potongan: Rp ' . number_format($voucher->potongan_harga, 0, ',', '.'));
    }

    public function hapus()
    {
        session()->forget('voucher');
        return back()->with('success', 'Voucher dilepas.');
    }
}
