@extends('layouts.app')
@section('title', $product->name)
@section('content')
<div style="padding: 40px 40px;">
    <!-- Top Section: Image & Info -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 80px; align-items: start; margin-bottom: 120px;">
        <!-- Image Side -->
        <div class="animate-fade-up glass-panel hover-glow" style="position: sticky; top: 120px; background: var(--surface-container-lowest); padding: 40px; display: flex; justify-content: center; align-items: center; min-height: 600px; border-radius: 24px; overflow: hidden;">
            @if($product->img)
                <img src="{{ asset('storage/' . $product->img) }}" alt="{{ $product->name }}" style="max-width: 100%; height: auto; mix-blend-mode: lighten; filter: drop-shadow(0 20px 40px rgba(0,0,0,0.8)); transition: transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
            @else
                <div style="color: var(--on-surface-variant); font-size: 2rem; font-weight: 300;">Chưa cập nhật hình ảnh</div>
            @endif
        </div>

        <!-- Details Side -->
        <div style="padding: 40px 0;">
            <div class="label-md animate-fade-up" style="margin-bottom: 16px; color: var(--on-surface-variant);">{{ $product->category->name ?? 'Chưa phân loại' }}</div>
            <h1 class="display-lg animate-fade-up delay-1" style="margin-bottom: 24px;">{{ $product->name }}</h1>
            <div id="product-price" class="animate-fade-up delay-2" style="font-size: 2rem; font-weight: 700; color: var(--primary); margin-bottom: 48px; text-shadow: 0 0 20px rgba(255,255,255,0.2);">
                {{ number_format($product->price, 0, ',', '.') }} ₫
            </div>

            <p class="body-lg animate-fade-up delay-3" style="margin-bottom: 48px; border-left: 3px solid var(--surface-container-highest); padding-left: 24px; line-height: 1.8;">
                {{ $product->desc }}
            </p>

            <style>
                .variant-options { display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 32px; }
                .variant-radio { display: none; }
                .variant-label { 
                    padding: 12px 24px; 
                    border-radius: 12px; 
                    border: 1px solid rgba(255,255,255,0.1); 
                    color: var(--on-surface-variant); 
                    font-weight: 600; 
                    cursor: pointer; 
                    transition: all 0.3s ease; 
                }
                .variant-radio:checked + .variant-label { 
                    border-color: white; 
                    color: white; 
                    background: rgba(255,255,255,0.1); 
                }
                
                .color-options { display: flex; gap: 16px; margin-bottom: 48px; }
                .color-radio { display: none; }
                .color-label {
                    width: 36px; height: 36px;
                    border-radius: 50%;
                    cursor: pointer;
                    display: flex; justify-content: center; align-items: center;
                    transition: all 0.3s ease;
                    border: 2px solid transparent;
                }
                .color-radio:checked + .color-label {
                    border-color: white;
                    transform: scale(1.1);
                    box-shadow: 0 0 15px rgba(255,255,255,0.2);
                }
                .color-circle {
                    width: 28px; height: 28px;
                    border-radius: 50%;
                    box-shadow: inset 0 2px 4px rgba(0,0,0,0.3);
                }
                
                /* Hide native number input arrows */
                input[type=number]::-webkit-inner-spin-button, 
                input[type=number]::-webkit-outer-spin-button { 
                    -webkit-appearance: none; 
                    margin: 0; 
                }
                input[type=number] {
                    -moz-appearance: textfield;
                }
            </style>

            @php
                $isAirpod = str_contains(strtolower($product->name), 'airpod') || str_contains(strtolower($product->category->name ?? ''), 'airpod');
                $isMacbook = str_contains(strtolower($product->name), 'macbook') || str_contains(strtolower($product->category->name ?? ''), 'macbook');
            @endphp

            @if(!$isAirpod)
            <!-- Storage Variants -->
            <div class="animate-fade-up delay-3">
                <div style="font-size: 1rem; font-weight: 600; color: white; margin-bottom: 16px;">Dung lượng bộ nhớ</div>
                <div class="variant-options">
                    <input type="radio" name="storage" id="storage_128" class="variant-radio" checked onchange="updatePrice(0)">
                    <label for="storage_128" class="variant-label">128GB</label>

                    <input type="radio" name="storage" id="storage_256" class="variant-radio" onchange="updatePrice(2000000)">
                    <label for="storage_256" class="variant-label">256GB</label>

                    <input type="radio" name="storage" id="storage_512" class="variant-radio" onchange="updatePrice(5000000)">
                    <label for="storage_512" class="variant-label">512GB</label>

                    <input type="radio" name="storage" id="storage_1024" class="variant-radio" onchange="updatePrice(10000000)">
                    <label for="storage_1024" class="variant-label">1TB</label>
                </div>
            </div>
            @endif

            <!-- Color Variants -->
            <div class="animate-fade-up delay-3">
                <div style="font-size: 1rem; font-weight: 600; color: white; margin-bottom: 16px;">Màu sắc</div>
                <div class="color-options">
                    @if($isAirpod)
                        <input type="radio" name="color" id="color_white" class="color-radio" checked>
                        <label for="color_white" class="color-label" title="Trắng"><div class="color-circle" style="background: #F3F2F1;"></div></label>
                    @elseif($isMacbook)
                        <input type="radio" name="color" id="color_silver" class="color-radio" checked>
                        <label for="color_silver" class="color-label" title="Bạc"><div class="color-circle" style="background: #E3E4E5;"></div></label>

                        <input type="radio" name="color" id="color_space_gray" class="color-radio">
                        <label for="color_space_gray" class="color-label" title="Xám Không Gian"><div class="color-circle" style="background: #7D7E80;"></div></label>
                    @else
                        <input type="radio" name="color" id="color_titan_black" class="color-radio" checked>
                        <label for="color_titan_black" class="color-label" title="Titan Đen"><div class="color-circle" style="background: #343435;"></div></label>

                        <input type="radio" name="color" id="color_titan_white" class="color-radio">
                        <label for="color_titan_white" class="color-label" title="Titan Trắng"><div class="color-circle" style="background: #F3F2F1;"></div></label>

                        <input type="radio" name="color" id="color_titan_blue" class="color-radio">
                        <label for="color_titan_blue" class="color-label" title="Titan Xanh"><div class="color-circle" style="background: #2F394D;"></div></label>

                        <input type="radio" name="color" id="color_titan_natural" class="color-radio">
                        <label for="color_titan_natural" class="color-label" title="Titan Tự Nhiên"><div class="color-circle" style="background: #D8D4CE;"></div></label>
                    @endif
                </div>
            </div>

            <form action="javascript:void(0)" onsubmit="submitAddToCart({{ $product->id }})" class="animate-fade-up delay-3" style="display: flex; gap: 16px; align-items: center; margin-bottom: 64px; background: var(--surface-container-low); padding: 16px; border-radius: 16px; border: 1px solid rgba(255,255,255,0.05);">
                <div style="display: flex; align-items: center; background: var(--surface-container-highest); border-radius: 8px; overflow: hidden; height: 56px;">
                    <button type="button" onclick="document.getElementById('qty-input').stepDown()" style="background: none; border: none; color: white; padding: 0 20px; cursor: pointer; font-size: 1.25rem; height: 100%; transition: background 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='none'">-</button>
                    <input type="number" name="qty" id="qty-input" value="1" min="1" style="width: 80px; text-align: center; font-size: 1.125rem; font-weight: 600; background: transparent; border: none; color: white; outline: none; -moz-appearance: textfield;">
                    <button type="button" onclick="document.getElementById('qty-input').stepUp()" style="background: none; border: none; color: white; padding: 0 20px; cursor: pointer; font-size: 1.25rem; height: 100%; transition: background 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='none'">+</button>
                </div>
                <button type="submit" class="btn btn-primary" style="flex: 1; height: 56px; font-size: 1.125rem;">Thêm Vào Giỏ Hàng</button>
            </form>
        </div>
    </div>

    <!-- Tech Specs Section (Bento Grid) -->
    <div class="animate-fade-up" style="max-width: 1200px; margin: 0 auto; margin-bottom: 120px;">
        <div style="text-align: center; margin-bottom: 64px;">
            <h2 class="display-lg" style="margin-bottom: 16px; font-size: 2.5rem;">Thông Số Kỹ Thuật</h2>
            <p class="body-lg" style="color: var(--on-surface-variant);">Khám phá chi tiết những trang bị tối tân.</p>
        </div>

        <!-- 3 Columns Top Row -->
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-bottom: 24px;">
            <!-- Display -->
            <div class="glass-panel hover-glow" style="padding: 40px; border-radius: 24px; background: #111111;">
                <i class="ri-smartphone-line" style="font-size: 2.5rem; color: white; margin-bottom: 24px; display: block;"></i>
                <h3 style="font-size: 1.5rem; color: white; font-weight: 600; margin-bottom: 16px;">Màn hình Super Retina XDR</h3>
                <p style="color: var(--on-surface-variant); margin-bottom: 40px; font-size: 0.95rem; line-height: 1.6;">
                    Màn hình OLED toàn viền 6.1 inch (theo đường chéo).
                </p>
                <p style="color: var(--on-surface-variant); font-size: 0.95rem; line-height: 1.6;">
                    Độ phân giải 2556x1179 pixel với mật độ điểm ảnh 460 ppi.
                </p>
            </div>

            <!-- Chip -->
            <div class="glass-panel hover-glow" style="padding: 40px; border-radius: 24px; background: #111111;">
                <i class="ri-cpu-line" style="font-size: 2.5rem; color: white; margin-bottom: 24px; display: block;"></i>
                <h3 style="font-size: 1.5rem; color: white; font-weight: 600; margin-bottom: 16px;">Chip A17 Pro</h3>
                <p style="color: var(--on-surface-variant); margin-bottom: 40px; font-size: 0.95rem; line-height: 1.6;">
                    CPU 6 lõi mới với 2 lõi hiệu năng và 4 lõi tiết kiệm điện.
                </p>
                <p style="color: var(--on-surface-variant); font-size: 0.95rem; line-height: 1.6;">
                    GPU 6 lõi mới. Neural Engine 16 lõi mới.
                </p>
            </div>

            <!-- Camera -->
            <div class="glass-panel hover-glow" style="padding: 40px; border-radius: 24px; background: #111111;">
                <i class="ri-camera-lens-line" style="font-size: 2.5rem; color: white; margin-bottom: 24px; display: block;"></i>
                <h3 style="font-size: 1.5rem; color: white; font-weight: 600; margin-bottom: 16px;">Hệ thống camera Pro</h3>
                <p style="color: var(--on-surface-variant); margin-bottom: 40px; font-size: 0.95rem; line-height: 1.6;">
                    Camera chính 48MP, Ultra Wide 12MP, và Telephoto 12MP.
                </p>
                <p style="color: var(--on-surface-variant); font-size: 0.95rem; line-height: 1.6;">
                    Zoom quang học 3x, zoom quang học thu nhỏ 2x.
                </p>
            </div>
        </div>

        <!-- Full Width Bottom Row -->
        <div class="glass-panel hover-glow" style="padding: 40px; border-radius: 24px; background: #111111; display: flex; justify-content: space-between; align-items: center;">
            <div style="max-width: 60%;">
                <i class="ri-battery-charge-line" style="font-size: 2.5rem; color: white; margin-bottom: 24px; display: block;"></i>
                <h3 style="font-size: 1.5rem; color: white; font-weight: 600; margin-bottom: 16px;">Pin và Nguồn điện</h3>
                <p style="color: var(--on-surface-variant); font-size: 0.95rem; line-height: 1.6;">
                    Thời gian xem video: Lên đến 23 giờ. Xem video (trực tuyến): Lên đến 20 giờ.
                </p>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 3rem; color: white; font-weight: 700; line-height: 1.1; margin-bottom: 8px;">23 giờ</div>
                <div style="color: var(--on-surface-variant); font-size: 0.85rem; font-weight: 600; letter-spacing: 0.1em; text-transform: uppercase;">Xem video tối đa</div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let currentAdditionalPrice = 0;

    function updatePrice(additionalPrice) {
        currentAdditionalPrice = additionalPrice;
        const basePrice = {{ $product->price }};
        const totalPrice = basePrice + additionalPrice;
        
        // Format to Vietnamese currency
        const formattedPrice = new Intl.NumberFormat('vi-VN').format(totalPrice) + ' ₫';
        document.getElementById('product-price').innerText = formattedPrice;
    }

    function submitAddToCart(productId) {
        const qty = parseInt(document.getElementById('qty-input').value) || 1;
        
        let storage = '';
        const storageInput = document.querySelector('input[name="storage"]:checked');
        if (storageInput) {
            storage = storageInput.nextElementSibling.innerText.trim();
        }
        
        let color = '';
        const colorInput = document.querySelector('input[name="color"]:checked');
        if (colorInput) {
            color = colorInput.nextElementSibling.getAttribute('title');
        }
        
        const basePrice = {{ $product->price }};
        const finalPrice = basePrice + currentAdditionalPrice;
        
        addToCart(productId, qty, storage, color, finalPrice);
    }
</script>
@endpush

@endsection
