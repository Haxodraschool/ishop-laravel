<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\UserController as AdminUserController;

// Healthcheck for Railway/Render
Route::get('/health', function () {
    return response('OK', 200);
});

Route::get('/debug-images', function () {
    $products = \App\Models\Product::all();
    $debug = [];
    foreach ($products as $product) {
        $media = $product->getFirstMedia('thumbnail');
        $debug[] = [
            'name' => $product->name,
            'media_id' => $media ? $media->id : 'NONE',
            'url' => $product->getFirstMediaUrl('thumbnail'),
            'path' => $media ? $media->getPath() : 'N/A',
            'exists' => $media ? (file_exists($media->getPath()) ? 'YES' : 'NO') : 'N/A',
            'public_path' => $media ? public_path('storage/' . $media->id . '/' . $media->file_name) : 'N/A',
            'public_exists' => $media ? (file_exists(public_path('storage/' . $media->id . '/' . $media->file_name)) ? 'YES' : 'NO') : 'N/A',
        ];
    }
    return response()->json($debug);
});

// Public
Route::get('/rebuild-images', function () {
    try {
        $output = new \Symfony\Component\Console\Output\BufferedOutput();
        
        \Illuminate\Support\Facades\Artisan::call('migrate:fresh', [
            '--seed' => true,
            '--force' => true,
        ], $output);
        
        $log = $output->fetch();
        return "✨ Thành công! Hệ thống đã tái cấu trúc xong.<br><pre>" . $log . "</pre><br><a href='/'>Quay về trang chủ</a>";
    } catch (\Exception $e) {
        return "❌ Lỗi: " . $e->getMessage();
    }
});
// Public
Route::get('/', [HomeController::class, 'index']);
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/collection', [ProductController::class, 'index'])->name('collection');
Route::get('/products/{slug}', [ProductController::class, 'show']);


// Auth routes
Route::get('/login', [AuthController::class, 'loginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::get('/register', [AuthController::class, 'registerForm'])->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

// Cart (public, no auth needed)
Route::get('/cart', [CartController::class, 'index']);
Route::post('/cart/add', [CartController::class, 'add']);
Route::post('/cart/update', [CartController::class, 'update']);
Route::post('/cart/remove', [CartController::class, 'remove']);

// Auth-protected customer routes
Route::middleware('auth')->group(function () {
    Route::post('/products/{id}/review', [ProductController::class, 'review']);
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/create', [OrderController::class, 'create']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::get('/wishlist', [WishlistController::class, 'index']);
    Route::post('/wishlist/add', [WishlistController::class, 'add']);
    Route::post('/wishlist/remove', [WishlistController::class, 'remove']);
});
