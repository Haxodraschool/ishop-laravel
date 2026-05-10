@extends('layouts.admin')

@section('title', 'Đơn hàng #' . $order->id)

@section('content')
@php
    $statusLabels = [
        0 => 'Chờ xử lý',
        1 => 'Đang xử lý',
        2 => 'Đã giao',
        3 => 'Đã hủy',
    ];
    $badgeColors = [
        0 => ['bg' => '#9e9e9e22', 'color' => '#9e9e9e', 'border' => '#9e9e9e55'],
        1 => ['bg' => '#1976d222', 'color' => '#1976d2', 'border' => '#1976d255'],
        2 => ['bg' => '#388e3c22', 'color' => '#388e3c', 'border' => '#388e3c55'],
        3 => ['bg' => '#e3183722', 'color' => '#e31837', 'border' => '#e3183755'],
    ];
    $badge = $badgeColors[$order->status] ?? $badgeColors[0];
    $statusLabel = $statusLabels[$order->status] ?? 'Không rõ';
@endphp

<div class="page-header">
    <div style="display:flex;align-items:center;gap:12px;">
        <h1>Đơn hàng #{{ $order->id }}</h1>
        <span style="background:{{ $badge['bg'] }};color:{{ $badge['color'] }};border:1px solid {{ $badge['border'] }};padding:4px 12px;border-radius:20px;font-size:13px;font-weight:600;">
            {{ $statusLabel }}
        </span>
    </div>
    <a href="/admin/orders" style="color:#a2a3b7;font-size:14px;text-decoration:none;">← Quay lại danh sách</a>
</div>

{{-- Order info --}}
<div class="card">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
        <div>
            <div style="font-size:11px;color:#555570;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:4px;">ID đơn hàng</div>
            <div style="color:#fff;font-weight:600;">#{{ $order->id }}</div>
        </div>
        <div>
            <div style="font-size:11px;color:#555570;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:4px;">Khách hàng</div>
            <div style="color:#fff;font-weight:500;">{{ $order->name ?? ($order->user->name ?? '—') }}</div>
        </div>
        <div>
            <div style="font-size:11px;color:#555570;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:4px;">Người nhận</div>
            <div style="color:#fff;font-weight:500;">{{ $order->receiver ?? '—' }}</div>
        </div>
        <div>
            <div style="font-size:11px;color:#555570;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:4px;">Địa chỉ / Mô tả</div>
            <div style="color:#a2a3b7;">{{ $order->desc ?? '—' }}</div>
        </div>
        <div>
            <div style="font-size:11px;color:#555570;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:4px;">Trạng thái</div>
            <span style="background:{{ $badge['bg'] }};color:{{ $badge['color'] }};border:1px solid {{ $badge['border'] }};padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;">
                {{ $statusLabel }}
            </span>
        </div>
        <div>
            <div style="font-size:11px;color:#555570;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:4px;">Ngày tạo</div>
            <div style="color:#a2a3b7;">{{ $order->created_at->format('d/m/Y H:i') }}</div>
        </div>
    </div>
</div>

{{-- Order items --}}
<div class="card">
    <div style="font-size:15px;font-weight:600;color:#fff;margin-bottom:16px;">Sản phẩm trong đơn</div>
    <table>
        <thead>
            <tr>
                <th>Tên sản phẩm</th>
                <th>Số lượng</th>
                <th>Đơn giá</th>
                <th style="text-align:right;">Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach($order->orderItems as $item)
            @php
                $price = $item->product->price ?? 0;
                $subtotal = $price * $item->amount;
                $total += $subtotal;
            @endphp
            <tr>
                <td style="color:#fff;">{{ $item->product->name ?? 'Sản phẩm đã xóa' }}</td>
                <td>{{ $item->amount }}</td>
                <td>{{ number_format($price, 0, ',', '.') }}₫</td>
                <td style="text-align:right;color:#fff;font-weight:500;">{{ number_format($subtotal, 0, ',', '.') }}₫</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align:right;font-weight:700;color:#fff;border-top:1px solid #2d2d3f;padding-top:14px;">Tổng cộng:</td>
                <td style="text-align:right;font-weight:700;color:#f57224;font-size:16px;border-top:1px solid #2d2d3f;padding-top:14px;">{{ number_format($total, 0, ',', '.') }}₫</td>
            </tr>
        </tfoot>
    </table>
</div>

{{-- Update status form --}}
<div class="card">
    <div style="font-size:15px;font-weight:600;color:#fff;margin-bottom:16px;">Cập nhật trạng thái</div>
    <form action="/admin/orders/{{ $order->id }}/status" method="POST" style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
        @csrf
        @method('PUT')
        <select name="status"
                style="background:#1e1e2d;color:#fff;border:1px solid #2d2d3f;padding:9px 12px;border-radius:6px;font-size:14px;outline:none;min-width:200px;">
            <option value="0" {{ $order->status == 0 ? 'selected' : '' }}>Chờ xử lý</option>
            <option value="1" {{ $order->status == 1 ? 'selected' : '' }}>Đang xử lý</option>
            <option value="2" {{ $order->status == 2 ? 'selected' : '' }}>Đã giao</option>
            <option value="3" {{ $order->status == 3 ? 'selected' : '' }}>Đã hủy</option>
        </select>
        <button type="submit" style="background:#f57224;color:#fff;border:none;padding:8px 16px;border-radius:4px;font-size:14px;font-weight:600;cursor:pointer;">
            Cập nhật
        </button>
    </form>
</div>
@endsection
