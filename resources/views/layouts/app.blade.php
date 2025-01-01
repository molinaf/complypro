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

<header class="bg-blue-900 text-white">
    <div class="text-center py-1 bg-blue-900">
        <h1 class="text-2xl ">Table Maintenance</h1>
    </div>
    <!-- Navbar -->
    <nav class="container mx-auto flex max-w-7xl items-center justify-between px-6 py-1 lg:px-8">
        <!-- Logo -->
        <div class="flex-none items-center">
            <a href="/home" class="-m-1.5 p-1.5">
                <span class="sr-only">ComplyPro</span>
                <img class="h-8 w-auto" src="https://complypro.com.au/homeres/img/logo.png" alt="ComplyPro Logo">
            </a>
        </div>

        <!-- Navigation Links -->
        <div class="flex items-center justify-between w-full">
            <!-- Hamburger Icon for Mobile -->
            <div class="block md:hidden">
                <button id="navbar-toggle" class="text-white focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>

            <!-- Navbar Links -->
            <div id="navbar-links" class="hidden sm:flex flex-row flex-wrap md:space-x-4 md:items-center w-full text-lg py-2">
                <a href="/main" class="block px-1 py-2 hover:text-blue-600">Diagram</a>
                <div class="block px-0 py-2" >|</div>
                <a href="/categories" class="block px-1 py-2 hover:text-blue-600">Categories</a>
                <a href="/authorisations" class="block px-1 py-2 hover:text-blue-600">Authorisations</a>
                <div class="block px-0 py-2" >|</div>
                <a href="/modules" class="block px-1 py-2 hover:text-blue-600">Modules</a>
                <a href="/f2fs" class="block px-1 py-2 hover:text-blue-600">Face2Face</a>
                <a href="/inductions" class="block px-1 py-2 hover:text-blue-600">Inductions</a>
                <a href="/licenses" class="block px-1 py-2 hover:text-blue-600">Licenses</a>
                <div class="block px-0 py-2" >|</div>
                <a href="/requisites" class="block px-1 py-2 hover:text-blue-600">Requisites</a>
            </div>

            <!-- Right Side Navbar -->
            <div>
                @guest
                    <script>
                        window.location.href = "{{ route('login') }}";
                    </script>
                @else
                <div class="relative">
                    <button class="text-white focus:outline-none" id="dropdown-button">
                        {{ Auth::user()->name }}
                    </button>
                    <!-- Dropdown -->
                    <div 
                        class="absolute hidden bg-white shadow-lg right-0 mt-2 py-2 w-48"
                        id="dropdown-menu">
                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                           class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                            {{ __('Logout') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    </div>
                </div>
                
                <!-- JavaScript for Dropdown -->
                <script>
                    const dropdownButton = document.getElementById('dropdown-button');
                    const dropdownMenu = document.getElementById('dropdown-menu');
                
                    dropdownButton.addEventListener('click', () => {
                        dropdownMenu.classList.toggle('hidden');
                    });
                
                    // Close dropdown when clicking outside
                    document.addEventListener('click', (event) => {
                        if (!dropdownButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
                            dropdownMenu.classList.add('hidden');
                        }
                    });
                </script>
                    </div>
                @endguest
            </div>
        </div>
    </nav>
</header>

<!-- JavaScript for toggling the Navbar -->
<script>
    const navbarToggle = document.getElementById('navbar-toggle');
    const navbarLinks = document.getElementById('navbar-links');

    navbarToggle.addEventListener('click', () => {
        navbarLinks.classList.toggle('hidden');
    });
</script>

<!-- Content -->
<div class="container mx-auto p-4">
    @yield('content')
</div>

<!-- Dynamic Content Script -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        fetch('/api/data')
            .then(response => response.json())
            .then(data => {
                const contentDiv = document.querySelector('.content');
                contentDiv.innerHTML = data.htmlContent;
            });
    });
</script>

<!-- Footer Section -->
<footer class="bg-blue-900 text-white text-center py-4">
    <p class="text-sm">
    &copy; 2024 ComplyPro. All rights reserved.
    </p>
</footer>

</body>
</html>
