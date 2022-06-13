<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CSM and Store') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <header>
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <!-- Container wrapper -->
            <div class="container">
                <!-- Navbar brand -->
                <a class="navbar-brand me-2" href="/">
                    Demo Stores
                </a>

                <div class="d-flex align-items-end">
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-link px-3 me-2">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-link px-3 me-2">Register </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="btn btn-link px-3 me-2">Dashboard</a>

                        <a class="btn btn-link px-3 me-2" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    @endguest
                </div>
            </div>
            <!-- Container wrapper -->
        </nav>
        <!-- Navbar -->
    </header>

    <div class="bg-light min-vh-70 d-flex flex-row align-items-center">
        <div class="container">
            @include('inc.messages')
            @yield('content')
        </div>
    </div>

    <!-- jquery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <!-- MDB -->
    <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
    <!-- Additional scripts -->
    @yield('script')
</body>

</html>
