<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Wishlist::with('product')
            ->where('users_id', auth()->id())
            ->get();

        return view('wishlist.index', compact('wishlists'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        Wishlist::firstOrCreate([
            'users_id'    => auth()->id(),
            'products_id' => $request->product_id,
        ]);

        return redirect()->back()->with('success', 'Đã thêm vào danh sách yêu thích');
    }

    public function remove(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        Wishlist::where('users_id', auth()->id())
            ->where('products_id', $request->product_id)
            ->delete();

        return redirect()->back()->with('success', 'Đã xóa khỏi danh sách yêu thích');
    }
}
