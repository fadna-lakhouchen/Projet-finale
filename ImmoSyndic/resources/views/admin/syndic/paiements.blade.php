@extends('layouts.app')

@section('content')
<!-- Header -->
<div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight dark:text-white">Suivi des Paiements</h2>
        <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">Vérifiez les versements des charges mensuelles des résidents.</p>
    </div>
    <div class="flex flex-wrap gap-3">
        <button type="button" class="py-2.5 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-xl border border-transparent bg-gradient-to-r from-primary-600 to-purple-600 hover:from-primary-700 hover:to-purple-700 text-white shadow-md shadow-primary-500/10 hover:shadow-lg hover:shadow-primary-500/20 transition-all duration-300 transform hover:-translate-y-0.5" data-hs-overlay="#hs-saisir-paiement-modal">
            <i data-lucide="plus" class="size-4"></i> Saisir un paiement
        </button>
        <button type="button" class="py-2.5 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-xl border border-gray-200/80 bg-white/50 hover:bg-gray-50 dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-white dark:hover:bg-slate-900/50 shadow-sm transition-all duration-200">
            <i data-lucide="external-link" class="size-4"></i> Export CSV
        </button>
    </div>
</div>

<!-- Table Section -->
<div x-data="{
    search: '',
    moisSelectionne: 'all',
    immeubleSelectionne: 'all',
    statutSelectionne: 'all',
    openMois: false,
    openImm: false,
    openStat: false,
    matches(name, mois, immeuble, statut) {
        const s = this.search.toLowerCase();
        const matchSearch = name.toLowerCase().includes(s) || immeuble.toLowerCase().includes(s);
        const matchMois = this.moisSelectionne === 'all' || mois === this.moisSelectionne;
        const matchImmeuble = this.immeubleSelectionne === 'all' || immeuble === this.immeubleSelectionne;
        const matchStatut = this.statutSelectionne === 'all' || statut === this.statutSelectionne;
        return matchSearch && matchMois && matchImmeuble && matchStatut;
    }
}" class="flex flex-col bg-white/80 dark:bg-[#0D121F]/90 border border-gray-200/60 dark:border-slate-800/60 rounded-2xl shadow-premium backdrop-blur-md overflow-hidden">

    <!-- Header / Filters -->
    <div class="px-6 py-5 grid gap-4 md:flex md:justify-between md:items-center border-b border-gray-200/60 dark:border-slate-800/60 bg-white/40 dark:bg-[#0D121F]/40">
        <div class="sm:col-span-1 max-w-sm w-full relative">
            <label for="syndic-paiement-search" class="sr-only">Rechercher</label>
            <input x-model="search" type="text" id="syndic-paiement-search" class="py-2.5 px-4 ps-11 block w-full border-gray-200/80 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 bg-white/50 dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-neutral-300 dark:placeholder-neutral-500 transition-all duration-200" placeholder="Rechercher un résident...">
            <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                <i data-lucide="search" class="size-4 text-gray-400 dark:text-neutral-500"></i>
            </div>
        </div>

        <div class="sm:col-span-2 md:grow flex justify-end gap-x-3 relative">
            <!-- Dropdown Mois -->
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

            <!-- Dropdown Immeubles -->
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

            <!-- Dropdown Statut -->
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

    <!-- Table -->
    <div class="overflow-x-auto">
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
                @foreach($paiements as $paiement)
                @php
                    $dateFr = \Carbon\Carbon::parse($paiement->date_paiement)->translatedFormat('F Y');
                    $dateFr = ucfirst($dateFr);
                    $statut = $paiement->statut;
                    $appt = $paiement->user->appartements->first();
                    $immeubleName = $appt ? $appt->immeuble->nom : 'N/A';
                    $apptNumero = $appt ? $appt->numero : 'N/A';
                    $fullName = $paiement->user->prenom . ' ' . $paiement->user->nom;
                @endphp
                <tr x-show="matches('{{ addslashes($fullName) }}', '{{ $dateFr }}', '{{ addslashes($immeubleName) }}', '{{ $statut }}')" class="hover:bg-gray-50/50 dark:hover:bg-slate-900/30 transition-colors duration-150 {{ $statut == 'En retard' ? 'bg-rose-50/5 dark:bg-rose-950/5' : '' }}">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-x-3">
                            <img class="size-9 rounded-xl shadow-sm border border-gray-200/30" src="https://ui-avatars.com/api/?name={{ urlencode($paiement->user->prenom . '+' . $paiement->user->nom) }}&background=6366F1&color=fff&bold=true">
                            <div class="grow">
                                <span class="block text-sm font-semibold text-gray-800 dark:text-slate-200">{{ $fullName }}</span>
                                <span class="block text-xs text-gray-500 dark:text-slate-400">{{ $immeubleName }} - Appt {{ $apptNumero }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap"><span class="block text-sm font-medium text-gray-800 dark:text-slate-300">{{ $dateFr }}</span></td>
                    <td class="px-6 py-4 whitespace-nowrap"><span class="block text-sm font-semibold text-gray-800 dark:text-slate-200">{{ number_format($paiement->montant, 2) }} MAD</span></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        @if($statut == 'Payé')
                            <span class="inline-flex items-center gap-x-1.5 py-1 px-3.5 rounded-full text-xs font-semibold bg-emerald-50/80 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30">
                                <span class="size-1.5 rounded-full bg-emerald-600 dark:bg-emerald-400"></span>
                                Payé
                            </span>
                        @else
                            <span class="inline-flex items-center gap-x-1.5 py-1 px-3.5 rounded-full text-xs font-semibold bg-rose-50/80 text-rose-600 dark:bg-rose-950/40 dark:text-rose-400 border border-rose-100 dark:border-rose-900/30">
                                <span class="size-1.5 rounded-full bg-rose-600 dark:bg-rose-400"></span>
                                En retard
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                        <div class="inline-flex items-center gap-x-2">
                            @if($statut == 'Payé' || $statut == 'validé')
                                <a href="{{ route('syndic.paiements.receipt', $paiement->id) }}" target="_blank" class="py-1.5 px-3 inline-flex items-center gap-x-2 text-xs font-semibold rounded-xl border border-gray-200/80 hover:border-blue-300 bg-white/50 hover:bg-blue-50/50 text-gray-800 dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-slate-300 dark:hover:bg-slate-900 dark:hover:text-white transition-all duration-200">
                                    <i data-lucide="printer" class="size-3.5"></i> Reçu
                                </a>
                            @else
                                <button type="button" class="py-1.5 px-3 inline-flex items-center gap-x-2 text-xs font-semibold rounded-xl border border-transparent bg-rose-600 text-white hover:bg-rose-700 shadow-md shadow-rose-500/10 hover:shadow-lg hover:shadow-rose-500/20 transition-all duration-200">
                                    <i data-lucide="bell-ring" class="size-3.5"></i> Rappel
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Saisir Paiement -->
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
      <form action="{{ route('syndic.paiements.store') }}" method="POST">
        @csrf
        <div class="p-6 overflow-y-auto">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold mb-2 dark:text-neutral-200">Sélectionner la Charge</label>
                    <select name="charge_id" required class="py-2.5 px-4 block w-full border-gray-200 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 bg-white/50 dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-neutral-300 transition-all duration-200">
                        <option value="">Sélectionnez...</option>
                        <option value="1">Charge de test (ID:1) - Si existante</option>
                    </select>
                    <p class="text-xs text-gray-400 dark:text-slate-500 mt-1.5">Note: Assurez-vous d'avoir des charges générées pour pouvoir les imputer.</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2 dark:text-neutral-200">Montant payé (MAD)</label>
                    <input type="number" step="0.01" name="montant" required class="py-2.5 px-4 block w-full border-gray-200 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 bg-white/50 dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-neutral-300 transition-all duration-200">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2 dark:text-neutral-200">Méthode de paiement</label>
                    <select name="methode_paiement" required class="py-2.5 px-4 block w-full border-gray-200 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 bg-white/50 dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-neutral-300 transition-all duration-200">
                        <option value="espece">Espèce</option>
                        <option value="cheque">Chèque</option>
                        <option value="virement">Virement</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2 dark:text-neutral-200">Date de paiement</label>
                    <input type="date" name="date_paiement" value="{{ date('Y-m-d') }}" required class="py-2.5 px-4 block w-full border-gray-200 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 bg-white/50 dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-neutral-300 transition-all duration-200">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2 dark:text-neutral-200">Référence (Optionnel)</label>
                    <input type="text" name="reference" placeholder="Ex: N° Chèque" class="py-2.5 px-4 block w-full border-gray-200 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 bg-white/50 dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-neutral-300 transition-all duration-200">
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
@endsection
  </div>
</div>
@endsection
