@extends('layouts.app')
@section('title', 'Giỏ Hàng Của Bạn')
@section('content')
<div style="padding: 0 40px; max-width: 1200px; margin: 0 auto;">
    <h1 class="display-lg animate-fade-up" style="margin-bottom: 64px;">Giỏ Hàng <span style="color: var(--on-surface-variant); font-size: 1.5rem; vertical-align: middle;">({{ collect(session('cart', []))->sum('quantity') }} sản phẩm)</span></h1>

    @if(empty($cart))
        <div class="animate-fade-up glass-panel" style="text-align: center; padding: 100px 0; border: 1px dashed rgba(255,255,255,0.1);">
            <div style="font-size: 4rem; opacity: 0.2; margin-bottom: 24px;">🛒</div>
            <p class="body-lg" style="margin-bottom: 32px; color: var(--on-surface-variant);">Giỏ hàng của bạn đang trống rỗng.</p>
            <a href="/products" class="btn btn-primary" style="padding: 16px 40px;">Bắt đầu mua sắm</a>
        </div>
    @else
        <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 40px; align-items: start;">
            {{-- Cart Items --}}
            <div style="display: flex; flex-direction: column; gap: 16px;">
                <div class="glass-panel animate-fade-up" style="padding: 0; overflow: hidden; background: var(--surface-container-lowest);">
                    <div style="padding: 24px 32px; border-bottom: 1px solid rgba(255,255,255,0.05); display: flex; align-items: center; justify-content: space-between;">
                        <span class="label-md" style="color: var(--on-surface-variant); text-transform: uppercase; letter-spacing: 1px;">Sản phẩm</span>
                        <span class="label-md" style="color: var(--on-surface-variant); text-transform: uppercase; letter-spacing: 1px;">Thao tác</span>
                    </div>
                    
                    @foreach($cart as $id => $details)
                        <div class="cart-item hover-glow" style="display: flex; align-items: center; padding: 32px; gap: 32px; border-bottom: 1px solid rgba(255,255,255,0.03); transition: background 0.3s; position: relative;">
                            
                            {{-- Image --}}
                            <div style="width: 100px; height: 100px; background: white; display: flex; justify-content: center; align-items: center; flex-shrink: 0; border-radius: 16px; padding: 12px;">
                                @if($details['img'])
                                    <img src="{{ asset('storage/' . $details['img']) }}" alt="{{ $details['name'] }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                @endif
                            </div>
                            
                            {{-- Info --}}
                            <div style="flex: 1;">
                                <h3 class="headline-sm" style="color: white; margin-bottom: 8px; font-size: 1.25rem;">{{ $details['name'] }}</h3>
                                @if(!empty($details['storage']) || !empty($details['color']))
                                    <div class="label-md" style="margin-bottom: 8px; display: flex; flex-wrap: wrap; gap: 8px;">
                                        @if(!empty($details['storage']))
                                            <span style="background: rgba(255,255,255,0.1); padding: 4px 8px; border-radius: 4px; white-space: nowrap;">{{ $details['storage'] }}</span>
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
                                            <span style="background: rgba(255,255,255,0.1); padding: 4px 8px; border-radius: 4px; white-space: nowrap; display: inline-flex; align-items: center; gap: 6px;">
                                                @if($hex !== 'transparent')
                                                    <span style="width: 12px; height: 12px; border-radius: 50%; background: {{ $hex }}; display: inline-block; border: 1px solid rgba(0,0,0,0.2);"></span>
                                                @endif
                                                {{ $details['color'] }}
                                            </span>
                                        @endif
                                    </div>
                                @endif
                                <div class="body-md" style="color: var(--primary); font-weight: 600;">{{ number_format($details['price'], 0, ',', '.') }} ₫</div>
                            </div>

                            {{-- Quantity Update Form --}}
                            <form action="/cart/update" method="POST" style="display: flex; align-items: center; gap: 12px;">
                                @csrf
                                <input type="hidden" name="cart_item_id" value="{{ $id }}">
                                <div style="display: flex; align-items: center; background: rgba(255,255,255,0.05); border-radius: 8px; border: 1px solid rgba(255,255,255,0.1); overflow: hidden;">
                                    <button type="button" onclick="this.nextElementSibling.stepDown()" style="background: none; border: none; color: white; padding: 10px 16px; cursor: pointer; font-size: 1.25rem;">-</button>
                                    <input type="number" name="quantity" value="{{ $details['quantity'] }}" min="1" style="width: 70px; text-align: center; background: transparent; color: white; border: none; padding: 10px 0; font-size: 1rem; font-weight: 600; outline: none; -moz-appearance: textfield;" onchange="this.form.submit()">
                                    <button type="button" onclick="this.previousElementSibling.stepUp()" style="background: none; border: none; color: white; padding: 10px 16px; cursor: pointer; font-size: 1.25rem;">+</button>
                                </div>
                                <button type="submit" class="btn btn-secondary" style="padding: 10px 20px; font-size: 0.9rem; white-space: nowrap;">Cập nhật</button>
                            </form>

                            {{-- Remove Form --}}
                            <form action="/cart/remove" method="POST" style="margin-left: 16px;">
                                @csrf
                                <input type="hidden" name="cart_item_id" value="{{ $id }}">
                                <button type="submit" class="btn btn-ghost" style="color: var(--error); padding: 12px; border-radius: 50%; width: 44px; height: 44px; display: flex; align-items: center; justify-content: center;" title="Xóa khỏi giỏ hàng">
                                    <i class="ri-delete-bin-line" style="font-size: 1.25rem;"></i>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Order Summary --}}
            <div class="glass-panel animate-fade-up delay-1" style="position: sticky; top: 120px; background: var(--surface-container-lowest); padding: 32px;">
                <h2 class="headline-sm" style="margin-bottom: 32px; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 16px;">Tóm tắt đơn hàng</h2>
                
                <div style="display: flex; justify-content: space-between; margin-bottom: 16px; color: var(--on-surface-variant);">
                    <span class="body-md">Tổng phụ</span>
                    <span class="body-md" style="font-weight: 600; color: white;">{{ number_format($total, 0, ',', '.') }} ₫</span>
                </div>
                
                <div style="display: flex; justify-content: space-between; margin-bottom: 32px; color: var(--on-surface-variant);">
                    <span class="body-md">Phí vận chuyển</span>
                    <span class="body-md">Miễn phí</span>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 24px; margin-bottom: 32px; gap: 16px;">
                    <span class="body-lg" style="font-weight: 600; white-space: nowrap;">Tổng thanh toán</span>
                    <span style="font-size: 2rem; color: var(--primary); font-weight: 800; letter-spacing: -0.02em; white-space: nowrap;">{{ number_format($total, 0, ',', '.') }} ₫</span>
                </div>

                <a href="/orders/create" class="btn btn-primary" style="width: 100%; text-align: center; justify-content: center; padding: 18px 0; font-size: 1.125rem;">Tiến Hành Thanh Toán</a>
                <div style="text-align: center; margin-top: 16px;">
                    <a href="/products" class="btn btn-ghost" style="color: var(--on-surface-variant); font-size: 0.9rem;">Tiếp tục mua sắm</a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
