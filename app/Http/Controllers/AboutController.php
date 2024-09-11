<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\FavoriteProduct;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index()
    {
        $countCarts = Cart::where('user_id', '=', auth('user')->user()->id ?? 0)->count();
        $countProductsFavorite = FavoriteProduct::where('user_id', '=', auth('user')->user()->id ?? 0)->count();
        $categories = Category::where('active', '=', true)->get();
        return view('about', ['countCarts' => $countCarts, 'categories' => $categories, 'countProductsFavorite' => $countProductsFavorite]);
    }
}
