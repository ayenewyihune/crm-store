<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Store;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Cart;
use App\Models\CustomerDetail;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class StoresController extends Controller
{
    // Show latest products in a specific store
    public function index($client_id)
    {
        $user = User::findOrFail($client_id);
        $product_categories = $user->product_categories;
        $products = $user->products()->get();
        return view('public.store.index')->with([
            'user' => $user,
            'product_categories' => $product_categories,
            'products' => $products,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function get_by_category($client_id, $category_id)
    {
        $user = User::findOrFail($client_id);
        $product_categories = $user->product_categories;
        $product_category = ProductCategory::findOrFail($category_id);
        $products = $product_category->products()->paginate(50);
        return view('public.store.by_category')->with([
            'user' => $user,
            'product_categories' => $product_categories,
            'products' => $products,
            'product_category' => $product_category,
        ]);
    }

    public function show_product($client_id, $product_id)
    {
        $user = User::findOrFail($client_id);
        $product_categories = $user->product_categories;
        $product = Product::findOrFail($product_id);
        return view('public.store.show_product')->with([
            'user' => $user,
            'product_categories' => $product_categories,
            'product' => $product,
        ]);
    }

    public function add_to_cart(Request $request, $client_id, $product_id)
    {
        $products = Auth::user()->carts()->pluck('product_id');
        if (in_array($product_id, $products->toArray())) {
            return redirect(route('store.products.show',[$client_id, $product_id]))->with('error', 'This product is already in your cart, you can add the quantity there if you need more.');
        }
        $validated = $request->validate(['quantity' => 'required|integer']);

        $product = Product::findOrFail($product_id);

        $cart = new Cart($validated);
        $cart->user_id = Auth::id();
        $cart->product_id = $product_id;
        $cart->client_id = $client_id;
        $cart->save();

        return redirect(route('store.products.show',[$client_id, $product_id]))->with('success', $product->name.' added to cart successfully.');
    }

    public function cart($client_id)
    {
        $user = User::findOrFail($client_id);
        $product_categories = $user->product_categories;
        $carts = Auth::user()->carts()->where('client_id', $client_id)->get();
        $total_before_tax = 0;
        foreach ($carts as $cart) {
            $total_before_tax += $cart->quantity * $cart->product->price;
        }
        return view('public.store.cart')->with([
            'user' => $user,
            'product_categories' => $product_categories,
            'carts' => $carts,
            'total_before_tax' => $total_before_tax,
        ]);
    }

    public function delete_cart($client_id, $cart_id)
    {
        $cart = Cart::findOrFail($cart_id);
        $cart->delete();
        return redirect(route('store.cart',[$client_id]))->with('success', 'Cart deleted successfully.');
    }

    public function update_cart(Request $request, $client_id)
    {
        $request->validate(['quantity.*' => 'required|integer']);
        $quantities = $request->input('quantity');
        foreach ($quantities as $key => $quantity) {
            $cart = Cart::findOrFail($key);
            $cart->quantity = $quantity;
            $cart->update();
        }
        return redirect(route('store.cart',[$client_id]))->with('success', 'Update successful.');;
    }

    public function checkout($client_id)
    {
        $user = User::findOrFail($client_id);
        $product_categories = $user->product_categories;
        $carts = Auth::user()->carts()->where('client_id', $client_id)->get();
        $total_before_tax = 0;
        foreach ($carts as $cart) {
            $total_before_tax += $cart->quantity * $cart->product->price;
        }
        return view('public.store.checkout')->with([
            'user' => $user,
            'product_categories' => $product_categories,
            'carts' => $carts,
            'total_before_tax' => $total_before_tax,
        ]);
    }

    public function place_order(Request $request, $client_id)
    {
        $carts = Auth::user()->carts()->where('client_id', $client_id)->get();
        $products = Product::select('id','quantity')->whereIn('id', $carts->pluck('product_id'))->pluck('quantity','id');
        foreach ($carts as $cart) {
            if ($products[$cart->product_id] < $cart->quantity) {
                return redirect(route('store.cart',[$client_id]))->with('error', 'Availiable quantity for '.$cart->product->name.' is '.$products[$cart->product_id].'. But '.$cart->quantity.' ordered.');
            }
        }

        $validated_customer = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'company' => 'nullable|string',
            'country' => 'required|string',
            'street_address' => 'required|string',
            'town' => 'required|string',
            'region' => 'required|string',
            'post_code' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|email',
            'remark' => 'nullable',
        ]);

        $customer_detail = new CustomerDetail($validated_customer);
        $customer_detail->user_id = Auth::id();
        $customer_detail->client_id = $client_id;
        $customer_detail->save();

        foreach ($carts as $cart) {
            $order = new Order();
            $order->user_id = Auth::id();
            $order->client_id = $client_id;
            $order->order_status_id = 1;
            $order->customer_detail_id = $customer_detail->id;
            $order->quantity = $cart->quantity;
            $order->save();
            $order->products()->attach($cart->product_id);

            Product::find($cart->product_id)->decrement('quantity', $cart->quantity);
        }

        Auth::user()->carts()->where('client_id', $client_id)->delete();

        return redirect(route('store.index', $client_id))->with('success', 'Your order received successfully.');
    }

    public function admin_index()
    {
        $stores = Store::paginate(2);
        return view('stores.index')->with('stores',$stores);
    }
}
