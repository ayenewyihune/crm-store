@extends('layouts.basic')

@section('content')
    <div class="pt-3 p-md-5">
        <div class="text-center">
            <h3 class="text-black">List of Stores</h3>
            <div class="row">
                <div class="col-2 offset-5">
                    <hr class="bg-dark mx-4" style="height: 2px">
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        @foreach ($stores as $store)
            <div class="col-md-4 p-1">
                <a href="{{ route('store.index', $store->user->id) }}" style="text-decoration: none; color:black;">
                    <div class="card">
                        <img height="300px" width="100%" src="{{ asset('storage/store.jpg') }}" alt="">
                        <div class="px-3 mb-2">
                            <h5 class="mb-0" style="font-family: 'Open Sans'">{{ $store->user->name }}</h5>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
@endsection
