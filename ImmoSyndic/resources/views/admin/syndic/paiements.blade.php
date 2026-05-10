@extends('layouts.app')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Suivi des Paiements</h2>
        <p class="text-sm text-gray-600 dark:text-neutral-400">Vérifiez les versements des charges mensuelles des résidents.</p>
    </div>
    <div class="flex gap-2">
        <button type="button" class="py-2.5 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800">
            <i data-lucide="external-link" class="size-4"></i> Export CSV
        </button>
    </div>
</div>

<!-- Table Section --<!-- Table Section -->
<div x-data="{
    search: '',
    moisSelectionne: 'all',
    immeubleSelectionne: 'all',
    statutSelectionne: 'all',
    openMois: false,
    openImm: false,
    openStat: false,
    matches(name, mois, immeuble, statut) {
        const s = this.search.toLowerCase();
        const matchSearch = name.toLowerCase().includes(s) || immeuble.toLowerCase().includes(s);
        const matchMois = this.moisSelectionne === 'all' || mois === this.moisSelectionne;
        const matchImmeuble = this.immeubleSelectionne === 'all' || immeuble === this.immeubleSelectionne;
        const matchStatut = this.statutSelectionne === 'all' || statut === this.statutSelectionne;
        return matchSearch && matchMois && matchImmeuble && matchStatut;
    }
}" class="flex flex-col">
    <div class="-m-1.5 overflow-x-auto">
        <div class="p-1.5 min-w-full inline-block align-middle">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-neutral-800 dark:border-neutral-700">

                <!-- Header / Filters -->
                <div class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-b border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 rounded-t-xl">
                    <div class="sm:col-span-1 max-w-sm w-full relative">
                        <label for="syndic-paiement-search" class="sr-only">Rechercher</label>
                        <input x-model="search" type="text" id="syndic-paiement-search" class="py-2 px-3 ps-11 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" placeholder="Rechercher un résident...">
                        <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                            <i data-lucide="search" class="size-4 text-gray-400"></i>
                        </div>
                    </div>

                    <div class="sm:col-span-2 md:grow flex justify-end gap-x-2">
                        <!-- Dropdown Mois -->
                        <div class="relative inline-flex">
                          <button @click="openMois = !openMois" @click.outside="openMois = false" type="button" class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white">
                            <span x-text="moisSelectionne === 'all' ? 'Mois' : moisSelectionne"></span>
                            <i data-lucide="chevron-down" :class="openMois ? 'rotate-180' : ''" class="size-4 transition-transform text-gray-400"></i>
                          </button>
                          <div x-show="openMois" x-cloak class="absolute right-0 top-full z-[100] mt-1 w-48 bg-white border border-gray-200 shadow-xl rounded-lg p-1 dark:bg-neutral-800 dark:border-neutral-700">
                            <div @click="moisSelectionne = 'all'; openMois = false" class="cursor-pointer w-full flex items-center py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700">Tous les mois</div>
                            @foreach($moisDisponibles as $mois)
                                <div @click="moisSelectionne = '{{ $mois }}'; openMois = false" class="cursor-pointer w-full flex items-center py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700">{{ $mois }}</div>
                            @endforeach
                          </div>
                        </div>

                        <!-- Dropdown Immeubles -->
                        <div class="relative inline-flex">
                          <button @click="openImm = !openImm" @click.outside="openImm = false" type="button" class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white">
                            <span x-text="immeubleSelectionne === 'all' ? 'Immeubles' : immeubleSelectionne"></span>
                            <i data-lucide="chevron-down" :class="openImm ? 'rotate-180' : ''" class="size-4 transition-transform text-gray-400"></i>
                          </button>
                          <div x-show="openImm" x-cloak class="absolute right-0 top-full z-[100] mt-1 w-56 bg-white border border-gray-200 shadow-xl rounded-lg p-1 dark:bg-neutral-800 dark:border-neutral-700">
                            <div @click="immeubleSelectionne = 'all'; openImm = false" class="cursor-pointer w-full flex items-center py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700">Tous les immeubles</div>
                            @foreach($immeubles as $immeuble)
                                <div @click="immeubleSelectionne = '{{ $immeuble->nom }}'; openImm = false" class="cursor-pointer w-full flex items-center py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700">{{ $immeuble->nom }}</div>
                            @endforeach
                          </div>
                        </div>

                        <!-- Dropdown Statut -->
                        <div class="relative inline-flex">
                          <button @click="openStat = !openStat" @click.outside="openStat = false" type="button" class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white">
                            <span x-text="statutSelectionne === 'all' ? 'Statuts' : statutSelectionne"></span>
                            <i data-lucide="chevron-down" :class="openStat ? 'rotate-180' : ''" class="size-4 transition-transform text-gray-400"></i>
                          </button>
                          <div x-show="openStat" x-cloak class="absolute right-0 top-full z-[100] mt-1 w-48 bg-white border border-gray-200 shadow-xl rounded-lg p-1 dark:bg-neutral-800 dark:border-neutral-700">
                            <div @click="statutSelectionne = 'all'; openStat = false" class="cursor-pointer w-full flex items-center py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700">Tous les statuts</div>
                            <div @click="statutSelectionne = 'Payé'; openStat = false" class="cursor-pointer w-full flex items-center py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700">Payé</div>
                            <div @click="statutSelectionne = 'En retard'; openStat = false" class="cursor-pointer w-full flex items-center py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700">En retard</div>
                          </div>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                    <thead class="bg-gray-50 dark:bg-neutral-800">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Résident</th>
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Mois</th>
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Montant</th>
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Statut</th>
                            <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                        @foreach($paiements as $paiement)
                        @php
                            $dateFr = \Carbon\Carbon::parse($paiement->date_paiement)->translatedFormat('F Y');
                            $dateFr = ucfirst($dateFr);
                            $statut = $paiement->statut;
                            $appt = $paiement->user->appartements->first();
                            $immeubleName = $appt ? $appt->immeuble->nom : 'N/A';
                            $apptNumero = $appt ? $appt->numero : 'N/A';
                            $fullName = $paiement->user->prenom . ' ' . $paiement->user->nom;
                        @endphp
                        <tr x-show="matches('{{ addslashes($fullName) }}', '{{ $dateFr }}', '{{ addslashes($immeubleName) }}', '{{ $statut }}')" class="bg-white hover:bg-gray-50 dark:bg-neutral-900 dark:hover:bg-neutral-800 transition-colors {{ $statut == 'En retard' ? 'bg-red-50/20 dark:bg-red-900/10' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="grow">
                                    <span class="block text-sm font-semibold text-gray-800 dark:text-neutral-200">{{ $fullName }}</span>
                                    <span class="block text-sm text-gray-500 dark:text-neutral-400">{{ $immeubleName }} - Appt {{ $apptNumero }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap"><span class="block text-sm text-gray-800 dark:text-neutral-200">{{ $dateFr }}</span></td>
                            <td class="px-6 py-4 whitespace-nowrap"><span class="block text-sm font-medium text-gray-800 dark:text-neutral-200">{{ number_format($paiement->montant, 2) }} MAD</span></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                                @if($statut == 'Payé')
                                    <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-800/30 dark:text-green-500">Payé</span>
                                @else
                                    <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-800/30 dark:text-red-500">En retard</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                <div class="inline-flex items-center gap-x-2">
                                    @if($statut == 'Payé')
                                        <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-800">
                                            <i data-lucide="download" class="size-4"></i> PDF
                                        </button>
                                    @else
                                        <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-red-600 text-white hover:bg-red-700 focus:outline-none focus:bg-red-700 disabled:opacity-50 disabled:pointer-events-none">
                                            <i data-lucide="bell-ring" class="size-4"></i> Rappel
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>>
</div>
@endsection
