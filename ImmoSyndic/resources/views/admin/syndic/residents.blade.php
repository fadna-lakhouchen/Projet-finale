@extends('layouts.app')

@section('content')
<div x-data="syndicResidents">
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
                        <th class="px-6 py-4 text-start text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-slate-400">Rôle</th>
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
                        $typeResident = $appt ? $appt->pivot->type_resident : 'Locataire';
                    @endphp
                    <tr x-show="matches('{{ $resident->prenom }} {{ $resident->nom }}', '{{ $immeubleName }}', '{{ $typeResident }}')" class="hover:bg-gray-50/50 dark:hover:bg-slate-900/30 transition-colors duration-150">
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
                            @if(strtolower($typeResident) === 'propriétaire')
                                <span class="inline-flex items-center gap-x-1.5 py-1 px-3.5 rounded-full text-xs font-medium bg-purple-50/80 text-purple-600 dark:bg-purple-950/40 dark:text-purple-400 border border-purple-100 dark:border-purple-900/30">
                                    <span class="size-1.5 rounded-full bg-purple-600 dark:bg-purple-400"></span>
                                    {{ $typeResident }}
                                </span>
                            @else
                                <span class="inline-flex items-center gap-x-1.5 py-1 px-3.5 rounded-full text-xs font-medium bg-blue-50/80 text-blue-600 dark:bg-blue-950/40 dark:text-blue-400 border border-blue-100 dark:border-blue-900/30">
                                    <span class="size-1.5 rounded-full bg-blue-600 dark:bg-blue-400"></span>
                                    {{ $typeResident }}
                                </span>
                            @endif
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
                                <span class="inline-flex items-center gap-x-1.5 py-1 px-3 rounded-full text-xs font-medium bg-rose-50/80 text-rose-600 dark:bg-rose-950/40 dark:text-rose-400 border border-rose-100 dark:border-rose-900/30">
                                    <span class="size-1.5 rounded-full bg-rose-600 dark:bg-rose-400"></span>
                                    En retard
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                            <div class="inline-flex items-center gap-x-2">
                                <button @click="initEdit('{{ $resident->id }}', '{{ addslashes($resident->prenom) }}', '{{ addslashes($resident->nom) }}', '{{ addslashes($resident->email) }}', '{{ $resident->telephone }}', '{{ $resident->cin ?? '' }}', '{{ strtolower($typeResident) }}', '{{ $immeubleId }}', '{{ $appt ? $appt->id : '' }}', '{{ $appt ? $appt->pivot->date_entree : '' }}', '{{ addslashes($resident->notes ?? '') }}')" type="button" data-hs-overlay="#hs-modal-add-resident" class="py-1.5 px-1.5 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-950/30 rounded-lg border border-gray-200/80 hover:border-blue-300 dark:border-slate-800/80 dark:hover:border-blue-900/30 transition-all duration-200" title="Modifier">
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
                            <div class="grid sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold mb-2 dark:text-neutral-200">CIN</label>
                                    <input name="cin" x-model="residentEnCours.cin" type="text" class="py-2.5 px-4 block w-full border-gray-200 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 bg-white/50 dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-neutral-300 transition-all duration-200" placeholder="AB123456">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold mb-2 dark:text-neutral-200">Type</label>
                                    <select name="type_resident" x-model="residentEnCours.type_resident" class="py-2.5 px-4 pe-9 block w-full border-gray-200 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 bg-white/50 dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-neutral-300 transition-all duration-200">
                                        <option value="locataire">Locataire</option>
                                        <option value="propriétaire">Propriétaire</option>
                                    </select>
                                </div>
                            </div>
                            <div class="grid sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold mb-2 dark:text-neutral-200">Immeuble</label>
                                    <select name="immeuble_id" x-model="residentEnCours.immeuble_id" class="py-2.5 px-4 pe-9 block w-full border-gray-200 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 bg-white/50 dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-neutral-300 transition-all duration-200" required>
                                        <option value="">Sélectionner</option>
                                        @foreach($immeubles as $immeuble)
                                            <option value="{{ $immeuble->id }}">{{ $immeuble->nom }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold mb-2 dark:text-neutral-200">Appartement</label>
                                    <select name="appartement_id" x-model="residentEnCours.appartement_id" class="py-2.5 px-4 pe-9 block w-full border-gray-200 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 bg-white/50 dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-neutral-300 transition-all duration-200" required>
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
                                <label class="block text-sm font-semibold mb-2 dark:text-neutral-200">Date d'entrée</label>
                                <input name="date_entree" x-model="residentEnCours.date_entree" type="date" class="py-2.5 px-4 block w-full border-gray-200 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 bg-white/50 dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-neutral-300 transition-all duration-200" required>
                            </div>
                            <!-- Tiptap -->
                            <div>
                                <label class="block text-sm font-semibold mb-2 dark:text-neutral-200">Notes</label>
                                <input type="hidden" name="notes" x-model="residentEnCours.notes" style="display: none !important;">
                                <div class="bg-white border border-gray-200/80 rounded-xl overflow-hidden dark:bg-[#090D16]/50 dark:border-slate-800/80">
                                    <div id="hs-editor-tiptap">
                                        <div class="sticky top-0 bg-white flex align-middle gap-x-0.5 border-b border-gray-200/80 p-2 dark:bg-[#0D121F]/90 dark:border-slate-800/80">
                                            <button class="size-8 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg text-gray-800 hover:bg-gray-100 focus:outline-none disabled:opacity-50 dark:text-white dark:hover:bg-slate-800/50" type="button" data-hs-editor-bold>
                                                <i data-lucide="bold" class="size-4"></i>
                                            </button>
                                            <button class="size-8 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg text-gray-800 hover:bg-gray-100 focus:outline-none disabled:opacity-50 dark:text-white dark:hover:bg-slate-800/50" type="button" data-hs-editor-italic>
                                                <i data-lucide="italic" class="size-4"></i>
                                            </button>
                                            <button class="size-8 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg text-gray-800 hover:bg-gray-100 focus:outline-none disabled:opacity-50 dark:text-white dark:hover:bg-slate-800/50" type="button" data-hs-editor-underline>
                                                <i data-lucide="underline" class="size-4"></i>
                                            </button>
                                            <button class="size-8 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg text-gray-800 hover:bg-gray-100 focus:outline-none disabled:opacity-50 dark:text-white dark:hover:bg-slate-800/50" type="button" data-hs-editor-strike>
                                                <i data-lucide="strikethrough" class="size-4"></i>
                                            </button>
                                            <button class="size-8 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg text-gray-800 hover:bg-gray-100 focus:outline-none disabled:opacity-50 dark:text-white dark:hover:bg-slate-800/50" type="button" data-hs-editor-link>
                                                <i data-lucide="link" class="size-4"></i>
                                            </button>
                                            <button class="size-8 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg text-gray-800 hover:bg-gray-100 focus:outline-none disabled:opacity-50 dark:text-white dark:hover:bg-slate-800/50" type="button" data-hs-editor-ol>
                                                <i data-lucide="list-ordered" class="size-4"></i>
                                            </button>
                                            <button class="size-8 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg text-gray-800 hover:bg-gray-100 focus:outline-none disabled:opacity-50 dark:text-white dark:hover:bg-slate-800/50" type="button" data-hs-editor-ul>
                                                <i data-lucide="list" class="size-4"></i>
                                            </button>
                                            <button class="size-8 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg text-gray-800 hover:bg-gray-100 focus:outline-none disabled:opacity-50 dark:text-white dark:hover:bg-slate-800/50" type="button" data-hs-editor-blockquote>
                                                <i data-lucide="quote" class="size-4"></i>
                                            </button>
                                            <button class="size-8 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg text-gray-800 hover:bg-gray-100 focus:outline-none disabled:opacity-50 dark:text-white dark:hover:bg-slate-800/50" type="button" data-hs-editor-code>
                                                <i data-lucide="code" class="size-4"></i>
                                            </button>
                                        </div>
                                        <div class="h-40 overflow-auto p-4 focus:outline-none tiptap-content dark:text-neutral-300" data-hs-editor-field></div>
                                    </div>
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

