<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Section</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>

<body>
    <header class="bg-blue-900 text-white">
        <!-- Navbar -->
        <nav class="container mx-auto flex max-w-7xl items-center justify-between px-6 py-1 lg:px-8">
            <!-- Logo -->
            <div class="flex-none items-center">
                <a href="/home" class="-m-1.5 p-1.5">
                    <span class="sr-only">ComplyPro</span>
                    <img class="h-8 w-auto" src="https://complypro.com.au/homeres/img/logo.png" alt="ComplyPro Logo">
                </a>
            </div>
                    <!-- Right Side Of Navbar -->
            <!-- Right Side Navbar -->
            <div>
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                </div>
        </nav>

    </header>
        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
