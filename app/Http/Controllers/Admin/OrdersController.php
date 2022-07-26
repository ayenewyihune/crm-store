<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\Gate;

class OrdersController extends Controller
{
    // Show orders to the store of the authenticated user
    public function index($client_id)
    {
        $client = User::findOrFail($client_id);
        $orders = Order::where('client_id', $client_id)->paginate(10);
        return view('orders.admin.index')->with([
            'orders' => $orders,
            'client' => $client,
        ]);
    }

    // Show details of a single order
    public function show($client_id, $id)
    {
        $client = User::findOrFail($client_id);
        $order = Order::findOrFail($id);
        Gate::authorize('view', $order);
        return view('orders.admin.show')->with([
            'order' => $order,
            'client' => $client,
        ]);
    }
    
    // Confirm/accept order
    public function complete(Request $request, $client_id, $id)
    {
        $order = Order::findOrFail($id);
        Gate::authorize('update', $order);
        $order->order_status_id = 2;
        $order->update();
        return redirect(route('admin.orders.show', [$client_id, $id]))->with('success','Order completed successfully.');
    }

    // Cancel/decline order
    public function cancel(Request $request, $client_id, $id)
    {
        $order = Order::findOrFail($id);
        Gate::authorize('update', $order);
        $order->order_status_id = 3;
        $order->update();
        return redirect(route('admin.orders.show', [$client_id, $id]))->with('success','Order canceled successfully.');
    }
}
