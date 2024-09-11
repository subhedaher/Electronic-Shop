<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, OrderProducts::class,  'order_id', 'product_id');
    }

    public function order_detailes()
    {
        return $this->hasMany(OrderProducts::class,  'order_id', 'id');
    }


    public function shipping_address()
    {
        return $this->hasOne(ShippingAddress::class, 'order_id', 'id');
    }
}
