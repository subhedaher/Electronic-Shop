<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Product;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OfferController extends Controller
{


    public function __construct()
    {
        $this->authorizeResource(Offer::class, 'offer');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $offers = Offer::with('product')->paginate(10);
        return view('cms.offers.index', ['offers' => $offers]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::whereDoesntHave('offer')->get();
        return view('cms.offers.create', ['products' => $products]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator($request->all(), [
            'product_id' => 'required|numeric|unique:offers|exists:products,id',
            'name' => 'required|string',
            'discount' => 'required|numeric|min:1|max:100',
            'active' => 'required|boolean',
        ]);

        if (!$validator->fails()) {
            $offer = new Offer();
            $offer->product_id = $request->input('product_id');
            $offer->name = $request->input('name');
            $offer->discount = $request->input('discount');
            $offer->active = $request->input('active');
            $saved = $offer->save();
            return response()->json([
                'status' => $saved,
                'message' => $saved ? "Offer Added Successfully" : "Offer Added Failed!"
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
    public function show(Offer $offer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Offer $offer)
    {
        $products = Product::where('id', '=', $offer->product_id)->orwhereDoesntHave('offer')->get();
        return view('cms.offers.edit', ['offer' => $offer, 'products' => $products]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Offer $offer)
    {
        $validator = Validator($request->all(), [
            'product_id' => 'required|numeric|exists:products,id|unique:offers,product_id,' . $offer->id,
            'name' => 'required|string',
            'discount' => 'required|numeric|min:1|max:100',
            'active' => 'required|boolean',
        ]);

        if (!$validator->fails()) {
            $offer->product_id = $request->input('product_id');
            $offer->name = $request->input('name');
            $offer->discount = $request->input('discount');
            $offer->active = $request->input('active');
            $saved = $offer->save();
            return response()->json([
                'status' => $saved,
                'message' => $saved ? "Offer Updated Successfully" : "Offer Updated Failed!"
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
    public function destroy(Offer $offer)
    {
        $deleted = $offer->delete();
        return response()->json([
            'status' => $deleted,
            'message' => $deleted ? "Offer Deleted Successfully" : "Offer Deleted Failed!"
        ], $deleted ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
    }
}