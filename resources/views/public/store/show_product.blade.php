@extends('layouts.store')

@section('content')
    <div class="card mt-5 p-5">
        <div class="row">
            <div class="col-md-6">
                <div class="bg-image">
                    <img class="w-100" src="{{ asset('storage/product/image/' . $product->image) }}" alt="">
                    <div class="mask" style="background-color: rgba(194, 194, 194, 0.275)"></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-3 mb-2">
                    <h1 class="text-black" style="font-family: 'Open Sans'">{{ $product->name }}</h1>
                    <h4 class="text-black">$ {{ $product->price }}</h4>
                    <hr>
                    <p>Categories</p>
                    <small class="fst-italic fw-light" style="color: darkgray">
                        {{ implode(', ',$product->product_categories()->pluck('name')->toArray()) }}</small>
                    <div class="pt-4">
                        <input type="number" class="py-1" style="width:15%">
                        <a class="btn btn-primary btn-lg px-5 py-2"
                            href="{{ route('store.product.add_to_cart', [$user->id, $product->id]) }}">Add
                            to Cart</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
