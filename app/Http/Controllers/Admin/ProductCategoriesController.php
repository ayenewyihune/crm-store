<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($client_id)
    {
        $client = User::findOrFail($client_id);
        $product_categories = $client->product_categories()->withTrashed()->paginate(10);
        return view('product_categories.admin.index')->with([
            'product_categories' => $product_categories,
            'client' => $client,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $client_id)
    {
        $validated = $request->validate([
            'name' => ['required','string',
                        Rule::unique('product_categories')->where(fn ($query) => $query->where('user_id', $client_id))],
        ]);

        $category = new ProductCategory($validated);
        $category->user_id = $client_id;
        $category->slug = Str::slug($request->input('name'), '-').'-'.$client_id;
        $category->save();
        return redirect(route('admin.product-categories.index', $client_id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $client_id, $id)
    {
        $validated = $request->validate([
            'name' => ['required','string',
                        Rule::unique('product_categories')->where(fn ($query) => $query->where('user_id', $client_id))
                                                            ->ignore($id)],
        ]);
        $category = ProductCategory::withTrashed()->where('id', $id)->first();
        Gate::authorize('update', $category);
        $category->fill($validated);
        $category->slug = Str::slug($request->input('name'), '-').'-'.$client_id;
        $category->update();
        return redirect(route('admin.product-categories.index', $client_id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($client_id, $id)
    {
        $category = ProductCategory::withTrashed()->where('id',$id)->first();
        Gate::authorize('forceDelete', $category);
        $category->forceDelete();
        return redirect(route('admin.product-categories.index', $client_id));
    }

    // Hide/show categories as per the need of the client
    public function toggle_hide(Request $request, $client_id, $id)
    {
        $category = ProductCategory::withTrashed()->where('id', $id)->first();
        Gate::authorize('delete', $category);
        if ($category->trashed()) {
            $category->restore();
        } else {
            $category->delete();
        }
        return redirect(route('admin.product-categories.index', $client_id));
    }
}
