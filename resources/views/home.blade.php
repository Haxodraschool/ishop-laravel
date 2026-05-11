@extends('layouts.app')
@section('title', 'Phòng Trưng Bày Obsidian')
@section('content')

<style>
    /* 2026 Liquid Glass Effects */
    .liquid-glass {
        background: rgba(255, 255, 255, 0.03);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        position: relative;
        overflow: hidden;
    }

    .liquid-glass::before {
        content: '';
        position: absolute;
        top: -50%; left: -50%; width: 200%; height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 70%);
        transform: rotate(0deg);
        transition: transform 0.5s ease;
        pointer-events: none;
    }

    .liquid-glass:hover::before {
        transform: rotate(180deg) translate(10%, 10%);
    }

    /* Metallic Text Effect */
    .text-metallic {
        background: linear-gradient(90deg, #888 0%, #fff 50%, #888 100%);
        background-size: 200% auto;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        animation: shine 4s linear infinite;
    }

    @keyframes shine {
        to { background-position: 200% center; }
    }

    /* Bento Grid for Categories */
    .bento-grid {
        display: grid;
        grid-template-columns: repeat(12, 1fr);
        grid-template-rows: repeat(2, 300px);
        gap: 24px;
        margin-bottom: 80px;
    }

    .bento-item {
        border-radius: 32px;
        position: relative;
        cursor: pointer;
        transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 40px;
        text-decoration: none;
    }

    /* Floating Card Effect */
    .product-card {
        border-radius: 32px;
        background: #0f0f0f;
        padding: 32px;
        border: 1px solid rgba(255,255,255,0.03);
        transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        transform-style: preserve-3d;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .product-card:hover {
        border-color: rgba(255,255,255,0.1);
        box-shadow: 0 30px 60px rgba(0,0,0,0.5);
    }

    .product-card .glow {
        position: absolute;
        width: 150px; height: 150px;
        background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 70%);
        border-radius: 50%;
        filter: blur(20px);
        opacity: 0;
        transition: opacity 0.5s;
        pointer-events: none;
        z-index: 0;
    }

    .product-card:hover .glow {
        opacity: 1;
    }

    /* Custom Scroll Down Indicator */
    .scroll-down {
        position: absolute;
        bottom: 40px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
        color: var(--on-surface-variant);
        font-size: 0.75rem;
        letter-spacing: 0.1em;
        text-transform: uppercase;
    }

    .scroll-down .line {
        width: 1px;
        height: 60px;
        background: linear-gradient(to bottom, rgba(255,255,255,0.3), transparent);
        position: relative;
        overflow: hidden;
    }

    .scroll-down .line::after {
        content: '';
        position: absolute;
        top: -100%; left: 0; width: 100%; height: 100%;
        background: white;
        animation: scroll-line 2s infinite cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes scroll-line {
        0% { top: -100%; }
        100% { top: 100%; }
    }
</style>

{{-- Hero Section --}}
<section id="hero-vanta" style="height: 100vh; position: relative; display: flex; align-items: center; justify-content: center; overflow: hidden; margin-top: -80px;">
    <div style="z-index: 10; text-align: center; max-width: 900px; padding: 0 24px;">
        <h2 class="label-md animate-fade-up" style="margin-bottom: 24px; color: var(--on-surface-variant);">Tương lai trên đôi tay bạn</h2>
        <h1 class="display-lg animate-fade-up delay-1 text-metallic" style="font-size: 5rem; margin-bottom: 32px; font-weight: 900;">Phòng Trưng Bày<br>Obsidian</h1>
        <p class="body-lg animate-fade-up delay-2" style="max-width: 600px; margin: 0 auto 48px; line-height: 1.8;">Nơi hội tụ những tinh hoa công nghệ đỉnh cao, được chế tác tỉ mỉ để kiến tạo phong cách sống đẳng cấp 2026.</p>
        <div class="animate-fade-up delay-3" style="display: flex; gap: 20px; justify-content: center;">
            <a href="/products" class="btn btn-primary" style="padding: 18px 48px; border-radius: 100px; font-size: 1rem;">Khám Phá Ngay</a>
            <a href="#featured" class="btn btn-ghost" style="padding: 18px 32px; font-size: 1rem;">Xem Tuyệt Tác <i class="ri-arrow-right-line" style="margin-left: 8px;"></i></a>
        </div>
    </div>
    <div class="scroll-down animate-fade-up delay-3">
        <span>Khám phá</span>
        <div class="line"></div>
    </div>
</section>

<div class="container" style="padding-bottom: 120px;">
    
    {{-- Category Bento Grid --}}
    <div id="categories" class="bento-grid" style="margin-top: 120px;">
        <a href="/collection?categories_id=1" class="bento-item liquid-glass animate-fade-up" style="grid-column: span 7; grid-row: span 1; background: #000; overflow: hidden; padding: 0;">
            <img src="{{ asset('storage/categoryimg/iphone.jpeg') }}" style="position: absolute; width: 100%; height: 120%; top: 50%; left: 50%; transform: translate(-50%, -50%); object-fit: cover; opacity: 1;">
            <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, transparent 60%); z-index: 1;"></div>
            <div style="z-index: 2; position: absolute; bottom: 40px; left: 40px;">
                <h3 class="headline-sm" style="font-size: 2rem; margin-bottom: 8px; color: white;">iPhone</h3>
                <p class="body-md" style="color: rgba(255,255,255,0.7);">Quyền năng trong tầm tay.</p>
            </div>
        </a>
        <a href="/collection?categories_id=4" class="bento-item liquid-glass animate-fade-up delay-1" style="grid-column: span 5; grid-row: span 1; background: #000; overflow: hidden; padding: 0;">
            <img src="{{ asset('storage/categoryimg/airpod.jpg') }}" style="position: absolute; width: 100%; height: 120%; top: 50%; left: 50%; transform: translate(-50%, -50%); object-fit: cover; opacity: 1;">
            <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, transparent 60%); z-index: 1;"></div>
            <div style="z-index: 2; position: absolute; bottom: 40px; left: 40px;">
                <h3 class="headline-sm" style="font-size: 2rem; margin-bottom: 8px; color: white;">AirPods Pro</h3>
                <p class="body-md" style="color: rgba(255,255,255,0.7);">Âm thanh tuyệt đỉnh.</p>
            </div>
        </a>
        <a href="/collection?categories_id=2" class="bento-item liquid-glass animate-fade-up delay-2" style="grid-column: span 4; grid-row: span 1; background: #000; overflow: hidden; padding: 0;">
            <img src="{{ asset('storage/categoryimg/ipad.jpg') }}" style="position: absolute; width: 100%; height: 120%; top: 50%; left: 50%; transform: translate(-50%, -50%); object-fit: cover; opacity: 1;">
            <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, transparent 60%); z-index: 1;"></div>
            <div style="z-index: 2; position: absolute; bottom: 40px; left: 40px;">
                <h3 class="headline-sm" style="font-size: 2rem; margin-bottom: 8px; color: white;">iPad Pro</h3>
                <p class="body-md" style="color: rgba(255,255,255,0.7);">Đỉnh cao sáng tạo.</p>
            </div>
        </a>
        <a href="/collection?categories_id=3" class="bento-item liquid-glass animate-fade-up delay-3" style="grid-column: span 8; grid-row: span 1; background: #000; overflow: hidden; padding: 0;">
            <img src="{{ asset('storage/categoryimg/macbook.jpg') }}" style="position: absolute; width: 100%; height: 120%; top: 50%; left: 50%; transform: translate(-50%, -50%); object-fit: cover; opacity: 1;">
            <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, transparent 60%); z-index: 1;"></div>
            <div style="z-index: 2; position: absolute; bottom: 40px; left: 40px;">
                <h3 class="headline-sm" style="font-size: 2rem; margin-bottom: 8px; color: white;">MacBook Pro</h3>
                <p class="body-md" style="color: rgba(255,255,255,0.7);">Sức mạnh bứt phá mọi giới hạn.</p>
            </div>
        </a>
    </div>

    {{-- Featured Section --}}
    <div id="featured" style="margin-bottom: 120px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 64px;">
            <div class="animate-fade-up">
                <h2 class="display-lg" style="font-size: 3rem; margin-bottom: 16px; white-space: nowrap;">Sản phẩm Tiêu biểu.</h2>
                <p class="body-lg">Những lựa chọn hoàn hảo nhất cho hệ sinh thái của bạn.</p>
            </div>
            <a href="/products" class="btn btn-secondary animate-fade-up delay-1" style="border-radius: 100px; padding: 14px 32px;">Xem tất cả</a>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 32px;">
            @foreach($featuredProducts as $name => $product)
                <div class="animate-fade-up" style="transition-delay: {{ $loop->index * 0.1 }}s;">
                    <div class="product-card" data-tilt data-tilt-max="10" data-tilt-speed="1000" data-tilt-perspective="2000">
                        <div class="glow"></div>
                        <a href="/products/{{ $product->slug }}" style="text-decoration: none; display: block; flex: 1; z-index: 1; position: relative;">
                            <div style="border-radius: 20px; overflow: hidden; margin-bottom: 32px; height: 280px; background: white; display: flex; align-items: center; justify-content: center; padding: 32px; box-shadow: inset 0 0 20px rgba(0,0,0,0.05);">
                                @php
                                    $imageUrl = $product->getFirstMediaUrl('thumbnail');
                                    if (!$imageUrl) {
                                        $fallbackMap = [
                                            'iPhone 17 Pro Max' => asset('storage/categoryimg/iphone.jpeg'),
                                            'MacBook Pro M5' => asset('storage/categoryimg/macbook.jpg'),
                                            'iPad Pro 11-inch' => asset('storage/categoryimg/ipad.jpg'),
                                            'AirPods Pro 2' => asset('storage/categoryimg/airpod.jpg'),
                                        ];
                                        $imageUrl = $fallbackMap[$name] ?? asset('storage/hero.png');
                                    }
                                @endphp
                                <img src="{{ $imageUrl }}" style="max-width: 100%; max-height: 100%; object-fit: contain; transform: translateZ(50px);">
                            </div>
                            <h3 style="font-size: 1.75rem; color: white; margin-bottom: 12px; font-weight: 800;">{{ $product->name }}</h3>
                            <p style="color: var(--on-surface-variant); margin-bottom: 32px; line-height: 1.6; font-size: 1rem;">{{ $product->desc }}</p>
                        </a>
                        
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: auto; z-index: 1;">
                            <span style="font-size: 1.5rem; font-weight: 800; color: white;">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                            <button onclick="addToCart({{ $product->id }})" class="btn btn-primary" style="width: 54px; height: 54px; border-radius: 16px; padding: 0;"><i class="ri-add-line" style="font-size: 1.5rem;"></i></button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- 2026 Standards Section (New Replaced Section) --}}
    <div id="standards" style="margin-top: 160px; padding-top: 80px; border-top: 1px solid rgba(255,255,255,0.05);">
        <div style="text-align: center; margin-bottom: 100px;">
            <h2 class="display-lg animate-fade-up" style="margin-bottom: 24px; font-weight: 900;">Chuẩn Mực <span class="text-metallic">iShop</span></h2>
            <p class="body-lg animate-fade-up delay-1" style="max-width: 700px; margin: 0 auto;">Chúng tôi không chỉ bán thiết bị, chúng tôi định nghĩa lại cách bạn tương tác với tương lai thông qua 3 giá trị cốt lõi.</p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 40px;">
            {{-- Standard 1 --}}
            <div class="animate-fade-up" style="text-align: left; padding: 48px; border-radius: 40px; background: linear-gradient(135deg, rgba(255,255,255,0.03) 0%, transparent 100%); border: 1px solid rgba(255,255,255,0.05);">
                <div style="width: 64px; height: 64px; background: white; border-radius: 20px; display: flex; align-items: center; justify-content: center; margin-bottom: 32px;">
                    <i class="ri-gemini-fill" style="color: black; font-size: 2rem;"></i>
                </div>
                <h3 class="headline-sm" style="margin-bottom: 16px; font-size: 1.5rem;">Chất Lượng Tối Thượng</h3>
                <p class="body-md" style="line-height: 1.8;">Mọi thiết bị đều được tuyển chọn và kiểm định nghiêm ngặt qua 21 bước tiêu chuẩn quốc tế, đảm bảo sự hoàn hảo tuyệt đối từ phần cứng đến phần mềm.</p>
            </div>
            {{-- Standard 2 --}}
            <div class="animate-fade-up delay-1" style="text-align: left; padding: 48px; border-radius: 40px; background: linear-gradient(135deg, rgba(255,255,255,0.03) 0%, transparent 100%); border: 1px solid rgba(255,255,255,0.05);">
                <div style="width: 64px; height: 64px; background: white; border-radius: 20px; display: flex; align-items: center; justify-content: center; margin-bottom: 32px;">
                    <i class="ri-speed-up-fill" style="color: black; font-size: 2rem;"></i>
                </div>
                <h3 class="headline-sm" style="margin-bottom: 16px; font-size: 1.5rem;">Dịch Vụ Chuyên Cơ</h3>
                <p class="body-md" style="line-height: 1.8;">Giao hàng siêu tốc trong 2 giờ tại nội thành. Đội ngũ kỹ thuật viên riêng biệt luôn sẵn sàng hỗ trợ tận nơi cho mọi vấn đề của khách hàng VIP.</p>
            </div>
            {{-- Standard 3 --}}
            <div class="animate-fade-up delay-2" style="text-align: left; padding: 48px; border-radius: 40px; background: linear-gradient(135deg, rgba(255,255,255,0.03) 0%, transparent 100%); border: 1px solid rgba(255,255,255,0.05);">
                <div style="width: 64px; height: 64px; background: white; border-radius: 20px; display: flex; align-items: center; justify-content: center; margin-bottom: 32px;">
                    <i class="ri-vip-crown-2-fill" style="color: black; font-size: 2rem;"></i>
                </div>
                <h3 class="headline-sm" style="margin-bottom: 16px; font-size: 1.5rem;">Đặc Quyền Thành Viên</h3>
                <p class="body-md" style="line-height: 1.8;">Tham gia vào cộng đồng Obsidian Club, nhận đặc quyền sở hữu các phiên bản giới hạn (Limited Edition) và thư mời tham dự các sự kiện công nghệ kín.</p>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.waves.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Vanta Waves
        VANTA.WAVES({
            el: "#hero-vanta",
            mouseControls: true,
            touchControls: true,
            gyroControls: false,
            minHeight: 200.00,
            minWidth: 200.00,
            scale: 1.00,
            scaleMobile: 1.00,
            color: 0x050505,          // Deep obsidian black
            shininess: 35.00,        // High polish shine
            waveHeight: 25.00,       // Prominent waves
            waveSpeed: 0.40,         // Slow, cinematic movement
            zoom: 0.65               // Wide view
        });

        // Initialize Vanilla Tilt
        VanillaTilt.init(document.querySelectorAll(".product-card"), {
            max: 10,
            speed: 400,
            glare: true,
            "max-glare": 0.2,
            perspective: 1500,
        });

        // GSAP Scroll Animations
        gsap.registerPlugin(ScrollTrigger);

        // Bento Grid Parallax
        gsap.utils.toArray('.bento-item img').forEach(img => {
            gsap.to(img, {
                y: -50,
                rotate: 0,
                scrollTrigger: {
                    trigger: img,
                    start: "top bottom",
                    end: "bottom top",
                    scrub: 1
                }
            });
        });

        // Standards Section Hover Effects
        const cards = document.querySelectorAll('#standards > div:last-child > div');
        cards.forEach(card => {
            card.addEventListener('mouseenter', () => {
                gsap.to(card, { y: -10, borderColor: 'rgba(255,255,255,0.2)', duration: 0.4 });
            });
            card.addEventListener('mouseleave', () => {
                gsap.to(card, { y: 0, borderColor: 'rgba(255,255,255,0.05)', duration: 0.4 });
            });
        });
    });
</script>
@endpush

@endsection
