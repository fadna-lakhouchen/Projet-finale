@extends('layouts.app')

@section('content')
<div>
    <!-- Alert Banner (Amber glass card) -->
    @if ($urgentIncident)
    <div class="relative overflow-hidden bg-amber-500/10 border border-amber-500/20 text-sm text-amber-800 dark:text-amber-400 rounded-2xl p-4 mb-6 shadow-sm flex items-start gap-x-3.5" role="alert">
        <div class="size-8 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-600 dark:text-amber-400 shrink-0">
            <i data-lucide="alert-circle" class="size-4.5"></i>
        </div>
        <div>
            <span class="font-bold text-amber-700 dark:text-amber-300">À faire :</span> L'intervention concernant <strong>{{ $urgentIncident->titre }}</strong> ({{ $urgentIncident->immeuble->nom ?? 'N/A' }})@if($urgentIncident->user), signalée par <strong>{{ $urgentIncident->user->name }}</strong>,@endif nécessite votre attention.
        </div>
    </div>
    @endif

    <!-- Cash collection requests banner (Emerald glass card) -->
    @if (isset($demandesCollecte) && $demandesCollecte->isNotEmpty())
    <div class="mb-6 space-y-3">
        @foreach ($demandesCollecte as $demande)
            <div class="relative overflow-hidden bg-emerald-500/10 border border-emerald-500/20 text-sm text-emerald-800 dark:text-emerald-400 rounded-2xl p-4 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4" role="alert">
                <div class="flex items-start gap-x-3.5">
                    <div>
                        <span class="font-bold text-emerald-700 dark:text-emerald-300">Demande de collecte cash :</span> {{ $demande->message }}
                        <span class="block text-[11px] text-slate-500 dark:text-neutral-400 mt-1 font-semibold">Signalée {{ $demande->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                <div class="flex items-center gap-x-2 shrink-0">
                    <a href="{{ route('syndic.paiements') }}" class="py-1.5 px-3 inline-flex items-center gap-x-1.5 text-xs font-semibold rounded-xl border border-transparent bg-emerald-600 text-white hover:bg-emerald-700 shadow-sm transition-all">
                        <i data-lucide="plus" class="size-3.5"></i>
                        Saisir le paiement
                    </a>
                    <form action="{{ route('notifications.read.single', $demande->id) }}" method="POST" class="inline-block">
                        @csrf
                        <button type="submit" class="py-1.5 px-3 inline-flex items-center gap-x-1.5 text-xs font-semibold rounded-xl border border-emerald-200/30 hover:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 dark:border-emerald-900/30 transition-all" title="Marquer comme traité et masquer">
                            <i data-lucide="check" class="size-3.5"></i>
                            Masquer
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
    @endif

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
                        @forelse ($activites as $act)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/30 transition-colors {{ $act['bg_row'] }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-x-3">
                                    <div class="size-8 rounded-xl bg-{{ $act['color'] }}-500/10 border border-{{ $act['color'] }}-500/20 flex items-center justify-center text-{{ $act['color'] }}-600 dark:text-{{ $act['color'] }}-450">
                                        <i data-lucide="{{ $act['icon'] }}" class="size-4"></i>
                                    </div>
                                    <span class="block text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $act['evenement'] }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-neutral-400">{{ $act['concerne'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-850 dark:text-white font-medium">{{ $act['details'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-end text-sm text-slate-500 dark:text-neutral-400">
                                {{ \Carbon\Carbon::parse($act['date'])->diffForHumans() }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-sm text-slate-450 dark:text-neutral-500">
                                <div class="flex flex-col items-center justify-center py-4">
                                    <i data-lucide="calendar" class="size-8 text-slate-300 dark:text-neutral-600 mb-2"></i>
                                    <span>Aucune activité récente pour le moment.</span>
                                </div>
                            </td>
                        </tr>
                        @endforelse
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
