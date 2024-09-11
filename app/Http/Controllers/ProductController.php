<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Product::class, 'product');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('category')->withAvg('ratings', 'rating')->paginate(10);
        return view('cms.products.index', ['products' => $products]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('active', '=', true)->get();
        return view('cms.products.create', ['categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator($request->all(), [
            'category_id' => 'required|numeric|exists:categories,id',
            'name' => 'required|string|unique:products|min:3',
            'price' => 'required|numeric|min:1',
            'details' => 'required|string|min:3',
            'quantity' => 'required|numeric|min:0',
            'images' => 'required',
            'images.*' => 'image|mimes:png,jpg,jpeg'
        ]);



        if (!$validator->fails()) {
            $product = new Product();
            $product->category_id = $request->input('category_id');
            $product->name = $request->input('name');
            $product->slug = strtolower(str_replace(' ', '-', $request->input('name')));
            $product->price = $request->input('price');
            $product->details = $request->input('details');
            $product->stock_quantity = $request->input('quantity');
            $product->admin_id = $request->user()->id;
            $saved = $product->save();
            if ($saved) {
                foreach ($request->file('images') as $image) {
                    $product_image = new ProductImage();
                    $imageName = $image->store('products', ['disk' => 'public']);
                    $product_image->url = $imageName;
                    $product_image->product_id = $product->id;
                    $product_image->save();
                }
            }
            return response()->json([
                'status' => $saved,
                'message' => $saved ? "Product Added Successfully" : "Product Added Failed!"
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
    public function show(Product $product)
    {
        $images = ProductImage::where('product_id', '=', $product->id)->get();
        return view('cms.products.show', ['product' => $product, 'images' => $images]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::where('active', '=', true)->get();
        return view('cms.products.edit', ['product' => $product, 'categories' => $categories]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {

        $validator = Validator($request->all(), [
            'category_id' => 'required|numeric|exists:categories,id',
            'name' => 'required|string||min:3|unique:products,name,' . $product->id,
            'price' => 'required|numeric|min:1',
            'details' => 'required|string|min:3',
            'quantity' => 'required|numeric|min:0',
            'images' => 'nullable',
            'images.*' => 'image|mimes:png,jpg,jpeg'
        ]);

        if (!$validator->fails()) {
            $product->category_id = $request->input('category_id');
            $product->name = $request->input('name');
            $product->slug = strtolower(str_replace(' ', '-', $request->input('name')));
            $product->price = $request->input('price');
            $product->details = $request->input('details');
            $product->stock_quantity = $request->input('quantity');
            $product->admin_id = $request->user()->id;
            $saved = $product->save();
            if ($request->hasFile('images')) {
                $images = ProductImage::where('product_id', '=', $product->id)->get();
                foreach ($images as $image) {
                    Storage::delete($image->url);
                    $image->delete();
                }
                if ($saved) {
                    foreach ($request->file('images') as $image) {
                        $product_image = new ProductImage();
                        $imageName = $image->store('products', ['disk' => 'public']);
                        $product_image->url = $imageName;
                        $product_image->product_id = $product->id;
                        $product_image->save();
                    }
                }
            }
            return response()->json([
                'status' => $saved,
                'message' => $saved ? "Product Updated Successfully" : "Product Updated Failed!"
            ], $saved ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
        } else {
            return response()->json([
                'status' => false,
                'message' => $validator->getMessageBag()->first()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $deleted = $product->delete();
        return response()->json([
            'status' => $deleted,
            'message' => $deleted ? "Product Deleted Successfully" : "Product Deleted Failed!"
        ], $deleted ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
    }

    /**
     * Display the specified trash resource
     */
    public function trash(Request $request)
    {
        // $this->authorize('trash', $request->user());
        $products = Product::onlyTrashed()->paginate(10);
        return view('cms.products.trash', ['products' => $products]);
    }

    public function restore($id)
    {
        $this->authorize('restore', Product::onlyTrashed()->findOrFail($id));
        $product = Product::onlyTrashed()->find($id);
        $restored = $product->restore();
        return response()->json([
            'status' => $restored,
            'message' => $restored ? "Product Restore Successfully" : "Product Restore Failed!"
        ], $restored ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
    }

    public function forcedelete($id)
    {
        $this->authorize('forceDelete', Product::onlyTrashed()->findOrFail($id));
        $product = Product::onlyTrashed()->find($id);
        $product_images = ProductImage::where('product_id', '=', $id)->get();
        foreach ($product_images as $image) {
            Storage::delete($image->url);
        }
        $forceDeleted = $product->forceDelete();
        return response()->json([
            'status' => $forceDeleted,
            'message' => $forceDeleted ? "Product Deleted Successfully" : "Product Deleted Failed!"
        ], $forceDeleted ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
    }
}
