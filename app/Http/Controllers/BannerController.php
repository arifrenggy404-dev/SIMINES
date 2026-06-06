<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('urutan', 'asc')->get();
        return view('admin.banner.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banner.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'judul' => 'nullable|string|max:255',
            'subjudul' => 'nullable|string|max:255',
            'urutan' => 'nullable|integer'
        ]);

        $file = $request->file('gambar');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('banners'), $filename);

        Banner::create([
            'gambar' => 'banners/' . $filename,
            'judul' => $request->judul,
            'subjudul' => $request->subjudul,
            'urutan' => $request->urutan ?? 0,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('admin.banner.index')->with('success', 'Banner berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $banner = Banner::findOrFail($id);
        return view('admin.banner.edit', compact('banner'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'judul' => 'nullable|string|max:255',
            'subjudul' => 'nullable|string|max:255',
            'urutan' => 'nullable|integer'
        ]);

        $banner = Banner::findOrFail($id);
        $data = [
            'judul' => $request->judul,
            'subjudul' => $request->subjudul,
            'urutan' => $request->urutan ?? 0,
            'is_active' => $request->has('is_active')
        ];

        if ($request->hasFile('gambar')) {
            // Hapus file lama
            if (File::exists(public_path($banner->gambar))) {
                File::delete(public_path($banner->gambar));
            }
            
            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('banners'), $filename);
            $data['gambar'] = 'banners/' . $filename;
        }

        $banner->update($data);

        return redirect()->route('admin.banner.index')->with('success', 'Banner berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);
        if (File::exists(public_path($banner->gambar))) {
            File::delete(public_path($banner->gambar));
        }
        $banner->delete();

        return redirect()->route('admin.banner.index')->with('success', 'Banner berhasil dihapus!');
    }
}
