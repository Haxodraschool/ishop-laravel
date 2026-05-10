@extends('layouts.app')
@section('title', 'Tạo Tài Khoản')
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
        min-height: 80vh;
        padding: 40px;
        overflow: hidden;
    }
    .auth-glow {
        position: absolute;
        width: 700px;
        height: 700px;
        background: radial-gradient(circle, rgba(255,255,255,0.06) 0%, rgba(0,0,0,0) 70%);
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
    <div class="glass-panel animate-fade-up" style="width: 100%; max-width: 480px; padding: 48px; position: relative; z-index: 1; border: 1px solid rgba(255,255,255,0.05); background: rgba(10, 10, 10, 0.6); backdrop-filter: blur(20px);">
        
        <div style="text-align: center; margin-bottom: 40px;">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-bottom: 16px;">
                <path d="M 20 4 L 20 20 L 4 20 L 4 8 L 12 16 L 12 4 Z" stroke="white" stroke-width="2" stroke-linejoin="miter"/>
            </svg>
            <h1 class="display-lg" style="font-size: 2rem; margin-bottom: 8px;">Gia Nhập Thế Giới Mới</h1>
            <p class="body-md" style="color: var(--on-surface-variant);">Khởi tạo tài khoản để tận hưởng các dịch vụ đẳng cấp.</p>
        </div>

        <form action="/register" method="POST" style="display: flex; flex-direction: column; gap: 20px;">
            @csrf
            <div>
                <label class="label-md" style="display: block; margin-bottom: 8px; color: var(--on-surface-variant); font-weight: 500;">Họ Và Tên</label>
                <input type="text" name="name" class="auth-input" placeholder="Tên hiển thị của bạn" required autocomplete="name">
            </div>
            <div>
                <label class="label-md" style="display: block; margin-bottom: 8px; color: var(--on-surface-variant); font-weight: 500;">Tên Đăng Nhập</label>
                <input type="text" name="username" class="auth-input" placeholder="Tên dùng để đăng nhập" required autocomplete="username">
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div>
                    <label class="label-md" style="display: block; margin-bottom: 8px; color: var(--on-surface-variant); font-weight: 500;">Mật Khẩu</label>
                    <input type="password" name="password" class="auth-input" placeholder="Tạo mật khẩu" required autocomplete="new-password">
                </div>
                <div>
                    <label class="label-md" style="display: block; margin-bottom: 8px; color: var(--on-surface-variant); font-weight: 500;">Xác Nhận</label>
                    <input type="password" name="password_confirmation" class="auth-input" placeholder="Nhập lại mật khẩu" required autocomplete="new-password">
                </div>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 18px; margin-top: 12px; font-size: 1.1rem; border-radius: 12px; font-weight: 600;">Mở Khóa Đặc Quyền</button>
        </form>
        
        <div style="margin-top: 32px; text-align: center; padding-top: 24px; border-top: 1px solid rgba(255,255,255,0.05);">
            <p class="body-md" style="color: var(--on-surface-variant);">Đã là thành viên? <a href="/login" style="color: white; text-decoration: none; font-weight: 600; transition: opacity 0.3s;">Đăng nhập ngay</a></p>
        </div>
    </div>
</div>
@endsection
