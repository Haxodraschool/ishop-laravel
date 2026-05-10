<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories|max:255',
            'desc' => 'nullable',
        ]);

        Category::create($request->only('name', 'desc'));

        return redirect('/admin/categories')->with('success', 'Thêm danh mục thành công!');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => "required|unique:categories,name,{$id}|max:255",
            'desc' => 'nullable',
        ]);

        $category = Category::findOrFail($id);
        $category->update($request->only('name', 'desc'));

        return redirect('/admin/categories')->with('success', 'Cập nhật danh mục thành công!');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        if ($category->products()->count() > 0) {
            return redirect()->back()->with('error', 'Không thể xóa danh mục đang có sản phẩm');
        }

        $category->delete();

        return redirect('/admin/categories')->with('success', 'Xóa danh mục thành công!');
    }
}
