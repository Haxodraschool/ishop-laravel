@extends('layouts.app')
@section('title', 'Thanh Toán')
@section('content')
<div style="padding: 0 40px; max-width: 1200px; margin: 0 auto;">
    <h1 class="display-lg animate-fade-up" style="margin-bottom: 64px;">Cổng Thanh Toán</h1>

    @if(empty(session('cart', [])))
        <div class="animate-fade-up glass-panel" style="text-align: center; padding: 80px 0;">
            <p class="body-lg" style="margin-bottom: 24px;">Giỏ hàng của bạn đang trống.</p>
            <a href="/products" class="btn btn-primary">Quay lại Bộ Sưu Tập</a>
        </div>
    @else
        <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 64px; align-items: start;">
            
            <!-- Form thông tin -->
            <div class="animate-fade-up delay-1 glass-panel">
                <h2 class="headline-sm" style="margin-bottom: 32px; padding-bottom: 16px; border-bottom: 1px solid rgba(255,255,255,0.1);">Thông Tin Nhận Hàng</h2>
                
                <form action="/orders" method="POST" id="checkout-form" style="display: flex; flex-direction: column; gap: 24px;">
                    @csrf
                    <div>
                        <label class="label-md" style="display: block; margin-bottom: 8px;">Họ và Tên</label>
                        <input type="text" name="name" value="{{ auth()->user()->name ?? '' }}" required>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                        <div>
                            <label class="label-md" style="display: block; margin-bottom: 8px;">Số Điện Thoại</label>
                            <input type="text" name="phone" required>
                        </div>
                        <div>
                            <label class="label-md" style="display: block; margin-bottom: 8px;">Email</label>
                            <input type="email" name="email" value="{{ auth()->user()->email ?? '' }}" required>
                        </div>
                    </div>

                    <div>
                        <label class="label-md" style="display: block; margin-bottom: 8px;">Địa Chỉ Giao Hàng</label>
                        <textarea name="address" rows="3" required></textarea>
                    </div>
                    
                    <div>
                        <label class="label-md" style="display: block; margin-bottom: 8px;">Ghi chú (Tùy chọn)</label>
                        <textarea name="note" rows="2"></textarea>
                    </div>

                    <h3 class="headline-sm" style="margin-top: 24px; margin-bottom: 16px;">Phương Thức Thanh Toán</h3>
                    <div style="display: flex; gap: 16px;">
                        <label style="flex: 1; background: var(--surface-container-highest); padding: 20px; border-radius: 12px; cursor: pointer; border: 1px solid rgba(255,255,255,0.2); transition: all 0.3s;" class="hover-glow">
                            <input type="radio" name="payment_method" value="cod" checked style="display: inline-block; width: auto; margin-right: 8px;">
                            <span class="body-md" style="font-weight: 600; color: white;">Thanh Toán Khi Nhận Hàng (COD)</span>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary" style="margin-top: 32px; padding: 20px; font-size: 1.125rem;">Hoàn Tất Đặt Hàng</button>
                </form>
            </div>

            <!-- Tóm tắt đơn hàng -->
            <div class="animate-fade-up delay-2 glass-panel" style="background: var(--surface-container-lowest); position: sticky; top: 120px;">
                <h2 class="headline-sm" style="margin-bottom: 32px; padding-bottom: 16px; border-bottom: 1px solid rgba(255,255,255,0.1);">Đơn Hàng Của Bạn</h2>
                
                <div style="display: flex; flex-direction: column; gap: 16px; margin-bottom: 32px; max-height: 400px; overflow-y: auto; padding-right: 8px;">
                    @php $total = 0; @endphp
                    @foreach(session('cart', []) as $id => $details)
                        @php $total += $details['price'] * $details['quantity']; @endphp
                        <div style="display: flex; align-items: center; gap: 16px;">
                            <div style="width: 60px; height: 60px; background: var(--surface-container-high); border-radius: 8px; display: flex; justify-content: center; align-items: center;">
                                @if($details['img'])
                                    <img src="{{ asset('storage/' . $details['img']) }}" style="max-width: 80%; max-height: 80%;">
                                @endif
                            </div>
                            <div style="flex: 1;">
                                <div class="body-md" style="color: white; font-weight: 500;">{{ $details['name'] }}</div>
                                @if(!empty($details['storage']) || !empty($details['color']))
                                    <div class="label-md" style="margin-top: 4px; opacity: 0.7; display: flex; align-items: center; gap: 8px;">
                                        @if(!empty($details['storage']))
                                            <span>{{ $details['storage'] }}</span>
                                        @endif
                                        @if(!empty($details['storage']) && !empty($details['color']))
                                            <span>|</span>
                                        @endif
                                        @if(!empty($details['color']))
                                            @php
                                                $colorMap = [
                                                    'Titan Đen' => '#343435',
                                                    'Titan Trắng' => '#F3F2F1',
                                                    'Titan Xanh' => '#2F394D',
                                                    'Titan Tự Nhiên' => '#D8D4CE',
                                                    'Trắng' => '#F3F2F1',
                                                    'Bạc' => '#E3E4E5',
                                                    'Xám Không Gian' => '#7D7E80'
                                                ];
                                                $hex = $colorMap[$details['color']] ?? 'transparent';
                                            @endphp
                                            <span style="display: inline-flex; align-items: center; gap: 6px;">
                                                @if($hex !== 'transparent')
                                                    <span style="width: 10px; height: 10px; border-radius: 50%; background: {{ $hex }}; display: inline-block; border: 1px solid rgba(255,255,255,0.2);"></span>
                                                @endif
                                                {{ $details['color'] }}
                                            </span>
                                        @endif
                                    </div>
                                @endif
                                <div class="label-md" style="margin-top: 4px;">SL: {{ $details['quantity'] }}</div>
                            </div>
                            <div class="body-md" style="font-weight: 600;">{{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }} ₫</div>
                        </div>
                    @endforeach
                </div>

                <div style="border-top: 1px solid rgba(255,255,255,0.1); padding-top: 24px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 16px;">
                        <span class="body-md">Tạm tính</span>
                        <span class="body-md">{{ number_format($total, 0, ',', '.') }} ₫</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 24px;">
                        <span class="body-md">Phí vận chuyển</span>
                        <span class="body-md" style="color: var(--primary);">Miễn phí</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 24px; padding-top: 24px; border-top: 1px dashed rgba(255,255,255,0.2);">
                        <span class="headline-sm">Tổng Cộng</span>
                        <span class="display-lg" style="font-size: 2rem; color: var(--primary);">{{ number_format($total, 0, ',', '.') }} ₫</span>
                    </div>
                </div>
            </div>

        </div>
    @endif
</div>
@endsection
