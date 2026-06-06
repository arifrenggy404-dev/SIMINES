<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlist = Wishlist::with('produk')->where('id_user', Auth::id())->get();
        return view('wishlist.index', compact('wishlist'));
    }

    public function toggle(Request $request)
    {
        $id_produk = $request->id_produk;
        $id_user = Auth::id();

        $exists = Wishlist::where('id_user', $id_user)->where('id_produk', $id_produk)->first();

        if ($exists) {
            $exists->delete();
            return response()->json(['status' => 'removed', 'message' => 'Dihapus dari wishlist']);
        } else {
            Wishlist::create(['id_user' => $id_user, 'id_produk' => $id_produk]);
            return response()->json(['status' => 'added', 'message' => 'Ditambahkan ke wishlist']);
        }
    }
}
