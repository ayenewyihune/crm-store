<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class ProductCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product_categories = Auth::user()->product_categories()->withTrashed()->paginate(10);
        return view('product_categories.index')->with('product_categories',$product_categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
        ]);

        $category = new ProductCategory($validated);
        $category->user_id = Auth::id();
        $category->slug = Str::slug($request->input('name'), '-').'-'.Auth::id();
        $category->save();
        return redirect('/product-categories');
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
        $validated = $request->validate([
            'name' => 'required|string',
        ]);
        $category = ProductCategory::withTrashed()->where('id', $id)->first();
        Gate::authorize('update', $category);
        $category->fill($validated);
        $category->slug = Str::slug($request->input('name'), '-').'-'.Auth::id();
        $category->update();
        return redirect('/product-categories');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = ProductCategory::withTrashed()->where('id',$id)->first();
        Gate::authorize('forceDelete', $category);
        $category->forceDelete();
        return redirect('/product-categories');
    }

    // Hide/show categories as per the need of the client
    public function toggle_hide(Request $request, $id)
    {
        $category = ProductCategory::withTrashed()->where('id', $id)->first();
        Gate::authorize('delete', $category);
        if ($category->trashed()) {
            $category->restore();
        } else {
            $category->delete();
        }
        return redirect('/product-categories');
    }
}
