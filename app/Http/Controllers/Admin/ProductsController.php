<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($client_id)
    {
        $client = User::findOrFail($client_id);
        $products = $client->products()->paginate(10);
        return view('products.admin.index')->with([
            'products' => $products,
            'client' => $client,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($client_id)
    {
        $client = User::findOrFail($client_id);
        $product_categories = $client->product_categories;
        return view('products.admin.create')->with([
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
        // Validate form request data
        $validated = $request->validate([
            'name' => ['required','string',
                        Rule::unique('products')->where(fn ($query) => $query->where('user_id', $client_id))],
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'image' => 'required|image|max:10240'
        ]);
        $request->validate(['category_id.*' => 'nullable|integer']);

        $product = new Product($validated);
        $product->user_id = $client_id;
        $product->slug = Str::slug($request->input('name'), '-').'-'.$client_id;

        // Store image to storage
        $extention = $request->file('image')->getClientOriginalExtension();
        $image_name_to_save = 'product_' . $client_id . '_'. time() . '.' . $extention;
        $img = Image::make($request->file('image'))->orientate();
        $size = min(1000, $img->width());
        $img = Image::make($request->file('image'))->orientate()->resize($size, null, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->save(storage_path('app/public/product/image/'.$image_name_to_save));
        $product->image = $image_name_to_save;

        $product->save();
        $product->product_categories()->attach($request->input('category_id'));
        return redirect(route('admin.products.index', $client_id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($client_id, $id)
    {
        $client = User::findOrFail($client_id);
        $product_categories = $client->product_categories;
        $product = Product::find($id);
        Gate::authorize('update', $product);
        return view('products.admin.edit')->with([
            'product_categories' => $product_categories,
            'product' => $product,
            'client' => $client,
        ]);
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
        // Validate form request data
        $validated = $request->validate([
            'name' => ['required','string',
                        Rule::unique('products')->where(fn ($query) => $query->where('user_id', $client_id))
                                                            ->ignore($id)],
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
        ]);
        $request->validate(['category_id.*' => 'nullable|integer']);

        $product = Product::find($id);
        Gate::authorize('update', $product);
        $product->fill($validated);
        $product->slug = Str::slug($request->input('name'), '-').'-'.$client_id;

        // Delete previous image and store new image to storage
        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'required|image|max:10240'
            ]);
            Storage::delete('public/product/image/' . $product->image);
            $extention = $request->file('image')->getClientOriginalExtension();
            $image_name_to_save = 'product_' . $client_id . '_'. time() . '.' . $extention;
            $img = Image::make($request->file('image'))->orientate();
            $size = min(1000, $img->width());
            $img = Image::make($request->file('image'))->orientate()->resize($size, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->save(storage_path('app/public/product/image/'.$image_name_to_save));
            $product->image = $image_name_to_save;
        }
        $product->update();
        $product->product_categories()->sync($request->input('category_id'));
        return redirect(route('admin.products.index', $client_id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($client_id, $id)
    {
        $product = Product::find($id);
        Gate::authorize('delete', $product);
        Storage::delete('public/product/image/' . $product->image);
        $product->delete();
        return redirect(route('admin.products.index', $client_id))->with('success', 'Product deleted');
    }
}
