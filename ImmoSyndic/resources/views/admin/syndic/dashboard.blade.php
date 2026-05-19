@extends('layouts.app')

@section('content')
<div>
    <!-- Alert Banner (Amber glass card) -->
    <div class="relative overflow-hidden bg-amber-500/10 border border-amber-500/20 text-sm text-amber-800 dark:text-amber-400 rounded-2xl p-4 mb-6 shadow-sm flex items-start gap-x-3.5" role="alert">
        <div class="size-8 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-600 dark:text-amber-400 shrink-0">
            <i data-lucide="alert-circle" class="size-4.5"></i>
        </div>
        <div>
            <span class="font-bold text-amber-700 dark:text-amber-300">À faire :</span> L'intervention concernant l'ascenseur du bâtiment A (Résidence Al Amal) nécessite une affectation de prestataire d'urgence.
        </div>
    </div>

    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 dark:text-white">Tableau de Bord Syndic</h2>
            <p class="text-sm text-slate-500 dark:text-neutral-400">Suivi des immeubles et des résidents sous votre responsabilité.</p>
        </div>
        <button type="button" data-hs-overlay="#hs-modal-new-signalement" class="py-2.5 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-bold rounded-xl border border-transparent bg-gradient-to-r from-primary-600 to-purple-600 text-white hover:from-primary-700 hover:to-purple-700 shadow-md shadow-primary-500/15 transition-all glow-hover">
            <i data-lucide="plus" class="size-4.5"></i>
            Nouveau signalement
        </button>
    </div>

    <!-- 3 Stats Cards Grid -->
    <div class="grid sm:grid-cols-3 gap-6 mb-8">
        <!-- Card 1 (Residents) -->
        <div class="relative overflow-hidden bg-white dark:bg-[#0D121F] border border-gray-200/60 dark:border-slate-800/60 rounded-2xl p-6 shadow-premium transition-all hover:scale-[1.01] duration-300">
            <div class="absolute -right-6 -bottom-6 opacity-[0.08] dark:opacity-[0.12] text-primary-500">
                <i data-lucide="users" class="size-28"></i>
            </div>
            <div class="flex items-center gap-x-4">
                <div class="size-11 rounded-xl bg-primary-500/10 border border-primary-500/20 flex items-center justify-center text-primary-600 dark:text-primary-400">
                    <i data-lucide="users" class="size-5.5"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 dark:text-neutral-400 uppercase tracking-widest">Total Résidents</p>
                    <div class="flex items-baseline gap-x-2 mt-0.5">
                        <h3 class="text-2xl font-extrabold text-slate-800 dark:text-white">{{ $stats['total_residents'] }}</h3>
                        <span class="text-xs font-bold text-slate-400 dark:text-neutral-450">/ {{ $stats['total_appartements'] }} appartements</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2 (Finance) -->
        <div class="relative overflow-hidden bg-white dark:bg-[#0D121F] border border-gray-200/60 dark:border-slate-800/60 rounded-2xl p-6 shadow-premium transition-all hover:scale-[1.01] duration-300">
            <div class="absolute -right-6 -bottom-6 opacity-[0.08] dark:opacity-[0.12] text-emerald-500">
                <i data-lucide="wallet" class="size-28"></i>
            </div>
            <div class="flex items-center gap-x-4">
                <div class="size-11 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                    <i data-lucide="wallet" class="size-5.5"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 dark:text-neutral-400 uppercase tracking-widest">Collecté ce mois</p>
                    <h3 class="text-2xl font-extrabold text-emerald-600 dark:text-emerald-400 tracking-tight mt-0.5">{{ number_format($stats['paiements_ce_mois'], 2) }} DH</h3>
                </div>
            </div>
        </div>

        <!-- Card 3 (Issues) -->
        <div class="relative overflow-hidden bg-white dark:bg-[#0D121F] border border-gray-200/60 dark:border-slate-800/60 rounded-2xl p-6 shadow-premium transition-all hover:scale-[1.01] duration-300">
            <div class="absolute -right-6 -bottom-6 opacity-[0.08] dark:opacity-[0.12] text-rose-500">
                <i data-lucide="alert-triangle" class="size-28"></i>
            </div>
            <div class="flex items-center gap-x-4">
                <div class="size-11 rounded-xl bg-rose-500/10 border border-rose-500/20 flex items-center justify-center text-rose-600 dark:text-rose-400">
                    <i data-lucide="alert-triangle" class="size-5.5"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 dark:text-neutral-400 uppercase tracking-widest">Problèmes Ouverts</p>
                    <h3 class="text-2xl font-extrabold text-rose-600 dark:text-rose-500 mt-0.5">{{ $stats['incidents_ouverts'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section Container (Premium Glass Panel) -->
    <div class="mb-6">
        <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-4">Activité récente sur vos immeubles</h3>
        
        <div class="flex flex-col border border-gray-200/60 dark:border-slate-800/60 rounded-2xl shadow-premium bg-white dark:bg-[#0D121F] overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-250 dark:divide-slate-800">
                    <thead class="bg-slate-50 dark:bg-slate-900/50">
                        <tr>
                            <th scope="col" class="px-6 py-3.5 text-start text-xs font-bold text-slate-400 uppercase dark:text-neutral-450 tracking-wider">Événement</th>
                            <th scope="col" class="px-6 py-3.5 text-start text-xs font-bold text-slate-400 uppercase dark:text-neutral-450 tracking-wider">Concerne</th>
                            <th scope="col" class="px-6 py-3.5 text-start text-xs font-bold text-slate-400 uppercase dark:text-neutral-450 tracking-wider">Détails</th>
                            <th scope="col" class="px-6 py-3.5 text-end text-xs font-bold text-slate-400 uppercase dark:text-neutral-450 tracking-wider">Date & Heure</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200/60 dark:divide-slate-800/60">
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/30 transition-colors bg-rose-500/[0.02]">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-x-3">
                                    <div class="size-8 rounded-xl bg-rose-500/10 border border-rose-500/20 flex items-center justify-center text-rose-600 dark:text-rose-450">
                                        <i data-lucide="alert-triangle" class="size-4"></i>
                                    </div>
                                    <span class="block text-sm font-semibold text-slate-800 dark:text-slate-200">Signalement (Admin)</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-neutral-400">Résidence Al Amal</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-850 dark:text-white font-medium">Ordre d'intervention: Ascenseur Bât A</td>
                            <td class="px-6 py-4 whitespace-nowrap text-end text-sm text-slate-500 dark:text-neutral-400">Aujourd'hui, 09:12</td>
                        </tr>
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/30 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-x-3">
                                    <div class="size-8 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-450">
                                        <i data-lucide="check" class="size-4"></i>
                                    </div>
                                    <span class="block text-sm font-semibold text-slate-800 dark:text-slate-200">Paiement reçu</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-neutral-400">Tour Hassan - Appt 12</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-850 dark:text-white font-medium">850 MAD (Charges Mars)</td>
                            <td class="px-6 py-4 whitespace-nowrap text-end text-sm text-slate-500 dark:text-neutral-400">Hier, 18:30</td>
                        </tr>
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/30 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-x-3">
                                    <div class="size-8 rounded-xl bg-primary-500/10 border border-primary-500/20 flex items-center justify-center text-primary-600 dark:text-primary-450">
                                        <i data-lucide="info" class="size-4"></i>
                                    </div>
                                    <span class="block text-sm font-semibold text-slate-800 dark:text-slate-200">Signalement Résident</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-neutral-400">Résidence Al Amal - Appt 4</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-850 dark:text-white font-medium">Ampoule couloir grillée</td>
                            <td class="px-6 py-4 whitespace-nowrap text-end text-sm text-slate-500 dark:text-neutral-400">10 Mars 2026</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nouveau Signalement (Premium Styling) -->
<div id="hs-modal-new-signalement" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none" role="dialog" tabindex="-1" aria-labelledby="hs-modal-new-signalement-label">
    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
        <div class="w-full flex flex-col bg-white dark:bg-[#0D121F] border border-gray-200/60 dark:border-slate-800/60 shadow-premium rounded-2xl pointer-events-auto overflow-hidden">
            <div class="flex justify-between items-center py-4 px-6 border-b border-gray-200/60 dark:border-slate-800/60">
                <div class="flex items-center gap-x-2.5">
                    <div class="size-8 rounded-lg bg-primary-500/10 border border-primary-500/20 flex items-center justify-center text-primary-600 dark:text-primary-400">
                        <i data-lucide="plus" class="size-4"></i>
                    </div>
                    <h3 id="hs-modal-new-signalement-label" class="font-bold text-slate-800 dark:text-white">Nouveau Signalement / Intervention</h3>
                </div>
                <button type="button" class="size-8 inline-flex justify-center items-center rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:text-neutral-400 transition-all" data-hs-overlay="#hs-modal-new-signalement">
                    <i data-lucide="x" class="size-4"></i>
                </button>
            </div>
            
            <div class="p-6 space-y-4">
                <div class="bg-slate-50 dark:bg-[#080B11] border border-slate-200/60 dark:border-slate-800/60 rounded-xl p-4 text-center">
                    <i data-lucide="wrench" class="size-8 mx-auto text-primary-500 mb-2"></i>
                    <p class="text-sm text-slate-600 dark:text-slate-350">Le formulaire d'ajout d'intervention sera configuré dynamiquement par le système de gestion.</p>
                </div>
            </div>
            
            <div class="flex justify-end items-center gap-x-3 py-4 px-6 border-t border-gray-200/60 dark:border-slate-800/60 bg-slate-50/50 dark:bg-slate-900/30">
                <button type="button" class="py-2.5 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-xl border border-gray-200 bg-white hover:bg-gray-50 text-slate-800 dark:bg-neutral-850 dark:border-neutral-800 dark:text-white dark:hover:bg-neutral-800 transition-all shadow-sm" data-hs-overlay="#hs-modal-new-signalement">Annuler</button>
                <button type="button" class="py-2.5 px-4 inline-flex items-center gap-x-2 text-sm font-bold rounded-xl border border-transparent bg-gradient-to-r from-primary-600 to-purple-600 text-white hover:from-primary-700 hover:to-purple-700 shadow-md shadow-primary-500/15 transition-all glow-hover" data-hs-overlay="#hs-modal-new-signalement">Créer le signalement</button>
            </div>
        </div>
    </div>
</div>
@endsection
