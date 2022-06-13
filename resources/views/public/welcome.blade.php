@extends('layouts.basic')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="d-flex justify-content-center align-items-center" style="height: 100vh">
                    <div class="text-center">
                        <h1 class="display-2">Demo Stores</h1>
                        <h5 class="mb-3">Welcome to Demo Stores. Purchase online, get your own store and add your
                            products. Access any store you want and have the client_id at www.demostore.com/client_id</h5>
                        @guest
                            <a class="btn btn-primary btn-lg" href="{{ route('login') }}" role="button">Login</a>
                            <a class="btn btn-primary btn-lg" href="{{ route('register') }}" role=" button">Register</a>
                        @else
                            <a class="btn btn-primary btn-lg" href="{{ route('dashboard') }}" role="button">Dashboard</a>
                            <a class="btn btn-primary btn-lg" href="{{ route('store.index', Auth::id()) }}"
                                role=" button">Your Store</a>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
