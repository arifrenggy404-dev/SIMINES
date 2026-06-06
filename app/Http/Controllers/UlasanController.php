<?php

namespace App\Http\Controllers;

use App\Models\Ulasan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UlasanController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'id_produk' => 'required|exists:produk,id_produk',
            'bintang' => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:500',
        ]);

        // Cek apakah user pernah membeli produk ini dan statusnya Selesai
        $pernahBeli = Transaksi::where('id_user', Auth::id())
            ->where('status', 'Selesai')
            ->whereHas('detailTransaksi', function($q) use ($request) {
                $q->where('id_produk', $request->id_produk);
            })->exists();

        if (!$pernahBeli) {
            return back()->with('error', 'Anda hanya dapat mengulas produk yang sudah Anda beli.');
        }

        Ulasan::create([
            'id_user' => Auth::id(),
            'id_produk' => $request->id_produk,
            'bintang' => $request->bintang,
            'komentar' => $request->komentar,
        ]);

        return back()->with('success', 'Terima kasih atas ulasan Anda!');
    }
}
