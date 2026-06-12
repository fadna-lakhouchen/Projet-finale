<!-- CTA Section -->
<section class="py-28 relative overflow-hidden bg-slate-50 dark:bg-[#070B16] border-t border-slate-200/60 dark:border-white/5 text-slate-800 dark:text-white">
    <!-- Overlay effects -->
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#0f172a02_1px,transparent_1px),linear-gradient(to_bottom,#0f172a02_1px,transparent_1px)] bg-[size:32px_32px] pointer-events-none"></div>
    <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[350px] bg-gradient-to-r from-blue-500/5 to-indigo-500/5 blur-[130px] rounded-full pointer-events-none"></div>
    
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center z-10 animate-fade-in-up">
        <h2 class="font-heading text-4xl md:text-6xl font-extrabold text-slate-900 dark:text-white mb-6">Prêt à digitaliser votre copropriété ?</h2>
        <p class="text-base sm:text-lg md:text-xl text-slate-600 dark:text-slate-400 mb-12 max-w-2xl mx-auto leading-relaxed">
            Rejoignez dès aujourd'hui les résidences au Maroc qui font confiance à ImmoSyndic pour simplifier leur quotidien.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4.5">
            <a href="/login" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4.5 text-base font-bold text-white transition-all duration-300 bg-brand-900 hover:bg-brand-850 rounded-xl hover:shadow-[0_10px_25px_rgba(8,101,253,0.35)] hover:scale-105">
                Accéder au Portail
                <i data-lucide="arrow-right" class="ml-2.5 size-5"></i>
            </a>
            @if (Route::has('register'))
                <a href="/register" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4.5 text-base font-bold text-slate-700 bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 dark:text-white rounded-xl hover:bg-slate-50 dark:hover:bg-white/10">
                    S'inscrire
                </a>
            @endif
        </div>
    </div>
</section>
