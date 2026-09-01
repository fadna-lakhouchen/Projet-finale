@extends('layouts.app')

@section('content')
<div x-data="secondarySyndicsComponent({ 
    items: [ 
        @foreach($secondarySyndics as $syndic) 
        { 
            id: '{{ $syndic->id }}', 
            name: '{{ addslashes($syndic->prenom) }} {{ addslashes($syndic->nom) }}', 
            email: '{{ addslashes($syndic->email) }}',
            telephone: '{{ addslashes($syndic->telephone ?? "N/A") }}',
            cin: '{{ addslashes($syndic->cin ?? "N/A") }}',
            immeubles: [
                @foreach($syndic->secondaryImmeubles as $imm)
                    { id: '{{ $imm->id }}', nom: '{{ addslashes($imm->nom) }}' },
                @endforeach
            ]
        }, 
        @endforeach 
    ] 
})">
    <!-- Header -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight dark:text-white">{{ __('Gestion des Syndics Secondaires') }}</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">{{ __('Gérez les syndics secondaires qui vous assistent dans la gestion de vos immeubles.') }}</p>
        </div>
        <button type="button" data-hs-overlay="#hs-modal-add-secondary-syndic" class="py-2.5 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-xl border border-transparent bg-gradient-to-r from-primary-600 to-purple-600 hover:from-primary-700 hover:to-purple-700 text-white shadow-md shadow-primary-500/10 hover:shadow-lg hover:shadow-primary-500/20 transition-all duration-300 transform hover:-translate-y-0.5">
            <i data-lucide="user-plus" class="size-4"></i>
            {{ __('Ajouter un Syndic Secondaire') }}
        </button>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-800 dark:text-emerald-400 rounded-xl text-sm flex items-center gap-x-3">
        <i data-lucide="check-circle" class="size-5 text-emerald-600 dark:text-emerald-400"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <!-- Table & Filters Container -->
    <div class="flex flex-col bg-white/80 dark:bg-[#0D121F]/90 border border-gray-200/60 dark:border-slate-800/60 rounded-2xl shadow-premium backdrop-blur-md overflow-hidden">
        <!-- Filters -->
        <div class="px-6 py-5 grid gap-4 md:flex md:justify-between md:items-center border-b border-gray-200/60 dark:border-slate-800/60 bg-white/40 dark:bg-[#0D121F]/40">
            <div class="sm:col-span-1 max-w-sm w-full relative">
                <input x-model="search" type="text" class="py-2.5 px-4 ps-11 block w-full border-gray-200/80 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 bg-white/50 dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-neutral-300 dark:placeholder-neutral-500 transition-all duration-200" placeholder="{{ __('Rechercher un nom ou email...') }}">
                <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                    <i data-lucide="search" class="size-4 text-gray-400 dark:text-neutral-500"></i>
                </div>
            </div>

            <div class="sm:col-span-2 md:grow flex justify-end gap-x-3 relative">
                <div class="relative inline-flex">
                    <button @click="openImm = !openImm" @click.outside="openImm = false" type="button" class="py-2.5 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-xl border border-gray-200/80 bg-white/50 hover:bg-gray-50 dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-white dark:hover:bg-slate-900/50 shadow-sm transition-all duration-200">
                        <span x-text="immeubleSelectionne === 'all' ? '{{ __('Filtrer par Immeuble') }}' : immeubleSelectionne" class="truncate max-w-[150px]"></span>
                        <i data-lucide="chevron-down" class="size-4 text-gray-400 transition-transform duration-200" :class="{'rotate-180': openImm}"></i>
                    </button>
                    <div x-show="openImm" x-cloak class="absolute right-0 top-full z-[100] mt-2 w-56 bg-white/95 dark:bg-[#0D121F]/95 border border-gray-200/60 dark:border-slate-800/60 shadow-xl rounded-xl p-1.5 backdrop-blur-md">
                        <div @click="immeubleSelectionne = 'all'; openImm = false" class="cursor-pointer py-2 px-3 rounded-lg text-sm text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800/50 transition-colors">{{ __('Tous les immeubles') }}</div>
                        @foreach($immeubles as $immeuble)
                            <div @click="immeubleSelectionne = '{{ addslashes($immeuble->nom) }}'; openImm = false" class="cursor-pointer py-2 px-3 rounded-lg text-sm text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800/50 transition-colors">{{ $immeuble->nom }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200/60 dark:divide-slate-800/60">
                <thead class="bg-gray-50/50 dark:bg-[#090D16]/40">
                    <tr>
                        <th class="px-6 py-4 text-start text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-slate-400">{{ __('Syndic Secondaire') }}</th>
                        <th class="px-6 py-4 text-start text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-slate-400">{{ __('Coordonnées') }}</th>
                        <th class="px-6 py-4 text-start text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-slate-400">{{ __('Immeuble(s) assigné(s)') }}</th>
                        <th class="px-6 py-4 text-end text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-slate-400">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200/60 dark:divide-slate-800/60">
                    <template x-for="syndic in paginatedItems" :key="syndic.id">
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-900/30 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-x-3.5">
                                    <img class="size-10 rounded-xl shadow-sm border border-gray-200/30" :src="`https://ui-avatars.com/api/?name=${encodeURIComponent(syndic.name)}&background=3b66f5&color=fff&bold=true`">
                                    <div>
                                        <span class="block text-sm font-semibold text-gray-800 dark:text-slate-200" x-text="syndic.name"></span>
                                        <span class="block text-xs text-gray-500 dark:text-slate-400" x-text="`CIN : ${syndic.cin}`"></span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="block text-sm font-medium text-gray-850 dark:text-slate-200" x-text="syndic.email"></span>
                                <span class="block text-xs text-gray-500 dark:text-slate-450" x-text="syndic.telephone"></span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-2 max-w-[280px]">
                                    <template x-for="imm in syndic.immeubles" :key="imm.id">
                                        <div class="flex items-center justify-between gap-x-2 py-1 px-2 rounded-lg bg-gray-50 dark:bg-slate-900/30 border border-gray-200/40 dark:border-slate-800/40">
                                            <span class="text-xs font-semibold text-gray-705 dark:text-slate-300" x-text="imm.nom"></span>
                                            <form :action="`/syndic/secondary-syndics/${syndic.id}/transfer-primary`" method="POST" onsubmit="return confirm('{{ __('Êtes-vous sûr de vouloir transférer la gestion principale de cet immeuble à ce syndic secondaire ? Vous deviendrez alors un syndic secondaire.') }}');" class="inline-block">
                                                @csrf
                                                <input type="hidden" name="immeuble_id" :value="imm.id">
                                                <button type="submit" class="py-1 px-2 inline-flex items-center gap-x-1 text-[10px] font-bold rounded-lg border border-transparent bg-amber-500/10 text-amber-600 hover:bg-amber-500 hover:text-white transition-all duration-200" title="{{ __('Transférer le rôle principal') }}">
                                                    <i data-lucide="arrow-left-right" class="size-3"></i>
                                                    {{ __('Transférer') }}
                                                </button>
                                            </form>
                                        </div>
                                    </template>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                <form :action="`/syndic/secondary-syndics/${syndic.id}`" method="POST" onsubmit="return confirm('{{ __('Retirer l\'accès de ce syndic secondaire ?') }}');" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="py-2 px-3 inline-flex items-center gap-x-1.5 text-xs font-semibold rounded-xl border border-transparent bg-rose-500/10 text-rose-500 hover:bg-rose-500 hover:text-white transition-all duration-200" title="{{ __('Retirer l\'accès') }}">
                                        <i data-lucide="trash-2" class="size-3.5"></i>
                                        {{ __('Retirer l\'accès') }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="filteredItems.length === 0">
                        <td colspan="4" class="px-6 py-8 text-center text-sm text-slate-450 dark:text-neutral-500">
                            <div class="flex flex-col items-center justify-center py-4">
                                <i data-lucide="users" class="size-8 text-slate-300 dark:text-neutral-600 mb-2"></i>
                                <span>{{ __('Aucun syndic secondaire trouvé.') }}</span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination controls -->
        <div class="px-6 py-4 flex items-center justify-between border-t border-gray-200/60 dark:border-slate-800/60 bg-white/40 dark:bg-[#0D121F]/40">
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-slate-400">
                        {{ __('Affichage de') }} <span class="font-semibold text-gray-800 dark:text-white" x-text="filteredItems.length === 0 ? 0 : (currentPage - 1) * perPage + 1"></span> {{ __('à') }} <span class="font-semibold text-gray-800 dark:text-white" x-text="Math.min(currentPage * perPage, filteredItems.length)"></span> {{ __('sur') }} <span class="font-semibold text-gray-800 dark:text-white" x-text="filteredItems.length"></span> {{ __('résultats') }}
                    </p>
                </div>
                <div class="inline-flex gap-x-2">
                    <button @click="if (currentPage > 1) currentPage--" :disabled="currentPage === 1" class="py-2 px-3 inline-flex items-center gap-x-1.5 text-sm font-medium rounded-xl border border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-gray-800 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-800/50 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200">
                        <i data-lucide="chevron-left" class="size-4"></i>
                        {{ __('Précédent') }}
                    </button>
                    
                    <button @click="if (currentPage < Math.ceil(filteredItems.length / perPage)) currentPage++" :disabled="currentPage === Math.ceil(filteredItems.length / perPage) || filteredItems.length === 0" class="py-2 px-3 inline-flex items-center gap-x-1.5 text-sm font-medium rounded-xl border border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-gray-800 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-800/50 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200">
                        {{ __('Suivant') }}
                        <i data-lucide="chevron-right" class="size-4"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Invite Modal -->
    <div id="hs-modal-add-secondary-syndic" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none bg-slate-950/40 backdrop-blur-sm" role="dialog" tabindex="-1">
        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
            <div class="w-full flex flex-col bg-white border border-gray-200/60 dark:border-slate-800/60 shadow-premium rounded-2xl pointer-events-auto dark:bg-[#0D121F]">
                <div class="flex justify-between items-center py-4 px-5 border-b border-gray-200/60 dark:border-slate-800/60">
                    <h3 class="font-bold text-gray-800 dark:text-white text-lg">{{ __('Nouveau Syndic Secondaire') }}</h3>
                    <button type="button" class="size-8 inline-flex justify-center items-center rounded-xl bg-gray-100 text-gray-800 hover:bg-gray-200 dark:bg-slate-800 dark:text-neutral-400 dark:hover:bg-slate-700 transition-colors" data-hs-overlay="#hs-modal-add-secondary-syndic">
                        <i data-lucide="x" class="size-4"></i>
                    </button>
                </div>
                <div class="p-6">
                    <form action="{{ route('syndic.secondary-syndics.store') }}" method="POST">
                        @csrf
                        <div class="grid gap-y-4">
                            <div class="grid sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold mb-2 dark:text-neutral-200">{{ __('Prénom') }}</label>
                                    <input name="prenom" type="text" class="py-2.5 px-4 block w-full border-gray-200 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 bg-white/50 dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-neutral-300 transition-all duration-200" required placeholder="Said">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold mb-2 dark:text-neutral-200">{{ __('Nom') }}</label>
                                    <input name="nom" type="text" class="py-2.5 px-4 block w-full border-gray-200 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 bg-white/50 dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-neutral-300 transition-all duration-200" required placeholder="Tazi">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-2 dark:text-neutral-200">{{ __('Email') }}</label>
                                <input name="email" type="email" class="py-2.5 px-4 block w-full border-gray-200 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 bg-white/50 dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-neutral-300 transition-all duration-200" required placeholder="said@example.com">
                            </div>
                            <div class="grid sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold mb-2 dark:text-neutral-200">{{ __('Téléphone') }}</label>
                                    <input name="telephone" type="text" class="py-2.5 px-4 block w-full border-gray-200 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 bg-white/50 dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-neutral-300 transition-all duration-200" placeholder="0600000000">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold mb-2 dark:text-neutral-200">{{ __('CIN') }}</label>
                                    <input name="cin" type="text" class="py-2.5 px-4 block w-full border-gray-200 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 bg-white/50 dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-neutral-300 transition-all duration-200" placeholder="AB123456">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-2 dark:text-neutral-200">{{ __('Immeuble à assigner') }}</label>
                                <select name="immeuble_id" class="py-2.5 px-4 block w-full border-gray-200 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 bg-white/50 dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-neutral-300 transition-all duration-200" required>
                                    <option value="" disabled selected>{{ __('Sélectionner un immeuble') }}</option>
                                    @foreach($immeubles as $imm)
                                        <option value="{{ $imm->id }}">{{ $imm->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="flex justify-end items-center gap-x-3 mt-6 border-t border-gray-100 dark:border-slate-800/60 pt-4">
                            <button type="button" class="py-2 px-4 text-sm font-medium border border-gray-200 dark:border-slate-800 dark:text-neutral-300 hover:bg-gray-50 dark:hover:bg-slate-900 rounded-xl transition-colors duration-150" data-hs-overlay="#hs-modal-add-secondary-syndic">{{ __('Annuler') }}</button>
                            <button type="submit" class="py-2 px-4 text-sm font-semibold bg-gradient-to-r from-primary-600 to-purple-600 hover:from-primary-700 hover:to-purple-700 text-white rounded-xl shadow-md shadow-primary-500/10 transition-all duration-300">{{ __('Ajouter le Syndic') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('secondarySyndicsComponent', (config) => ({
            items: config.items || [],
            search: '',
            immeubleSelectionne: 'all',
            openImm: false,
            currentPage: 1,
            perPage: 10,
            
            get filteredItems() {
                return this.items.filter(item => {
                    const matchesSearch = item.name.toLowerCase().includes(this.search.toLowerCase()) || 
                                          item.email.toLowerCase().includes(this.search.toLowerCase());
                    const matchesImmeuble = this.immeubleSelectionne === 'all' || 
                                            item.immeubles.some(imm => imm.nom === this.immeubleSelectionne);
                    return matchesSearch && matchesImmeuble;
                });
            },

            get paginatedItems() {
                const start = (this.currentPage - 1) * this.perPage;
                const end = start + this.perPage;
                return this.filteredItems.slice(start, end);
            },

            isRowVisible(id) {
                return this.paginatedItems.some(item => item.id == id);
            }
        }));
    });
</script>
@endpush
