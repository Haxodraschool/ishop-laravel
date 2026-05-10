@extends('layouts.app')
@section('title', 'Phòng Trưng Bày Obsidian')
@section('content')

<style>
    /* Bento Grid for Categories */
    .bento-grid {
        display: grid;
        grid-template-columns: repeat(12, 1fr);
        grid-auto-rows: 340px;
        gap: 24px;
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 40px;
    }
    .bento-item {
        background: #111111;
        border-radius: 28px;
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 40px;
        transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.5s ease;
        text-decoration: none;
        border: 1px solid rgba(255,255,255,0.03);
    }
    .bento-item:hover {
        transform: scale(1.02);
        box-shadow: 0 20px 40px rgba(0,0,0,0.5);
        border-color: rgba(255,255,255,0.1);
    }
    .bento-bg {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        object-fit: cover;
        z-index: 0;
        opacity: 0.6;
        transition: opacity 0.5s ease, transform 0.8s ease;
    }
    .bento-item:hover .bento-bg {
        opacity: 0.8;
        transform: scale(1.05);
    }
    .bento-content {
        position: relative;
        z-index: 1;
        text-shadow: 0 2px 10px rgba(0,0,0,0.8);
    }
    .bento-title {
        color: white;
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 8px;
        letter-spacing: -0.03em;
    }
    .bento-subtitle {
        color: #E2E2E2;
        font-size: 1rem;
    }
    .bento-icon {
        position: absolute;
        bottom: 40px;
        right: 40px;
        width: 44px; height: 44px;
        border-radius: 50%;
        background: rgba(255,255,255,0.1);
        display: flex; align-items: center; justify-content: center;
        color: white;
        backdrop-filter: blur(10px);
        transition: background 0.3s ease;
        z-index: 2;
    }
    .bento-item:hover .bento-icon {
        background: rgba(255,255,255,0.25);
    }
    
    /* Perfect Rectangle 12-column Grid Setup */
    .item-ipad { grid-column: span 4; grid-row: span 2; }
    .item-iphone { grid-column: span 4; grid-row: span 1; }
    .item-airpods { grid-column: span 4; grid-row: span 1; }
    .item-macbook { grid-column: span 8; grid-row: span 1; }

    /* Fix container for features */
    .features-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 40px;
    }

    /* Metallic Text Shimmer */
    .text-shimmer {
        background: linear-gradient(90deg, #6c6c6c 0%, #ffffff 50%, #6c6c6c 100%);
        background-size: 200% auto;
        color: #000;
        background-clip: text;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        animation: shimmer 4s cubic-bezier(0.4, 0, 0.2, 1) infinite;
        display: inline-block;
    }
    @keyframes shimmer {
        0% { background-position: 200% center; }
        100% { background-position: -200% center; }
    }
</style>

<!-- Hero Section -->
<section style="min-height: 100vh; width: 100%; display: flex; align-items: center; justify-content: center; position: relative; padding: 40px; overflow: hidden; margin-top: -85px; padding-top: 85px;">
    
    <!-- 3D Vanta.js Background -->
    <div id="hero-vanta" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 0;"></div>
    
    <!-- Overlay for Depth and Readability -->
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: radial-gradient(circle, rgba(10,10,10,0.1) 0%, rgba(10,10,10,0.9) 100%); z-index: 1; pointer-events: none;"></div>

    <div style="text-align: center; max-width: 900px; z-index: 2; pointer-events: none;">
        <h1 class="display-lg animate-fade-up" style="margin-bottom: 24px; font-size: 4.5rem; letter-spacing: -0.04em;">
            Tuyệt Tác Của <br><span class="text-shimmer">Titanium.</span>
        </h1>
        <p class="body-lg animate-fade-up delay-1" style="margin-bottom: 48px; max-width: 600px; margin-left: auto; margin-right: auto; font-size: 1.25rem; font-weight: 300;">
            Bộ sưu tập tuyển chọn những tạo tác tinh xảo và tối tân nhất thế giới. Bước vào không gian không giới hạn của Phòng Trưng Bày Obsidian.
        </p>
        <div class="animate-fade-up delay-2" style="display: flex; gap: 16px; justify-content: center; pointer-events: auto;">
            <a href="/products" class="btn btn-primary" style="padding: 18px 48px; font-size: 1.125rem; border-radius: 100px;">
                Khám Phá Bộ Sưu Tập
            </a>
            <a href="/register" class="btn btn-secondary" style="padding: 18px 48px; font-size: 1.125rem; border-radius: 100px;">
                Gia Nhập Đặc Quyền
            </a>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section style="padding: 120px 0 80px; background: var(--background);">
    <div class="features-container">
        <h2 class="display-lg animate-fade-up" style="font-size: 2.5rem; margin-bottom: 48px;">Danh mục <span style="color: var(--on-surface-variant);">nổi bật.</span></h2>
    </div>
    
    <div class="bento-grid">
        <!-- iPad Pro (Col 1-4, Row 1-2) -->
        <a href="/products?category=ipad" class="bento-item item-ipad animate-fade-up delay-1">
            <img src="{{ asset('storage/categoryimg/ipad.jpg') }}" alt="iPad Pro" class="bento-bg" style="object-position: center;">
            <div class="bento-content">
                <h3 class="bento-title">iPad Pro</h3>
                <p class="bento-subtitle">Đỉnh cao sáng tạo.</p>
            </div>
            <div class="bento-icon"><i class="ri-arrow-right-line"></i></div>
        </a>
        
        <!-- iPhone (Col 5-8, Row 1) -->
        <a href="/products?category=iphone" class="bento-item item-iphone animate-fade-up delay-2">
            <img src="{{ asset('storage/categoryimg/iphone.jpeg') }}" alt="iPhone" class="bento-bg" style="object-position: center 20%;">
            <div class="bento-content">
                <h3 class="bento-title">iPhone</h3>
                <p class="bento-subtitle">Quyền năng trong tay.</p>
            </div>
            <div class="bento-icon"><i class="ri-arrow-right-line"></i></div>
        </a>
        
        <!-- AirPods Pro (Col 9-12, Row 1) -->
        <a href="/products?category=airpods" class="bento-item item-airpods animate-fade-up delay-3">
            <img src="{{ asset('storage/categoryimg/airpod.jpg') }}" alt="AirPods Pro" class="bento-bg">
            <div class="bento-content">
                <h3 class="bento-title">AirPods Pro</h3>
                <p class="bento-subtitle">Âm thanh tuyệt đỉnh.</p>
            </div>
            <div class="bento-icon"><i class="ri-arrow-right-line"></i></div>
        </a>
        
        <!-- MacBook Pro (Col 5-12, Row 2) -->
        <a href="/products?category=macbook" class="bento-item item-macbook animate-fade-up delay-2">
            <img src="{{ asset('storage/categoryimg/macbook.jpg') }}" alt="MacBook Pro" class="bento-bg" style="object-position: center 30%;">
            <div class="bento-content">
                <h3 class="bento-title">MacBook Pro</h3>
                <p class="bento-subtitle">Sức mạnh bứt phá mọi giới hạn.</p>
            </div>
            <div class="bento-icon"><i class="ri-arrow-right-line"></i></div>
        </a>
    </div>
</section>

<!-- Products Section -->
<section style="padding: 100px 0; background: var(--surface-container-lowest);">
    <div class="features-container">
        <h2 class="display-lg animate-fade-up" style="font-size: 2.5rem; margin-bottom: 48px;">Sản phẩm <span style="color: var(--on-surface-variant);">nổi bật.</span></h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 32px;">
            <!-- Product 1 -->
            <div class="glass-panel animate-fade-up delay-1" style="padding: 24px; display: flex; flex-direction: column;">
                <a href="/products/{{ isset($featuredProducts['iPhone 17 Pro Max']) ? $featuredProducts['iPhone 17 Pro Max']->slug : '#' }}" style="text-decoration: none; color: inherit; display: block; flex: 1;">
                    <div style="border-radius: 16px; overflow: hidden; margin-bottom: 24px; height: 240px; background: white; display: flex; align-items: center; justify-content: center; padding: 20px;">
                        <img src="{{ asset('storage/productsimg/iphone17promax.jpeg') }}" style="max-width: 100%; max-height: 100%; object-fit: contain; transition: transform 0.5s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                    </div>
                    <h3 style="font-size: 1.5rem; color: var(--primary); margin-bottom: 8px; font-weight: 700;">iPhone 17 Pro Max</h3>
                    <p style="color: var(--on-surface-variant); margin-bottom: 16px; font-size: 0.95rem;">Thiết kế titan đẳng cấp, sức mạnh vượt bậc.</p>
                    <ul style="color: var(--on-surface-variant); font-size: 0.85rem; margin-bottom: 24px; padding-left: 20px;">
                        <li style="margin-bottom: 6px;">Màn hình Super Retina XDR 6.9"</li>
                        <li style="margin-bottom: 6px;">Hệ thống camera Pro 48MP</li>
                        <li>Chip A19 Pro Bionic</li>
                    </ul>
                </a>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: auto; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 16px;">
                    <span style="font-size: 1.25rem; font-weight: 700; color: var(--primary);">34.990.000₫</span>
                    <button onclick="addToCart({{ isset($featuredProducts['iPhone 17 Pro Max']) ? $featuredProducts['iPhone 17 Pro Max']->id : 0 }})" class="btn btn-primary" style="padding: 8px 16px; border-radius: 8px;"><i class="ri-shopping-cart-2-line"></i></button>
                </div>
            </div>

            <!-- Product 2 -->
            <div class="glass-panel animate-fade-up delay-2" style="padding: 24px; display: flex; flex-direction: column;">
                <a href="/products/{{ isset($featuredProducts['MacBook Pro M5']) ? $featuredProducts['MacBook Pro M5']->slug : '#' }}" style="text-decoration: none; color: inherit; display: block; flex: 1;">
                    <div style="border-radius: 16px; overflow: hidden; margin-bottom: 24px; height: 240px; background: white; display: flex; align-items: center; justify-content: center; padding: 20px;">
                        <img src="{{ asset('storage/productsimg/macbookm5.jpg') }}" style="max-width: 100%; max-height: 100%; object-fit: contain; transition: transform 0.5s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                    </div>
                    <h3 style="font-size: 1.5rem; color: var(--primary); margin-bottom: 8px; font-weight: 700;">MacBook Pro M5</h3>
                    <p style="color: var(--on-surface-variant); margin-bottom: 16px; font-size: 0.95rem;">Hiệu năng vô song cho dân chuyên nghiệp.</p>
                    <ul style="color: var(--on-surface-variant); font-size: 0.85rem; margin-bottom: 24px; padding-left: 20px;">
                        <li style="margin-bottom: 6px;">Màn hình Liquid Retina XDR 14"</li>
                        <li style="margin-bottom: 6px;">Chip Apple M5 Max mạnh mẽ</li>
                        <li>Thời lượng pin lên đến 22 giờ</li>
                    </ul>
                </a>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: auto; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 16px;">
                    <span style="font-size: 1.25rem; font-weight: 700; color: var(--primary);">79.990.000₫</span>
                    <button onclick="addToCart({{ isset($featuredProducts['MacBook Pro M5']) ? $featuredProducts['MacBook Pro M5']->id : 0 }})" class="btn btn-primary" style="padding: 8px 16px; border-radius: 8px;"><i class="ri-shopping-cart-2-line"></i></button>
                </div>
            </div>

            <!-- Product 3 -->
            <div class="glass-panel animate-fade-up delay-3" style="padding: 24px; display: flex; flex-direction: column;">
                <a href="/products/{{ isset($featuredProducts['iPad Pro 11-inch']) ? $featuredProducts['iPad Pro 11-inch']->slug : '#' }}" style="text-decoration: none; color: inherit; display: block; flex: 1;">
                    <div style="border-radius: 16px; overflow: hidden; margin-bottom: 24px; height: 240px; background: white; display: flex; align-items: center; justify-content: center; padding: 20px;">
                        <img src="{{ asset('storage/productsimg/ipadpro11.jpg') }}" style="max-width: 100%; max-height: 100%; object-fit: contain; transition: transform 0.5s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                    </div>
                    <h3 style="font-size: 1.5rem; color: var(--primary); margin-bottom: 8px; font-weight: 700;">iPad Pro 11-inch</h3>
                    <p style="color: var(--on-surface-variant); margin-bottom: 16px; font-size: 0.95rem;">Mỏng nhẹ nhất với màn hình OLED tuyệt hảo.</p>
                    <ul style="color: var(--on-surface-variant); font-size: 0.85rem; margin-bottom: 24px; padding-left: 20px;">
                        <li style="margin-bottom: 6px;">Màn hình Ultra Retina XDR</li>
                        <li style="margin-bottom: 6px;">Chip Apple M4 thế hệ mới</li>
                        <li>Hỗ trợ Apple Pencil Pro</li>
                    </ul>
                </a>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: auto; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 16px;">
                    <span style="font-size: 1.25rem; font-weight: 700; color: var(--primary);">28.990.000₫</span>
                    <button onclick="addToCart({{ isset($featuredProducts['iPad Pro 11-inch']) ? $featuredProducts['iPad Pro 11-inch']->id : 0 }})" class="btn btn-primary" style="padding: 8px 16px; border-radius: 8px;"><i class="ri-shopping-cart-2-line"></i></button>
                </div>
            </div>

            <!-- Product 4 -->
            <div class="glass-panel animate-fade-up delay-1" style="padding: 24px; display: flex; flex-direction: column;">
                <a href="/products/{{ isset($featuredProducts['AirPods Pro 2']) ? $featuredProducts['AirPods Pro 2']->slug : '#' }}" style="text-decoration: none; color: inherit; display: block; flex: 1;">
                    <div style="border-radius: 16px; overflow: hidden; margin-bottom: 24px; height: 240px; background: white; display: flex; align-items: center; justify-content: center; padding: 20px;">
                        <img src="{{ asset('storage/productsimg/airpodpro.jpg') }}" style="max-width: 100%; max-height: 100%; object-fit: contain; transition: transform 0.5s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                    </div>
                    <h3 style="font-size: 1.5rem; color: var(--primary); margin-bottom: 8px; font-weight: 700;">AirPods Pro 2</h3>
                    <p style="color: var(--on-surface-variant); margin-bottom: 16px; font-size: 0.95rem;">Khử tiếng ồn vượt trội, âm thanh không gian.</p>
                    <ul style="color: var(--on-surface-variant); font-size: 0.85rem; margin-bottom: 24px; padding-left: 20px;">
                        <li style="margin-bottom: 6px;">Chip H2 cực kỳ mạnh mẽ</li>
                        <li style="margin-bottom: 6px;">Chống ồn chủ động gấp 2 lần</li>
                        <li>Thời lượng pin lên đến 30 giờ</li>
                    </ul>
                </a>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: auto; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 16px;">
                    <span style="font-size: 1.25rem; font-weight: 700; color: var(--primary);">6.190.000₫</span>
                    <button onclick="addToCart({{ isset($featuredProducts['AirPods Pro 2']) ? $featuredProducts['AirPods Pro 2']->id : 0 }})" class="btn btn-primary" style="padding: 8px 16px; border-radius: 8px;"><i class="ri-shopping-cart-2-line"></i></button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section style="padding: 120px 0; background: var(--background); position: relative; border-top: 1px solid rgba(255,255,255,0.03);">
    <div class="features-container">
        <div style="text-align: center; margin-bottom: 80px;">
            <h2 class="headline-sm animate-fade-up" style="font-size: 2rem;">Chuẩn Mực iShop</h2>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 40px;">
            <!-- Feature 1 -->
            <div class="glass-panel hover-glow animate-fade-up delay-1">
                <div style="font-size: 2.5rem; color: var(--primary); margin-bottom: 24px;"><i class="ri-vip-diamond-line"></i></div>
                <h3 class="body-lg" style="color: var(--primary); margin-bottom: 16px; font-weight: 700;">Chất Lượng Tối Thượng</h3>
                <p class="body-md">Mọi chế tác đều trải qua quy trình kiểm định khắt khe nhất để đảm bảo sự hoàn hảo từ chất liệu đến độ hoàn thiện.</p>
            </div>
            
            <!-- Feature 2 -->
            <div class="glass-panel hover-glow animate-fade-up delay-2">
                <div style="font-size: 2.5rem; color: var(--primary); margin-bottom: 24px;"><i class="ri-truck-line"></i></div>
                <h3 class="body-lg" style="color: var(--primary); margin-bottom: 16px; font-weight: 700;">Dịch Vụ Chuyên Cơ</h3>
                <p class="body-md">Giao hàng toàn cầu với tiêu chuẩn bảo vệ nghiêm ngặt, đảm bảo tác phẩm đến tay bạn trong tình trạng nguyên bản.</p>
            </div>
            
            <!-- Feature 3 -->
            <div class="glass-panel hover-glow animate-fade-up delay-3">
                <div style="font-size: 2.5rem; color: var(--primary); margin-bottom: 24px;"><i class="ri-medal-line"></i></div>
                <h3 class="body-lg" style="color: var(--primary); margin-bottom: 16px; font-weight: 700;">Đặc Quyền Thành Viên</h3>
                <p class="body-md">Thành viên có quyền ưu tiên tiếp cận các phiên bản giới hạn và các tác phẩm được chế tác theo yêu cầu riêng.</p>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.waves.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Init 3D Liquid Metal Waves
        VANTA.WAVES({
            el: "#hero-vanta",
            mouseControls: true,
            touchControls: true,
            gyroControls: false,
            minHeight: 200.00,
            minWidth: 200.00,
            scale: 1.00,
            scaleMobile: 1.00,
            color: 0x111111,         // Dark graphite color
            shininess: 80.00,        // High reflection for metallic feel
            waveHeight: 20.00,       // Prominent waves
            waveSpeed: 0.50,         // Slow, luxurious movement
            zoom: 0.7                // Zooms out slightly to see more ripples
        });
    });
</script>
@endpush

@endsection
