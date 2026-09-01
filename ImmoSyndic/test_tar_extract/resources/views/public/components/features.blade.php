<!-- Features Section -->
<section id="features" class="py-28 bg-white dark:bg-gray-950 border-t border-slate-200/60 dark:border-white/5 relative overflow-hidden">
    <!-- Premium background glowing gradients -->
    <div class="absolute left-1/3 top-1/4 w-[40rem] h-[40rem] bg-brand-900/5 rounded-full blur-[150px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <!-- Header -->
        <div class="text-center max-w-3xl mx-auto mb-20">
            <span class="text-xs font-bold uppercase tracking-widest text-brand-900 bg-blue-50 px-3.5 py-1.5 rounded-full mb-4 inline-block dark:bg-white/5 dark:text-blue-300">
                {{ __('Fonctionnalités Clés') }}
            </span>
            <h2 class="font-heading text-3xl md:text-5xl font-extrabold text-slate-900 dark:text-white leading-tight">
                {{ __('Des outils modernes pour une gestion sans friction.') }}
            </h2>
            <p class="text-slate-600 dark:text-slate-400 text-base sm:text-lg mt-4">
                {{ __('Chaque fonctionnalité est pensée pour automatiser les tâches répétitives et sécuriser les finances de votre immeuble.') }}
            </p>
        </div>

        <!-- Bento Grid Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Feature 1: Pilotage Financier (Large Card, spans 2 cols on lg) -->
            <div class="lg:col-span-2 relative group overflow-hidden bg-slate-50 dark:bg-gray-900/60 rounded-[2.5rem] border border-slate-200/70 dark:border-white/5 flex flex-col justify-between hover:-translate-y-1.5 hover:shadow-2xl hover:border-brand-900/30 dark:hover:border-blue-500/30 transition-all duration-500">
                <div class="p-10">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="size-12 rounded-2xl bg-blue-500/10 text-brand-900 dark:text-blue-400 flex items-center justify-center shadow-sm">
                            <i data-lucide="line-chart" class="size-6"></i>
                        </div>
                        <span class="text-xs font-bold text-brand-900 dark:text-blue-400 uppercase tracking-widest">{{ __('Finances & Budgets') }}</span>
                    </div>
                    <h4 class="text-2xl md:text-3xl font-bold text-slate-900 dark:text-white mb-4">
                        {{ __('Pilotage Financier de Précision') }}
                    </h4>
                    <p class="text-slate-650 dark:text-slate-400 text-base leading-relaxed max-w-md">
                        {{ __('Contrôlez vos appels de fonds, suivez les encaissements en temps réel et générez vos bilans annuels en un clic. Une transparence totale pour une gestion sereine.') }}
                    </p>
                </div>
                
                <!-- Premium High-Fidelity UI simulation -->
                <div class="px-10 pb-10">
                    <div class="bg-white dark:bg-gray-950 border border-slate-100 dark:border-white/5 rounded-2xl p-6 shadow-lg backdrop-blur-xl transform group-hover:scale-[1.01] transition-transform duration-500">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <span class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider">{{ __('Objectif Mensuel') }}</span>
                                <span class="text-sm font-extrabold text-slate-900 dark:text-white">{{ __('45,000 DH Recouvrés') }}</span>
                            </div>
                            <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 px-2.5 py-1 rounded-lg">
                                {{ __('94% Atteint') }}
                            </span>
                        </div>
                        <!-- Beautiful customized progress bar -->
                        <div class="h-2.5 bg-slate-100 dark:bg-white/5 rounded-full overflow-hidden mb-6 relative">
                            <div class="h-full bg-gradient-to-r from-brand-800 to-blue-500 rounded-full w-[94%]" style="transition: width 1.5s ease-out;"></div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 text-xs">
                            <div class="p-4 bg-slate-50 dark:bg-white/2 border border-slate-100 dark:border-white/2 rounded-xl flex items-center justify-between">
                                <div>
                                    <span class="block text-slate-400 mb-1 text-[10px] uppercase font-bold tracking-wider">{{ __('Total Charges') }}</span>
                                    <span class="font-extrabold text-slate-800 dark:text-white text-sm">{{ __('48,200 DH') }}</span>
                                </div>
                                <div class="size-8.5 rounded-lg bg-blue-500/5 text-blue-500 flex items-center justify-center">
                                    <i data-lucide="wallet" class="size-4.5"></i>
                                </div>
                            </div>
                            <div class="p-4 bg-slate-50 dark:bg-white/2 border border-slate-100 dark:border-white/2 rounded-xl flex items-center justify-between">
                                <div>
                                    <span class="block text-slate-400 mb-1 text-[10px] uppercase font-bold tracking-wider">{{ __('Restant à payer') }}</span>
                                    <span class="font-extrabold text-rose-600 dark:text-rose-450 text-sm">{{ __('3,200 DH') }}</span>
                                </div>
                                <div class="size-8.5 rounded-lg bg-rose-500/5 text-rose-500 flex items-center justify-center">
                                    <i data-lucide="alert-circle" class="size-4.5"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Feature 2: Tickets & Maintenance (Small Card) -->
            <div class="relative group p-8 bg-slate-50 dark:bg-gray-900/60 rounded-[2.5rem] border border-slate-200/70 dark:border-white/5 flex flex-col justify-between hover:-translate-y-1.5 hover:shadow-2xl hover:border-brand-900/30 dark:hover:border-blue-500/30 transition-all duration-500 min-h-[380px]">
                <div>
                    <div class="size-12 rounded-2xl bg-rose-500/10 text-rose-600 dark:text-rose-400 flex items-center justify-center mb-6 shadow-sm">
                        <i data-lucide="wrench" class="size-6"></i>
                    </div>
                    <h4 class="text-xl font-bold text-slate-900 dark:text-white mb-3">
                        {{ __('Tickets & Maintenance') }}
                    </h4>
                    <p class="text-slate-650 dark:text-slate-400 text-sm leading-relaxed mb-6">
                        {{ __('Signalement d\'incidents instantané et suivi collaboratif jusqu\'à la résolution par les artisans.') }}
                    </p>
                </div>
                
                <!-- Urgency & Status Mockup -->
                <div class="space-y-3.5 bg-white dark:bg-gray-950 p-4.5 rounded-2xl border border-slate-100 dark:border-white/5 shadow-md">
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-bold text-slate-800 dark:text-white">{{ __('Plomberie Appt 14') }}</span>
                        <span class="text-[9px] uppercase font-bold text-emerald-600 dark:text-emerald-450 bg-emerald-50 dark:bg-emerald-500/10 px-2 py-0.5 rounded-md">{{ __('Résolu') }}</span>
                    </div>
                    <div class="h-1.5 bg-slate-100 dark:bg-white/5 rounded-full overflow-hidden">
                        <div class="h-full bg-emerald-500 w-full"></div>
                    </div>
                    <div class="flex justify-between items-center text-[10px] text-slate-400">
                        <span class="font-medium">{{ __('Technicien assigné') }}</span>
                        <span class="font-bold text-slate-700 dark:text-white bg-slate-50 dark:bg-white/5 px-2 py-0.5 rounded-md">{{ __('El Alami') }}</span>
                    </div>
                </div>
            </div>

            <!-- Feature 3: Documentation Cloud (Small Card) -->
            <div class="relative group p-8 bg-slate-50 dark:bg-gray-900/60 rounded-[2.5rem] border border-slate-200/70 dark:border-white/5 flex flex-col justify-between hover:-translate-y-1.5 hover:shadow-2xl hover:border-brand-900/30 dark:hover:border-blue-500/30 transition-all duration-500 min-h-[380px]">
                <div>
                    <div class="size-12 rounded-2xl bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 flex items-center justify-center mb-6 shadow-sm">
                        <i data-lucide="folder-open" class="size-6"></i>
                    </div>
                    <h4 class="text-xl font-bold text-slate-900 dark:text-white mb-3">
                        {{ __('Documentation Cloud') }}
                    </h4>
                    <p class="text-slate-650 dark:text-slate-400 text-sm leading-relaxed mb-6">
                        {{ __('Accessibilité totale aux PV d\'assemblées générales, devis, règlements de copropriété.') }}
                    </p>
                </div>
                
                <!-- Cloud Documents Mockup -->
                <div class="space-y-2.5">
                    <div class="flex items-center justify-between p-3 bg-white dark:bg-gray-950 border border-slate-100 dark:border-white/5 rounded-xl text-xs shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="size-8 bg-rose-500/10 text-rose-500 rounded-lg flex items-center justify-center font-bold text-[10px]">{{ __('PDF') }}</div>
                            <span class="font-bold text-slate-755 dark:text-slate-300 truncate max-w-[120px]">{{ __('PV_Assemblée_2026.pdf') }}</span>
                        </div>
                        <i data-lucide="eye" class="size-4 text-slate-400"></i>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-white dark:bg-gray-950 border border-slate-100 dark:border-white/5 rounded-xl text-xs shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="size-8 bg-blue-500/10 text-blue-500 rounded-lg flex items-center justify-center font-bold text-[10px]">{{ __('DOC') }}</div>
                            <span class="font-bold text-slate-755 dark:text-slate-300 truncate max-w-[120px]">{{ __('Règlement_Atlas.docx') }}</span>
                        </div>
                        <i data-lucide="download" class="size-4 text-slate-400"></i>
                    </div>
                </div>
            </div>

            <!-- Feature 5: Multi-Immeubles (Large Card, spans 2 cols on lg) -->
            <div class="lg:col-span-2 relative group p-10 bg-slate-50 dark:bg-gray-900/60 rounded-[2.5rem] border border-slate-200/70 dark:border-white/5 flex flex-col md:flex-row items-center gap-8 justify-between hover:-translate-y-1.5 hover:shadow-2xl hover:border-brand-900/30 dark:hover:border-blue-500/30 transition-all duration-500 overflow-hidden">
                <div class="flex-1 space-y-4">
                    <div class="size-12 rounded-2xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-450 flex items-center justify-center shadow-sm">
                        <i data-lucide="building-2" class="size-6"></i>
                    </div>
                    <h4 class="text-2xl font-bold text-slate-900 dark:text-white">
                        {{ __('Multi-Résidences') }}
                    </h4>
                    <p class="text-slate-650 dark:text-slate-400 text-sm leading-relaxed max-w-sm">
                        {{ __('Basculez entre vos différents immeubles avec une ergonomie optimisée pour les syndics gérant plusieurs résidences.') }}
                    </p>
                </div>
                
                <!-- Premium Sidebar Selector simulation -->
                <div class="flex gap-4 bg-white dark:bg-gray-950 p-5 rounded-2xl border border-slate-100 dark:border-white/5 shadow-md">
                    <div class="size-20 rounded-xl bg-gradient-to-tr from-brand-900 to-blue-550 text-white flex flex-col items-center justify-center gap-1.5 text-[10px] font-bold shadow-md cursor-pointer hover:scale-105 transition-transform">
                        <i data-lucide="building" class="size-6"></i>
                        {{ __('Atlas') }}
                    </div>
                    <div class="size-20 rounded-xl bg-slate-50 dark:bg-white/5 border border-slate-100 dark:border-white/5 text-slate-500 flex flex-col items-center justify-center gap-1.5 text-[10px] font-bold cursor-pointer hover:scale-105 transition-transform">
                        <i data-lucide="building" class="size-6"></i>
                        {{ __('Majorelle') }}
                    </div>
                </div>
            </div>

            <!-- Feature 4: Communication (Small Card) -->
            <div class="relative group p-8 bg-slate-50 dark:bg-gray-900/60 rounded-[2.5rem] border border-slate-200/70 dark:border-white/5 flex flex-col justify-between hover:-translate-y-1.5 hover:shadow-2xl hover:border-brand-900/30 dark:hover:border-blue-500/30 transition-all duration-500 min-h-[260px]">
                <div>
                    <div class="size-12 rounded-2xl bg-amber-500/10 text-amber-600 dark:text-amber-450 flex items-center justify-center mb-6 shadow-sm">
                        <i data-lucide="bell" class="size-6"></i>
                    </div>
                    <h4 class="text-xl font-bold text-slate-900 dark:text-white mb-2">
                        {{ __('Communications') }}
                    </h4>
                    <p class="text-slate-650 dark:text-slate-400 text-sm leading-relaxed">
                        {{ __('Notifications push, rappels de charges et annonces importantes diffusées à tous les copropriétaires en un éclair.') }}
                    </p>
                </div>
                
                <!-- Custom Notification alert box -->
                <div class="mt-4 bg-white dark:bg-gray-950 p-3.5 border border-slate-100 dark:border-white/5 rounded-xl flex gap-3 shadow-sm items-start">
                    <div class="size-7 bg-amber-500/10 text-amber-500 rounded-lg flex items-center justify-center shrink-0">
                        <i data-lucide="bell" class="size-4"></i>
                    </div>
                    <div class="text-[10px] space-y-1">
                        <span class="font-bold text-slate-800 dark:text-white">{{ __('Alerte Immédiate') }}</span>
                        <p class="text-slate-500 leading-normal">{{ __('Coupure d\'eau vendredi 14 février') }}</p>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</section>
