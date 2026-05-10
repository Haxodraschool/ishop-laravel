<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['img', 'name', 'slug', 'desc', 'price', 'categories_id'];

    /**
     * Định nghĩa các collection ảnh cho sản phẩm.
     */
    public function registerMediaCollections(): void
    {
        // Ảnh đại diện — chỉ 1 file
        $this->addMediaCollection('thumbnail')->singleFile();

        // Ảnh gallery — nhiều file
        $this->addMediaCollection('gallery');
    }

    /**
     * Tự động tạo các phiên bản ảnh resize.
     */
    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
             ->width(400)
             ->height(400)
             ->sharpen(10);

        $this->addMediaConversion('card')
             ->width(800)
             ->height(600);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'categories_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'products_id');
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class, 'products_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'products_id');
    }
}
