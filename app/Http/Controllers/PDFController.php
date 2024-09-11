<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PDFController extends Controller
{
    public function orders()
    {
        $orders = Order::with('user')->get();
        $data = [
            'orders' => $orders
        ];
        $pdf = Pdf::loadView('pdf.orders', $data);
        return $pdf->download('orders.pdf');
    }

    public function order($id)
    {
        $order = Order::where('id', '=', $id)->with(['user', 'products', 'order_detailes', 'shipping_address'])->first();
        $data = [
            'order' => $order
        ];
        $pdf = Pdf::loadView('pdf.order', $data);
        return $pdf->download('order.pdf');
    }
}
