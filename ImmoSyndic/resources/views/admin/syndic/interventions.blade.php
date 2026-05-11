@extends('layouts.app')

@section('content')
<div x-data="{
    statutSelectionne: 'all',
    isEditing: false,
    interventionEnCours: { id: '', titre: '', description: '', immeuble_id: '', statut: '' },
    initAjout() {
        this.isEditing = false;
        this.interventionEnCours = { id: '', titre: '', description: '', immeuble_id: '', statut: '' };
    },
    initEdit(id, titre, desc, imm_id, stat) {
        this.isEditing = true;
        this.interventionEnCours = { id: id, titre: titre, description: desc, immeuble_id: imm_id, statut: stat };
    },
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
        <button @click="initAjout()" type="button" data-hs-overlay="#hs-add-intervention-modal" class="py-2.5 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-red-600 text-white hover:bg-red-700 shadow-sm transition-colors">
            <i data-lucide="alert-octagon" class="size-4"></i>
            Signaler une urgence
        </button>
    </div>

    <!-- Filters -->
    <div class="flex flex-wrap gap-2">
        <button @click="statutSelectionne = 'all'" :class="statutSelectionne === 'all' ? 'bg-blue-600 text-white' : 'bg-white border-gray-200 text-gray-600 hover:bg-gray-50'" class="py-2 px-4 rounded-full text-sm font-medium border transition-colors">Tous</button>
        <button @click="statutSelectionne = 'à traiter'" :class="statutSelectionne === 'à traiter' ? 'bg-blue-600 text-white' : 'bg-white border-gray-200 text-gray-600 hover:bg-gray-50'" class="py-2 px-4 rounded-full text-sm font-medium border transition-colors">À traiter</button>
        <button @click="statutSelectionne = 'en cours'" :class="statutSelectionne === 'en cours' ? 'bg-blue-600 text-white' : 'bg-white border-gray-200 text-gray-600 hover:bg-gray-50'" class="py-2 px-4 rounded-full text-sm font-medium border transition-colors">En cours</button>
        <button @click="statutSelectionne = 'terminé'" :class="statutSelectionne === 'terminé' ? 'bg-blue-600 text-white' : 'bg-white border-gray-200 text-gray-600 hover:bg-gray-50'" class="py-2 px-4 rounded-full text-sm font-medium border transition-colors">Terminé</button>
    </div>

    <!-- Interventions Grid -->
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($incidents as $intervention)
            @php
                $statutColor = match($intervention->statut) {
                    'Ouvert' => 'bg-red-100 text-red-800',
                    'En cours' => 'bg-blue-100 text-blue-800',
                    'Résolu' => 'bg-green-100 text-green-800',
                    default => 'bg-gray-100 text-gray-800'
                };
                $immeubleName = $intervention->immeuble ? $intervention->immeuble->nom : 'N/A';
            @endphp
            <div x-show="matches('{{ $intervention->statut }}')" class="flex flex-col bg-white border shadow-sm rounded-xl hover:shadow-md transition dark:bg-neutral-900 dark:border-neutral-700">
                <div class="p-4 md:p-5">
                    <div class="flex justify-between items-start mb-3">
                        <span class="inline-flex items-center gap-x-1.5 py-1 px-2 rounded font-semibold text-[10px] {{ $statutColor }} uppercase tracking-wider">
                            {{ $intervention->statut }}
                        </span>
                        <div class="flex gap-x-1">
                            <button @click="initEdit('{{ $intervention->id }}', '{{ addslashes($intervention->titre) }}', '{{ addslashes($intervention->description) }}', '{{ $intervention->immeuble_id }}', '{{ $intervention->statut }}')" data-hs-overlay="#hs-add-intervention-modal" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                <i data-lucide="edit-2" class="size-4"></i>
                            </button>
                        </div>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">{{ $intervention->titre }}</h3>
                    <p class="mt-2 text-sm text-gray-600 dark:text-neutral-400 line-clamp-2">{{ $intervention->description }}</p>
                    <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between items-center text-xs text-gray-500">
                        <span class="flex items-center"><i data-lucide="map-pin" class="size-3 me-2"></i> {{ $immeubleName }}</span>
                        <span>{{ $intervention->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Modal (INSIDE x-data scope) -->
    <div id="hs-add-intervention-modal" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none">
        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
            <div class="flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 w-full">
                <div class="flex justify-between items-center py-3 px-4 border-b dark:border-neutral-700">
                    <h3 class="font-bold text-gray-800 dark:text-white" x-text="isEditing ? 'Modifier l\'intervention' : 'Signaler une nouvelle intervention'"></h3>
                    <button type="button" class="size-7 rounded-full bg-gray-100 dark:bg-neutral-700" data-hs-overlay="#hs-add-intervention-modal">
                        <i data-lucide="x" class="size-4"></i>
                    </button>
                </div>
                <div class="p-4 overflow-y-auto">
                    <form :action="isEditing ? `/syndic/interventions/${interventionEnCours.id}` : '/syndic/interventions'" method="POST">
                        @csrf
                        <template x-if="isEditing">
                            <input type="hidden" name="_method" value="PUT">
                        </template>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-2 dark:text-white">Titre de l’incident</label>
                                <input x-model="interventionEnCours.titre" type="text" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2 dark:text-white">Résidence</label>
                                <select x-model="interventionEnCours.immeuble_id" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
                                    <option value="">Sélectionner</option>
                                    @foreach($immeubles as $immeuble)
                                        <option value="{{ $immeuble->id }}">{{ $immeuble->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div x-show="isEditing">
                                <label class="block text-sm font-medium mb-2 dark:text-white">Statut</label>
                                <select x-model="interventionEnCours.statut" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
                                    <option value="Ouvert">À traiter</option>
                                    <option value="En cours">En cours</option>
                                    <option value="Résolu">Terminé</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2 dark:text-white">Description</label>
                                <textarea x-model="interventionEnCours.description" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="flex justify-end items-center gap-x-2 mt-6">
                            <button type="button" class="py-2 px-3 text-sm border rounded-lg" data-hs-overlay="#hs-add-intervention-modal">Annuler</button>
                            <button type="submit" class="py-2 px-3 text-sm bg-red-600 text-white rounded-lg hover:bg-red-700" x-text="isEditing ? 'Sauvegarder les modifications' : 'Enregistrer ل’urgence'"></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
