<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth" @if(app()->getLocale() === 'ar') dir="rtl" @else dir="ltr" @endif>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'ImmoSyn - La gestion de copropriété réinventée')</title>
    <link rel="icon" href="{{ asset('logo.png') }}" type="image/png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Styles / Scripts -->
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
                                50: '#f0f6ff',
                                100: '#e0edff',
                                300: '#b3d4ff',
                                500: '#3b82f6',
                                600: '#1d4ed8',
                                700: '#1e40af',
                                800: '#001F74', // Logo Navy
                                900: '#0865FD', // Logo Electric Blue
                            }
                        },
                        animation: {
                            'blob': 'blob 7s infinite',
                            'fade-in-up': 'fadeInUp 0.8s ease-out forwards',
                            'bounce-slow': 'bounce 3s infinite',
                        },
                        keyframes: {
                            blob: {
                                '0%': { transform: 'translate(0px, 0px) scale(1)' },
                                '33%': { transform: 'translate(30px, -50px) scale(1.1)' },
                                '66%': { transform: 'translate(-20px, 20px) scale(0.9)' },
                                '100%': { transform: 'translate(0px, 0px) scale(1)' },
                            },
                            fadeInUp: {
                                '0%': { opacity: '0', transform: 'translateY(20px)' },
                                '100%': { opacity: '1', transform: 'translateY(0)' },
                            }
                        }
                    }
                }
            }
        </script>
    @endif

    <style>
        .font-heading { font-family: 'Outfit', sans-serif; }
        .glass-panel {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.4);
        }
        .dark .glass-panel {
            background: rgba(17, 24, 39, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 antialiased selection:bg-teal-500 selection:text-white transition-colors duration-300">

    <!-- Navigation -->
    @include('partials.public.navbar')

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    @include('partials.public.footer')

    <!-- Scripts stack -->
    @stack('scripts')
    <!-- Speed Optimization: preloads pages on hover -->
    <script src="https://cdn.jsdelivr.net/npm/instant.page@5.2.0/instantpage.us.js" type="module" defer></script>
</body>
</html>
