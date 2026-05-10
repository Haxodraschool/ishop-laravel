<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->paginate(15);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'desc'          => 'nullable|string',
            'price'         => 'required|numeric|min:0',
            'categories_id' => 'required|exists:categories,id',
            'img'           => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('img')) {
            $validated['img'] = $request->file('img')->store('products', 'public');
        }

        Product::create($validated);

        return redirect('/admin/products')->with('success', 'Thêm sản phẩm thành công!');
    }

    public function edit($id)
    {
        $product    = Product::findOrFail($id);
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'desc'          => 'nullable|string',
            'price'         => 'required|numeric|min:0',
            'categories_id' => 'required|exists:categories,id',
            'img'           => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('img')) {
            $validated['img'] = $request->file('img')->store('products', 'public');
        }

        $product->update($validated);

        return redirect('/admin/products')->with('success', 'Cập nhật sản phẩm thành công!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect('/admin/products')->with('success', 'Xóa sản phẩm thành công!');
    }
}
