<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrdersController extends Controller
{
    public function index()
    {
        $orders = Order::where('client_id',Auth::id())->paginate(10);
        return view('orders.index')->with('orders',$orders);
    }

    public function my_orders()
    {
        $orders = Auth::user()->orders()->paginate(10);
        return view('orders.index')->with('orders',$orders);
    }

    public function show($id)
    {
        $order = Order::findOrFail($id);
        return view('orders.show')->with('order',$order);
    }
    
    public function complete(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->order_status_id = 2;
        $order->update();
        return redirect(route('orders.show', $id))->with('success','Order completed successfully.');
    }

    public function cancel(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->order_status_id = 3;
        $order->update();
        return redirect(route('orders.show', $id))->with('success','Order canceled successfully.');
    }
}
