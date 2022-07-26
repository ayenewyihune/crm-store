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
                <a class="navbar-brand" href="{{ route('welcome') }}">
                    <img src="{{ asset('gebeya-logo.png') }}" alt="" width="90" height="35">
                </a>

                <!-- Left links -->
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('store.listing') }}">Stores List</a>
                    </li>
                </ul>
                <!-- Left links -->

                <!-- Right elements -->
                @guest
                    <ul class="navbar-nav">
                        <li class="nav-item d-flex">
                            <a class="nav-link me-2" href="{{ route('login') }}">Login</a>
                            <a class="nav-link me-2" href="{{ route('register') }}">Register</a>
                        </li>
                    </ul>
                @else
                    <!-- Avatar -->
                    <div class="dropdown">
                        <a class="dropdown-toggle d-flex align-items-center hidden-arrow" href="#"
                            id="navbarDropdownMenuAvatar" role="button" data-mdb-toggle="dropdown" aria-expanded="false">
                            @if (Auth::user()->image)
                                <img src="{{ asset('storage/user/image/' . Auth::user()->image) }}" class="rounded-circle"
                                    height="27" alt="img" loading="lazy" />
                            @else
                                <img src="{{ asset('storage/user/image/default.jpg') }}" class="rounded-circle"
                                    height="27" alt="img" loading="lazy" />
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuAvatar">
                            <li>
                                <a class="dropdown-item" href="{{ route('dashboard') }}">Dashboard</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                </form>
                            </li>

                        </ul>
                    </div>
                @endguest
                <!-- Right elements -->
            </div>
            <!-- Container wrapper -->
        </nav>
    </header>

    <div style="min-height: calc(100vh - 117px);">
        <div class="container">
            @include('inc.messages')
            @yield('content')
        </div>
    </div>

    <footer class="bg-light text-center text-lg-start mt-5">
        <!-- Copyright -->
        <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
            Copyright &copy; {{ now()->year }} <a href="http://ec2-54-73-20-77.eu-west-1.compute.amazonaws.com">CRM
                and Store</a>.
        </div>
        <!-- Copyright -->
    </footer>

    <!-- jquery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <!-- MDB -->
    <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
    <!-- Additional scripts -->
    @yield('script')
</body>

</html>
