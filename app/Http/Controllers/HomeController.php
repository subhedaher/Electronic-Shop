<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Message;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $ordersCount = Order::where('status', '=', 'pending')->count();
        $productCount = Product::count();
        $categoriesCount = Category::count();
        $userCount = User::count();
        $messagesCount = Message::count();
        return view('cms.home', ['messagesCount' => $messagesCount, 'ordersCount' => $ordersCount, 'userCount' => $userCount, 'productCount' => $productCount, 'categoriesCount' => $categoriesCount]);
    }
}
