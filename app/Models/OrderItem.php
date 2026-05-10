<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = ['products_id', 'amount', 'orders_id'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'products_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'orders_id');
    }
}
