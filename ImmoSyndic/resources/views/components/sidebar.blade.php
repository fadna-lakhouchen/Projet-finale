<div class="sticky top-0 start-0 lg:fixed lg:bottom-0 lg:z-[60] size-full w-64 bg-white border-e border-gray-200 pt-7 pb-10 overflow-y-auto lg:block hidden dark:bg-neutral-800 dark:border-neutral-700">
    <div class="px-6 flex items-center gap-x-2 mb-8">
        <img src="{{ asset('logo.png') }}" alt="ImmoSyndic Logo" class="h-8 w-auto object-contain">
        <a class="flex-none text-xl font-bold dark:text-white" href="{{ route('dashboard') }}">ImmoSyndic</a>
    </div>

    <nav class="hs-accordion-group p-6 w-full flex flex-col flex-wrap">
        <ul class="space-y-1.5">
            @if(auth()->user()->isAdministrateur())
                <li><a class="flex items-center gap-x-3.5 py-2 px-2.5 {{ request()->routeIs('admin.dashboard') ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400' : 'text-gray-700 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700' }} text-sm font-medium rounded-lg transition-colors" href="{{ route('admin.dashboard') }}">
                    <i data-lucide="layout-dashboard" class="size-4"></i> Dashboard
                </a></li>
                <li><a class="flex items-center gap-x-3.5 py-2 px-2.5 {{ request()->routeIs('admin.immeubles') ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400' : 'text-gray-700 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700' }} text-sm rounded-lg transition-colors" href="{{ route('admin.immeubles') }}">
                    <i data-lucide="building-2" class="size-4"></i> Immeubles
                </a></li>
                <li><a class="flex items-center gap-x-3.5 py-2 px-2.5 {{ request()->routeIs('admin.residents') ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400' : 'text-gray-700 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700' }} text-sm rounded-lg transition-colors" href="{{ route('admin.residents') }}">
                    <i data-lucide="users" class="size-4"></i> Résidents
                </a></li>
                <li><a class="flex items-center gap-x-3.5 py-2 px-2.5 {{ request()->routeIs('admin.syndics') ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400' : 'text-gray-700 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700' }} text-sm rounded-lg transition-colors" href="{{ route('admin.syndics') }}">
                    <i data-lucide="user-cog" class="size-4"></i> Syndics
                </a></li>
                <li><a class="flex items-center gap-x-3.5 py-2 px-2.5 {{ request()->routeIs('admin.paiements') ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400' : 'text-gray-700 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700' }} text-sm rounded-lg transition-colors" href="{{ route('admin.paiements') }}">
                    <i data-lucide="wallet" class="size-4"></i> Paiements
                </a></li>
                <li><a class="flex items-center gap-x-3.5 py-2 px-2.5 {{ request()->routeIs('admin.parametres') ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400' : 'text-gray-700 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700' }} text-sm rounded-lg transition-colors" href="{{ route('admin.parametres') }}">
                    <i data-lucide="settings" class="size-4"></i> Paramètres
                </a></li>
            @endif

            @if(auth()->user()->isSyndic())
                <li><a class="flex items-center gap-x-3.5 py-2 px-2.5 {{ request()->routeIs('syndic.dashboard') ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400' : 'text-gray-700 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700' }} text-sm font-medium rounded-lg transition-colors" href="{{ route('syndic.dashboard') }}">
                    <i data-lucide="layout-dashboard" class="size-4"></i> Dashboard
                </a></li>
                <li><a class="flex items-center gap-x-3.5 py-2 px-2.5 {{ request()->routeIs('syndic.immeubles') ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400' : 'text-gray-700 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700' }} text-sm rounded-lg transition-colors" href="{{ route('syndic.immeubles') }}">
                    <i data-lucide="building-2" class="size-4"></i> Mes Immeubles
                </a></li>
                <li><a class="flex items-center gap-x-3.5 py-2 px-2.5 {{ request()->routeIs('syndic.residents') ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400' : 'text-gray-700 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700' }} text-sm rounded-lg transition-colors" href="{{ route('syndic.residents') }}">
                    <i data-lucide="users" class="size-4"></i> Résidents
                </a></li>
                <li><a class="flex items-center gap-x-3.5 py-2 px-2.5 {{ request()->routeIs('syndic.paiements') ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400' : 'text-gray-700 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700' }} text-sm rounded-lg transition-colors" href="{{ route('syndic.paiements') }}">
                    <i data-lucide="wallet" class="size-4"></i> Paiements
                </a></li>
                <li><a class="flex items-center gap-x-3.5 py-2 px-2.5 {{ request()->routeIs('syndic.interventions') ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400' : 'text-gray-700 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700' }} text-sm rounded-lg transition-colors" href="{{ route('syndic.interventions') }}">
                    <i data-lucide="wrench" class="size-4"></i> Interventions
                </a></li>
                <li class="mt-4 pt-4 border-t border-gray-200 dark:border-neutral-700">
                    <a class="flex items-center gap-x-3.5 py-2 px-2.5 {{ request()->routeIs('syndic.parametres') ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400' : 'text-gray-700 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700' }} text-sm rounded-lg transition-colors" href="{{ route('syndic.parametres') }}">
                        <i data-lucide="settings" class="size-4"></i> Paramètres
                    </a>
                </li>
            @endif

            @if(auth()->user()->isResident())
                <li><a class="flex items-center gap-x-3.5 py-2 px-2.5 {{ request()->routeIs('resident.dashboard') ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400' : 'text-gray-700 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700' }} text-sm font-medium rounded-lg transition-colors" href="{{ route('resident.dashboard') }}">
                    <i data-lucide="layout-dashboard" class="size-4"></i> Vue d'ensemble
                </a></li>
                <li><a class="flex items-center gap-x-3.5 py-2 px-2.5 {{ request()->routeIs('resident.paiements') ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400' : 'text-gray-700 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700' }} text-sm rounded-lg transition-colors" href="{{ route('resident.paiements') }}">
                    <i data-lucide="file-invoice" class="size-4"></i> Mes Charges
                </a></li>
                <li><a class="flex items-center gap-x-3.5 py-2 px-2.5 {{ request()->routeIs('resident.incidents') ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400' : 'text-gray-700 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700' }} text-sm rounded-lg transition-colors" href="{{ route('resident.incidents') }}">
                    <i data-lucide="alert-circle" class="size-4"></i> Mes Signalements
                </a></li>
            @endif
        </ul>
    </nav>
</div>
