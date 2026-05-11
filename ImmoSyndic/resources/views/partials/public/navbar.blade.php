<header class="fixed top-0 w-full z-50 transition-all duration-300 backdrop-blur-md bg-white/70 dark:bg-gray-900/80 border-b border-gray-200/50 dark:border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- Logo -->
            <div class="flex items-center gap-2 group cursor-pointer">
                <img src="{{ asset('logo.png') }}" alt="Logo" class="h-12 w-auto object-contain group-hover:rotate-6 group-hover:scale-105 transition-all duration-300">
                <span class="font-heading font-extrabold text-2xl tracking-tight text-gray-900 dark:text-white">
                    Immo<span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-800 to-teal-500">Syndic</span>
                </span>
            </div>

            <!-- Desktop Menu -->
            <nav class="hidden md:flex items-center gap-8 font-medium">
                <a href="#features" class="text-gray-600 hover:text-teal-600 dark:text-gray-300 dark:hover:text-teal-400 transition-colors">Fonctionnalités</a>
                <a href="#solutions" class="text-gray-600 hover:text-teal-600 dark:text-gray-300 dark:hover:text-teal-400 transition-colors">Pour qui ?</a>
                <a href="#about" class="text-gray-600 hover:text-teal-600 dark:text-gray-300 dark:hover:text-teal-400 transition-colors">À propos</a>
            </nav>

            <!-- Actions -->
            <div class="flex items-center gap-4">
                @guest
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-teal-600 dark:text-gray-300 dark:hover:text-teal-400 transition-colors font-medium">Connexion</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-bold text-white transition-all bg-gradient-to-r from-brand-800 to-teal-500 rounded-xl hover:from-brand-900 hover:to-teal-600 shadow-[0_0_20px_rgba(20,184,166,0.3)] hover:shadow-[0_0_25px_rgba(20,184,166,0.5)] hover:-translate-y-1">
                            Inscription
                        </a>
                    @endif
                @else
                    <div class="flex items-center gap-3">
                        <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-bold text-white transition-all bg-gradient-to-r from-brand-800 to-teal-500 rounded-xl hover:from-brand-900 hover:to-teal-600 shadow-md">
                            Mon Compte
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-600 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400 transition-colors font-medium text-sm">
                                Déconnexion
                            </button>
                        </form>
                    </div>
                @endguest
            </div>

        </div>
    </div>
</header>
