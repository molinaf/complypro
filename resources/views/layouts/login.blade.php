<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Main Menu - Dashboard</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <!-- Header -->
    <header class="bg-blue-900 text-white">
        <!-- Navbar -->
        <nav class="container mx-auto flex max-w-7xl items-center justify-between px-6 py-4">
            <!-- Logo -->
            <div>
                <a href="/" class="flex items-center space-x-2">
                    <img class="h-8 w-auto" src="https://complypro.com.au/homeres/img/logo.png" alt="ComplyPro Logo">
                    <span class="text-lg font-semibold">ComplyPro</span>
                </a>
            </div>
            <!-- Navbar Links -->
            <div class="hidden md:flex flex-row space-x-6 items-center text-lg">
                <span class="text-white font-medium">Main Menu - Dashboard</span>
            </div>
            <!-- Authentication Links -->
            <div class="relative">
                @guest
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}" class="text-white hover:text-blue-300">{{ __('Login') }}</a>
                    @endif
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="ml-4 text-white hover:text-blue-300">{{ __('Register') }}</a>
                    @endif
                @else
                    <button id="dropdown-button" class="text-white focus:outline-none">
                        {{ Auth::user()->name }}
                    </button>
                    <!-- Dropdown -->
                    <div id="dropdown-menu" class="absolute hidden bg-white shadow-lg right-0 mt-2 py-2 w-48 rounded-lg">
                        <a href="{{ route('logout') }}" 
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                           class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                            {{ __('Logout') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    </div>
                @endguest
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="py-8">
        <div class="container mx-auto px-6">
            @yield('content')
        </div>
    </main>

    <!-- Footer Section -->
    <footer class="bg-blue-900 text-white text-center py-4">
      <p class="text-sm">
        &copy; 2024 ComplyPro. All rights reserved.
      </p>
    </footer>

    <!-- JavaScript for Dropdown -->
    <script>
        const dropdownButton = document.getElementById('dropdown-button');
        const dropdownMenu = document.getElementById('dropdown-menu');

        dropdownButton?.addEventListener('click', () => {
            dropdownMenu?.classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (event) => {
            if (!dropdownButton?.contains(event.target) && !dropdownMenu?.contains(event.target)) {
                dropdownMenu?.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
