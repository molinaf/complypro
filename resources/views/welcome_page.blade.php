<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <style type="text/tailwindcss">
    @layer utilities {
      .content-auto {
        content-visibility: auto;
      }
    }
  </style>
</head>
  <body>
    <!-- Header Section -->
    <header class="bg-blue-900 text-white">
      <nav class="container mx-auto flex max-w-7xl items-center justify-between px-7 py-1 lg:px-8">
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
          <div id="navbar-links" class="hidden sm:flex flex-row flex-wrap md:space-x-4 md:items-center w-full text-base py-2">
            <a href="#about" class="text-base font-semibold text-white hover:bg-blue-800 px-4 py-2 rounded transition duration-300">About</a>
            <a href="#key" class="text-base font-semibold text-white hover:bg-blue-800 px-4 py-2 rounded transition duration-300">Features</a>
            <a href="#why" class="text-base font-semibold text-white hover:bg-blue-800 px-4 py-2 rounded transition duration-300">Why Choose</a>
          </div>
        <!-- Log in and Register -->
        <div>
          <a href="/login" class="px-1 py-1 hover:text-blue-600">Log in </a>
          <a href="/register" class="px-1 py-1 hover:text-blue-600">Register</a>
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

    <!-- Hero Section -->
    <a name="about"></a>
    <section class="text-center py-12 bg-gray-100">
      <div class="container mx-auto px-6">
        <h1 class="text-4xl font-bold text-gray-900">
          Powering Safe Compliance for Utilities
        </h1>
        <p class="mt-4 text-gray-700 text-lg">
            ComplyPro is an innovative compliance management platform designed for utility industries. It streamlines the training, induction, and authorisation processes to ensure safety and regulatory compliance while boosting operational efficiency.
        </p>
        <div class="mt-6">
          <button class="bg-orange-500 hover:bg-orange-600 text-white font-semibold px-6 py-3 rounded">Request a Demo</button>
        </div>
      </div>
    </section>

    <!-- Why Choose Section -->
    <a name="why"></a>
    <section class="py-10">
      <div class="container mx-auto px-6 text-center">
        <h2 class="text-2xl font-bold text-gray-900">Why Choose ComplyPro?</h2>
        <ul class="mt-4 text-gray-700 text-lg text-left mx-auto max-w-3xl">
          <li class="mb-2">✔ Ensure safety with authorised personnel for critical infrastructure.</li>
          <li class="mb-2">✔ Stay compliant with minimal administrative burden.</li>
          <li class="mb-2">✔ Streamline workflows, saving time and resources.</li>
          <li>✔ Customise features for power, water, and gas sectors.</li>
        </ul>
      </div>
    </section>

<!-- Key Features Section -->
<a name="key"></a>
<section class="bg-gray-100 py-10">
  <div class="container mx-auto px-6">
    <h2 class="text-2xl font-bold text-center text-gray-900">Key Features</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8 mt-8">
      <!-- Feature 1 -->
      <div class="text-center bg-white p-4 shadow-md rounded">
        <img
          src="https://complypro.com.au/welcome/img/photo-01.png"
          alt="Compliance Management"
          class="w-full h-32 object-cover rounded"
        />
        <h3 class="font-semibold mt-4 text-gray-900">
          Centralised Compliance Management
        </h3>
        <p class="mt-2 text-gray-600 text-sm">
          Track certifications, licenses, and training for all your staff in one place.
        </p>
      </div>
      <!-- Feature 2 -->
      <div class="text-center bg-white p-4 shadow-md rounded">
        <img
          src="https://complypro.com.au/welcome/img/photo-02.png"
          alt="Customisable Workflows"
          class="w-full h-32 object-cover rounded"
        />
        <h3 class="font-semibold mt-4 text-gray-900">Customisable Workflows</h3>
        <p class="mt-2 text-gray-600 text-sm">
          Tailor compliance processes to your organisation and industry needs.
        </p>
      </div>
      <!-- Feature 3 -->
      <div class="text-center bg-white p-4 shadow-md rounded">
        <img
          src="https://complypro.com.au/welcome/img/photo-03.png"
          alt="Real-Time Tracking"
          class="w-full h-32 object-cover rounded"
        />
        <h3 class="font-semibold mt-4 text-gray-900">Real-Time Tracking</h3>
        <p class="mt-2 text-gray-600 text-sm">
          Monitor the status of activities instantly, ensuring nothing is missed.
        </p>
      </div>
      <!-- Feature 4 -->
      <div class="text-center bg-white p-4 shadow-md rounded">
        <img
          src="https://complypro.com.au/welcome/img/photo-05.png"
          alt="Automated Reporting"
          class="w-full h-32 object-cover rounded"
        />
        <h3 class="font-semibold mt-4 text-gray-900">Automated Reporting</h3>
        <p class="mt-2 text-gray-600 text-sm">
          Generate audit-ready reports automatically, saving time and reducing errors.
        </p>
      </div>
      <!-- Feature 5 -->
      <div class="text-center bg-white p-4 shadow-md rounded">
        <img
          src="https://complypro.com.au/welcome/img/photo-04.png"
          alt="Scheduling of Training"
          class="w-full h-32 object-cover rounded"
        />
        <h3 class="font-semibold mt-4 text-gray-900">Scheduling of Training</h3>
        <p class="mt-2 text-gray-600 text-sm">
        Plan, schedule, and monitor training activities efficiently.
        </p>
      </div>
    </div>
  </div>
</section>


    <!-- Footer Section -->
    <footer class="bg-blue-900 text-white text-center py-4">
      <p class="text-sm">
        &copy; 2024 ComplyPro. All rights reserved.
      </p>
    </footer>
  </body>
</html>
