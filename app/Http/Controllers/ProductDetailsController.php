<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\FavoriteProduct;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\RatingProducts;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductDetailsController extends Controller
{
    public function index(Product $product)
    {

        $avg = RatingProducts::where('product_id', '=', $product->id)->avg('rating');
        $reviews1 = RatingProducts::where('product_id', '=', $product->id)->where('rating', '=', 1)->count();
        $reviews2 = RatingProducts::where('product_id', '=', $product->id)->where('rating', '=', 2)->count();
        $reviews3 = RatingProducts::where('product_id', '=', $product->id)->where('rating', '=', 3)->count();
        $reviews4 = RatingProducts::where('product_id', '=', $product->id)->where('rating', '=', 4)->count();
        $reviews5 = RatingProducts::where('product_id', '=', $product->id)->where('rating', '=', 5)->count();

        $reviews = RatingProducts::with('user')->where('product_id', '=', $product->id)->get();
        $countCarts = Cart::where('user_id', '=', auth('user')->user()->id ?? 0)->count();
        $countv = FavoriteProduct::where('user_id', '=', auth('user')->user()->id ?? 0)->where('product_id', '=', $product->id)->count();
        $countProductsFavorite = FavoriteProduct::where('user_id', '=', auth('user')->user()->id ?? 0)->count();
        $categories = Category::with('products')->where('active', '=', true)->get();
        $images = ProductImage::where('product_id', '=', $product->id)->get();
        return view('product-details', ['avg' => $avg, 'reviews1' => $reviews1, 'reviews2' => $reviews2, 'reviews3' => $reviews3, 'reviews4' => $reviews4, 'reviews5' => $reviews5, 'reviews' => $reviews, 'countCarts' => $countCarts, 'countv' => $countv, 'product' => $product, 'images' => $images, 'categories' => $categories, 'countProductsFavorite' => $countProductsFavorite]);
    }

    public function rating(Request $request, $id)
    {
        $validator = Validator($request->all(), [
            'subject' => 'required|string|min:3',
            'rating' => 'required|numeric|min:1|max:5',
            'review' => 'required|string|min:3',
        ]);

        if (!$validator->fails()) {
            $product_id = $id;
            $user_id = auth('user')->user()->id;
            $rating_product = RatingProducts::where('user_id', '=', $user_id)->where('product_id', '=', $product_id)->first();
            if ($rating_product == null) {
                $new_rating_product = new RatingProducts();
                $new_rating_product->user_id = $user_id;
                $new_rating_product->product_id = $product_id;
                $new_rating_product->subject = $request->input('subject');
                $new_rating_product->rating = $request->input('rating');
                $new_rating_product->review = $request->input('review');
                $new_rating_product->save();
            } else {
                $rating_product->subject = $request->input('subject');
                $rating_product->rating = $request->input('rating');
                $rating_product->review = $request->input('review');
                $rating_product->save();
            }
            return response()->json([
                'status' => true,
                'message' => "Rating Successfully"
            ], Response::HTTP_CREATED);
        } else {
            return response()->json([
                'status' => false,
                'message' => $validator->getMessageBag()->first()
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}