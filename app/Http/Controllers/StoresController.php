<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Store;
use App\Models\Product;
use App\Models\ProductCategory;

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

    public function admin_index()
    {
        $stores = Store::paginate(2);
        return view('stores.index')->with('stores',$stores);
    }
}
