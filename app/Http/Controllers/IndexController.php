<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\FavoriteProduct;
use App\Models\Offer;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends Controller
{
    public function index()
    {
        $countCarts = Cart::where('user_id', '=', auth('user')->user()->id ?? 0)->count();
        $countProductsFavorite = FavoriteProduct::where('user_id', '=', auth('user')->user()->id ?? 0)->count();
        $categories = Category::with('products')->where('active', '=', true)->get();
        $products = Product::with('product_images', 'offer')->withAvg('ratings', 'rating')->withCount('ratings')->take(8)->get();

        $offers = Offer::where('active', '=', true)->with(['product' => function ($quere) {
            $quere->select('id', 'created_at', 'name', 'category_id', 'slug', 'price');
        }, 'product.ratings' => function ($quere) {
            $quere->select('product_id', DB::raw("AVG(rating) as avg_rating"), DB::raw("COUNT(id) as count_rating"))->GROUPBY('product_id');
        }])->take(4)->get();
        $bestSellers = Product::withCount('orders')->withCount(['orders as total_sales' => function ($query) {
            $query->select(DB::raw("SUM(quantity)"));
        }])->orderBy('total_sales', 'desc')->take(3)->get();

        $new_arrivals = Product::orderBy('created_at', 'desc')->take(3)->get();

        $top_rated = Product::withCount('ratings')->withCount(['ratings as total_rating' => function ($query) {
            $query->select(DB::raw("SUM(rating)"));
        }])->orderBy('total_rating', 'desc')->take(3)->get();

        $popular_brands = Product::withCount('ratings')->withCount(['ratings as total_rating' => function ($query) {
            $query->select(DB::raw("SUM(rating)"));
        }])->orderBy('total_rating', 'desc')->withCount('orders')->withCount(['orders as total_sales' => function ($query) {
            $query->select(DB::raw("SUM(quantity)"));
        }])->orderBy('total_sales', 'desc')->take(8)->get();

        return view('index', ['popular_brands' => $popular_brands, 'top_rated' => $top_rated, 'new_arrivals' => $new_arrivals, 'bestSellers' => $bestSellers, 'countCarts' => $countCarts, 'categories' => $categories, 'products' => $products, 'offers' => $offers, 'countProductsFavorite' => $countProductsFavorite]);
    }

    public function search(Request $request)
    {
        $validator = validator($request->all(), [
            'name' => 'required'
        ]);

        if (!$validator->fails()) {
            $product = Product::where('name', 'like',  '%' . $request->input('name') . '%')->first();
            if ($product != null) {
                return response()->json([
                    'status' => true,
                    'message' => route('product', $product->slug)
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'no product'
                ], Response::HTTP_BAD_REQUEST);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => $validator->getMessageBag()->first()
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
