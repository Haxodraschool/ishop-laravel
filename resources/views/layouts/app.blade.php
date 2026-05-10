<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iShop - @yield('title', 'Phòng Trưng Bày Obsidian')</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'><path d='M 20 4 L 20 20 L 4 20 L 4 8 L 12 16 L 12 4 Z' fill='none' stroke='%23ffffff' stroke-width='3' stroke-linejoin='miter'/></svg>" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Frontend Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <script src="https://unpkg.com/@studio-freight/lenis@1.0.33/dist/lenis.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://unpkg.com/typed.js@2.1.0/dist/typed.umd.js"></script>
    <style>
        :root {
            --primary: #FFFFFF;
            --primary-container: #D4D4D4;
            --background: #0a0a0a;
            --surface: #131313;
            --surface-container-low: #1B1B1B;
            --surface-container: #1F1F1F;
            --surface-container-high: #2A2A2A;
            --surface-container-highest: #353535;
            --surface-container-lowest: #050505;
            --on-surface: #E2E2E2;
            --on-surface-variant: #C6C6C6;
            --outline-variant: #474747;
            --inverse-surface: #E2E2E2;
            --inverse-on-surface: #303030;
            --secondary-container: #46474B;
            --on-secondary-container: #E3E2E7;
            --accent: #FFFFFF;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html, body {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        ::-webkit-scrollbar {
            display: none;
        }

        body {
            font-family: 'Be Vietnam Pro', sans-serif;
            background-color: var(--background);
            color: var(--on-surface);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Preloader */
        #preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--background);
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: opacity 0.8s ease-out, visibility 0.8s;
        }
        
        .loader-logo {
            font-size: 2.5rem;
            font-weight: 900;
            color: var(--primary);
            letter-spacing: -0.05em;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: pulse-glow 2s infinite ease-in-out;
        }
        
        @keyframes pulse-glow {
            0% { opacity: 0.5; filter: drop-shadow(0 0 10px rgba(255,255,255,0)); }
            50% { opacity: 1; filter: drop-shadow(0 0 20px rgba(255,255,255,0.6)); }
            100% { opacity: 0.5; filter: drop-shadow(0 0 10px rgba(255,255,255,0)); }
        }

        /* Typography */
        h1, h2, h3, h4, h5, h6 {
            font-weight: 800;
            letter-spacing: -0.02em;
            color: var(--on-surface);
        }

        .display-lg {
            font-size: 3.5rem;
            line-height: 1.2;
            letter-spacing: -0.03em;
        }

        .headline-sm {
            font-size: 1.5rem;
            line-height: 1.4;
        }

        .body-lg {
            font-size: 1.125rem;
            line-height: 1.6;
            color: var(--on-surface-variant);
        }

        .body-md {
            font-size: 0.875rem;
            line-height: 1.6;
            color: var(--on-surface-variant);
        }

        .label-md {
            font-size: 0.75rem;
            line-height: 1.5;
            color: var(--on-surface-variant);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* Header / Nav (Glassmorphism) */
        header {
            position: sticky;
            top: 0;
            z-index: 100;
            background: transparent;
            border-bottom: 1px solid transparent;
            transition: all 0.4s ease;
        }

        header.scrolled {
            background: rgba(10, 10, 10, 0.85);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }

        .header-inner {
            max-width: 1400px;
            margin: 0 auto;
            padding: 16px 40px;
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            align-items: center;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 900;
            color: var(--primary);
            text-decoration: none;
            letter-spacing: -0.05em;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: transform 0.3s ease;
        }
        
        .logo:hover {
            transform: scale(1.05);
        }

        .nav-links {
            display: flex;
            gap: 40px;
            align-items: center;
            justify-content: center;
        }

        .nav-links a {
            color: var(--on-surface-variant);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: color 0.3s ease, text-shadow 0.3s ease;
            position: relative;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 1px;
            bottom: -4px;
            left: 0;
            background-color: var(--primary);
            transition: width 0.3s ease;
        }

        .nav-links a:hover, .nav-links a.active {
            color: var(--primary);
            text-shadow: 0 0 12px rgba(255,255,255,0.3);
        }
        
        .nav-links a:hover::after {
            width: 100%;
        }

        .header-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 16px;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 24px;
            font-size: 0.875rem;
            font-weight: 600;
            border-radius: 0.75rem;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            border: none;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0; left: -100%; width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-primary {
            background: var(--primary);
            color: var(--background);
            box-shadow: 0 4px 14px rgba(255, 255, 255, 0.1);
        }

        .btn-primary:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 6px 20px rgba(255, 255, 255, 0.2);
            background: #e0e0e0;
        }

        .btn-secondary {
            background: var(--surface-container-high);
            color: var(--primary);
            border: 1px solid rgba(255,255,255,0.1);
        }

        .btn-secondary:hover {
            background: var(--surface-container-highest);
            transform: translateY(-2px);
            border-color: rgba(255,255,255,0.3);
        }

        .btn-ghost {
            background: transparent;
            color: var(--primary);
        }

        .btn-ghost:hover {
            background: rgba(255, 255, 255, 0.08);
            transform: translateY(-1px);
        }

        /* Layout */
        main {
            flex: 1;
            width: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 40px;
            width: 100%;
        }

        /* Inputs */
        input, select, textarea {
            background: var(--surface-container-high);
            border: 1px solid transparent;
            color: var(--on-surface);
            padding: 14px 16px;
            border-radius: 0.5rem;
            font-family: 'Be Vietnam Pro', sans-serif;
            font-size: 0.875rem;
            width: 100%;
            transition: all 0.3s ease;
            outline: none;
        }

        input:focus, select:focus, textarea:focus {
            border-color: rgba(255, 255, 255, 0.2);
            background: var(--surface-container-highest);
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.05);
        }

        /* Hide native number input arrows globally */
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { 
            -webkit-appearance: none; 
            margin: 0; 
        }
        input[type=number] {
            -moz-appearance: textfield;
        }

        /* Flash Messages */
        .flash {
            padding: 16px 24px;
            border-radius: 0.5rem;
            margin-bottom: 32px;
            font-weight: 500;
            font-size: 0.875rem;
            animation: slideInDown 0.5s ease;
        }

        .flash-success {
            background: rgba(255, 255, 255, 0.1);
            color: var(--primary);
            border-left: 4px solid var(--primary);
        }

        /* Fix Laravel Pagination */
        nav[aria-label="Pagination Navigation"] {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 16px;
            margin-top: 40px;
        }
        nav[aria-label="Pagination Navigation"] svg {
            width: 20px;
            height: 20px;
            fill: currentColor;
        }
        nav[aria-label="Pagination Navigation"] a, 
        nav[aria-label="Pagination Navigation"] span {
            background: var(--surface-container-low);
            color: var(--on-surface);
            border: 1px solid rgba(255,255,255,0.05);
            padding: 10px 16px;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        nav[aria-label="Pagination Navigation"] a:hover {
            background: var(--surface-container-highest);
            border-color: rgba(255,255,255,0.2);
        }
        nav[aria-label="Pagination Navigation"] .text-gray-500 {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .hidden.sm\:flex-1.sm\:flex.sm\:items-center.sm\:justify-between {
            display: flex; flex-direction: column; align-items: center; gap: 16px;
        }

        /* Footer */
        footer {
            background: var(--surface-container-lowest);
            padding: 80px 40px 40px;
            margin-top: auto;
            position: relative;
        }
        
        footer::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
        }

        .footer-inner {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 64px;
        }

        .footer-inner h4 {
            font-size: 1rem;
            margin-bottom: 24px;
            color: var(--primary);
        }

        .footer-inner p, .footer-inner a {
            color: var(--on-surface-variant);
            font-size: 0.875rem;
            text-decoration: none;
            margin-bottom: 12px;
            display: block;
            transition: color 0.3s, transform 0.3s;
        }

        .footer-inner a:hover {
            color: var(--primary);
            transform: translateX(4px);
        }

        /* Utilities */
        .glass-panel {
            background: var(--surface-container);
            border-radius: 1rem;
            padding: 32px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
            border: 1px solid rgba(255,255,255,0.03);
            backdrop-filter: blur(10px);
        }
        
        .hover-glow {
            transition: all 0.4s ease;
        }
        .hover-glow:hover {
            box-shadow: 0 0 30px rgba(255,255,255,0.05);
            border-color: rgba(255,255,255,0.1);
        }

        /* Animations */
        @keyframes slideInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fade-up {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }
        
        .animate-fade-up.visible {
            opacity: 1;
            transform: translateY(0);
        }
        
        .delay-1 { transition-delay: 0.1s; }
        .delay-2 { transition-delay: 0.2s; }
        .delay-3 { transition-delay: 0.3s; }
        
        /* Search Overlay */
        #search-overlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(10,10,10,0.95);
            backdrop-filter: blur(20px);
            z-index: 1000;
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            visibility: hidden;
            transition: all 0.5s ease;
        }
        #search-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        .search-form-overlay {
            width: 100%;
            max-width: 800px;
            padding: 0 40px;
            transform: translateY(40px);
            transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        }
        
        #search-overlay.active .search-form-overlay {
            transform: translateY(0);
        }
        
        .search-input-huge {
            width: 100%;
            background: transparent;
            border: none;
            border-bottom: 2px solid rgba(255,255,255,0.2);
            color: white;
            font-size: 3rem;
            padding: 20px 0;
            outline: none;
            border-radius: 0;
            font-weight: 300;
        }
        .search-input-huge:focus {
            border-bottom-color: white;
            box-shadow: none;
            background: transparent;
        }
        
        .close-search {
            position: absolute;
            top: 40px; right: 40px;
            background: transparent;
            border: none;
            color: white;
            font-size: 2rem;
            cursor: pointer;
            transition: transform 0.3s;
        }
        .close-search:hover { transform: rotate(90deg); }
    </style>
    @stack('styles')
