@extends('layouts.admin')

@section('title', 'Người dùng: ' . $user->name)

@section('content')
@php
    $isAdmin = $user->role == 1;
    $roleBg    = $isAdmin ? '#ff980022' : '#9e9e9e22';
    $roleColor = $isAdmin ? '#ff9800'   : '#9e9e9e';
    $roleBorder= $isAdmin ? '#ff980055' : '#9e9e9e55';
    $roleLabel = $isAdmin ? 'Admin'     : 'Khách hàng';

    $orderStatusLabels = [
        0 => 'Chờ xử lý',
        1 => 'Đang xử lý',
        2 => 'Đã giao',
        3 => 'Đã hủy',
    ];
    $orderBadgeColors = [
        0 => ['bg' => '#9e9e9e22', 'color' => '#9e9e9e', 'border' => '#9e9e9e55'],
        1 => ['bg' => '#1976d222', 'color' => '#1976d2', 'border' => '#1976d255'],
        2 => ['bg' => '#388e3c22', 'color' => '#388e3c', 'border' => '#388e3c55'],
        3 => ['bg' => '#e3183722', 'color' => '#e31837', 'border' => '#e3183755'],
    ];
@endphp

<div class="page-header">
    <h1>{{ $user->name }}</h1>
    <a href="/admin/users" style="color:#a2a3b7;font-size:14px;text-decoration:none;">← Quay lại danh sách</a>
</div>

{{-- User info card --}}
<div class="card">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
        <div>
            <div style="font-size:11px;color:#555570;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:4px;">Tên</div>
            <div style="color:#fff;font-weight:600;">{{ $user->name }}</div>
        </div>
        <div>
            <div style="font-size:11px;color:#555570;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:4px;">Username</div>
            <div style="color:#a2a3b7;">{{ $user->username }}</div>
        </div>
        <div>
            <div style="font-size:11px;color:#555570;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:4px;">Quyền</div>
            <span style="background:{{ $roleBg }};color:{{ $roleColor }};border:1px solid {{ $roleBorder }};padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;">
                {{ $roleLabel }}
            </span>
        </div>
        <div>
            <div style="font-size:11px;color:#555570;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:4px;">Ngày tạo</div>
            <div style="color:#a2a3b7;">{{ $user->created_at->format('d/m/Y H:i') }}</div>
        </div>
    </div>
</div>

{{-- Update role form --}}
<div class="card">
    <div style="font-size:15px;font-weight:600;color:#fff;margin-bottom:16px;">Cập nhật quyền</div>
    <form action="/admin/users/{{ $user->id }}/role" method="POST" style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
        @csrf
        @method('PUT')
        <select name="role"
                style="background:#1e1e2d;color:#fff;border:1px solid #2d2d3f;padding:9px 12px;border-radius:6px;font-size:14px;outline:none;min-width:180px;">
            <option value="0" {{ $user->role == 0 ? 'selected' : '' }}>Khách hàng</option>
            <option value="1" {{ $user->role == 1 ? 'selected' : '' }}>Admin</option>
        </select>
        <button type="submit" style="background:#f57224;color:#fff;border:none;padding:8px 16px;border-radius:4px;font-size:14px;font-weight:600;cursor:pointer;">
            Cập nhật
        </button>
    </form>
</div>

{{-- Orders history --}}
<div class="card">
    <div style="font-size:15px;font-weight:600;color:#fff;margin-bottom:16px;">Lịch sử đơn hàng ({{ $user->orders->count() }})</div>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên đơn</th>
                <th>Người nhận</th>
                <th>Trạng thái</th>
                <th>Ngày tạo</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($user->orders as $order)
            @php
                $obadge = $orderBadgeColors[$order->status] ?? $orderBadgeColors[0];
                $olabel = $orderStatusLabels[$order->status] ?? 'Không rõ';
            @endphp
            <tr>
                <td style="color:#fff;font-weight:600;">#{{ $order->id }}</td>
                <td style="color:#fff;">{{ $order->name ?? '—' }}</td>
                <td style="color:#a2a3b7;">{{ $order->receiver ?? '—' }}</td>
                <td>
                    <span style="background:{{ $obadge['bg'] }};color:{{ $obadge['color'] }};border:1px solid {{ $obadge['border'] }};padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;white-space:nowrap;">
                        {{ $olabel }}
                    </span>
                </td>
                <td style="color:#a2a3b7;">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    <a href="/admin/orders/{{ $order->id }}"
                       style="background:rgba(245,114,36,0.15);color:#f57224;border:1px solid rgba(245,114,36,0.3);padding:6px 14px;border-radius:4px;font-size:13px;font-weight:500;text-decoration:none;display:inline-block;">
                        Xem chi tiết
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;padding:24px;color:#555570;">Người dùng chưa có đơn hàng nào.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
