<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\FavoriteProduct;
use App\Models\Order;
use App\Models\OrderProducts;
use App\Models\Product;
use App\Models\ShippingAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class CheckoutController extends Controller
{

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
        return view('checkout', ['subTotal' => $subTotal, 'categories' => $categories, 'countCarts' => $countCarts, 'countProductsFavorite' => $countProductsFavorite]);
    }

    public function order(Request $request)
    {
        $validator = validator($request->all(), [
            'country' => 'required|string',
            'city' => 'required|string',
            'post_code' => 'required|string',
            'address_details' => 'required|string',
        ]);

        if (!$validator->fails()) {
            $carts = Cart::where('user_id', '=', auth('user')->user()->id)->get();
            $order = new Order();
            $order->user_id = auth('user')->user()->id;
            if (session::has('coupon')) {
                $order->total = ceil($request->input('total') - session::get('coupon')->discount * $request->input('total') * 0.01);
            } else {
                $order->total = $request->input('total');
            }
            $order->status = 'pending';
            $saved = $order->save();
            session()->remove('coupon');
            if ($saved) {
                foreach ($carts as $cart) {
                    $order_product = new OrderProducts();
                    $order_product->order_id = $order->id;
                    $order_product->product_id = $cart->product_id;
                    $order_product->price = 0;
                    $order_product->quantity = $cart->quantity;
                    if ($cart->product->offer && $cart->product->offer->active) {
                        $discount = floor($cart->product->offer->discount * $cart->product->price * 0.01);
                        $order_product->price += $cart->quantity * ($cart->product->price - $discount);
                    } else {
                        $order_product->price = $cart->qsuantity * $cart->product->price;
                    }
                    $order_product->save();
                }
            }
            if ($saved) {
                $shipping_address = new ShippingAddress();
                $shipping_address->order_id = $order->id;
                $shipping_address->country = $request->input('country');
                $shipping_address->city = $request->input('city');
                $shipping_address->post_code = $request->input('post_code');
                $shipping_address->address_details = $request->input('address_details');
                $shipping_address->save();
            }

            if ($saved) {
                $carts = Cart::where('user_id', '=', auth('user')->user()->id)->delete();
            }

            return response()->json([
                'status' => $saved,
                'message' => $saved ? "Operation Successfully" : "Operation Failed!"
            ], $saved ? Response::HTTP_CREATED :  Response::HTTP_BAD_REQUEST);
        } else {
            return response()->json([
                'status' => false,
                'message' => $validator->getMessageBag()->first()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function orders()
    {
        $categories = Category::where('active', '=', true)->get();
        $orders = Order::with(['products', 'order_detailes'])->where('user_id', '=', auth('user')->user()->id)->orderBy('created_at', 'desc')->get();
        $orders_count = Order::where('user_id', '=', auth('user')->user()->id)->count();
        $countCarts = Cart::where('user_id', '=', auth('user')->user()->id ?? 0)->count();
        $countProductsFavorite = FavoriteProduct::where('user_id', '=', auth('user')->user()->id)->count();
        return view('orders', ['orders_count' => $orders_count, 'orders' => $orders, 'categories' => $categories, 'countCarts' => $countCarts, 'countProductsFavorite' => $countProductsFavorite]);
    }

    public function ordersForAdmin()
    {
        $orders = Order::with(['products', 'order_detailes', 'shipping_address', 'user'])->orderBy('created_at', 'desc')->paginate(20);
        return view('cms.orders.index', ['orders' => $orders]);
    }

    public function orderForAdmin(Order $order)
    {
        return view('cms.orders.show', ['order' => $order]);
    }

    public function changeStatus(Request $request, Order $order)
    {
        $order->status = $request->input('status');
        $orderGet = Order::with('order_detailes')->where('id', '=', $order->id)->first();
        if ($request->input('status') == 'canceled') {
            foreach ($orderGet->order_detailes as $detailes) {
                $product = Product::where('id', '=', $detailes->product->id)->first();
                $product->stock_quantity += $detailes->quantity;
                $product->save();
            }
        }
        $saved = $order->save();
        return response()->json([
            'status' => $saved,
            'message' => $saved ? "Operation Successfully" : "Operation Failed!"
        ], $saved ? Response::HTTP_OK :  Response::HTTP_BAD_REQUEST);
    }

    public function coupon(Request $request)
    {
        $validator = validator($request->all(), [
            'name' => 'required|string|exists:coupons',
        ]);
        if (!$validator->fails()) {
            $coupon = Coupon::where('name', '=', $request->input('name'))->where('active', '=', true)->first();
            if (!is_null($coupon)) {
                Session::put('coupon', $coupon);
                return response()->json([
                    'status' => true,
                    'message' => 'operation Successfully'
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Coupon in not Active'
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
