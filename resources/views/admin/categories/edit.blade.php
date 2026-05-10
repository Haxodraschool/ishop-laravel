@extends('layouts.admin')

@section('title', 'Sửa danh mục')

@section('content')
<div class="page-header">
    <h1>Sửa danh mục</h1>
    <a href="/admin/categories" style="color:#a2a3b7;text-decoration:none;font-size:14px;">← Quay lại</a>
</div>

<div class="card" style="max-width:600px;">
    <form action="/admin/categories/{{ $category->id }}" method="POST">
        @csrf
        @method('PUT')

        <div style="margin-bottom:20px;">
            <label style="display:block;font-size:13px;font-weight:600;color:#a2a3b7;margin-bottom:8px;">
                Tên danh mục <span style="color:#ef9a9a;">*</span>
            </label>
            <input type="text" name="name" value="{{ old('name', $category->name) }}"
                   style="background:#1e1e2d;color:#fff;border:1px solid #2d2d3f;padding:9px 12px;border-radius:6px;width:100%;font-size:14px;outline:none;"
                   placeholder="Nhập tên danh mục">
            @error('name')
                <div style="color:#ef9a9a;font-size:12px;margin-top:6px;">{{ $message }}</div>
            @enderror
        </div>

        <div style="margin-bottom:24px;">
            <label style="display:block;font-size:13px;font-weight:600;color:#a2a3b7;margin-bottom:8px;">
                Mô tả
            </label>
            <textarea name="desc" rows="4"
                      style="background:#1e1e2d;color:#fff;border:1px solid #2d2d3f;padding:9px 12px;border-radius:6px;width:100%;font-size:14px;outline:none;resize:vertical;"
                      placeholder="Nhập mô tả (tuỳ chọn)">{{ old('desc', $category->desc) }}</textarea>
            @error('desc')
                <div style="color:#ef9a9a;font-size:12px;margin-top:6px;">{{ $message }}</div>
            @enderror
        </div>

        <div style="display:flex;gap:12px;">
            <button type="submit" class="btn-primary">Lưu thay đổi</button>
            <a href="/admin/categories"
               style="background:rgba(255,255,255,0.05);color:#a2a3b7;border:1px solid #2d2d3f;padding:9px 20px;border-radius:6px;font-size:14px;font-weight:600;text-decoration:none;">
                Hủy
            </a>
        </div>
    </form>
</div>
@endsection
