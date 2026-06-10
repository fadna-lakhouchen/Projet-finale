{{-- Héritage du layout général de l'application --}}
@extends('layouts.app')

@section('content')
{{-- Liaison du scope Alpine.js avec initialisation des données réactives de cotisations --}}
<div x-data="syndicPaiements({ items: [
    @foreach($chargesList as $item)
    @php
        $appt = $item->appartement;
        $immeubleName = $appt ? $appt->immeuble->nom : 'N/A';
        $resident = $appt ? $appt->residents->first() : null;
        $fullName = $resident ? $resident->prenom . ' ' . $resident->nom : 'Non assigné';
        $dateFr = \Carbon\Carbon::parse($item->date_echeance)->translatedFormat('F Y');
        $dateFr = ucfirst($dateFr);
        $statutDb = strtolower($item->statut);
        $viewStatut = $statutDb === 'payé' ? 'Payé' : 'En retard';
    @endphp
    {
        id: '{{ $item->id }}',
        name: '{{ addslashes($fullName) }}',
        mois: '{{ $dateFr }}',
        immeuble: '{{ addslashes($immeubleName) }}',
        statut: '{{ $viewStatut }}'
    },
    @endforeach
] })">

{{-- En-tête de page avec titre et boutons d'export/création --}}
<div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight dark:text-white">Suivi des Paiements</h2>
        <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">Vérifiez les versements des charges mensuelles des résidents.</p>
    </div>
    <div class="flex flex-wrap gap-3">
        {{-- Bouton ouvrant la modale de saisie de paiement --}}
        <button type="button" class="py-2.5 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-xl border border-transparent bg-gradient-to-r from-primary-600 to-purple-600 hover:from-primary-700 hover:to-purple-700 text-white shadow-md shadow-primary-500/10 hover:shadow-lg hover:shadow-primary-500/20 transition-all duration-300 transform hover:-translate-y-0.5" data-hs-overlay="#hs-saisir-paiement-modal">
            <i data-lucide="plus" class="size-4"></i> Saisir un paiement
        </button>
        {{-- Liens d'exportations de rapports Excel et PDF --}}
        <a href="{{ route('syndic.paiements.export.excel') }}" class="py-2.5 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-xl border border-transparent bg-emerald-600 text-white hover:bg-emerald-700 shadow-md shadow-emerald-500/10 hover:shadow-lg hover:shadow-emerald-500/20 transition-all duration-300 transform hover:-translate-y-0.5">
            <i data-lucide="file-spreadsheet" class="size-4"></i> Export Excel
        </a>
        <a href="{{ route('syndic.paiements.export.pdf') }}" target="_blank" class="py-2.5 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-xl border border-transparent bg-rose-600 text-white hover:bg-rose-700 shadow-md shadow-rose-500/10 hover:shadow-lg hover:shadow-rose-500/20 transition-all duration-300 transform hover:-translate-y-0.5">
            <i data-lucide="file-text" class="size-4"></i> Export PDF
        </a>
    </div>
</div>

{{-- Panneau financier principal --}}
<div class="flex flex-col bg-white/80 dark:bg-[#0D121F]/90 border border-gray-200/60 dark:border-slate-800/60 rounded-2xl shadow-premium backdrop-blur-md">

    {{-- Filtres du tableau principal (Recherche, Mois, Immeubles et Statuts) --}}
    <div class="px-6 py-5 grid gap-4 md:flex md:justify-between md:items-center border-b border-gray-200/60 dark:border-slate-800/60 bg-white/40 dark:bg-[#0D121F]/40">
        {{-- Barre de recherche par résident --}}
        <div class="sm:col-span-1 max-w-sm w-full relative">
            <label for="syndic-paiement-search" class="sr-only">Rechercher</label>
            <input x-model="search" type="text" id="syndic-paiement-search" class="py-2.5 px-4 ps-11 block w-full border-gray-200/80 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 bg-white/50 dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-neutral-300 dark:placeholder-neutral-500 transition-all duration-200" placeholder="Rechercher un résident...">
            <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                <i data-lucide="search" class="size-4 text-gray-400 dark:text-neutral-500"></i>
            </div>
        </div>

        <div class="sm:col-span-2 md:grow flex justify-end gap-x-3 relative">
            <!-- Dropdown Filtre par Mois -->
            <div class="relative inline-flex">
              <button @click="openMois = !openMois" @click.outside="openMois = false" type="button" class="py-2.5 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-xl border border-gray-200/80 bg-white/50 hover:bg-gray-50 dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-white dark:hover:bg-slate-900/50 shadow-sm transition-all duration-200">
                <span x-text="moisSelectionne === 'all' ? 'Filtrer par Mois' : moisSelectionne" class="truncate max-w-[120px]"></span>
                <i data-lucide="chevron-down" :class="openMois ? 'rotate-180' : ''" class="size-4 transition-transform duration-200 text-gray-400"></i>
              </button>
              <div x-show="openMois" x-cloak class="absolute right-0 top-full z-[100] mt-2 w-48 bg-white/95 dark:bg-[#0D121F]/95 border border-gray-200/60 dark:border-slate-800/60 shadow-xl rounded-xl p-1.5 backdrop-blur-md">
                <div @click="moisSelectionne = 'all'; openMois = false" class="cursor-pointer w-full flex items-center py-2 px-3 rounded-lg text-sm text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800/50 transition-colors">Tous les mois</div>
                @foreach($moisDisponibles as $mois)
                    <div @click="moisSelectionne = '{{ $mois }}'; openMois = false" class="cursor-pointer w-full flex items-center py-2 px-3 rounded-lg text-sm text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800/50 transition-colors">{{ $mois }}</div>
                @endforeach
              </div>
            </div>

            <!-- Dropdown Filtre par Immeubles -->
            <div class="relative inline-flex">
              <button @click="openImm = !openImm" @click.outside="openImm = false" type="button" class="py-2.5 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-xl border border-gray-200/80 bg-white/50 hover:bg-gray-50 dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-white dark:hover:bg-slate-900/50 shadow-sm transition-all duration-200">
                <span x-text="immeubleSelectionne === 'all' ? 'Filtrer par Immeuble' : immeubleSelectionne" class="truncate max-w-[150px]"></span>
                <i data-lucide="chevron-down" :class="openImm ? 'rotate-180' : ''" class="size-4 transition-transform duration-200 text-gray-400"></i>
              </button>
              <div x-show="openImm" x-cloak class="absolute right-0 top-full z-[100] mt-2 w-56 bg-white/95 dark:bg-[#0D121F]/95 border border-gray-200/60 dark:border-slate-800/60 shadow-xl rounded-xl p-1.5 backdrop-blur-md">
                <div @click="immeubleSelectionne = 'all'; openImm = false" class="cursor-pointer w-full flex items-center py-2 px-3 rounded-lg text-sm text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800/50 transition-colors">Tous les immeubles</div>
                @foreach($immeubles as $immeuble)
                    <div @click="immeubleSelectionne = '{{ $immeuble->nom }}'; openImm = false" class="cursor-pointer w-full flex items-center py-2 px-3 rounded-lg text-sm text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800/50 transition-colors">{{ $immeuble->nom }}</div>
                @endforeach
              </div>
            </div>

            <!-- Dropdown Filtre par Statut -->
            <div class="relative inline-flex">
              <button @click="openStat = !openStat" @click.outside="openStat = false" type="button" class="py-2.5 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-xl border border-gray-200/80 bg-white/50 hover:bg-gray-50 dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-white dark:hover:bg-slate-900/50 shadow-sm transition-all duration-200">
                <span x-text="statutSelectionne === 'all' ? 'Statuts' : statutSelectionne" class="truncate max-w-[120px]"></span>
                <i data-lucide="chevron-down" :class="openStat ? 'rotate-180' : ''" class="size-4 transition-transform duration-200 text-gray-400"></i>
              </button>
              <div x-show="openStat" x-cloak class="absolute right-0 top-full z-[100] mt-2 w-48 bg-white/95 dark:bg-[#0D121F]/95 border border-gray-200/60 dark:border-slate-800/60 shadow-xl rounded-xl p-1.5 backdrop-blur-md">
                <div @click="statutSelectionne = 'all'; openStat = false" class="cursor-pointer w-full flex items-center py-2 px-3 rounded-lg text-sm text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800/50 transition-colors">Tous les statuts</div>
                <div @click="statutSelectionne = 'Payé'; openStat = false" class="cursor-pointer w-full flex items-center py-2 px-3 rounded-lg text-sm text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800/50 transition-colors">Payé</div>
                <div @click="statutSelectionne = 'En retard'; openStat = false" class="cursor-pointer w-full flex items-center py-2 px-3 rounded-lg text-sm text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800/50 transition-colors">En retard</div>
              </div>
            </div>
        </div>
    </div>

    <!-- Tableau principal de cotisations -->
    <div class="overflow-x-auto rounded-b-2xl">
        <table class="min-w-full divide-y divide-gray-200/60 dark:divide-slate-800/60">
            <thead class="bg-gray-50/50 dark:bg-[#090D16]/40">
                <tr>
                    <th scope="col" class="px-6 py-4 text-start text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-slate-400">Résident</th>
                    <th scope="col" class="px-6 py-4 text-start text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-slate-400">Mois</th>
                    <th scope="col" class="px-6 py-4 text-start text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-slate-400">Montant</th>
                    <th scope="col" class="px-6 py-4 text-start text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-slate-400">Statut</th>
                    <th scope="col" class="px-6 py-4 text-end text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-slate-400">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200/60 dark:divide-slate-800/60">
                @foreach($chargesList as $item)
                @php
                    $dateFr = \Carbon\Carbon::parse($item->date_echeance)->translatedFormat('F Y');
                    $dateFr = ucfirst($dateFr);
                    $statutDb = strtolower($item->statut);
                    
                    $viewStatut = $statutDb === 'payé' ? 'Payé' : 'En retard';
                    
                    $appt = $item->appartement;
                    $immeubleName = $appt ? $appt->immeuble->nom : 'N/A';
                    $apptNumero = $appt ? $appt->numero : 'N/A';
                    $resident = $appt ? $appt->residents->first() : null;
                    $fullName = $resident ? $resident->prenom . ' ' . $resident->nom : 'Non assigné';
                    
                    $validatedPaymentsSum = $item->paiements->where('statut', 'validé')->sum('montant');
                    $lastPayment = $item->paiements->sortByDesc('created_at')->first();
                @endphp
                <tr x-show="isRowVisible('{{ $item->id }}', '{{ addslashes($fullName) }}', '{{ $dateFr }}', '{{ addslashes($immeubleName) }}', '{{ $viewStatut }}')" class="hover:bg-gray-50/50 dark:hover:bg-slate-900/30 transition-colors duration-150 {{ $statutDb == 'impayé' ? 'bg-rose-50/[0.02]' : '' }}">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-x-3">
                            <img class="size-9 rounded-xl shadow-sm border border-gray-200/30" src="https://ui-avatars.com/api/?name={{ urlencode($fullName) }}&background=6366F1&color=fff&bold=true">
                            <div class="grow">
                                <span class="block text-sm font-semibold text-gray-800 dark:text-slate-200">{{ $fullName }}</span>
                                <span class="block text-xs text-gray-500 dark:text-slate-400">{{ $immeubleName }} - Appt {{ $apptNumero }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap"><span class="block text-sm font-medium text-gray-800 dark:text-slate-300">{{ $dateFr }}</span></td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="block text-sm font-semibold text-gray-800 dark:text-slate-200">{{ number_format($item->montant, 2) }} MAD</span>
                        @if($statutDb === 'partiel')
                            <span class="block text-xs text-amber-500 font-medium">Payé: {{ number_format($validatedPaymentsSum, 2) }} MAD</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        @if($statutDb === 'payé')
                            <span class="inline-flex items-center gap-x-1.5 py-1 px-3.5 rounded-full text-xs font-semibold bg-emerald-50/80 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30">
                                <span class="size-1.5 rounded-full bg-emerald-600 dark:bg-emerald-400"></span>
                                Payé
                            </span>
                        @elseif($statutDb === 'partiel')
                            <span class="inline-flex items-center gap-x-1.5 py-1 px-3.5 rounded-full text-xs font-semibold bg-amber-50/80 text-amber-600 dark:bg-amber-950/40 dark:text-amber-400 border border-amber-100 dark:border-amber-900/30">
                                <span class="size-1.5 rounded-full bg-amber-600 dark:bg-amber-400"></span>
                                Partiel
                            </span>
                        @else
                            <span class="inline-flex items-center gap-x-1.5 py-1 px-3.5 rounded-full text-xs font-semibold bg-rose-50/80 text-rose-600 dark:bg-rose-950/40 dark:text-rose-400 border border-rose-100 dark:border-rose-900/30">
                                <span class="size-1.5 rounded-full bg-rose-600 dark:bg-rose-400"></span>
                                Impayé
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                        <div class="inline-flex items-center gap-x-2">
                            @if($validatedPaymentsSum > 0 || $item->paiements->count() > 0)
                                <button type="button" 
                                        @click="
                                            currentCharge = {{ json_encode($item->load(['paiements.user', 'appartement.residents'])) }};
                                            HSOverlay.open('#hs-gerer-paiements-modal');
                                        " 
                                        class="py-1.5 px-3 inline-flex items-center gap-x-2 text-xs font-semibold rounded-xl border border-gray-250 dark:border-slate-800 bg-white/50 hover:bg-gray-50 text-gray-800 dark:bg-slate-900/50 dark:text-slate-300 dark:hover:bg-slate-900/80 transition-all duration-200" 
                                        title="Gérer les versements (Modifier ou Supprimer)">
                                    <i data-lucide="settings" class="size-3.5"></i> Gérer
                                </button>
                            @endif
                            
                            @if($statutDb !== 'payé')
                                <button type="button" @click="selectedChargeId = '{{ $item->id }}'; selectedChargeLabel = '{{ $item->titre }} (Reste: {{ number_format($item->reste_a_payer, 2) }} DH) - {{ $fullName }} ({{ $immeubleName }} - Appt {{ $apptNumero }})'; selectedMontant = '{{ $item->reste_a_payer }}'; HSOverlay.open('#hs-saisir-paiement-modal')" class="py-1.5 px-3 inline-flex items-center gap-x-2 text-xs font-semibold rounded-xl border border-transparent bg-primary-600 text-white hover:bg-primary-700 shadow-md shadow-primary-500/10 hover:shadow-lg hover:shadow-primary-500/20 transition-all duration-200" title="Saisir un versement pour cette charge">
                                    <i data-lucide="plus" class="size-3.5"></i> Régler
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    {{-- Pagination des résultats --}}
    <div class="px-6 py-4 flex items-center justify-between border-t border-gray-200/60 dark:border-slate-800/60 bg-white/40 dark:bg-[#0D121F]/40">
        <div class="flex-1 flex justify-between sm:hidden">
            <button @click="if (currentPage > 1) currentPage--" :disabled="currentPage === 1" class="relative inline-flex items-center px-4 py-2 text-sm font-medium rounded-xl border border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-800/50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200">
                Précédent
            </button>
            <button @click="if (currentPage < Math.ceil(filteredItems.length / perPage)) currentPage++" :disabled="currentPage === Math.ceil(filteredItems.length / perPage) || filteredItems.length === 0" class="relative ml-3 inline-flex items-center px-4 py-2 text-sm font-medium rounded-xl border border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-800/50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200">
                Suivant
            </button>
        </div>
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-500 dark:text-slate-400">
                    Affichage de <span class="font-semibold text-gray-800 dark:text-white" x-text="filteredItems.length === 0 ? 0 : (currentPage - 1) * perPage + 1"></span> à <span class="font-semibold text-gray-800 dark:text-white" x-text="Math.min(currentPage * perPage, filteredItems.length)"></span> sur <span class="font-semibold text-gray-800 dark:text-white" x-text="filteredItems.length"></span> résultats
                </p>
            </div>
            <div class="inline-flex gap-x-2">
                <button @click="if (currentPage > 1) currentPage--" :disabled="currentPage === 1" class="py-2 px-3 inline-flex items-center gap-x-1.5 text-sm font-medium rounded-xl border border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-gray-800 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-800/50 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200">
                    <i data-lucide="chevron-left" class="size-4"></i>
                    Précédent
                </button>
                
                <div class="flex items-center gap-x-1">
                    <template x-for="page in Math.ceil(filteredItems.length / perPage)" :key="page">
                        <button @click="currentPage = page" 
                                :class="currentPage === page ? 'bg-primary-600 text-white border-transparent' : 'bg-white dark:bg-slate-900 border-gray-200 dark:border-slate-800 text-gray-800 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-800/50'"
                                class="size-9 inline-flex justify-center items-center text-sm font-semibold rounded-xl border transition-all duration-200" 
                                x-text="page">
                        </button>
                    </template>
                </div>

                <button @click="if (currentPage < Math.ceil(filteredItems.length / perPage)) currentPage++" :disabled="currentPage === Math.ceil(filteredItems.length / perPage) || filteredItems.length === 0" class="py-2 px-3 inline-flex items-center gap-x-1.5 text-sm font-medium rounded-xl border border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-gray-800 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-800/50 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200">
                    Suivant
                    <i data-lucide="chevron-right" class="size-4"></i>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modale Saisir Paiement --}}
<div id="hs-saisir-paiement-modal" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none bg-slate-950/40 backdrop-blur-sm">
  <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
    <div class="flex flex-col bg-white border border-gray-200/60 dark:border-slate-800/60 shadow-premium rounded-2xl pointer-events-auto dark:bg-[#0D121F] w-full overflow-hidden">
      <div class="flex justify-between items-center py-4 px-5 border-b border-gray-200/60 dark:border-slate-800/60">
        <h3 class="font-bold text-gray-800 dark:text-white text-lg">
          Saisir un paiement
        </h3>
        <button type="button" class="size-8 inline-flex justify-center items-center rounded-xl bg-gray-100 text-gray-800 hover:bg-gray-200 dark:bg-slate-800 dark:text-neutral-400 dark:hover:bg-slate-700 transition-colors" data-hs-overlay="#hs-saisir-paiement-modal">
          <i data-lucide="x" class="size-4"></i>
        </button>
      </div>
      <form action="{{ route('syndic.paiements.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="p-6 overflow-y-auto">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold mb-2 dark:text-neutral-200">Sélectionner la Cotisation Impayée</label>
                    
                    <div class="relative w-full inline-flex">
                        <button type="button" @click="openSelectCharge = !openSelectCharge" @click.outside="openSelectCharge = false" class="py-2.5 px-4 w-full inline-flex justify-between items-center gap-x-2 text-sm font-semibold rounded-xl border border-gray-200/80 bg-white/50 hover:bg-white text-slate-800 shadow-sm dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-white dark:hover:bg-slate-900/50 transition-all duration-200">
                            <span x-text="selectedChargeLabel" class="truncate text-left pr-4"></span>
                            <i data-lucide="chevron-down" :class="openSelectCharge ? 'rotate-180' : ''" class="size-4 transition-transform duration-200 text-gray-400"></i>
                        </button>
                        
                        <input type="hidden" name="charge_id" :value="selectedChargeId" required>
                        
                        <div x-show="openSelectCharge" x-cloak class="absolute left-0 top-full z-[100] mt-2 w-full max-h-60 overflow-y-auto bg-white dark:bg-[#0D121F] border border-gray-200/60 dark:border-slate-800/60 shadow-xl rounded-xl p-1.5 backdrop-blur-md">
                            @forelse($charges as $c)
                                <button type="button" 
                                        @click="
                                            selectedChargeId = '{{ $c->id }}';
                                            selectedChargeLabel = '{{ $c->titre }} (Reste: {{ number_format($c->reste_a_payer, 2) }} DH) - {{ $c->resident_nom }}';
                                            selectedMontant = '{{ $c->reste_a_payer }}';
                                            openSelectCharge = false;
                                        " 
                                        class="w-full text-start flex items-center py-2.5 px-3 rounded-lg text-sm text-gray-700 dark:text-slate-350 hover:bg-gray-150 dark:hover:bg-slate-800/50 transition-colors">
                                    {{ $c->titre }} (Reste: {{ number_format($c->reste_a_payer, 2) }} DH) - Résident: {{ $c->resident_nom }} ({{ $c->appartement->immeuble->nom }} - Appt {{ $c->appartement->numero }})
                                </button>
                            @empty
                                <button type="button" disabled class="w-full text-start flex items-center py-2 px-3 text-sm text-gray-400 dark:text-slate-650 cursor-not-allowed">
                                    Aucune cotisation impayée en attente
                                </button>
                            @endforelse
                        </div>
                    </div>
                    
                    <p class="text-xs text-gray-400 dark:text-slate-500 mt-1.5">Note: Les résidents doivent avoir des cotisations impayées pour pouvoir saisir un règlement.</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2 dark:text-neutral-200">Montant payé (MAD)</label>
                    <input type="number" step="0.01" name="montant" x-model="selectedMontant" required class="py-2.5 px-4 block w-full border-gray-200 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 bg-white/50 dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-neutral-300 transition-all duration-200">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2 dark:text-neutral-200">Date de paiement</label>
                    <input type="date" name="date_paiement" value="{{ date('Y-m-d') }}" required class="py-2.5 px-4 block w-full border-gray-200 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 bg-white/50 dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-neutral-300 transition-all duration-200">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2 dark:text-neutral-200">Pièce jointe / Reçu (Optionnel - Image ou PDF)</label>
                    <input type="file" name="piece_jointe" accept="image/*,application/pdf" class="py-2 px-3 block w-full border border-gray-200/80 dark:border-slate-800/80 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 bg-white/50 dark:bg-[#090D16]/50 dark:text-neutral-300 file:bg-gray-100 file:border-0 file:me-4 file:py-1.5 file:px-3 file:rounded-lg file:text-xs file:font-semibold dark:file:bg-slate-800 dark:file:text-neutral-350 transition-all duration-200">
                </div>
            </div>
        </div>
        <div class="flex justify-end items-center gap-x-3 mt-6 border-t border-gray-100 dark:border-slate-800/60 p-4">
          <button type="button" class="py-2 px-4 text-sm font-medium border border-gray-200 dark:border-slate-800 dark:text-neutral-300 hover:bg-gray-50 dark:hover:bg-slate-900 rounded-xl transition-colors duration-150" data-hs-overlay="#hs-saisir-paiement-modal">
            Annuler
          </button>
          <button type="submit" class="py-2 px-4 text-sm font-semibold bg-gradient-to-r from-primary-600 to-purple-600 hover:from-primary-700 hover:to-purple-700 text-white rounded-xl shadow-md shadow-primary-500/10 transition-all duration-300">
            Enregistrer le paiement
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Modale : Liste et gestion des versements d'une cotisation spécifique --}}
<div id="hs-gerer-paiements-modal" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none bg-slate-950/40 backdrop-blur-sm">
  <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-xl sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
    <div class="flex flex-col bg-white border border-gray-200/60 dark:border-slate-800/60 shadow-premium rounded-2xl pointer-events-auto dark:bg-[#0D121F] w-full overflow-hidden">
      {{-- En-tête de la modale --}}
      <div class="flex justify-between items-center py-4 px-5 border-b border-gray-200/60 dark:border-slate-800/60">
        <h3 class="font-bold text-gray-800 dark:text-white text-lg">
          Versements enregistrés (Détails)
        </h3>
        <button type="button" class="size-8 inline-flex justify-center items-center rounded-xl bg-gray-100 text-gray-800 hover:bg-gray-200 dark:bg-slate-800 dark:text-neutral-400 dark:hover:bg-slate-700 transition-colors" data-hs-overlay="#hs-gerer-paiements-modal">
          <i data-lucide="x" class="size-4"></i>
        </button>
      </div>

      {{-- Corps de la modale --}}
      <div class="p-6 overflow-y-auto">
        {{-- Section récapulative de la cotisation mensuelle --}}
        <div class="mb-6 p-4 rounded-xl bg-gray-50 dark:bg-slate-900/50 border border-gray-150 dark:border-slate-800">
            <span class="block text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider">Cotisation concernée</span>
            <span x-text="currentCharge ? currentCharge.titre : ''" class="block font-bold text-gray-800 dark:text-slate-200 mt-1"></span>
            <div class="mt-2 flex justify-between text-sm">
                <span class="text-gray-500 dark:text-slate-400">Montant total requis :</span>
                <span x-text="currentCharge ? parseFloat(currentCharge.montant).toFixed(2) + ' MAD' : ''" class="font-semibold text-gray-800 dark:text-white"></span>
            </div>
            <div class="mt-1 flex justify-between text-sm">
                <span class="text-gray-500 dark:text-slate-400">Statut actuel :</span>
                <span x-text="currentCharge ? currentCharge.statut.toUpperCase() : ''" 
                      :class="currentCharge?.statut === 'payé' ? 'text-emerald-500 font-bold' : (currentCharge?.statut === 'partiel' ? 'text-amber-500 font-bold' : 'text-rose-500 font-bold')">
                </span>
            </div>
        </div>

        {{-- Liste dynamique des versements --}}
        <h4 class="text-sm font-semibold mb-3 dark:text-neutral-200">Règlements saisis :</h4>
        <div class="space-y-3">
            <template x-for="(p, index) in (currentCharge ? currentCharge.paiements : [])" :key="p.id">
                <div class="p-4 rounded-xl border border-gray-200/80 dark:border-slate-800/80 bg-white/50 dark:bg-[#0D121F]/50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="space-y-1">
                        <div class="flex items-center gap-x-2">
                            <span x-text="parseFloat(p.montant).toFixed(2) + ' MAD'" class="font-bold text-slate-800 dark:text-white"></span>
                            <span class="text-xs px-2 py-0.5 rounded-full font-medium"
                                  :class="p.statut === 'validé' ? 'bg-emerald-500/10 text-emerald-500' : 'bg-amber-500/10 text-amber-500'"
                                  x-text="p.statut === 'validé' ? 'Payé' : 'En attente'">
                            </span>
                        </div>
                        <div class="text-xs text-gray-500 dark:text-slate-400 flex flex-wrap gap-x-3 gap-y-1">
                            <span><strong class="font-medium text-gray-600 dark:text-slate-350">Date :</strong> <span x-text="formatDate(p.date_paiement)"></span></span>
                            <span><strong class="font-medium text-gray-600 dark:text-slate-350">Mode :</strong> <span x-text="p.mode_paiement"></span></span>
                        </div>
                    </div>
                    
                    {{-- Actions individuelles sur versement --}}
                    <div class="flex items-center gap-2">
                        <template x-if="p.recu_path">
                            <a :href="'/storage/' + p.recu_path" target="_blank" class="p-2 inline-flex items-center justify-center text-xs font-semibold rounded-lg border border-gray-200 dark:border-slate-800 text-gray-500 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-900" title="Justificatif">
                                <i data-lucide="paperclip" class="size-4"></i>
                            </a>
                        </template>

                        <template x-if="p.statut === 'validé'">
                            <a :href="'/syndic/paiements/' + p.id + '/receipt'" target="_blank" class="p-2 inline-flex items-center justify-center text-xs font-semibold rounded-lg border border-gray-200 dark:border-slate-800 text-gray-500 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-900" title="Imprimer le Reçu">
                                <i data-lucide="printer" class="size-4"></i>
                            </a>
                        </template>

                        <button type="button" 
                                @click="
                                    editPaiementId = p.id;
                                    editChargeId = p.charge_id;
                                    editChargeLabel = currentCharge.titre + ' - ' + (currentCharge.appartement?.residents?.[0]?.prenom || '') + ' ' + (currentCharge.appartement?.residents?.[0]?.nom || '');
                                    editMontant = p.montant;
                                    editDate = p.date_paiement.split('T')[0];
                                    editStatut = p.statut;
                                    HSOverlay.close('#hs-gerer-paiements-modal');
                                    setTimeout(() => HSOverlay.open('#hs-modifier-paiement-modal'), 250);
                                " 
                                class="p-2 inline-flex items-center justify-center text-xs font-semibold rounded-lg border border-blue-200/80 text-blue-600 hover:bg-blue-50/50 dark:border-blue-900/30 dark:text-blue-450 dark:hover:bg-blue-950/20" title="Modifier ce versement">
                            <i data-lucide="pencil" class="size-4"></i>
                        </button>

                        <button type="button" @click="deletePaiement(p.id)" class="p-2 inline-flex items-center justify-center text-xs font-semibold rounded-lg border border-rose-200/80 text-rose-600 hover:bg-rose-50/50 dark:border-rose-900/30 dark:text-rose-450 dark:hover:bg-rose-950/20" title="Supprimer ce versement">
                            <i data-lucide="trash-2" class="size-4"></i>
                        </button>
                    </div>
                </div>
            </template>
            
            <template x-if="!currentCharge || currentCharge.paiements.length === 0">
                <div class="text-center py-6 text-sm text-gray-400 dark:text-slate-500">
                    Aucun versement enregistré pour cette cotisation.
                </div>
            </template>
        </div>
      </div>
      
      <div class="flex justify-end items-center gap-x-3 border-t border-gray-100 dark:border-slate-800/60 p-4 bg-gray-50/50 dark:bg-slate-900/10">
        <button type="button" class="py-2 px-4 text-sm font-medium border border-gray-200 dark:border-slate-800 dark:text-neutral-350 hover:bg-gray-50 dark:hover:bg-slate-900 rounded-xl" data-hs-overlay="#hs-gerer-paiements-modal">
          Fermer
        </button>
      </div>
    </div>
  </div>
</div>

{{-- Modale : Formulaire de modification --}}
<div id="hs-modifier-paiement-modal" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none bg-slate-950/40 backdrop-blur-sm">
  <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
    <div class="flex flex-col bg-white border border-gray-200/60 dark:border-slate-800/60 shadow-premium rounded-2xl pointer-events-auto dark:bg-[#0D121F] w-full overflow-hidden">
      <div class="flex justify-between items-center py-4 px-5 border-b border-gray-200/60 dark:border-slate-800/60">
        <h3 class="font-bold text-gray-800 dark:text-white text-lg">
          Modifier les détails du versement
        </h3>
        <button type="button" class="size-8 inline-flex justify-center items-center rounded-xl bg-gray-100 text-gray-800 hover:bg-gray-200 dark:bg-slate-800 dark:text-neutral-400 dark:hover:bg-slate-700 transition-colors" data-hs-overlay="#hs-modifier-paiement-modal">
          <i data-lucide="x" class="size-4"></i>
        </button>
      </div>

      <form :action="'/syndic/paiements/' + editPaiementId" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="p-6 overflow-y-auto">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold mb-2 dark:text-neutral-200">Cotisation / Résident Destinataire</label>
                    <div class="relative w-full inline-flex">
                        <button type="button" @click="openEditSelectCharge = !openEditSelectCharge" @click.outside="openEditSelectCharge = false" class="py-2.5 px-4 w-full inline-flex justify-between items-center gap-x-2 text-sm font-semibold rounded-xl border border-gray-200/80 bg-white/50 hover:bg-white text-slate-800 shadow-sm dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-white dark:hover:bg-slate-900/50 transition-all duration-200">
                            <span x-text="editChargeLabel" class="truncate text-left pr-4"></span>
                            <i data-lucide="chevron-down" :class="openEditSelectCharge ? 'rotate-180' : ''" class="size-4 transition-transform duration-200 text-gray-400"></i>
                        </button>
                        
                        <input type="hidden" name="charge_id" :value="editChargeId" required>
                        
                        <div x-show="openEditSelectCharge" x-cloak class="absolute left-0 top-full z-[100] mt-2 w-full max-h-60 overflow-y-auto bg-white dark:bg-[#0D121F] border border-gray-200/60 dark:border-slate-800/60 shadow-xl rounded-xl p-1.5 backdrop-blur-md">
                            @foreach($chargesList as $c)
                                @php
                                    $resName = $c->resident_nom;
                                    $cLabel = $c->titre . " - " . $resName . " (" . ($c->appartement->immeuble->nom ?? '') . " - Appt " . ($c->appartement->numero ?? '') . ")";
                                @endphp
                                <button type="button" 
                                        @click="
                                            editChargeId = '{{ $c->id }}';
                                            editChargeLabel = '{{ addslashes($cLabel) }}';
                                            openEditSelectCharge = false;
                                        " 
                                        class="w-full text-start flex items-center py-2.5 px-3 rounded-lg text-sm text-gray-700 dark:text-slate-350 hover:bg-gray-150 dark:hover:bg-slate-800/50 transition-colors">
                                    {{ $cLabel }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2 dark:text-neutral-200">Montant versé (MAD)</label>
                    <input type="number" step="0.01" name="montant" x-model="editMontant" required class="py-2.5 px-4 block w-full border-gray-200 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 bg-white/50 dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-neutral-300 transition-all duration-200">
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2 dark:text-neutral-200">Date du versement</label>
                    <input type="date" name="date_paiement" x-model="editDate" required class="py-2.5 px-4 block w-full border-gray-200 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 bg-white/50 dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-neutral-300 transition-all duration-200">
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2 dark:text-neutral-200">Statut du Versement</label>
                    <div class="relative w-full inline-flex">
                        <button type="button" @click="openEditStat = !openEditStat" @click.outside="openEditStat = false" class="py-2.5 px-4 w-full inline-flex justify-between items-center gap-x-2 text-sm font-semibold rounded-xl border border-gray-200/80 bg-white/50 hover:bg-white text-slate-800 shadow-sm dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-white dark:hover:bg-slate-900/50 transition-all duration-200">
                            <span x-text="editStatut === 'validé' ? 'Payé (Validé)' : 'En attente'" class="truncate text-left pr-4"></span>
                            <i data-lucide="chevron-down" :class="openEditStat ? 'rotate-180' : ''" class="size-4 transition-transform duration-200 text-gray-400"></i>
                        </button>
                        
                        <input type="hidden" name="statut" :value="editStatut" required>
                        
                        <div x-show="openEditStat" x-cloak class="absolute left-0 top-full z-[100] mt-2 w-full bg-white dark:bg-[#0D121F] border border-gray-200/60 dark:border-slate-800/60 shadow-xl rounded-xl p-1.5 backdrop-blur-md">
                            <button type="button" @click="editStatut = 'validé'; openEditStat = false" class="w-full text-start flex items-center py-2 px-3 rounded-lg text-sm text-gray-700 dark:text-slate-350 hover:bg-gray-150 dark:hover:bg-slate-800/50 transition-colors">
                                Payé (Validé)
                            </button>
                            <button type="button" @click="editStatut = 'en attente'; openEditStat = false" class="w-full text-start flex items-center py-2 px-3 rounded-lg text-sm text-gray-700 dark:text-slate-350 hover:bg-gray-150 dark:hover:bg-slate-800/50 transition-colors">
                                En attente
                            </button>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2 dark:text-neutral-200">Pièce jointe / Preuve de virement (Optionnel - Image ou PDF)</label>
                    <input type="file" name="piece_jointe" accept="image/*,application/pdf" class="py-2 px-3 block w-full border border-gray-200/80 dark:border-slate-800/80 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 bg-white/50 dark:bg-[#090D16]/50 dark:text-neutral-300 file:bg-gray-100 file:border-0 file:me-4 file:py-1.5 file:px-3 file:rounded-lg file:text-xs file:font-semibold dark:file:bg-slate-800 dark:file:text-neutral-350 transition-all duration-200">
                </div>
            </div>
        </div>

        <div class="flex justify-end items-center gap-x-3 border-t border-gray-100 dark:border-slate-800/60 p-4 bg-gray-50/50 dark:bg-slate-900/10">
          <button type="button" class="py-2 px-4 text-sm font-medium border border-gray-200 dark:border-slate-800 dark:text-neutral-350 hover:bg-gray-50 dark:hover:bg-slate-900 rounded-xl" data-hs-overlay="#hs-modifier-paiement-modal">
            Annuler
          </button>
          <button type="submit" class="py-2 px-4 text-sm font-semibold bg-gradient-to-r from-primary-600 to-purple-600 hover:from-primary-700 hover:to-purple-700 text-white rounded-xl shadow-md shadow-primary-500/10 transition-all duration-300">
            Enregistrer les modifications
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Formulaire fantôme pour la suppression --}}
<form id="delete-paiement-form" method="POST" action="" class="hidden">
    @csrf
    @method('DELETE')
</form>

</div>
@endsection
