@extends('layouts.app')
@section('title', 'Đơn hàng của tôi')
@section('content')
<div style="padding: 0 40px; margin-top: 40px;">
    <div class="animate-fade-up" style="margin-bottom: 60px; text-align: center;">
        <h1 class="display-lg" style="margin-bottom: 16px;">Đơn Hàng Của Bạn</h1>
        <p class="body-lg" style="max-width: 600px; margin: 0 auto; color: var(--on-surface-variant);">Lịch sử mua sắm các tuyệt tác công nghệ tại Obsidian Gallery.</p>
    </div>

    @if($orders->isEmpty())
        <div class="glass-panel animate-fade-up delay-1" style="text-align:center; padding:80px 24px;">
            <div style="font-size: 3rem; color: var(--on-surface-variant); margin-bottom: 24px;"><i class="ri-shopping-bag-3-line"></i></div>
            <p style="font-size:1.2rem; color:var(--on-surface-variant); margin-bottom:32px;">Bạn chưa có đơn hàng nào.</p>
            <a href="/products" class="btn btn-primary" style="padding:12px 32px; border-radius:12px; text-decoration:none; font-weight:700;">Khám Phá Bộ Sưu Tập</a>
        </div>
    @else
        <div class="glass-panel animate-fade-up delay-1" style="overflow-x: auto; padding: 0;">
            <table style="width: 100%; border-collapse: collapse; min-width: 800px;">
                <thead>
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.05); background: rgba(255,255,255,0.02);">
                        <th style="padding: 24px; text-align: left; color: var(--on-surface-variant); font-weight: 600; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.05em;">Mã đơn</th>
                        <th style="padding: 24px; text-align: left; color: var(--on-surface-variant); font-weight: 600; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.05em;">Tên đơn hàng</th>
                        <th style="padding: 24px; text-align: left; color: var(--on-surface-variant); font-weight: 600; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.05em;">Người nhận</th>
                        <th style="padding: 24px; text-align: left; color: var(--on-surface-variant); font-weight: 600; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.05em;">Ngày đặt</th>
                        <th style="padding: 24px; text-align: center; color: var(--on-surface-variant); font-weight: 600; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.05em;">Trạng thái</th>
                        <th style="padding: 24px; text-align: center; color: var(--on-surface-variant); font-weight: 600; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.05em;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        @php
                            $statusMap = [
                                0 => ['label' => 'Chờ xác nhận', 'bg' => 'rgba(255, 255, 255, 0.1)', 'color' => 'white'],
                                1 => ['label' => 'Đang xử lý',   'bg' => 'rgba(64, 156, 255, 0.15)', 'color' => '#66b2ff'],
                                2 => ['label' => 'Đang giao',    'bg' => 'rgba(255, 171, 64, 0.15)', 'color' => '#ffb74d'],
                                3 => ['label' => 'Đã giao',      'bg' => 'rgba(102, 204, 153, 0.15)', 'color' => '#81c784'],
                            ];
                            $s = $statusMap[$order->status] ?? ['label' => 'Không rõ', 'bg' => 'rgba(255,255,255,0.1)', 'color' => 'white'];
                        @endphp
                        <tr class="hover-glow" style="border-bottom: 1px solid rgba(255,255,255,0.03); transition: background 0.3s;">
                            <td style="padding: 24px; font-weight: 600; color: var(--primary);">#{{ $order->id }}</td>
                            <td style="padding: 24px; color: var(--on-surface);">{{ $order->name }}</td>
                            <td style="padding: 24px; color: var(--on-surface);">{{ $order->receiver }}</td>
                            <td style="padding: 24px; color: var(--on-surface-variant);">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td style="padding: 24px; text-align: center;">
                                <span style="display: inline-block; padding: 6px 16px; border-radius: 999px; font-size: 0.85rem; font-weight: 600; background: {{ $s['bg'] }}; color: {{ $s['color'] }}; border: 1px solid {{ str_replace('0.15', '0.3', $s['bg']) }};">
                                    {{ $s['label'] }}
                                </span>
                            </td>
                            <td style="padding: 24px; text-align: center;">
                                <a href="/orders/{{ $order->id }}" class="btn btn-secondary" style="padding: 8px 24px; border-radius: 8px; font-size: 0.9rem; text-decoration: none;">Chi tiết</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
