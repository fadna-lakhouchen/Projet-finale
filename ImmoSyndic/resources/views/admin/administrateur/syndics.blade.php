@extends('layouts.app')

@section('content')
<div x-data="{ 
    search: '', 
    filterStatut: 'all', 
    filterCharge: 'all',
    showStat: false,
    showCharge: false,
    matches(name, email, statut, nbImmeubles) {
        const s = this.search.toLowerCase();
        const matchesSearch = name.toLowerCase().includes(s) || email.toLowerCase().includes(s);
        const matchesStatut = this.filterStatut === 'all' || statut === this.filterStatut;
        let matchesCharge = true;
        if (this.filterCharge === 'Sans immeuble') matchesCharge = nbImmeubles === 0;
        else if (this.filterCharge === '1-3 Immeubles') matchesCharge = nbImmeubles >= 1 && nbImmeubles <= 3;
        else if (this.filterCharge === '4+ Immeubles') matchesCharge = nbImmeubles >= 4;
        return matchesSearch && matchesStatut && matchesCharge;
    }
}">
    <!-- Page Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Gestion des Syndics</h2>
            <p class="text-sm text-gray-600 dark:text-neutral-400">Gérez les comptes syndics et leurs immeubles assignés.</p>
        </div>
        <button type="button" data-hs-overlay="#hs-modal-add-syndic" class="py-2.5 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-primary-600 text-white hover:bg-primary-700 disabled:opacity-50 disabled:pointer-events-none transition-colors">
            <i data-lucide="plus" class="size-4"></i>
            Ajouter un syndic
        </button>
    </div>

    <!-- Table Section Container -->
    <div class="flex flex-col border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
        
        <!-- Header / Filters (Isolated) -->
        <div class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-b border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 rounded-t-xl">
            <div class="sm:col-span-1 max-w-sm w-full relative">
                <label for="syndic-search" class="sr-only">Rechercher</label>
                <input x-model="search" type="text" id="syndic-search" class="py-2 px-3 ps-11 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" placeholder="Rechercher syndic...">
                <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                    <i data-lucide="search" class="size-4 text-gray-400"></i>
                </div>
            </div>

            <div class="sm:col-span-2 md:grow flex justify-end gap-x-2">
                <!-- Filter Statut -->
                <div class="relative inline-flex">
                  <button @click="showStat = !showStat; showCharge = false" @click.outside="showStat = false" type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white">
                    <span x-text="filterStatut === 'all' ? 'Statut' : filterStatut"></span>
                    <i data-lucide="chevron-down" :class="showStat ? 'rotate-180' : ''" class="size-4 transition-transform"></i>
                  </button>
                  <div x-show="showStat" class="absolute right-0 top-full z-[100] mt-2 min-w-48 bg-white border border-gray-200 shadow-xl rounded-lg p-1 dark:bg-neutral-800 dark:border-neutral-700" style="display: none;">
                    <div @click="filterStatut = 'all'; showStat = false" class="cursor-pointer flex items-center py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700">Tous les statuts</div>
                    <div @click="filterStatut = 'Actif'; showStat = false" class="cursor-pointer flex items-center py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700">Actif</div>
                    <div @click="filterStatut = 'Inactif'; showStat = false" class="cursor-pointer flex items-center py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700">Inactif</div>
                  </div>
                </div>

                <!-- Filter Charge -->
                <div class="relative inline-flex">
                  <button @click="showCharge = !showCharge; showStat = false" @click.outside="showCharge = false" type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white">
                    <span x-text="filterCharge === 'all' ? 'Charge' : filterCharge"></span>
                    <i data-lucide="chevron-down" :class="showCharge ? 'rotate-180' : ''" class="size-4 transition-transform"></i>
                  </button>
                  <div x-show="showCharge" class="absolute right-0 top-full z-[100] mt-2 min-w-48 bg-white border border-gray-200 shadow-xl rounded-lg p-1 dark:bg-neutral-800 dark:border-neutral-700" style="display: none;">
                    <div @click="filterCharge = 'all'; showCharge = false" class="cursor-pointer flex items-center py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700">Toute charge</div>
                    <div @click="filterCharge = 'Sans immeuble'; showCharge = false" class="cursor-pointer flex items-center py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700">Sans immeuble</div>
                    <div @click="filterCharge = '1-3 Immeubles'; showCharge = false" class="cursor-pointer flex items-center py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700">1-3 Immeubles</div>
                    <div @click="filterCharge = '4+ Immeubles'; showCharge = false" class="cursor-pointer flex items-center py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700">4+ Immeubles</div>
                  </div>
                </div>
            </div>
        </div>

        <!-- Table Body -->
        <div class="overflow-x-auto bg-white dark:bg-neutral-900 rounded-b-xl">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                <thead class="bg-gray-50 dark:bg-neutral-800">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Nom complet</th>
                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Immeuble(s) assigné(s)</th>
                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Statut</th>
                        <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                    @foreach($syndics as $syndic)
                    @php
                        $nbImmeubles = $syndic->immeubles->count();
                        $statut = $syndic->is_active ? 'Actif' : 'Inactif';
                    @endphp
                    <tr x-show="matches('{{ $syndic->prenom }} {{ $syndic->nom }}', '{{ $syndic->email }}', '{{ $statut }}', {{ $nbImmeubles }})" class="bg-white hover:bg-gray-50 dark:bg-neutral-900 dark:hover:bg-neutral-800 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-x-3">
                                <div class="size-[38px] rounded-full bg-primary-100 flex items-center justify-center text-primary-600 font-bold dark:bg-primary-900/40 dark:text-primary-400">
                                    {{ substr($syndic->prenom, 0, 1) }}{{ substr($syndic->nom, 0, 1) }}
                                </div>
                                <div class="grow">
                                    <span class="block text-sm font-semibold text-gray-800 dark:text-neutral-200">{{ $syndic->prenom }} {{ $syndic->nom }}</span>
                                    <span class="block text-sm text-gray-500 dark:text-neutral-400">{{ $syndic->email }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-wrap gap-1 max-w-[200px]">
                                @forelse($syndic->immeubles as $immeuble)
                                    <span class="inline-flex items-center gap-x-1.5 py-1 px-2 text-xs font-medium bg-gray-100 text-gray-800 rounded dark:bg-neutral-700 dark:text-neutral-300">{{ $immeuble->nom }}</span>
                                @empty
                                    <span class="text-sm text-gray-500 italic">Aucun immeuble</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                            <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium {{ $syndic->is_active ? 'bg-green-100 text-green-800 dark:bg-green-800/30 dark:text-green-500' : 'bg-red-100 text-red-800 dark:bg-red-800/30 dark:text-red-500' }}">
                                {{ $statut }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                            <div class="inline-flex items-center gap-x-2">
                                <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 dark:bg-transparent dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-800">
                                    <i data-lucide="edit-2" class="size-4"></i>
                                </button>
                                <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-red-100 text-red-800 hover:bg-red-200 dark:bg-red-800/30 dark:text-red-500 dark:hover:bg-red-800/20">
                                    <i data-lucide="trash-2" class="size-4"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="hs-modal-add-syndic" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none" role="dialog" tabindex="-1">
    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
        <div class="w-full flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
            <div class="flex justify-between items-center py-3 px-4 border-b dark:border-neutral-700">
                <h3 class="font-bold text-gray-800 dark:text-white">Ajouter un nouveau compte syndic</h3>
                <button type="button" class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-gray-100 text-gray-800 hover:bg-gray-200 dark:bg-neutral-700 dark:hover:bg-neutral-600 dark:text-neutral-400" data-hs-overlay="#hs-modal-add-syndic">
                    <i data-lucide="x" class="size-4"></i>
                </button>
            </div>
            <div class="p-4 overflow-y-auto">
                <form>
                    <div class="grid gap-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-2 dark:text-white">Prénom</label>
                                <input type="text" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2 dark:text-white">Nom</label>
                                <input type="text" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2 dark:text-white">Adresse Email</label>
                            <input type="email" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" placeholder="youssef.khadir@email.com">
                        </div>
                    </div>
                </form>
            </div>
            <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
                <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700" data-hs-overlay="#hs-modal-add-syndic">Annuler</button>
                <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-primary-600 text-white hover:bg-primary-700 transition-colors">Créer le compte</button>
            </div>
        </div>
    </div>
</div>
@endsection
