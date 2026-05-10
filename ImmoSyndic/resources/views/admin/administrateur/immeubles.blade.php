@extends('layouts.app')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Supervision des Immeubles</h2>
        <p class="text-sm text-gray-600 dark:text-neutral-400">Vue d'ensemble de tous les bâtiments enregistrés sur la plateforme.</p>
    </div>
    <button type="button" data-hs-overlay="#hs-modal-add-immeuble" class="py-2.5 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-primary-600 text-white hover:bg-primary-700 disabled:opacity-50 disabled:pointer-events-none transition-colors">
        <i data-lucide="plus" class="size-4"></i>
        Ajouter un immeuble
    </button>
</div>

<!-- Table Section -->
<div x-data="{ 
    search: '', 
    filterSyndic: 'all', 
    filterStatut: 'all',
    matches(name, address, syndic, statut) {
        const matchesSearch = name.toLowerCase().includes(this.search.toLowerCase()) || address.toLowerCase().includes(this.search.toLowerCase());
        const matchesSyndic = this.filterSyndic === 'all' || syndic === this.filterSyndic;
        const matchesStatut = this.filterStatut === 'all' || statut === this.filterStatut;
        return matchesSearch && matchesSyndic && matchesStatut;
    }
}" class="flex flex-col">
    <div class="-m-1.5 overflow-x-auto">
        <div class="p-1.5 min-w-full inline-block align-middle">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-neutral-800 dark:border-neutral-700">

                <!-- Header / Filters -->
                <div class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-b border-gray-200 dark:border-neutral-700">
                    <div class="sm:col-span-1 max-w-sm w-full relative">
                        <label for="search" class="sr-only">Rechercher</label>
                        <input x-model="search" type="text" id="search" name="search" class="py-2 px-3 ps-11 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500" placeholder="Nom ou adresse...">
                        <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                            <i data-lucide="search" class="size-4 text-gray-400 dark:text-neutral-500"></i>
                        </div>
                    </div>

                    <div class="sm:col-span-2 md:grow flex justify-end gap-x-2">
                        <!-- Filter Syndics -->
                        <div x-data="{ open: false }" class="relative inline-flex">
                          <button @click="open = !open" @click.outside="open = false" type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
                            <span x-text="filterSyndic === 'all' ? 'Syndics' : filterSyndic"></span>
                            <svg :class="open ? 'rotate-180' : ''" class="size-4 text-gray-600 dark:text-neutral-600 transition-transform" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                              <path d="m6 9 6 6 6-6"></path>
                            </svg>
                          </button>
                          <div x-show="open" x-transition class="absolute right-0 top-full z-10 mt-2 min-w-48 bg-white shadow-md rounded-lg p-2 dark:bg-neutral-800 dark:border dark:border-neutral-700" style="display: none;">
                            <a @click.prevent="filterSyndic = 'all'; open = false" class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700" href="#">Tous les syndics</a>
                            @foreach($syndics as $s)
                                <a @click.prevent="filterSyndic = '{{ $s->prenom }} {{ $s->nom }}'; open = false" class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700" href="#">{{ $s->prenom }} {{ $s->nom }}</a>
                            @endforeach
                          </div>
                        </div>

                        <!-- Filter Statut Financier -->
                        <div x-data="{ open: false }" class="relative inline-flex">
                          <button @click="open = !open" @click.outside="open = false" type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
                            <span x-text="filterStatut === 'all' ? 'Statut' : filterStatut"></span>
                            <svg :class="open ? 'rotate-180' : ''" class="size-4 text-gray-600 dark:text-neutral-600 transition-transform" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                              <path d="m6 9 6 6 6-6"></path>
                            </svg>
                          </button>
                          <div x-show="open" x-transition class="absolute right-0 top-full z-10 mt-2 min-w-48 bg-white shadow-md rounded-lg p-2 dark:bg-neutral-800 dark:border dark:border-neutral-700" style="display: none;">
                            <a @click.prevent="filterStatut = 'all'; open = false" class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700" href="#">Tous statuts</a>
                            <a @click.prevent="filterStatut = 'Sain'; open = false" class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700" href="#">Sain</a>
                            <a @click.prevent="filterStatut = 'En retard'; open = false" class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700" href="#">En retard</a>
                          </div>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                    <thead class="bg-gray-50 dark:bg-neutral-800">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Immeuble</th>
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Syndic Responsable</th>
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Résidents</th>
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Statut Financier</th>
                            <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                        @foreach($immeubles as $immeuble)
                        <tr x-show="matches('{{ $immeuble->nom }}', '{{ $immeuble->adresse }}', '{{ $immeuble->syndic ? $immeuble->syndic->prenom . ' ' . $immeuble->syndic->nom : 'N/A' }}', 'Sain')" class="bg-white hover:bg-gray-50 dark:bg-neutral-900 dark:hover:bg-neutral-800 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-x-3">
                                    <div class="size-[38px] rounded-lg bg-primary-100 flex items-center justify-center text-primary-600 dark:bg-primary-900/40 dark:text-primary-400">
                                        <i data-lucide="building-2" class="size-5"></i>
                                    </div>
                                    <div class="grow">
                                        <span class="block text-sm font-semibold text-gray-800 dark:text-neutral-200">{{ $immeuble->nom }}</span>
                                        <span class="block text-xs text-gray-500 dark:text-neutral-400">{{ $immeuble->adresse }}, {{ $immeuble->ville }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="block text-sm text-gray-800 dark:text-neutral-200">{{ $immeuble->syndic ? $immeuble->syndic->prenom . ' ' . $immeuble->syndic->nom : 'Non assigné' }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">{{ $immeuble->nombre_appartements }} Appts</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-800/30 dark:text-green-500">Sain</span>
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
</div>

<!-- Modal Ajouter un immeuble -->
<div id="hs-modal-add-immeuble" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none" role="dialog" tabindex="-1">
    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
        <div class="w-full flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
            <div class="flex justify-between items-center py-3 px-4 border-b dark:border-neutral-700">
                <h3 class="font-bold text-gray-800 dark:text-white">Enregistrer un nouvel immeuble</h3>
                <button type="button" class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-gray-100 text-gray-800 hover:bg-gray-200 dark:bg-neutral-700 dark:hover:bg-neutral-600 dark:text-neutral-400" data-hs-overlay="#hs-modal-add-immeuble">
                    <i data-lucide="x" class="size-4"></i>
                </button>
            </div>
            <div class="p-4 overflow-y-auto">
                <form>
                    <div class="grid gap-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-2 dark:text-white">Nom de l'immeuble</label>
                            <input type="text" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" placeholder="Ex: Résidence Al Amal">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2 dark:text-white">Adresse complète</label>
                            <input type="text" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" placeholder="Ex: Angle Avenue Mohammed V, Rabat">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2 dark:text-white">Assigner un Syndic</label>
                            <select class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
                                <option selected disabled>Choisir un syndic responsable</option>
                                @foreach($syndics as $s)
                                    <option value="{{ $s->id }}">{{ $s->prenom }} {{ $s->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-2 dark:text-white">Nombre d'étages</label>
                                <input type="number" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" placeholder="5">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2 dark:text-white">Nombre d'appartements</label>
                                <input type="number" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" placeholder="20">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
                <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700" data-hs-overlay="#hs-modal-add-immeuble">Annuler</button>
                <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-primary-600 text-white hover:bg-primary-700">Enregistrer l'immeuble</button>
            </div>
        </div>
    </div>
</div>
@endsection
