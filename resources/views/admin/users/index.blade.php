@extends('layouts.admin')

@section('title', 'Quản lý người dùng')

@section('content')
<div class="page-header">
    <h1>Quản lý người dùng</h1>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th>Username</th>
                <th>Quyền</th>
                <th>Số đơn hàng</th>
                <th>Ngày tạo</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            @php
                $isAdmin = $user->role == 1;
                $roleBg    = $isAdmin ? '#ff980022' : '#9e9e9e22';
                $roleColor = $isAdmin ? '#ff9800'   : '#9e9e9e';
                $roleBorder= $isAdmin ? '#ff980055' : '#9e9e9e55';
                $roleLabel = $isAdmin ? 'Admin'     : 'Khách hàng';
            @endphp
            <tr>
                <td style="color:#fff;font-weight:600;">{{ $user->id }}</td>
                <td style="color:#fff;">{{ $user->name }}</td>
                <td style="color:#a2a3b7;">{{ $user->username }}</td>
                <td>
                    <span style="background:{{ $roleBg }};color:{{ $roleColor }};border:1px solid {{ $roleBorder }};padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;white-space:nowrap;">
                        {{ $roleLabel }}
                    </span>
                </td>
                <td style="color:#a2a3b7;">{{ $user->orders_count }}</td>
                <td style="color:#a2a3b7;">{{ $user->created_at->format('d/m/Y') }}</td>
                <td>
                    <a href="/admin/users/{{ $user->id }}"
                       style="background:#f57224;color:#fff;padding:6px 14px;border-radius:4px;font-size:13px;font-weight:500;text-decoration:none;display:inline-block;">
                        Xem chi tiết
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center;padding:32px;color:#555570;">Chưa có người dùng nào.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($users->hasPages())
<div style="margin-top:16px;">
    {{ $users->links() }}
</div>
@endif
@endsection
