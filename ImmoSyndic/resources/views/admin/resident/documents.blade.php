@extends('layouts.app')

@section('content')
<div x-data="residentDocuments" class="space-y-8">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight dark:text-white">Documents de la Copropriété</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">
                Consultez et téléchargez les procès-verbaux, contrats et documents officiels de l'immeuble 
                <span class="font-bold text-primary-600 dark:text-primary-400">{{ $immeuble->nom ?? 'N/A' }}</span>.
            </p>
        </div>
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

            <!-- Custom Alpine Dropdowns (No double borders) -->
            <div class="flex items-center gap-3 relative">
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
                        <th class="px-6 py-4 text-start text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-slate-400">Taille</th>
                        <th class="px-6 py-4 text-start text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-slate-400">Date de partage</th>
                        <th class="px-6 py-4 text-end text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-slate-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200/60 dark:divide-slate-800/60">
                    @forelse($documents as $doc)
                    @php
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
                    <tr x-show="matches('{{ addslashes($doc->titre) }}', '{{ $doc->categorie }}')" class="hover:bg-gray-50/50 dark:hover:bg-slate-900/30 transition-colors duration-150">
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
                            <!-- Download button -->
                            <a href="{{ $doc->url }}" target="_blank" download class="py-1.5 px-3 inline-flex items-center gap-x-2 text-xs font-semibold rounded-xl border border-gray-200 bg-white hover:bg-gray-50 text-gray-800 dark:bg-slate-800 dark:border-slate-700 dark:text-white dark:hover:bg-slate-700 transition-all duration-200" title="Télécharger">
                                <i data-lucide="download" class="size-3.5"></i>
                                Télécharger
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500 dark:text-slate-400">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <i data-lucide="folder" class="size-10 text-gray-300 dark:text-slate-700"></i>
                                <span class="font-medium">Aucun document officiel partagé pour votre immeuble pour le moment.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
