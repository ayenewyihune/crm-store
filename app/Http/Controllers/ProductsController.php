<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
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
    public function index()
    {
        $products = Auth::user()->products()->paginate(10);
        return view('products.index')->with('products',$products);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $product_categories = Auth::user()->product_categories;
        return view('products.create')->with('product_categories', $product_categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate form request data
        $validated = $request->validate([
            'name' => ['required','string',
                        Rule::unique('products')->where(fn ($query) => $query->where('user_id', Auth::id()))],
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'image' => 'required|image|max:10240'
        ]);
        $request->validate(['category_id.*' => 'nullable|integer']);

        $product = new Product($validated);
        $product->user_id = Auth::id();
        $product->slug = Str::slug($request->input('name'), '-').'-'.Auth::id();

        // Store image to storage
        $extention = $request->file('image')->getClientOriginalExtension();
        $image_name_to_save = 'product_' . Auth::id() . '_'. time() . '.' . $extention;
        $img = Image::make($request->file('image'))->orientate();
        $size = min(1000, $img->width());
        $img = Image::make($request->file('image'))->orientate()->resize($size, null, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->save(storage_path('app/public/product/image/'.$image_name_to_save));
        $product->image = $image_name_to_save;

        $product->save();
        $product->product_categories()->attach($request->input('category_id'));
        return redirect('/products');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product_categories = Auth::user()->product_categories;
        $product = Product::find($id);
        Gate::authorize('update', $product);
        return view('products.edit')->with([
            'product_categories' => $product_categories,
            'product' => $product,
        ]);
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
        // Validate form request data
        $validated = $request->validate([
            'name' => ['required','string',
                        Rule::unique('products')->where(fn ($query) => $query->where('user_id', Auth::id()))
                                                            ->ignore($id)],
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
        ]);
        $request->validate(['category_id.*' => 'nullable|integer']);

        $product = Product::find($id);
        Gate::authorize('update', $product);
        $product->fill($validated);
        $product->slug = Str::slug($request->input('name'), '-').'-'.Auth::id();

        // Delete previous image and store new image to storage
        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'required|image|max:10240'
            ]);
            Storage::delete('public/product/image/' . $product->image);
            $extention = $request->file('image')->getClientOriginalExtension();
            $image_name_to_save = 'product_' . Auth::id() . '_'. time() . '.' . $extention;
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
        return redirect('/products');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        Gate::authorize('delete', $product);
        Storage::delete('public/product/image/' . $product->image);
        $product->delete();
        return redirect('/products')->with('success', 'Product deleted');
    }
}
