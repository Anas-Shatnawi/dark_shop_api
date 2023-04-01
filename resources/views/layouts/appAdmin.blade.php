<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Chakra+Petch&display=swap" rel="stylesheet">
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    {{-- boxicons css --}}
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    {{-- styles --}}
    <link rel="stylesheet" href="{{asset('css/appAdmin.css')}}">
    @yield('styles')
</head>

<body>
    <div id="app">
        <nav class="sidebar close">
            <header>
                <div class="image-text">
                    <span class="image">
                        <img src="{{asset('storage/adminImage/logo.png')}}" alt="dark-shop-logo">
                    </span>
                    <div class="text header-text">
                        <span class="name">Dark Shop</span>
                        <span class="profession">Admin Dashboard</span>
                    </div>
                </div>
                <i class="bx bx-chevron-right toggle" type='solid' name='chevrons-right'></i>
            </header>
            <div class="menu-bar">
                <div class="menu">
                    <ul class="menu-links">
                        <li class="nav-link">
                            <a href="{{route('dashboard')}}">
                                <i class="bx bx-home-alt icon"></i>
                                <span class="text nav-text">Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-link">
                            <a href="#">
                                <i class='bx bxs-group icon'></i>
                                <span class="text nav-text">Users</span>
                            </a>
                        </li>
                        <li class="nav-link">
                            <a href="#">
                                <i class="bx bxs-store-alt icon"></i>
                                <span class="text nav-text">Stores</span>
                            </a>
                        </li>
                        <li class="nav-link">
                            <a href="#">
                                <i class="bx bx-mail-send icon"></i>
                                <span class="text nav-text">Send Email</span>
                            </a>
                        </li>
                        <li class="nav-link">
                            <a href="#">
                                <i class="bx bxs-package icon"></i>
                                <span class="text nav-text">Products</span>
                            </a>
                        </li>
                        <li class="nav-link">
                            <a href="#">
                                <i class="bx bxs-receipt icon"></i>
                                <span class="text nav-text">Orders</span>
                            </a>
                        </li>
                        <li class="nav-link">
                            <a href="#">
                                <i class="bx bxs-category-alt icon"></i>
                                <span class="text nav-text">Categories</span>
                            </a>
                        </li>
                        <li class="nav-link">
                            <a href="#">
                                <i class="bx bxs-chat icon"></i>
                                <span class="text nav-text">Comments</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="bottom-content">
                    <li class="">
                        <a href="#">
                            <i class="bx bx-log-out icon"></i>
                            <span class="text nav-text">Logout</span>
                        </a>
                    </li>
                </div>
            </div>
        </nav>
        <main class="py-4 main-content">
            @yield('content')
        </main>
    </div>
    <script>
        document.querySelector('.toggle').addEventListener('click', () => {
            document.querySelector('.sidebar').classList.toggle('close')
        })
    </script>
    @yield('script')
    <script type="text/javascript" src="{{asset('js/appAdmin.js')}}"></script>
</body>

</html>