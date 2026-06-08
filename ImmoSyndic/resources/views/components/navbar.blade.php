<header class="sticky top-0 inset-x-0 flex flex-wrap sm:justify-start sm:flex-nowrap z-[48] w-full glass-panel border-b border-gray-200/40 dark:border-slate-800/40 text-sm py-2.5 sm:py-4 lg:ps-64 shadow-premium shadow-slate-100/10">
    <nav class="flex basis-full items-center w-full mx-auto px-4 sm:px-6" aria-label="Global">
        
        <!-- Mobile Sidebar Toggle & Brand -->
        <div class="flex items-center gap-x-3 lg:hidden me-5">
            {{-- RESPONSIVE : Le bouton burger bascule l'état "sidebarOpen" d'Alpine.js pour afficher/masquer la sidebar en mobile --}}
            <button @click.stop="sidebarOpen = !sidebarOpen" type="button" class="p-2 inline-flex items-center justify-center gap-x-2 rounded-xl border border-gray-200 bg-white/80 text-gray-800 hover:bg-gray-50 focus:outline-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-750" aria-label="Toggle navigation">
                <span class="sr-only">Toggle navigation</span>
                <i data-lucide="menu" class="size-5"></i>
            </button>
            
            {{-- RESPONSIVE : Le Logo & Titre ne s'affichent que sur mobile/tablette (<lg) pour ne pas encombrer le menu avec doublons --}}
            <a class="flex items-center gap-x-2 text-lg font-bold dark:text-white shrink-0" href="{{ route('dashboard') }}">
                <img src="{{ asset('logo.png') }}" alt="Logo" class="h-6 w-auto object-contain">
                <span class="bg-gradient-to-r from-primary-500 to-purple-600 bg-clip-text text-transparent">ImmoSyndic</span>
            </a>
        </div>

        <div class="w-full flex items-center justify-end ms-auto lg:justify-between lg:gap-x-3">
            <!-- Greeting & Role (Desktop) -->
            {{-- RESPONSIVE : Le message de bienvenue n'est visible que sur grand écran (hidden lg:flex) pour éviter de se chevaucher avec le logo sur tablette --}}
            <div class="hidden lg:flex items-center gap-x-3">
                <h1 class="text-base font-semibold text-slate-800 dark:text-white flex items-center gap-1.5">
                    Bonjour, <span class="bg-gradient-to-r from-primary-600 to-purple-600 bg-clip-text text-transparent font-bold">{{ auth()->user()->prenom }} {{ auth()->user()->nom }}</span>
                </h1>
                @php
                    $roleColor = match(auth()->user()->role) {
                        'administrateur' => 'bg-red-500/10 text-red-500 border border-red-500/20',
                        'syndic' => 'bg-primary-500/10 text-primary-500 border border-primary-500/20',
                        default => 'bg-green-500/10 text-green-500 border border-green-500/20',
                    };
                    $roleLabel = match(auth()->user()->role) {
                        'administrateur' => 'Super Admin',
                        'syndic' => 'Syndic',
                        default => 'Résident',
                    };
                @endphp
                <span class="inline-flex items-center gap-x-1.5 py-1 px-3 rounded-full text-xs font-bold {{ $roleColor }}">
                    <span class="size-1.5 inline-block bg-current rounded-full animate-pulse"></span> {{ $roleLabel }}
                </span>
            </div>

            <!-- Profile & Notifications Controls -->
            <div class="flex flex-row items-center justify-end gap-2.5">
                @php
                    $notifications = collect();
                    $unreadCount = 0;
                    if (auth()->check()) {
                        $notifications = \App\Models\Notification::where('user_id', auth()->id())->latest()->take(5)->get();
                        $unreadCount = \App\Models\Notification::where('user_id', auth()->id())->where('lu', false)->count();
                    }
                @endphp
                <!-- Notifications button & popover (AlpineJS) -->
                <div x-data="{ openNotif: false }" class="relative inline-flex">
                    <button @click="openNotif = !openNotif" @click.outside="openNotif = false" type="button" class="size-[38px] relative inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-xl border border-gray-200/50 bg-white/40 hover:bg-white text-gray-800 shadow-sm dark:text-white dark:bg-neutral-800/40 dark:border-neutral-700/50 dark:hover:bg-neutral-800 transition-all">
                        <i data-lucide="bell" class="size-4.5"></i>
                        @if($unreadCount > 0)
                            <span class="absolute top-1.5 end-1.5 inline-flex items-center size-2 rounded-full bg-red-500 border-2 border-white dark:border-neutral-800 animate-bounce"></span>
                        @endif
                    </button>
                    
                    <!-- Dropdown List -->
                    <div x-show="openNotif" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 top-full z-50 min-w-80 max-w-sm bg-white shadow-xl border border-gray-150 rounded-2xl p-2 mt-2 dark:bg-neutral-900 dark:border-neutral-800" 
                         style="display: none;">
                        
                        <!-- Header -->
                        <div class="flex items-center justify-between py-2 px-4 border-b border-gray-100 dark:border-neutral-800 pb-2 mb-2">
                            <h4 class="font-bold text-slate-800 dark:text-white">Notifications</h4>
                            @if($unreadCount > 0)
                                <form action="{{ route('notifications.read') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-xs font-bold text-primary-600 hover:text-primary-500 dark:text-primary-400">Tout marquer comme lu</button>
                                </form>
                            @endif
                        </div>

                        <!-- Notifications list -->
                        <div class="max-h-72 overflow-y-auto space-y-1 scrollbar-none">
                            @forelse($notifications as $notif)
                                <div class="p-3 rounded-xl transition-colors hover:bg-slate-50 dark:hover:bg-neutral-800/50 flex gap-x-3 {{ !$notif->lu ? 'bg-primary-500/[0.03] dark:bg-primary-500/[0.05]' : '' }}">
                                    <!-- Icon badge based on type/lu -->
                                    <div class="size-8 rounded-lg flex items-center justify-center shrink-0 {{ !$notif->lu ? 'bg-primary-500/10 text-primary-600' : 'bg-slate-100 text-slate-400 dark:bg-neutral-800' }}">
                                        @if($notif->type === 'warning')
                                            <i data-lucide="alert-triangle" class="size-4"></i>
                                        @elseif($notif->type === 'success')
                                            <i data-lucide="check" class="size-4"></i>
                                        @else
                                            <i data-lucide="info" class="size-4"></i>
                                        @endif
                                    </div>
                                    <div class="flex-col min-w-0">
                                        <p class="text-xs font-bold text-slate-800 dark:text-white truncate">{{ $notif->titre }}</p>
                                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 leading-normal line-clamp-2">{{ $notif->message }}</p>
                                        <span class="block text-[9px] text-slate-400 dark:text-slate-500 mt-1 font-semibold">
                                            {{ $notif->date_envoi ? $notif->date_envoi->diffForHumans() : $notif->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <div class="py-6 text-center text-xs text-slate-450 dark:text-neutral-500">
                                    <i data-lucide="bell-off" class="size-7 mx-auto text-slate-300 dark:text-neutral-600 mb-1.5"></i>
                                    <span>Aucune notification récente.</span>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Profile Dropdown (AlpineJS powered) -->
                <div x-data="{ open: false }" class="relative inline-flex">
                    <button @click="open = !open" @click.outside="open = false" id="hs-dropdown-profile" type="button" class="w-[38px] h-[38px] inline-flex justify-center items-center rounded-xl border border-gray-200/60 hover:bg-white/80 bg-white/40 ring-2 ring-primary-500/10 shadow-sm dark:bg-neutral-800/40 dark:border-neutral-700/60 focus:outline-none transition-all">
                        <img class="inline-block size-[38px] rounded-xl object-cover" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->prenom . ' ' . auth()->user()->nom) }}&background=3b66f5&color=fff&font-size=0.4" alt="Avatar">
                    </button>
                    
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 top-full z-50 min-w-60 bg-white shadow-xl border border-gray-150 rounded-2xl p-2 mt-2 dark:bg-neutral-900 dark:border-neutral-800" 
                         style="display: none;">
                        <div class="py-3 px-5 -m-2 bg-gradient-to-r from-primary-50/50 to-purple-50/30 rounded-t-2xl mb-2 dark:from-neutral-800 dark:to-neutral-800/80 border-b border-gray-100 dark:border-neutral-800">
                            <p class="text-xs text-slate-400 dark:text-neutral-450 uppercase font-bold tracking-wider mb-0.5">Identifié en tant que</p>
                            <p class="text-sm font-semibold text-slate-800 dark:text-white truncate">{{ auth()->user()->email }}</p>
                        </div>
                        @php
                            $paramsRoute = match(auth()->user()->role) {
                                'administrateur' => route('admin.parametres'),
                                'syndic' => route('syndic.parametres'),
                                default => route('resident.parametres'),
                            };
                        @endphp
                        @if($paramsRoute !== '#')
                        <a class="flex items-center gap-x-3 py-2 px-3 rounded-xl text-sm text-slate-700 hover:bg-slate-50 dark:text-neutral-350 dark:hover:bg-neutral-800 transition-colors" href="{{ $paramsRoute }}">
                            <i data-lucide="settings" class="size-4 text-slate-400"></i> Paramètres du profil
                        </a>
                        @endif
                        <hr class="my-2 border-gray-100 dark:border-neutral-800">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-x-3 py-2 px-3 rounded-xl text-sm text-red-500 hover:bg-red-50 dark:hover:bg-red-950/20 transition-colors">
                                <i data-lucide="log-out" class="size-4"></i> Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>
