<header x-data="{ mobileMenuOpen: false }" class="fixed top-0 w-full z-50 transition-all duration-300 backdrop-blur-md bg-white/70 dark:bg-gray-900/80 border-b border-gray-200/50 dark:border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- Logo -->
            <a href="/" class="flex items-center gap-2 group cursor-pointer">
                <img src="{{ asset('logo.png') }}" alt="Logo" class="h-12 w-auto object-contain group-hover:rotate-6 group-hover:scale-105 transition-all duration-300">
                <span class="font-heading font-extrabold text-2xl tracking-tight text-gray-900 dark:text-white">
                    Immo<span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-800 to-brand-900">Syn</span>
                </span>
            </a>

            <!-- Desktop Menu -->
            <nav class="hidden md:flex items-center gap-8 font-medium">
                <a href="#features" class="text-gray-600 hover:text-brand-900 dark:text-gray-300 dark:hover:text-blue-400 transition-colors">{{ __('Fonctionnalités') }}</a>
                <a href="#solutions" class="text-gray-600 hover:text-brand-900 dark:text-gray-300 dark:hover:text-blue-400 transition-colors">{{ __('Pour qui ?') }}</a>
                <a href="#about" class="text-gray-600 hover:text-brand-900 dark:text-gray-300 dark:hover:text-blue-400 transition-colors">{{ __('À propos') }}</a>
            </nav>

            <!-- Desktop Actions -->
            <div class="hidden md:flex items-center gap-4">
                <x-language-switcher />
                @guest
                    <a href="https://wa.me/212720366418?text=Bonjour%20ImmoSyn,%20je%20souhaite%20demander%20une%20d%C3%A9mo%20de%20l'application." target="_blank" class="text-gray-600 hover:text-brand-900 dark:text-gray-300 dark:hover:text-blue-400 transition-colors font-medium">{{ __('Connexion') }}</a>
                    @if (Route::has('register'))
                        <a href="https://wa.me/212720366418?text=Bonjour%20ImmoSyn,%20je%20souhaite%20demander%20une%20d%C3%A9mo%20de%20l'application." target="_blank" class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-bold text-white transition-all bg-gradient-to-r from-brand-800 to-brand-900 rounded-xl hover:from-brand-900 hover:to-brand-800 shadow-[0_4px_15px_rgba(8,101,253,0.3)] hover:-translate-y-0.5">
                            {{ __('Demander une démo') }}
                        </a>
                    @endif
                @else
                    <div class="flex items-center gap-3">
                        <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-bold text-white transition-all bg-gradient-to-r from-brand-800 to-brand-900 rounded-xl hover:from-brand-900 hover:to-brand-800 shadow-md">
                            {{ __('Mon Compte') }}
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-600 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400 transition-colors font-medium text-sm">
                                {{ __('Déconnexion') }}
                            </button>
                        </form>
                    </div>
                @endguest
            </div>

            <!-- Mobile Controls -->
            <div class="flex md:hidden items-center gap-2">
                <x-language-switcher />
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-neutral-800 rounded-xl transition-all focus:outline-none">
                    <!-- Hamburger icon when menu is closed -->
                    <svg x-show="!mobileMenuOpen" class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <!-- Close icon when menu is open -->
                    <svg x-show="mobileMenuOpen" class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

        </div>
    </div>

    <!-- Mobile Drawer / Menu -->
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         class="md:hidden border-b border-gray-200/50 bg-white/95 backdrop-blur-md dark:bg-gray-900/95 dark:border-gray-800 px-4 pt-2 pb-6 space-y-4 shadow-lg absolute left-0 w-full z-40"
         style="display: none;">
         <!-- Mobile Nav Links -->
         <nav class="flex flex-col gap-1 font-medium">
             <a href="#features" @click="mobileMenuOpen = false" class="text-gray-600 hover:text-brand-900 dark:text-gray-300 dark:hover:text-blue-400 py-3 transition-colors border-b border-gray-100 dark:border-neutral-800">{{ __('Fonctionnalités') }}</a>
             <a href="#solutions" @click="mobileMenuOpen = false" class="text-gray-600 hover:text-brand-900 dark:text-gray-300 dark:hover:text-blue-400 py-3 transition-colors border-b border-gray-100 dark:border-neutral-800">{{ __('Pour qui ?') }}</a>
             <a href="#about" @click="mobileMenuOpen = false" class="text-gray-600 hover:text-brand-900 dark:text-gray-300 dark:hover:text-blue-400 py-3 transition-colors border-b border-gray-100 dark:border-neutral-800">{{ __('À propos') }}</a>
         </nav>
         <!-- Mobile Actions -->
         <div class="flex flex-col gap-3 pt-2">
             @guest
                 <a href="https://wa.me/212720366418?text=Bonjour%20ImmoSyn,%20je%20souhaite%20demander%20une%20d%C3%A9mo%20de%20l'application." target="_blank" class="flex items-center justify-center py-3 text-sm font-semibold text-gray-700 hover:text-brand-900 border border-gray-200 rounded-xl dark:text-gray-300 dark:border-neutral-700 transition-colors bg-white/50 dark:bg-neutral-850/50">{{ __('Connexion') }}</a>
                 @if (Route::has('register'))
                     <a href="https://wa.me/212720366418?text=Bonjour%20ImmoSyn,%20je%20souhaite%20demander%20une%20d%C3%A9mo%20de%20l'application." target="_blank" class="flex items-center justify-center py-3 text-sm font-bold text-white bg-gradient-to-r from-brand-800 to-brand-900 rounded-xl hover:from-brand-900 hover:to-brand-800 shadow-md transition-all">
                         {{ __('Demander une démo') }}
                     </a>
                 @endif
             @else
                 <a href="{{ route('home') }}" class="flex items-center justify-center py-3 text-sm font-bold text-white bg-gradient-to-r from-brand-800 to-brand-900 rounded-xl hover:from-brand-900 hover:to-brand-800 shadow-md transition-all">
                     {{ __('Mon Compte') }}
                 </a>
                 <form action="{{ route('logout') }}" method="POST" class="w-full">
                     @csrf
                     <button type="submit" class="flex items-center justify-center w-full py-3 text-sm font-medium text-red-650 hover:bg-red-50 dark:hover:bg-red-950/20 border border-red-200/50 dark:border-red-900/50 rounded-xl transition-all">
                         {{ __('Déconnexion') }}
                     </button>
                 </form>
             @endguest
         </div>
    </div>
</header>
