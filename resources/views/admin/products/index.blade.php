@extends('layouts.admin')

@section('title', 'Sản phẩm')

@section('content')
<div class="page-header">
    <h1>Sản phẩm</h1>
    <a href="/admin/products/create" class="btn-primary">+ Thêm sản phẩm</a>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>Ảnh</th>
                <th>Tên sản phẩm</th>
                <th>Giá</th>
                <th>Danh mục</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
            <tr>
                <td>
                    @if($product->img)
                        <img src="{{ asset('storage/' . $product->img) }}"
                             alt="{{ $product->name }}"
                             style="width:48px;height:48px;object-fit:cover;border-radius:6px;">
                    @else
                        <div style="width:48px;height:48px;background:#2d2d3f;border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:20px;">📦</div>
                    @endif
                </td>
                <td style="color:#fff;font-weight:500;">{{ $product->name }}</td>
                <td>{{ number_format($product->price, 0, ',', '.') }}₫</td>
                <td>{{ $product->category->name ?? '—' }}</td>
                <td>
                    <div style="display:flex;gap:8px;">
                        <a href="/admin/products/{{ $product->id }}/edit"
                           style="background:rgba(245,114,36,0.15);color:#f57224;border:1px solid rgba(245,114,36,0.3);padding:6px 14px;border-radius:6px;font-size:13px;font-weight:500;text-decoration:none;">
                            Sửa
                        </a>
                        <form action="/admin/products/{{ $product->id }}" method="POST"
                              onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    style="background:rgba(198,40,40,0.15);color:#ef9a9a;border:1px solid rgba(198,40,40,0.3);padding:6px 14px;border-radius:6px;font-size:13px;font-weight:500;cursor:pointer;">
                                Xóa
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center;padding:32px;color:#555570;">Chưa có sản phẩm nào.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($products->hasPages())
<div style="display:flex;gap:8px;justify-content:center;margin-top:16px;">
    {{ $products->links() }}
</div>
@endif
@endsection
