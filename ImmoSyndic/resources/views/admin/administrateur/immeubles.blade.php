@extends('layouts.app')

@section('content')
<div x-data="{ 
    search: '', 
    filterSyndic: 'all', 
    filterStatut: 'all',
    isEditing: false,
    immeubleEnCours: { id: '', nom: '', adresse: '', syndic_id: '', nb_etages: '', nb_appartements: '' },
    initAjout() {
        this.isEditing = false;
        this.immeubleEnCours = { id: '', nom: '', adresse: '', syndic_id: '', nb_etages: '', nb_appartements: '' };
    },
    initEdit(id, nom, adresse, syndic_id, etages, appts) {
        this.isEditing = true;
        this.immeubleEnCours = { id: id, nom: nom, adresse: adresse, syndic_id: syndic_id, nb_etages: etages, nb_appartements: appts };
    },
    matches(name, address, syndic, statut) {
        const matchesSearch = name.toLowerCase().includes(this.search.toLowerCase()) || address.toLowerCase().includes(this.search.toLowerCase());
        const matchesSyndic = this.filterSyndic === 'all' || syndic === this.filterSyndic;
        const matchesStatut = this.filterStatut === 'all' || statut === this.filterStatut;
        return matchesSearch && matchesSyndic && matchesStatut;
    }
}">
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Supervision des Immeubles</h2>
            <p class="text-sm text-gray-600 dark:text-neutral-400">Vue d'ensemble de tous les bâtiments enregistrés.</p>
        </div>
        <button @click="initAjout()" type="button" data-hs-overlay="#hs-modal-add-immeuble" class="py-2.5 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-primary-600 text-white hover:bg-primary-700 transition-colors">
            <i data-lucide="plus" class="size-4"></i>
            Ajouter un immeuble
        </button>
    </div>

    <!-- Table Section -->
    <div class="flex flex-col border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
        <!-- Filters -->
        <div class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-b border-gray-200 dark:border-neutral-700">
            <div class="sm:col-span-1 max-w-sm w-full relative">
                <input x-model="search" type="text" class="py-2 px-3 ps-11 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" placeholder="Nom ou adresse...">
                <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                    <i data-lucide="search" class="size-4 text-gray-400"></i>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                <thead class="bg-gray-50 dark:bg-neutral-800">
                    <tr>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Immeuble</th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Syndic Responsable</th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Résidents</th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Statut</th>
                        <th class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                    @foreach($immeubles as $immeuble)
                    <tr x-show="matches('{{ $immeuble->nom }}', '{{ $immeuble->adresse }}', '{{ $immeuble->syndic ? $immeuble->syndic->prenom . ' ' . $immeuble->syndic->nom : 'N/A' }}', 'Sain')" class="hover:bg-gray-50 dark:hover:bg-neutral-800 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="grow">
                                <span class="block text-sm font-semibold text-gray-800 dark:text-neutral-200">{{ $immeuble->nom }}</span>
                                <span class="block text-xs text-gray-500 dark:text-neutral-400">{{ $immeuble->adresse }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-800 dark:text-neutral-200">{{ $immeuble->syndic ? $immeuble->syndic->prenom . ' ' . $immeuble->syndic->nom : 'Non assigné' }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">{{ $immeuble->nombre_appartements }} Appts</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center py-1.5 px-3 rounded-full text-xs font-medium bg-green-100 text-green-800">Sain</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                            <div class="inline-flex items-center gap-x-2">
                                <button @click="initEdit('{{ $immeuble->id }}', '{{ addslashes($immeuble->nom) }}', '{{ addslashes($immeuble->adresse) }}', '{{ $immeuble->syndic_id }}', '{{ $immeuble->nombre_etages }}', '{{ $immeuble->nombre_appartements }}')" type="button" data-hs-overlay="#hs-modal-add-immeuble" class="py-2 px-3 text-gray-800 hover:bg-gray-100 rounded-lg border border-gray-200 dark:text-neutral-300 dark:hover:bg-neutral-800">
                                    <i data-lucide="edit-2" class="size-4"></i>
                                </button>
                                <form action="{{ route('admin.immeubles.destroy', $immeuble->id) }}" method="POST" onsubmit="return confirm('Supprimer cet immeuble ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="py-2 px-3 bg-red-100 text-red-800 hover:bg-red-200 rounded-lg">
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
    </div>

    <!-- Modal (INSIDE x-data scope) -->
    <div id="hs-modal-add-immeuble" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none" role="dialog" tabindex="-1">
        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
            <div class="w-full flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700">
                <div class="flex justify-between items-center py-3 px-4 border-b dark:border-neutral-700">
                    <h3 class="font-bold text-gray-800 dark:text-white" x-text="isEditing ? 'Modifier l\'immeuble' : 'Enregistrer un nouvel immeuble'"></h3>
                    <button type="button" class="size-8 inline-flex justify-center items-center rounded-full bg-gray-100 text-gray-800 hover:bg-gray-200 dark:bg-neutral-700 dark:text-neutral-400" data-hs-overlay="#hs-modal-add-immeuble">
                        <i data-lucide="x" class="size-4"></i>
                    </button>
                </div>
                <div class="p-4 overflow-y-auto">
                    <form :action="isEditing ? `/admin/immeubles/${immeubleEnCours.id}` : '{{ route('admin.immeubles.store') }}'" method="POST">
                        @csrf
                        <template x-if="isEditing">
                            <input type="hidden" name="_method" value="PUT">
                        </template>
                        <input type="hidden" name="id" x-model="immeubleEnCours.id">
                        
                        <div class="grid gap-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-2 dark:text-white">Nom de l'immeuble</label>
                                <input x-model="immeubleEnCours.nom" type="text" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" placeholder="Ex: Résidence Al Amal">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2 dark:text-white">Adresse complète</label>
                                <input x-model="immeubleEnCours.adresse" type="text" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" placeholder="Ex: Angle Avenue Mohammed V, Rabat">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2 dark:text-white">Assigner un Syndic</label>
                                <select x-model="immeubleEnCours.syndic_id" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
                                    <option value="">Choisir un syndic responsable</option>
                                    @foreach($syndics as $s)
                                        <option value="{{ $s->id }}">{{ $s->prenom }} {{ $s->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-2 dark:text-white">Nombre d'étages</label>
                                    <input x-model="immeubleEnCours.nb_etages" type="number" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2 dark:text-white">Nombre d'appartements</label>
                                    <input x-model="immeubleEnCours.nb_appartements" type="number" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-end items-center gap-x-2 mt-6">
                            <button type="button" class="py-2 px-3 text-sm border rounded-lg" data-hs-overlay="#hs-modal-add-immeuble">Annuler</button>
                            <button type="submit" class="py-2 px-3 text-sm bg-primary-600 text-white rounded-lg hover:bg-primary-700" x-text="isEditing ? 'Sauvegarder les modifications' : 'Enregistrer'"></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
