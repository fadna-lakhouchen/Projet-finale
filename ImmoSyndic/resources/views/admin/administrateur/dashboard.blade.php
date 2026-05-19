@extends('layouts.app')

@section('content')
<div class="space-y-8">

    <!-- Alert Banner -->
    @if($stats['paiements_retard'] > 0)
    <div class="bg-amber-500/10 border border-amber-500/20 text-sm text-amber-600 dark:text-amber-400 rounded-2xl p-4 shadow-premium backdrop-blur-md flex items-start gap-x-3 animate-pulse" role="alert">
        <i data-lucide="alert-circle" class="size-5 shrink-0 mt-0.5 text-amber-500"></i>
        <div>
            <span class="font-bold">Attention :</span> {{ $stats['paiements_retard'] }} paiements de cotisations sont actuellement en retard ce mois-ci. Veuillez effectuer les rappels nécessaires.
        </div>
    </div>
    @endif

    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-850 dark:text-white">Supervision Dashboard</h2>
            <p class="text-sm text-slate-550 dark:text-neutral-400">Suivi global de l'activité de copropriété, de la performance financière et des alertes.</p>
        </div>
        <button type="button" data-hs-overlay="#hs-modal-export-report" class="py-2.5 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-bold rounded-xl border border-transparent bg-gradient-to-r from-primary-600 to-purple-600 text-white hover:from-primary-700 hover:to-purple-700 shadow-md shadow-primary-500/15 transition-all glow-hover">
            <i data-lucide="download" class="size-4"></i>
            Exporter Rapport
        </button>
    </div>

    <!-- Stats Grids -->
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <!-- Card 1: Total Residents -->
        <div class="p-5 bg-white border border-gray-200/60 rounded-2xl shadow-premium hover:shadow-premium-hover transition-all duration-300 dark:bg-[#0D121F] dark:border-slate-800/60 flex items-center justify-between group">
            <div class="space-y-2">
                <span class="text-xs font-bold text-slate-400 dark:text-neutral-450 uppercase tracking-wider">Total Résidents</span>
                <div class="flex items-baseline gap-x-2">
                    <span class="text-3xl font-extrabold text-slate-850 dark:text-white leading-none">{{ number_format($stats['total_residents']) }}</span>
                </div>
            </div>
            <div class="size-12 rounded-xl bg-primary-500/10 text-primary-500 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                <i data-lucide="users" class="size-5.5"></i>
            </div>
        </div>

        <!-- Card 2: Payments / Delay -->
        <div class="p-5 bg-white border border-gray-200/60 rounded-2xl shadow-premium hover:shadow-premium-hover transition-all duration-300 dark:bg-[#0D121F] dark:border-slate-800/60 flex items-center justify-between group">
            <div class="space-y-2">
                <span class="text-xs font-bold text-slate-400 dark:text-neutral-450 uppercase tracking-wider">Paiements en Retard</span>
                <div class="flex items-baseline gap-x-2">
                    <span class="text-3xl font-extrabold text-rose-500 leading-none">{{ $stats['paiements_retard'] }}</span>
                    <span class="text-xs font-semibold text-slate-400 dark:text-neutral-500">/ {{ $stats['total_paiements_attendus'] }} total</span>
                </div>
            </div>
            <div class="size-12 rounded-xl bg-rose-500/10 text-rose-500 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                <i data-lucide="wallet" class="size-5.5"></i>
            </div>
        </div>

        <!-- Card 3: Open Incidents -->
        <div class="p-5 bg-white border border-gray-200/60 rounded-2xl shadow-premium hover:shadow-premium-hover transition-all duration-300 dark:bg-[#0D121F] dark:border-slate-800/60 flex items-center justify-between group">
            <div class="space-y-2">
                <span class="text-xs font-bold text-slate-400 dark:text-neutral-450 uppercase tracking-wider">Problèmes Ouverts</span>
                <div class="flex items-baseline gap-x-2">
                    <span class="text-3xl font-extrabold text-slate-850 dark:text-white leading-none">{{ $stats['incidents_ouverts'] }}</span>
                </div>
            </div>
            <div class="size-12 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                <i data-lucide="alert-triangle" class="size-5.5"></i>
            </div>
        </div>

        <!-- Card 4: Buildings managed -->
        <div class="p-5 bg-white border border-gray-200/60 rounded-2xl shadow-premium hover:shadow-premium-hover transition-all duration-300 dark:bg-[#0D121F] dark:border-slate-800/60 flex items-center justify-between group">
            <div class="space-y-2">
                <span class="text-xs font-bold text-slate-400 dark:text-neutral-450 uppercase tracking-wider">Immeubles Gérés</span>
                <div class="flex items-baseline gap-x-2">
                    <span class="text-3xl font-extrabold text-slate-850 dark:text-white leading-none">{{ $stats['total_immeubles'] }}</span>
                </div>
            </div>
            <div class="size-12 rounded-xl bg-purple-500/10 text-purple-500 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                <i data-lucide="building-2" class="size-5.5"></i>
            </div>
        </div>
    </div>

    <!-- Activity Table -->
    <div class="flex flex-col border border-gray-200/60 dark:border-slate-800/60 rounded-2xl shadow-premium bg-white dark:bg-[#0D121F] overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200/60 dark:border-slate-800/60 bg-slate-50/50 dark:bg-slate-900/30 flex items-center justify-between">
            <h4 class="text-sm font-bold text-slate-800 dark:text-white">Activité Récente</h4>
            <span class="inline-flex items-center gap-x-1.5 py-1 px-2.5 rounded-full text-xs font-bold bg-primary-500/10 text-primary-500">
                <span class="size-1.5 inline-block bg-primary-500 rounded-full animate-ping"></span> Live Logs
            </span>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-250 dark:divide-slate-800">
                <thead class="bg-slate-50 dark:bg-slate-900/50">
                    <tr>
                        <th scope="col" class="px-6 py-3.5 text-start text-xs font-semibold text-slate-400 uppercase dark:text-neutral-450">Utilisateur</th>
                        <th scope="col" class="px-6 py-3.5 text-start text-xs font-semibold text-slate-400 uppercase dark:text-neutral-450">Action</th>
                        <th scope="col" class="px-6 py-3.5 text-start text-xs font-semibold text-slate-400 uppercase dark:text-neutral-450">Détails</th>
                        <th scope="col" class="px-6 py-3.5 text-end text-xs font-semibold text-slate-400 uppercase dark:text-neutral-450">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200/60 dark:divide-slate-800/60">
                    @forelse($recentActivity as $log)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/10 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-x-3">
                                <div class="size-8.5 rounded-xl bg-gradient-to-tr from-primary-500 to-purple-600 text-white font-bold flex items-center justify-center shadow-md shadow-primary-500/10 text-xs shrink-0">
                                    {{ substr($log->user->prenom, 0, 1) }}{{ substr($log->user->nom, 0, 1) }}
                                </div>
                                <div class="grow">
                                    <span class="block text-sm font-bold text-slate-800 dark:text-neutral-250">{{ $log->user->prenom }} {{ $log->user->nom }}</span>
                                    <span class="block text-[10px] text-slate-400 dark:text-neutral-500 font-semibold">{{ $log->user->email }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-slate-700 dark:text-neutral-300">
                            {{ $log->action }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-550 dark:text-neutral-400">
                            {{ $log->details }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-semibold text-slate-400 dark:text-neutral-450">
                            {{ $log->created_at->diffForHumans() }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-sm font-medium text-slate-400 dark:text-neutral-500">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <i data-lucide="archive" class="size-8 text-slate-300 dark:text-neutral-700"></i>
                                <span>Aucune activité récente enregistrée.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Export Rapport (Modernized Glassmorphism) -->
<div id="hs-modal-export-report" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none" role="dialog" tabindex="-1" aria-labelledby="hs-modal-export-report-label">
    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
        <div class="w-full flex flex-col bg-white/95 border border-gray-200/60 shadow-xl rounded-2xl pointer-events-auto dark:bg-slate-900/95 dark:border-slate-800/60 backdrop-blur-xl">
            <div class="flex justify-between items-center py-3.5 px-5 border-b border-gray-200/50 dark:border-slate-800/50">
                <h3 id="hs-modal-export-report-label" class="font-bold text-slate-800 dark:text-white">Exporter un rapport d'activité</h3>
                <button type="button" class="size-8 inline-flex justify-center items-center gap-x-2 rounded-xl border border-transparent bg-slate-100 text-slate-800 hover:bg-slate-200 focus:outline-none dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-neutral-400" aria-label="Close" data-hs-overlay="#hs-modal-export-report">
                    <span class="sr-only">Close</span>
                    <i data-lucide="x" class="size-4"></i>
                </button>
            </div>
            <div class="p-5 overflow-y-auto text-sm text-slate-600 dark:text-neutral-400 space-y-3">
                <p>Sélectionnez le format et la période pour l'exportation du rapport d'activité global.</p>
                <div class="space-y-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400">Format</label>
                    <select class="py-2.5 px-3 block w-full border-gray-200/80 rounded-xl text-sm dark:bg-slate-800 dark:border-slate-700 dark:text-neutral-300">
                        <option>Format PDF (.pdf)</option>
                        <option>Format Excel (.xlsx)</option>
                        <option>Format CSV (.csv)</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end items-center gap-x-2 py-3 px-5 border-t border-gray-200/50 dark:border-slate-800/50">
                <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-xl border border-gray-200 bg-white text-slate-700 shadow-sm hover:bg-slate-50 dark:bg-slate-800 dark:border-slate-750 dark:text-white dark:hover:bg-slate-700" data-hs-overlay="#hs-modal-export-report">Annuler</button>
                <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-xl border border-transparent bg-gradient-to-r from-primary-600 to-purple-600 text-white hover:from-primary-700 hover:to-purple-700 shadow-md shadow-primary-500/10 transition-all glow-hover" data-hs-overlay="#hs-modal-export-report">Générer l'export</button>
            </div>
        </div>
    </div>
</div>
@endsection

