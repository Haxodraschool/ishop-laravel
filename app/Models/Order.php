<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'desc', 'users_id', 'status', 'receiver'];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'orders_id');
    }
}
