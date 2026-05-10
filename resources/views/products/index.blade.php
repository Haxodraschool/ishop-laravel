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

        <form action="/products" method="GET" style="position: relative; width: 100%; max-width: 320px;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm kiếm sản phẩm..." style="width: 100%; padding: 14px 20px 14px 48px; border-radius: 100px; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.1); color: white; outline: none; transition: all 0.3s; font-size: 0.95rem; box-shadow: inset 0 2px 10px rgba(0,0,0,0.2);" onfocus="this.style.background='rgba(255,255,255,0.08)'; this.style.borderColor='rgba(255,255,255,0.3)'; this.style.boxShadow='0 0 20px rgba(255,255,255,0.05)';" onblur="this.style.background='rgba(255,255,255,0.03)'; this.style.borderColor='rgba(255,255,255,0.1)'; this.style.boxShadow='inset 0 2px 10px rgba(0,0,0,0.2)';">
            <i class="ri-search-line" style="position: absolute; left: 20px; top: 50%; transform: translateY(-50%); color: var(--on-surface-variant); font-size: 1.15rem;"></i>
            @if(request('categories_id'))
                <input type="hidden" name="categories_id" value="{{ request('categories_id') }}">
            @endif
            <button type="submit" style="display: none;"></button>
        </form>
    </div>

    <div style="display: flex; gap: 40px; align-items: flex-start; margin-bottom: 80px;">
        <!-- Sidebar -->
        <div class="animate-fade-up delay-1" style="width: 260px; flex-shrink: 0; position: sticky; top: 120px; background: rgba(255,255,255,0.02); padding: 32px; border-radius: 24px; border: 1px solid rgba(255,255,255,0.05); backdrop-filter: blur(20px);">
            <h3 class="headline-sm" style="margin-bottom: 24px; font-size: 1.25rem;">Danh Mục</h3>
            <div style="display: flex; flex-direction: column; gap: 8px;">
                <a href="/products" style="padding: 12px 16px; border-radius: 12px; font-size: 0.95rem; font-weight: 600; text-decoration: none; transition: all 0.3s; {{ !request('categories_id') ? 'background: rgba(255,255,255,0.1); color: white;' : 'background: transparent; color: var(--on-surface-variant);' }}" onmouseover="if({{ request('categories_id') ? 'true' : 'false' }}) this.style.color='white'" onmouseout="if({{ request('categories_id') ? 'true' : 'false' }}) this.style.color='var(--on-surface-variant)'">
                    Tất Cả
                </a>
                @foreach($categories as $category)
                    <a href="/products?categories_id={{ $category->id }}" style="padding: 12px 16px; border-radius: 12px; font-size: 0.95rem; font-weight: 600; text-decoration: none; transition: all 0.3s; {{ request('categories_id') == $category->id ? 'background: rgba(255,255,255,0.1); color: white;' : 'background: transparent; color: var(--on-surface-variant);' }}" onmouseover="if({{ request('categories_id') != $category->id ? 'true' : 'false' }}) this.style.color='white'" onmouseout="if({{ request('categories_id') != $category->id ? 'true' : 'false' }}) this.style.color='var(--on-surface-variant)'">
                        {{ $category->name }}
                    </a>
                @endforeach
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
                        <div class="glass-panel animate-fade-up" style="padding: 24px; display: flex; flex-direction: column; transition-delay: {{ ($index % 4) * 0.1 }}s;">
                            <a href="/products/{{ $product->slug }}" style="text-decoration: none; color: inherit; display: block; flex: 1;">
                                <div style="border-radius: 16px; overflow: hidden; margin-bottom: 24px; height: 240px; background: white; display: flex; align-items: center; justify-content: center; padding: 20px;">
                                    @if($product->img)
                                        <img src="{{ asset('storage/' . $product->img) }}" alt="{{ $product->name }}" style="max-width: 100%; max-height: 100%; object-fit: contain; transition: transform 0.5s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                    @else
                                        <div style="color: var(--surface-container-highest);">Không có ảnh</div>
                                    @endif
                                </div>
                                <h3 style="font-size: 1.5rem; color: var(--primary); margin-bottom: 8px; font-weight: 700;">{{ $product->name }}</h3>
                                <p style="color: var(--on-surface-variant); margin-bottom: 16px; font-size: 0.95rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ $product->desc }}</p>
                            </a>
                            
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: auto; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 16px;">
                                <span style="font-size: 1.25rem; font-weight: 700; color: var(--primary);">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                                <button onclick="addToCart({{ $product->id }})" class="btn btn-primary" style="padding: 8px 16px; border-radius: 8px;"><i class="ri-shopping-cart-2-line"></i></button>
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
@endsection
