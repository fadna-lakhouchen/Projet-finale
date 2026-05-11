@extends('layouts.app')

@section('content')
<div x-data="{
    statutSelectionne: 'all',
    matches(statut) {
        if (this.statutSelectionne === 'all') return true;
        if (this.statutSelectionne === 'à traiter' && statut === 'Ouvert') return true;
        if (this.statutSelectionne === 'en cours' && statut === 'En cours') return true;
        if (this.statutSelectionne === 'terminé' && statut === 'Résolu') return true;
        return false;
    }
}" class="p-4 sm:p-6 space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Carnet d’Interventions</h1>
            <p class="text-sm text-gray-600 dark:text-neutral-400">Gérez les demandes de maintenance et le suivi technique.</p>
        </div>
        <button type="button" data-hs-overlay="#hs-add-intervention-modal" class="py-2.5 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-red-600 text-white hover:bg-red-700 shadow-sm disabled:opacity-50 disabled:pointer-events-none">
            <i data-lucide="alert-octagon" class="size-4"></i>
            Signaler une urgence
        </button>
    </div>

    <!-- Custom Filters (Tabs style) -->
    <div class="flex flex-wrap gap-2">
        @php
            $countTotal = $incidents->count();
            $countATraiter = $incidents->where('statut', 'Ouvert')->count();
            $countEnCours = $incidents->where('statut', 'En cours')->count();
            $countTermine = $incidents->where('statut', 'Résolu')->count();
        @endphp
        <button @click="statutSelectionne = 'all'" :class="statutSelectionne === 'all' ? 'bg-blue-600 text-white shadow-sm border-transparent' : 'bg-white border-gray-200 text-gray-600 hover:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 dark:hover:bg-neutral-700'" class="py-2 px-4 rounded-full text-sm font-medium border transition-colors">Tous ({{ $countTotal }})</button>
        <button @click="statutSelectionne = 'à traiter'" :class="statutSelectionne === 'à traiter' ? 'bg-blue-600 text-white shadow-sm border-transparent' : 'bg-white border-gray-200 text-gray-600 hover:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 dark:hover:bg-neutral-700'" class="py-2 px-4 rounded-full text-sm font-medium border transition-colors">À traiter ({{ $countATraiter }})</button>
        <button @click="statutSelectionne = 'en cours'" :class="statutSelectionne === 'en cours' ? 'bg-blue-600 text-white shadow-sm border-transparent' : 'bg-white border-gray-200 text-gray-600 hover:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 dark:hover:bg-neutral-700'" class="py-2 px-4 rounded-full text-sm font-medium border transition-colors">En cours ({{ $countEnCours }})</button>
        <button @click="statutSelectionne = 'terminé'" :class="statutSelectionne === 'terminé' ? 'bg-blue-600 text-white shadow-sm border-transparent' : 'bg-white border-gray-200 text-gray-600 hover:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 dark:hover:bg-neutral-700'" class="py-2 px-4 rounded-full text-sm font-medium border transition-colors">Terminé ({{ $countTermine }})</button>
    </div>

    <!-- Interventions Grid -->
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($incidents as $intervention)
            @php
                $statutColor = match($intervention->statut) {
                    'Ouvert' => 'bg-red-100 text-red-800 dark:bg-red-800/10 dark:text-red-500',
                    'En cours' => 'bg-blue-100 text-blue-800 dark:bg-blue-800/10 dark:text-blue-500',
                    'Résolu' => 'bg-green-100 text-green-800 dark:bg-green-800/10 dark:text-green-500',
                    default => 'bg-gray-100 text-gray-800'
                };
                $statutLabel = match($intervention->statut) {
                    'Ouvert' => 'Urgent / À traiter',
                    'En cours' => 'En cours',
                    'Résolu' => 'Terminé',
                    default => $intervention->statut
                };
                $immeubleName = $intervention->immeuble ? $intervention->immeuble->nom : 'N/A';
            @endphp
            <a x-show="matches('{{ $intervention->statut }}')" class="group flex flex-col bg-white border shadow-sm rounded-xl hover:shadow-md transition dark:bg-neutral-900 dark:border-neutral-700 dark:shadow-neutral-700/70" href="#">
                <div class="p-4 md:p-5">
                    <div class="flex justify-between items-center mb-3">
                        <div class="flex items-center gap-x-2">
                            <span class="inline-flex items-center gap-x-1.5 py-1 px-2 rounded font-semibold text-[10px] {{ $statutColor }} uppercase tracking-wider">
                                {{ $statutLabel }}
                            </span>
                            <span class="text-xs font-medium text-gray-400 dark:text-neutral-500">#INT-{{ $intervention->id }}</span>
                        </div>
                        <span class="text-xs text-gray-500 dark:text-neutral-500 flex items-center gap-x-1">
                            <i data-lucide="clock" class="size-3"></i> {{ $intervention->created_at->diffForHumans() }}
                        </span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white group-hover:text-primary-600 transition-colors">
                        {{ $intervention->titre }}
                    </h3>
                    <p class="mt-2 text-sm text-gray-600 dark:text-neutral-400 line-clamp-2">{{ $intervention->description }}</p>
                    <div class="mt-4 pt-4 border-t border-gray-100 dark:border-neutral-800 flex items-center text-xs text-gray-500 dark:text-neutral-500">
                        <i data-lucide="map-pin" class="size-3 me-2"></i> {{ $immeubleName }}
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</div>

<!-- Modal Dialog: Signaler une urgence -->
<div id="hs-add-intervention-modal" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none">
    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
        <div class="flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70 w-full">
            <div class="flex justify-between items-center py-3 px-4 border-b dark:border-neutral-700">
                <h3 class="font-bold text-gray-800 dark:text-white">Signaler une nouvelle intervention</h3>
                <button type="button" class="flex justify-center items-center size-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700" data-hs-overlay="#hs-add-intervention-modal">
                    <span class="sr-only">Fermer</span>
                    <i data-lucide="x" class="size-4"></i>
                </button>
            </div>
            <div class="p-4 overflow-y-auto">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-2 dark:text-white">Titre de l’incident</label>
                        <input type="text" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" placeholder="ex: Panne ascenseur">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2 dark:text-white">Résidence</label>
                        <select class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
                            @foreach($immeubles as $immeuble)
                                <option value="{{ $immeuble->id }}">{{ $immeuble->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2 dark:text-white">Description</label>
                        <textarea class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" rows="3" placeholder="Détails de l’incident..."></textarea>
                    </div>
                </div>
            </div>
            <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
                <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800" data-hs-overlay="#hs-add-intervention-modal">Annuler</button>
                <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-red-600 text-white hover:bg-red-700">Enregistrer l’urgence</button>
            </div>
        </div>
    </div>
</div>
@endsection
