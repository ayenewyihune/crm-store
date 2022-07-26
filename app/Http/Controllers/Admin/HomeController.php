<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index($client_id)
    {
        $client = User::findOrFail($client_id);
        $orders = Order::where('client_id', $client_id)->get(['created_at']);
        $order_trend = [];
        foreach ($orders as $i => $order) {
            $date[$i] = date("m/d/Y G:i:s",strtotime($order->created_at));
            $order_trend[$i] = ['x'=>$date[$i], 'y'=>$i+1];
        }
        return view('dashboard_admin')->with([
            'order_trend' => $order_trend,
            'client' => $client
        ]);
    }
}
