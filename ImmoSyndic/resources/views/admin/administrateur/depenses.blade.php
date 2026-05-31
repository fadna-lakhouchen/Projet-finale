@extends('layouts.app')

@section('content')
<div x-data="adminDepenses" class="space-y-8">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight dark:text-white">Dépenses de Copropriété</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">Suivi comptable global et contrôle de toutes les sorties financières de toutes les copropriétés.</p>
        </div>
        <button @click="initAjout()" type="button" data-hs-overlay="#hs-modal-add-depense" class="py-2.5 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-xl border border-transparent bg-gradient-to-r from-primary-600 to-purple-600 hover:from-primary-700 hover:to-purple-700 text-white shadow-md shadow-primary-500/10 hover:shadow-lg hover:shadow-primary-500/20 transition-all duration-300 transform hover:-translate-y-0.5">
            <i data-lucide="plus" class="size-4"></i>
            Enregistrer une Dépense
        </button>
    </div>

    <!-- Summary Card -->
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="p-6 bg-gradient-to-br from-primary-600 to-indigo-600 rounded-2xl shadow-premium text-white relative overflow-hidden group">
            <div class="absolute right-0 bottom-0 translate-y-6 translate-x-6 text-white/10 group-hover:scale-110 transition-transform duration-500">
                <i data-lucide="wallet" class="size-36"></i>
            </div>
            <span class="block text-xs font-bold uppercase tracking-widest text-primary-200">Budget Global Dépensé</span>
            <span class="block text-3xl font-black mt-2 tracking-tight">{{ number_format($depenses->sum('montant'), 2) }} DH</span>
            <span class="block text-xs text-primary-100/80 mt-2 font-medium">Cumulé sur {{ $depenses->count() }} opération(s) enregistrée(s)</span>
        </div>
        
        <div class="p-6 bg-white border border-gray-200/60 rounded-2xl shadow-premium dark:bg-[#0D121F] dark:border-slate-800/60 flex items-center gap-x-4">
            <div class="size-12 rounded-xl bg-purple-500/10 flex items-center justify-center text-purple-600 dark:bg-purple-950/20">
                <i data-lucide="building" class="size-6"></i>
            </div>
            <div>
                <span class="block text-xs font-bold text-slate-400 dark:text-neutral-450 uppercase tracking-wider">Immeubles Concernés</span>
                <span class="block text-2xl font-extrabold text-slate-850 dark:text-white mt-1">{{ $depenses->pluck('immeuble_id')->unique()->count() }} / {{ $immeubles->count() }}</span>
            </div>
        </div>

        <div class="p-6 bg-white border border-gray-200/60 rounded-2xl shadow-premium dark:bg-[#0D121F] dark:border-slate-800/60 flex items-center gap-x-4">
            <div class="size-12 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-600 dark:bg-emerald-950/20">
                <i data-lucide="file-check" class="size-6"></i>
            </div>
            <div>
                <span class="block text-xs font-bold text-slate-400 dark:text-neutral-450 uppercase tracking-wider">Avec Justificatif</span>
                <span class="block text-2xl font-extrabold text-slate-850 dark:text-white mt-1">{{ $depenses->whereNotNull('justificatif_path')->count() }} fichier(s)</span>
            </div>
        </div>
    </div>

    <!-- Table Container -->
    <div class="flex flex-col bg-white/85 dark:bg-[#0D121F]/90 border border-gray-200/60 dark:border-slate-800/60 rounded-2xl shadow-premium backdrop-blur-md overflow-hidden">
        
        <!-- Filters Bar -->
        <div class="px-6 py-5 grid gap-4 md:flex md:justify-between md:items-center border-b border-gray-200/60 dark:border-slate-800/60 bg-white/40 dark:bg-[#0D121F]/40">
            <!-- Search bar -->
            <div class="sm:col-span-1 max-w-sm w-full relative">
                <input x-model="search" type="text" class="py-2.5 px-4 ps-11 block w-full border-gray-200 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 bg-white/50 dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-neutral-300 dark:placeholder-neutral-500 transition-all duration-200" placeholder="Rechercher par titre...">
                <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                    <i data-lucide="search" class="size-4 text-gray-400 dark:text-neutral-500"></i>
                </div>
            </div>

            <!-- Custom Alpine Dropdowns (No double borders) -->
            <div class="flex items-center gap-3 relative">
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
            </div>
        </div>

        <!-- Table View -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200/60 dark:divide-slate-800/60">
                <thead class="bg-gray-50/50 dark:bg-[#090D16]/40">
                    <tr>
                        <th class="px-6 py-4 text-start text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-slate-400">Désignation Dépense</th>
                        <th class="px-6 py-4 text-start text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-slate-400">Immeuble</th>
                        <th class="px-6 py-4 text-start text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-slate-400">Montant</th>
                        <th class="px-6 py-4 text-start text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-slate-400">Date d'opération</th>
                        <th class="px-6 py-4 text-start text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-slate-400">Justificatif</th>
                        <th class="px-6 py-4 text-end text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-slate-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200/60 dark:divide-slate-800/60">
                    @forelse($depenses as $depense)
                    @php
                        $immeubleNom = $depense->immeuble ? $depense->immeuble->nom : 'N/A';
                    @endphp
                    <tr x-show="matches('{{ addslashes($depense->titre) }}', '{{ addslashes($immeubleNom) }}')" class="hover:bg-gray-50/50 dark:hover:bg-slate-900/30 transition-colors duration-150">
                        <!-- Désignation -->
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-sm font-semibold text-gray-800 dark:text-slate-200">{{ $depense->titre }}</span>
                                <span class="text-xs text-gray-400 dark:text-slate-500 line-clamp-1 mt-0.5">{{ $depense->description ?? 'Aucune description fournie.' }}</span>
                            </div>
                        </td>

                        <!-- Immeuble -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-slate-300">
                            {{ $immeubleNom }}
                        </td>

                        <!-- Montant -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-rose-600 dark:text-rose-450">
                            - {{ number_format($depense->montant, 2) }} DH
                        </td>

                        <!-- Date -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-slate-400">
                            {{ \Carbon\Carbon::parse($depense->date_depense)->translatedFormat('d F Y') }}
                        </td>

                        <!-- Justificatif -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($depense->justificatif_path)
                                <a href="{{ $depense->justificatif_url }}" target="_blank" class="inline-flex items-center gap-x-1 py-1 px-2.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/20 hover:bg-emerald-100/50 dark:hover:bg-emerald-900/40 transition-colors">
                                    <i data-lucide="receipt" class="size-3.5"></i>
                                    Voir Reçu
                                </a>
                            @else
                                <span class="text-xs text-gray-400 dark:text-slate-600 italic">Non fourni</span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                            <form action="{{ route('admin.depenses.destroy', $depense->id) }}" method="POST" onsubmit="return confirm('Voulez-vous supprimer cette dépense ? Cette action supprimera définitivement le justificatif sur le disque.');" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="py-1.5 px-1.5 text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/30 rounded-lg border border-gray-200/80 hover:border-rose-300 dark:border-slate-800/80 dark:hover:border-rose-900/30 transition-all duration-200" title="Supprimer">
                                    <i data-lucide="trash-2" class="size-4"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-sm text-gray-500 dark:text-slate-400">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <i data-lucide="receipt" class="size-10 text-gray-300 dark:text-slate-700"></i>
                                <span class="font-medium">Aucune dépense enregistrée pour le moment.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Form (Add Depense) -->
    <div id="hs-modal-add-depense" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none bg-slate-950/40 backdrop-blur-sm" role="dialog" tabindex="-1">
        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
            <div class="w-full flex flex-col bg-white border border-gray-200/60 dark:border-slate-800/60 shadow-premium rounded-2xl pointer-events-auto dark:bg-[#0D121F]">
                
                <!-- Modal Header -->
                <div class="flex justify-between items-center py-4 px-5 border-b border-gray-200/60 dark:border-slate-800/60">
                    <h3 class="font-bold text-gray-800 dark:text-white text-lg">Enregistrer une Dépense</h3>
                    <button type="button" class="size-8 inline-flex justify-center items-center rounded-xl bg-gray-100 text-gray-800 hover:bg-gray-200 dark:bg-slate-800 dark:text-neutral-400 dark:hover:bg-slate-700 transition-colors" data-hs-overlay="#hs-modal-add-depense">
                        <i data-lucide="x" class="size-4"></i>
                    </button>
                </div>
                
                <!-- Modal Body -->
                <div class="p-6">
                    <form action="{{ route('admin.depenses.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="grid gap-y-4">
                            <!-- Titre -->
                            <div>
                                <label class="block text-sm font-semibold mb-2 dark:text-neutral-200">Désignation / Titre de la dépense</label>
                                <input name="titre" x-model="depenseEnCours.titre" type="text" class="py-2.5 px-4 block w-full border border-gray-200 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 bg-white/50 dark:bg-[#090D16]/50 dark:border-slate-800/85 dark:text-neutral-300 transition-all duration-200" placeholder="Ex: Achat d'une ampoule LED ou réparation..." required>
                            </div>

                            <!-- Immeuble Selector (Custom Alpine Dropdown) -->
                            <div>
                                <label class="block text-sm font-semibold mb-2 dark:text-neutral-200">Immeuble concerné</label>
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" @click.outside="open = false" type="button" class="py-2.5 px-4 flex justify-between items-center w-full border border-gray-200 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 bg-white/50 dark:bg-[#090D16]/50 dark:border-slate-800/85 dark:text-neutral-300 transition-all duration-200 text-left">
                                        <span x-text="depenseEnCours.immeuble_id ? ({ @foreach($immeubles as $imm) '{{ $imm->id }}': '{{ addslashes($imm->nom) }}', @endforeach }[depenseEnCours.immeuble_id] || 'Sélectionner l\'immeuble') : 'Sélectionner l\'immeuble'"></span>
                                        <i data-lucide="chevron-down" class="size-4 text-gray-400 transition-transform duration-200" :class="{'rotate-180': open}"></i>
                                    </button>
                                    <input type="hidden" name="immeuble_id" :value="depenseEnCours.immeuble_id" required>
                                    <div x-show="open" x-cloak class="absolute left-0 top-full z-[100] mt-2 w-full max-h-60 overflow-y-auto bg-white dark:bg-[#0D121F] border border-gray-200/60 dark:border-slate-800/60 shadow-xl rounded-xl p-1.5 backdrop-blur-md" style="display: none;">
                                        @foreach($immeubles as $immeuble)
                                            <div @click="depenseEnCours.immeuble_id = '{{ $immeuble->id }}'; open = false" class="cursor-pointer py-2 px-3 rounded-lg text-sm text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800/50 transition-colors">{{ $immeuble->nom }}</div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <!-- Montant -->
                                <div>
                                    <label class="block text-sm font-semibold mb-2 dark:text-neutral-200">Montant (DH)</label>
                                    <input name="montant" x-model="depenseEnCours.montant" type="number" step="0.01" class="py-2.5 px-4 block w-full border border-gray-200 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 bg-white/50 dark:bg-[#090D16]/50 dark:border-slate-800/85 dark:text-neutral-300 transition-all duration-200" placeholder="Ex: 450.00" required>
                                </div>

                                <!-- Date -->
                                <div>
                                    <label class="block text-sm font-semibold mb-2 dark:text-neutral-200">Date d'opération</label>
                                    <input name="date_depense" x-model="depenseEnCours.date_depense" type="date" class="py-2.5 px-4 block w-full border border-gray-200 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 bg-white/50 dark:bg-[#090D16]/50 dark:border-slate-800/85 dark:text-neutral-300 transition-all duration-200" required>
                                </div>
                            </div>

                            <!-- Description -->
                            <div>
                                <label class="block text-sm font-semibold mb-2 dark:text-neutral-200">Description / Détails</label>
                                <textarea name="description" x-model="depenseEnCours.description" rows="3" class="py-2.5 px-4 block w-full border border-gray-200 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 bg-white/50 dark:bg-[#090D16]/50 dark:border-slate-800/85 dark:text-neutral-300 transition-all duration-200" placeholder="Entrez des détails ou raisons sur cette dépense..."></textarea>
                            </div>

                            <!-- Justificatif -->
                            <div>
                                <label class="block text-sm font-semibold mb-2 dark:text-neutral-200">Télécharger le justificatif (Image/PDF)</label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-slate-800 border-dashed rounded-xl hover:bg-gray-50/50 dark:hover:bg-slate-900/10 transition-colors cursor-pointer relative group">
                                    <div class="space-y-1 text-center">
                                        <div class="flex justify-center text-gray-400 group-hover:scale-105 transition-transform duration-200">
                                            <i data-lucide="receipt" class="size-10 text-rose-500"></i>
                                        </div>
                                        <div class="flex text-sm text-gray-600 dark:text-slate-400 justify-center">
                                            <label for="justificatif-upload" class="relative cursor-pointer rounded-md font-semibold text-primary-600 hover:text-primary-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary-500">
                                                <span>Télécharger le justificatif</span>
                                                <input id="justificatif-upload" name="justificatif" type="file" class="sr-only" onchange="document.getElementById('fileNameSpanAdminDep').innerText = this.files[0] ? this.files[0].name : 'Aucun fichier sélectionné'">
                                            </label>
                                        </div>
                                        <p class="text-xs text-gray-500 dark:text-slate-500">PDF, PNG, JPG jusqu'à 20 Mo</p>
                                        <div id="fileNameDisplayAdminDep" class="mt-2 text-xs font-semibold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/20 py-1 px-2 rounded-lg inline-block">
                                            <span id="fileNameSpanAdminDep">Aucun fichier sélectionné</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="flex justify-end items-center gap-x-3 mt-6 border-t border-gray-100 dark:border-slate-800/60 pt-4">
                            <button type="button" class="py-2 px-4 text-sm font-medium border border-gray-200 dark:border-slate-800 dark:text-neutral-300 hover:bg-gray-50 dark:hover:bg-slate-900 rounded-xl transition-colors duration-150" data-hs-overlay="#hs-modal-add-depense">Annuler</button>
                            <button type="submit" class="py-2 px-4 text-sm font-semibold bg-gradient-to-r from-primary-600 to-purple-600 hover:from-primary-700 hover:to-purple-700 text-white rounded-xl shadow-md shadow-primary-500/10 transition-all duration-300">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
