<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\FavoriteProduct;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductShopController extends Controller
{
    public function index(Request $request)
    {

        if ($request->expectsJson()) {
            $categories = $request->input('categories');
            $products = Product::with(['category', 'product_images', 'offer', 'ratings'])->whereIn('category_id', $categories)->get();
            return response()->json($products);
        }

        $countCarts = Cart::where('user_id', '=', auth('user')->user()->id ?? 0)->count();
        $countProductsFavorite = FavoriteProduct::where('user_id', '=', auth('user')->user()->id ?? 0)->count();
        $categories = Category::with('products')->withCount('products')->where('active', '=', true)->get();
        $products = Product::with('product_images')->withAvg('ratings', 'rating')->withCount('ratings')->paginate(9);
        $productsCount50100 = Product::whereBetween('price', [50, 100 - 1])->count();
        $productsCount100500 = Product::whereBetween('price', [100, 500 - 1])->count();
        $productsCount5001000 = Product::whereBetween('price', [500, 1000 - 1])->count();
        $productsCount10005000 = Product::whereBetween('price', [1000, 5000 - 1])->count();
        return view('products', [
            'products' => $products,
            'categories' => $categories,
            'productsCount50100' => $productsCount50100,
            'productsCount100500' => $productsCount100500,
            'productsCount5001000' => $productsCount5001000,
            'productsCount10005000' => $productsCount10005000,
            'countProductsFavorite' => $countProductsFavorite,
            'countCarts' => $countCarts
        ]);
    }
}
