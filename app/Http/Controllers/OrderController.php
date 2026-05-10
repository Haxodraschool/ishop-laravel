<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('users_id', auth()->id())
            ->latest()
            ->get();

        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect('/cart')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        return view('orders.create', compact('cart'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'required|string|max:20',
            'email'   => 'required|email|max:255',
            'address' => 'required|string|max:1000',
            'note'    => 'nullable|string|max:1000',
        ]);

        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect('/cart')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        $order = DB::transaction(function () use ($request, $cart) {
            $descText = "SĐT: {$request->phone} - Email: {$request->email}\nĐịa chỉ: {$request->address}\nGhi chú: {$request->note}\n\nChi tiết Đơn hàng:\n";
            foreach ($cart as $item) {
                $variantInfo = '';
                if (!empty($item['storage']) || !empty($item['color'])) {
                    $storageStr = $item['storage'] ?? '';
                    $colorStr = $item['color'] ?? '';
                    $variantInfo = " (" . trim("$storageStr $colorStr") . ")";
                }
                $priceStr = number_format($item['price'], 0, ',', '.');
                $descText .= "- {$item['name']}{$variantInfo} x {$item['quantity']} = {$priceStr}₫/sp\n";
            }

            $order = Order::create([
                'name'     => 'Đơn hàng #' . time(),
                'desc'     => $descText,
                'users_id' => auth()->id(),
                'status'   => 0,
                'receiver' => $request->input('name'),
            ]);

            foreach ($cart as $cartItemId => $item) {
                OrderItem::create([
                    'orders_id'   => $order->id,
                    'products_id' => $item['product_id'] ?? $cartItemId,
                    'amount'      => $item['quantity'],
                ]);
            }

            return $order;
        });

        session()->forget('cart');

        return redirect('/orders/' . $order->id)->with('success', 'Đặt hàng thành công!');
    }

    public function show($id)
    {
        $order = Order::with('orderItems.product')->findOrFail($id);

        if ($order->users_id !== auth()->id()) {
            abort(403);
        }

        return view('orders.show', compact('order'));
    }
}
