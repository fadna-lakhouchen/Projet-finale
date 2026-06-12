{{-- Héritage du layout général de l'application --}}
@extends('layouts.app')

@section('content')
@php
    // Liste des grandes villes standard du Maroc pour le sélecteur
    $standardVilles = [
        'Casablanca', 'Rabat', 'Tanger', 'Marrakech', 'Fès', 'Meknès', 'Agadir', 'Oujda',
        'Kénitra', 'Tétouan', 'Salé', 'Nador', 'Béni Mellal', 'Mohammedia', 'El Jadida',
        'Taza', 'Settat', 'Safi', 'Laâyoune', 'Khémisset', 'Berrechid', 'Khénifra',
        'Taourirt', 'Guelmim', 'Larache', 'Khouribga', 'Berkane', 'Tiznit', 'Ifrane', 'Errachidia'
    ];
    // Fusion et élimination des doublons avec les villes déjà existantes en base
    $allVilles = array_values(array_unique(array_merge($standardVilles, $villes->toArray())));
@endphp

{{-- Scope Alpine.js lié pour la gestion dynamique des immeubles --}}
<div x-data="syndicImmeubles({ syndicNames: { @foreach($syndics as $s) '{{ $s->id }}': '{{ addslashes($s->prenom) }} {{ addslashes($s->nom) }}', @endforeach } })">
    
    {{-- En-tête de page avec titre et bouton de création d'immeuble --}}
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 dark:text-white">Mes Immeubles</h2>
            <p class="text-sm text-slate-500 dark:text-neutral-400">Gérez et supervisez les bâtiments sous votre responsabilité directe.</p>
        </div>
        {{-- Bouton déclenchant le mode création et ouvrant la modale --}}
        <button @click="initAjout()" type="button" data-hs-overlay="#hs-modal-add-immeuble" class="py-2.5 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-bold rounded-xl border border-transparent bg-gradient-to-r from-primary-600 to-purple-600 text-white hover:from-primary-700 hover:to-purple-700 shadow-md shadow-primary-500/15 transition-all glow-hover">
            <i data-lucide="plus" class="size-4.5"></i>
            Ajouter un immeuble
        </button>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-emerald-500/10 border border-emerald-500/20 text-sm text-emerald-600 dark:text-emerald-400 rounded-2xl p-4 flex items-center gap-x-2.5 shadow-sm">
        <i data-lucide="check-circle" class="size-5 shrink-0 text-emerald-500"></i>
        <span class="font-semibold">{{ session('success') }}</span>
    </div>
    @endif

    @if($errors->any())
    <div class="mb-6 bg-rose-500/10 border border-rose-500/20 text-sm text-rose-600 dark:text-rose-400 rounded-2xl p-4 flex flex-col gap-y-1 shadow-sm">
        <div class="flex items-center gap-x-2.5 font-semibold">
            <i data-lucide="alert-circle" class="size-5 shrink-0 text-rose-500"></i>
            <span>Veuillez corriger les erreurs suivantes :</span>
        </div>
        <ul class="list-disc list-inside ps-7 mt-1.5 space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Panneau d'affichage contenant la table des copropriétés --}}
    <div class="flex flex-col border border-gray-200/60 dark:border-slate-800/60 rounded-2xl shadow-premium bg-white dark:bg-[#0D121F] overflow-hidden">
        {{-- En-tête avec les filtres de recherche et de sélection de ville --}}
        <div class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-b border-gray-200/60 dark:border-slate-800/60 bg-slate-50/50 dark:bg-slate-900/30">
            {{-- Barre de recherche textuelle --}}
            <div class="sm:col-span-1 max-w-sm w-full relative">
                <input x-model="search" type="text" class="py-2.5 px-4 ps-11 block w-full border-gray-200 dark:border-slate-850 dark:bg-[#080B11] dark:text-slate-300 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500" placeholder="Nom de l'immeuble...">
                <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                    <i data-lucide="search" class="size-4 text-gray-400"></i>
                </div>
            </div>
            
            {{-- Menu déroulant de filtrage par ville --}}
            <div class="sm:col-span-2 md:grow flex justify-end gap-x-3 relative" x-data="{ openVille: false }">
                <div class="relative inline-flex">
                    <button @click="openVille = !openVille" @click.outside="openVille = false" type="button" class="py-2.5 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-xl border border-gray-200/80 bg-white/50 hover:bg-gray-50 dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-white dark:hover:bg-slate-900/50 shadow-sm transition-all duration-200">
                        <span x-text="filterVille === 'all' ? 'Filtrer par Ville' : filterVille" class="truncate max-w-[150px]"></span>
                        <i data-lucide="chevron-down" :class="openVille ? 'rotate-180' : ''" class="size-4 transition-transform duration-200 text-gray-400"></i>
                    </button>
                    <div x-show="openVille" x-cloak class="absolute right-0 top-full z-[100] mt-2 w-48 bg-white/95 dark:bg-[#0D121F]/95 border border-gray-200/60 dark:border-slate-800/60 shadow-xl rounded-xl p-1.5 backdrop-blur-md" style="display: none;">
                        <div @click="filterVille = 'all'; openVille = false" class="cursor-pointer w-full flex items-center py-2 px-3 rounded-lg text-sm text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800/50 transition-colors">Toutes les villes</div>
                        @foreach($villes as $v)
                            <div @click="filterVille = '{{ $v }}'; openVille = false" class="cursor-pointer w-full flex items-center py-2 px-3 rounded-lg text-sm text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800/50 transition-colors">{{ $v }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Tableau d'affichage des immeubles --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-250 dark:divide-slate-800">
                <thead class="bg-slate-50 dark:bg-slate-900/50">
                    <tr>
                        <th scope="col" class="px-6 py-3.5 text-start text-xs font-bold text-slate-400 uppercase dark:text-neutral-450 tracking-wider">Immeuble</th>
                        <th scope="col" class="px-6 py-3.5 text-start text-xs font-bold text-slate-400 uppercase dark:text-neutral-450 tracking-wider">Adresse complète</th>
                        <th scope="col" class="px-6 py-3.5 text-start text-xs font-bold text-slate-400 uppercase dark:text-neutral-450 tracking-wider">Résidents &amp; Appartements</th>
                        <th scope="col" class="px-6 py-3.5 text-start text-xs font-bold text-slate-400 uppercase dark:text-neutral-450 tracking-wider">Statut</th>
                        <th scope="col" class="px-6 py-3.5 text-end text-xs font-bold text-slate-400 uppercase dark:text-neutral-450 tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200/60 dark:divide-slate-800/60">
                    {{-- Boucle sur les immeubles avec filtrage local Alpine.js --}}
                    @foreach($immeubles as $immeuble)
                    <tr x-show="matches('{{ addslashes($immeuble->nom) }}', '{{ addslashes($immeuble->ville) }}', 'Actif')" class="hover:bg-slate-50/50 dark:hover:bg-slate-900/30 transition-colors">
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
                                {{-- Bouton d'édition chargeant l'immeuble ciblé dans Alpine.js et ouvrant la modale --}}
                                <button @click="initEdit('{{ $immeuble->id }}', '{{ addslashes($immeuble->nom) }}', '{{ addslashes($immeuble->adresse) }}', '{{ addslashes($immeuble->ville) }}', '{{ $immeuble->syndic_id }}', {{ $immeuble->nombre_etages }}, {{ $immeuble->nombre_appartements }}, {{ json_encode($allVilles) }})" type="button" data-hs-overlay="#hs-modal-add-immeuble" class="size-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:text-slate-300 flex items-center justify-center transition-all shadow-sm border border-gray-200/40 dark:border-slate-750">
                                    <i data-lucide="edit-2" class="size-3.5"></i>
                                </button>
                                <button @click="supprimerImmeuble('{{ $immeuble->id }}', '{{ addslashes($immeuble->nom) }}')" type="button" class="size-8 rounded-xl bg-rose-500/10 hover:bg-rose-500/25 border border-rose-500/20 text-rose-600 dark:text-rose-400 flex items-center justify-center transition-all shadow-sm">
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

    {{-- Modale d'ajout ou de modification d'immeuble --}}
    <div id="hs-modal-add-immeuble" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none" role="dialog" tabindex="-1">
        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
            <div class="w-full flex flex-col bg-white dark:bg-[#0D121F] border border-gray-200/60 dark:border-slate-800/60 shadow-premium rounded-2xl pointer-events-auto">
                <div class="flex justify-between items-center py-4 px-6 border-b border-gray-200/60 dark:border-slate-800/60">
                    <div class="flex items-center gap-x-2.5">
                        <div class="size-8 rounded-lg bg-primary-500/10 border border-primary-500/20 flex items-center justify-center text-primary-600 dark:text-primary-400">
                            <i data-lucide="building" class="size-4.5"></i>
                        </div>
                        <h3 class="font-bold text-slate-800 dark:text-white" x-text="isEditing ? 'Modifier l\'Immeuble' : 'Ajouter un Immeuble'"></h3>
                    </div>
                    <button type="button" class="size-8 inline-flex justify-center items-center rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 dark:bg-neutral-850 dark:hover:bg-neutral-800 dark:text-neutral-400 transition-all" data-hs-overlay="#hs-modal-add-immeuble">
                        <i data-lucide="x" class="size-4"></i>
                    </button>
                </div>
                
                <div class="p-6">
                    {{-- Formulaire dynamique s'adaptant à l'ajout ou la mise à jour (PUT) --}}
                    <form :action="isEditing ? `/syndic/immeubles/${immeubleEnCours.id}` : '/syndic/immeubles'" method="POST">
                        @csrf
                        <template x-if="isEditing">
                            <input type="hidden" name="_method" value="PUT">
                        </template>
                        <input type="hidden" name="id" x-model="immeubleEnCours.id">
                        
                        <div class="space-y-4">
                            <!-- Nom de l'immeuble -->
                            <div>
                                <label class="block text-sm font-semibold mb-2 text-slate-700 dark:text-slate-200">Nom de l'immeuble</label>
                                <input name="nom" x-model="immeubleEnCours.nom" type="text" class="py-2.5 px-4 block w-full border-gray-200 dark:border-slate-850 dark:bg-[#080B11] dark:text-slate-300 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500" placeholder="Résidence Al Amal" required>
                            </div>
                            
                            <!-- Assigner un Syndic responsable -->
                            <div>
                                <label class="block text-sm font-semibold mb-2 text-slate-700 dark:text-slate-200">Assigner un Syndic</label>
                                <input type="hidden" name="syndic_id" x-model="immeubleEnCours.syndic_id">
                                
                                <div class="relative" x-data="{ openFormSyndic: false }">
                                    <button @click="openFormSyndic = !openFormSyndic" @click.outside="openFormSyndic = false" type="button" 
                                        class="py-2.5 px-4 flex items-center justify-between w-full bg-white/50 dark:bg-[#080B11] dark:text-slate-300 rounded-xl text-sm transition-all duration-200 focus:outline-none">
                                        <span x-text="immeubleEnCours.syndic_id ? syndicNames[immeubleEnCours.syndic_id] : 'Choisir un syndic responsable'" class="truncate text-slate-700 dark:text-slate-300"></span>
                                        <i data-lucide="chevron-down" :class="openFormSyndic ? 'rotate-180' : ''" class="size-4 transition-transform duration-200 text-gray-400 shrink-0 ms-2"></i>
                                    </button>
                                    
                                    <div x-show="openFormSyndic" x-cloak
                                        x-transition:enter="transition ease-out duration-100"
                                        x-transition:enter-start="opacity-0 scale-95"
                                        x-transition:enter-end="opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-75"
                                        x-transition:leave-start="opacity-100 scale-100"
                                        x-transition:leave-end="opacity-0 scale-95"
                                        class="absolute left-0 right-0 z-[200] mt-2 max-h-60 overflow-y-auto bg-white/95 dark:bg-[#0D121F]/95 border border-gray-200/60 dark:border-slate-800/60 shadow-xl rounded-xl p-1.5 backdrop-blur-md">
                                        <div @click="immeubleEnCours.syndic_id = ''; openFormSyndic = false" 
                                            class="cursor-pointer w-full flex items-center py-2 px-3 rounded-lg text-sm text-gray-700 dark:text-slate-300 hover:bg-gray-150 dark:hover:bg-slate-800/50 transition-colors">
                                            Aucun (Non assigné)
                                        </div>
                                        @foreach($syndics as $s)
                                            <div @click="immeubleEnCours.syndic_id = '{{ $s->id }}'; openFormSyndic = false" 
                                                class="cursor-pointer w-full flex items-center py-2 px-3 rounded-lg text-sm text-gray-700 dark:text-slate-300 hover:bg-gray-150 dark:hover:bg-slate-800/50 transition-colors">
                                                {{ $s->prenom }} {{ $s->nom }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Ville + Adresse -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                {{-- Dropdown Ville (avec alternative de saisie libre si "Autre ville..." est choisi) --}}
                                <div>
                                    <label class="block text-sm font-semibold mb-2 text-slate-700 dark:text-slate-200">Ville</label>
                                    
                                    <!-- Select personnalisé réactif -->
                                    <div x-show="!isAddingCustomVille" class="relative" x-data="{ openFormVille: false }">
                                        <input type="hidden" name="ville" :value="immeubleEnCours.ville" :disabled="isAddingCustomVille">
                                        
                                        <button @click="openFormVille = !openFormVille" @click.outside="openFormVille = false" type="button"
                                            class="py-2.5 px-4 inline-flex items-center justify-between w-full bg-white/50 dark:bg-[#080B11] text-slate-700 dark:text-slate-300 rounded-xl text-sm transition-all duration-200 hover:bg-gray-50 dark:hover:bg-[#0a0e18] focus:outline-none">
                                            <span x-text="immeubleEnCours.ville ? immeubleEnCours.ville : 'Sélectionner une ville'" class="truncate"></span>
                                            <i data-lucide="chevron-down" :class="openFormVille ? 'rotate-180' : ''" class="size-4 transition-transform duration-200 text-gray-400 shrink-0 ms-2"></i>
                                        </button>
                                        
                                        <div x-show="openFormVille" x-cloak
                                            x-transition:enter="transition ease-out duration-100"
                                            x-transition:enter-start="opacity-0 scale-95"
                                            x-transition:enter-end="opacity-100 scale-100"
                                            x-transition:leave="transition ease-in duration-75"
                                            x-transition:leave-start="opacity-100 scale-100"
                                            x-transition:leave-end="opacity-0 scale-95"
                                            class="absolute left-0 right-0 z-[200] mt-2 max-h-60 overflow-y-auto bg-white/95 dark:bg-[#0D121F]/95 border border-gray-200/60 dark:border-slate-800/60 shadow-xl rounded-xl p-1.5 backdrop-blur-md">
                                            
                                            <div @click="immeubleEnCours.ville = ''; openFormVille = false"
                                                class="cursor-pointer w-full flex items-center py-2 px-3 rounded-lg text-sm text-gray-700 dark:text-slate-300 hover:bg-gray-150 dark:hover:bg-slate-800/50 transition-colors">
                                                Sélectionner une ville
                                            </div>
                                            
                                            @foreach($allVilles as $v)
                                                <div @click="immeubleEnCours.ville = '{{ $v }}'; openFormVille = false"
                                                    :class="immeubleEnCours.ville === '{{ $v }}' ? 'bg-primary-500/10 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-slate-300'"
                                                    class="cursor-pointer w-full flex items-center py-2 px-3 rounded-lg text-sm hover:bg-gray-150 dark:hover:bg-slate-800/50 transition-colors">
                                                    {{ $v }}
                                                </div>
                                            @endforeach
                                            
                                            <div class="my-1 border-t border-gray-200/60 dark:border-slate-800/60"></div>
                                            <div @click="enableCustomVille(); openFormVille = false"
                                                class="cursor-pointer w-full flex items-center gap-x-2 py-2 px-3 rounded-lg text-sm font-semibold text-primary-600 dark:text-primary-400 hover:bg-primary-500/10 transition-colors">
                                                <i data-lucide="plus" class="size-3.5 shrink-0"></i>
                                                Autre ville...
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Input text affiché si "Autre ville..." est activé -->
                                    <div x-show="isAddingCustomVille" class="relative">
                                        <button @click="disableCustomVille()" type="button" title="Retour à la liste" class="absolute inset-y-0 start-0 flex items-center ps-3 text-slate-400 hover:text-primary-500 dark:hover:text-primary-400 transition-colors">
                                            <i data-lucide="arrow-left" class="size-4"></i>
                                        </button>
                                        <input name="ville" x-model="customVilleInput" @input="immeubleEnCours.ville = customVilleInput" type="text"
                                            class="py-2.5 px-4 ps-10 block w-full border-gray-200 dark:border-slate-850 dark:bg-[#080B11] dark:text-slate-300 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500"
                                            placeholder="Ex: Béni Mellal" :required="isAddingCustomVille" :disabled="!isAddingCustomVille">
                                    </div>
                                </div>
                                
                                <!-- Adresse complète -->
                                <div>
                                    <label class="block text-sm font-semibold mb-2 text-slate-700 dark:text-slate-200">Adresse complète</label>
                                    <input name="adresse" x-model="immeubleEnCours.adresse" type="text" class="py-2.5 px-4 block w-full border-gray-200 dark:border-slate-850 dark:bg-[#080B11] dark:text-slate-300 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500" placeholder="14 Rue des Oliviers" required>
                                </div>
                            </div>
                            
                            <!-- Étages + Appartements -->
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
                        
                        {{-- Actions du formulaire modal --}}
                        <div class="flex justify-end items-center gap-x-3 mt-6 pt-4 border-t border-gray-200/60 dark:border-slate-800/60">
                            <button type="button" class="py-2.5 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-xl border border-gray-200 bg-white hover:bg-gray-50 text-slate-800 dark:bg-neutral-850 dark:border-neutral-800 dark:text-white dark:hover:bg-neutral-800 transition-all shadow-sm" data-hs-overlay="#hs-modal-add-immeuble">Annuler</button>
                            <button type="submit" class="py-2.5 px-4 inline-flex items-center gap-x-2 text-sm font-bold rounded-xl border border-transparent bg-gradient-to-r from-primary-600 to-purple-600 text-white hover:from-primary-700 hover:to-purple-700 shadow-md shadow-primary-500/15 transition-all glow-hover" x-text="isEditing ? 'Enregistrer les modifications' : 'Ajouter l\'immeuble'"></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- Formulaire masqué pour soumettre la suppression --}}
    <form id="delete-immeuble-form" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
</div>
@endsection

