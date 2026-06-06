<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Menampilkan profil pelanggan
     */
    public function index()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    /**
     * Memperbarui profil pelanggan
     */
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'nama' => 'required|string|max:255',
            'no_hp' => 'required|string|unique:users,no_hp,' . $user->id_user . ',id_user',
            'alamat' => 'required|string',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = [
            'nama' => $request->nama,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ];

        // Handle Foto Upload
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada (cek di folder public)
            if ($user->foto && file_exists(public_path($user->foto))) {
                unlink(public_path($user->foto));
            }
            
            $file = $request->file('foto');
            $filename = time() . '_' . $user->id_user . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('avatars'), $filename);
            $data['foto'] = 'avatars/' . $filename;
        }

        if ($request->filled('password')) {
            $data['password'] = $request->password; // Otomatis hash oleh cast model
        }

        // Gunakan User::find untuk memastikan instansi Eloquent segar
        \App\Models\User::where('id_user', $user->id_user)->update($data);

        return back()->with('success', 'Profil Anda berhasil diperbarui!');
    }
}
