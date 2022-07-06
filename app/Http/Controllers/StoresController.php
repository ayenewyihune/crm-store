<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Store;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class StoresController extends Controller
{
    // Show latest products in a specific store
    public function index($client_id)
    {
        $user = User::findOrFail($client_id);
        $product_categories = $user->product_categories;
        $products = $user->products()->latest()->take(30)->get();
        return view('public.store.index')->with([
            'user' => $user,
            'product_categories' => $product_categories,
            'products' => $products,
        ]);
    }

    // Get products by category
    public function get_by_category($client_id, $category_slug)
    {
        $user = User::findOrFail($client_id);
        $product_categories = $user->product_categories;
        $product_category = ProductCategory::where('slug', $category_slug)->first();
        $products = $product_category->products()->paginate(30);
        return view('public.store.by_category')->with([
            'user' => $user,
            'product_categories' => $product_categories,
            'products' => $products,
            'product_category' => $product_category,
        ]);
    }

    // Show single product
    public function show_product($client_id, $product_slug)
    {
        $user = User::findOrFail($client_id);
        $product_categories = $user->product_categories;
        $product = Product::where('slug', $product_slug)->first();
        return view('public.store.show_product')->with([
            'user' => $user,
            'product_categories' => $product_categories,
            'product' => $product,
        ]);
    }

     // Show single product from the category page (url including category)
    public function show_product_with_category($client_id, $category_slug, $product_slug)
    {
        $user = User::findOrFail($client_id);
        $product_categories = $user->product_categories;
        $product = Product::where('slug', $product_slug)->first();
        return view('public.store.show_product')->with([
            'user' => $user,
            'product_categories' => $product_categories,
            'product' => $product,
        ]);
    }

    // Add a product to cart
    public function add_to_cart(Request $request, $client_id, $product_id)
    {
        $products = Auth::user()->carts()->pluck('product_id');
        if (in_array($product_id, $products->toArray())) {
            return redirect(route('store.products.show',[$client_id, Product::findOrFail($product_id)->slug]))->with('error', 'This product is already in your cart, you can add the quantity there if you need more.');
        }
        $validated = $request->validate(['quantity' => 'required|integer']);

        $product = Product::findOrFail($product_id);

        $cart = new Cart($validated);
        $cart->user_id = Auth::id();
        $cart->product_id = $product_id;
        $cart->client_id = $client_id;
        $cart->save();

        return redirect(route('store.products.show',[$client_id, Product::findOrFail($product_id)->slug]))->with('success', $product->name.' added to cart successfully.');
    }

    // Show cart
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

    // Delete single product from cart
    public function delete_cart($client_id, $cart_id)
    {
        $cart = Cart::findOrFail($cart_id);
        $cart->delete();
        return redirect(route('store.cart',[$client_id]))->with('success', 'Cart product deleted successfully.');
    }

    // Update quantity in the cart
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

    // Show checkout page
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

    // Place order (checkout)
    public function place_order(Request $request, $client_id)
    {
        $carts = Auth::user()->carts()->where('client_id', $client_id)->get();
        $products = Product::select('id','quantity')->whereIn('id', $carts->pluck('product_id'))->pluck('quantity','id');
        foreach ($carts as $cart) {
            if ($products[$cart->product_id] < $cart->quantity) {
                return redirect(route('store.cart',[$client_id]))->with('error', 'Availiable quantity for '.$cart->product->name.' is '.$products[$cart->product_id].'. But '.$cart->quantity.' ordered.');
            }
        }

        $validated_order = $request->validate([
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

        $order = new Order($validated_order);
        $order->user_id = Auth::id();
        $order->client_id = $client_id;
        $order->order_status_id = 1;

        $order->save();

        foreach ($carts as $cart) {
            $order->products()->attach($cart->product_id, ['quantity' => $cart->quantity]);
            Product::find($cart->product_id)->decrement('quantity', $cart->quantity);
        }

        Auth::user()->carts()->where('client_id', $client_id)->delete();

        return redirect(route('store.index', $client_id))->with('success', 'Your order received successfully.');
    }

    // See all stores (admin only)
    public function admin_index()
    {
        $stores = Store::paginate(10);
        return view('stores.index')->with('stores',$stores);
    }

    // See all deactivated stores (admin only)
    public function admin_deactivated_index()
    {
        $stores = Store::onlyTrashed()->paginate(10);
        return view('stores.deactivated_index')->with('stores',$stores);
    }

    // Deactivate store (admin only)
    public function admin_destroy($id)
    {
        $store = Store::findOrFail($id);
        $store->delete();
        return redirect(route('admin.stores.index'))->with('success', 'Store deactivated successfully.');
    }

     // Restore deactivated store (admin only)
    public function admin_restore($id)
    {
        Store::withTrashed()->where('id', $id)->first()->restore();
        return redirect(route('admin.stores.index'))->with('success', 'Store restored successfully.');
    }
}
