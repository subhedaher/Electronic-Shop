<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CouponController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Coupon::class, 'coupon');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coupons = Coupon::paginate(10);
        return view('cms.coupons.index', ['coupons' => $coupons]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('cms.coupons.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator($request->all(), [
            'name' => 'required|string|unique:coupons',
            'discount' => 'required|numeric|min:1|max:100',
            'active' => 'required|boolean',
        ]);

        if (!$validator->fails()) {
            $coupon = new Coupon();
            $coupon->name = $request->input('name');
            $coupon->discount = $request->input('discount');
            $coupon->active = $request->input('active');
            $saved = $coupon->save();
            return response()->json([
                'status' => $saved,
                'message' => $saved ? "Coupon Added Successfully" : "Coupon Added Failed!"
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
    public function show(Coupon $coupon)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Coupon $coupon)
    {
        return view('cms.coupons.edit', ['coupon' => $coupon]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Coupon $coupon)
    {
        $validator = Validator($request->all(), [
            'name' => 'required|string|unique:coupons,name,' . $coupon->id,
            'discount' => 'required|numeric|min:1|max:100',
            'active' => 'required|boolean',
        ]);

        if (!$validator->fails()) {
            $coupon->name = $request->input('name');
            $coupon->discount = $request->input('discount');
            $coupon->active = $request->input('active');
            $saved = $coupon->save();
            return response()->json([
                'status' => $saved,
                'message' => $saved ? "Coupon Updated Successfully" : "Coupon Updated Failed!"
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
    public function destroy(Coupon $coupon)
    {
        $deleted = $coupon->delete();
        return response()->json([
            'status' => $deleted,
            'message' => $deleted ? "Coupon Deleted Successfully" : "Coupon Deleted Failed!"
        ], $deleted ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
    }
}
