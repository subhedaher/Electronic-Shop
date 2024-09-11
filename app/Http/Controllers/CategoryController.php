<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    public function __construct()
    {
        return $this->authorizeResource(Category::class, 'category');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::withCount('products')->with('admin')->paginate(10);
        return view('cms.categories.index', ['categories' => $categories]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('cms.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator($request->all(), [
            'name' => 'required|string|unique:categories|min:3|max:45',
            'description' => 'required|string',
            'image' => 'required|image|mimes:png,jpg,jpeg',
            'active' => 'required|boolean',
        ]);


        if (!$validator->fails()) {
            $category = new Category();
            $category->name = $request->input('name');
            $category->slug = strtolower(str_replace(' ', '-', $request->input('name')));
            $category->description = $request->input('description');
            $image = $request->file('image');
            $imageName = $image->store('categories', ['disk' => 'public']);
            $category->image = $imageName;
            $category->active = $request->input('active');
            $category->admin_id = $request->user()->id;
            $saved = $category->save();
            return response()->json([
                'status' => $saved,
                'message' => $saved ? "Category Added Successfully" : "Category Added Failed!"
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
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('cms.categories.edit', ['category' => $category]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validator = Validator($request->all(), [
            'name' => "required|string|unique:categories,name,$category->id|min:3|max:45",
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:png,jpg,jpeg',
            'active' => 'required|boolean',
        ]);

        if (!$validator->fails()) {
            $category->name = $request->input('name');
            $category->slug = str_replace(' ', '-', $request->input('name'));
            $category->description = $request->input('description');
            if ($request->hasFile('image')) {
                Storage::delete($category->image);
                $image = $request->file('image');
                $imageName = $image->store('categories', ['disk' => 'public']);
                $category->image = $imageName;
            }
            $category->active = $request->input('active');
            $saved = $category->save();
            return response()->json([
                'status' => $saved,
                'message' => $saved ? "Category Updated Successfully" : "Category Updated Failed!"
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
    public function destroy(Category $category)
    {
        $countProducts = Product::withTrashed()->where('category_id', '=', $category->id)->count();
        if ($countProducts < 1) {
            $countRowDeleted = Category::destroy($category->id);
            return response()->json([
                'status' => $countRowDeleted,
                'message' => $countRowDeleted ? "Category has been Moved to Trash Successfully" : "Category has been Moved to Trash Failed!"
            ], $countRowDeleted ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Can Not be Deleted!'
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function trash(Request $request)
    {
        $this->authorize('Trash-Categories', $request->user());
        $trashCategories = Category::onlyTrashed()->paginate(10);
        return view('cms.categories.trash', ['trashCategories' => $trashCategories]);
    }

    public function restore($id)
    {
        $this->authorize('restore', Category::onlyTrashed()->findOrFail($id));
        $category = Category::onlyTrashed()->find($id);
        $restored = Category::onlyTrashed()->restore($category);
        return response()->json([
            'status' => $restored,
            'message' => $restored ? "Category Restore Successfully" : "Category Restore Failed!"
        ], $restored ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
    }

    public function forcedelete($id)
    {
        $this->authorize('forceDelete', Category::onlyTrashed()->findOrFail($id));
        $category = Category::onlyTrashed()->find($id);
        Storage::delete($category->image);
        $forceDeleted = $category->forceDelete();
        return response()->json([
            'status' => $forceDeleted,
            'message' => $forceDeleted ? "Category Deleted Successfully" : "Category Deleted Failed!"
        ], $forceDeleted ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
    }

    public function search(Request $request)
    {
        $categoriesSearch = Category::where('name', 'Like', '%' . $request->input('search') . '%')->get();
        return response()->json($categoriesSearch);
    }
}
