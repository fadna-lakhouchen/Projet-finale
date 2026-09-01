<!-- Solutions / Roles -->
<section id="solutions" class="py-24 bg-slate-50 dark:bg-[#070B16] border-t border-slate-200/60 dark:border-white/5 relative overflow-hidden text-slate-800 dark:text-white">
    <!-- Glowing background elements -->
    <div class="absolute right-0 top-1/4 w-96 h-96 bg-blue-500/5 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col lg:flex-row items-center gap-20">
            <!-- Left Side: Interactive Mockup Cards -->
            <div class="w-full lg:w-1/2">
                <div class="relative">
                    <div class="absolute -inset-4 bg-gradient-to-tr from-blue-500/10 to-indigo-500/10 rounded-3xl opacity-60 blur-3xl animate-pulse" style="animation-duration: 6s;"></div>
                    
                    <div class="relative bg-white dark:bg-[#0c1020]/90 border border-slate-200/80 dark:border-white/10 rounded-3xl p-8 shadow-xl backdrop-blur-xl">
                        <div class="space-y-6">
                            <!-- Mockup Role 1: Resident -->
                            <div class="group flex items-center gap-4.5 p-4.5 bg-slate-50 border border-slate-100 dark:bg-white/5 dark:border-white/5 rounded-2xl shadow-sm hover:bg-slate-100/50 hover:scale-[1.03] transition-all duration-300 cursor-default">
                                <div class="size-12 rounded-xl bg-blue-100 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <i data-lucide="user" class="size-6"></i>
                                </div>
                                <div class="grow min-w-0">
                                    <p class="font-bold text-slate-900 dark:text-white text-sm">{{ __('Yassine El Alami') }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 truncate font-medium">{{ __('Résident - Cotisation payée (Juin 2026)') }}</p>
                                </div>
                                <div class="size-8 rounded-full bg-emerald-100 dark:bg-emerald-500/15 flex items-center justify-center">
                                   <i data-lucide="check" class="size-5 text-emerald-600 dark:text-emerald-400"></i>
                                </div>
                            </div>
                            <!-- Mockup Role 2: Syndic -->
                            <div class="group flex items-center gap-4.5 p-4.5 bg-slate-50 border border-slate-100 dark:bg-white/5 dark:border-white/10 rounded-2xl shadow-sm hover:bg-slate-100/50 hover:scale-[1.03] transition-all duration-300 cursor-default translate-x-0 {{ app()->getLocale() === 'ar' ? 'sm:-translate-x-6' : 'sm:translate-x-6' }}">
                                <div class="size-12 rounded-xl bg-blue-100 text-brand-900 dark:bg-blue-500/10 dark:text-blue-300 flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <i data-lucide="shield-check" class="size-6"></i>
                                </div>
                                <div class="grow min-w-0">
                                    <p class="font-bold text-slate-900 dark:text-white text-sm">{{ __('Karim Benzakour') }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 truncate font-medium">{{ __('Syndic Principal - Publication du PV d\'AG') }}</p>
                                </div>
                                <div class="size-8 rounded-full bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center">
                                    <i data-lucide="file-text" class="size-4.5 text-blue-600 dark:text-blue-400"></i>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Text Highlights -->
            <div class="w-full lg:w-1/2 space-y-8">
                <div>
                    <span class="text-xs font-bold uppercase tracking-widest text-brand-900 bg-blue-50 px-3.5 py-1.5 rounded-full mb-4 inline-block dark:bg-white/5 dark:text-blue-300">{{ __('Rôles & Accessibilité') }}</span>
                    <h2 class="font-heading text-3xl md:text-5xl font-extrabold text-slate-900 dark:text-white leading-tight">
                        {{ __('Une interface unique, deux espaces sur-mesure.') }}
                    </h2>
                    <p class="text-slate-650 dark:text-slate-400 text-base leading-relaxed mt-4">
                        {{ __('ImmoSyn sépare intelligemment la gestion comptable et technique du syndic de l\'espace d\'information et de paiement des résidents.') }}
                    </p>
                </div>

                <div class="space-y-6">
                    <!-- Resident Role -->
                    <div class="flex gap-4.5 group cursor-default">
                        <div class="mt-1 size-11 rounded-xl bg-blue-100 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400 flex items-center justify-center flex-shrink-0 group-hover:scale-110 group-hover:rotate-3 transition-transform shadow-sm">
                            <i data-lucide="home" class="size-5.5"></i>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-slate-900 dark:text-white mb-1 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">{{ __('Espace Résidents : Clarté & Confiance') }}</h4>
                            <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed">{{ __('Les résidents suivent l\'état de la caisse, paient leurs cotisations, téléchargent leurs reçus PDF et déclarent les pannes en direct.') }}</p>
                        </div>
                    </div>
                    <!-- Syndic Role -->
                    <div class="flex gap-4.5 group cursor-default">
                        <div class="mt-1 size-11 rounded-xl bg-blue-100 text-brand-900 dark:bg-blue-500/10 dark:text-blue-300 flex items-center justify-center flex-shrink-0 group-hover:scale-110 group-hover:-rotate-3 transition-transform shadow-sm">
                            <i data-lucide="briefcase" class="size-5.5"></i>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-slate-900 dark:text-white mb-1 group-hover:text-brand-900 dark:group-hover:text-blue-300 transition-colors">{{ __('Portail Syndics : Contrôle & Efficacité') }}</h4>
                            <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed">{{ __('Pilotez la trésorerie, automatisez les appels de fonds, suivez les interventions techniques et gérez vos immeubles de manière professionnelle.') }}</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
