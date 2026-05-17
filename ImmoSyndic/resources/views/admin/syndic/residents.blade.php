@extends('layouts.app')

@section('content')
<div x-data="{ 
    search: '', 
    immeubleSelectionne: 'all', 
    statutSelectionne: 'all',
    openImm: false,
    openStat: false,
    isEditing: false,
    residentEnCours: { id: '', prenom: '', nom: '', email: '', telephone: '', cin: '', notes: '', role: 'resident', type_resident: 'locataire', immeuble_id: '', appartement_id: '', date_entree: '' },
    init() {
        this.$watch('residentEnCours.immeuble_id', (value) => {
            if (!this.isEditing) {
                this.residentEnCours.appartement_id = '';
            }
        });
    },
    initAjout() {
        this.isEditing = false;
        this.residentEnCours = { id: '', prenom: '', nom: '', email: '', telephone: '', cin: '', notes: '', role: 'resident', type_resident: 'locataire', immeuble_id: '', appartement_id: '', date_entree: '' };
        if (window.editor) window.editor.commands.setContent('');
    },
    initEdit(id, prenom, nom, email, telephone, cin, type, immeuble_id, appt_id, date_e, notes) {
        this.isEditing = true;
        this.residentEnCours = { id: id, prenom: prenom, nom: nom, email: email, telephone: telephone, cin: cin, notes: notes, role: 'resident', type_resident: type, immeuble_id: immeuble_id, appartement_id: appt_id, date_entree: date_e };
        if (window.editor) window.editor.commands.setContent(notes || '');
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
        <button @click="initAjout()" type="button" data-hs-overlay="#hs-modal-add-resident" class="py-2.5 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-primary-600 text-white hover:bg-primary-700 shadow-sm transition-colors">
            <i data-lucide="user-plus" class="size-4"></i>
            Ajouter un Résident
        </button>
    </div>

    <!-- Table Section -->
    <div class="flex flex-col border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
        <!-- Filters -->
        <div class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-b border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 rounded-t-xl">
            <div class="sm:col-span-1 max-w-sm w-full relative">
                <input x-model="search" type="text" class="py-2 px-3 ps-11 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" placeholder="Rechercher un nom...">
                <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                    <i data-lucide="search" class="size-4 text-gray-400"></i>
                </div>
            </div>

            <div class="sm:col-span-2 md:grow flex justify-end gap-x-2">
                <button @click="openImm = !openImm" @click.outside="openImm = false" type="button" class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white">
                    <span x-text="immeubleSelectionne === 'all' ? 'Immeubles' : immeubleSelectionne"></span>
                    <i data-lucide="chevron-down" class="size-4 text-gray-400"></i>
                </button>
                <div x-show="openImm" x-cloak class="absolute right-0 top-full z-[100] mt-1 w-56 bg-white border border-gray-200 shadow-xl rounded-lg p-1 dark:bg-neutral-800 dark:border-neutral-700" style="display: none;">
                    <div @click="immeubleSelectionne = 'all'; openImm = false" class="cursor-pointer py-2 px-3 rounded-lg text-sm hover:bg-gray-100">Tous les immeubles</div>
                    @foreach($immeubles as $immeuble)
                        <div @click="immeubleSelectionne = '{{ $immeuble->nom }}'; openImm = false" class="cursor-pointer py-2 px-3 rounded-lg text-sm hover:bg-gray-100">{{ $immeuble->nom }}</div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto bg-white dark:bg-neutral-900 rounded-b-xl">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                <thead class="bg-gray-50 dark:bg-neutral-800">
                    <tr>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Nom complet</th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Rôle</th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Immeuble & Appt</th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Statut Charges</th>
                        <th class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">Action</th>
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
                    <tr x-show="matches('{{ $resident->prenom }} {{ $resident->nom }}', '{{ $immeubleName }}', '{{ $typeResident }}')" class="hover:bg-gray-50 dark:hover:bg-neutral-800 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-x-3">
                                <img class="size-[38px] rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($resident->prenom . '+' . $resident->nom) }}&background=random">
                                <div class="grow">
                                    <span class="block text-sm font-semibold text-gray-800 dark:text-neutral-200">{{ $resident->prenom }} {{ $resident->nom }}</span>
                                    <span class="block text-sm text-gray-500 dark:text-neutral-400">{{ $resident->email }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center py-1 px-3 rounded-full text-xs font-medium {{ $typeResident === 'Propriétaire' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ $typeResident }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="block text-sm text-gray-800 dark:text-neutral-200">{{ $immeubleName }}</span>
                            <span class="block text-sm text-gray-500 dark:text-neutral-400">{{ $apptInfo }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center py-1.5 px-3 rounded-full text-xs font-medium {{ $resident->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $resident->is_active ? 'À jour' : 'En retard' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                            <div class="inline-flex items-center gap-x-2">
                                <button @click="initEdit('{{ $resident->id }}', '{{ addslashes($resident->prenom) }}', '{{ addslashes($resident->nom) }}', '{{ addslashes($resident->email) }}', '{{ $resident->telephone }}', '{{ $resident->cin ?? '' }}', '{{ strtolower($typeResident) }}', '{{ $immeubleId }}', '{{ $appt ? $appt->id : '' }}', '{{ $appt ? $appt->pivot->date_entree : '' }}', '{{ addslashes($resident->notes ?? '') }}')" type="button" data-hs-overlay="#hs-modal-add-resident" class="py-2 px-2 text-blue-600 hover:bg-gray-100 rounded-lg border border-gray-200" title="Modifier">
                                    <i data-lucide="edit-2" class="size-4"></i>
                                </button>
                                <form action="{{ route('syndic.residents.destroy', $resident->id) }}" method="POST" onsubmit="return confirm('Supprimer ce résident ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="py-2 px-2 text-red-600 hover:bg-red-50 rounded-lg border border-gray-200" title="Supprimer">
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
    <div id="hs-modal-add-resident" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none" role="dialog" tabindex="-1">
        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
            <div class="w-full flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700">
                <div class="flex justify-between items-center py-3 px-4 border-b dark:border-neutral-700">
                    <h3 class="font-bold text-gray-800 dark:text-white" x-text="isEditing ? 'Modifier le Résident' : 'Ajouter un Résident'"></h3>
                    <button type="button" class="size-8 inline-flex justify-center items-center rounded-full bg-gray-100 text-gray-800 hover:bg-gray-200 dark:bg-neutral-700 dark:text-neutral-400" data-hs-overlay="#hs-modal-add-resident">
                        <i data-lucide="x" class="size-4"></i>
                    </button>
                </div>
                <div class="p-4 overflow-y-auto">
                    <form :action="isEditing ? `/syndic/residents/${residentEnCours.id}` : '{{ route('syndic.residents.store') }}'" method="POST">
                        @csrf
                        <template x-if="isEditing">
                            <input type="hidden" name="_method" value="PUT">
                        </template>
                        <input type="hidden" name="id" x-model="residentEnCours.id">
                        
                        <div class="grid gap-y-4">
                            <div class="grid sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-2 dark:text-white">Prénom</label>
                                    <input name="prenom" x-model="residentEnCours.prenom" type="text" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2 dark:text-white">Nom</label>
                                    <input name="nom" x-model="residentEnCours.nom" type="text" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" required>
                                </div>
                            </div>
                            <div class="grid sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-2 dark:text-white">Email</label>
                                    <input name="email" x-model="residentEnCours.email" type="email" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2 dark:text-white">Téléphone</label>
                                    <input name="telephone" x-model="residentEnCours.telephone" type="text" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" placeholder="0600000000">
                                </div>
                            </div>
                            <div class="grid sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-2 dark:text-white">CIN</label>
                                    <input name="cin" x-model="residentEnCours.cin" type="text" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" placeholder="AB123456">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2 dark:text-white">Type</label>
                                    <select name="type_resident" x-model="residentEnCours.type_resident" class="py-3 px-4 pe-9 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
                                        <option value="locataire">Locataire</option>
                                        <option value="propriétaire">Propriétaire</option>
                                    </select>
                                </div>
                            </div>
                            <div class="grid sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-2 dark:text-white">Immeuble</label>
                                    <select name="immeuble_id" x-model="residentEnCours.immeuble_id" class="py-3 px-4 pe-9 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" required>
                                        <option value="">Sélectionner</option>
                                        @foreach($immeubles as $immeuble)
                                            <option value="{{ $immeuble->id }}">{{ $immeuble->nom }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2 dark:text-white">Appartement</label>
                                    <select name="appartement_id" x-model="residentEnCours.appartement_id" class="py-3 px-4 pe-9 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" required>
                                        <option value="">Sélectionner</option>
                                        @foreach($immeubles as $immeuble)
                                            @foreach($immeuble->appartements as $appt)
                                                <option 
                                                    value="{{ $appt->id }}" 
                                                    x-show="residentEnCours.immeuble_id == '{{ $immeuble->id }}'"
                                                    x-bind:disabled="residentEnCours.immeuble_id != '{{ $immeuble->id }}'"
                                                >
                                                    N° {{ $appt->numero }} (Étage {{ $appt->etage }})
                                                </option>
                                            @endforeach
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2 dark:text-white">Date d'entrée</label>
                                <input name="date_entree" x-model="residentEnCours.date_entree" type="date" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" required>
                            </div>
                            <!-- Tiptap -->
                            <div>
                                <label class="block text-sm font-medium mb-2 dark:text-white">Notes</label>
                                <input type="hidden" name="notes" x-model="residentEnCours.notes" style="display: none !important;">
                                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden dark:bg-neutral-900 dark:border-neutral-700">
                                    <div id="hs-editor-tiptap">
                                        <div class="sticky top-0 bg-white flex align-middle gap-x-0.5 border-b border-gray-200 p-2 dark:bg-neutral-900 dark:border-neutral-700">
                                            <button class="size-8 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full text-gray-800 hover:bg-gray-100 focus:outline-none disabled:opacity-50 dark:text-white dark:hover:bg-neutral-800" type="button" data-hs-editor-bold>
                                                <i data-lucide="bold" class="size-4"></i>
                                            </button>
                                            <button class="size-8 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full text-gray-800 hover:bg-gray-100 focus:outline-none disabled:opacity-50 dark:text-white dark:hover:bg-neutral-800" type="button" data-hs-editor-italic>
                                                <i data-lucide="italic" class="size-4"></i>
                                            </button>
                                            <button class="size-8 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full text-gray-800 hover:bg-gray-100 focus:outline-none disabled:opacity-50 dark:text-white dark:hover:bg-neutral-800" type="button" data-hs-editor-underline>
                                                <i data-lucide="underline" class="size-4"></i>
                                            </button>
                                            <button class="size-8 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full text-gray-800 hover:bg-gray-100 focus:outline-none disabled:opacity-50 dark:text-white dark:hover:bg-neutral-800" type="button" data-hs-editor-strike>
                                                <i data-lucide="strikethrough" class="size-4"></i>
                                            </button>
                                            <button class="size-8 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full text-gray-800 hover:bg-gray-100 focus:outline-none disabled:opacity-50 dark:text-white dark:hover:bg-neutral-800" type="button" data-hs-editor-link>
                                                <i data-lucide="link" class="size-4"></i>
                                            </button>
                                            <button class="size-8 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full text-gray-800 hover:bg-gray-100 focus:outline-none disabled:opacity-50 dark:text-white dark:hover:bg-neutral-800" type="button" data-hs-editor-ol>
                                                <i data-lucide="list-ordered" class="size-4"></i>
                                            </button>
                                            <button class="size-8 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full text-gray-800 hover:bg-gray-100 focus:outline-none disabled:opacity-50 dark:text-white dark:hover:bg-neutral-800" type="button" data-hs-editor-ul>
                                                <i data-lucide="list" class="size-4"></i>
                                            </button>
                                            <button class="size-8 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full text-gray-800 hover:bg-gray-100 focus:outline-none disabled:opacity-50 dark:text-white dark:hover:bg-neutral-800" type="button" data-hs-editor-blockquote>
                                                <i data-lucide="quote" class="size-4"></i>
                                            </button>
                                            <button class="size-8 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full text-gray-800 hover:bg-gray-100 focus:outline-none disabled:opacity-50 dark:text-white dark:hover:bg-neutral-800" type="button" data-hs-editor-code>
                                                <i data-lucide="code" class="size-4"></i>
                                            </button>
                                        </div>
                                        <div class="h-40 overflow-auto p-4 focus:outline-none tiptap-content" data-hs-editor-field></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-end items-center gap-x-2 mt-6">
                            <button type="button" class="py-2 px-3 text-sm border rounded-lg" data-hs-overlay="#hs-modal-add-resident">Annuler</button>
                            <button type="submit" class="py-2 px-3 text-sm bg-primary-600 text-white rounded-lg hover:bg-primary-700" x-text="isEditing ? 'Enregistrer les modifications' : 'Ajouter le Résident'"></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@push('styles')
<style>
    .tiptap-content .ProseMirror {
        outline: none;
        min-height: 100%;
    }
    .tiptap-content p.is-editor-empty:first-child::before {
        content: attr(data-placeholder);
        float: left;
        color: #adb5bd;
        pointer-events: none;
        height: 0;
    }
    /* Style for lists inside editor */
    .tiptap-content ul { list-style-type: disc; padding-left: 1.5rem; }
    .tiptap-content ol { list-style-type: decimal; padding-left: 1.5rem; }
    .tiptap-content blockquote { border-left: 3px solid #ddd; padding-left: 1rem; font-style: italic; }
    .tiptap-content code { background: #f0f0f0; padding: 0.2rem 0.4rem; border-radius: 4px; }
</style>
@endpush
@push('scripts')
<script type="module">
    import { Editor } from 'https://esm.sh/@tiptap/core';
    import StarterKit from 'https://esm.sh/@tiptap/starter-kit';
    import Underline from 'https://esm.sh/@tiptap/extension-underline';
    import Link from 'https://esm.sh/@tiptap/extension-link';

    const editor = new Editor({
        element: document.querySelector('#hs-editor-tiptap [data-hs-editor-field]'),
        extensions: [
            StarterKit,
            Underline,
            Link.configure({
                openOnClick: false,
            }),
        ],
        content: '',
        onUpdate({ editor }) {
            const html = editor.getHTML();
            const el = document.querySelector('[x-data]');
            const alpineData = Alpine.$data(el);
            if (alpineData) alpineData.residentEnCours.notes = html;
        }
    });

    window.editor = editor;

    const actions = [
        { id: 'bold', action: () => editor.chain().focus().toggleBold().run() },
        { id: 'italic', action: () => editor.chain().focus().toggleItalic().run() },
        { id: 'underline', action: () => editor.chain().focus().toggleUnderline().run() },
        { id: 'strike', action: () => editor.chain().focus().toggleStrike().run() },
        { id: 'link', action: () => {
            const url = window.prompt('URL');
            if (url) editor.chain().focus().extendMarkRange('link').setLink({ href: url }).run();
        }},
        { id: 'ol', action: () => editor.chain().focus().toggleOrderedList().run() },
        { id: 'ul', action: () => editor.chain().focus().toggleBulletList().run() },
        { id: 'blockquote', action: () => editor.chain().focus().toggleBlockquote().run() },
        { id: 'code', action: () => editor.chain().focus().toggleCode().run() },
    ];

    actions.forEach(({ id, action }) => {
        const btn = document.querySelector(`[data-hs-editor-${id}]`);
        if (btn) btn.addEventListener('click', action);
    });
</script>
@endpush
@endsection
