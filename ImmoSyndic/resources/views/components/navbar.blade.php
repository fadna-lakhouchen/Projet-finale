<header class="sticky top-0 inset-x-0 flex flex-wrap sm:justify-start sm:flex-nowrap z-[48] w-full bg-white border-b border-gray-200 text-sm py-2.5 sm:py-4 lg:ps-64 dark:bg-neutral-800 dark:border-neutral-700 shadow-sm">
    <nav class="flex basis-full items-center w-full mx-auto px-4 sm:px-6" aria-label="Global">
        <div class="me-5 lg:me-0 lg:hidden">
            <a class="flex items-center gap-x-2 text-xl font-semibold dark:text-white" href="{{ route('dashboard') }}">
                <img src="{{ asset('logo.png') }}" alt="Logo" class="h-7 w-auto object-contain">
                ImmoSyndic
            </a>
        </div>
        <div class="w-full flex items-center justify-end ms-auto sm:justify-between sm:gap-x-3">
            <div class="hidden sm:flex items-center gap-x-3">
                <h1 class="text-lg font-semibold text-gray-800 dark:text-white">Bonjour, {{ auth()->user()->prenom }} {{ auth()->user()->nom }}</h1>
                @php
                    $roleColor = match(auth()->user()->role) {
                        'administrateur' => 'bg-red-100 text-red-800 dark:bg-red-800/30 dark:text-red-500',
                        'syndic' => 'bg-primary-100 text-primary-800 dark:bg-primary-800/30 dark:text-primary-500',
                        default => 'bg-green-100 text-green-800 dark:bg-green-800/30 dark:text-green-500',
                    };
                    $roleLabel = match(auth()->user()->role) {
                        'administrateur' => 'Super Admin',
                        'syndic' => 'Syndic',
                        default => 'Résident',
                    };
                @endphp
                <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium {{ $roleColor }}">
                    <span class="size-1.5 inline-block bg-current rounded-full"></span> {{ $roleLabel }}
                </span>
            </div>

            <div class="flex flex-row items-center justify-end gap-2">
                <button type="button" class="size-[38px] relative inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-gray-800 hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-700">
                    <i data-lucide="bell" class="size-5"></i>
                    <span class="absolute top-0 end-0 inline-flex items-center size-2 rounded-full bg-red-600 border-2 border-white dark:border-neutral-800"></span>
                </button>

                <div x-data="{ open: false }" class="relative inline-flex">
                    <button @click="open = !open" @click.outside="open = false" id="hs-dropdown-profile" type="button" class="w-[38px] h-[38px] inline-flex justify-center items-center rounded-full border border-transparent hover:bg-gray-100 dark:hover:bg-neutral-700 focus:outline-none">
                        <img class="inline-block size-[38px] rounded-full ring-2 ring-white" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->prenom . ' ' . auth()->user()->nom) }}&background=4f46e5&color=fff" alt="Avatar">
                    </button>
                    
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 top-full z-50 min-w-60 bg-white shadow-md rounded-lg p-2 mt-2 dark:bg-neutral-800 dark:border dark:border-neutral-700" 
                         style="display: none;">
                        <div class="py-3 px-5 -m-2 bg-gray-100 rounded-t-lg mb-2 dark:bg-neutral-700">
                            <p class="text-sm text-gray-500 dark:text-neutral-400">Connecté en tant que</p>
                            <p class="text-sm font-medium text-gray-800 dark:text-white">{{ auth()->user()->email }}</p>
                        </div>
                        @php
                            $paramsRoute = match(auth()->user()->role) {
                                'administrateur' => route('admin.parametres'),
                                default => '#',
                            };
                        @endphp
                        <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700" href="{{ $paramsRoute }}">
                            <i data-lucide="settings" class="size-4"></i> Paramètres
                        </a>
                        <hr class="my-2 border-gray-200 dark:border-neutral-700">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/40">
                                <i data-lucide="log-out" class="size-4"></i> Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>
