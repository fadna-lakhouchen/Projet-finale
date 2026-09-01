@extends('layouts.app')

@section('content')
<div x-data="{
    search: '',
    moisSelectionne: 'all',
    statutSelectionne: 'all',
    openMois: false,
    openStat: false,
    
    // Pagination attributes
    items: [
        @foreach($paiements as $paiement)
        {
            id: '{{ $paiement->id }}',
            ref: 'REF-{{ str_pad($paiement->id, 6, '0', STR_PAD_LEFT) }}',
            mois: '{{ ucfirst(\Carbon\Carbon::parse($paiement->date_paiement)->translatedFormat('F Y')) }}',
            statut: '{{ $paiement->statut }}'
        },
        @endforeach
    ],
    currentPage: 1,
    perPage: 10,
    
    init() {
        this.$watch('search', () => this.currentPage = 1);
        this.$watch('moisSelectionne', () => this.currentPage = 1);
        this.$watch('statutSelectionne', () => this.currentPage = 1);
    },
    
    get filteredItems() {
        return this.items.filter(item => this.matches(item.ref, item.mois, item.statut));
    },
    
    isRowVisible(id, ref, mois, statut) {
        if (!this.matches(ref, mois, statut)) return false;
        const index = this.filteredItems.findIndex(item => item.id == id);
        if (index === -1) return false;
        const start = (this.currentPage - 1) * this.perPage;
        const end = this.currentPage * this.perPage;
        return index >= start && index < end;
    },

    matches(ref, mois, statut) {
        const s = this.search.toLowerCase();
        const matchSearch = ref.toLowerCase().includes(s);
        const matchMois = this.moisSelectionne === 'all' || mois === this.moisSelectionne;
        
        const normStatut = statut.toLowerCase();
        const normSelected = this.statutSelectionne.toLowerCase();
        
        let matchStatut = false;
        if (normSelected === 'all') {
            matchStatut = true;
        } else if (normSelected === 'payé') {
            matchStatut = normStatut === 'payé' || normStatut === 'validé';
        } else if (normSelected === 'en attente') {
            matchStatut = normStatut === 'en attente';
        }
        
        return matchSearch && matchMois && matchStatut;
    }
}" class="space-y-6">
    <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">{{ __('Paiements') }}</h2>

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
        <!-- Header / Filters -->
        <div class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-b border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-800">
            <div class="sm:col-span-1 max-w-sm w-full relative">
                <label for="resident-charge-search" class="sr-only">{{ __('Rechercher') }}</label>
                <input x-model="search" type="text" id="resident-charge-search" class="py-2 px-3 ps-11 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" placeholder="{{ __('Référence du paiement...') }}">
                <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                    <i data-lucide="search" class="size-4 text-gray-400"></i>
                </div>
            </div>

            <div class="sm:col-span-2 md:grow flex justify-end gap-x-2">
                <!-- Dropdown Mois -->
                <div class="relative inline-flex">
                  <button @click="openMois = !openMois" @click.outside="openMois = false" type="button" class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white">
                    <span x-text="moisSelectionne === 'all' ? '{{ __('Mois') }}' : moisSelectionne"></span>
                    <i data-lucide="chevron-down" :class="openMois ? 'rotate-180' : ''" class="size-4 transition-transform text-gray-400"></i>
                  </button>
                  <div x-show="openMois" x-cloak class="absolute right-0 top-full z-[100] mt-1 w-48 bg-white border border-gray-200 shadow-xl rounded-lg p-1 dark:bg-neutral-800 dark:border-neutral-700">
                    <div @click="moisSelectionne = 'all'; openMois = false" class="cursor-pointer w-full flex items-center py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700">{{ __('Tous les mois') }}</div>
                    @foreach($moisDisponibles as $mois)
                        <div @click="moisSelectionne = '{{ $mois }}'; openMois = false" class="cursor-pointer w-full flex items-center py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700">{{ $mois }}</div>
                    @endforeach
                  </div>
                </div>

                <!-- Dropdown Statut -->
                <div class="relative inline-flex">
                  <button @click="openStat = !openStat" @click.outside="openStat = false" type="button" class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white">
                    <span x-text="statutSelectionne === 'all' ? '{{ __('Statuts') }}' : statutSelectionne"></span>
                    <i data-lucide="chevron-down" :class="openStat ? 'rotate-180' : ''" class="size-4 transition-transform text-gray-400"></i>
                  </button>
                  <div x-show="openStat" x-cloak class="absolute right-0 top-full z-[100] mt-1 w-48 bg-white border border-gray-200 shadow-xl rounded-lg p-1 dark:bg-neutral-800 dark:border-neutral-700">
                    <div @click="statutSelectionne = 'all'; openStat = false" class="cursor-pointer w-full flex items-center py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700">{{ __('Tous les statuts') }}</div>
                    <div @click="statutSelectionne = '{{ __('Payé') }}'; openStat = false" class="cursor-pointer w-full flex items-center py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700">{{ __('Payé') }}</div>
                    <div @click="statutSelectionne = '{{ __('En attente') }}'; openStat = false" class="cursor-pointer w-full flex items-center py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700">{{ __('En attente') }}</div>
                  </div>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto rounded-b-xl">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                <thead class="bg-gray-50 dark:bg-neutral-700">
                    <tr>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">{{ __('Date') }}</th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">{{ __('Référence') }}</th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">{{ __('Montant') }}</th>
                        <th class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">{{ __('Statut') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                    @forelse($paiements as $paiement)
                    @php
                        $dateMois = ucfirst(\Carbon\Carbon::parse($paiement->date_paiement)->translatedFormat('F Y'));
                        $ref = 'REF-' . str_pad($paiement->id, 6, '0', STR_PAD_LEFT);
                    @endphp
                    <tr x-show="isRowVisible('{{ $paiement->id }}', '{{ $ref }}', '{{ $dateMois }}', '{{ $paiement->statut }}')">
                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-neutral-200 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($paiement->date_paiement)->translatedFormat('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-neutral-400">{{ $ref }}</td>
                        <td class="px-6 py-4 text-sm font-bold text-gray-800 dark:text-neutral-200">{{ number_format($paiement->montant, 2) }} {{ __('MAD') }}</td>
                        <td class="px-6 py-4 text-end whitespace-nowrap">
                            @if(strtolower($paiement->statut) === 'validé' || strtolower($paiement->statut) === 'payé')
                            <span class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400">
                                <span class="size-1.5 rounded-full bg-current"></span>
                                {{ __('Payé') }}
                            </span>
                            @elseif(strtolower($paiement->statut) === 'en attente')
                            <span class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400">
                                <span class="size-1.5 rounded-full bg-current"></span>
                                {{ __('En attente du Syndic') }}
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-full text-xs font-bold bg-rose-100 text-rose-800 dark:bg-rose-900/30 dark:text-rose-400">
                                <span class="size-1.5 rounded-full bg-current"></span>
                                {{ ucfirst($paiement->statut) }}
                            </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500 dark:text-neutral-400">{{ __('Aucun historique de paiement trouvé.') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination controls -->
        <div class="px-6 py-4 flex items-center justify-between border-t border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-800">
            <div class="flex-1 flex justify-between sm:hidden">
                <button @click="if (currentPage > 1) currentPage--" :disabled="currentPage === 1" class="relative inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400">
                    {{ __('Précédent') }}
                </button>
                <button @click="if (currentPage < Math.ceil(filteredItems.length / perPage)) currentPage++" :disabled="currentPage === Math.ceil(filteredItems.length / perPage) || filteredItems.length === 0" class="relative ml-3 inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400">
                    {{ __('Suivant') }}
                </button>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-neutral-400">
                        {{ __('Affichage de') }} <span class="font-semibold text-gray-800 dark:text-white" x-text="filteredItems.length === 0 ? 0 : (currentPage - 1) * perPage + 1"></span> {{ __('à') }} <span class="font-semibold text-gray-800 dark:text-white" x-text="Math.min(currentPage * perPage, filteredItems.length)"></span> {{ __('sur') }} <span class="font-semibold text-gray-800 dark:text-white" x-text="filteredItems.length"></span> {{ __('résultats') }}
                    </p>
                </div>
                <div class="inline-flex gap-x-2">
                    <button @click="if (currentPage > 1) currentPage--" :disabled="currentPage === 1" class="py-2 px-3 inline-flex items-center gap-x-1.5 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 dark:bg-neutral-800 dark:border-neutral-700 dark:text-white">
                        <i data-lucide="chevron-left" class="size-4"></i>
                        {{ __('Précédent') }}
                    </button>
                    
                    <div class="flex items-center gap-x-1">
                        <template x-for="page in Math.ceil(filteredItems.length / perPage)" :key="page">
                            <button @click="currentPage = page" 
                                    :class="currentPage === page ? 'bg-primary-600 text-white border-transparent' : 'bg-white dark:bg-neutral-800 border-gray-200 dark:border-neutral-700 text-gray-800 dark:text-neutral-350 hover:bg-gray-50 dark:hover:bg-neutral-700'"
                                    class="size-9 inline-flex justify-center items-center text-sm font-semibold rounded-lg border transition-all duration-200" 
                                    x-text="page">
                            </button>
                        </template>
                    </div>

                    <button @click="if (currentPage < Math.ceil(filteredItems.length / perPage)) currentPage++" :disabled="currentPage === Math.ceil(filteredItems.length / perPage) || filteredItems.length === 0" class="py-2 px-3 inline-flex items-center gap-x-1.5 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 dark:bg-neutral-800 dark:border-neutral-700 dark:text-white">
                        {{ __('Suivant') }}
                        <i data-lucide="chevron-right" class="size-4"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
