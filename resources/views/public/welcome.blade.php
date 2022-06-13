@extends('layouts.basic')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="d-flex justify-content-center align-items-center" style="height: 70vh">
                    <div id="title" class="text-center center" style="opacity:0;">
                        <h3>Demo Stores</h3>
                        <hr>

                        <h4 class="display-4 mb-3" style="font-size: 1.1rem">Welcome to Demo Stores. Purchase online, get
                            your own store and add your
                            products. Access any store you want and have the client_id at www.demostore.com/client_id</h4>
                        @guest
                            <div class="text-center">
                                <a href="{{ route('login') }}" class="px-4 btn btn-primary">Login</a>
                                <a href="{{ route('register') }}" class="px-4 btn btn-outline-primary">Register</a>
                            </div>
                        @else
                            <div class="text-center">
                                <a href="{{ route('dashboard') }}" class="px-4 btn btn-primary">Dashboard</a>
                                <a href="{{ route('store.index', Auth::id()) }}" class="px-4 btn btn-outline-primary">Your
                                    store</a>
                            </div>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $("#title").animate({
                opacity: '1'
            }, 500);
            $("#desc").animate({
                opacity: '1'
            }, 1000);
        });
    </script>
@endsection
