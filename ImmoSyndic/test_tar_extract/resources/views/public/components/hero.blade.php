<!-- Hero Section -->
<section class="relative pt-32 pb-24 lg:pt-48 lg:pb-36 overflow-hidden min-h-[92vh] flex items-center bg-slate-50 dark:bg-gray-950 text-slate-850 dark:text-white">
    <!-- Premium SVG Grid Pattern (Light Slate by default, very subtle) -->
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#0f172a05_1px,transparent_1px),linear-gradient(to_bottom,#0f172a05_1px,transparent_1px)] bg-[size:24px_24px] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_0%,#000_80%,transparent_100%)] dark:bg-[linear-gradient(to_right,#ffffff02_1px,transparent_1px),linear-gradient(to_bottom,#ffffff02_1px,transparent_1px)] pointer-events-none"></div>

    <!-- Glowing Blob Overlays (Electric Blue and Navy) -->
    <div class="absolute top-0 right-1/4 w-[35rem] h-[35rem] bg-blue-500/10 rounded-full blur-[120px] animate-pulse pointer-events-none" style="animation-duration: 8s;"></div>
    <div class="absolute bottom-0 left-1/4 w-[35rem] h-[35rem] bg-indigo-500/10 rounded-full blur-[120px] animate-pulse pointer-events-none" style="animation-duration: 6s; animation-delay: 2s;"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 z-10 w-full">
        <div class="text-center max-w-4xl mx-auto mb-16 animate-fade-in-up">
            <!-- Badge -->
            <div class="inline-flex items-center gap-2.5 px-4 py-2 rounded-full bg-blue-50 border border-blue-150/40 text-sm font-semibold text-brand-800 dark:bg-white/5 dark:border-white/10 dark:text-blue-300 mb-8 shadow-sm">
                <span class="flex h-2 w-2 rounded-full bg-blue-600 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-500 opacity-75"></span>
                </span>
                {{ __('Plus de 80% des immeubles au Maroc souffrent d\'impayés et de désorganisation.') }}
            </div>
            
            <h1 class="font-heading text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-extrabold tracking-tight text-slate-900 dark:text-white leading-[1.05] mb-8">
                {!! __('Fini le cahier papier et les rappels WhatsApp <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-800 to-brand-900">sans réponse</span>.') !!}
            </h1>
            
            <p class="text-base sm:text-lg md:text-xl text-slate-650 dark:text-slate-400 mb-10 max-w-2xl mx-auto leading-relaxed">
                {{ __('Le manque de transparence et les retards de paiement bloquent la caisse de votre copropriété. ImmoSyn automatise le recouvrement des cotisations, clarifie les dépenses en temps réel et remet de l\'ordre dans votre gestion.') }}
            </p>
            
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4.5">
                <a href="https://wa.me/212720366418?text=Bonjour%20ImmoSyn,%20je%20souhaite%20demander%20une%20d%C3%A9mo%20de%20l'application." target="_blank" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 text-base font-bold text-white transition-all duration-300 bg-brand-900 hover:bg-brand-800 rounded-xl hover:shadow-[0_10px_25px_rgba(8,101,253,0.35)] hover:scale-105">
                    {{ __('Découvrir la plateforme') }}
                    <i data-lucide="arrow-right" class="ms-2 size-5 rtl:rotate-180"></i>
                </a>
                <a href="#features" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 text-base font-bold text-slate-700 bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 dark:text-white rounded-xl hover:bg-slate-50 dark:hover:bg-white/10 hover:shadow-md transition-all duration-200 group">
                    {{ __('Voir les fonctionnalités') }}
                    <i data-lucide="chevron-down" class="ms-2 size-5 text-brand-900 dark:text-blue-400 group-hover:translate-y-1 transition-transform"></i>
                </a>
            </div>
        </div>

        <!-- High-Fidelity Mockup Container (Clean Light/Dark Hybrid Dashboard) -->
        <div class="relative mx-auto max-w-5xl group animate-fade-in-up" style="animation-delay: 0.2s;">
            <!-- Outer Glow -->
            <div class="absolute -inset-1.5 bg-gradient-to-r from-brand-900 to-brand-800 rounded-[2rem] blur opacity-15 group-hover:opacity-25 transition duration-1000"></div>
            
            <!-- Window Container -->
            <div class="relative rounded-[1.8rem] border border-slate-200/80 dark:border-white/10 bg-white dark:bg-gray-900 shadow-2xl overflow-hidden">
                <!-- Browser bar (Header) -->
                <div class="bg-slate-100 dark:bg-gray-950 px-5 py-4 border-b border-slate-200 dark:border-white/5 flex items-center justify-between">
                    <div class="flex gap-2">
                        <div class="size-3 rounded-full bg-slate-300 dark:bg-rose-500/90 shadow-inner"></div>
                        <div class="size-3 rounded-full bg-slate-300 dark:bg-amber-500/90 shadow-inner"></div>
                        <div class="size-3 rounded-full bg-slate-300 dark:bg-emerald-500/90 shadow-inner"></div>
                    </div>
                    <div class="bg-slate-200/60 dark:bg-white/5 border border-slate-200/40 dark:border-white/5 text-[11px] text-slate-550 dark:text-slate-450 px-8 py-1 rounded-lg select-none hidden sm:block tracking-wide">
                        dashboard.immosyn.ma/syndic
                    </div>
                    <div class="size-4 text-slate-400 dark:text-slate-500">
                        <i data-lucide="help-circle" class="size-full"></i>
                    </div>
                </div>

                <!-- Dashboard Layout -->
                <div class="flex min-h-[440px]">
                    <!-- Sidebar Mockup (Matches App Sidebar Style) -->
                    <aside class="w-56 border-e border-slate-200/85 dark:border-white/5 bg-[#0B1224] text-white p-5 hidden md:flex flex-col justify-between">
                        <div class="space-y-6">
                            <div class="h-6 w-24 bg-white/10 rounded-lg"></div>
                            <nav class="space-y-2.5">
                                <div class="flex items-center gap-3 py-2 px-3 bg-white/5 text-blue-400 rounded-xl border-l-2 border-blue-500 text-xs font-semibold">
                                    <i data-lucide="layout-dashboard" class="size-4 shrink-0"></i> {{ __('Dashboard') }}
                                </div>
                                <div class="flex items-center gap-3 py-2 px-3 text-slate-400 text-xs font-medium">
                                    <i data-lucide="building" class="size-4 shrink-0"></i> {{ __('Mes Immeubles') }}
                                </div>
                                <div class="flex items-center gap-3 py-2 px-3 text-slate-400 text-xs font-medium">
                                    <i data-lucide="users" class="size-4 shrink-0"></i> {{ __('Mes Résidents') }}
                                </div>
                                <div class="flex items-center gap-3 py-2 px-3 text-slate-400 text-xs font-medium">
                                    <i data-lucide="wallet" class="size-4 shrink-0"></i> {{ __('Paiements') }}
                                </div>
                                <div class="flex items-center gap-3 py-2 px-3 text-slate-400 text-xs font-medium">
                                    <i data-lucide="wrench" class="size-4 shrink-0"></i> {{ __('Interventions') }}
                                </div>
                            </nav>
                        </div>
                        <div class="h-10 bg-white/5 rounded-xl border border-white/5"></div>
                    </aside>

                    <!-- Main Dashboard Content -->
                    <main class="flex-1 p-6 sm:p-8 space-y-6 bg-slate-50 dark:bg-gray-900/60">
                        <!-- Top header bar -->
                        <div class="flex justify-between items-center pb-4 border-b border-slate-200/70 dark:border-white/5">
                            <div>
                                <h3 class="text-base font-bold text-slate-800 dark:text-white tracking-tight">{{ __('Résidence Atlas') }}</h3>
                                <p class="text-[11px] text-slate-500">{{ __('Marrakech, Maroc') }}</p>
                            </div>
                            <div class="flex items-center gap-3.5">
                                <div class="size-8.5 rounded-xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/5 flex items-center justify-center text-slate-600 dark:text-blue-400 relative shadow-sm">
                                    <i data-lucide="bell" class="size-4"></i>
                                    <span class="absolute top-1.5 right-2 size-1.5 bg-rose-500 rounded-full"></span>
                                </div>
                                <img class="size-8.5 rounded-xl object-cover ring-2 ring-slate-100 dark:ring-blue-500/20" src="https://ui-avatars.com/api/?name=Karim+Benzakour&background=0865FD&color=fff&bold=true" alt="Avatar">
                            </div>
                        </div>

                        <!-- Stats grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <!-- Stat Card 1 -->
                            <div class="p-4 bg-white dark:bg-[#0c1020] border border-slate-200/70 dark:border-white/5 rounded-2xl flex flex-col gap-1 shadow-sm">
                                <span class="text-[9px] text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider">{{ __('Caisse Commune') }}</span>
                                <div class="flex items-baseline gap-2">
                                    <span class="text-lg font-extrabold text-slate-900 dark:text-white">{{ __('142,500 DH') }}</span>
                                    <span class="text-[9px] text-emerald-600 font-bold bg-emerald-50 px-1.5 py-0.5 rounded-md dark:bg-emerald-500/10 dark:text-emerald-400">+12%</span>
                                </div>
                            </div>
                            <!-- Stat Card 2 -->
                            <div class="p-4 bg-white dark:bg-[#0c1020] border border-slate-200/70 dark:border-white/5 rounded-2xl flex flex-col gap-1 shadow-sm">
                                <span class="text-[9px] text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider">{{ __('Incidents Actifs') }}</span>
                                <div class="flex items-baseline gap-2">
                                    <span class="text-lg font-extrabold text-slate-900 dark:text-white">{{ __('2 Ouverts') }}</span>
                                    <span class="text-[9px] text-amber-600 font-bold bg-amber-50 px-1.5 py-0.5 rounded-md dark:bg-amber-500/10 dark:text-amber-400">{{ __('À traiter') }}</span>
                                </div>
                            </div>
                            <!-- Stat Card 3 -->
                            <div class="p-4 bg-white dark:bg-[#0c1020] border border-slate-200/70 dark:border-white/5 rounded-2xl flex flex-col gap-1 shadow-sm">
                                <span class="text-[9px] text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider">{{ __('Recouvrement') }}</span>
                                <div class="flex items-baseline gap-2">
                                    <span class="text-lg font-extrabold text-slate-900 dark:text-white">94.2%</span>
                                    <span class="text-[9px] text-brand-900 font-bold bg-blue-50 px-1.5 py-0.5 rounded-md dark:bg-blue-500/10 dark:text-blue-300">{{ __('Optimal') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Bottom Split Layout -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                            <!-- Visual Chart Simulation -->
                            <div class="p-5 bg-white dark:bg-[#0c1020] border border-slate-200/70 dark:border-white/5 rounded-2xl space-y-4 shadow-sm">
                                <div class="flex justify-between items-center">
                                    <span class="text-xs font-bold text-slate-800 dark:text-white tracking-tight">{{ __('Flux de Cotisation') }}</span>
                                    <span class="text-[10px] text-slate-400">{{ __('6 Derniers Mois') }}</span>
                                </div>
                                <div class="h-32 flex items-end justify-between gap-3 pt-4 relative">
                                    <svg class="absolute inset-x-0 bottom-4 h-20 w-full overflow-visible" viewBox="0 0 100 100" preserveAspectRatio="none">
                                        <defs>
                                            <linearGradient id="gradient-chart" x1="0" y1="0" x2="0" y2="1">
                                                <stop offset="0%" stop-color="#0865FD" stop-opacity="0.25"></stop>
                                                <stop offset="100%" stop-color="#0865FD" stop-opacity="0"></stop>
                                            </linearGradient>
                                        </defs>
                                        <path d="M 0 80 Q 20 60 40 40 T 80 20 T 100 30 L 100 100 L 0 100 Z" fill="url(#gradient-chart)"></path>
                                        <path d="M 0 80 Q 20 60 40 40 T 80 20 T 100 30" fill="none" stroke="#0865FD" stroke-width="3" stroke-linecap="round"></path>
                                    </svg>
                                    <div class="w-full h-full bg-slate-100/50 dark:bg-white/2 rounded-md"></div>
                                    <div class="w-full h-full bg-slate-100/50 dark:bg-white/2 rounded-md"></div>
                                    <div class="w-full h-full bg-slate-100/50 dark:bg-white/2 rounded-md"></div>
                                    <div class="w-full h-full bg-slate-100/50 dark:bg-white/2 rounded-md"></div>
                                    <div class="w-full h-full bg-slate-100/50 dark:bg-white/2 rounded-md"></div>
                                    <div class="w-full h-full bg-slate-100/50 dark:bg-white/2 rounded-md"></div>
                                </div>
                            </div>

                            <!-- Recent Payments Simulation -->
                            <div class="p-5 bg-white dark:bg-[#0c1020] border border-slate-200/70 dark:border-white/5 rounded-2xl space-y-3 shadow-sm">
                                <span class="block text-xs font-bold text-slate-800 dark:text-white tracking-tight mb-1">{{ __('Dernières Opérations') }}</span>
                                <div class="space-y-2.5">
                                    <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 dark:bg-white/2 border border-slate-100 dark:border-white/2 text-xs">
                                        <div class="flex items-center gap-3">
                                            <div class="size-6 bg-blue-100 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400 rounded-lg flex items-center justify-center font-bold">K</div>
                                            <div>
                                                <span class="block font-bold text-slate-800 dark:text-white">{{ __('Karim Benzakour') }}</span>
                                                <span class="block text-[10px] text-slate-500">{{ __('Appt 4') }}</span>
                                            </div>
                                        </div>
                                        <span class="font-bold text-blue-600 dark:text-blue-400">{{ __('800.00 DH') }}</span>
                                    </div>
                                    <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 dark:bg-white/2 border border-slate-100 dark:border-white/2 text-xs">
                                        <div class="flex items-center gap-3">
                                            <div class="size-6 bg-blue-100 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400 rounded-lg flex items-center justify-center font-bold">S</div>
                                            <div>
                                                <span class="block font-bold text-slate-800 dark:text-white">{{ __('Said El Fassi') }}</span>
                                                <span class="block text-[10px] text-slate-500">{{ __('Appt 12') }}</span>
                                            </div>
                                        </div>
                                        <span class="font-bold text-blue-600 dark:text-blue-400">{{ __('500.00 DH') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </main>
                </div>
            </div>
        </div>
    </div>
</section>
