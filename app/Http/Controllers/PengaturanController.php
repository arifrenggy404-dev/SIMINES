<?php

namespace App\Http\Controllers;

use App\Models\PengaturanOngkir;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    /**
     * Tampilkan halaman pengaturan ongkir & lokasi toko
     */
    public function index()
    {
        $pengaturan = PengaturanOngkir::first();
        return view('admin.pengaturan.ongkir', compact('pengaturan'));
    }

    /**
     * Update pengaturan ongkir & lokasi toko
     */
    public function update(Request $request)
    {
        $request->validate([
            'toko_latitude' => 'required|string',
            'toko_longitude' => 'required|string',
            'harga_per_km' => 'required|integer|min:0',
            'gratis_ongkir_km' => 'required|integer|min:0',
            'max_jarak_km' => 'required|integer|min:1',
            'jam_buka' => 'required',
            'jam_tutup' => 'required',
        ]);

        $pengaturan = PengaturanOngkir::first();
        $data = $request->all();
        $data['manual_tutup'] = $request->has('manual_tutup');
        
        $pengaturan->update($data);

        return back()->with('success', 'Pengaturan operasional dan lokasi berhasil diperbarui!');
    }
}
