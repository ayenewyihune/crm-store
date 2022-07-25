@extends('layouts.basic')

@section('content')
    <div class="pt-3 p-md-5">
        <div class="text-center">
            <h1 class="display-1">Welcome</h1>
            <div class="row">
                <div class="col-2 offset-5">
                    <hr class="bg-dark mx-md-4" style="height: 2px">
                </div>
            </div>
            <p>Explore recently added products in all our stores...</p>
        </div>
    </div>
    <div class="row">
        @foreach ($products as $product)
            @if ($product->product_categories->isNotEmpty())
                <div class="col-md-3 p-1">
                    <a href="{{ route('store.products.show', [$product->user_id, $product->slug]) }}"
                        style="text-decoration: none; color:black;">
                        <div class="card">
                            <img height="250px" width="100%" src="{{ asset('storage/product/image/' . $product->image) }}"
                                alt="">
                            <div class="px-3 mb-2">
                                <h5 class="mb-0" style="font-family: 'Open Sans'">{{ $product->name }}</h5>
                                <small class="fst-italic fw-light" style="color: darkgray">
                                    {{ implode(', ',$product->product_categories()->pluck('name')->toArray()) }}</small>
                                <p>$ {{ $product->price }}</p>
                            </div>
                        </div>
                    </a>
                </div>
            @endif
        @endforeach
    </div>
@endsection
