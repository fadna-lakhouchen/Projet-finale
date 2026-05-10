@extends('layouts.app')

@section('content')
<div x-data="{
    search: '',
    moisSelectionne: 'all',
    statutSelectionne: 'all',
    openMois: false,
    openStat: false,
    matches(ref, mois, statut) {
        const s = this.search.toLowerCase();
        const matchSearch = ref.toLowerCase().includes(s);
        const matchMois = this.moisSelectionne === 'all' || mois === this.moisSelectionne;
        const matchStatut = this.statutSelectionne === 'all' || statut === this.statutSelectionne;
        return matchSearch && matchMois && matchStatut;
    }
}" class="space-y-6">
    <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Mes Charges</h2>

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-neutral-800 dark:border-neutral-700">
        <!-- Header / Filters -->
        <div class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-b border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-800">
            <div class="sm:col-span-1 max-w-sm w-full relative">
                <label for="resident-charge-search" class="sr-only">Rechercher</label>
                <input x-model="search" type="text" id="resident-charge-search" class="py-2 px-3 ps-11 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" placeholder="Référence du paiement...">
                <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                    <i data-lucide="search" class="size-4 text-gray-400"></i>
                </div>
            </div>

            <div class="sm:col-span-2 md:grow flex justify-end gap-x-2">
                <!-- Dropdown Mois -->
                <div class="relative inline-flex">
                  <button @click="openMois = !openMois" @click.outside="openMois = false" type="button" class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white">
                    <span x-text="moisSelectionne === 'all' ? 'Mois' : moisSelectionne"></span>
                    <i data-lucide="chevron-down" :class="openMois ? 'rotate-180' : ''" class="size-4 transition-transform text-gray-400"></i>
                  </button>
                  <div x-show="openMois" x-cloak class="absolute right-0 top-full z-[100] mt-1 w-48 bg-white border border-gray-200 shadow-xl rounded-lg p-1 dark:bg-neutral-800 dark:border-neutral-700">
                    <div @click="moisSelectionne = 'all'; openMois = false" class="cursor-pointer w-full flex items-center py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700">Tous les mois</div>
                    @foreach($moisDisponibles as $mois)
                        <div @click="moisSelectionne = '{{ $mois }}'; openMois = false" class="cursor-pointer w-full flex items-center py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700">{{ $mois }}</div>
                    @endforeach
                  </div>
                </div>

                <!-- Dropdown Statut -->
                <div class="relative inline-flex">
                  <button @click="openStat = !openStat" @click.outside="openStat = false" type="button" class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white">
                    <span x-text="statutSelectionne === 'all' ? 'Statuts' : statutSelectionne"></span>
                    <i data-lucide="chevron-down" :class="openStat ? 'rotate-180' : ''" class="size-4 transition-transform text-gray-400"></i>
                  </button>
                  <div x-show="openStat" x-cloak class="absolute right-0 top-full z-[100] mt-1 w-48 bg-white border border-gray-200 shadow-xl rounded-lg p-1 dark:bg-neutral-800 dark:border-neutral-700">
                    <div @click="statutSelectionne = 'all'; openStat = false" class="cursor-pointer w-full flex items-center py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700">Tous les statuts</div>
                    <div @click="statutSelectionne = 'Payé'; openStat = false" class="cursor-pointer w-full flex items-center py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700">Payé</div>
                    <div @click="statutSelectionne = 'En retard'; openStat = false" class="cursor-pointer w-full flex items-center py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700">En retard</div>
                  </div>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                <thead class="bg-gray-50 dark:bg-neutral-700">
                    <tr>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">Date</th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">Référence</th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">Montant</th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">Méthode</th>
                        <th class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">Reçu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                    @forelse($paiements as $paiement)
                    @php
                        $dateMois = ucfirst(\Carbon\Carbon::parse($paiement->date_paiement)->translatedFormat('F Y'));
                        $ref = 'REF-' . str_pad($paiement->id, 6, '0', STR_PAD_LEFT);
                    @endphp
                    <tr x-show="matches('{{ $ref }}', '{{ $dateMois }}', '{{ $paiement->statut }}')">
                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-neutral-200 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($paiement->date_paiement)->translatedFormat('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-neutral-400">{{ $ref }}</td>
                        <td class="px-6 py-4 text-sm font-bold text-gray-800 dark:text-neutral-200">{{ number_format($paiement->montant, 2) }} MAD</td>
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-neutral-400">Virement / Autre</td>
                        <td class="px-6 py-4 text-end">
                            @if($paiement->statut === 'Payé')
                            <button class="text-primary-600 hover:text-primary-800 font-medium text-sm inline-flex items-center gap-1 dark:text-primary-400 dark:hover:text-primary-300 transition-colors">
                                <i data-lucide="download" class="size-4"></i> Reçu PDF
                            </button>
                            @else
                            <button data-hs-overlay="#hs-modal-payment-simulation" class="py-1.5 px-3 inline-flex items-center gap-x-2 text-xs font-semibold rounded-lg border border-transparent bg-red-600 text-white hover:bg-red-700 disabled:opacity-50 transition-colors shadow-sm">
                                <i data-lucide="credit-card" class="size-3"></i> Payer
                            </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-neutral-400">Aucun historique de paiement trouvé.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Simulation de Paiement -->
<div id="hs-modal-payment-simulation" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none" role="dialog" tabindex="-1">
    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
        <div x-data="{ processing: false, done: false }" class="w-full flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
            <div class="flex justify-between items-center py-3 px-4 border-b dark:border-neutral-700">
                <h3 class="font-bold text-gray-800 dark:text-white">Paiement sécurisé</h3>
                <button type="button" class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-gray-100 text-gray-800 hover:bg-gray-200 focus:outline-none dark:bg-neutral-700 dark:text-neutral-400" data-hs-overlay="#hs-modal-payment-simulation">
                    <i data-lucide="x" class="size-4"></i>
                </button>
            </div>
            
            <div class="p-6 overflow-y-auto">
                <template x-if="!done">
                    <div class="space-y-4">
                        <div class="bg-gray-50 p-4 rounded-lg dark:bg-neutral-900 mb-4 text-center">
                            <span class="text-xs uppercase tracking-wider text-gray-500 dark:text-neutral-500 block mb-1">Montant de la charge</span>
                            <span class="text-xl font-bold text-gray-800 dark:text-white">Simulation de paiement</span>
                        </div>

                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium mb-1 dark:text-white">Titulaire de la carte</label>
                                <input type="text" class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" placeholder="{{ auth()->user()->fullName }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1 dark:text-white">Numéro de carte</label>
                                <div class="relative">
                                    <input type="text" class="py-2 px-3 ps-11 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" placeholder="0000 0000 0000 0000">
                                    <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                                        <i data-lucide="credit-card" class="size-4 text-gray-400"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-sm font-medium mb-1 dark:text-white">Expiration</label>
                                    <input type="text" class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" placeholder="MM/YY">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1 dark:text-white">CVC</label>
                                    <input type="password" class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" placeholder="***">
                                </div>
                            </div>
                        </div>

                        <div class="pt-4">
                            <button @click="processing = true; setTimeout(() => { processing = false; done = true; }, 2000)" type="button" class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-primary-600 text-white hover:bg-primary-700 disabled:opacity-50" :disabled="processing">
                                <template x-if="!processing">
                                    <span>Confirmer le paiement</span>
                                </template>
                                <template x-if="processing">
                                    <div class="flex items-center gap-2">
                                        <span class="animate-spin inline-block size-4 border-[3px] border-current border-t-transparent text-white rounded-full"></span>
                                        Traitement...
                                    </div>
                                </template>
                            </button>
                        </div>
                    </div>
                </template>

                <template x-if="done">
                    <div class="text-center py-8">
                        <div class="size-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="check" class="size-10"></i>
                        </div>
                        <h4 class="text-xl font-bold text-gray-800 dark:text-white mb-2">Paiement validé !</h4>
                        <p class="text-gray-600 dark:text-neutral-400 mb-6">Votre compte sera mis à jour d'ici quelques instants.</p>
                        <button @click="location.reload()" type="button" class="py-2 px-4 bg-gray-100 text-gray-800 rounded-lg text-sm font-medium hover:bg-gray-200 dark:bg-neutral-700 dark:text-white">Terminer</button>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
@endsection
