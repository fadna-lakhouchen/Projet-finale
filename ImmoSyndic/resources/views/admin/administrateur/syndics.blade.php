@extends('layouts.app')

@section('content')
<div x-data="{ 
    search: '', 
    filterStatut: 'all', 
    filterCharge: 'all',
    showStat: false,
    showCharge: false,
    isEditing: false,
    syndicEnCours: { id: '', prenom: '', nom: '', email: '', telephone: '', cin: '', ville: '', date_entree: '', date_sortie: '', notes: '', immeubles: [] },
    initAjout() {
        this.isEditing = false;
        this.syndicEnCours = { id: '', prenom: '', nom: '', email: '', telephone: '', cin: '', ville: '', date_entree: '', date_sortie: '', notes: '', immeubles: [] };
        if (window.editor) window.editor.commands.setContent('');
    },
    initEdit(id, prenom, nom, email, telephone, cin, ville, date_entree, date_sortie, notes, immeubles) {
        this.isEditing = true;
        this.syndicEnCours = { id: id, prenom: prenom, nom: nom, email: email, telephone: telephone, cin: cin, ville: ville, date_entree: date_entree, date_sortie: date_sortie, notes: notes, immeubles: immeubles };
        if (window.editor) window.editor.commands.setContent(notes || '');
    },
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
        <button @click="initAjout()" type="button" data-hs-overlay="#hs-modal-add-syndic" class="py-2.5 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-primary-600 text-white hover:bg-primary-700 disabled:opacity-50 disabled:pointer-events-none transition-colors">
            <i data-lucide="plus" class="size-4"></i>
            Ajouter un syndic
        </button>
    </div>

    <!-- Table Section Container -->
    <div class="flex flex-col border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
        <!-- Filters -->
        <div class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-b border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 rounded-t-xl">
            <div class="sm:col-span-1 max-w-sm w-full relative">
                <input x-model="search" type="text" class="py-2 px-3 ps-11 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" placeholder="Rechercher syndic...">
                <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                    <i data-lucide="search" class="size-4 text-gray-400"></i>
                </div>
            </div>

            <div class="sm:col-span-2 md:grow flex justify-end gap-x-2">
                <!-- Filter Statut -->
                <div class="relative inline-flex">
                  <button @click="showStat = !showStat; showCharge = false" @click.outside="showStat = false" type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white">
                    <span x-text="filterStatut === 'all' ? 'Statut' : filterStatut"></span>
                    <i data-lucide="chevron-down" :class="showStat ? 'rotate-180' : ''" class="size-4 transition-transform text-gray-400"></i>
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
                    <i data-lucide="chevron-down" :class="showCharge ? 'rotate-180' : ''" class="size-4 transition-transform text-gray-400"></i>
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
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Nom & Contact</th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Immeuble(s) assigné(s)</th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Détails</th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Statut</th>
                        <th class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                    @foreach($syndics as $syndic)
                    @php
                        $nbImmeubles = $syndic->immeubles->count();
                        $statut = $syndic->is_active ? 'Actif' : 'Inactif';
                    @endphp
                    <tr x-show="matches('{{ $syndic->prenom }} {{ $syndic->nom }}', '{{ $syndic->email }}', '{{ $statut }}', {{ $nbImmeubles }})" class="hover:bg-gray-50 dark:hover:bg-neutral-800 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-x-3">
                                <div class="size-[38px] rounded-full bg-primary-100 flex items-center justify-center text-primary-600 font-bold">
                                    {{ substr($syndic->prenom, 0, 1) }}{{ substr($syndic->nom, 0, 1) }}
                                </div>
                                <div class="grow">
                                    <span class="block text-sm font-semibold text-gray-800 dark:text-neutral-200">{{ $syndic->prenom }} {{ $syndic->nom }}</span>
                                    <span class="block text-sm text-gray-500 dark:text-neutral-400"><i data-lucide="mail" class="inline size-3 me-1"></i>{{ $syndic->email }}</span>
                                    @if($syndic->telephone)
                                        <span class="block text-sm text-gray-500 dark:text-neutral-400"><i data-lucide="phone" class="inline size-3 me-1"></i>{{ $syndic->telephone }}</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-wrap gap-1 max-w-[200px]">
                                @forelse($syndic->immeubles as $immeuble)
                                    <span class="py-1 px-2 text-xs font-medium bg-gray-100 text-gray-800 rounded dark:bg-neutral-700 dark:text-neutral-300">{{ $immeuble->nom }}</span>
                                @empty
                                    <span class="text-sm text-gray-500 italic">Aucun immeuble</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="block text-sm text-gray-800 dark:text-neutral-200">
                                @if($syndic->ville) {{ $syndic->ville }} @else <span class="text-gray-400 italic">Ville N/A</span> @endif
                            </span>
                            <span class="block text-sm text-gray-500 dark:text-neutral-400">
                                @if($syndic->date_entree) Entrée: {{ \Carbon\Carbon::parse($syndic->date_entree)->format('d/m/Y') }} @endif
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center py-1.5 px-3 rounded-full text-xs font-medium {{ $syndic->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $statut }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                            <div class="inline-flex items-center gap-x-2">
                                <button @click="initEdit('{{ $syndic->id }}', '{{ addslashes($syndic->prenom) }}', '{{ addslashes($syndic->nom) }}', '{{ addslashes($syndic->email) }}', '{{ addslashes($syndic->telephone) }}', '{{ addslashes($syndic->cin) }}', '{{ addslashes($syndic->ville) }}', '{{ $syndic->date_entree }}', '{{ $syndic->date_sortie }}', '{{ addslashes($syndic->notes ?? '') }}', {{ json_encode($syndic->immeubles->pluck('id')) }})" type="button" data-hs-overlay="#hs-modal-add-syndic" class="py-2 px-3 text-gray-800 hover:bg-gray-100 rounded-lg border border-gray-200 dark:text-neutral-300 dark:hover:bg-neutral-800">
                                    <i data-lucide="edit-2" class="size-4"></i>
                                </button>
                                <form action="{{ route('admin.syndics.destroy', $syndic->id) }}" method="POST" onsubmit="return confirm('Supprimer ce syndic ?');">
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
    <div id="hs-modal-add-syndic" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none" role="dialog" tabindex="-1">
        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
            <div class="w-full flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700">
                <div class="flex justify-between items-center py-3 px-4 border-b dark:border-neutral-700">
                    <h3 class="font-bold text-gray-800 dark:text-white" x-text="isEditing ? 'Modifier le compte syndic' : 'Ajouter un nouveau compte syndic'"></h3>
                    <button type="button" class="size-8 inline-flex justify-center items-center rounded-full bg-gray-100 text-gray-800 hover:bg-gray-200 dark:bg-neutral-700 dark:text-neutral-400" data-hs-overlay="#hs-modal-add-syndic">
                        <i data-lucide="x" class="size-4"></i>
                    </button>
                </div>
                <div class="p-4 overflow-y-auto">
                    <form :action="isEditing ? `/admin/syndics/${syndicEnCours.id}` : '{{ route('admin.syndics.store') }}'" method="POST">
                        @csrf
                        <template x-if="isEditing">
                            <input type="hidden" name="_method" value="PUT">
                        </template>
                        <input type="hidden" name="id" x-model="syndicEnCours.id">
                        
                        <div class="grid gap-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-2 dark:text-white">Prénom</label>
                                    <input name="prenom" x-model="syndicEnCours.prenom" type="text" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2 dark:text-white">Nom</label>
                                    <input name="nom" x-model="syndicEnCours.nom" type="text" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" required>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2 dark:text-white">Adresse Email</label>
                                <input name="email" x-model="syndicEnCours.email" type="email" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" placeholder="youssef.khadir@email.com" required>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-2 dark:text-white">Téléphone</label>
                                    <input name="telephone" x-model="syndicEnCours.telephone" type="text" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" placeholder="0600000000">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2 dark:text-white">CIN</label>
                                    <input name="cin" x-model="syndicEnCours.cin" type="text" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" placeholder="AB123456">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-2 dark:text-white">Ville</label>
                                    <input name="ville" x-model="syndicEnCours.ville" type="text" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" placeholder="Ex: Casablanca">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2 dark:text-white">Date d'entrée</label>
                                    <input name="date_entree" x-model="syndicEnCours.date_entree" type="date" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
                                </div>
                            </div>
                            
                            <div x-show="isEditing">
                                <label class="block text-sm font-medium mb-2 dark:text-white">Date de sortie</label>
                                <input name="date_sortie" x-model="syndicEnCours.date_sortie" type="date" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
                                <p class="text-xs text-gray-500 mt-1">À renseigner uniquement si le syndic quitte ses fonctions.</p>
                            </div>
                            
                            <!-- Assignation d'immeubles -->
                            <div>
                                <label class="block text-sm font-medium mb-2 dark:text-white">Assigner des immeubles (Optionnel)</label>
                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 max-h-48 overflow-y-auto dark:bg-neutral-800 dark:border-neutral-700">
                                    @forelse($immeubles as $immeuble)
                                    <div class="flex items-center mb-2 last:mb-0">
                                        <input type="checkbox" name="immeubles[]" value="{{ $immeuble->id }}" x-model="syndicEnCours.immeubles" id="imm-{{ $immeuble->id }}" class="shrink-0 mt-0.5 border-gray-200 rounded text-primary-600 focus:ring-primary-500 dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-primary-500 dark:checked:border-primary-500 dark:focus:ring-offset-gray-800">
                                        <label for="imm-{{ $immeuble->id }}" class="text-sm text-gray-700 ms-3 dark:text-neutral-400 cursor-pointer">
                                            {{ $immeuble->nom }} ({{ $immeuble->ville ?? 'Ville non précisée' }})
                                        </label>
                                    </div>
                                    @empty
                                    <p class="text-sm text-gray-500 dark:text-neutral-400 italic">Aucun immeuble disponible.</p>
                                    @endforelse
                                </div>
                            </div>
                            
                            <!-- Tiptap Notes -->
                            <div>
                                <label class="block text-sm font-medium mb-2 dark:text-white">Notes</label>
                                <input type="hidden" name="notes" x-model="syndicEnCours.notes" style="display: none !important;">
                                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden dark:bg-neutral-900 dark:border-neutral-700">
                                    <div id="hs-editor-tiptap">
                                        <div class="sticky top-0 bg-white flex align-middle gap-x-0.5 border-b border-gray-200 p-2 dark:bg-neutral-900 dark:border-neutral-700">
                                            <button class="size-8 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full text-gray-800 hover:bg-gray-100 focus:outline-none dark:text-white dark:hover:bg-neutral-800" type="button" data-hs-editor-bold><i data-lucide="bold" class="size-4"></i></button>
                                            <button class="size-8 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full text-gray-800 hover:bg-gray-100 focus:outline-none dark:text-white dark:hover:bg-neutral-800" type="button" data-hs-editor-italic><i data-lucide="italic" class="size-4"></i></button>
                                            <button class="size-8 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full text-gray-800 hover:bg-gray-100 focus:outline-none dark:text-white dark:hover:bg-neutral-800" type="button" data-hs-editor-underline><i data-lucide="underline" class="size-4"></i></button>
                                            <button class="size-8 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full text-gray-800 hover:bg-gray-100 focus:outline-none dark:text-white dark:hover:bg-neutral-800" type="button" data-hs-editor-ol><i data-lucide="list-ordered" class="size-4"></i></button>
                                            <button class="size-8 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full text-gray-800 hover:bg-gray-100 focus:outline-none dark:text-white dark:hover:bg-neutral-800" type="button" data-hs-editor-ul><i data-lucide="list" class="size-4"></i></button>
                                        </div>
                                        <div class="h-32 overflow-auto p-4 focus:outline-none tiptap-content" data-hs-editor-field></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-end items-center gap-x-2 mt-6">
                            <button type="button" class="py-2 px-3 text-sm border rounded-lg" data-hs-overlay="#hs-modal-add-syndic">Annuler</button>
                            <button type="submit" class="py-2 px-3 text-sm bg-primary-600 text-white rounded-lg hover:bg-primary-700" x-text="isEditing ? 'Sauvegarder les modifications' : 'Créer le compte'"></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@push('styles')
<style>
    .tiptap-content .ProseMirror { outline: none; min-height: 100%; }
    .tiptap-content ul { list-style-type: disc; padding-left: 1.5rem; }
    .tiptap-content ol { list-style-type: decimal; padding-left: 1.5rem; }
</style>
@endpush
@push('scripts')
<script type="module">
    import { Editor } from 'https://esm.sh/@tiptap/core';
    import StarterKit from 'https://esm.sh/@tiptap/starter-kit';
    import Underline from 'https://esm.sh/@tiptap/extension-underline';

    const editor = new Editor({
        element: document.querySelector('#hs-editor-tiptap [data-hs-editor-field]'),
        extensions: [StarterKit, Underline],
        content: '',
        onUpdate({ editor }) {
            const html = editor.getHTML();
            const el = document.querySelector('[x-data]');
            const alpineData = Alpine.$data(el);
            if (alpineData) alpineData.syndicEnCours.notes = html;
        }
    });
    window.editor = editor;

    const actions = [
        { id: 'bold', action: () => editor.chain().focus().toggleBold().run() },
        { id: 'italic', action: () => editor.chain().focus().toggleItalic().run() },
        { id: 'underline', action: () => editor.chain().focus().toggleUnderline().run() },
        { id: 'ol', action: () => editor.chain().focus().toggleOrderedList().run() },
        { id: 'ul', action: () => editor.chain().focus().toggleBulletList().run() }
    ];

    actions.forEach(({ id, action }) => {
        document.querySelector(`[data-hs-editor-${id}]`)?.addEventListener('click', action);
    });
</script>
@endpush
@endsection
