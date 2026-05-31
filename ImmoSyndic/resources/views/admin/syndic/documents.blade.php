@extends('layouts.app')

@section('content')
<div x-data="syndicDocuments" class="space-y-8">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight dark:text-white">Documents de Copropriété</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">Gérez le coffre-fort documentaire et partagez des pièces officielles avec vos résidents.</p>
        </div>
        <button @click="initAjout()" type="button" data-hs-overlay="#hs-modal-upload-document-syndic" class="py-2.5 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-xl border border-transparent bg-gradient-to-r from-primary-600 to-purple-600 hover:from-primary-700 hover:to-purple-700 text-white shadow-md shadow-primary-500/10 hover:shadow-lg hover:shadow-primary-500/20 transition-all duration-300 transform hover:-translate-y-0.5">
            <i data-lucide="upload" class="size-4"></i>
            Déposer un Document
        </button>
    </div>

    <!-- Folders/Categories -->
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Facture Card -->
        <div @click="categorieSelectionne = categorieSelectionne === 'Facture' ? 'all' : 'Facture'" 
             :class="{'ring-2 ring-primary-500 bg-primary-500/5 dark:bg-primary-500/10 border-primary-500': categorieSelectionne === 'Facture', 'border-gray-200/60 dark:border-slate-800/60': categorieSelectionne !== 'Facture'}" 
             class="p-5 bg-white border rounded-2xl shadow-premium hover:shadow-premium-hover transition-all duration-300 dark:bg-[#0D121F] cursor-pointer group">
            <div class="flex items-center gap-x-4">
                <div class="size-11 rounded-xl bg-rose-500/10 flex items-center justify-center text-rose-600 group-hover:bg-rose-600 group-hover:text-white transition-all duration-300">
                    <i data-lucide="receipt" class="size-5"></i>
                </div>
                <div>
                    <span class="block text-sm font-bold text-slate-800 dark:text-white">Factures</span>
                    <span class="block text-xs font-semibold text-slate-400 dark:text-neutral-450 mt-0.5">{{ $documents->where('categorie', 'Facture')->count() }} fichier(s)</span>
                </div>
            </div>
        </div>

        <!-- Contrat Card -->
        <div @click="categorieSelectionne = categorieSelectionne === 'Contrat' ? 'all' : 'Contrat'" 
             :class="{'ring-2 ring-primary-500 bg-primary-500/5 dark:bg-primary-500/10 border-primary-500': categorieSelectionne === 'Contrat', 'border-gray-200/60 dark:border-slate-800/60': categorieSelectionne !== 'Contrat'}" 
             class="p-5 bg-white border rounded-2xl shadow-premium hover:shadow-premium-hover transition-all duration-300 dark:bg-[#0D121F] cursor-pointer group">
            <div class="flex items-center gap-x-4">
                <div class="size-11 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300">
                    <i data-lucide="file-text" class="size-5"></i>
                </div>
                <div>
                    <span class="block text-sm font-bold text-slate-800 dark:text-white">Contrats & Devis</span>
                    <span class="block text-xs font-semibold text-slate-400 dark:text-neutral-450 mt-0.5">{{ $documents->where('categorie', 'Contrat')->count() }} fichier(s)</span>
                </div>
            </div>
        </div>

        <!-- PV Card -->
        <div @click="categorieSelectionne = categorieSelectionne === 'PV' ? 'all' : 'PV'" 
             :class="{'ring-2 ring-primary-500 bg-primary-500/5 dark:bg-primary-500/10 border-primary-500': categorieSelectionne === 'PV', 'border-gray-200/60 dark:border-slate-800/60': categorieSelectionne !== 'PV'}" 
             class="p-5 bg-white border rounded-2xl shadow-premium hover:shadow-premium-hover transition-all duration-300 dark:bg-[#0D121F] cursor-pointer group">
            <div class="flex items-center gap-x-4">
                <div class="size-11 rounded-xl bg-purple-500/10 flex items-center justify-center text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition-all duration-300">
                    <i data-lucide="folder-open" class="size-5"></i>
                </div>
                <div>
                    <span class="block text-sm font-bold text-slate-800 dark:text-white">Procès-Verbaux</span>
                    <span class="block text-xs font-semibold text-slate-400 dark:text-neutral-450 mt-0.5">{{ $documents->where('categorie', 'PV')->count() }} fichier(s)</span>
                </div>
            </div>
        </div>

        <!-- Autre Card -->
        <div @click="categorieSelectionne = categorieSelectionne === 'Autre' ? 'all' : 'Autre'" 
             :class="{'ring-2 ring-primary-500 bg-primary-500/5 dark:bg-primary-500/10 border-primary-500': categorieSelectionne === 'Autre', 'border-gray-200/60 dark:border-slate-800/60': categorieSelectionne !== 'Autre'}" 
             class="p-5 bg-white border rounded-2xl shadow-premium hover:shadow-premium-hover transition-all duration-300 dark:bg-[#0D121F] cursor-pointer group">
            <div class="flex items-center gap-x-4">
                <div class="size-11 rounded-xl bg-amber-500/10 flex items-center justify-center text-amber-600 group-hover:bg-amber-600 group-hover:text-white transition-all duration-300">
                    <i data-lucide="files" class="size-5"></i>
                </div>
                <div>
                    <span class="block text-sm font-bold text-slate-800 dark:text-white">Autres</span>
                    <span class="block text-xs font-semibold text-slate-400 dark:text-neutral-450 mt-0.5">{{ $documents->where('categorie', 'Autre')->count() }} fichier(s)</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Container -->
    <div class="flex flex-col bg-white/85 dark:bg-[#0D121F]/90 border border-gray-200/60 dark:border-slate-800/60 rounded-2xl shadow-premium backdrop-blur-md overflow-hidden">
        
        <!-- Filters Bar -->
        <div class="px-6 py-5 grid gap-4 md:flex md:justify-between md:items-center border-b border-gray-200/60 dark:border-slate-800/60 bg-white/40 dark:bg-[#0D121F]/40">
            <!-- Search bar -->
            <div class="sm:col-span-1 max-w-sm w-full relative">
                <input x-model="search" type="text" class="py-2.5 px-4 ps-11 block w-full border-gray-200 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 bg-white/50 dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-neutral-300 dark:placeholder-neutral-500 transition-all duration-200" placeholder="Rechercher un document...">
                <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                    <i data-lucide="search" class="size-4 text-gray-400 dark:text-neutral-500"></i>
                </div>
            </div>

            <!-- Select selectors (Alpine custom dropdowns) -->
            <div class="flex flex-wrap items-center gap-3 relative">
                <!-- Immeuble Filter -->
                <div class="relative">
                    <button @click="openImm = !openImm" @click.outside="openImm = false" type="button" class="py-2.5 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-xl border border-gray-200 bg-white/50 hover:bg-gray-50 dark:bg-[#090D16]/50 dark:border-slate-800 dark:text-white dark:hover:bg-slate-900/50 shadow-sm transition-all duration-200">
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

                <!-- Categorie Filter -->
                <div class="relative">
                    <button @click="openCat = !openCat" @click.outside="openCat = false" type="button" class="py-2.5 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-xl border border-gray-200 bg-white/50 hover:bg-gray-50 dark:bg-[#090D16]/50 dark:border-slate-800 dark:text-white dark:hover:bg-slate-900/50 shadow-sm transition-all duration-200">
                        <span x-text="categorieSelectionne === 'all' ? 'Filtrer par Catégorie' : (categorieSelectionne === 'PV' ? 'PV (Procès-Verbal)' : categorieSelectionne)" class="truncate max-w-[150px]"></span>
                        <i data-lucide="chevron-down" class="size-4 text-gray-400 transition-transform duration-200" :class="{'rotate-180': openCat}"></i>
                    </button>
                    <div x-show="openCat" x-cloak class="absolute right-0 top-full z-[100] mt-2 w-56 bg-white/95 dark:bg-[#0D121F]/95 border border-gray-200/60 dark:border-slate-800/60 shadow-xl rounded-xl p-1.5 backdrop-blur-md" style="display: none;">
                        <div @click="categorieSelectionne = 'all'; openCat = false" class="cursor-pointer py-2 px-3 rounded-lg text-sm text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800/50 transition-colors">Toutes les catégories</div>
                        <div @click="categorieSelectionne = 'Facture'; openCat = false" class="cursor-pointer py-2 px-3 rounded-lg text-sm text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800/50 transition-colors">Facture</div>
                        <div @click="categorieSelectionne = 'Contrat'; openCat = false" class="cursor-pointer py-2 px-3 rounded-lg text-sm text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800/50 transition-colors">Contrat & Devis</div>
                        <div @click="categorieSelectionne = 'PV'; openCat = false" class="cursor-pointer py-2 px-3 rounded-lg text-sm text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800/50 transition-colors">PV (Procès-Verbal)</div>
                        <div @click="categorieSelectionne = 'Autre'; openCat = false" class="cursor-pointer py-2 px-3 rounded-lg text-sm text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800/50 transition-colors">Autre</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table View -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200/60 dark:divide-slate-800/60">
                <thead class="bg-gray-50/50 dark:bg-[#090D16]/40">
                    <tr>
                        <th class="px-6 py-4 text-start text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-slate-400">Nom du Fichier</th>
                        <th class="px-6 py-4 text-start text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-slate-400">Catégorie</th>
                        <th class="px-6 py-4 text-start text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-slate-400">Immeuble</th>
                        <th class="px-6 py-4 text-start text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-slate-400">Taille</th>
                        <th class="px-6 py-4 text-start text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-slate-400">Date d'ajout</th>
                        <th class="px-6 py-4 text-end text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-slate-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200/60 dark:divide-slate-800/60">
                    @forelse($documents as $doc)
                    @php
                        $immeubleNom = $doc->immeuble ? $doc->immeuble->nom : 'N/A';
                        $iconMap = [
                            'Facture' => 'receipt',
                            'Contrat' => 'file-text',
                            'PV' => 'folder-open',
                            'Autre' => 'files'
                        ];
                        $colorMap = [
                            'Facture' => 'rose',
                            'Contrat' => 'blue',
                            'PV' => 'purple',
                            'Autre' => 'amber'
                        ];
                        $icon = $iconMap[$doc->categorie] ?? 'file';
                        $color = $colorMap[$doc->categorie] ?? 'primary';
                        
                        $fileSize = 'N/A';
                        if ($doc->fichier_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($doc->fichier_path)) {
                            $bytes = \Illuminate\Support\Facades\Storage::disk('public')->size($doc->fichier_path);
                            $fileSize = round($bytes / (1024 * 1024), 2) . ' MB';
                        }
                    @endphp
                    <tr x-show="matches('{{ addslashes($doc->titre) }}', '{{ addslashes($immeubleNom) }}', '{{ $doc->categorie }}')" class="hover:bg-gray-50/50 dark:hover:bg-slate-900/30 transition-colors duration-150">
                        <!-- Nom du Fichier -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-x-3">
                                <div class="size-9 rounded-lg bg-{{ $color }}-500/10 flex items-center justify-center text-{{ $color }}-600 dark:bg-{{ $color }}-500/20">
                                    <i data-lucide="{{ $icon }}" class="size-4.5"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm font-semibold text-gray-800 dark:text-slate-200">{{ $doc->titre }}</span>
                                    <span class="text-[10px] text-gray-400 dark:text-slate-500 font-mono tracking-tight line-clamp-1 mt-0.5">{{ basename($doc->fichier_path) }}</span>
                                </div>
                            </div>
                        </td>
                        
                        <!-- Categorie -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center gap-x-1.5 py-1 px-2.5 rounded-full text-xs font-semibold bg-{{ $color }}-50 text-{{ $color }}-600 dark:bg-{{ $color }}-950/40 dark:text-{{ $color }}-400 border border-{{ $color }}-100 dark:border-{{ $color }}-900/20">
                                {{ $doc->categorie === 'PV' ? 'PV (Procès-Verbal)' : $doc->categorie }}
                            </span>
                        </td>

                        <!-- Immeuble -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-slate-300">
                            {{ $immeubleNom }}
                        </td>

                        <!-- Taille -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-slate-400 font-mono">
                            {{ $fileSize }}
                        </td>

                        <!-- Date d'ajout -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-slate-400">
                            {{ $doc->created_at->translatedFormat('d F Y') }}
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                            <div class="inline-flex items-center gap-x-2">
                                <!-- Download -->
                                <a href="{{ $doc->url }}" target="_blank" download class="py-1.5 px-1.5 text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-950/30 rounded-lg border border-gray-200/80 hover:border-primary-300 dark:border-slate-800/80 dark:hover:border-primary-900/30 transition-all duration-200" title="Télécharger">
                                    <i data-lucide="download" class="size-4"></i>
                                </a>
                                
                                <!-- Delete -->
                                <form action="{{ route('syndic.documents.destroy', $doc->id) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer définitivement ce document ?');" class="inline-block">
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
                        <td colspan="6" class="px-6 py-12 text-center text-sm text-gray-500 dark:text-slate-400">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <i data-lucide="folder" class="size-10 text-gray-300 dark:text-slate-700"></i>
                                <span class="font-medium">Aucun document stocké pour le moment.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Form (Upload Document Syndic) -->
    <div id="hs-modal-upload-document-syndic" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none bg-slate-950/40 backdrop-blur-sm" role="dialog" tabindex="-1">
        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
            <div class="w-full flex flex-col bg-white border border-gray-200/60 dark:border-slate-800/60 shadow-premium rounded-2xl pointer-events-auto dark:bg-[#0D121F]">
                
                <!-- Modal Header -->
                <div class="flex justify-between items-center py-4 px-5 border-b border-gray-200/60 dark:border-slate-800/60">
                    <h3 class="font-bold text-gray-800 dark:text-white text-lg">Déposer un Document</h3>
                    <button type="button" class="size-8 inline-flex justify-center items-center rounded-xl bg-gray-100 text-gray-800 hover:bg-gray-200 dark:bg-slate-800 dark:text-neutral-400 dark:hover:bg-slate-700 transition-colors" data-hs-overlay="#hs-modal-upload-document-syndic">
                        <i data-lucide="x" class="size-4"></i>
                    </button>
                </div>
                
                <!-- Modal Body -->
                <div class="p-6">
                    <form action="{{ route('syndic.documents.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="grid gap-y-4">
                            <!-- Titre -->
                            <div>
                                <label class="block text-sm font-semibold mb-2 dark:text-neutral-200">Titre du document</label>
                                <input name="titre" x-model="documentEnCours.titre" type="text" class="py-2.5 px-4 block w-full border border-gray-200 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 bg-white/50 dark:bg-[#090D16]/50 dark:border-slate-800/85 dark:text-neutral-300 transition-all duration-200" placeholder="Ex: Contrat d'entretien Ascenseur..." required>
                            </div>

                            <!-- Immeuble Selector (Custom Alpine Dropdown button) -->
                            <div>
                                <label class="block text-sm font-semibold mb-2 dark:text-neutral-200">Immeuble</label>
                                <div class="relative">
                                    <button @click="openFormImm = !openFormImm" @click.outside="openFormImm = false" type="button" class="py-2.5 px-4 flex justify-between items-center w-full border border-gray-200 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 bg-white/50 dark:bg-[#090D16]/50 dark:border-slate-800/85 dark:text-neutral-300 transition-all duration-200 text-left">
                                        <span x-text="documentEnCours.immeuble_id ? ({ @foreach($immeubles as $imm) '{{ $imm->id }}': '{{ addslashes($imm->nom) }}', @endforeach }[documentEnCours.immeuble_id] || 'Sélectionner l\'immeuble') : 'Sélectionner l\'immeuble'"></span>
                                        <i data-lucide="chevron-down" class="size-4 text-gray-400 transition-transform duration-200" :class="{'rotate-180': openFormImm}"></i>
                                    </button>
                                    <input type="hidden" name="immeuble_id" :value="documentEnCours.immeuble_id" required>
                                    <div x-show="openFormImm" x-cloak class="absolute left-0 top-full z-[100] mt-2 w-full max-h-60 overflow-y-auto bg-white dark:bg-[#0D121F] border border-gray-200/60 dark:border-slate-800/60 shadow-xl rounded-xl p-1.5 backdrop-blur-md" style="display: none;">
                                        @foreach($immeubles as $immeuble)
                                            <div @click="documentEnCours.immeuble_id = '{{ $immeuble->id }}'; openFormImm = false" class="cursor-pointer py-2 px-3 rounded-lg text-sm text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800/50 transition-colors">{{ $immeuble->nom }}</div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Catégorie Selector (Custom Alpine Dropdown button) -->
                            <div>
                                <label class="block text-sm font-semibold mb-2 dark:text-neutral-200">Catégorie</label>
                                <div class="relative">
                                    <button @click="openFormCat = !openFormCat" @click.outside="openFormCat = false" type="button" class="py-2.5 px-4 flex justify-between items-center w-full border border-gray-200 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 bg-white/50 dark:bg-[#090D16]/50 dark:border-slate-800/85 dark:text-neutral-300 transition-all duration-200 text-left">
                                        <span x-text="documentEnCours.categorie ? (documentEnCours.categorie === 'PV' ? 'PV (Procès-Verbal)' : documentEnCours.categorie) : 'Sélectionner une catégorie'"></span>
                                        <i data-lucide="chevron-down" class="size-4 text-gray-400 transition-transform duration-200" :class="{'rotate-180': openFormCat}"></i>
                                    </button>
                                    <input type="hidden" name="categorie" :value="documentEnCours.categorie" required>
                                    <div x-show="openFormCat" x-cloak class="absolute left-0 top-full z-[100] mt-2 w-full bg-white dark:bg-[#0D121F] border border-gray-200/60 dark:border-slate-800/60 shadow-xl rounded-xl p-1.5 backdrop-blur-md" style="display: none;">
                                        <div @click="documentEnCours.categorie = 'Facture'; openFormCat = false" class="cursor-pointer py-2 px-3 rounded-lg text-sm text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800/50 transition-colors">Facture</div>
                                        <div @click="documentEnCours.categorie = 'Contrat'; openFormCat = false" class="cursor-pointer py-2 px-3 rounded-lg text-sm text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800/50 transition-colors">Contrat & Devis</div>
                                        <div @click="documentEnCours.categorie = 'PV'; openFormCat = false" class="cursor-pointer py-2 px-3 rounded-lg text-sm text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800/50 transition-colors">PV (Procès-Verbal)</div>
                                        <div @click="documentEnCours.categorie = 'Autre'; openFormCat = false" class="cursor-pointer py-2 px-3 rounded-lg text-sm text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800/50 transition-colors">Autre</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Fichier Upload -->
                            <div>
                                <label class="block text-sm font-semibold mb-2 dark:text-neutral-200">Fichier (PDF, Image, Doc - Max 20MB)</label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-slate-800 border-dashed rounded-xl hover:bg-gray-50/50 dark:hover:bg-slate-900/10 transition-colors cursor-pointer relative group">
                                    <div class="space-y-1 text-center">
                                        <div class="flex justify-center text-gray-400 group-hover:scale-105 transition-transform duration-200">
                                            <i data-lucide="file-up" class="size-10 text-primary-500"></i>
                                        </div>
                                        <div class="flex text-sm text-gray-600 dark:text-slate-400 justify-center">
                                            <label for="file-upload-syndic" class="relative cursor-pointer rounded-md font-semibold text-primary-600 hover:text-primary-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary-500">
                                                <span>Télécharger un fichier</span>
                                                <input id="file-upload-syndic" name="fichier" type="file" class="sr-only" required onchange="document.getElementById('fileNameSpanSyndic').innerText = this.files[0] ? this.files[0].name : 'Aucun fichier sélectionné'">
                                            </label>
                                        </div>
                                        <p class="text-xs text-gray-500 dark:text-slate-500">PDF, PNG, JPG, DOC, XLS jusqu'à 20 Mo</p>
                                        <div id="fileNameDisplaySyndic" class="mt-2 text-xs font-semibold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/20 py-1 px-2 rounded-lg inline-block">
                                            <span id="fileNameSpanSyndic">Aucun fichier sélectionné</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="flex justify-end items-center gap-x-3 mt-6 border-t border-gray-100 dark:border-slate-800/60 pt-4">
                            <button type="button" class="py-2 px-4 text-sm font-medium border border-gray-200 dark:border-slate-800 dark:text-neutral-300 hover:bg-gray-50 dark:hover:bg-slate-900 rounded-xl transition-colors duration-150" data-hs-overlay="#hs-modal-upload-document-syndic">Annuler</button>
                            <button type="submit" class="py-2 px-4 text-sm font-semibold bg-gradient-to-r from-primary-600 to-purple-600 hover:from-primary-700 hover:to-purple-700 text-white rounded-xl shadow-md shadow-primary-500/10 transition-all duration-300">Valider & Déposer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
