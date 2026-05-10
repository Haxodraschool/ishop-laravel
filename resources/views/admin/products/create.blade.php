@extends('layouts.admin')

@section('title', 'Thêm sản phẩm')

@section('content')
<div class="page-header">
    <h1>Thêm sản phẩm</h1>
    <a href="/admin/products" style="color:#a2a3b7;text-decoration:none;font-size:14px;">← Quay lại</a>
</div>

<div class="card" style="max-width:640px;">
    @if($errors->any())
        <div class="flash-error" style="margin-bottom:20px;">
            <ul style="margin:0;padding-left:18px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="/admin/products" method="POST" enctype="multipart/form-data">
        @csrf

        <div style="margin-bottom:16px;">
            <label style="display:block;font-size:13px;font-weight:600;color:#a2a3b7;margin-bottom:6px;">Tên sản phẩm *</label>
            <input type="text" name="name" value="{{ old('name') }}"
                   style="background:#1e1e2d;color:#fff;border:1px solid #2d2d3f;padding:9px 12px;border-radius:6px;width:100%;font-size:14px;outline:none;">
        </div>

        <div style="margin-bottom:16px;">
            <label style="display:block;font-size:13px;font-weight:600;color:#a2a3b7;margin-bottom:6px;">Mô tả</label>
            <textarea name="desc" rows="4"
                      style="background:#1e1e2d;color:#fff;border:1px solid #2d2d3f;padding:9px 12px;border-radius:6px;width:100%;font-size:14px;outline:none;resize:vertical;">{{ old('desc') }}</textarea>
        </div>

        <div style="margin-bottom:16px;">
            <label style="display:block;font-size:13px;font-weight:600;color:#a2a3b7;margin-bottom:6px;">Giá (₫) *</label>
            <input type="number" name="price" value="{{ old('price') }}" min="0" step="1000"
                   style="background:#1e1e2d;color:#fff;border:1px solid #2d2d3f;padding:9px 12px;border-radius:6px;width:100%;font-size:14px;outline:none;">
        </div>

        <div style="margin-bottom:16px;">
            <label style="display:block;font-size:13px;font-weight:600;color:#a2a3b7;margin-bottom:6px;">Danh mục *</label>
            <select name="categories_id"
                    style="background:#1e1e2d;color:#fff;border:1px solid #2d2d3f;padding:9px 12px;border-radius:6px;width:100%;font-size:14px;outline:none;">
                <option value="">-- Chọn danh mục --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('categories_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom:24px;">
            <label style="display:block;font-size:13px;font-weight:600;color:#a2a3b7;margin-bottom:6px;">Ảnh sản phẩm</label>
            <input type="file" name="img" accept="image/*"
                   style="background:#1e1e2d;color:#a2a3b7;border:1px solid #2d2d3f;padding:9px 12px;border-radius:6px;width:100%;font-size:14px;outline:none;">
            <p style="font-size:12px;color:#555570;margin-top:4px;">Tối đa 2MB. Định dạng: JPG, PNG, GIF, WEBP.</p>
        </div>

        <div style="display:flex;gap:12px;">
            <button type="submit" class="btn-primary">Thêm sản phẩm</button>
            <a href="/admin/products"
               style="background:rgba(255,255,255,0.05);color:#a2a3b7;border:1px solid #2d2d3f;padding:9px 20px;border-radius:6px;font-size:14px;font-weight:600;text-decoration:none;display:inline-block;">
                Hủy
            </a>
        </div>
    </form>
</div>
@endsection
