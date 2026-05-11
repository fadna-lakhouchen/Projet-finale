@extends('layouts.app')

@section('content')
<div x-data="{ 
    search: '', 
    immeubleSelectionne: 'all', 
    statutSelectionne: 'all',
    openImm: false,
    openStat: false,
    isEditing: false,
    residentEnCours: { prenom: '', nom: '', email: '', role: '', immeuble_id: '' },
    initAjout() {
        this.isEditing = false;
        this.residentEnCours = { prenom: '', nom: '', email: '', role: '', immeuble_id: '' };
    },
    initEdit(prenom, nom, email, role, immeuble_id) {
        this.isEditing = true;
        this.residentEnCours = { prenom: prenom, nom: nom, email: email, role: role, immeuble_id: immeuble_id };
    },
    matches(name, immeuble, role) {
        const s = this.search.toLowerCase();
        const matchesSearch = name.toLowerCase().includes(s);
        const matchesImmeuble = this.immeubleSelectionne === 'all' || immeuble === this.immeubleSelectionne;
        const matchesStatut = this.statutSelectionne === 'all' || role === this.statutSelectionne;
        return matchesSearch && matchesImmeuble && matchesStatut;
    }
}">
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Gestion des Résidents</h2>
            <p class="text-sm text-gray-600 dark:text-neutral-400">Liste des copropriétaires et locataires associés à vos immeubles.</p>
        </div>
        <button @click="initAjout()" type="button" data-hs-overlay="#hs-modal-add-resident" class="py-2.5 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-primary-600 text-white hover:bg-primary-700 disabled:opacity-50 disabled:pointer-events-none shadow-sm transition-colors">
            <i data-lucide="user-plus" class="size-4"></i>
            Ajouter un Résident
        </button>
    </div>

    <!-- Isolated Filter Container -->
    <div class="flex flex-col border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
        
        <!-- Filter Header -->
        <div class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-b border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 rounded-t-xl">
            <div class="sm:col-span-1 max-w-sm w-full relative">
                <label for="syndic-res-search" class="sr-only">Rechercher</label>
                <input x-model="search" type="text" id="syndic-res-search" class="py-2 px-3 ps-11 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" placeholder="Rechercher un nom...">
                <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                    <i data-lucide="search" class="size-4 text-gray-400"></i>
                </div>
            </div>

            <div class="sm:col-span-2 md:grow flex justify-end gap-x-2">
                <!-- Dropdown Immeubles -->
                <div class="relative inline-flex">
                  <button @click="openImm = !openImm" @click.outside="openImm = false" type="button" class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white">
                    <span x-text="immeubleSelectionne === 'all' ? 'Immeubles' : immeubleSelectionne"></span>
                    <i data-lucide="chevron-down" :class="openImm ? 'rotate-180' : ''" class="size-4 transition-transform text-gray-400"></i>
                  </button>

                  <div x-show="openImm" x-cloak class="absolute right-0 top-full z-[100] mt-1 w-56 bg-white border border-gray-200 shadow-xl rounded-lg p-1 dark:bg-neutral-800 dark:border-neutral-700">
                    <div @click="immeubleSelectionne = 'all'; openImm = false" class="cursor-pointer w-full flex items-center py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700">Tous les immeubles</div>
                    @foreach($immeubles as $immeuble)
                        <div @click="immeubleSelectionne = '{{ $immeuble->nom }}'; openImm = false" class="cursor-pointer w-full flex items-center py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700">
                            {{ $immeuble->nom }}
                        </div>
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
                    <div @click="statutSelectionne = 'Locataire'; openStat = false" class="cursor-pointer w-full flex items-center py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700">Locataire</div>
                    <div @click="statutSelectionne = 'Propriétaire'; openStat = false" class="cursor-pointer w-full flex items-center py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700">Propriétaire</div>
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
                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Rôle</th>
                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Immeuble & Appt</th>
                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Statut Charges</th>
                        <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                    @foreach($residents as $resident)
                    @php
                        $appt = $resident->appartements->first();
                        $immeubleName = $appt ? $appt->immeuble->nom : 'N/A';
                        $immeubleId = $appt ? $appt->immeuble->id : '';
                        $apptInfo = $appt ? 'Appt ' . $appt->numero : 'Non assigné';
                        $typeResident = $appt ? $appt->pivot->type_resident : 'Locataire';
                    @endphp
                    <tr x-show="matches('{{ $resident->prenom }} {{ $resident->nom }}', '{{ $immeubleName }}', '{{ $typeResident }}')" class="bg-white hover:bg-gray-50 dark:bg-neutral-900 dark:hover:bg-neutral-800 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-x-3">
                                <img class="inline-block size-[38px] rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($resident->prenom . '+' . $resident->nom) }}&background=random" alt="Avatar">
                                <div class="grow">
                                    <span class="block text-sm font-semibold text-gray-800 dark:text-neutral-200">{{ $resident->prenom }} {{ $resident->nom }}</span>
                                    <span class="block text-sm text-gray-500 dark:text-neutral-400">{{ $resident->email }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                            <span class="inline-flex items-center gap-x-1.5 py-1 px-3 rounded-full text-xs font-medium {{ $typeResident === 'Propriétaire' ? 'bg-purple-100 text-purple-800 dark:bg-purple-800/30 dark:text-purple-500' : 'bg-blue-100 text-blue-800 dark:bg-blue-800/30 dark:text-blue-500' }}">
                                {{ $typeResident }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="block text-sm text-gray-800 dark:text-neutral-200">{{ $immeubleName }}</span>
                            <span class="block text-sm text-gray-500 dark:text-neutral-400">{{ $apptInfo }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                            <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium {{ $resident->is_active ? 'bg-green-100 text-green-800 dark:bg-green-800/30 dark:text-green-500' : 'bg-red-100 text-red-800 dark:bg-red-800/30 dark:text-red-500' }}">
                                {{ $resident->is_active ? 'À jour' : 'En retard' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                            <div class="inline-flex items-center gap-x-2">
                                <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-800 transition-colors">
                                    <i data-lucide="mail" class="size-4"></i>
                                </button>
                                <button @click="initEdit('{{ addslashes($resident->prenom) }}', '{{ addslashes($resident->nom) }}', '{{ addslashes($resident->email) }}', '{{ strtolower($typeResident) }}', '{{ $immeubleId }}')" type="button" data-hs-overlay="#hs-modal-add-resident" class="py-2 px-2 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-blue-600 shadow-sm hover:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-blue-500 dark:hover:bg-neutral-800" title="Modifier">
                                    <i data-lucide="edit-2" class="size-4"></i>
                                </button>
                                <button type="button" class="py-2 px-2 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-red-600 shadow-sm hover:bg-red-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-red-500 dark:hover:bg-red-900/20" title="Supprimer">
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
<div id="hs-modal-add-resident" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none" role="dialog" tabindex="-1">
    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
        <div class="w-full flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
            <div class="flex justify-between items-center py-3 px-4 border-b dark:border-neutral-700">
                <h3 class="font-bold text-gray-800 dark:text-white" x-text="isEditing ? 'Modifier le Résident' : 'Ajouter un Résident'"></h3>
                <button type="button" class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-gray-100 text-gray-800 hover:bg-gray-200 dark:bg-neutral-700 dark:hover:bg-neutral-600 dark:text-neutral-400" data-hs-overlay="#hs-modal-add-resident">
                    <i data-lucide="x" class="size-4"></i>
                </button>
            </div>
            <div class="p-4 overflow-y-auto">
                <form>
                    <div class="grid gap-y-4">
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-2 dark:text-white">Prénom</label>
                                <input x-model="residentEnCours.prenom" type="text" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2 dark:text-white">Nom</label>
                                <input x-model="residentEnCours.nom" type="text" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2 dark:text-white">Email</label>
                            <input x-model="residentEnCours.email" type="email" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
                        </div>
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-2 dark:text-white">Rôle</label>
                                <select x-model="residentEnCours.role" class="py-3 px-4 pe-9 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
                                    <option value="">Sélectionner le rôle</option>
                                    <option value="propriétaire">Propriétaire</option>
                                    <option value="locataire">Locataire</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2 dark:text-white">Immeuble</label>
                                <select x-model="residentEnCours.immeuble_id" class="py-3 px-4 pe-9 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
                                    <option value="">Sélectionner</option>
                                    @foreach($immeubles as $immeuble)
                                        <option value="{{ $immeuble->id }}">{{ $immeuble->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
                <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700" data-hs-overlay="#hs-modal-add-resident">Annuler</button>
                <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-primary-600 text-white hover:bg-primary-700 transition-colors" x-text="isEditing ? 'Enregistrer les modifications' : 'Ajouter le Résident'"></button>
            </div>
        </div>
    </div>
</div>
@endsection
