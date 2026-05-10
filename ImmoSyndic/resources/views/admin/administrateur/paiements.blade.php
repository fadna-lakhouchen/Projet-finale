@extends('layouts.app')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Supervision des Paiements</h2>
        <p class="text-sm text-gray-600 dark:text-neutral-400">Suivi global des flux financiers et validation des données.</p>
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
    <!-- Stats Row -->
    <div class="grid sm:grid-cols-3 gap-4 mb-8">
        <div class="bg-white border border-gray-200 rounded-xl p-4 dark:bg-neutral-800 dark:border-neutral-700">
            <p class="text-xs font-semibold text-gray-500 uppercase dark:text-neutral-500">Collecté (Validé)</p>
            <div class="flex items-center gap-x-2 mt-1">
                <h3 class="text-xl font-bold text-green-600">{{ number_format($stats['totalCollecte'], 2) }} DH</h3>
            </div>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4 dark:bg-neutral-800 dark:border-neutral-700">
            <p class="text-xs font-semibold text-gray-500 uppercase dark:text-neutral-500">En attente</p>
            <div class="flex items-center gap-x-2 mt-1">
                <h3 class="text-xl font-bold text-yellow-600">{{ number_format($stats['totalAttente'], 2) }} DH</h3>
            </div>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4 dark:bg-neutral-800 dark:border-neutral-700">
            <p class="text-xs font-semibold text-gray-500 uppercase dark:text-neutral-500">Total Transactions</p>
            <div class="flex items-center gap-x-2 mt-1">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white">{{ $stats['nbPaiements'] }}</h3>
            </div>
        </div>
    </div>

    <!-- Table Section Container -->
    <div class="flex flex-col border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
        
        <!-- Filter Header -->
        <div class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-b border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 rounded-t-xl">
            <div class="sm:col-span-1 max-w-sm w-full relative">
                <label for="pay-search" class="sr-only">Rechercher</label>
                <input x-model="search" type="text" id="pay-search" class="py-2 px-3 ps-11 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" placeholder="Rechercher résident...">
                <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                    <i data-lucide="search" class="size-4 text-gray-400"></i>
                </div>
            </div>

            <div class="sm:col-span-2 md:grow flex justify-end gap-x-2">
                <!-- Dropdown Immeubles -->
                <div class="relative inline-flex">
                  <button @click="showImm = !showImm; showStat = false" @click.outside="showImm = false" type="button" class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white">
                    <span x-text="immeubleSelectionne === 'all' ? 'Immeubles' : immeubleSelectionne"></span>
                    <i data-lucide="chevron-down" :class="showImm ? 'rotate-180' : ''" class="size-4 transition-transform text-gray-400"></i>
                  </button>
                  <div x-show="showImm" class="absolute right-0 top-full z-[100] mt-1 w-56 bg-white border border-gray-200 shadow-xl rounded-lg p-1 dark:bg-neutral-800 dark:border-neutral-700" style="display: none;">
                    <div @click="immeubleSelectionne = 'all'; showImm = false" class="cursor-pointer flex items-center py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700">Tous les immeubles</div>
                    @foreach($immeubles as $immeuble)
                        <div @click="immeubleSelectionne = '{{ $immeuble->nom }}'; showImm = false" class="cursor-pointer flex items-center py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700">
                            {{ $immeuble->nom }}
                        </div>
                    @endforeach
                  </div>
                </div>

                <!-- Dropdown Statuts (Corrected with DB Values) -->
                <div class="relative inline-flex">
                  <button @click="showStat = !showStat; showImm = false" @click.outside="showStat = false" type="button" class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white">
                    <span x-text="statutSelectionne === 'all' ? 'Statuts' : (statutSelectionne === 'validé' ? 'Payé' : 'En attente')"></span>
                    <i data-lucide="chevron-down" :class="showStat ? 'rotate-180' : ''" class="size-4 transition-transform text-gray-400"></i>
                  </button>
                  <div x-show="showStat" class="absolute right-0 top-full z-[100] mt-2 min-w-48 bg-white border border-gray-200 shadow-xl rounded-lg p-1 dark:bg-neutral-800 dark:border-neutral-700" style="display: none;">
                    <div @click="statutSelectionne = 'all'; showStat = false" class="cursor-pointer flex items-center py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700">Tous les statuts</div>
                    <div @click="statutSelectionne = 'validé'; showStat = false" class="cursor-pointer flex items-center py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700">Payé (Validé)</div>
                    <div @click="statutSelectionne = 'en attente'; showStat = false" class="cursor-pointer flex items-center py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700">En attente</div>
                  </div>
                </div>
            </div>
        </div>

        <!-- Table Body -->
        <div class="overflow-x-auto bg-white dark:bg-neutral-900 rounded-b-xl">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                <thead class="bg-gray-50 dark:bg-neutral-800">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Résident</th>
                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Immeuble & Appt</th>
                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Montant</th>
                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Date</th>
                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                    @foreach($paiements as $paiement)
                    @php
                        $appt = $paiement->charge?->appartement;
                        $immeubleName = $appt?->immeuble?->nom ?? 'N/A';
                        $residentName = $paiement->user?->prenom . ' ' . $paiement->user?->nom;
                    @endphp
                    <tr x-show="matches('{{ $residentName }}', '{{ $immeubleName }}', '{{ $paiement->statut }}')" class="bg-white hover:bg-gray-50 dark:bg-neutral-900 dark:hover:bg-neutral-800 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-800 dark:text-neutral-200">{{ $residentName }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-neutral-400">{{ $immeubleName }} - Appt {{ $appt?->numero }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200 font-medium">{{ number_format($paiement->montant, 2) }} DH</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-neutral-400">{{ $paiement->date_paiement->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium {{ $paiement->statut === 'validé' ? 'bg-green-100 text-green-800 dark:bg-green-800/30 dark:text-green-500' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800/30 dark:text-yellow-500' }}">
                                {{ $paiement->statut === 'validé' ? 'Payé' : 'En attente' }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
