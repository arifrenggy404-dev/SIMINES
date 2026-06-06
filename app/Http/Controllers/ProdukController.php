<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    /**
     * Menampilkan daftar produk (menu) untuk publik
     */
    public function index(Request $request)
    {
        if (auth()->check() && auth()->user()->peran === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        $query = Produk::query();

        // Fitur Pencarian & Filter
        if ($request->has('cari')) {
            $query->where('nama_produk', 'LIKE', '%' . $request->cari . '%')
                  ->orWhere('deskripsi', 'LIKE', '%' . $request->cari . '%');
        }

        if ($request->filled('kategori')) {
            $query->where('id_kategori', $request->kategori);
        }

        if ($request->filled('urutkan')) {
            if ($request->urutkan === 'termurah') $query->orderBy('harga', 'asc');
            elseif ($request->urutkan === 'termahal') $query->orderBy('harga', 'desc');
            elseif ($request->urutkan === 'terbaru') $query->orderBy('created_at', 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $produk = $query->with(['ulasan', 'kategori'])->get();
        $vouchers = \App\Models\Voucher::all();
        $kategori = \App\Models\Kategori::all();
        $banners = \App\Models\Banner::where('is_active', true)->orderBy('urutan', 'asc')->get();

        return view('produk.index', compact('produk', 'vouchers', 'kategori', 'banners'));
    }

    /**
     * Menampilkan detail produk
     */
    public function show($id)
    {
        $produk = Produk::with('ulasan.user')->findOrFail($id);
        $rekomendasi = Produk::where('id_produk', '!=', $id)->inRandomOrder()->take(4)->get();
        return view('produk.show', compact('produk', 'rekomendasi'));
    }

    /**
     * Menampilkan daftar produk untuk admin
     */
    public function adminIndex()
    {
        $produk = Produk::all();
        return view('admin.produk.index', compact('produk'));
    }

    /**
     * Form tambah produk
     */
    public function create()
    {
        $kategori = \App\Models\Kategori::all();
        return view('admin.produk.create', compact('kategori'));
    }

    /**
     * Simpan produk baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'id_kategori' => 'nullable|exists:kategori,id_kategori',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|integer|min:0',
            'harga_modal' => 'required|integer|min:0',
            'stok' => 'required|integer|min:0',
        ]);

        $data = $request->all();

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('produk', 'public');
        }

        Produk::create($data);

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * Form edit produk
     */
    public function edit($id)
    {
        $produk = Produk::findOrFail($id);
        $kategori = \App\Models\Kategori::all();
        return view('admin.produk.edit', compact('produk', 'kategori'));
    }

    /**
     * Update produk
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'id_kategori' => 'nullable|exists:kategori,id_kategori',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|integer|min:0',
            'harga_modal' => 'required|integer|min:0',
            'stok' => 'required|integer|min:0',
        ]);

        $produk = Produk::findOrFail($id);
        $data = $request->all();

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($produk->foto && \Illuminate\Support\Facades\Storage::disk('public')->exists($produk->foto)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($produk->foto);
            }
            $data['foto'] = $request->file('foto')->store('produk', 'public');
        }

        $produk->update($data);

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil diperbarui!');
    }

    /**
     * Hapus produk
     */
    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);
        $produk->delete();

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil dihapus!');
    }
}
