<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class OrdersController extends Controller
{
    // Show orders to the store of the authenticated user
    public function index()
    {
        $orders = Order::where('client_id', Auth::id())->paginate(10);
        return view('orders.index')->with('orders',$orders);
    }

    // Show details of a single order
    public function show($id)
    {
        $order = Order::findOrFail($id);
        Gate::authorize('view', $order);
        return view('orders.show')->with('order',$order);
    }
    
    // Confirm/accept order
    public function complete(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        Gate::authorize('update', $order);
        $order->order_status_id = 2;
        $order->update();
        return redirect(route('orders.show', $id))->with('success','Order completed successfully.');
    }

    // Cancel/decline order
    public function cancel(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        Gate::authorize('update', $order);
        $order->order_status_id = 3;
        $order->update();
        return redirect(route('orders.show', $id))->with('success','Order canceled successfully.');
    }
}
