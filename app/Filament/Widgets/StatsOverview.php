<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Filament\Support\Colors\Color;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Tính tổng doanh thu
        $totalRevenue = \App\Models\OrderItem::join('products', 'order_items.products_id', '=', 'products.id')
            ->sum(\Illuminate\Support\Facades\DB::raw('order_items.amount * products.price'));

        return [
            Stat::make('Tổng sản phẩm', Product::count())
                ->description('Sản phẩm trong kho')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('primary'),
            Stat::make('Đơn hàng mới', Order::where('status', 0)->count())
                ->description('Cần xác nhận')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
            Stat::make('Khách hàng', User::where('role', 0)->count())
                ->description('Thành viên đăng ký')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),
            Stat::make('Tổng doanh thu', number_format($totalRevenue, 0, ',', '.') . ' ₫')
                ->description('Tổng giá trị đơn hàng')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('info'),
        ];
    }
}
