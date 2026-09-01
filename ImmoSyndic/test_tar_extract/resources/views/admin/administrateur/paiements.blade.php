@extends('layouts.app')

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-slate-800 dark:text-white">{{ __('Supervision des Paiements') }}</h2>
        <p class="text-sm text-slate-500 dark:text-neutral-400">{{ __('Suivi global des flux financiers, statistiques et validation des données.') }}</p>
    </div>
</div>

<div x-data="{ 
    search: '', 
    immeubleSelectionne: 'all', 
    statutSelectionne: 'all',
    showImm: false,
    showStat: false,
    matches(resident, immeuble, statut) {
        const s = this.search.toLowerCase();
        const matchesSearch = resident.toLowerCase().includes(s);
        const matchesImmeuble = this.immeubleSelectionne === 'all' || immeuble === this.immeubleSelectionne;
        const matchesStatut = this.statutSelectionne === 'all' || statut === this.statutSelectionne;
        return matchesSearch && matchesImmeuble && matchesStatut;
    }
}">
    <!-- Stats Cards Grid -->
    <div class="grid sm:grid-cols-3 gap-6 mb-8">
        <!-- Collecté Card -->
        <div class="relative overflow-hidden bg-white dark:bg-[#0D121F] border border-gray-200/60 dark:border-slate-800/60 rounded-2xl p-6 shadow-premium transition-all hover:scale-[1.01] duration-300">
            <div class="absolute -right-6 -bottom-6 opacity-[0.08] dark:opacity-[0.12] text-emerald-500">
                <i data-lucide="check-circle-2" class="size-28"></i>
            </div>
            <div class="flex items-center gap-x-4">
                <div class="size-11 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                    <i data-lucide="check-circle" class="size-5.5"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 dark:text-neutral-400 uppercase tracking-widest">{{ __('Collecté (Validé)') }}</p>
                    <h3 class="text-2xl font-extrabold text-emerald-600 dark:text-emerald-400 tracking-tight mt-0.5">{{ number_format($stats['totalCollecte'], 2) }} {{ __('DH') }}</h3>
                </div>
            </div>
        </div>

        <!-- {{ __('En attente') }} Card -->
        <div class="relative overflow-hidden bg-white dark:bg-[#0D121F] border border-gray-200/60 dark:border-slate-800/60 rounded-2xl p-6 shadow-premium transition-all hover:scale-[1.01] duration-300">
            <div class="absolute -right-6 -bottom-6 opacity-[0.08] dark:opacity-[0.12] text-amber-500">
                <i data-lucide="clock" class="size-28"></i>
            </div>
            <div class="flex items-center gap-x-4">
                <div class="size-11 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-600 dark:text-amber-400">
                    <i data-lucide="clock" class="size-5.5"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 dark:text-neutral-400 uppercase tracking-widest">{{ __('En attente') }}</p>
                    <h3 class="text-2xl font-extrabold text-amber-600 dark:text-amber-550 mt-0.5">{{ number_format($stats['totalAttente'], 2) }} {{ __('DH') }}</h3>
                </div>
            </div>
        </div>

        <!-- {{ __('Total Transactions') }} Card -->
        <div class="relative overflow-hidden bg-white dark:bg-[#0D121F] border border-gray-200/60 dark:border-slate-800/60 rounded-2xl p-6 shadow-premium transition-all hover:scale-[1.01] duration-300">
            <div class="absolute -right-6 -bottom-6 opacity-[0.08] dark:opacity-[0.12] text-primary-500">
                <i data-lucide="wallet" class="size-28"></i>
            </div>
            <div class="flex items-center gap-x-4">
                <div class="size-11 rounded-xl bg-primary-500/10 border border-primary-500/20 flex items-center justify-center text-primary-600 dark:text-primary-400">
                    <i data-lucide="wallet" class="size-5.5"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 dark:text-neutral-400 uppercase tracking-widest">{{ __('Total Transactions') }}</p>
                    <h3 class="text-2xl font-extrabold text-slate-800 dark:text-white mt-0.5">{{ $stats['nbPaiements'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section Container (Premium Glass Panel) -->
    <div class="flex flex-col border border-gray-200/60 dark:border-slate-800/60 rounded-2xl shadow-premium bg-white dark:bg-[#0D121F] overflow-hidden">
        
        <!-- Filter Header -->
        <div class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-b border-gray-200/60 dark:border-slate-800/60 bg-slate-50/50 dark:bg-slate-900/30">
            <div class="sm:col-span-1 max-w-sm w-full relative">
                <input x-model="search" type="text" class="py-2.5 px-4 ps-11 block w-full border-gray-200 dark:border-slate-850 dark:bg-[#080B11] dark:text-slate-300 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500" placeholder="{{ __('Rechercher un résident...') }}">
                <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                    <i data-lucide="search" class="size-4 text-gray-400"></i>
                </div>
            </div>

            <div class="sm:col-span-2 md:grow flex justify-end gap-x-2">
                <!-- Dropdown Immeubles -->
                <div class="relative inline-flex">
                    <button @click="showImm = !showImm; showStat = false" @click.outside="showImm = false" type="button" class="py-2.5 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-xl border border-gray-200/80 bg-white/80 hover:bg-white text-slate-800 shadow-sm dark:bg-neutral-800 dark:border-neutral-700 dark:text-white transition-all">
                        <span x-text="immeubleSelectionne === 'all' ? '{{ __('Filtrer par Immeuble') }}' : immeubleSelectionne"></span>
                        <i data-lucide="chevron-down" :class="showImm ? 'rotate-180' : ''" class="size-4 transition-transform text-gray-400"></i>
                    </button>
                    <div x-show="showImm" class="absolute right-0 top-full z-[100] mt-2 w-60 bg-white border border-gray-200 shadow-xl rounded-2xl p-1.5 dark:bg-neutral-900 dark:border-neutral-800" style="display: none;"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100">
                        <div @click="immeubleSelectionne = 'all'; showImm = false" class="cursor-pointer flex items-center py-2 px-3 rounded-xl text-sm font-medium text-slate-700 hover:bg-slate-50 dark:text-neutral-350 dark:hover:bg-neutral-800/60">{{ __('Tous les immeubles') }}</div>
                        @foreach($immeubles as $immeuble)
                            <div @click="immeubleSelectionne = '{{ $immeuble->nom }}'; showImm = false" class="cursor-pointer flex items-center py-2 px-3 rounded-xl text-sm font-medium text-slate-700 hover:bg-slate-50 dark:text-neutral-350 dark:hover:bg-neutral-800/60">
                                {{ $immeuble->nom }}
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Dropdown Statuts -->
                <div class="relative inline-flex">
                    <button @click="showStat = !showStat; showImm = false" @click.outside="showStat = false" type="button" class="py-2.5 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-xl border border-gray-200/80 bg-white/80 hover:bg-white text-slate-800 shadow-sm dark:bg-neutral-800 dark:border-neutral-700 dark:text-white transition-all">
                        <span x-text="statutSelectionne === 'all' ? '{{ __('Statuts') }}' : (statutSelectionne === 'validé' ? '{{ __('Payé') }}' : '{{ __('En attente') }}')"></span>
                        <i data-lucide="chevron-down" :class="showStat ? 'rotate-180' : ''" class="size-4 transition-transform text-gray-400"></i>
                    </button>
                    <div x-show="showStat" class="absolute right-0 top-full z-[100] mt-2 w-48 bg-white border border-gray-200 shadow-xl rounded-2xl p-1.5 dark:bg-neutral-900 dark:border-neutral-800" style="display: none;"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100">
                        <div @click="statutSelectionne = 'all'; showStat = false" class="cursor-pointer flex items-center py-2 px-3 rounded-xl text-sm font-medium text-slate-700 hover:bg-slate-50 dark:text-neutral-350 dark:hover:bg-neutral-800/60">{{ __('Tous les statuts') }}</div>
                        <div @click="statutSelectionne = 'validé'; showStat = false" class="cursor-pointer flex items-center py-2 px-3 rounded-xl text-sm font-medium text-slate-700 hover:bg-slate-50 dark:text-neutral-350 dark:hover:bg-neutral-800/60">{{ __('Payé (Validé)') }}</div>
                        <div @click="statutSelectionne = 'en attente'; showStat = false" class="cursor-pointer flex items-center py-2 px-3 rounded-xl text-sm font-medium text-slate-700 hover:bg-slate-50 dark:text-neutral-350 dark:hover:bg-neutral-800/60">{{ __('En attente') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Grid -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-250 dark:divide-slate-800">
                <thead class="bg-slate-50 dark:bg-slate-900/50">
                    <tr>
                        <th scope="col" class="px-6 py-3.5 text-start text-xs font-bold text-slate-400 uppercase dark:text-neutral-450 tracking-wider">{{ __('Résident') }}</th>
                        <th scope="col" class="px-6 py-3.5 text-start text-xs font-bold text-slate-400 uppercase dark:text-neutral-450 tracking-wider">{{ __('Immeuble & Appt') }}</th>
                        <th scope="col" class="px-6 py-3.5 text-start text-xs font-bold text-slate-400 uppercase dark:text-neutral-450 tracking-wider">{{ __('Montant') }}</th>
                        <th scope="col" class="px-6 py-3.5 text-start text-xs font-bold text-slate-400 uppercase dark:text-neutral-450 tracking-wider">{{ __('Date') }}</th>
                        <th scope="col" class="px-6 py-3.5 text-start text-xs font-bold text-slate-400 uppercase dark:text-neutral-450 tracking-wider">{{ __('Statut') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200/60 dark:divide-slate-800/60">
                    @foreach($paiements as $paiement)
                    @php
                        $appt = $paiement->charge?->appartement;
                        $immeubleName = $appt?->immeuble?->nom ?? 'N/A';
                        $residentName = $paiement->user?->prenom . ' ' . $paiement->user?->nom;
                    @endphp
                    <tr x-show="matches('{{ $residentName }}', '{{ $immeubleName }}', '{{ $paiement->statut }}')" class="hover:bg-slate-50/50 dark:hover:bg-slate-900/30 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $residentName }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-neutral-400">{{ $immeubleName }} - {{ __('Appt') }} {{ $appt?->numero }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-850 dark:text-white">{{ number_format($paiement->montant, 2) }} {{ __('DH') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-neutral-400">{{ $paiement->date_paiement->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($paiement->statut === 'validé')
                                <span class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                                    <span class="size-1.5 rounded-full bg-emerald-500"></span>
                                    {{ __('Payé') }}
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-full text-xs font-bold bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20">
                                    <span class="size-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                    {{ __('En attente') }}
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
