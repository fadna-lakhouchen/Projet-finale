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
                <a href="/login" class="hidden sm:inline-flex text-sm font-semibold text-gray-700 hover:text-teal-600 dark:text-gray-200 dark:hover:text-brand-300 transition-colors">Se connecter</a>
                <a href="/login" class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-bold text-white transition-all bg-gradient-to-r from-brand-800 to-teal-500 rounded-xl hover:from-brand-900 hover:to-teal-600 shadow-[0_0_20px_rgba(20,184,166,0.3)] hover:shadow-[0_0_25px_rgba(20,184,166,0.5)] hover:-translate-y-1">
                    Démarrer
                </a>
            </div>
        </div>
    </div>
</header>
