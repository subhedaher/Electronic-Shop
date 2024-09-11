<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id', 'id');
    }

    public function product_images()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'id');
    }

    public function offer()
    {
        return $this->hasOne(Offer::class, 'product_id', 'id');
    }

    public function favoredByUsers()
    {
        return $this->hasMany(FavoriteProduct::class, 'product_id', 'id');
    }

    public function carts()
    {
        return $this->hasMany(Cart::class, 'product_id', 'id');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, OrderProducts::class, 'product_id', 'order_id',);
    }

    public function ratings()
    {
        return $this->hasMany(RatingProducts::class, 'product_id', 'id');
    }
}
