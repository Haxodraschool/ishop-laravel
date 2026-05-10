<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iShop Admin - @yield('title', 'Quản trị')</title>
    <style>
        :root {
            --admin-bg: #1e1e2d;
            --admin-sidebar: #15151e;
            --admin-accent: #f57224;
            --admin-text: #a2a3b7;
            --admin-text-active: #ffffff;
            --admin-topbar: #1e1e2d;
            --admin-border: #2d2d3f;
            --admin-card: #252535;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: var(--admin-bg);
            color: var(--admin-text);
            min-height: 100vh;
        }

        /* ── SIDEBAR ── */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 240px;
            height: 100vh;
            background: var(--admin-sidebar);
            display: flex;
            flex-direction: column;
            z-index: 100;
            border-right: 1px solid var(--admin-border);
        }

        .sidebar-logo {
            padding: 20px 24px;
            border-bottom: 1px solid var(--admin-border);
        }

        .sidebar-logo a {
            font-size: 22px;
            font-weight: 900;
            color: var(--admin-accent);
            text-decoration: none;
            letter-spacing: -0.5px;
        }

        .sidebar-logo span {
            font-size: 11px;
            color: var(--admin-text);
            display: block;
            font-weight: 400;
            margin-top: 2px;
        }

        .sidebar-nav {
            flex: 1;
            padding: 16px 0;
            overflow-y: auto;
        }

        .nav-label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #555570;
            padding: 8px 24px 4px;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 24px;
            color: var(--admin-text);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.15s;
            border-left: 3px solid transparent;
        }

        .sidebar-nav a:hover {
            background: rgba(255,255,255,0.05);
            color: var(--admin-text-active);
        }

        .sidebar-nav a.active {
            background: rgba(245, 114, 36, 0.12);
            color: var(--admin-accent);
            border-left-color: var(--admin-accent);
        }

        .nav-icon { font-size: 16px; width: 20px; text-align: center; }

        /* ── TOPBAR ── */
        .topbar {
            position: fixed;
            top: 0;
            left: 240px;
            right: 0;
            height: 60px;
            background: var(--admin-topbar);
            border-bottom: 1px solid var(--admin-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            z-index: 99;
        }

        .topbar-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--admin-text-active);
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .admin-name {
            font-size: 14px;
            color: var(--admin-text);
        }

        .admin-name strong {
            color: var(--admin-text-active);
        }

        .btn-logout {
            background: rgba(245, 114, 36, 0.15);
            border: 1px solid rgba(245, 114, 36, 0.3);
            color: var(--admin-accent);
            font-size: 13px;
            padding: 7px 16px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s;
            font-weight: 500;
        }

        .btn-logout:hover {
            background: var(--admin-accent);
            color: #fff;
        }

        /* ── MAIN CONTENT ── */
        .main-content {
            margin-left: 240px;
            padding-top: 60px;
            min-height: 100vh;
        }

        .content-inner {
            padding: 28px;
        }

        /* ── FLASH MESSAGES ── */
        .flash-success {
            background: rgba(46, 125, 50, 0.15);
            color: #81c784;
            border: 1px solid rgba(46, 125, 50, 0.3);
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .flash-error {
            background: rgba(198, 40, 40, 0.15);
            color: #ef9a9a;
            border: 1px solid rgba(198, 40, 40, 0.3);
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        /* ── COMMON ADMIN COMPONENTS ── */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .page-header h1 {
            font-size: 22px;
            font-weight: 700;
            color: var(--admin-text-active);
        }

        .btn-primary {
            background: var(--admin-accent);
            color: #fff;
            border: none;
            padding: 9px 20px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: background 0.2s;
        }

        .btn-primary:hover { background: #e05e10; }

        .card {
            background: var(--admin-card);
            border: 1px solid var(--admin-border);
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            padding: 12px 16px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #555570;
            border-bottom: 1px solid var(--admin-border);
        }

        td {
            padding: 12px 16px;
            font-size: 14px;
            border-bottom: 1px solid var(--admin-border);
            color: var(--admin-text);
        }

        tr:last-child td { border-bottom: none; }

        tr:hover td { background: rgba(255,255,255,0.02); }
    </style>
    @stack('styles')
</head>
<body>

<!-- Sidebar -->
<aside class="sidebar">
    <div class="sidebar-logo">
        <a href="/admin/dashboard">iShop</a>
        <span>Bảng điều khiển</span>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-label">Tổng quan</div>
        <a href="/admin/dashboard" class="{{ request()->is('admin/dashboard') ? 'active' : '' }}">
            <span class="nav-icon">📊</span> Dashboard
        </a>

        <div class="nav-label">Quản lý</div>
        <a href="/admin/products" class="{{ request()->is('admin/products*') ? 'active' : '' }}">
            <span class="nav-icon">📦</span> Sản phẩm
        </a>
        <a href="/admin/categories" class="{{ request()->is('admin/categories*') ? 'active' : '' }}">
            <span class="nav-icon">🏷️</span> Danh mục
        </a>
        <a href="/admin/orders" class="{{ request()->is('admin/orders*') ? 'active' : '' }}">
            <span class="nav-icon">🛍️</span> Đơn hàng
        </a>
        <a href="/admin/users" class="{{ request()->is('admin/users*') ? 'active' : '' }}">
            <span class="nav-icon">👥</span> Người dùng
        </a>
    </nav>
</aside>

<!-- Topbar -->
<div class="topbar">
    <div class="topbar-title">@yield('title', 'Dashboard')</div>
    <div class="topbar-right">
        <span class="admin-name">Xin chào, <strong>{{ auth()->user()->name }}</strong></span>
        <form action="/logout" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="btn-logout">Đăng xuất</button>
        </form>
    </div>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="content-inner">
        @if(session('success'))
            <div class="flash-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="flash-error">{{ session('error') }}</div>
        @endif

        @yield('content')
    </div>
</div>

@stack('scripts')
</body>
</html>
