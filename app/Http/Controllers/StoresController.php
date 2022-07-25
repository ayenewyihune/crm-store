<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Store;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class StoresController extends Controller
{
    // Show latest products in all stores
    public function welcome()
    {
        $products = Product::latest()->take(30)->get();
        return view('public.welcome')->with([
            'products' => $products,
        ]);
    }

    // Show list of all stores
    public function listing()
    {
        $stores = Store::paginate(30);
        return view('public.listing')->with([
            'stores' => $stores,
        ]);
    }

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
        $request->validate(['quantity' => 'required|integer|gt:0']);
        $product = Product::findOrFail($product_id);
        $cart = session('cart_'.$client_id);
        // If cart is empty, add it as the first product
        if (!$cart) {
            $cart = [
                    $product_id => [
                        "name" => $product->name,
                        "quantity" => $request->input('quantity'),
                        "price" => $product->price,
                        "image" => $product->image
                    ]
            ];
            session(['cart_'.$client_id => $cart]);
            return redirect(route('store.products.show',[$client_id, Product::findOrFail($product_id)->slug]))->with('product-add', $product->name.' added to cart successfully.');
        }
        // If cart is not empty, check if this product exists and increment quantity
        if(isset($cart[$product_id])) {
            $cart[$product_id]['quantity'] += $request->input('quantity');
            session(['cart_'.$client_id => $cart]);
            return redirect(route('store.products.show',[$client_id, Product::findOrFail($product_id)->slug]))->with('product-add', $product->name.' added to cart successfully.');
        }
        // If item does not exist in cart, add it to cart as new
        $cart[$product_id] = [
            "name" => $product->name,
            "quantity" => $request->input('quantity'),
            "price" => $product->price,
            "image" => $product->image
        ];
        session(['cart_'.$client_id => $cart]);
        return redirect(route('store.products.show',[$client_id, Product::findOrFail($product_id)->slug]))->with('product-add', $product->name.' added to cart successfully.');
    }

    // Show cart
    public function cart($client_id)
    {
        $user = User::findOrFail($client_id);
        $product_categories = $user->product_categories;
        $cart = session('cart_'.$client_id);
        $total_before_tax = 0;
        if ($cart) {
            foreach ($cart as $item) {
                $total_before_tax += $item['quantity'] * $item['price'];
            }
        }
        return view('public.store.cart')->with([
            'user' => $user,
            'product_categories' => $product_categories,
            'cart' => $cart,
            'total_before_tax' => $total_before_tax,
        ]);
    }

    // Delete single product from cart
    public function delete_cart($client_id, $product_id)
    {
        $cart = session('cart_'.$client_id);
        unset($cart[$product_id]);
        session(['cart_'.$client_id => $cart]);
        return redirect(route('store.cart',[$client_id]))->with('success', 'Cart product deleted successfully.');
    }

    // Update quantity in the cart
    public function update_cart(Request $request, $client_id)
    {
        $request->validate(['quantity.*' => 'required|integer|gt:0']);
        $quantities = $request->input('quantity');
        $cart = session('cart_'.$client_id);
        foreach ($quantities as $key=>$quantity) {
            $cart[$key]["quantity"] = $quantity;
        }
        session(['cart_'.$client_id => $cart]);
        return redirect(route('store.cart', $client_id))->with('success', 'Update successful.');
    }

    // Show checkout page
    public function checkout($client_id)
    {
        $user = User::findOrFail($client_id);
        $product_categories = $user->product_categories;
        $cart = session('cart_'.$client_id);
        if (!$cart) {
            return redirect(route('store.cart', $client_id))->with('error', 'You cannot proceed to checkout while the cart is empty.');
        }
        $total_before_tax = 0;
        foreach ($cart as $item) {
            $total_before_tax += $item['quantity'] * $item['price'];
        }
        return view('public.store.checkout')->with([
            'user' => $user,
            'product_categories' => $product_categories,
            'cart' => $cart,
            'total_before_tax' => $total_before_tax,
        ]);
    }

    // Place order (checkout)
    public function place_order(Request $request, $client_id)
    {
        $cart = session('cart_'.$client_id);
        $products = Product::select('id','quantity')->whereIn('id', array_keys($cart))->pluck('quantity','id');
        foreach ($cart as $key=>$item) {
            if ($products[$key] < $item['quantity']) {
                return redirect(route('store.cart',[$client_id]))->with('error', 'Availiable quantity for '.$item['name'].' is '.$products[$key].'. But '.$item['quantity'].' ordered.');
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
        $order->client_id = $client_id;
        $order->order_status_id = 1;

        $order->save();

        foreach ($cart as $key=>$item) {
            $order->products()->attach($key, ['quantity' => $item['quantity']]);
            Product::find($key)->decrement('quantity', $item['quantity']);
        }

        session()->forget('cart_'.$client_id);

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
