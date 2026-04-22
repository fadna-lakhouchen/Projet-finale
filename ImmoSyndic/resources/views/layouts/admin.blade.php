<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Dashboard - ImmoSyndic')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="https://cdn.jsdelivr.net/npm/preline/dist/preline.min.js"></script>
        <script>
            tailwind.config = {
                darkMode: 'class',
                theme: {
                    extend: {
                        fontFamily: { 
                            sans: ['Inter', 'sans-serif'],
                        },
                        colors: {
                            primary: {
                                50: '#f5f7ff',
                                100: '#ebf0fe',
                                200: '#ced9fd',
                                300: '#b1c2fb',
                                400: '#7694f8',
                                500: '#3b66f5',
                                600: '#355cdc',
                                700: '#2c4db8',
                                800: '#233d93',
                                900: '#1d3278',
                            }
                        }
                    }
                }
            }
        </script>
    @endif

    <style>
        [x-cloak] { display: none !important; }
        .hs-overlay.open { pointer-events: auto !important; }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 dark:bg-neutral-900 transition-colors duration-300">

    <!-- Header -->
    @include('partials.admin.header')

    <!-- Sidebar -->
    @include('partials.admin.sidebar')

    <!-- Content -->
    <main id="content" class="w-full pt-10 px-4 sm:px-6 md:px-8 lg:ps-72 pb-12">
        @yield('content')
    </main>

    <!-- Global Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });
    </script>
    @stack('scripts')
</body>
</html>
