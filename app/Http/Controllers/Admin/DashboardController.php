<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrders = Order::count();

        $totalRevenue = \App\Models\OrderItem::join('products', 'order_items.products_id', '=', 'products.id')
            ->selectRaw('SUM(products.price * order_items.amount) as total')
            ->value('total') ?? 0;

        $totalProducts = Product::count();
        $totalUsers = User::count();

        return view('admin.dashboard', compact(
            'totalOrders',
            'totalRevenue',
            'totalProducts',
            'totalUsers'
        ));
    }
}
