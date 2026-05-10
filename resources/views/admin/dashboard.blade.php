@extends('layouts.admin')

@section('title', 'Dashboard')

@push('styles')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 28px;
    }

    .stat-card {
        background: #ffffff;
        border-radius: 10px;
        padding: 24px 20px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        flex-shrink: 0;
    }

    .stat-icon.orange { background: rgba(245, 114, 36, 0.12); }
    .stat-icon.blue   { background: rgba(33, 150, 243, 0.12); }
    .stat-icon.green  { background: rgba(76, 175, 80, 0.12); }
    .stat-icon.purple { background: rgba(156, 39, 176, 0.12); }

    .stat-info {
        flex: 1;
        min-width: 0;
    }

    .stat-label {
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #9e9e9e;
        margin-bottom: 6px;
    }

    .stat-value {
        font-size: 22px;
        font-weight: 800;
        color: #1a1a2e;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    @media (max-width: 1100px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 600px) {
        .stats-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1>Dashboard</h1>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon orange">🛍️</div>
        <div class="stat-info">
            <div class="stat-label">Tổng đơn hàng</div>
            <div class="stat-value">{{ number_format($totalOrders) }}</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon green">💰</div>
        <div class="stat-info">
            <div class="stat-label">Doanh thu</div>
            <div class="stat-value">{{ number_format($totalRevenue, 0, ',', '.') }} ₫</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon blue">📦</div>
        <div class="stat-info">
            <div class="stat-label">Sản phẩm</div>
            <div class="stat-value">{{ number_format($totalProducts) }}</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon purple">👥</div>
        <div class="stat-info">
            <div class="stat-label">Người dùng</div>
            <div class="stat-value">{{ number_format($totalUsers) }}</div>
        </div>
    </div>
</div>
@endsection
