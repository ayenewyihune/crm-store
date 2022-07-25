<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>{{ config('app.name', 'CSM and Store') }}</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <!-- Google Fonts Roboto -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" />
    <!-- MDB -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
</head>

<body>
    <header>
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <!-- Container wrapper -->
            <div class="container">
                <!-- Toggle button -->
                <button class="navbar-toggler" type="button" data-mdb-toggle="collapse"
                    data-mdb-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fas fa-bars"></i>
                </button>

                <!-- Navbar brand -->
                <a class="navbar-brand"
                    href="{{ route('store.index', $user->id) }}">{{ explode(' ', $user->name)[0] }}'s Store</a>

                <!-- Right elements -->
                <div class="d-flex d-md-none align-items-center">

                    <!-- Icons -->
                    <a href="{{ route('welcome') }}" class="position-relative text-reset me-3">
                        <i class="fas fa-home fa-lg"></i>
                    </a>
                    <a class="position-relative text-reset me-3" href="{{ route('store.cart', $user->id) }}">
                        <i class="fas fa-shopping-cart fa-lg"></i>
                        @if (session('cart_' . $user->id) && count(session('cart_' . $user->id)) > 0)
                            <span
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning">
                                {{ array_sum(array_map(function ($item) {return $item['quantity'];}, session('cart_' . $user->id))) }}
                            </span>
                        @endif
                    </a>
                    @guest
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Login</a>
                            </li>
                        </ul>
                    @else
                        <!-- Avatar -->
                        <div class="dropdown">
                            <a class="dropdown-toggle d-flex align-items-center hidden-arrow" href="#"
                                id="navbarDropdownMenuAvatar" role="button" data-mdb-toggle="dropdown"
                                aria-expanded="false">
                                @if (Auth::user()->image)
                                    <img src="{{ asset('storage/user/image/' . Auth::user()->image) }}"
                                        class="rounded-circle" height="27" alt="img" loading="lazy" />
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
                </div>
                <!-- Right elements -->

                <!-- Collapsible wrapper -->
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left links -->
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        @if ($product_categories->isNotEmpty())
                            @if (count($product_categories) > 5)
                                @for ($i = 0; $i < 5; $i++)
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{ route('store.by_category', [$user->id, $product_categories[$i]->slug]) }}">{{ $product_categories[$i]->name }}</a>
                                    </li>
                                @endfor
                                <!-- Dropdown -->
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink"
                                        role="button" data-mdb-toggle="dropdown" aria-expanded="false">
                                        More...
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                        @for ($i = 5; $i < count($product_categories); $i++)
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ route('store.by_category', [$user->id, $product_categories[$i]->slug]) }}">{{ $product_categories[$i]->name }}</a>
                                            </li>
                                        @endfor
                                    </ul>
                                </li>
                            @else
                                @for ($i = 0; $i < count($product_categories); $i++)
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{ route('store.by_category', [$user->id, $product_categories[$i]->slug]) }}">{{ $product_categories[$i]->name }}</a>
                                    </li>
                                @endfor
                            @endif
                        @endif
                    </ul>
                    <!-- Left links -->
                </div>
                <!-- Collapsible wrapper -->

                <!-- Right elements -->
                <div class="d-flex d-none d-md-flex align-items-center">

                    <!-- Icons -->
                    <a href="{{ route('welcome') }}" class="position-relative text-reset me-3">
                        <i class="fas fa-home fa-lg"></i>
                    </a>
                    <a class="position-relative text-reset me-3" href="{{ route('store.cart', $user->id) }}">
                        <i class="fas fa-shopping-cart fa-lg"></i>
                        @if (session('cart_' . $user->id) && count(session('cart_' . $user->id)) > 0)
                            <span
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning">
                                {{ array_sum(array_map(function ($item) {return $item['quantity'];}, session('cart_' . $user->id))) }}
                            </span>
                        @endif
                    </a>
                    @guest
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item d-flex">
                                <a class="nav-link me-2" href="{{ route('login') }}">Login</a>
                                <a class="nav-link me-2" href="{{ route('register') }}">Register</a>
                            </li>
                        </ul>
                    @else
                        <!-- Avatar -->
                        <div class="dropdown">
                            <a class="dropdown-toggle d-flex align-items-center hidden-arrow" href="#"
                                id="navbarDropdownMenuAvatar" role="button" data-mdb-toggle="dropdown"
                                aria-expanded="false">
                                @if (Auth::user()->image)
                                    <img src="{{ asset('storage/user/image/' . Auth::user()->image) }}"
                                        class="rounded-circle" height="27" alt="img" loading="lazy" />
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
                </div>
                <!-- Right elements -->
            </div>
            <!-- Container wrapper -->
        </nav>
    </header>

    <div style="min-height: calc(100vh - 117px);">
        <div class="container">
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
