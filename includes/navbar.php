<!-- navbar.php -->

<!-- Navigation Bar -->
<header class="bg-red-600 text-white shadow-md">
  <div class="max-w-7xl mx-auto flex items-center justify-between px-6 py-4">

    <!-- Left: Logo and Name -->
    <div class="flex items-center gap-3">
      <img src="img/logo.png" alt="KARNATAKA NEWS" class="w-10 h-10 shadow-lg object-cover border-2 border-white">
      <span class="text-xl font-bold tracking-wide">KARNATAKA NEWS</span>
    </div>

    <!-- Center: Navigation Links -->
    <nav class="hidden md:flex gap-10 text-base font-semibold tracking-wide">
      <a href="index.php" class="hover:text-gray-200 transition duration-300">Home</a>
      <a href="read.php" class="hover:text-gray-200 transition duration-300">Read</a>
      <a href="latest.php" class="hover:text-gray-200 transition duration-300">Latest</a>
    </nav>

    <!-- Right: Icons -->
    <div class="hidden md:flex items-center gap-4">
      <button class="hover:bg-red-700 transition p-2 rounded">🔔</button>
    </div>

    <!-- Mobile Menu Button -->
    <div class="md:hidden">
      <button class="focus:outline-none">☰</button>
    </div>

  </div>
</header>

<!-- Secondary Navigation -->
<nav class="bg-white border-t border-gray-100 shadow-sm text-sm px-6 py-3">
  <div class="max-w-7xl mx-auto flex justify-center flex-wrap gap-x-6 font-medium text-gray-600 overflow-x-auto whitespace-nowrap">
    <a href="#" class="text-red-600 font-bold">Home</a>
    <a href="state.php" class="hover:text-red-500">State</a>
    <a href="national.php" class="hover:text-red-500">National</a>
    <a href="international.php" class="hover:text-red-500">International</a>
    <a href="health.php" class="hover:text-red-500">Health</a>
    <a href="employement.php" class="hover:text-red-500">Employment</a>
    <a href="education.php" class="hover:text-red-500">Education</a>
    <a href="sports.php" class="hover:text-red-500">Sports</a>
    <a href="videos.php" class="hover:text-red-500">Videos</a>
    <a href="contact.php" class="hover:text-red-500">Contact</a>
  </div>
</nav>

<!-- Floating Notification Bell -->
<div id="notify-bell"
     class="fixed bottom-5 right-5 z-50 bg-white p-3 rounded-full shadow-lg border hover:shadow-xl transition hover:bg-blue-100 cursor-pointer">
  <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-600 animate-pulse" fill="none"
       viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
    <path stroke-linecap="round" stroke-linejoin="round"
          d="M15 17h5l-1.405-1.405C18.21 14.79 18 13.918 18 13V9a6 6 0 10-12 0v4c0 .918-.21 1.79-.595 2.595L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
  </svg>
</div>
