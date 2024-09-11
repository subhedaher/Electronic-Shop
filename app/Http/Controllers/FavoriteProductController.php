<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\FavoriteProduct;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class FavoriteProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $countCarts = Cart::where('user_id', '=', auth('user')->user()->id ?? 0)->count();
        $categories = Category::where('active', '=', true)->get();


        $favoriteProducts = FavoriteProduct::with(['product' => function ($quere) {
            $quere->select('id', 'created_at', 'name', 'category_id', 'slug', 'price');
        }, 'product.ratings' => function ($quere) {
            $quere->select('product_id', DB::raw("AVG(rating) as avg_rating"), DB::raw("COUNT(id) as count_rating"))->GROUPBY('product_id');
        }])->where('user_id', '=', auth('user')->user()->id)->paginate(8);


        $countProductsFavorite = FavoriteProduct::where('user_id', '=', auth('user')->user()->id)->count();
        return view('favorite-products', ['countCarts' => $countCarts, 'favoriteProducts' => $favoriteProducts, 'categories' => $categories, 'countProductsFavorite' => $countProductsFavorite]);
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
    public function store(Request $request)
    {
        $validator = validator($request->all(), [
            'user_id' => 'required|numeric|exists:users,id',
            'product_id' => [
                'required', 'numeric', 'exists:products,id',
                Rule::unique('favorite_products')->where(function ($query) use ($request) {
                    return $query->where('user_id', $request->input('user_id'));
                })
            ]
        ], [
            'product_id.unique' => 'The product is already in favorites'
        ]);

        if (!$validator->fails()) {
            $favoriteProduct = new FavoriteProduct();
            $favoriteProduct->user_id = $request->input('user_id');
            $favoriteProduct->product_id = $request->input('product_id');
            $saved = $favoriteProduct->save();
            return response()->json([
                'status' => $saved,
                'message' => $saved ? "Added Favorite Successfully" : "Added Favorite Failed"
            ], $saved ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST);
        } else {
            return response()->json([
                'status' => false,
                'message' => $validator->getMessageBag()->first()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(FavoriteProduct $favoriteProduct)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FavoriteProduct $favoriteProduct)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FavoriteProduct $favoriteProduct)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $countRows = FavoriteProduct::destroy($id);
        return response()->json([
            'status' => $countRows,
            'message' =>  $countRows ? "Product Delete from Favorite Sucessfully" : "Product Delete from Favorite Failed!"
        ], $countRows ? Response::HTTP_OK :  Response::HTTP_BAD_REQUEST);
    }
}
