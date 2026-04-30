<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Authentification - ImmoSyndic')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                darkMode: 'class',
                theme: {
                    extend: {
                        fontFamily: { 
                            sans: ['Inter', 'sans-serif'],
                            heading: ['Outfit', 'sans-serif'],
                        },
                        colors: {
                            brand: {
                                50: '#e6f0fa',
                                100: '#dcf0fa',
                                300: '#d2e6f0',
                                500: '#3c648c',
                                600: '#28466e',
                                700: '#1e3c64',
                                800: '#1e325a',
                                900: '#1e285a',
                            }
                        }
                    }
                }
            }
        </script>
    @endif
</head>
<body class="bg-gray-50 dark:bg-slate-900 font-sans antialiased flex items-center justify-center min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <main class="w-full max-w-md">
        @yield('content')
    </main>
</body>
</html>
