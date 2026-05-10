<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Services\CartService;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index(Request $request)
    {
        $cart = session('cart', []);
        $total = $this->cartService->total($cart);
        return view('cart.index', compact('cart', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty'        => 'nullable|integer|min:1',
            'storage'    => 'nullable|string',
            'color'      => 'nullable|string',
            'price'      => 'nullable|numeric',
        ]);

        $product = Product::findOrFail($request->product_id);
        
        $finalPrice = $request->price ?? $product->price;

        $cart = session('cart', []);
        
        $cartItemId = $product->id;
        if ($request->storage || $request->color) {
            $cartItemId = $product->id . '_' . ($request->storage ?? 'default') . '_' . ($request->color ?? 'default');
        }

        $cart = $this->cartService->add(
            $cart,
            $cartItemId,
            (int) ($request->qty ?? 1),
            [
                'product_id' => $product->id,
                'name' => $product->name, 
                'price' => $finalPrice, 
                'img' => $product->img,
                'storage' => $request->storage,
                'color' => $request->color,
            ]
        );
        session(['cart' => $cart]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Đã thêm sản phẩm vào giỏ hàng!']);
        }

        return redirect()->back()->with('success', 'Đã thêm sản phẩm vào giỏ hàng!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'cart_item_id' => 'required',
            'quantity'   => 'required|integer|min:1',
        ]);

        $cart = session('cart', []);
        $cart = $this->cartService->update($cart, $request->cart_item_id, (int) $request->quantity);
        session(['cart' => $cart]);

        return redirect()->back()->with('success', 'Đã cập nhật giỏ hàng!');
    }

    public function remove(Request $request)
    {
        $request->validate([
            'cart_item_id' => 'required',
        ]);

        $cart = session('cart', []);
        $cart = $this->cartService->remove($cart, $request->cart_item_id);
        session(['cart' => $cart]);

        return redirect()->back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng!');
    }
}
