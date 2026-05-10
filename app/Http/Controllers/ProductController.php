<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Review;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('desc', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('categories_id')) {
            $query->where('categories_id', $request->categories_id);
        }

        if ($request->filled('category')) {
            $categoryName = $request->category;
            $query->whereHas('category', function($q) use ($categoryName) {
                $q->where('name', 'LIKE', "%{$categoryName}%");
            });
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }

    public function show($slug)
    {
        $product = Product::with(['category', 'reviews.user'])->where('slug', $slug)->firstOrFail();

        return view('products.show', compact('product'));
    }

    public function review(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        Review::create([
            'users_id' => auth()->id(),
            'products_id' => $id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->back()->with('success', 'Đánh giá của bạn đã được gửi!');
    }
}
