<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ComplyPro - Powering Safe Compliance</title>
    
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

@vite(['resources/sass/app.scss', 'resources/js/app.js'])
<link rel="stylesheet" href="{{ asset('homeres/css/style.css') }}">
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-s">      
        <div class="container">            
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name>', 'ComplyPro>') }}
            </a>  
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
            <ul class="navbar-nav me-auto" style="color: black;">
                <li>&nbsp;<i>Requisite-Links-to-Authorisations</i>&nbsp;</li> &nbsp;|&nbsp; 
                <li><a href="/link_table/module">&nbsp;Module&nbsp;</a></li>
                <li><a href="/link_table/f2f">&nbsp;Face2face&nbsp;</a></li>
                <li><a href="/link_table/induction">&nbsp;Induction&nbsp;</a></li>
                <li><a href="/link_table/license">&nbsp;License&nbsp;</a></li> &nbsp;|&nbsp; 
                <li><a href="/authorisations">&nbsp;Show-links&nbsp;</a></li> &nbsp;|&nbsp; 
                <li><a href="/">&nbsp;Home&nbsp;</a></li>
            </ul>
            <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ms-auto">
                @guest
                <script>
                    window.location.href = "{{ route('login') }}";
                </script>
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
                </ul>
            </div>    
        </div>
    </nav>
    <div class="content">
        <!-- Dynamic content will be rendered here -->
        
        @yield('content')
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fetch data from your database and render it dynamically
            fetch('/api/data')
                .then(response => response.json())
                .then(data => {
                    const contentDiv = document.querySelector('.content');
                    contentDiv.innerHTML = data.htmlContent; // Replace with your dynamic content
                });
        });
    </script>
        <!-- Footer -->
        <footer class="footer">
            &copy; 2024 ComplyPro  <a href="#">Cookie Policy</a>   <a href="#">Privacy Policy</a>   <a href="#">Disclaimer</a>
        </footer>
</body>
</html>