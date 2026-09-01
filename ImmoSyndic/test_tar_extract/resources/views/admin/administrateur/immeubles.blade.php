@extends('layouts.app')

@section('content')
<div x-data="adminImmeubles">
    <!-- Page Header -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 dark:text-white">{{ __('Supervision des Immeubles') }}</h2>
            <p class="text-sm text-slate-500 dark:text-neutral-400">{{ __('Vue d\'ensemble et administration de tous les bâtiments enregistrés.') }}</p>
        </div>
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

    <!-- Table Section (Premium Glass Panel) -->
    <div class="flex flex-col border border-gray-200/60 dark:border-slate-800/60 rounded-2xl shadow-premium bg-white dark:bg-[#0D121F] overflow-hidden">
        <!-- Filters Header -->
        <div class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-b border-gray-200/60 dark:border-slate-800/60 bg-slate-50/50 dark:bg-slate-900/30">
            <div class="sm:col-span-1 max-w-sm w-full relative">
                <input x-model="search" type="text" class="py-2.5 px-4 ps-11 block w-full border-gray-200 dark:border-slate-850 dark:bg-[#080B11] dark:text-slate-300 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500" placeholder="{{ __('Rechercher par nom ou adresse...') }}">
                <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                    <i data-lucide="search" class="size-4 text-gray-400"></i>
                </div>
            </div>

            <div class="sm:col-span-2 md:grow flex justify-end gap-x-2">
                <!-- Dropdown Syndic -->
                <div class="relative inline-flex">
                    <button @click="showSyndic = !showSyndic; showStatut = false" @click.outside="showSyndic = false" type="button" class="py-2.5 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-xl border border-gray-200/80 bg-white/80 hover:bg-white text-slate-800 shadow-sm dark:bg-neutral-800 dark:border-neutral-700 dark:text-white transition-all">
                        <span x-text="filterSyndic === 'all' ? 'Filtrer par Syndic' : filterSyndic"></span>
                        <i data-lucide="chevron-down" :class="showSyndic ? 'rotate-180' : ''" class="size-4 transition-transform text-gray-400"></i>
                    </button>
                    <div x-show="showSyndic" class="absolute right-0 top-full z-[100] mt-2 w-60 bg-white border border-gray-200 shadow-xl rounded-2xl p-1.5 dark:bg-neutral-900 dark:border-neutral-800" style="display: none;"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100">
                        <div @click="filterSyndic = 'all'; showSyndic = false" class="cursor-pointer flex items-center py-2 px-3 rounded-xl text-sm font-medium text-slate-700 hover:bg-slate-50 dark:text-neutral-350 dark:hover:bg-neutral-800/60">{{ __('Tous les syndics') }}</div>
                        @foreach($syndics as $s)
                            <div @click="filterSyndic = '{{ $s->prenom }} {{ $s->nom }}'; showSyndic = false" class="cursor-pointer flex items-center py-2 px-3 rounded-xl text-sm font-medium text-slate-700 hover:bg-slate-50 dark:text-neutral-350 dark:hover:bg-neutral-800/60">
                                {{ $s->prenom }} {{ $s->nom }}
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Dropdown Statut -->
                <div class="relative inline-flex">
                    <button @click="showStatut = !showStatut; showSyndic = false" @click.outside="showStatut = false" type="button" class="py-2.5 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-xl border border-gray-200/80 bg-white/80 hover:bg-white text-slate-800 shadow-sm dark:bg-neutral-800 dark:border-neutral-700 dark:text-white transition-all">
                        <span x-text="filterStatut === 'all' ? 'Statut' : filterStatut"></span>
                        <i data-lucide="chevron-down" :class="showStatut ? 'rotate-180' : ''" class="size-4 transition-transform text-gray-400"></i>
                    </button>
                    <div x-show="showStatut" class="absolute right-0 top-full z-[100] mt-2 w-48 bg-white border border-gray-200 shadow-xl rounded-2xl p-1.5 dark:bg-neutral-900 dark:border-neutral-800" style="display: none;"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100">
                        <div @click="filterStatut = 'all'; showStatut = false" class="cursor-pointer flex items-center py-2 px-3 rounded-xl text-sm font-medium text-slate-700 hover:bg-slate-50 dark:text-neutral-350 dark:hover:bg-neutral-800/60">{{ __('Tous les statuts') }}</div>
                        <div @click="filterStatut = 'Sain'; showStatut = false" class="cursor-pointer flex items-center py-2 px-3 rounded-xl text-sm font-medium text-slate-700 hover:bg-slate-50 dark:text-neutral-350 dark:hover:bg-neutral-800/60">{{ __('Sain') }}</div>
                        <div @click="filterStatut = 'En travaux'; showStatut = false" class="cursor-pointer flex items-center py-2 px-3 rounded-xl text-sm font-medium text-slate-700 hover:bg-slate-50 dark:text-neutral-350 dark:hover:bg-neutral-800/60">{{ __('En travaux') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Grid -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-250 dark:divide-slate-800">
                <thead class="bg-slate-50 dark:bg-slate-900/50">
                    <tr>
                        <th scope="col" class="px-6 py-3.5 text-start text-xs font-bold text-slate-400 uppercase dark:text-neutral-450 tracking-wider">{{ __('Immeuble') }}</th>
                        <th scope="col" class="px-6 py-3.5 text-start text-xs font-bold text-slate-400 uppercase dark:text-neutral-450 tracking-wider">{{ __('Syndic Responsable') }}</th>
                        <th scope="col" class="px-6 py-3.5 text-start text-xs font-bold text-slate-400 uppercase dark:text-neutral-450 tracking-wider">{{ __('Logements') }}</th>
                        <th scope="col" class="px-6 py-3.5 text-start text-xs font-bold text-slate-400 uppercase dark:text-neutral-450 tracking-wider">{{ __('Statut') }}</th>
                        <th scope="col" class="px-6 py-3.5 text-end text-xs font-bold text-slate-400 uppercase dark:text-neutral-450 tracking-wider">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200/60 dark:divide-slate-800/60">
                    @foreach($immeubles as $immeuble)
                    <tr x-show="matches('{{ addslashes($immeuble->nom) }}', '{{ addslashes($immeuble->adresse) }}', '{{ addslashes($immeuble->syndic ? $immeuble->syndic->prenom . ' ' . $immeuble->syndic->nom : 'N/A') }}', 'Sain')" class="hover:bg-slate-50/50 dark:hover:bg-slate-800/10 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-x-3">
                                <div class="size-9 rounded-xl bg-primary-500/10 text-primary-500 flex items-center justify-center shrink-0">
                                    <i data-lucide="building" class="size-4.5"></i>
                                </div>
                                <div class="grow">
                                    <span class="block text-sm font-bold text-slate-800 dark:text-neutral-250">{{ $immeuble->nom }}</span>
                                    <span class="block text-[11px] text-slate-400 dark:text-neutral-500 font-semibold leading-none mt-1">
                                        @if($immeuble->ville) {{ $immeuble->ville }} • @endif {{ $immeuble->adresse }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($immeuble->syndic)
                                <div class="flex items-center gap-x-2.5">
                                    <div class="size-7 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-650 dark:text-slate-300 font-bold flex items-center justify-center text-xs shrink-0 border border-slate-200/40 dark:border-slate-700/40">
                                        {{ substr($immeuble->syndic->prenom, 0, 1) }}{{ substr($immeuble->syndic->nom, 0, 1) }}
                                    </div>
                                    <span class="text-sm font-semibold text-slate-700 dark:text-neutral-300">{{ $immeuble->syndic->prenom }} {{ $immeuble->syndic->nom }}</span>
                                </div>
                            @else
                                <span class="inline-flex items-center gap-x-1.5 py-1 px-2 rounded-full text-xs font-medium bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20">
                                    Non assigné
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-slate-700 dark:text-neutral-300">{{ $immeuble->nombre_appartements }} {{ trans_choice('appartement|appartements', $immeuble->nombre_appartements) }}</span>
                                <span class="text-[10px] text-slate-400 font-semibold mt-0.5">{{ $immeuble->nombre_etages }} {{ __('Étages') }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center gap-x-1.5 py-1 px-3 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                                <span class="size-1 inline-block bg-emerald-500 rounded-full"></span>
                                Sain
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                            <div class="inline-flex items-center gap-x-2">
                                <button @click="initEdit('{{ $immeuble->id }}', '{{ addslashes($immeuble->nom) }}', '{{ addslashes($immeuble->adresse) }}', '{{ addslashes($immeuble->ville) }}', '{{ $immeuble->syndic_id }}', '{{ $immeuble->nombre_etages }}', '{{ $immeuble->nombre_appartements }}')" type="button" data-hs-overlay="#hs-modal-add-immeuble" class="p-2 inline-flex items-center justify-center gap-x-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 dark:bg-slate-800/40 dark:border-slate-800 dark:text-slate-300 dark:hover:bg-slate-800 transition-all">
                                    <i data-lucide="edit-2" class="size-4"></i>
                                </button>
                                <form action="{{ route('admin.immeubles.destroy', $immeuble->id) }}" method="POST" onsubmit="return confirm('Confirmer la suppression définitive de cet immeuble ?');">
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
    <div id="hs-modal-add-immeuble" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none" role="dialog" tabindex="-1">
        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
            <div class="w-full flex flex-col bg-white border border-gray-200/60 shadow-xl rounded-2xl pointer-events-auto dark:bg-slate-900 dark:border-slate-800/60 backdrop-blur-xl">
                <div class="flex justify-between items-center py-4 px-6 border-b border-gray-200/50 dark:border-slate-800/50">
                    <h3 class="font-bold text-slate-800 dark:text-white" x-text="isEditing ? 'Modifier l\'immeuble' : 'Enregistrer un nouvel immeuble'"></h3>
                    <button type="button" class="size-8 inline-flex justify-center items-center rounded-xl bg-slate-100 text-slate-800 hover:bg-slate-200 focus:outline-none dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-neutral-400" data-hs-overlay="#hs-modal-add-immeuble">
                        <i data-lucide="x" class="size-4"></i>
                    </button>
                </div>
                <div class="p-6">
                    <form :action="isEditing ? `/admin/immeubles/${immeubleEnCours.id}` : '{{ route('admin.immeubles.store') }}'" method="POST">
                        @csrf
                        <template x-if="isEditing">
                            <input type="hidden" name="_method" value="PUT">
                        </template>
                        <input type="hidden" name="id" x-model="immeubleEnCours.id">
                        
                        <div class="grid gap-y-5">
                            <div>
                                <label class="block text-sm font-semibold mb-2 dark:text-white">{{ __('Nom de l\'immeuble') }}</label>
                                <input x-model="immeubleEnCours.nom" name="nom" type="text" class="py-2.5 px-4 block w-full border-gray-200 dark:border-slate-850 dark:bg-[#080B11] dark:text-slate-300 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500" placeholder="Ex: Résidence Al Amal" required>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold mb-2 dark:text-white">Adresse complète</label>
                                    <input x-model="immeubleEnCours.adresse" name="adresse" type="text" class="py-2.5 px-4 block w-full border-gray-200 dark:border-slate-850 dark:bg-[#080B11] dark:text-slate-300 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500" placeholder="Ex: Av. Mohammed V" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold mb-2 dark:text-white">{{ __('Ville') }}</label>
                                    <input x-model="immeubleEnCours.ville" name="ville" type="text" class="py-2.5 px-4 block w-full border-gray-200 dark:border-slate-850 dark:bg-[#080B11] dark:text-slate-300 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500" placeholder="Ex: Rabat" required>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-2 dark:text-white">Assigner un Syndic</label>
                                <select x-model="immeubleEnCours.syndic_id" name="syndic_id" class="py-2.5 px-4 block w-full border-gray-200 dark:border-slate-850 dark:bg-[#080B11] dark:text-slate-300 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500">
                                    <option value="">{{ __('Choisir un syndic responsable') }}</option>
                                    @foreach($syndics as $s)
                                        <option value="{{ $s->id }}">{{ $s->prenom }} {{ $s->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold mb-2 dark:text-white">{{ __('Nombre d\'étages') }}</label>
                                    <input x-model="immeubleEnCours.nb_etages" name="nombre_etages" type="number" class="py-2.5 px-4 block w-full border-gray-200 dark:border-slate-850 dark:bg-[#080B11] dark:text-slate-300 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold mb-2 dark:text-white">{{ __('Nombre d\'appartements') }}</label>
                                    <input x-model="immeubleEnCours.nb_appartements" name="nombre_appartements" type="number" class="py-2.5 px-4 block w-full border-gray-200 dark:border-slate-850 dark:bg-[#080B11] dark:text-slate-300 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500" required>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-end items-center gap-x-3.5 mt-8 border-t border-slate-100 dark:border-slate-800 pt-5">
                            <button type="button" class="py-2.5 px-4 text-sm font-semibold border rounded-xl border-slate-200 hover:bg-slate-50 dark:border-slate-800 dark:hover:bg-slate-800 dark:text-white transition-colors" data-hs-overlay="#hs-modal-add-immeuble">{{ __('Annuler') }}</button>
                            <button type="submit" class="py-2.5 px-4 text-sm font-bold bg-primary-600 text-white rounded-xl hover:bg-primary-700 hover:from-primary-700 hover:to-purple-700 shadow-md shadow-primary-500/10 transition-colors" x-text="isEditing ? 'Sauvegarder les modifications' : 'Enregistrer'"></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
