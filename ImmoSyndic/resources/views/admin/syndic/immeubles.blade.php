@extends('layouts.app')

@section('content')
<div x-data="{ 
    search: '', 
    filterVille: 'all',
    filterStatut: 'all',
    openVille: false,
    openStatut: false,
    isEditing: false,
    immeubleEnCours: { id: '', nom: '', adresse: '', ville: '', nombre_etages: '', nombre_appartements: '' },
    initAjout() {
        this.isEditing = false;
        this.immeubleEnCours = { id: '', nom: '', adresse: '', ville: '', nombre_etages: '', nombre_appartements: '' };
    },
    initEdit(id, nom, adresse, ville, nb_etages, nb_app) {
        this.isEditing = true;
        this.immeubleEnCours = { id: id, nom: nom, adresse: adresse, ville: ville, nombre_etages: nb_etages, nombre_appartements: nb_app };
    },
    matches(nom, ville, statut) {
        const matchesSearch = nom.toLowerCase().includes(this.search.toLowerCase());
        const matchesVille = this.filterVille === 'all' || ville === this.filterVille;
        const matchesStatut = this.filterStatut === 'all' || statut === this.filterStatut;
        return matchesSearch && matchesVille && matchesStatut;
    }
}">
    <!-- Page Header -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 dark:text-white">Mes Immeubles</h2>
            <p class="text-sm text-slate-500 dark:text-neutral-400">Gérez et supervisez les bâtiments sous votre responsabilité directe.</p>
        </div>
        <button @click="initAjout()" type="button" data-hs-overlay="#hs-modal-add-immeuble" class="py-2.5 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-bold rounded-xl border border-transparent bg-gradient-to-r from-primary-600 to-purple-600 text-white hover:from-primary-700 hover:to-purple-700 shadow-md shadow-primary-500/15 transition-all glow-hover">
            <i data-lucide="plus" class="size-4.5"></i>
            Ajouter un immeuble
        </button>
    </div>

    <!-- Table Section Container (Premium Glass Panel) -->
    <div class="flex flex-col border border-gray-200/60 dark:border-slate-800/60 rounded-2xl shadow-premium bg-white dark:bg-[#0D121F] overflow-hidden">
        <!-- Filters Header -->
        <div class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-b border-gray-200/60 dark:border-slate-800/60 bg-slate-50/50 dark:bg-slate-900/30">
            <div class="sm:col-span-1 max-w-sm w-full relative">
                <input x-model="search" type="text" class="py-2.5 px-4 ps-11 block w-full border-gray-200 dark:border-slate-850 dark:bg-[#080B11] dark:text-slate-300 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500" placeholder="Nom de l'immeuble...">
                <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                    <i data-lucide="search" class="size-4 text-gray-400"></i>
                </div>
            </div>
        </div>

        <!-- Table Grid -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-250 dark:divide-slate-800">
                <thead class="bg-slate-50 dark:bg-slate-900/50">
                    <tr>
                        <th scope="col" class="px-6 py-3.5 text-start text-xs font-bold text-slate-400 uppercase dark:text-neutral-450 tracking-wider">Immeuble</th>
                        <th scope="col" class="px-6 py-3.5 text-start text-xs font-bold text-slate-400 uppercase dark:text-neutral-450 tracking-wider">Adresse complète</th>
                        <th scope="col" class="px-6 py-3.5 text-start text-xs font-bold text-slate-400 uppercase dark:text-neutral-450 tracking-wider">Résidents & Appartements</th>
                        <th scope="col" class="px-6 py-3.5 text-start text-xs font-bold text-slate-400 uppercase dark:text-neutral-450 tracking-wider">Statut</th>
                        <th scope="col" class="px-6 py-3.5 text-end text-xs font-bold text-slate-400 uppercase dark:text-neutral-450 tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200/60 dark:divide-slate-800/60">
                    @foreach($immeubles as $immeuble)
                    <tr x-show="matches('{{ $immeuble->nom }}', '{{ $immeuble->ville }}', 'Actif')" class="hover:bg-slate-50/50 dark:hover:bg-slate-900/30 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="block text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $immeuble->nom }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-neutral-400">
                            {{ $immeuble->adresse }}, {{ $immeuble->ville }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-700 dark:text-slate-300">
                            {{ $immeuble->appartements->count() }} Appartements
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                                <span class="size-1.5 rounded-full bg-emerald-500"></span>
                                Actif
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-semibold">
                            <div class="inline-flex items-center gap-x-2">
                                <button @click="initEdit('{{ $immeuble->id }}', '{{ addslashes($immeuble->nom) }}', '{{ addslashes($immeuble->adresse) }}', '{{ addslashes($immeuble->ville) }}', {{ $immeuble->nombre_etages }}, {{ $immeuble->nombre_appartements }})" type="button" data-hs-overlay="#hs-modal-add-immeuble" class="size-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:text-slate-300 flex items-center justify-center transition-all shadow-sm border border-gray-200/40 dark:border-slate-750">
                                    <i data-lucide="edit-2" class="size-3.5"></i>
                                </button>
                                <button type="button" class="size-8 rounded-xl bg-rose-500/10 hover:bg-rose-500/25 border border-rose-500/20 text-rose-600 dark:text-rose-400 flex items-center justify-center transition-all shadow-sm">
                                    <i data-lucide="trash-2" class="size-3.5"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal (Premium glass design) -->
    <div id="hs-modal-add-immeuble" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none" role="dialog" tabindex="-1">
        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
            <div class="w-full flex flex-col bg-white dark:bg-[#0D121F] border border-gray-200/60 dark:border-slate-800/60 shadow-premium rounded-2xl pointer-events-auto overflow-hidden">
                <div class="flex justify-between items-center py-4 px-6 border-b border-gray-200/60 dark:border-slate-800/60">
                    <div class="flex items-center gap-x-2.5">
                        <div class="size-8 rounded-lg bg-primary-500/10 border border-primary-500/20 flex items-center justify-center text-primary-600 dark:text-primary-400">
                            <i data-lucide="building" class="size-4.5"></i>
                        </div>
                        <h3 class="font-bold text-slate-800 dark:text-white" x-text="isEditing ? 'Modifier l\'Immeuble' : 'Ajouter un Immeuble'"></h3>
                    </div>
                    <button type="button" class="size-8 inline-flex justify-center items-center rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:text-neutral-400 transition-all" data-hs-overlay="#hs-modal-add-immeuble">
                        <i data-lucide="x" class="size-4"></i>
                    </button>
                </div>
                
                <div class="p-6">
                    <form :action="isEditing ? `/syndic/immeubles/${immeubleEnCours.id}` : '/syndic/immeubles'" method="POST">
                        @csrf
                        <template x-if="isEditing">
                            <input type="hidden" name="_method" value="PUT">
                        </template>
                        <input type="hidden" name="id" x-model="immeubleEnCours.id">
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold mb-2 text-slate-700 dark:text-slate-200">Nom de l'immeuble</label>
                                <input name="nom" x-model="immeubleEnCours.nom" type="text" class="py-2.5 px-4 block w-full border-gray-200 dark:border-slate-850 dark:bg-[#080B11] dark:text-slate-300 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500" placeholder="Résidence Al Amal" required>
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold mb-2 text-slate-700 dark:text-slate-200">Ville</label>
                                    <input name="ville" x-model="immeubleEnCours.ville" type="text" class="py-2.5 px-4 block w-full border-gray-200 dark:border-slate-850 dark:bg-[#080B11] dark:text-slate-300 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500" placeholder="Casablanca" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold mb-2 text-slate-700 dark:text-slate-200">Adresse complète</label>
                                    <input name="adresse" x-model="immeubleEnCours.adresse" type="text" class="py-2.5 px-4 block w-full border-gray-200 dark:border-slate-850 dark:bg-[#080B11] dark:text-slate-300 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500" placeholder="14 Rue des Oliviers" required>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold mb-2 text-slate-700 dark:text-slate-200">Nombre d'étages</label>
                                    <input name="nombre_etages" x-model="immeubleEnCours.nombre_etages" type="number" class="py-2.5 px-4 block w-full border-gray-200 dark:border-slate-850 dark:bg-[#080B11] dark:text-slate-300 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500" placeholder="0" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold mb-2 text-slate-700 dark:text-slate-200">Nombre d'appartements</label>
                                    <input name="nombre_appartements" x-model="immeubleEnCours.nombre_appartements" type="number" class="py-2.5 px-4 block w-full border-gray-200 dark:border-slate-850 dark:bg-[#080B11] dark:text-slate-300 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500" placeholder="0" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex justify-end items-center gap-x-3 mt-6 pt-4 border-t border-gray-200/60 dark:border-slate-800/60">
                            <button type="button" class="py-2.5 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-xl border border-gray-200 bg-white hover:bg-gray-50 text-slate-800 dark:bg-neutral-850 dark:border-neutral-800 dark:text-white dark:hover:bg-neutral-800 transition-all shadow-sm" data-hs-overlay="#hs-modal-add-immeuble">Annuler</button>
                            <button type="submit" class="py-2.5 px-4 inline-flex items-center gap-x-2 text-sm font-bold rounded-xl border border-transparent bg-gradient-to-r from-primary-600 to-purple-600 text-white hover:from-primary-700 hover:to-purple-700 shadow-md shadow-primary-500/15 transition-all glow-hover" x-text="isEditing ? 'Enregistrer les modifications' : 'Ajouter l\'immeuble'"></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
