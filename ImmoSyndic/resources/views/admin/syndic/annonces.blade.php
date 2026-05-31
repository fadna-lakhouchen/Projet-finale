@extends('layouts.app')

@section('content')
<div x-data="syndicAnnonces">
    <!-- Header -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight dark:text-white">Gestion des Annonces</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">Publiez des annonces et avertissements pour informer instantanément les résidents de vos immeubles.</p>
        </div>
        <button @click="initAjout()" type="button" data-hs-overlay="#hs-modal-add-annonce" class="py-2.5 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-xl border border-transparent bg-gradient-to-r from-primary-600 to-purple-600 hover:from-primary-700 hover:to-purple-700 text-white shadow-md shadow-primary-500/10 hover:shadow-lg hover:shadow-primary-500/20 transition-all duration-300 transform hover:-translate-y-0.5">
            <i data-lucide="megaphone" class="size-4"></i>
            Publier une Annonce
        </button>
    </div>

    <!-- Table & Filters Container -->
    <div class="flex flex-col bg-white/80 dark:bg-[#0D121F]/90 border border-gray-200/60 dark:border-slate-800/60 rounded-2xl shadow-premium backdrop-blur-md overflow-hidden">
        <!-- Filters -->
        <div class="px-6 py-5 grid gap-4 md:flex md:justify-between md:items-center border-b border-gray-200/60 dark:border-slate-800/60 bg-white/40 dark:bg-[#0D121F]/40">
            <div class="sm:col-span-1 max-w-sm w-full relative">
                <input x-model="search" type="text" class="py-2.5 px-4 ps-11 block w-full border-gray-200/80 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 bg-white/50 dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-neutral-300 dark:placeholder-neutral-500 transition-all duration-200" placeholder="Rechercher une annonce...">
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
                        <th class="px-6 py-4 text-start text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-slate-400">Annonce</th>
                        <th class="px-6 py-4 text-start text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-slate-400">Immeuble ciblé</th>
                        <th class="px-6 py-4 text-start text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-slate-400">Date de publication</th>
                        <th class="px-6 py-4 text-end text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-slate-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200/60 dark:divide-slate-800/60">
                    @forelse($annonces as $annonce)
                    @php
                        $immeubleNom = $annonce->immeuble ? $annonce->immeuble->nom : 'Tous';
                        $immeubleId = $annonce->immeuble ? $annonce->immeuble->id : '';
                    @endphp
                    <tr x-show="matches('{{ addslashes($annonce->titre) }}', '{{ addslashes($immeubleNom) }}')" class="hover:bg-gray-50/50 dark:hover:bg-slate-900/30 transition-colors duration-150">
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-sm font-semibold text-gray-800 dark:text-slate-200">{{ $annonce->titre }}</span>
                                <span class="text-xs text-gray-550 dark:text-slate-400 line-clamp-1 mt-0.5">{{ $annonce->contenu }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center gap-x-1.5 py-1 px-3 rounded-full text-xs font-medium bg-primary-50/80 text-primary-600 dark:bg-primary-950/40 dark:text-primary-400 border border-primary-100 dark:border-primary-900/30">
                                <span class="size-1.5 rounded-full bg-primary-600 dark:bg-primary-400"></span>
                                {{ $immeubleNom }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-slate-400">
                            {{ \Carbon\Carbon::parse($annonce->date_publication)->translatedFormat('d F Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                            <div class="inline-flex items-center gap-x-2">
                                <button @click="initEdit('{{ $annonce->id }}', '{{ addslashes($annonce->titre) }}', '{{ addslashes($annonce->contenu) }}', '{{ $immeubleId }}')" type="button" data-hs-overlay="#hs-modal-add-annonce" class="py-1.5 px-1.5 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-950/30 rounded-lg border border-gray-200/80 hover:border-blue-300 dark:border-slate-800/80 dark:hover:border-blue-900/30 transition-all duration-200" title="Modifier">
                                    <i data-lucide="edit-2" class="size-4"></i>
                                </button>
                                <form action="{{ route('syndic.annonces.destroy', $annonce->id) }}" method="POST" onsubmit="return confirm('Supprimer définitivement cette annonce ?');" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="py-1.5 px-1.5 text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/30 rounded-lg border border-gray-200/80 hover:border-rose-300 dark:border-slate-800/80 dark:hover:border-rose-900/30 transition-all duration-200" title="Supprimer">
                                        <i data-lucide="trash-2" class="size-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-sm text-gray-500 dark:text-slate-400">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <i data-lucide="megaphone" class="size-8 text-gray-300 dark:text-slate-600"></i>
                                <span>Aucune annonce publiée pour le moment.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Form (Add/Edit Annonce) -->
    <div id="hs-modal-add-annonce" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none bg-slate-950/40 backdrop-blur-sm" role="dialog" tabindex="-1">
        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
            <div class="w-full flex flex-col bg-white border border-gray-200/60 dark:border-slate-800/60 shadow-premium rounded-2xl pointer-events-auto dark:bg-[#0D121F]">
                <div class="flex justify-between items-center py-4 px-5 border-b border-gray-200/60 dark:border-slate-800/60">
                    <h3 class="font-bold text-gray-800 dark:text-white text-lg" x-text="isEditing ? 'Modifier l\'Annonce' : 'Publier une Annonce'"></h3>
                    <button type="button" class="size-8 inline-flex justify-center items-center rounded-xl bg-gray-100 text-gray-800 hover:bg-gray-200 dark:bg-slate-800 dark:text-neutral-400 dark:hover:bg-slate-700 transition-colors" data-hs-overlay="#hs-modal-add-annonce">
                        <i data-lucide="x" class="size-4"></i>
                    </button>
                </div>
                <div class="p-6 overflow-y-auto">
                    <form :action="isEditing ? `/syndic/annonces/${annonceEnCours.id}` : '{{ route('syndic.annonces.store') }}'" method="POST">
                        @csrf
                        <template x-if="isEditing">
                            <input type="hidden" name="_method" value="PUT">
                        </template>
                        <input type="hidden" name="id" x-model="annonceEnCours.id">
                        
                        <div class="grid gap-y-4">
                            <div>
                                <label class="block text-sm font-semibold mb-2 dark:text-neutral-200">Titre de l'annonce</label>
                                <input name="titre" x-model="annonceEnCours.titre" type="text" class="py-2.5 px-4 block w-full border border-gray-200 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 bg-white/50 dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-neutral-300 transition-all duration-200" placeholder="Ex: Panne générale d'eau..." required>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold mb-2 dark:text-neutral-200">Immeuble ciblé</label>
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" @click.outside="open = false" type="button" class="py-2.5 px-4 flex justify-between items-center w-full border border-gray-200 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 bg-white/50 dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-neutral-300 transition-all duration-200 text-left">
                                        <span x-text="annonceEnCours.immeuble_id ? ({ @foreach($immeubles as $imm) '{{ $imm->id }}': '{{ addslashes($imm->nom) }}', @endforeach }[annonceEnCours.immeuble_id] || 'Sélectionner l\'immeuble') : 'Sélectionner l\'immeuble'"></span>
                                        <i data-lucide="chevron-down" class="size-4 text-gray-400 transition-transform duration-200" :class="{'rotate-180': open}"></i>
                                    </button>
                                    <input type="hidden" name="immeuble_id" :value="annonceEnCours.immeuble_id">
                                    <div x-show="open" x-cloak class="absolute left-0 top-full z-[100] mt-2 w-full max-h-60 overflow-y-auto bg-white dark:bg-[#0D121F] border border-gray-200/60 dark:border-slate-800/60 shadow-xl rounded-xl p-1.5 backdrop-blur-md" style="display: none;">
                                        @foreach($immeubles as $immeuble)
                                            <div @click="annonceEnCours.immeuble_id = '{{ $immeuble->id }}'; open = false" class="cursor-pointer py-2 px-3 rounded-lg text-sm text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800/50 transition-colors">{{ $immeuble->nom }}</div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold mb-2 dark:text-neutral-200">Description / Contenu</label>
                                <textarea name="contenu" x-model="annonceEnCours.contenu" rows="5" class="py-2.5 px-4 block w-full border border-gray-200 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 bg-white/50 dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-neutral-300 transition-all duration-200" placeholder="Décrivez le contenu détaillé de l'annonce..." required></textarea>
                            </div>
                        </div>

                        <div class="flex justify-end items-center gap-x-3 mt-6 border-t border-gray-100 dark:border-slate-800/60 pt-4">
                            <button type="button" class="py-2 px-4 text-sm font-medium border border-gray-200 dark:border-slate-800 dark:text-neutral-300 hover:bg-gray-50 dark:hover:bg-slate-900 rounded-xl transition-colors duration-150" data-hs-overlay="#hs-modal-add-annonce">Annuler</button>
                            <button type="submit" class="py-2 px-4 text-sm font-semibold bg-gradient-to-r from-primary-600 to-purple-600 hover:from-primary-700 hover:to-purple-700 text-white rounded-xl shadow-md shadow-primary-500/10 transition-all duration-300" x-text="isEditing ? 'Enregistrer les modifications' : 'Publier'"></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
