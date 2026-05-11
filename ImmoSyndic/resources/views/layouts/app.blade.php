<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'ImmoSyndic') }}</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/preline/dist/preline.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: { primary: { 50: '#eef2ff', 100: '#e0e7ff', 200: '#c7d2fe', 300: '#a5b4fc', 400: '#818cf8', 500: '#6366f1', 600: '#4f46e5', 700: '#4338ca', 800: '#3730a3', 900: '#312e81' } }
                }
            }
        }
    </script>
    
    <style>
        .logo-transparent { mix-blend-mode: multiply; }
        /* Fallback for Preline plugin variants when using Tailwind CDN */
        .hs-overlay.open { pointer-events: auto !important; }
        .hs-overlay.open>div { opacity: 1 !important; margin-top: 1.75rem !important; }
        [x-cloak] { display: none !important; }
    </style>
    @stack('styles')
</head>

<body class="bg-gray-50 dark:bg-neutral-900">
    <!-- Navbar (Header) -->
    <x-navbar />

    <!-- Sidebar -->
    @auth
        <x-sidebar />
    @endauth

    <!-- Main Content -->
    <main class="w-full pt-6 px-4 sm:px-6 md:px-8 lg:ps-72 pb-12">
        @yield('content')
    </main>

    <script>
        lucide.createIcons();
        window.addEventListener('load', () => {
            if (window.HSStaticMethods) {
                window.HSStaticMethods.autoInit();
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
