@extends('layouts.app')
@section('title', 'Đăng Nhập')
@section('content')
<style>
    .auth-input {
        width: 100%;
        padding: 16px 20px;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        color: white;
        font-size: 1rem;
        outline: none;
        transition: all 0.3s ease;
        box-sizing: border-box;
    }
    .auth-input:focus {
        background: rgba(255, 255, 255, 0.08);
        border-color: rgba(255, 255, 255, 0.3);
        box-shadow: 0 0 0 4px rgba(255, 255, 255, 0.05);
    }
    .auth-input::placeholder {
        color: rgba(255, 255, 255, 0.3);
    }
    .auth-wrapper {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 70vh;
        padding: 40px;
        overflow: hidden;
    }
    .auth-glow {
        position: absolute;
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, rgba(0,0,0,0) 70%);
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 0;
        pointer-events: none;
        animation: pulse-glow 8s infinite alternate;
    }
</style>

<div class="auth-wrapper">
    <div class="auth-glow"></div>
    <div class="glass-panel animate-fade-up" style="width: 100%; max-width: 440px; padding: 48px; position: relative; z-index: 1; border: 1px solid rgba(255,255,255,0.05); background: rgba(10, 10, 10, 0.6); backdrop-filter: blur(20px);">
        
        <div style="text-align: center; margin-bottom: 40px;">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-bottom: 16px;">
                <path d="M 20 4 L 20 20 L 4 20 L 4 8 L 12 16 L 12 4 Z" stroke="white" stroke-width="2" stroke-linejoin="miter"/>
            </svg>
            <h1 class="display-lg" style="font-size: 2rem; margin-bottom: 8px;">Mừng Bạn Trở Lại</h1>
            <p class="body-md" style="color: var(--on-surface-variant);">Đăng nhập để tiếp tục đặc quyền của bạn.</p>
        </div>

        <form action="/login" method="POST" style="display: flex; flex-direction: column; gap: 24px;">
            @csrf
            <div>
                <label class="label-md" style="display: block; margin-bottom: 8px; color: var(--on-surface-variant); font-weight: 500;">Tên Tài Khoản</label>
                <input type="text" name="username" class="auth-input" placeholder="Nhập tên đăng nhập" required autocomplete="off">
            </div>
            <div>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                    <label class="label-md" style="color: var(--on-surface-variant); font-weight: 500;">Mật Khẩu</label>
                    <a href="#" style="color: rgba(255,255,255,0.5); font-size: 0.85rem; text-decoration: none; transition: color 0.3s;">Quên mật khẩu?</a>
                </div>
                <input type="password" name="password" class="auth-input" placeholder="Nhập mật khẩu" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 18px; margin-top: 8px; font-size: 1.1rem; border-radius: 12px; font-weight: 600;">Xác Nhận Đăng Nhập</button>
        </form>
        
        <div style="margin-top: 32px; text-align: center; padding-top: 24px; border-top: 1px solid rgba(255,255,255,0.05);">
            <p class="body-md" style="color: var(--on-surface-variant);">Chưa sở hữu tài khoản? <a href="/register" style="color: white; text-decoration: none; font-weight: 600; transition: opacity 0.3s;">Khởi tạo ngay</a></p>
        </div>
    </div>
</div>
@endsection
