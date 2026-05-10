@extends('layouts.admin')

@section('title', 'Danh mục')

@section('content')
<div class="page-header">
    <h1>Danh mục</h1>
    <a href="/admin/categories/create" class="btn-primary">+ Thêm danh mục</a>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>Tên</th>
                <th>Mô tả</th>
                <th>Số sản phẩm</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $category)
            <tr>
                <td style="color:#fff;font-weight:500;">{{ $category->name }}</td>
                <td>{{ $category->desc ?? '—' }}</td>
                <td>{{ $category->products_count }}</td>
                <td>
                    <div style="display:flex;gap:8px;">
                        <a href="/admin/categories/{{ $category->id }}/edit"
                           style="background:rgba(245,114,36,0.15);color:#f57224;border:1px solid rgba(245,114,36,0.3);padding:6px 14px;border-radius:6px;font-size:13px;font-weight:500;text-decoration:none;">
                            Sửa
                        </a>
                        <form action="/admin/categories/{{ $category->id }}" method="POST"
                              onsubmit="return confirm('Bạn có chắc muốn xóa danh mục này?')">
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
                <td colspan="4" style="text-align:center;padding:32px;color:#555570;">Chưa có danh mục nào.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
