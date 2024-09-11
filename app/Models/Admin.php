<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = ['full_name', 'email', 'phone_number', 'address'];

    public function categories()
    {
        return $this->hasMany(Category::class, 'admin_id', 'id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'admin_id', 'id');
    }
}
