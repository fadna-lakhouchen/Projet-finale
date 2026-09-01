@extends('layouts.app')

@section('content')
<div x-data="{ 
    search: '', 
    filterStatut: 'all', 
    filterCharge: 'all',
    showStat: false,
    showCharge: false,
    isEditing: false,
    syndicEnCours: { id: '', prenom: '', nom: '', email: '', telephone: '', cin: '', ville: '', date_entree: '', date_sortie: '', notes: '', immeubles: [], is_resident: false, resident_immeuble_id: '', resident_numero_appartement: '', resident_date_entree: '' },
    initAjout() {
        this.isEditing = false;
        this.syndicEnCours = { id: '', prenom: '', nom: '', email: '', telephone: '', cin: '', ville: '', date_entree: '', date_sortie: '', notes: '', immeubles: [], is_resident: false, resident_immeuble_id: '', resident_numero_appartement: '', resident_date_entree: '' };
        if (window.editor) window.editor.commands.setContent('');
    },
    initEdit(id, prenom, nom, email, telephone, cin, ville, date_entree, date_sortie, notes, immeubles, is_resident, resident_immeuble_id, resident_numero_appartement, resident_date_entree) {
        this.isEditing = true;
        this.syndicEnCours = { id: id, prenom: prenom, nom: nom, email: email, telephone: telephone, cin: cin, ville: ville, date_entree: date_entree, date_sortie: date_sortie, notes: notes, immeubles: immeubles, is_resident: is_resident, resident_immeuble_id: resident_immeuble_id, resident_numero_appartement: resident_numero_appartement, resident_date_entree: resident_date_entree };
        if (window.editor) window.editor.commands.setContent(notes || '');
    },
    matches(name, email, statut, nbImmeubles) {
        const s = this.search.toLowerCase();
        const matchesSearch = name.toLowerCase().includes(s) || email.toLowerCase().includes(s);
        const matchesStatut = this.filterStatut === 'all' || statut === this.filterStatut;
        let matchesCharge = true;
        if (this.filterCharge === 'Sans immeuble') matchesCharge = nbImmeubles === 0;
        else if (this.filterCharge === '1-3 Immeubles') matchesCharge = nbImmeubles >= 1 && nbImmeubles <= 3;
        else if (this.filterCharge === '4+ Immeubles') matchesCharge = nbImmeubles >= 4;
        return matchesSearch && matchesStatut && matchesCharge;
    }
}">
    <!-- Page Header -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 dark:text-white">{{ __('Gestion des Syndics') }}</h2>
            <p class="text-sm text-slate-500 dark:text-neutral-400">{{ __('Gérez les comptes des syndics référents et supervisez leurs assignations.') }}</p>
        </div>
        <button @click="initAjout()" type="button" data-hs-overlay="#hs-modal-add-syndic" class="py-2.5 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-bold rounded-xl border border-transparent bg-gradient-to-r from-primary-600 to-purple-600 text-white hover:from-primary-700 hover:to-purple-700 shadow-md shadow-primary-500/15 transition-all glow-hover">
            <i data-lucide="plus" class="size-4.5"></i>
            Ajouter un syndic
        </button>
    </div>

    <!-- Table Section Container (Premium Glass Panel) -->
    <div class="flex flex-col border border-gray-200/60 dark:border-slate-800/60 rounded-2xl shadow-premium bg-white dark:bg-[#0D121F] overflow-hidden">
        <!-- Filters Header -->
        <div class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-b border-gray-200/60 dark:border-slate-800/60 bg-slate-50/50 dark:bg-slate-900/30">
            <div class="sm:col-span-1 max-w-sm w-full relative">
                <input x-model="search" type="text" class="py-2.5 px-4 ps-11 block w-full border-gray-200 dark:border-slate-850 dark:bg-[#080B11] dark:text-slate-300 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500" placeholder="{{ __('Rechercher par nom, email...') }}">
                <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                    <i data-lucide="search" class="size-4 text-gray-400"></i>
                </div>
            </div>

            <div class="sm:col-span-2 md:grow flex justify-end gap-x-2">
                <!-- Filter Statut -->
                <div class="relative inline-flex">
                    <button @click="showStat = !showStat; showCharge = false" @click.outside="showStat = false" type="button" class="py-2.5 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-xl border border-gray-200/80 bg-white/80 hover:bg-white text-slate-800 shadow-sm dark:bg-neutral-800 dark:border-neutral-700 dark:text-white transition-all">
                        <span x-text="filterStatut === 'all' ? 'Filtrer par Statut' : filterStatut"></span>
                        <i data-lucide="chevron-down" :class="showStat ? 'rotate-180' : ''" class="size-4 transition-transform text-gray-400"></i>
                    </button>
                    <div x-show="showStat" class="absolute right-0 top-full z-[100] mt-2 min-w-48 bg-white border border-gray-200 shadow-xl rounded-2xl p-1.5 dark:bg-neutral-900 dark:border-neutral-800" style="display: none;"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100">
                        <div @click="filterStatut = 'all'; showStat = false" class="cursor-pointer flex items-center py-2 px-3 rounded-xl text-sm font-medium text-slate-700 hover:bg-slate-50 dark:text-neutral-350 dark:hover:bg-neutral-800/60">{{ __('Tous les statuts') }}</div>
                        <div @click="filterStatut = 'Actif'; showStat = false" class="cursor-pointer flex items-center py-2 px-3 rounded-xl text-sm font-medium text-slate-700 hover:bg-slate-50 dark:text-neutral-350 dark:hover:bg-neutral-800/60">Actif</div>
                        <div @click="filterStatut = 'Inactif'; showStat = false" class="cursor-pointer flex items-center py-2 px-3 rounded-xl text-sm font-medium text-slate-700 hover:bg-slate-50 dark:text-neutral-350 dark:hover:bg-neutral-800/60">{{ __('Inactif') }}</div>
                    </div>
                </div>

                <!-- Filter Charge -->
                <div class="relative inline-flex">
                    <button @click="showCharge = !showCharge; showStat = false" @click.outside="showCharge = false" type="button" class="py-2.5 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-xl border border-gray-200/80 bg-white/80 hover:bg-white text-slate-800 shadow-sm dark:bg-neutral-800 dark:border-neutral-700 dark:text-white transition-all">
                        <span x-text="filterCharge === 'all' ? 'Filtrer par Charge' : filterCharge"></span>
                        <i data-lucide="chevron-down" :class="showCharge ? 'rotate-180' : ''" class="size-4 transition-transform text-gray-400"></i>
                    </button>
                    <div x-show="showCharge" class="absolute right-0 top-full z-[100] mt-2 min-w-48 bg-white border border-gray-200 shadow-xl rounded-2xl p-1.5 dark:bg-neutral-900 dark:border-neutral-800" style="display: none;"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100">
                        <div @click="filterCharge = 'all'; showCharge = false" class="cursor-pointer flex items-center py-2 px-3 rounded-xl text-sm font-medium text-slate-700 hover:bg-slate-50 dark:text-neutral-350 dark:hover:bg-neutral-800/60">{{ __('Toute charge') }}</div>
                        <div @click="filterCharge = 'Sans immeuble'; showCharge = false" class="cursor-pointer flex items-center py-2 px-3 rounded-xl text-sm font-medium text-slate-700 hover:bg-slate-50 dark:text-neutral-350 dark:hover:bg-neutral-800/60">{{ __('Sans immeuble') }}</div>
                        <div @click="filterCharge = '1-3 Immeubles'; showCharge = false" class="cursor-pointer flex items-center py-2 px-3 rounded-xl text-sm font-medium text-slate-700 hover:bg-slate-50 dark:text-neutral-350 dark:hover:bg-neutral-800/60">1-3 Immeubles</div>
                        <div @click="filterCharge = '4+ Immeubles'; showCharge = false" class="cursor-pointer flex items-center py-2 px-3 rounded-xl text-sm font-medium text-slate-700 hover:bg-slate-50 dark:text-neutral-350 dark:hover:bg-neutral-800/60">4+ Immeubles</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Grid -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-250 dark:divide-slate-800">
                <thead class="bg-slate-50 dark:bg-slate-900/50">
                    <tr>
                        <th scope="col" class="px-6 py-3.5 text-start text-xs font-bold text-slate-400 uppercase dark:text-neutral-450 tracking-wider">{{ __('Nom & Contact') }}</th>
                        <th scope="col" class="px-6 py-3.5 text-start text-xs font-bold text-slate-400 uppercase dark:text-neutral-450 tracking-wider">{{ __('Immeuble(s) Assigné(s)') }}</th>
                        <th scope="col" class="px-6 py-3.5 text-start text-xs font-bold text-slate-400 uppercase dark:text-neutral-450 tracking-wider">{{ __('Détails') }}</th>
                        <th scope="col" class="px-6 py-3.5 text-start text-xs font-bold text-slate-400 uppercase dark:text-neutral-450 tracking-wider">Abonnement</th>
                        <th scope="col" class="px-6 py-3.5 text-start text-xs font-bold text-slate-400 uppercase dark:text-neutral-450 tracking-wider">{{ __('Statut') }}</th>
                        <th scope="col" class="px-6 py-3.5 text-end text-xs font-bold text-slate-400 uppercase dark:text-neutral-450 tracking-wider">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200/60 dark:divide-slate-800/60">
                    @foreach($syndics as $syndic)
                    @php
                        $nbImmeubles = $syndic->immeubles->count();
                        $statut = $syndic->is_active ? 'Actif' : 'Inactif';
                        $subInfo = $syndic->calculateTotalSubscription();
                    @endphp
                    <tr x-show="matches('{{ $syndic->prenom }} {{ $syndic->nom }}', '{{ $syndic->email }}', '{{ $statut }}', {{ $nbImmeubles }})" class="hover:bg-slate-50/50 dark:hover:bg-slate-800/10 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-x-3.5">
                                <div class="size-10 rounded-xl bg-gradient-to-tr from-primary-500 to-indigo-500 text-white font-bold flex items-center justify-center shrink-0 border border-primary-500/10 shadow-sm">
                                    {{ substr($syndic->prenom, 0, 1) }}{{ substr($syndic->nom, 0, 1) }}
                                </div>
                                <div class="grow">
                                    <span class="block text-sm font-bold text-slate-800 dark:text-neutral-250 leading-none mb-1">{{ $syndic->prenom }} {{ $syndic->nom }}</span>
                                    <span class="inline-flex items-center gap-x-1.5 text-[11px] text-slate-400 dark:text-neutral-500 font-semibold">
                                        <i data-lucide="mail" class="size-3"></i>{{ $syndic->email }}
                                    </span>
                                    @if($syndic->telephone)
                                        <span class="block text-[11px] text-slate-400 dark:text-neutral-500 font-semibold mt-0.5">
                                            <i data-lucide="phone" class="inline size-3 me-1"></i>{{ $syndic->telephone }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-wrap gap-1.5 max-w-[240px]">
                                @forelse($syndic->immeubles as $immeuble)
                                    <span class="py-1 px-2.5 text-[11px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-lg border border-slate-200/50 dark:border-slate-700/50">{{ $immeuble->nom }}</span>
                                @empty
                                    <span class="text-xs font-semibold text-slate-400 italic">Aucun immeuble assigné</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="block text-sm font-bold text-slate-700 dark:text-neutral-350">
                                @if($syndic->ville) {{ $syndic->ville }} @else <span class="text-slate-400 font-medium italic">{{ __('Ville N/A') }}</span> @endif
                            </span>
                            <span class="block text-[10px] text-slate-400 font-semibold mt-0.5">
                                @if($syndic->date_entree) Assignation: {{ \Carbon\Carbon::parse($syndic->date_entree)->format('d/m/Y') }} @endif
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap" x-data="{ open: false }">
                            <div class="relative inline-block">
                                <button @click="open = !open" @click.outside="open = false" type="button" class="text-sm font-bold text-slate-700 dark:text-neutral-300 hover:text-primary-600 dark:hover:text-primary-400 focus:outline-none flex items-center gap-x-1 border-b border-dashed border-slate-400">
                                    {{ number_format($subInfo['total_price'], 2) }} {{ __('DH') }}
                                    <i data-lucide="info" class="size-3.5 text-slate-400"></i>
                                </button>
                                
                                <div x-show="open" style="display: none;" class="absolute left-0 bottom-full z-[120] mb-2 w-64 bg-slate-900 text-xs text-white rounded-xl p-3 shadow-xl dark:bg-neutral-900 border border-slate-800 dark:border-neutral-800"
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100">
                                    <div class="font-bold border-b border-slate-800 dark:border-neutral-850 pb-1.5 mb-1.5 flex justify-between items-center">
                                        <span>{{ __('Détail Abonnement') }}</span>
                                        <button @click="open = false" class="text-slate-400 hover:text-white"><i data-lucide="x" class="size-3"></i></button>
                                    </div>
                                    <div class="space-y-2 max-h-40 overflow-y-auto pr-1">
                                        @forelse($subInfo['breakdown'] as $item)
                                            <div class="border-b border-slate-800 dark:border-neutral-850 pb-2 last:border-0 last:pb-0">
                                                <div class="font-semibold text-primary-400">{{ $item['immeuble']->nom }}</div>
                                                <div class="flex justify-between text-[11px] text-slate-400 mt-1">
                                                    <span>{{ $item['calculation']['residents_count'] }} {{ __('Résidents') }} × 4 {{ __('DH') }}</span>
                                                    <span>{{ number_format($item['calculation']['residents_price'], 2) }} {{ __('DH') }}</span>
                                                </div>
                                                <div class="flex justify-between text-[11px] text-slate-400">
                                                    <span>{{ $item['calculation']['syndics_count'] }} {{ __('Syndics') }} × 8 {{ __('DH') }}</span>
                                                    <span>{{ number_format($item['calculation']['syndics_price'], 2) }} {{ __('DH') }}</span>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="text-slate-400 italic text-[11px]">{{ __('Aucun immeuble géré comme principal') }}</div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('admin.syndics.abonnements', $syndic->id) }}" class="inline-flex items-center gap-x-1 text-xs text-primary-500 hover:text-primary-400 font-bold mt-1.5 transition-colors">
                                <i data-lucide="history" class="size-3.5"></i> {{ __('Historique Facturation') }}
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center gap-x-1.5 py-1 px-3 rounded-full text-xs font-bold {{ $syndic->is_active ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20' : 'bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20' }}">
                                <span class="size-1.5 inline-block rounded-full bg-current"></span>
                                {{ $statut }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                            <div class="inline-flex items-center gap-x-2">
                                <form action="{{ route('admin.syndics.toggle-status', $syndic->id) }}" method="POST" onsubmit="return confirm('{{ $syndic->is_active ? 'Suspendre ce compte syndic ?' : 'Réactiver ce compte syndic ?' }}');">
                                    @csrf
                                    <button type="submit" class="p-2 inline-flex items-center justify-center gap-x-2 rounded-xl border {{ $syndic->is_active ? 'border-amber-200/40 bg-amber-50 text-amber-600 hover:bg-amber-100 hover:text-amber-700 dark:bg-amber-950/20 dark:text-amber-400 dark:border-amber-950/30 dark:hover:bg-amber-950/40' : 'border-emerald-200/40 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 hover:text-emerald-700 dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-950/30 dark:hover:bg-emerald-950/40' }} transition-all" title="{{ $syndic->is_active ? 'Suspendre' : 'Activer' }}">
                                        @if($syndic->is_active)
                                            <i data-lucide="ban" class="size-4"></i>
                                        @else
                                            <i data-lucide="check-circle" class="size-4"></i>
                                        @endif
                                    </button>
                                </form>
                                <button @click="initEdit({{ json_encode($syndic->id) }}, {{ json_encode($syndic->prenom) }}, {{ json_encode($syndic->nom) }}, {{ json_encode($syndic->email) }}, {{ json_encode($syndic->telephone) }}, {{ json_encode($syndic->cin) }}, {{ json_encode($syndic->ville) }}, {{ json_encode($syndic->date_entree) }}, {{ json_encode($syndic->date_sortie) }}, {{ json_encode($syndic->notes ?? '') }}, {{ json_encode($syndic->immeubles->pluck('id')) }}, {{ $syndic->hasRole('resident') ? 'true' : 'false' }}, {{ json_encode($syndic->appartements->first() ? $syndic->appartements->first()->immeuble_id : '') }}, {{ json_encode($syndic->appartements->first() ? $syndic->appartements->first()->numero : '') }}, {{ json_encode($syndic->appartements->first() ? $syndic->appartements->first()->pivot->date_entree : '') }})" type="button" data-hs-overlay="#hs-modal-add-syndic" class="p-2 inline-flex items-center justify-center gap-x-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 dark:bg-slate-800/40 dark:border-slate-800 dark:text-slate-300 dark:hover:bg-slate-800 transition-all">
                                    <i data-lucide="edit-2" class="size-4"></i>
                                </button>
                                <form action="{{ route('admin.syndics.destroy', $syndic->id) }}" method="POST" onsubmit="return confirm('Confirmer la suppression définitive de ce syndic ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 inline-flex items-center justify-center gap-x-2 rounded-xl border border-red-200/40 bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 dark:bg-red-950/20 dark:text-red-400 dark:border-red-950/30 dark:hover:bg-red-950/40 transition-all">
                                        <i data-lucide="trash-2" class="size-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Layout (Optimized Glassmorphism) -->
    <div id="hs-modal-add-syndic" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none" role="dialog" tabindex="-1">
        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
            <div class="w-full flex flex-col bg-white border border-gray-200/60 shadow-xl rounded-2xl pointer-events-auto dark:bg-slate-900 dark:border-slate-800/60 backdrop-blur-xl">
                <div class="flex justify-between items-center py-4 px-6 border-b border-gray-200/50 dark:border-slate-800/50">
                    <h3 class="font-bold text-slate-800 dark:text-white" x-text="isEditing ? 'Modifier le compte Syndic' : 'Créer un compte Syndic'"></h3>
                    <button type="button" class="size-8 inline-flex justify-center items-center rounded-xl bg-slate-100 text-slate-800 hover:bg-slate-200 focus:outline-none dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-neutral-400" data-hs-overlay="#hs-modal-add-syndic">
                        <i data-lucide="x" class="size-4"></i>
                    </button>
                </div>
                <div class="p-6 overflow-y-auto max-h-[75vh]">
                    <form :action="isEditing ? `/admin/syndics/${syndicEnCours.id}` : '{{ route('admin.syndics.store') }}'" method="POST">
                        @csrf
                        <template x-if="isEditing">
                            <input type="hidden" name="_method" value="PUT">
                        </template>
                        <input type="hidden" name="id" x-model="syndicEnCours.id">
                        
                        <div class="grid gap-y-5">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold mb-2 dark:text-white">{{ __('Prénom') }}</label>
                                    <input name="prenom" x-model="syndicEnCours.prenom" type="text" class="py-2.5 px-4 block w-full border-gray-200 dark:border-slate-850 dark:bg-[#080B11] dark:text-slate-300 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold mb-2 dark:text-white">{{ __('Nom') }}</label>
                                    <input name="nom" x-model="syndicEnCours.nom" type="text" class="py-2.5 px-4 block w-full border-gray-200 dark:border-slate-850 dark:bg-[#080B11] dark:text-slate-300 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500" required>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-2 dark:text-white">Adresse Email (Connexion)</label>
                                <input name="email" x-model="syndicEnCours.email" type="email" class="py-2.5 px-4 block w-full border-gray-200 dark:border-slate-850 dark:bg-[#080B11] dark:text-slate-300 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500" placeholder="youssef.khadir@email.com" required>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold mb-2 dark:text-white">{{ __('Téléphone') }}</label>
                                    <input name="telephone" x-model="syndicEnCours.telephone" type="text" class="py-2.5 px-4 block w-full border-gray-200 dark:border-slate-850 dark:bg-[#080B11] dark:text-slate-300 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500" placeholder="Ex: 0600000000">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold mb-2 dark:text-white">{{ __('CIN') }}</label>
                                    <input name="cin" x-model="syndicEnCours.cin" type="text" class="py-2.5 px-4 block w-full border-gray-200 dark:border-slate-850 dark:bg-[#080B11] dark:text-slate-300 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500" placeholder="Ex: AB123456">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold mb-2 dark:text-white">{{ __('Ville') }}</label>
                                    <input name="ville" x-model="syndicEnCours.ville" type="text" class="py-2.5 px-4 block w-full border-gray-200 dark:border-slate-850 dark:bg-[#080B11] dark:text-slate-300 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500" placeholder="Ex: Casablanca">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold mb-2 dark:text-white">{{ __('Date d\'entrée') }}</label>
                                    <div class="relative">
                                        <input name="date_entree" x-model="syndicEnCours.date_entree" x-init="flatpickr($el, { locale: '{{ app()->getLocale() }}', dateFormat: 'Y-m-d', onChange: (selectedDates, dateStr) => { syndicEnCours.date_entree = dateStr; } }); $watch('syndicEnCours.date_entree', val => $el._flatpickr.setDate(val))" type="text" class="py-2.5 px-4 ps-11 block w-full border-gray-200 dark:border-slate-850 dark:bg-[#080B11] dark:text-slate-300 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500">
                                        <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                                            <i data-lucide="calendar" class="size-4 text-gray-400 dark:text-neutral-500"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div x-show="isEditing">
                                <label class="block text-sm font-semibold mb-2 dark:text-white">{{ __('Date de sortie') }}</label>
                                <div class="relative">
                                    <input name="date_sortie" x-model="syndicEnCours.date_sortie" x-init="flatpickr($el, { locale: '{{ app()->getLocale() }}', dateFormat: 'Y-m-d', onChange: (selectedDates, dateStr) => { syndicEnCours.date_sortie = dateStr; } }); $watch('syndicEnCours.date_sortie', val => $el._flatpickr.setDate(val))" type="text" class="py-2.5 px-4 ps-11 block w-full border-gray-200 dark:border-slate-850 dark:bg-[#080B11] dark:text-slate-300 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500">
                                    <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                                        <i data-lucide="calendar" class="size-4 text-gray-400 dark:text-neutral-500"></i>
                                    </div>
                                </div>
                                <p class="text-xs text-slate-400 mt-1.5 font-medium">{{ __('Renseigner uniquement si le syndic quitte ses fonctions de gestion.') }}</p>
                            </div>
                            
                             <!-- Est aussi Résident Section -->
                            <div class="space-y-4 border border-gray-200/60 dark:border-slate-800/60 rounded-2xl p-4 bg-slate-50/[0.02]">
                                <div class="flex items-center">
                                    <input type="checkbox" name="is_resident" value="1" x-model="syndicEnCours.is_resident" id="is_resident" class="shrink-0 mt-0.5 border-gray-200 rounded-lg text-primary-600 focus:ring-primary-500 dark:bg-neutral-800 dark:border-neutral-700">
                                    <label for="is_resident" class="text-sm font-semibold text-slate-700 ms-3 dark:text-neutral-400 cursor-pointer">
                                        {{ __('Est aussi Résident (Habitant)') }}
                                    </label>
                                </div>
                                
                                <div x-show="syndicEnCours.is_resident" class="grid sm:grid-cols-2 gap-4 mt-2" x-transition>
                                    <div>
                                        <label class="block text-sm font-semibold mb-2 dark:text-white">{{ __('Immeuble') }}</label>
                                        <div class="relative" x-data="{ open: false }">
                                            <button @click="open = !open" @click.outside="open = false" type="button" class="py-2.5 px-4 flex justify-between items-center w-full border-gray-200 dark:border-slate-850 dark:bg-[#080B11] dark:text-slate-300 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 text-left">
                                                <span x-text="syndicEnCours.resident_immeuble_id ? ({ @foreach($immeubles as $imm) '{{ $imm->id }}': '{{ addslashes($imm->nom) }}', @endforeach }[syndicEnCours.resident_immeuble_id] || '{{ __('Sélectionner un immeuble') }}') : '{{ __('Sélectionner un immeuble') }}'"></span>
                                                <i data-lucide="chevron-down" class="size-4 text-gray-400 transition-transform duration-200" :class="{'rotate-180': open}"></i>
                                            </button>
                                            <input type="hidden" name="resident_immeuble_id" :value="syndicEnCours.resident_immeuble_id">
                                            <div x-show="open" x-cloak class="absolute left-0 top-full z-[100] mt-2 w-full max-h-60 overflow-y-auto bg-white dark:bg-[#0D121F] border border-gray-200/60 dark:border-slate-800/60 shadow-xl rounded-xl p-1.5 backdrop-blur-md" style="display: none;">
                                                @foreach($immeubles as $immeuble)
                                                    <div @click="syndicEnCours.resident_immeuble_id = '{{ $immeuble->id }}'; open = false" class="cursor-pointer py-2 px-3 rounded-lg text-sm text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800/50 transition-colors">{{ $immeuble->nom }}</div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold mb-2 dark:text-white">{{ __('Numéro d\'appartement') }}</label>
                                        <input name="resident_numero_appartement" x-model="syndicEnCours.resident_numero_appartement" type="text" class="py-2.5 px-4 block w-full border-gray-200 dark:border-slate-850 dark:bg-[#080B11] dark:text-slate-300 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500" placeholder="Ex: 5, 12B..." :required="syndicEnCours.is_resident">
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label class="block text-sm font-semibold mb-2 dark:text-white">{{ __('Date d\'entrée (en tant que résident)') }}</label>
                                        <div class="relative">
                                            <input name="resident_date_entree" x-model="syndicEnCours.resident_date_entree" x-init="flatpickr($el, { locale: '{{ app()->getLocale() }}', dateFormat: 'Y-m-d', onChange: (selectedDates, dateStr) => { syndicEnCours.resident_date_entree = dateStr; } }); $watch('syndicEnCours.resident_date_entree', val => $el._flatpickr.setDate(val))" type="text" class="py-2.5 px-4 ps-11 block w-full border-gray-200 dark:border-slate-850 dark:bg-[#080B11] dark:text-slate-300 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500" :required="syndicEnCours.is_resident">
                                            <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                                                <i data-lucide="calendar" class="size-4 text-gray-400 dark:text-neutral-500"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Assignation d'immeubles (Premium multi select check panel) -->
                            <div>
                                <label class="block text-sm font-semibold mb-2 dark:text-white">Assignation des immeubles</label>
                                <div class="bg-slate-50/50 border border-gray-200 rounded-2xl p-4 max-h-48 overflow-y-auto dark:bg-[#080B11] dark:border-slate-850">
                                    @forelse($immeubles as $immeuble)
                                    <div class="flex items-center mb-2.5 last:mb-0">
                                        <input type="checkbox" name="immeubles[]" value="{{ $immeuble->id }}" x-model="syndicEnCours.immeubles" id="imm-{{ $immeuble->id }}" class="shrink-0 mt-0.5 border-gray-200 rounded-lg text-primary-600 focus:ring-primary-500 dark:bg-neutral-800 dark:border-neutral-700">
                                        <label for="imm-{{ $immeuble->id }}" class="text-sm font-semibold text-slate-700 ms-3 dark:text-neutral-400 cursor-pointer">
                                            {{ $immeuble->nom }} <span class="text-xs text-slate-400 font-medium">({{ $immeuble->ville ?? 'Maroc' }})</span>
                                        </label>
                                    </div>
                                    @empty
                                    <p class="text-xs font-semibold text-slate-400 dark:text-neutral-400 italic">Aucun immeuble enregistré pour le moment.</p>
                                    @endforelse
                                </div>
                            </div>
                            
                            <!-- Tiptap Notes (Premium glass panel) -->
                            <div>
                                <label class="block text-sm font-semibold mb-2 dark:text-white">{{ __('Observations / Notes') }}</label>
                                <input type="hidden" name="notes" x-model="syndicEnCours.notes" style="display: none !important;">
                                <div class="bg-slate-50/50 border border-gray-200 rounded-2xl overflow-hidden dark:bg-[#080B11] dark:border-slate-850">
                                    <div id="hs-editor-tiptap">
                                        <div class="sticky top-0 bg-white flex align-middle gap-x-0.5 border-b border-gray-200 p-2 dark:bg-[#0D121F] dark:border-slate-850">
                                            <button class="size-8 inline-flex justify-center items-center rounded-xl text-slate-700 hover:bg-slate-100 focus:outline-none dark:text-white dark:hover:bg-slate-800" type="button" data-hs-editor-bold><i data-lucide="bold" class="size-4"></i></button>
                                            <button class="size-8 inline-flex justify-center items-center rounded-xl text-slate-700 hover:bg-slate-100 focus:outline-none dark:text-white dark:hover:bg-slate-800" type="button" data-hs-editor-italic><i data-lucide="italic" class="size-4"></i></button>
                                            <button class="size-8 inline-flex justify-center items-center rounded-xl text-slate-700 hover:bg-slate-100 focus:outline-none dark:text-white dark:hover:bg-slate-800" type="button" data-hs-editor-underline><i data-lucide="underline" class="size-4"></i></button>
                                            <button class="size-8 inline-flex justify-center items-center rounded-xl text-slate-700 hover:bg-slate-100 focus:outline-none dark:text-white dark:hover:bg-slate-800" type="button" data-hs-editor-ol><i data-lucide="list-ordered" class="size-4"></i></button>
                                            <button class="size-8 inline-flex justify-center items-center rounded-xl text-slate-700 hover:bg-slate-100 focus:outline-none dark:text-white dark:hover:bg-slate-800" type="button" data-hs-editor-ul><i data-lucide="list" class="size-4"></i></button>
                                        </div>
                                        <div class="h-32 overflow-auto p-4 focus:outline-none tiptap-content text-sm text-slate-800 dark:text-slate-200" data-hs-editor-field></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-end items-center gap-x-3 mt-8 border-t border-slate-100 dark:border-slate-800 pt-5">
                            <button type="button" class="py-2.5 px-4 text-sm font-semibold border rounded-xl border-slate-200 hover:bg-slate-50 dark:border-slate-800 dark:hover:bg-slate-800 dark:text-white transition-colors" data-hs-overlay="#hs-modal-add-syndic">{{ __('Annuler') }}</button>
                            <button type="submit" class="py-2.5 px-4 text-sm font-bold bg-primary-600 text-white rounded-xl hover:bg-primary-700 shadow-md shadow-primary-500/10 transition-colors" x-text="isEditing ? 'Sauvegarder les modifications' : 'Enregistrer'"></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .tiptap-content .ProseMirror { outline: none; min-height: 100%; }
    .tiptap-content ul { list-style-type: disc; padding-left: 1.5rem; }
    .tiptap-content ol { list-style-type: decimal; padding-left: 1.5rem; }
</style>
@endpush

@push('scripts')
<script type="module">
    import { Editor } from 'https://esm.sh/@tiptap/core';
    import StarterKit from 'https://esm.sh/@tiptap/starter-kit';
    import Underline from 'https://esm.sh/@tiptap/extension-underline';

    const editor = new Editor({
        element: document.querySelector('#hs-editor-tiptap [data-hs-editor-field]'),
        extensions: [StarterKit, Underline],
        content: '',
        onUpdate({ editor }) {
            const html = editor.getHTML();
            const el = document.querySelector('[x-data]');
            const alpineData = Alpine.$data(el);
            if (alpineData) alpineData.syndicEnCours.notes = html;
        }
    });
    window.editor = editor;

    const actions = [
        { id: 'bold', action: () => editor.chain().focus().toggleBold().run() }},
        { id: 'italic', action: () => editor.chain().focus().toggleItalic().run() }},
        { id: 'underline', action: () => editor.chain().focus().toggleUnderline().run() }},
        { id: 'ol', action: () => editor.chain().focus().toggleOrderedList().run() }},
        { id: 'ul', action: () => editor.chain().focus().toggleBulletList().run() }}
    ];

    actions.forEach(({ id, action }) => {
        document.querySelector(`[data-hs-editor-${id}]`)?.addEventListener('click', action);
    });
</script>
@endpush
