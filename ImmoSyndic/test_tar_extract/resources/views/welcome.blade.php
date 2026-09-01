<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ImmoSyn - Bienvenue</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
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
                        fontFamily: { sans: ['Inter', 'sans-serif'] },
                    }
                }
            }
        </script>
    @endif
</head>
<body class="bg-gray-50 dark:bg-neutral-900 min-h-screen flex items-center justify-center p-6 text-gray-900 dark:text-gray-100">
    <div class="max-w-3xl w-full text-center space-y-8">
        <!-- Icon/Logo Area -->
        <div class="relative inline-flex">
            <div class="size-24 bg-blue-600 rounded-3xl flex items-center justify-center text-white shadow-2xl shadow-blue-500/20 rotate-3 transition-transform hover:rotate-0">
                <i data-lucide="building-2" class="size-12"></i>
            </div>
            <div class="absolute -bottom-2 -p-2 size-10 bg-teal-500 rounded-xl flex items-center justify-center text-white shadow-lg -rotate-12 border-4 border-gray-50 dark:border-neutral-900">
                <i data-lucide="shield-check" class="size-6"></i>
            </div>
        </div>

        <!-- Welcome Text -->
        <div class="space-y-4">
            <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white tracking-tight sm:text-5xl">
                Bienvenue sur <span class="text-blue-600">ImmoSyn</span>
            </h1>
            <p class="text-lg text-gray-600 dark:text-neutral-400 max-w-xl mx-auto">
                La plateforme complète et intégrée pour la gestion de votre copropriété. Connectez-vous à votre espace dédié ci-dessous.
            </p>
        </div>

        <!-- Quick Tips (Access Cards) -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-8">
            <a href="/login" class="block p-5 bg-white border border-gray-200 rounded-2xl dark:bg-neutral-800 dark:border-neutral-700 text-left hover:border-blue-500 dark:hover:border-blue-500 transition-colors shadow-sm hover:shadow-md cursor-pointer group">
                <div class="size-8 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600 mb-3 dark:bg-blue-900/30 dark:text-blue-500 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                    <i data-lucide="user" class="size-4"></i>
                </div>
                <h3 class="font-bold text-gray-800 dark:text-neutral-200">Espace Résident</h3>
                <p class="text-sm text-gray-500 dark:text-neutral-500 mt-1">Consultez vos charges, paiements et documents.</p>
            </a>
            
            <a href="/login" class="block p-5 bg-white border border-gray-200 rounded-2xl dark:bg-neutral-800 dark:border-neutral-700 text-left hover:border-teal-500 dark:hover:border-teal-500 transition-colors shadow-sm hover:shadow-md cursor-pointer group">
                <div class="size-8 bg-teal-100 rounded-lg flex items-center justify-center text-teal-600 mb-3 dark:bg-teal-900/30 dark:text-teal-500 group-hover:bg-teal-600 group-hover:text-white transition-colors">
                    <i data-lucide="briefcase" class="size-4"></i>
                </div>
                <h3 class="font-bold text-gray-800 dark:text-neutral-200">Espace Syndic</h3>
                <p class="text-sm text-gray-500 dark:text-neutral-500 mt-1">Gérez la copropriété, les immeubles et finances.</p>
            </a>
            
            <a href="/login" class="block p-5 bg-white border border-gray-200 rounded-2xl dark:bg-neutral-800 dark:border-neutral-700 text-left hover:border-amber-500 dark:hover:border-amber-500 transition-colors shadow-sm hover:shadow-md cursor-pointer group">
                <div class="size-8 bg-amber-100 rounded-lg flex items-center justify-center text-amber-600 mb-3 dark:bg-amber-900/30 dark:text-amber-500 group-hover:bg-amber-600 group-hover:text-white transition-colors">
                    <i data-lucide="settings" class="size-4"></i>
                </div>
                <h3 class="font-bold text-gray-800 dark:text-neutral-200">Espace Admin</h3>
                <p class="text-sm text-gray-500 dark:text-neutral-500 mt-1">Gérez les utilisateurs et configurez le portail.</p>
            </a>
        </div>

        <div class="pt-10 flex items-center justify-center gap-x-2 text-sm text-gray-400 dark:text-neutral-600">
            <i data-lucide="lock" class="size-4"></i>
            Accès sécurisé
        </div>
    </div>

    <script>lucide.createIcons();</script>
</body>
</html>