</head>
<body>

<!-- Preloader -->
@if(request()->is('/'))
<div id="preloader">
    <div style="text-align: center;">
        <div class="loader-logo" style="margin-bottom: 24px;">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M 20 4 L 20 20 L 4 20 L 4 8 L 12 16 L 12 4 Z" stroke="white" stroke-width="3" stroke-linejoin="miter"/>
            </svg>
            iShop
        </div>
        <div style="width: 200px; height: 2px; background: rgba(255,255,255,0.1); border-radius: 2px; overflow: hidden; margin: 0 auto;">
            <div id="loader-bar" style="width: 0%; height: 100%; background: white; transition: width 0.45s ease-out;"></div>
        </div>
    </div>
</div>
@endif

<!-- Search Overlay -->
<div id="search-overlay">
    <button class="close-search" onclick="toggleSearch()">×</button>
    <form action="/products" method="GET" class="search-form-overlay">
        <input type="text" name="search" class="search-input-huge" placeholder="" autocomplete="off">
        <p style="color: var(--on-surface-variant); margin-top: 16px; font-size: 1.125rem;">Nhấn Enter để tìm kiếm</p>
    </form>
</div>

<header>
    <div class="header-inner">
        <a href="/" class="logo">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right: 4px;">
                <path d="M 20 4 L 20 20 L 4 20 L 4 8 L 12 16 L 12 4 Z" stroke="white" stroke-width="3" stroke-linejoin="miter"/>
            </svg>
            iShop
        </a>

        <nav class="nav-links">
            <a href="/products">Sản phẩm</a>
            <a href="/products">Danh mục</a>
            <a href="/cart">Giỏ hàng (<span id="cart-count">{{ collect(session('cart', []))->sum('quantity') }}</span>)</a>
            @auth
                <a href="/orders">Đơn hàng</a>
            @endauth
            <a href="javascript:void(0)" onclick="toggleSearch()" title="Tìm kiếm">
                <i class="ri-search-line" style="font-size: 1.25rem;"></i>
            </a>
        </nav>

        <div class="header-actions">
            @auth
                <div class="user-pill" style="display: flex; align-items: center; gap: 12px; background: rgba(255,255,255,0.03); padding: 6px 16px 6px 6px; border-radius: 100px; border: 1px solid rgba(255,255,255,0.08); transition: all 0.3s; cursor: pointer;" onmouseover="this.style.background='rgba(255,255,255,0.08)'" onmouseout="this.style.background='rgba(255,255,255,0.03)'">
                    <div style="width: 36px; height: 36px; border-radius: 50%; background: var(--surface-container-highest); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 0.95rem; box-shadow: inset 0 2px 4px rgba(0,0,0,0.5);">
                        {{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <a href="/orders" style="font-weight: 600; font-size: 0.9rem; color: white; text-decoration: none;">{{ auth()->user()->name }}</a>
                    <form action="/logout" method="POST" style="display:flex; align-items: center; margin-left: 4px; border-left: 1px solid rgba(255,255,255,0.1); padding-left: 12px; height: 20px;">
                        @csrf
                        <button type="submit" style="background: none; border: none; color: var(--on-surface-variant); cursor: pointer; display: flex; align-items: center; font-size: 1.25rem; transition: color 0.3s;" title="Đăng xuất" onmouseover="this.style.color='#ffb4ab'" onmouseout="this.style.color='var(--on-surface-variant)'">
                            <i class="ri-logout-circle-r-line"></i>
                        </button>
                    </form>
                </div>
            @else
                <a href="/login" class="btn btn-ghost" style="padding: 10px 20px;">Đăng nhập</a>
                <a href="/register" class="btn btn-primary" style="padding: 10px 24px; border-radius: 100px;">Tạo tài khoản</a>
            @endauth
        </div>
    </div>
</header>

<main>
    @if(session('success') || session('error'))
    <div class="container" style="padding-top: 40px;">
        @if(session('success'))
            <div class="flash flash-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="flash flash-error" style="background: rgba(255,0,0,0.1); color: #ffb4ab; border-left: 4px solid #ffb4ab;">{{ session('error') }}</div>
        @endif
    </div>
    @endif

    @yield('content')
</main>

<footer>
    <div class="footer-inner">
        <div>
            <h4 class="logo" style="margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M 20 4 L 20 20 L 4 20 L 4 8 L 12 16 L 12 4 Z" stroke="white" stroke-width="3" stroke-linejoin="miter"/>
                </svg>
                iShop
            </h4>
            <p>Phòng trưng bày Obsidian.<br>Định hình phong cách sống thượng lưu.</p>
        </div>
        <div>
            <h4>Khám phá</h4>
            <a href="/products">Sản phẩm mới nhất</a>
            <a href="/products">Bộ sưu tập độc quyền</a>
            <a href="#">Khái niệm nghệ thuật</a>
        </div>
        <div>
            <h4>Dịch vụ khách hàng</h4>
            <a href="#">Hỗ trợ đặc quyền</a>
            <a href="#">Vận chuyển & Hoàn trả</a>
            <a href="#">Câu hỏi thường gặp</a>
        </div>
    </div>
</footer>

<script>
    // Preloader
    @if(request()->is('/'))
    window.addEventListener('load', function() {
        const preloader = document.getElementById('preloader');
        const bar = document.getElementById('loader-bar');
        
        // Start animating the bar
        setTimeout(() => {
            if(bar) bar.style.width = '100%';
        }, 100);

        // Hide preloader after animation completes
        setTimeout(() => {
            if(preloader) {
                preloader.style.opacity = '0';
                setTimeout(() => {
                    preloader.style.display = 'none';
                }, 400);
            }
        }, 500); // 0.5 seconds delay for a premium feel
    });
    @endif

    // Initialize Lenis for Smooth Scrolling
    const lenis = new Lenis({
        duration: 1.2,
        easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
        smooth: true,
    });

    function raf(time) {
        lenis.raf(time);
        requestAnimationFrame(raf);
    }
    requestAnimationFrame(raf);

    // Header Scroll Effect
    window.addEventListener('scroll', () => {
        const header = document.querySelector('header');
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });

    // Scroll Animations
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.1
    };
    
    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.animate-fade-up').forEach((el) => {
            observer.observe(el);
        });
    });
    
    // Search Overlay
    function toggleSearch() {
        const overlay = document.getElementById('search-overlay');
        overlay.classList.toggle('active');
        if (overlay.classList.contains('active')) {
            setTimeout(() => {
                document.querySelector('.search-input-huge').focus();
            }, 100);

            if(!window.searchTyped) {
                window.searchTyped = new Typed('.search-input-huge', {
                    strings: ['Tìm kiếm iPhone 17 Pro Max...', 'Tìm kiếm MacBook Pro M5...', 'Tìm kiếm AirPods Pro...'],
                    typeSpeed: 50,
                    backSpeed: 30,
                    backDelay: 1500,
                    attr: 'placeholder',
                    bindInputFocusEvents: true,
                    loop: true
                });
            }
        }
    }

    // AJAX Add to Cart
    function addToCart(productId, qty = 1, storage = '', color = '', price = null) {
        if (!productId) return;
        fetch('/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ 
                product_id: productId, 
                qty: qty,
                storage: storage,
                color: color,
                price: price
            })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                let countBadge = document.getElementById('cart-count');
                if(countBadge) {
                    countBadge.innerText = parseInt(countBadge.innerText) + 1;
                }
                
                Toastify({
                    text: data.message || "Đã thêm vào giỏ hàng!",
                    duration: 3000,
                    gravity: "bottom",
                    position: "right",
                    style: {
                        background: "var(--surface-container-high)",
                        color: "var(--primary)",
                        borderRadius: "12px",
                        border: "1px solid rgba(255,255,255,0.1)",
                        boxShadow: "0 10px 30px rgba(0,0,0,0.5)",
                        fontFamily: "'Be Vietnam Pro', sans-serif",
                        fontSize: "14px",
                        padding: "16px 24px"
                    }
                }).showToast();
            }
        })
        .catch(error => console.error('Error adding to cart:', error));
    }
</script>
@stack('scripts')
</body>
</html>
