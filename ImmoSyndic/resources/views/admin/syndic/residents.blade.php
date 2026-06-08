@extends('layouts.app')

@section('content')
<div x-data="syndicResidents({ items: [ @foreach($residents as $resident) { id: '{{ $resident->id }}', name: '{{ addslashes($resident->prenom) }} {{ addslashes($resident->nom) }}', immeuble: '{{ addslashes($resident->appartements->first() ? $resident->appartements->first()->immeuble->nom : 'N/A') }}' }, @endforeach ] })">
    <!-- Header -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight dark:text-white">Gestion des Résidents</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">Liste des copropriétaires et locataires associés à vos immeubles sous gestion.</p>
        </div>
        <button @click="initAjout()" type="button" data-hs-overlay="#hs-modal-add-resident" class="py-2.5 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-xl border border-transparent bg-gradient-to-r from-primary-600 to-purple-600 hover:from-primary-700 hover:to-purple-700 text-white shadow-md shadow-primary-500/10 hover:shadow-lg hover:shadow-primary-500/20 transition-all duration-300 transform hover:-translate-y-0.5">
            <i data-lucide="user-plus" class="size-4"></i>
            Ajouter un Résident
        </button>
    </div>

    <!-- Table & Filters Container -->
    <div class="flex flex-col bg-white/80 dark:bg-[#0D121F]/90 border border-gray-200/60 dark:border-slate-800/60 rounded-2xl shadow-premium backdrop-blur-md overflow-hidden">
        <!-- Filters -->
        <div class="px-6 py-5 grid gap-4 md:flex md:justify-between md:items-center border-b border-gray-200/60 dark:border-slate-800/60 bg-white/40 dark:bg-[#0D121F]/40">
            <div class="sm:col-span-1 max-w-sm w-full relative">
                <input x-model="search" type="text" class="py-2.5 px-4 ps-11 block w-full border-gray-200/80 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 bg-white/50 dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-neutral-300 dark:placeholder-neutral-500 transition-all duration-200" placeholder="Rechercher un nom...">
                <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                    <i data-lucide="search" class="size-4 text-gray-400 dark:text-neutral-500"></i>
                </div>
            </div>

            <div class="sm:col-span-2 md:grow flex justify-end gap-x-3 relative">
                <button @click="openImm = !openImm" @click.outside="openImm = false" type="button" class="py-2.5 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-xl border border-gray-200/80 bg-white/50 hover:bg-gray-50 dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-white dark:hover:bg-slate-900/50 shadow-sm transition-all duration-200">
                    <span x-text="immeubleSelectionne === 'all' ? 'Filtrer par Immeuble' : immeubleSelectionne" class="truncate max-w-[150px]"></span>
                    <i data-lucide="chevron-down" class="size-4 text-gray-400 transition-transform duration-200" :class="{'rotate-180': openImm}"></i>
                </button>
                <div x-show="openImm" x-cloak class="absolute right-0 top-full z-[100] mt-2 w-56 bg-white/95 dark:bg-[#0D121F]/95 border border-gray-200/60 dark:border-slate-800/60 shadow-xl rounded-xl p-1.5 backdrop-blur-md" style="display: none;">
                    <div @click="immeubleSelectionne = 'all'; openImm = false" class="cursor-pointer py-2 px-3 rounded-lg text-sm text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800/50 transition-colors">Tous les immeubles</div>
                    @foreach($immeubles as $immeuble)
                        <div @click="immeubleSelectionne = '{{ $immeuble->nom }}'; openImm = false" class="cursor-pointer py-2 px-3 rounded-lg text-sm text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800/50 transition-colors">{{ $immeuble->nom }}</div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200/60 dark:divide-slate-800/60">
                <thead class="bg-gray-50/50 dark:bg-[#090D16]/40">
                    <tr>
                        <th class="px-6 py-4 text-start text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-slate-400">Nom complet</th>
                        <th class="px-6 py-4 text-start text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-slate-400">Immeuble & Appt</th>
                        <th class="px-6 py-4 text-start text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-slate-400">Statut Charges</th>
                        <th class="px-6 py-4 text-end text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-slate-400">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200/60 dark:divide-slate-800/60">
                    @foreach($residents as $resident)
                    @php
                        $appt = $resident->appartements->first();
                        $immeubleName = $appt ? $appt->immeuble->nom : 'N/A';
                        $immeubleId = $appt ? $appt->immeuble->id : '';
                        $apptInfo = $appt ? 'Appt ' . $appt->numero : 'Non assigné';
                    @endphp
                    <tr x-show="isRowVisible('{{ $resident->id }}', '{{ $resident->prenom }} {{ $resident->nom }}', '{{ $immeubleName }}')" class="hover:bg-gray-50/50 dark:hover:bg-slate-900/30 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-x-3.5">
                                <img class="size-10 rounded-xl shadow-sm border border-gray-200/30" src="https://ui-avatars.com/api/?name={{ urlencode($resident->prenom . '+' . $resident->nom) }}&background=6366F1&color=fff&bold=true">
                                <div class="grow">
                                    <span class="block text-sm font-semibold text-gray-800 dark:text-slate-200">{{ $resident->prenom }} {{ $resident->nom }}</span>
                                    <span class="block text-xs text-gray-500 dark:text-slate-400">{{ $resident->email }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="block text-sm font-medium text-gray-800 dark:text-slate-200">{{ $immeubleName }}</span>
                            <span class="block text-xs text-gray-500 dark:text-slate-400">{{ $apptInfo }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($resident->is_active)
                                <span class="inline-flex items-center gap-x-1.5 py-1 px-3 rounded-full text-xs font-medium bg-emerald-50/80 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30">
                                    <span class="size-1.5 rounded-full bg-emerald-600 dark:bg-emerald-400"></span>
                                    À jour
                                </span>
                            @else
                                <span class="inline-flex items-center gap-x-1.5 py-1 px-3 rounded-full text-xs font-medium bg-amber-50/80 text-amber-600 dark:bg-amber-950/40 dark:text-amber-400 border border-amber-100 dark:border-amber-900/30 animate-pulse">
                                    <span class="size-1.5 rounded-full bg-amber-600 dark:bg-amber-400"></span>
                                    En attente d'activation
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                            <div class="inline-flex items-center gap-x-2">
                                @if(!$resident->is_active)
                                    <form action="{{ route('syndic.residents.activate', $resident->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit" class="py-1.5 px-2.5 inline-flex items-center gap-x-1 text-xs font-semibold rounded-lg border border-transparent bg-emerald-600 text-white hover:bg-emerald-700 shadow-sm shadow-emerald-500/10 transition-all duration-150" title="Approuver l'inscription">
                                            <i data-lucide="check" class="size-3.5"></i>
                                            <span>Accepter</span>
                                        </button>
                                    </form>
                                @endif
                                <button @click="initEdit('{{ $resident->id }}', '{{ addslashes($resident->prenom) }}', '{{ addslashes($resident->nom) }}', '{{ addslashes($resident->email) }}', '{{ $resident->telephone }}', '{{ $resident->cin ?? '' }}', '{{ $immeubleId }}', '{{ $appt ? $appt->numero : '' }}', '{{ $appt ? $appt->pivot->date_entree : '' }}', '{{ addslashes($resident->notes ?? '') }}', '{{ $appt ? $appt->override_mois_retard : '' }}')" type="button" data-hs-overlay="#hs-modal-add-resident" class="py-1.5 px-1.5 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-950/30 rounded-lg border border-gray-200/80 hover:border-blue-300 dark:border-slate-800/80 dark:hover:border-blue-900/30 transition-all duration-200" title="Modifier">
                                    <i data-lucide="edit-2" class="size-4"></i>
                                </button>
                                <form action="{{ route('syndic.residents.destroy', $resident->id) }}" method="POST" onsubmit="return confirm('Supprimer ce résident ?');" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="py-1.5 px-1.5 text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/30 rounded-lg border border-gray-200/80 hover:border-rose-300 dark:border-slate-800/80 dark:hover:border-rose-900/30 transition-all duration-200" title="Supprimer">
                                        <i data-lucide="trash-2" class="size-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination controls -->
        <div class="px-6 py-4 flex items-center justify-between border-t border-gray-200/60 dark:border-slate-800/60 bg-white/40 dark:bg-[#0D121F]/40">
            <div class="flex-1 flex justify-between sm:hidden">
                <button @click="if (currentPage > 1) currentPage--" :disabled="currentPage === 1" class="relative inline-flex items-center px-4 py-2 text-sm font-medium rounded-xl border border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-800/50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200">
                    Précédent
                </button>
                <button @click="if (currentPage < Math.ceil(filteredItems.length / perPage)) currentPage++" :disabled="currentPage === Math.ceil(filteredItems.length / perPage) || filteredItems.length === 0" class="relative ml-3 inline-flex items-center px-4 py-2 text-sm font-medium rounded-xl border border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-800/50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200">
                    Suivant
                </button>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-slate-400">
                        Affichage de <span class="font-semibold text-gray-800 dark:text-white" x-text="filteredItems.length === 0 ? 0 : (currentPage - 1) * perPage + 1"></span> à <span class="font-semibold text-gray-800 dark:text-white" x-text="Math.min(currentPage * perPage, filteredItems.length)"></span> sur <span class="font-semibold text-gray-800 dark:text-white" x-text="filteredItems.length"></span> résultats
                    </p>
                </div>
                <div class="inline-flex gap-x-2">
                    <button @click="if (currentPage > 1) currentPage--" :disabled="currentPage === 1" class="py-2 px-3 inline-flex items-center gap-x-1.5 text-sm font-medium rounded-xl border border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-gray-800 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-800/50 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200">
                        <i data-lucide="chevron-left" class="size-4"></i>
                        Précédent
                    </button>
                    
                    <div class="flex items-center gap-x-1">
                        <template x-for="page in Math.ceil(filteredItems.length / perPage)" :key="page">
                            <button @click="currentPage = page" 
                                    :class="currentPage === page ? 'bg-primary-600 text-white border-transparent' : 'bg-white dark:bg-slate-900 border-gray-200 dark:border-slate-800 text-gray-800 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-800/50'"
                                    class="size-9 inline-flex justify-center items-center text-sm font-semibold rounded-xl border transition-all duration-200" 
                                    x-text="page">
                            </button>
                        </template>
                    </div>

                    <button @click="if (currentPage < Math.ceil(filteredItems.length / perPage)) currentPage++" :disabled="currentPage === Math.ceil(filteredItems.length / perPage) || filteredItems.length === 0" class="py-2 px-3 inline-flex items-center gap-x-1.5 text-sm font-medium rounded-xl border border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-gray-800 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-800/50 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200">
                        Suivant
                        <i data-lucide="chevron-right" class="size-4"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal (INSIDE x-data scope) -->
    <div id="hs-modal-add-resident" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none bg-slate-950/40 backdrop-blur-sm" role="dialog" tabindex="-1">
        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
            <div class="w-full flex flex-col bg-white border border-gray-200/60 dark:border-slate-800/60 shadow-premium rounded-2xl pointer-events-auto dark:bg-[#0D121F]">
                <div class="flex justify-between items-center py-4 px-5 border-b border-gray-200/60 dark:border-slate-800/60">
                    <h3 class="font-bold text-gray-800 dark:text-white text-lg" x-text="isEditing ? 'Modifier le Résident' : 'Ajouter un Résident'"></h3>
                    <button type="button" class="size-8 inline-flex justify-center items-center rounded-xl bg-gray-100 text-gray-800 hover:bg-gray-200 dark:bg-slate-800 dark:text-neutral-400 dark:hover:bg-slate-700 transition-colors" data-hs-overlay="#hs-modal-add-resident">
                        <i data-lucide="x" class="size-4"></i>
                    </button>
                </div>
                <div class="p-6 overflow-y-auto">
                    <form :action="isEditing ? `/syndic/residents/${residentEnCours.id}` : '{{ route('syndic.residents.store') }}'" method="POST">
                        @csrf
                        <template x-if="isEditing">
                            <input type="hidden" name="_method" value="PUT">
                        </template>
                        <input type="hidden" name="id" x-model="residentEnCours.id">
                        
                        <div class="grid gap-y-4">
                            <div class="grid sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold mb-2 dark:text-neutral-200">Prénom</label>
                                    <input name="prenom" x-model="residentEnCours.prenom" type="text" class="py-2.5 px-4 block w-full border-gray-200 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 bg-white/50 dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-neutral-300 transition-all duration-200" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold mb-2 dark:text-neutral-200">Nom</label>
                                    <input name="nom" x-model="residentEnCours.nom" type="text" class="py-2.5 px-4 block w-full border-gray-200 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 bg-white/50 dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-neutral-300 transition-all duration-200" required>
                                </div>
                            </div>
                            <div class="grid sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold mb-2 dark:text-neutral-200">Email</label>
                                    <input name="email" x-model="residentEnCours.email" type="email" class="py-2.5 px-4 block w-full border-gray-200 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 bg-white/50 dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-neutral-300 transition-all duration-200" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold mb-2 dark:text-neutral-200">Téléphone</label>
                                    <input name="telephone" x-model="residentEnCours.telephone" type="text" class="py-2.5 px-4 block w-full border-gray-200 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 bg-white/50 dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-neutral-300 transition-all duration-200" placeholder="0600000000">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-2 dark:text-neutral-200">CIN</label>
                                <input name="cin" x-model="residentEnCours.cin" type="text" class="py-2.5 px-4 block w-full border-gray-200 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 bg-white/50 dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-neutral-300 transition-all duration-200" placeholder="AB123456">
                            </div>
                            <div class="grid sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold mb-2 dark:text-neutral-200">Immeuble</label>
                                    <div class="relative" x-data="{ open: false }">
                                        <button @click="open = !open" @click.outside="open = false" type="button" class="py-2.5 px-4 flex justify-between items-center w-full border-gray-200 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 bg-white/50 dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-neutral-300 transition-all duration-200 text-left">
                                            <span x-text="residentEnCours.immeuble_id ? ({ @foreach($immeubles as $imm) '{{ $imm->id }}': '{{ addslashes($imm->nom) }}', @endforeach }[residentEnCours.immeuble_id] || 'Sélectionner l\'immeuble') : 'Sélectionner l\'immeuble'"></span>
                                            <i data-lucide="chevron-down" class="size-4 text-gray-400 transition-transform duration-200" :class="{'rotate-180': open}"></i>
                                        </button>
                                        <input type="hidden" name="immeuble_id" :value="residentEnCours.immeuble_id">
                                        <div x-show="open" x-cloak class="absolute left-0 top-full z-[100] mt-2 w-full max-h-60 overflow-y-auto bg-white dark:bg-[#0D121F] border border-gray-200/60 dark:border-slate-800/60 shadow-xl rounded-xl p-1.5 backdrop-blur-md" style="display: none;">
                                            @foreach($immeubles as $immeuble)
                                                <div @click="residentEnCours.immeuble_id = '{{ $immeuble->id }}'; open = false" class="cursor-pointer py-2 px-3 rounded-lg text-sm text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800/50 transition-colors">{{ $immeuble->nom }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold mb-2 dark:text-neutral-200">Numéro d'appartement</label>
                                    <input name="numero_appartement" x-model="residentEnCours.numero_appartement" type="text" class="py-2.5 px-4 block w-full border-gray-200 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 bg-white/50 dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-neutral-300 transition-all duration-200" placeholder="Ex: 5, 12B..." required>
                                </div>
                            </div>
                            <div class="grid sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold mb-2 dark:text-neutral-200">Date d'entrée</label>
                                    <input name="date_entree" x-model="residentEnCours.date_entree" type="date" class="py-2.5 px-4 block w-full border-gray-200 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 bg-white/50 dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-neutral-300 transition-all duration-200" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold mb-2 dark:text-neutral-200">Surcharge mois de retard</label>
                                    <input name="override_mois_retard" x-model="residentEnCours.override_mois_retard" type="number" min="0" class="py-2.5 px-4 block w-full border-gray-200 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 bg-white/50 dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-neutral-300 transition-all duration-200" placeholder="Automatique (laisser vide)">
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-end items-center gap-x-3 mt-6 border-t border-gray-100 dark:border-slate-800/60 pt-4">
                            <button type="button" class="py-2 px-4 text-sm font-medium border border-gray-200 dark:border-slate-800 dark:text-neutral-300 hover:bg-gray-50 dark:hover:bg-slate-900 rounded-xl transition-colors duration-150" data-hs-overlay="#hs-modal-add-resident">Annuler</button>
                            <button type="submit" class="py-2 px-4 text-sm font-semibold bg-gradient-to-r from-primary-600 to-purple-600 hover:from-primary-700 hover:to-purple-700 text-white rounded-xl shadow-md shadow-primary-500/10 transition-all duration-300" x-text="isEditing ? 'Enregistrer les modifications' : 'Ajouter le Résident'"></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

