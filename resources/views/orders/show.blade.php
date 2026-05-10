@extends('layouts.app')
@section('title', 'Chi tiết đơn hàng #{{ $order->id }}')
@section('content')
@php
    $statusMap = [
        0 => ['label' => 'Chờ xác nhận',  'bg' => 'rgba(255, 255, 255, 0.1)', 'color' => 'white'],
        1 => ['label' => 'Đang xử lý',    'bg' => 'rgba(64, 156, 255, 0.15)', 'color' => '#66b2ff'],
        2 => ['label' => 'Đang giao',    'bg' => 'rgba(255, 171, 64, 0.15)', 'color' => '#ffb74d'],
        3 => ['label' => 'Đã giao',      'bg' => 'rgba(102, 204, 153, 0.15)', 'color' => '#81c784'],
    ];
    $s = $statusMap[$order->status] ?? ['label' => 'Không rõ', 'bg' => 'rgba(255,255,255,0.1)', 'color' => 'white'];

    $total = $order->orderItems->sum(fn($item) => $item->amount * $item->product->price);
@endphp

<div style="padding: 0 40px; margin-top: 40px;">
    <div class="animate-fade-up" style="display:flex; align-items:center; justify-content:space-between; margin-bottom:40px; flex-wrap:wrap; gap:12px;">
        <h1 class="display-lg" style="font-size:2rem; margin: 0;">Chi tiết đơn hàng #{{ $order->id }}</h1>
        <span style="display:inline-block; padding:8px 24px; border-radius:999px; font-size:1rem; font-weight:600; background:{{ $s['bg'] }}; color:{{ $s['color'] }}; border: 1px solid {{ str_replace('0.15', '0.3', $s['bg']) }};">
            {{ $s['label'] }}
        </span>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 40px; align-items: start;">
        {{-- Order Info --}}
        <div class="glass-panel animate-fade-up delay-1" style="background: var(--surface-container-lowest); position: sticky; top: 120px;">
            <h2 class="headline-sm" style="margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid rgba(255,255,255,0.1);">Thông tin giao hàng</h2>
            <div style="display:flex; flex-direction:column; gap:20px;">
                <div>
                    <p class="label-md" style="color:var(--on-surface-variant); margin-bottom:4px;">Tên đơn hàng</p>
                    <p class="body-lg" style="font-weight:600;">{{ $order->name }}</p>
                </div>
                <div>
                    <p class="label-md" style="color:var(--on-surface-variant); margin-bottom:4px;">Người nhận</p>
                    <p class="body-lg" style="font-weight:600;">{{ $order->receiver }}</p>
                </div>
                <div>
                    <p class="label-md" style="color:var(--on-surface-variant); margin-bottom:4px;">Thông tin chi tiết</p>
                    <p class="body-md" style="line-height:1.6; white-space: pre-wrap;">{{ $order->desc }}</p>
                </div>
                <div>
                    <p class="label-md" style="color:var(--on-surface-variant); margin-bottom:4px;">Ngày đặt</p>
                    <p class="body-md">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
            
            <a href="/orders" class="btn btn-secondary" style="margin-top: 32px; display: block; text-align: center;">
                Quay lại danh sách
            </a>
        </div>

        {{-- Order Items --}}
        <div class="glass-panel animate-fade-up delay-2" style="padding: 0; overflow: hidden;">
            <div style="padding: 24px 32px; border-bottom: 1px solid rgba(255,255,255,0.05); background: rgba(255,255,255,0.02);">
                <h2 class="headline-sm" style="margin: 0;">Sản phẩm đã đặt</h2>
            </div>
            
            <div style="padding: 32px;">
                <div style="display: flex; flex-direction: column; gap: 24px;">
                    @foreach($order->orderItems as $item)
                        <div style="display: flex; align-items: center; gap: 24px; border-bottom: 1px solid rgba(255,255,255,0.03); padding-bottom: 24px;">
                            <div style="width: 80px; height: 80px; background: white; border-radius: 12px; display: flex; justify-content: center; align-items: center; padding: 10px;">
                                @if($item->product->img)
                                    <img src="{{ asset('storage/' . $item->product->img) }}" alt="{{ $item->product->name }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                @else
                                    <div style="color: var(--surface-container-highest);">N/A</div>
                                @endif
                            </div>
                            <div style="flex: 1;">
                                <div class="body-lg" style="font-weight: 600; color: white; margin-bottom: 8px;">{{ $item->product->name }}</div>
                                <div class="label-md" style="color: var(--on-surface-variant);">
                                    {{ number_format($item->product->price, 0, ',', '.') }} ₫ &times; {{ $item->amount }}
                                </div>
                            </div>
                            <div class="headline-sm" style="font-weight: 700; color: var(--primary);">
                                {{ number_format($item->amount * $item->product->price, 0, ',', '.') }} ₫
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div style="padding: 32px; border-top: 1px solid rgba(255,255,255,0.05); background: rgba(255,255,255,0.01); display: flex; justify-content: space-between; align-items: center;">
                <span class="headline-sm" style="color: var(--on-surface-variant);">Tổng thanh toán</span>
                <span class="display-lg" style="font-size: 2.5rem; color: var(--primary);">
                    {{ number_format($total, 0, ',', '.') }} ₫
                </span>
            </div>
        </div>
    </div>
</div>
@endsection
