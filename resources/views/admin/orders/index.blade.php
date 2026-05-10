@extends('layouts.admin')

@section('title', 'Quản lý đơn hàng')

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
    $currentStatus = request('status');
@endphp

<div class="page-header">
    <h1>Quản lý đơn hàng</h1>
</div>

{{-- Status filter --}}
<div style="margin-bottom:20px;display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
    <span style="font-size:13px;color:#a2a3b7;">Lọc theo trạng thái:</span>
    <form method="GET" action="/admin/orders" style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;">
        <select name="status"
                onchange="this.form.submit()"
                style="background:#252535;color:#fff;border:1px solid #2d2d3f;padding:8px 12px;border-radius:6px;font-size:13px;outline:none;cursor:pointer;">
            <option value="">Tất cả</option>
            @foreach($statusLabels as $val => $label)
                <option value="{{ $val }}" {{ $currentStatus !== null && $currentStatus !== '' && (int)$currentStatus === $val ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </form>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Khách hàng</th>
                <th>Người nhận</th>
                <th>Trạng thái</th>
                <th>Ngày tạo</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            @php
                $badge = $badgeColors[$order->status] ?? $badgeColors[0];
                $label = $statusLabels[$order->status] ?? 'Không rõ';
            @endphp
            <tr>
                <td style="color:#fff;font-weight:600;">#{{ $order->id }}</td>
                <td>{{ $order->name ?? ($order->user->name ?? '—') }}</td>
                <td>{{ $order->receiver ?? '—' }}</td>
                <td>
                    <span style="background:{{ $badge['bg'] }};color:{{ $badge['color'] }};border:1px solid {{ $badge['border'] }};padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;white-space:nowrap;">
                        {{ $label }}
                    </span>
                </td>
                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    <a href="/admin/orders/{{ $order->id }}"
                       style="background:#f57224;color:#fff;padding:6px 14px;border-radius:4px;font-size:13px;font-weight:500;text-decoration:none;display:inline-block;">
                        Xem chi tiết
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;padding:32px;color:#555570;">Chưa có đơn hàng nào.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($orders->hasPages())
<div style="margin-top:16px;">
    {{ $orders->appends(request()->query())->links() }}
</div>
@endif
@endsection
