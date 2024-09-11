<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\FavoriteProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productsCart = Cart::where('user_id', '=', auth('user')->user()->id)->get();
        $subTotal = 0;
        foreach ($productsCart as $cart) {
            if ($cart->product->offer && $cart->product->offer->active) {
                $discount = floor($cart->product->offer->discount * $cart->product->price * 0.01);
                $subTotal += $cart->quantity * ($cart->product->price - $discount);
            } else {
                $subTotal += $cart->quantity * $cart->product->price;
            }
        }
        $categories = Category::where('active', '=', true)->get();
        $countCarts = Cart::where('user_id', '=', auth('user')->user()->id ?? 0)->count();
        $countProductsFavorite = FavoriteProduct::where('user_id', '=', auth('user')->user()->id)->count();

        return view('cart', ['subTotal' => $subTotal, 'productsCart' => $productsCart, 'countCarts' => $countCarts, 'countProductsFavorite' => $countProductsFavorite, 'categories' => $categories]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $id)
    {
        $product = Product::where('id', '=', $id)->first();
        if ($product->stock_quantity > 0) {
            $test = Cart::where('user_id', '=', auth('user')->user()->id)->where('product_id', '=', $id)->first();
            if ($test) {
                $test->quantity += 1;
                $saved = $test->save();
                $product->stock_quantity -= 1;
                $product->save();
                return response()->json([
                    'status' => $saved,
                    'message' => $saved ? "Product Added to Cart Successfully" : "Product Added to Cart Failed!"
                ], $saved ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
            } else {
                $cart = new Cart();
                $cart->user_id = auth('user')->user()->id;
                $cart->product_id = $id;
                $cart->quantity = 1;
                $saved = $cart->save();
                $product->stock_quantity -= 1;
                $product->save();
                return response()->json([
                    'status' => $saved,
                    'qua' => true,
                    'message' => $saved ? "Product Added to Cart Successfully" : "Product Added to Cart Failed!"
                ], $saved ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Sorry, No Product More'
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cart $cart)
    {
        $product = Product::where('id', '=', $cart->product_id)->first();
        if ($request->input('chage') == 1) {
            if ($product->stock_quantity > 0) {
                $cart->quantity += 1;
                $saved = $cart->save();
                $product->stock_quantity -= 1;
                $product->save();
                return response()->json([
                    'status' => $saved,
                    'message' => $saved
                ], $saved ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Sorry, No Product More'
                ], Response::HTTP_BAD_REQUEST);
            }
        } else {
            if ($cart->quantity > 1) {
                $cart->quantity -= 1;
                $saved = $cart->save();
                $product->stock_quantity += 1;
                $product->save();
                return response()->json([
                    'status' => $saved,
                    'message' => $saved
                ], $saved ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $cart = Cart::where('id', '=', $id)->first();
        $quantity = $cart->quantity;
        $deleted = $cart->delete();
        $product = Product::where('id', '=', $cart->product_id)->first();
        $product->stock_quantity += $quantity;
        $product->save();
        return redirect()->back()->with([
            'status' => $deleted,
            'message' => $deleted ? "Product Delete from Carts Sucessfully" : "Product Delete from Carts Failed!"
        ]);
    }

}