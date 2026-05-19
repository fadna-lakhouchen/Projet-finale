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
}" class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight dark:text-white">Carnet d’Interventions</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">Gérez les demandes de maintenance et le suivi technique de vos résidences.</p>
        </div>
        <button @click="initAjout()" type="button" data-hs-overlay="#hs-add-intervention-modal" class="py-2.5 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-xl border border-transparent bg-gradient-to-r from-rose-600 to-red-600 hover:from-rose-700 hover:to-red-700 text-white shadow-md shadow-red-500/10 hover:shadow-lg hover:shadow-red-500/20 transition-all duration-300 transform hover:-translate-y-0.5">
            <i data-lucide="alert-octagon" class="size-4"></i>
            Signaler une urgence
        </button>
    </div>

    <!-- Filters -->
    <div class="flex flex-wrap gap-2.5 p-1.5 bg-gray-100/50 dark:bg-[#090D16]/40 rounded-2xl w-fit border border-gray-200/40 dark:border-slate-800/40 backdrop-blur-md">
        <button @click="statutSelectionne = 'all'" :class="statutSelectionne === 'all' ? 'bg-white dark:bg-slate-800 text-gray-900 dark:text-white shadow-sm' : 'text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white'" class="py-2 px-4 rounded-xl text-sm font-semibold transition-all duration-200">Tous</button>
        <button @click="statutSelectionne = 'à traiter'" :class="statutSelectionne === 'à traiter' ? 'bg-white dark:bg-slate-800 text-gray-900 dark:text-white shadow-sm' : 'text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white'" class="py-2 px-4 rounded-xl text-sm font-semibold transition-all duration-200">À traiter</button>
        <button @click="statutSelectionne = 'en cours'" :class="statutSelectionne === 'en cours' ? 'bg-white dark:bg-slate-800 text-gray-900 dark:text-white shadow-sm' : 'text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white'" class="py-2 px-4 rounded-xl text-sm font-semibold transition-all duration-200">En cours</button>
        <button @click="statutSelectionne = 'terminé'" :class="statutSelectionne === 'terminé' ? 'bg-white dark:bg-slate-800 text-gray-900 dark:text-white shadow-sm' : 'text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white'" class="py-2 px-4 rounded-xl text-sm font-semibold transition-all duration-200">Terminé</button>
    </div>

    <!-- Interventions Grid -->
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($incidents as $intervention)
            @php
                $statutColor = match($intervention->statut) {
                    'Ouvert' => 'bg-rose-50/80 text-rose-600 dark:bg-rose-950/40 dark:text-rose-400 border border-rose-100 dark:border-rose-900/30',
                    'En cours' => 'bg-amber-50/80 text-amber-600 dark:bg-amber-950/40 dark:text-amber-400 border border-amber-100 dark:border-amber-900/30',
                    'Résolu' => 'bg-emerald-50/80 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30',
                    default => 'bg-gray-50/80 text-gray-600 dark:bg-slate-900/40 dark:text-slate-400 border border-gray-100 dark:border-slate-800/30'
                };
                $immeubleName = $intervention->immeuble ? $intervention->immeuble->nom : 'N/A';
            @endphp
            <div x-show="matches('{{ $intervention->statut }}')" class="flex flex-col bg-white/80 dark:bg-[#0D121F]/90 border border-gray-200/60 dark:border-slate-800/60 shadow-premium backdrop-blur-md rounded-2xl hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                <div class="p-6 flex flex-col h-full justify-between">
                    <div>
                        <div class="flex justify-between items-start mb-4">
                            <span class="inline-flex items-center gap-x-1.5 py-1 px-3 rounded-full text-xs font-semibold {{ $statutColor }} tracking-wider">
                                <span class="size-1.5 rounded-full bg-current"></span>
                                {{ $intervention->statut === 'Ouvert' ? 'À traiter' : ($intervention->statut === 'En cours' ? 'En cours' : 'Terminé') }}
                            </span>
                            <button @click="initEdit('{{ $intervention->id }}', '{{ addslashes($intervention->titre) }}', '{{ addslashes($intervention->description) }}', '{{ $intervention->immeuble_id }}', '{{ $intervention->statut }}')" data-hs-overlay="#hs-add-intervention-modal" class="p-1.5 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-950/30 rounded-xl border border-gray-200/80 dark:border-slate-800/80 transition-all duration-200" title="Modifier">
                                <i data-lucide="edit-2" class="size-4"></i>
                            </button>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white line-clamp-1 leading-snug">{{ $intervention->titre }}</h3>
                        <p class="mt-2 text-sm text-gray-600 dark:text-slate-400 line-clamp-3 leading-relaxed">{{ $intervention->description }}</p>
                    </div>
                    
                    <div class="mt-6 pt-4 border-t border-gray-100 dark:border-slate-800/60 flex justify-between items-center text-xs text-gray-500 dark:text-slate-400">
                        <span class="flex items-center"><i data-lucide="map-pin" class="size-3.5 me-2 text-gray-400"></i> {{ $immeubleName }}</span>
                        <span class="flex items-center"><i data-lucide="clock" class="size-3.5 me-1.5 text-gray-400"></i> {{ $intervention->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Modal (INSIDE x-data scope) -->
    <div id="hs-add-intervention-modal" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none bg-slate-950/40 backdrop-blur-sm">
        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
            <div class="flex flex-col bg-white border border-gray-200/60 dark:border-slate-800/60 shadow-premium rounded-2xl pointer-events-auto dark:bg-[#0D121F] w-full overflow-hidden">
                <div class="flex justify-between items-center py-4 px-5 border-b border-gray-200/60 dark:border-slate-800/60">
                    <h3 class="font-bold text-gray-800 dark:text-white text-lg" x-text="isEditing ? 'Modifier l\'intervention' : 'Signaler une nouvelle intervention'"></h3>
                    <button type="button" class="size-8 inline-flex justify-center items-center rounded-xl bg-gray-100 text-gray-800 hover:bg-gray-200 dark:bg-slate-800 dark:text-neutral-400 dark:hover:bg-slate-700 transition-colors" data-hs-overlay="#hs-add-intervention-modal">
                        <i data-lucide="x" class="size-4"></i>
                    </button>
                </div>
                <div class="p-6 overflow-y-auto">
                    <form :action="isEditing ? `/syndic/interventions/${interventionEnCours.id}` : '/syndic/interventions'" method="POST">
                        @csrf
                        <template x-if="isEditing">
                            <input type="hidden" name="_method" value="PUT">
                        </template>
                        
                        <div class="space-y-5">
                            <div>
                                <label class="block text-sm font-semibold mb-2 dark:text-neutral-200">Titre de l’incident</label>
                                <input x-model="interventionEnCours.titre" name="titre" type="text" class="py-2.5 px-4 block w-full border-gray-200 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 bg-white/50 dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-neutral-300 transition-all duration-200" required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-2 dark:text-neutral-200">Résidence / Immeuble</label>
                                <select x-model="interventionEnCours.immeuble_id" name="immeuble_id" class="py-2.5 px-4 block w-full border-gray-200 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 bg-white/50 dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-neutral-300 transition-all duration-200" required>
                                    <option value="">Sélectionner</option>
                                    @foreach($immeubles as $immeuble)
                                        <option value="{{ $immeuble->id }}">{{ $immeuble->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div x-show="isEditing">
                                <label class="block text-sm font-semibold mb-2 dark:text-neutral-200">Statut de la demande</label>
                                <select x-model="interventionEnCours.statut" name="statut" class="py-2.5 px-4 block w-full border-gray-200 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 bg-white/50 dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-neutral-300 transition-all duration-200">
                                    <option value="Ouvert">À traiter</option>
                                    <option value="En cours">En cours</option>
                                    <option value="Résolu">Terminé</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-2 dark:text-neutral-200">Description détaillée</label>
                                <textarea x-model="interventionEnCours.description" name="description" class="py-2.5 px-4 block w-full border-gray-200 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 bg-white/50 dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-neutral-300 transition-all duration-200" rows="4" required></textarea>
                            </div>
                        </div>
                        <div class="flex justify-end items-center gap-x-3 mt-8 border-t border-gray-100 dark:border-slate-800/60 pt-4">
                            <button type="button" class="py-2 px-4 text-sm font-medium border border-gray-200 dark:border-slate-800 dark:text-neutral-300 hover:bg-gray-50 dark:hover:bg-slate-900 rounded-xl transition-colors duration-150" data-hs-overlay="#hs-add-intervention-modal">Annuler</button>
                            <button type="submit" class="py-2 px-4 text-sm font-semibold bg-gradient-to-r from-rose-600 to-red-600 hover:from-rose-700 hover:to-red-700 text-white rounded-xl shadow-md shadow-red-500/10 transition-all duration-300" x-text="isEditing ? 'Sauvegarder les modifications' : 'Enregistrer l’urgence'"></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

