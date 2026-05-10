@extends('layouts.app')

@section('title', 'Danh sách yêu thích')

@push('styles')
<style>
    .page-title {
        font-size: 22px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 24px;
    }

    .wishlist-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 32px;
    }

    @media (max-width: 1024px) {
        .wishlist-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 600px) {
        .wishlist-grid { grid-template-columns: 1fr; }
    }

    .product-card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: box-shadow 0.2s, transform 0.2s;
    }

    .product-card:hover {
        box-shadow: 0 4px 16px rgba(0,0,0,0.15);
        transform: translateY(-2px);
    }

    .product-card-img-placeholder {
        width: 100%;
        height: 200px;
        background: #f0f0f0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #aaa;
        font-size: 40px;
    }

    .product-card-body {
        padding: 12px;
        display: flex;
        flex-direction: column;
        flex: 1;
        gap: 6px;
    }

    .product-card-name {
        font-weight: 600;
        font-size: 15px;
        color: #333;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .product-card-price {
        color: var(--primary);
        font-weight: 700;
        font-size: 16px;
        margin-top: 4px;
    }

    .btn-detail {
        display: block;
        padding: 8px 12px;
        background: var(--primary);
        color: #fff;
        text-align: center;
        text-decoration: none;
        border-radius: 4px;
        font-size: 13px;
        font-weight: 600;
        transition: background 0.2s;
        margin-top: 8px;
    }

    .btn-detail:hover { background: #e0641a; }

    .btn-remove {
        display: block;
        width: 100%;
        padding: 8px 12px;
        background: #fff;
        color: var(--accent);
        border: 1px solid var(--accent);
        border-radius: 4px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s, color 0.2s;
        margin-top: 6px;
    }

    .btn-remove:hover {
        background: var(--accent);
        color: #fff;
    }

    .empty-state {
        text-align: center;
        padding: 60px 24px;
        color: #888;
        font-size: 16px;
    }

    .empty-state a {
        color: var(--primary);
        text-decoration: none;
        font-weight: 600;
    }

    .empty-state a:hover { text-decoration: underline; }
</style>
@endpush

@section('content')
    <div class="page-title">❤️ Danh sách yêu thích</div>

    @if($wishlists->isEmpty())
        <div class="empty-state">
            <div style="font-size:48px;margin-bottom:16px;">💔</div>
            <p>Bạn chưa có sản phẩm yêu thích nào.</p>
            <p style="margin-top:8px;"><a href="/products">Khám phá sản phẩm ngay</a></p>
        </div>
    @else
        <div class="wishlist-grid">
            @foreach($wishlists as $wishlist)
                @php $product = $wishlist->product; @endphp
                <div class="product-card">
                    @if($product->img)
                        <img src="{{ asset('storage/' . $product->img) }}" alt="{{ $product->name }}" style="width:100%;height:200px;object-fit:cover;">
                    @else
                        <div class="product-card-img-placeholder">🖼️</div>
                    @endif

                    <div class="product-card-body">
                        <div class="product-card-name">{{ $product->name }}</div>
                        <div class="product-card-price">{{ number_format($product->price, 0, ',', '.') }} ₫</div>
                        <a href="/products/{{ $product->id }}" class="btn-detail">Xem chi tiết</a>
                        <form action="/wishlist/remove" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit" class="btn-remove">🗑 Xóa khỏi yêu thích</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
