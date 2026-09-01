<!-- About / Challenges Section -->
<section id="about" class="py-24 bg-white dark:bg-gray-950 border-t border-slate-200/60 dark:border-white/5 relative overflow-hidden text-slate-800 dark:text-white">
    <!-- Subtle gradients -->
    <div class="absolute left-0 bottom-0 w-[30rem] h-[30rem] bg-blue-500/5 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute right-0 top-0 w-[30rem] h-[30rem] bg-indigo-500/5 rounded-full blur-[120px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-20">
            <span class="text-xs font-bold uppercase tracking-widest text-brand-900 bg-blue-50 px-3.5 py-1.5 rounded-full mb-4 inline-block dark:bg-white/5 dark:text-blue-300">
                {{ __('Les défis du Syndic') }}
            </span>
            <h2 class="font-heading text-3xl md:text-5xl font-extrabold text-slate-900 dark:text-white leading-tight">
                {{ __('Pourquoi la gestion classique de copropriété échoue au Maroc ?') }}
            </h2>
            <p class="text-slate-600 dark:text-slate-400 text-base sm:text-lg mt-4 leading-relaxed">
                {{ __('Gérer un immeuble sur papier ou via des groupes WhatsApp crée des conflits et bloque la caisse. Voici comment ImmoSyn change la donne.') }}
            </p>
        </div>

        <!-- Problems vs Solutions Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Challenge 1 -->
            <div class="bg-slate-50 dark:bg-gray-900/60 border border-slate-200/60 dark:border-white/5 p-8 rounded-3xl space-y-6 hover:shadow-lg transition-all duration-300 group">
                <div class="flex items-center gap-4">
                    <div class="size-12 rounded-2xl bg-rose-50 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400 flex items-center justify-center font-bold">
                        <i data-lucide="alert-triangle" class="size-6"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">{{ __('Problème : Les impayés chroniques') }}</h3>
                </div>
                <p class="text-slate-650 dark:text-slate-400 text-sm leading-relaxed">
                    {{ __('Frapper aux portes des résidents, envoyer des rappels manuels, faire face aux oublis... Le recouvrement des charges est le premier calvaire d\'un syndic.') }}
                </p>
                <div class="pt-4 border-t border-slate-200/50 dark:border-white/5 flex items-start gap-3 text-emerald-600 dark:text-emerald-450">
                    <i data-lucide="check-circle" class="size-5 shrink-0 mt-0.5"></i>
                    <div class="text-sm">
                        <span class="font-bold text-slate-900 dark:text-white block mb-0.5">{{ __('Solution ImmoSyn') }}</span>
                        {{ __('Relances automatiques par email/push et notifications de charges prêtes. Le recouvrement devient rigoureux et sans relance physique gênante.') }}
                    </div>
                </div>
            </div>

            <!-- Challenge 2 -->
            <div class="bg-slate-50 dark:bg-gray-900/60 border border-slate-200/60 dark:border-white/5 p-8 rounded-3xl space-y-6 hover:shadow-lg transition-all duration-300 group">
                <div class="flex items-center gap-4">
                    <div class="size-12 rounded-2xl bg-rose-50 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400 flex items-center justify-center font-bold">
                        <i data-lucide="shield-alert" class="size-6"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">{{ __('Problème : Manque de confiance et de clarté') }}</h3>
                </div>
                <p class="text-slate-650 dark:text-slate-400 text-sm leading-relaxed">
                    {{ __('Les copropriétaires doutent souvent de la bonne utilisation de leur argent, ce qui les pousse à suspendre leurs paiements de cotisations.') }}
                </p>
                <div class="pt-4 border-t border-slate-200/50 dark:border-white/5 flex items-start gap-3 text-emerald-600 dark:text-emerald-450">
                    <i data-lucide="check-circle" class="size-5 shrink-0 mt-0.5"></i>
                    <div class="text-sm">
                        <span class="font-bold text-slate-900 dark:text-white block mb-0.5">{{ __('Solution ImmoSyn') }}</span>
                        {{ __('Une caisse commune visible en direct et un historique transparent des dépenses avec factures à l\'appui pour restaurer une confiance absolue.') }}
                    </div>
                </div>
            </div>

            <!-- Challenge 3 -->
            <div class="bg-slate-50 dark:bg-gray-900/60 border border-slate-200/60 dark:border-white/5 p-8 rounded-3xl space-y-6 hover:shadow-lg transition-all duration-300 group">
                <div class="flex items-center gap-4">
                    <div class="size-12 rounded-2xl bg-rose-50 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400 flex items-center justify-center font-bold">
                        <i data-lucide="message-square" class="size-6"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">{{ __('Problème : WhatsApp surchargé et plaintes perdues') }}</h3>
                </div>
                <p class="text-slate-650 dark:text-slate-400 text-sm leading-relaxed">
                    {{ __('Mélanger les pannes urgentes, les discussions amicales et les plaintes rend la communication ingérable et les urgences passent inaperçues.') }}
                </p>
                <div class="pt-4 border-t border-slate-200/50 dark:border-white/5 flex items-start gap-3 text-emerald-600 dark:text-emerald-450">
                    <i data-lucide="check-circle" class="size-5 shrink-0 mt-0.5"></i>
                    <div class="text-sm">
                        <span class="font-bold text-slate-900 dark:text-white block mb-0.5">{{ __('Solution ImmoSyn') }}</span>
                        {{ __('Un système de tickets dédié pour déclarer les pannes (ascenseur, fuite) avec suivi d\'avancement en temps réel jusqu\'à la réparation.') }}
                    </div>
                </div>
            </div>

            <!-- Challenge 4 -->
            <div class="bg-slate-50 dark:bg-gray-900/60 border border-slate-200/60 dark:border-white/5 p-8 rounded-3xl space-y-6 hover:shadow-lg transition-all duration-300 group">
                <div class="flex items-center gap-4">
                    <div class="size-12 rounded-2xl bg-rose-50 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400 flex items-center justify-center font-bold">
                        <i data-lucide="book-open" class="size-6"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">{{ __('Problème : Gestion administrative papier lourde') }}</h3>
                </div>
                <p class="text-slate-650 dark:text-slate-400 text-sm leading-relaxed">
                    {{ __('Tenir des cahiers de reçus physiques, éditer des rapports de caisse complexes et imprimer les PV d\'Assemblées Générales prend un temps infini.') }}
                </p>
                <div class="pt-4 border-t border-slate-200/50 dark:border-white/5 flex items-start gap-3 text-emerald-600 dark:text-emerald-450">
                    <i data-lucide="check-circle" class="size-5 shrink-0 mt-0.5"></i>
                    <div class="text-sm">
                        <span class="font-bold text-slate-900 dark:text-white block mb-0.5">{{ __('Solution ImmoSyn') }}</span>
                        {{ __('Zéro papier. Reçus générés en PDF à l\'encaissement, bilans financiers exportables en Excel, et partage cloud des documents légaux.') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
