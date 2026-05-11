@extends('layouts.app')
@section('title', 'Danh Mục Sản Phẩm')
@section('content')
<div style="padding: 0 40px; margin-top: 40px; width: 100%;">
    <div class="animate-fade-up" style="margin-bottom: 40px; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 24px;">
        <div style="text-align: left;">
            @if(request('search'))
                <h1 class="display-lg" style="margin-bottom: 16px;">Kết Quả Tìm Kiếm</h1>
                <p class="body-lg" style="color: var(--on-surface-variant);">Tìm thấy {{ $products->total() }} kết quả cho "{{ request('search') }}"</p>
            @else
                <h1 class="display-lg" style="margin-bottom: 16px;">Bộ Sưu Tập</h1>
                <p class="body-lg" style="color: var(--on-surface-variant);">Khám phá danh sách các thiết bị cao cấp được tuyển chọn cẩn thận.</p>
            @endif
        </div>

        <div style="display: flex; gap: 16px; align-items: center; width: 100%; max-width: 600px; justify-content: flex-end;">
            <form action="/products" method="GET" id="sortForm">
                @foreach(request()->except('sort', 'page') as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach
                <select name="sort" onchange="document.getElementById('sortForm').submit()" style="padding: 14px 24px; border-radius: 100px; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.1); color: white; outline: none; cursor: pointer; font-size: 0.95rem;">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Mới nhất</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá: Thấp đến Cao</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá: Cao đến Thấp</option>
                </select>
            </form>

            <form action="/products" method="GET" style="position: relative; width: 100%; max-width: 320px;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm kiếm sản phẩm..." style="width: 100%; padding: 14px 20px 14px 48px; border-radius: 100px; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.1); color: white; outline: none; transition: all 0.3s; font-size: 0.95rem; box-shadow: inset 0 2px 10px rgba(0,0,0,0.2);" onfocus="this.style.background='rgba(255,255,255,0.08)'; this.style.borderColor='rgba(255,255,255,0.3)'; this.style.boxShadow='0 0 20px rgba(255,255,255,0.05)';" onblur="this.style.background='rgba(255,255,255,0.03)'; this.style.borderColor='rgba(255,255,255,0.1)'; this.style.boxShadow='inset 0 2px 10px rgba(0,0,0,0.2)';">
                <i class="ri-search-line" style="position: absolute; left: 20px; top: 50%; transform: translateY(-50%); color: var(--on-surface-variant); font-size: 1.15rem;"></i>
                @if(request('categories_id'))
                    <input type="hidden" name="categories_id" value="{{ request('categories_id') }}">
                @endif
                @if(request('sort'))
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                @endif
                <button type="submit" style="display: none;"></button>
            </form>
        </div>
    </div>

    <div style="display: flex; gap: 40px; align-items: flex-start; margin-bottom: 80px;">
        <!-- Sidebar -->
        <div class="animate-fade-up delay-1" style="width: 280px; flex-shrink: 0; position: sticky; top: 120px; display: flex; flex-direction: column; gap: 24px;">
            {{-- Category Filter --}}
            <div style="background: rgba(255,255,255,0.02); padding: 32px; border-radius: 24px; border: 1px solid rgba(255,255,255,0.05); backdrop-filter: blur(20px);">
                <h3 class="headline-sm" style="margin-bottom: 24px; font-size: 1.25rem;">Danh Mục</h3>
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <a href="{{ url()->current() . '?' . http_build_query(request()->except('categories_id', 'page')) }}" style="padding: 12px 16px; border-radius: 12px; font-size: 0.95rem; font-weight: 600; text-decoration: none; transition: all 0.3s; {{ !request('categories_id') ? 'background: rgba(255,255,255,0.1); color: white;' : 'background: transparent; color: var(--on-surface-variant);' }}">
                        Tất Cả
                    </a>
                    @foreach($categories as $category)
                        <a href="{{ url()->current() . '?' . http_build_query(array_merge(request()->all(), ['categories_id' => $category->id])) }}" style="padding: 12px 16px; border-radius: 12px; font-size: 0.95rem; font-weight: 600; text-decoration: none; transition: all 0.3s; {{ request('categories_id') == $category->id ? 'background: rgba(255,255,255,0.1); color: white;' : 'background: transparent; color: var(--on-surface-variant);' }}">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Price Filter --}}
            <div style="background: rgba(255,255,255,0.02); padding: 32px; border-radius: 24px; border: 1px solid rgba(255,255,255,0.05); backdrop-filter: blur(20px);">
                <h3 class="headline-sm" style="margin-bottom: 24px; font-size: 1.25rem;">Khoảng Giá</h3>
                <form action="/products" method="GET" style="display: flex; flex-direction: column; gap: 16px;">
                    @foreach(request()->except('min_price', 'max_price', 'page') as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Từ (₫)" style="width: 100%; padding: 12px 16px; border-radius: 12px; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.1); color: white; outline: none; font-size: 0.9rem;">
                        <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Đến (₫)" style="width: 100%; padding: 12px 16px; border-radius: 12px; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.1); color: white; outline: none; font-size: 0.9rem;">
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; border-radius: 12px; font-weight: 700;">Áp dụng</button>
                    @if(request('min_price') || request('max_price'))
                        <a href="{{ url()->current() . '?' . http_build_query(request()->except('min_price', 'max_price', 'page')) }}" style="text-align: center; color: var(--on-surface-variant); font-size: 0.85rem; text-decoration: none;">Xóa lọc giá</a>
                    @endif
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div style="flex: 1;">
            @if($products->isEmpty())
                <div class="glass-panel animate-fade-up delay-2" style="text-align:center; padding:80px 24px;">
                    <div style="font-size: 3rem; color: var(--on-surface-variant); margin-bottom: 24px;"><i class="ri-search-eye-line"></i></div>
                    <p style="font-size:1.2rem; color:var(--on-surface-variant); margin-bottom:32px;">Không tìm thấy sản phẩm nào phù hợp.</p>
                    <a href="/products" class="btn btn-primary" style="padding:12px 32px; border-radius:12px; text-decoration:none; font-weight:700;">Xem Tất Cả Sản Phẩm</a>
                </div>
            @else
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 32px;">
                    @foreach($products as $index => $product)
                        <div class="animate-fade-up" style="transition-delay: {{ ($index % 3) * 0.1 }}s;">
                            <div class="product-card" data-tilt data-tilt-max="10" data-tilt-speed="1000" data-tilt-perspective="2000" style="border-radius: 32px; background: #0f0f0f; padding: 32px; border: 1px solid rgba(255,255,255,0.03); transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1); transform-style: preserve-3d; height: 100%; display: flex; flex-direction: column; position: relative; overflow: hidden;">
                                <div class="glow" style="position: absolute; width: 150px; height: 150px; background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 70%); border-radius: 50%; filter: blur(20px); opacity: 0; transition: opacity 0.5s; pointer-events: none; z-index: 0;"></div>
                                <a href="/products/{{ $product->slug }}" style="text-decoration: none; color: inherit; display: block; flex: 1; z-index: 1; position: relative;">
                                    <div style="border-radius: 20px; overflow: hidden; margin-bottom: 32px; height: 240px; background: white; display: flex; align-items: center; justify-content: center; padding: 24px; box-shadow: inset 0 0 20px rgba(0,0,0,0.05);">
                                        @php
                                            $thumbnailUrl = $product->getFirstMediaUrl('thumbnail');
                                            if (!$thumbnailUrl && $product->img) {
                                                $thumbnailUrl = asset('storage/' . $product->img);
                                            }
                                        @endphp

                                        @if($thumbnailUrl)
                                            <img src="{{ $thumbnailUrl }}" alt="{{ $product->name }}" style="max-width: 100%; max-height: 100%; object-fit: contain; transform: translateZ(30px);">
                                        @else
                                            <div style="color: var(--surface-container-highest);">Không có ảnh</div>
                                        @endif
                                    </div>
                                    <h3 style="font-size: 1.5rem; color: white; margin-bottom: 12px; font-weight: 800;">{{ $product->name }}</h3>
                                    <p style="color: var(--on-surface-variant); margin-bottom: 24px; font-size: 0.95rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.6;">{{ $product->desc }}</p>
                                </a>
                                
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: auto; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 24px; z-index: 1; position: relative;">
                                    <span style="font-size: 1.25rem; font-weight: 800; color: white;">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                                    <button onclick="addToCart({{ $product->id }})" class="btn btn-primary" style="width: 48px; height: 48px; border-radius: 12px; padding: 0;"><i class="ri-shopping-cart-2-line" style="font-size: 1.25rem;"></i></button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="animate-fade-up delay-2" style="margin-top: 80px; width: 100%; display: flex; justify-content: center;">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        VanillaTilt.init(document.querySelectorAll(".product-card"), {
            max: 10,
            speed: 400,
            glare: true,
            "max-glare": 0.2,
            perspective: 1500,
        });
    });
</script>
@endpush
@endsection
