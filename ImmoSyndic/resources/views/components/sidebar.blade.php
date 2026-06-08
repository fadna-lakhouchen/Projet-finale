@php
    $roleLabel = match(auth()->user()->role) {
        'administrateur' => 'Super Admin',
        'syndic' => 'Syndic Référent',
        default => 'Résident',
    };
@endphp

<div id="docs-sidebar" 
     {{-- RESPONSIVE : Glisse le menu sur mobile en fonction de l'état Alpine.js (translate-x-0 = affiché, -translate-x-full = masqué) --}}
     :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
     {{-- RESPONSIVE : Ferme automatiquement le menu mobile en cas de clic en dehors de la sidebar --}}
     @click.outside="sidebarOpen = false"
     class="hs-overlay [--auto-close:lg] hs-overlay-open:translate-x-0 -translate-x-full transition-all duration-300 transform fixed top-0 start-0 bottom-0 z-[60] w-64 bg-[#0B1224] border-e border-slate-800/60 pt-7 pb-10 overflow-y-auto lg:translate-x-0 lg:end-auto lg:bottom-0 scrollbar-none flex flex-col justify-between">
    <div>
        <!-- Logo Brand -->
        <div class="px-6 flex items-center gap-x-3 mb-8">
            <a class="flex items-center gap-x-3 group" href="{{ route('dashboard') }}">
                <div class="size-12 flex items-center justify-center shrink-0">
                    <img src="{{ asset('logo.png') }}" alt="Logo" class="size-full object-contain">
                </div>
                <div class="flex flex-col justify-center">
                    <span class="text-xl font-extrabold text-white tracking-tight leading-none group-hover:text-primary-400 transition-colors">Immo<span class="text-primary-500">Syndic</span></span>
                </div>
            </a>
        </div>

        <!-- Navigation Links -->
        <nav class="p-4 w-full flex flex-col flex-wrap">
            <ul class="space-y-1.5">
                <!-- ADMINISTRATEUR -->
                @if(auth()->user()->isAdministrateur())
                    <li class="px-3 mb-2">
                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Supervision</span>
                    </li>
                    <li>
                        <a class="flex items-center gap-x-3.5 py-2.5 px-3 rounded-xl text-sm font-medium transition-all duration-300 group border-l-2 {{ request()->routeIs('admin.dashboard') ? 'bg-primary-500/10 border-primary-500 text-primary-400 shadow-sm shadow-primary-500/5' : 'text-slate-400 hover:text-white hover:bg-white/5 border-transparent hover:border-slate-800' }}" href="{{ route('admin.dashboard') }}">
                            <i data-lucide="layout-dashboard" class="size-4.5 shrink-0 group-hover:scale-105 transition-transform"></i> Dashboard
                        </a>
                    </li>
                    <li>
                        <a class="flex items-center gap-x-3.5 py-2.5 px-3 rounded-xl text-sm font-medium transition-all duration-300 group border-l-2 {{ request()->routeIs('admin.immeubles') ? 'bg-primary-500/10 border-primary-500 text-primary-400 shadow-sm shadow-primary-500/5' : 'text-slate-400 hover:text-white hover:bg-white/5 border-transparent hover:border-slate-800' }}" href="{{ route('admin.immeubles') }}">
                            <i data-lucide="building" class="size-4.5 shrink-0 group-hover:scale-105 transition-transform"></i> Immeubles
                        </a>
                    </li>
                    <li>
                        <a class="flex items-center gap-x-3.5 py-2.5 px-3 rounded-xl text-sm font-medium transition-all duration-300 group border-l-2 {{ request()->routeIs('admin.residents') ? 'bg-primary-500/10 border-primary-500 text-primary-400 shadow-sm shadow-primary-500/5' : 'text-slate-400 hover:text-white hover:bg-white/5 border-transparent hover:border-slate-800' }}" href="{{ route('admin.residents') }}">
                            <i data-lucide="users" class="size-4.5 shrink-0 group-hover:scale-105 transition-transform"></i> Résidents
                        </a>
                    </li>
                    <li>
                        <a class="flex items-center gap-x-3.5 py-2.5 px-3 rounded-xl text-sm font-medium transition-all duration-300 group border-l-2 {{ request()->routeIs('admin.syndics') ? 'bg-primary-500/10 border-primary-500 text-primary-400 shadow-sm shadow-primary-500/5' : 'text-slate-400 hover:text-white hover:bg-white/5 border-transparent hover:border-slate-800' }}" href="{{ route('admin.syndics') }}">
                            <i data-lucide="user-cog" class="size-4.5 shrink-0 group-hover:scale-105 transition-transform"></i> Syndics
                        </a>
                    </li>
                    <li>
                        <a class="flex items-center gap-x-3.5 py-2.5 px-3 rounded-xl text-sm font-medium transition-all duration-300 group border-l-2 {{ request()->routeIs('admin.paiements') ? 'bg-primary-500/10 border-primary-500 text-primary-400 shadow-sm shadow-primary-500/5' : 'text-slate-400 hover:text-white hover:bg-white/5 border-transparent hover:border-slate-800' }}" href="{{ route('admin.paiements') }}">
                            <i data-lucide="wallet" class="size-4.5 shrink-0 group-hover:scale-105 transition-transform"></i> Paiements
                        </a>
                    </li>
                    <li>
                        <a class="flex items-center gap-x-3.5 py-2.5 px-3 rounded-xl text-sm font-medium transition-all duration-300 group border-l-2 {{ request()->routeIs('admin.depenses') ? 'bg-primary-500/10 border-primary-500 text-primary-400 shadow-sm shadow-primary-500/5' : 'text-slate-400 hover:text-white hover:bg-white/5 border-transparent hover:border-slate-800' }}" href="{{ route('admin.depenses') }}">
                            <i data-lucide="receipt" class="size-4.5 shrink-0 group-hover:scale-105 transition-transform"></i> Dépenses
                        </a>
                    </li>
                    <li>
                        <a class="flex items-center gap-x-3.5 py-2.5 px-3 rounded-xl text-sm font-medium transition-all duration-300 group border-l-2 {{ request()->routeIs('admin.documents') ? 'bg-primary-500/10 border-primary-500 text-primary-400 shadow-sm shadow-primary-500/5' : 'text-slate-400 hover:text-white hover:bg-white/5 border-transparent hover:border-slate-800' }}" href="{{ route('admin.documents') }}">
                            <i data-lucide="folder-open" class="size-4.5 shrink-0 group-hover:scale-105 transition-transform"></i> Documents
                        </a>
                    </li>
                    <li class="pt-4 mt-4 border-t border-slate-800">
                        <span class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Configuration</span>
                    </li>
                    <li>
                        <a class="flex items-center gap-x-3.5 py-2.5 px-3 rounded-xl text-sm font-medium transition-all duration-300 group border-l-2 {{ request()->routeIs('admin.parametres') ? 'bg-primary-500/10 border-primary-500 text-primary-400 shadow-sm shadow-primary-500/5' : 'text-slate-400 hover:text-white hover:bg-white/5 border-transparent hover:border-slate-800' }}" href="{{ route('admin.parametres') }}">
                            <i data-lucide="settings" class="size-4.5 shrink-0 group-hover:scale-105 transition-transform"></i> Paramètres
                        </a>
                    </li>
                    <li>
                        <a class="flex items-center gap-x-3.5 py-2.5 px-3 rounded-xl text-sm font-medium transition-all duration-300 group border-l-2 {{ request()->routeIs('admin.logs') ? 'bg-primary-500/10 border-primary-500 text-primary-400 shadow-sm shadow-primary-500/5' : 'text-slate-400 hover:text-white hover:bg-white/5 border-transparent hover:border-slate-800' }}" href="{{ route('admin.logs') }}">
                            <i data-lucide="scroll-text" class="size-4.5 shrink-0 group-hover:scale-105 transition-transform"></i> Logs Système
                        </a>
                    </li>
                @endif

                <!-- SYNDIC -->
                @if(auth()->user()->isSyndic())
                    <li class="px-3 mb-2">
                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Gestion Syndic</span>
                    </li>
                    <li>
                        <a class="flex items-center gap-x-3.5 py-2.5 px-3 rounded-xl text-sm font-medium transition-all duration-300 group border-l-2 {{ request()->routeIs('syndic.dashboard') ? 'bg-primary-500/10 border-primary-500 text-primary-400 shadow-sm shadow-primary-500/5' : 'text-slate-400 hover:text-white hover:bg-white/5 border-transparent hover:border-slate-800' }}" href="{{ route('syndic.dashboard') }}">
                            <i data-lucide="layout-dashboard" class="size-4.5 shrink-0 group-hover:scale-105 transition-transform"></i> Dashboard
                        </a>
                    </li>
                    <li>
                        <a class="flex items-center gap-x-3.5 py-2.5 px-3 rounded-xl text-sm font-medium transition-all duration-300 group border-l-2 {{ request()->routeIs('syndic.immeubles') ? 'bg-primary-500/10 border-primary-500 text-primary-400 shadow-sm shadow-primary-500/5' : 'text-slate-400 hover:text-white hover:bg-white/5 border-transparent hover:border-slate-800' }}" href="{{ route('syndic.immeubles') }}">
                            <i data-lucide="building" class="size-4.5 shrink-0 group-hover:scale-105 transition-transform"></i> Mes Immeubles
                        </a>
                    </li>
                    <li>
                        <a class="flex items-center gap-x-3.5 py-2.5 px-3 rounded-xl text-sm font-medium transition-all duration-300 group border-l-2 {{ request()->routeIs('syndic.residents') ? 'bg-primary-500/10 border-primary-500 text-primary-400 shadow-sm shadow-primary-500/5' : 'text-slate-400 hover:text-white hover:bg-white/5 border-transparent hover:border-slate-800' }}" href="{{ route('syndic.residents') }}">
                            <i data-lucide="users" class="size-4.5 shrink-0 group-hover:scale-105 transition-transform"></i> Mes Résidents
                        </a>
                    </li>
                    <li>
                        <a class="flex items-center gap-x-3.5 py-2.5 px-3 rounded-xl text-sm font-medium transition-all duration-300 group border-l-2 {{ request()->routeIs('syndic.paiements') ? 'bg-primary-500/10 border-primary-500 text-primary-400 shadow-sm shadow-primary-500/5' : 'text-slate-400 hover:text-white hover:bg-white/5 border-transparent hover:border-slate-800' }}" href="{{ route('syndic.paiements') }}">
                            <i data-lucide="wallet" class="size-4.5 shrink-0 group-hover:scale-105 transition-transform"></i> Paiements
                        </a>
                    </li>
                    <li>
                        <a class="flex items-center gap-x-3.5 py-2.5 px-3 rounded-xl text-sm font-medium transition-all duration-300 group border-l-2 {{ request()->routeIs('syndic.interventions') ? 'bg-primary-500/10 border-primary-500 text-primary-400 shadow-sm shadow-primary-500/5' : 'text-slate-400 hover:text-white hover:bg-white/5 border-transparent hover:border-slate-800' }}" href="{{ route('syndic.interventions') }}">
                            <i data-lucide="wrench" class="size-4.5 shrink-0 group-hover:scale-105 transition-transform"></i> Interventions
                        </a>
                    </li>
                    <li>
                        <a class="flex items-center gap-x-3.5 py-2.5 px-3 rounded-xl text-sm font-medium transition-all duration-300 group border-l-2 {{ request()->routeIs('syndic.annonces') ? 'bg-primary-500/10 border-primary-500 text-primary-400 shadow-sm shadow-primary-500/5' : 'text-slate-400 hover:text-white hover:bg-white/5 border-transparent hover:border-slate-800' }}" href="{{ route('syndic.annonces') }}">
                            <i data-lucide="megaphone" class="size-4.5 shrink-0 group-hover:scale-105 transition-transform"></i> Annonces
                        </a>
                    </li>
                    <li>
                        <a class="flex items-center gap-x-3.5 py-2.5 px-3 rounded-xl text-sm font-medium transition-all duration-300 group border-l-2 {{ request()->routeIs('syndic.depenses') ? 'bg-primary-500/10 border-primary-500 text-primary-400 shadow-sm shadow-primary-500/5' : 'text-slate-400 hover:text-white hover:bg-white/5 border-transparent hover:border-slate-800' }}" href="{{ route('syndic.depenses') }}">
                            <i data-lucide="receipt" class="size-4.5 shrink-0 group-hover:scale-105 transition-transform"></i> Dépenses
                        </a>
                    </li>
                    <li>
                        <a class="flex items-center gap-x-3.5 py-2.5 px-3 rounded-xl text-sm font-medium transition-all duration-300 group border-l-2 {{ request()->routeIs('syndic.documents') ? 'bg-primary-500/10 border-primary-500 text-primary-400 shadow-sm shadow-primary-500/5' : 'text-slate-400 hover:text-white hover:bg-white/5 border-transparent hover:border-slate-800' }}" href="{{ route('syndic.documents') }}">
                            <i data-lucide="folder-open" class="size-4.5 shrink-0 group-hover:scale-105 transition-transform"></i> Documents
                        </a>
                    </li>
                    <li class="pt-4 mt-4 border-t border-slate-800">
                        <span class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Configuration</span>
                    </li>
                    <li>
                        <a class="flex items-center gap-x-3.5 py-2.5 px-3 rounded-xl text-sm font-medium transition-all duration-300 group border-l-2 {{ request()->routeIs('syndic.parametres') ? 'bg-primary-500/10 border-primary-500 text-primary-400 shadow-sm shadow-primary-500/5' : 'text-slate-400 hover:text-white hover:bg-white/5 border-transparent hover:border-slate-800' }}" href="{{ route('syndic.parametres') }}">
                            <i data-lucide="settings" class="size-4.5 shrink-0 group-hover:scale-105 transition-transform"></i> Paramètres
                        </a>
                    </li>
                @endif

                <!-- RESIDENT -->
                @if(auth()->user()->isResident())
                    <li class="px-3 mb-2">
                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Espace Résident</span>
                    </li>
                    <li>
                        <a class="flex items-center gap-x-3.5 py-2.5 px-3 rounded-xl text-sm font-medium transition-all duration-300 group border-l-2 {{ request()->routeIs('resident.dashboard') ? 'bg-primary-500/10 border-primary-500 text-primary-400 shadow-sm shadow-primary-500/5' : 'text-slate-400 hover:text-white hover:bg-white/5 border-transparent hover:border-slate-800' }}" href="{{ route('resident.dashboard') }}">
                            <i data-lucide="layout-dashboard" class="size-4.5 shrink-0 group-hover:scale-105 transition-transform"></i> Dashboard
                        </a>
                    </li>
                    <li>
                        <a class="flex items-center gap-x-3.5 py-2.5 px-3 rounded-xl text-sm font-medium transition-all duration-300 group border-l-2 {{ request()->routeIs('resident.paiements') ? 'bg-primary-500/10 border-primary-500 text-primary-400 shadow-sm shadow-primary-500/5' : 'text-slate-400 hover:text-white hover:bg-white/5 border-transparent hover:border-slate-800' }}" href="{{ route('resident.paiements') }}">
                            <i data-lucide="credit-card" class="size-4.5 shrink-0 group-hover:scale-105 transition-transform"></i> Paiements
                        </a>
                    </li>
                    <li>
                        <a class="flex items-center gap-x-3.5 py-2.5 px-3 rounded-xl text-sm font-medium transition-all duration-300 group border-l-2 {{ request()->routeIs('resident.incidents') ? 'bg-primary-500/10 border-primary-500 text-primary-400 shadow-sm shadow-primary-500/5' : 'text-slate-400 hover:text-white hover:bg-white/5 border-transparent hover:border-slate-800' }}" href="{{ route('resident.incidents') }}">
                            <i data-lucide="alert-circle" class="size-4.5 shrink-0 group-hover:scale-105 transition-transform"></i> Mes Signalements
                        </a>
                    </li>
                    <li>
                        <a class="flex items-center gap-x-3.5 py-2.5 px-3 rounded-xl text-sm font-medium transition-all duration-300 group border-l-2 {{ request()->routeIs('resident.annonces') ? 'bg-primary-500/10 border-primary-500 text-primary-400 shadow-sm shadow-primary-500/5' : 'text-slate-400 hover:text-white hover:bg-white/5 border-transparent hover:border-slate-800' }}" href="{{ route('resident.annonces') }}">
                            <i data-lucide="megaphone" class="size-4.5 shrink-0 group-hover:scale-105 transition-transform"></i> Annonces
                        </a>
                    </li>

                    <li>
                        <a class="flex items-center gap-x-3.5 py-2.5 px-3 rounded-xl text-sm font-medium transition-all duration-300 group border-l-2 {{ request()->routeIs('resident.documents') ? 'bg-primary-500/10 border-primary-500 text-primary-400 shadow-sm shadow-primary-500/5' : 'text-slate-400 hover:text-white hover:bg-white/5 border-transparent hover:border-slate-800' }}" href="{{ route('resident.documents') }}">
                            <i data-lucide="folder-open" class="size-4.5 shrink-0 group-hover:scale-105 transition-transform"></i> Documents
                        </a>
                    </li>
                    <li class="pt-4 mt-4 border-t border-slate-800">
                        <span class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Configuration</span>
                    </li>
                    <li>
                        <a class="flex items-center gap-x-3.5 py-2.5 px-3 rounded-xl text-sm font-medium transition-all duration-300 group border-l-2 {{ request()->routeIs('resident.parametres') ? 'bg-primary-500/10 border-primary-500 text-primary-400 shadow-sm shadow-primary-500/5' : 'text-slate-400 hover:text-white hover:bg-white/5 border-transparent hover:border-slate-800' }}" href="{{ route('resident.parametres') }}">
                            <i data-lucide="settings" class="size-4.5 shrink-0 group-hover:scale-105 transition-transform"></i> Paramètres
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
    </div>

    <!-- Bottom Profile Card & Logout -->
    <div class="px-4">
        <div class="p-3 bg-white/5 rounded-2xl border border-white/10 flex items-center gap-x-3 mb-4 shadow-sm">
            <img class="size-10 rounded-xl ring-2 ring-primary-500/20 shrink-0 object-cover" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->prenom . ' ' . auth()->user()->nom) }}&background=3b66f5&color=fff&font-size=0.4" alt="Avatar">
            <div class="grow min-w-0">
                <span class="block text-sm font-bold text-white truncate leading-none mb-1">{{ auth()->user()->prenom }} {{ auth()->user()->nom }}</span>
                <span class="inline-flex items-center gap-x-1.5 py-0.5 px-2 rounded-full text-[9px] font-bold bg-primary-500/20 text-primary-300">
                    <span class="size-1 inline-block bg-primary-400 rounded-full animate-ping"></span>
                    {{ $roleLabel }}
                </span>
            </div>
        </div>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center gap-x-2 py-2.5 px-3 text-sm font-semibold rounded-xl bg-white/5 text-red-400 border border-white/5 hover:bg-red-500/10 hover:text-red-300 hover:border-red-500/20 transition-all duration-300">
                <i data-lucide="log-out" class="size-4"></i>
                Déconnexion
            </button>
        </form>
    </div>
</div>
