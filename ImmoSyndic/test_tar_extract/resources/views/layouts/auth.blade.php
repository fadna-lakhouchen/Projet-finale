<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light h-full" @if(app()->getLocale() === 'ar') dir="rtl" @else dir="ltr" @endif>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'ImmoSyn') }} - Auth</title>
    <link rel="icon" href="{{ asset('logo.png') }}" type="image/png">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/preline/dist/preline.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    @vite(['resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
    
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
</head>

<body class="bg-gray-50 dark:bg-neutral-900 flex items-center justify-center min-h-screen py-4 relative">
    <div class="absolute top-4 {{ app()->getLocale() === 'ar' ? 'left-4' : 'right-4' }} z-50">
        <x-language-switcher />
    </div>

    @yield('content')
    
    <script>lucide.createIcons();</script>
    <!-- Speed Optimization: preloads pages on hover -->
    <script src="https://cdn.jsdelivr.net/npm/instant.page@5.2.0/instantpage.us.js" type="module" defer></script>
</body>
</html>
