<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light" @if(app()->getLocale() === 'ar') dir="rtl" @else dir="ltr" @endif>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'ImmoSyn') }}</title>
    <link rel="icon" href="{{ asset('logo.png') }}" type="image/png">
    
    <!-- Scripts & Frameworks -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/preline/dist/preline.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    @vite(['resources/js/app.js'])

    <!-- Localized Custom Flatpickr Calendar -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/fr.js"></script>
    
    <!-- Premium Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind Configuration -->
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { 
                        sans: ['Outfit', 'Plus Jakarta Sans', 'Inter', 'sans-serif'] 
                    },
                    colors: { 
                        primary: { 
                            50: '#f5f7ff', 
                            100: '#ebf0fe', 
                            200: '#ced9fd', 
                            300: '#b1c2fb', 
                            400: '#7694f8', 
                            500: '#3b66f5', 
                            600: '#2c4db8', 
                            700: '#1d3278', 
                            800: '#152559', 
                            900: '#0e193c' 
                        },
                        slate: {
                            950: '#090D16'
                        }
                    },
                    boxShadow: {
                        'premium': '0 10px 30px -10px rgba(0, 0, 0, 0.04), 0 1px 1px rgba(0, 0, 0, 0.01)',
                        'premium-hover': '0 20px 40px -15px rgba(59, 102, 245, 0.1), 0 1px 2px rgba(59, 102, 245, 0.02)',
                        'glass': '0 8px 32px 0 rgba(31, 38, 135, 0.04)',
                    }
                }
            }
        }
    </script>
    
    <!-- Custom Visual Polish (Zwaq) -->
    <style>
        .logo-transparent { mix-blend-mode: multiply; }
        [x-cloak] { display: none !important; }
        
        /* Glassmorphism Utilities */
        .glass-panel {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        .dark .glass-panel {
            background: rgba(15, 23, 42, 0.65);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        /* Fine Scrollbars */
        ::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(156, 163, 175, 0.25);
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(156, 163, 175, 0.45);
        }
        
        /* Animated Ring/Glow transitions */
        .glow-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .glow-hover:hover {
            box-shadow: 0 0 20px 2px rgba(59, 102, 245, 0.15);
            transform: translateY(-1px);
        }
        
        /* Fallback for Preline overlays */
        .hs-overlay.open { pointer-events: auto !important; }
        .hs-overlay.open>div { opacity: 1 !important; margin-top: 1.75rem !important; }
    </style>
    @stack('styles')
</head>

<body x-data="{ sidebarOpen: false }" {{-- RESPONSIVE : État global Alpine.js pour contrôler l'ouverture/fermeture du menu sur mobile --}} class="bg-[#F8FAFC] text-slate-800 dark:bg-[#080B11] dark:text-neutral-200 min-h-screen transition-colors duration-300">
    <!-- Navbar (Header) -->
    <x-navbar />

    <!-- Sidebar -->
    @auth
        <x-sidebar />
        <!-- Backdrop for mobile -->
        {{-- RESPONSIVE : Voile d'arrière-plan flouté (glassmorphism) visible uniquement sur mobile/tablette (<lg) lorsque le menu est ouvert. Un clic dessus ferme le menu. --}}
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-slate-950/40 backdrop-blur-sm z-[59] lg:hidden" x-cloak></div>
    @endauth

    <!-- Main Content Grid -->
    <main class="w-full pt-6 px-4 sm:px-6 md:px-8 lg:ps-72 pb-16">
        <div class="max-w-[85rem] mx-auto">
            @yield('content')
        </div>
    </main>

    <!-- Global AutoInits -->
    <script>
        lucide.createIcons();

        // Initial Preline auto-init on page load
        window.addEventListener('load', () => {
            if (window.HSStaticMethods) {
                window.HSStaticMethods.autoInit();
            }
        });

        // Re-init Preline AFTER Alpine.js finishes rendering components
        document.addEventListener('alpine:init', () => {
            // Use queueMicrotask to wait for Alpine to finish DOM manipulation
            queueMicrotask(() => {
                if (window.HSStaticMethods) {
                    window.HSStaticMethods.autoInit();
                }
            });
        });

        // Also re-init when Alpine updates the DOM dynamically (navigation, modals, etc.)
        document.addEventListener('alpine:initialized', () => {
            if (window.HSStaticMethods) {
                window.HSStaticMethods.autoInit();
            }
        });

        // Re-create Lucide icons after Alpine renders new content without infinite recursion
        const observer = new MutationObserver((mutations) => {
            let hasNewIcons = false;
            for (const mutation of mutations) {
                if (mutation.addedNodes.length > 0) {
                    for (const node of mutation.addedNodes) {
                        if (node.nodeType === 1) { // Element node
                            if (node.hasAttribute('data-lucide') || node.querySelector('[data-lucide]')) {
                                hasNewIcons = true;
                                break;
                            }
                        }
                    }
                }
                if (hasNewIcons) break;
            }

            if (hasNewIcons) {
                observer.disconnect();
                lucide.createIcons();
                observer.observe(document.body, { childList: true, subtree: true });
            }
        });
        observer.observe(document.body, { childList: true, subtree: true });
    </script>
    @stack('scripts')
    <!-- Speed Optimization: preloads pages on hover -->
    <script src="https://cdn.jsdelivr.net/npm/instant.page@5.2.0/instantpage.us.js" type="module" defer></script>
</body>
</html>
