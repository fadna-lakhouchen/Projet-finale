{{-- Héritage du layout général de l'application --}}
@extends('layouts.app')

@section('content')
{{-- Liaison du scope Alpine.js pour la gestion réactive du tableau de bord --}}
<div x-data="syndicDashboard">
    
    {{-- Bannière d'alerte pour les incidents urgents en cours --}}
    @if ($urgentIncident)
    <div class="relative overflow-hidden bg-amber-500/10 border border-amber-500/20 text-sm text-amber-800 dark:text-amber-400 rounded-2xl p-4 mb-6 shadow-sm flex items-start gap-x-3.5" role="alert">
        <div class="size-8 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-600 dark:text-amber-400 shrink-0">
            <i data-lucide="alert-circle" class="size-4.5"></i>
        </div>
        <div>
            <span class="font-bold text-amber-700 dark:text-amber-300">{{ __('À faire :') }}</span> {{ __("L'intervention concernant") }} <strong>{{ $urgentIncident->titre }}</strong> ({{ $urgentIncident->immeuble->nom ?? 'N/A' }})@if($urgentIncident->user), {{ __('signalée par') }} <strong>{{ $urgentIncident->user->name }}</strong>,@endif {{ __('nécessite votre attention.') }}
        </div>
    </div>
    @endif

    {{-- Bannière verte récapitulant les demandes de collecte de cotisations en espèces émise par les résidents --}}
    @if (isset($demandesCollecte) && $demandesCollecte->isNotEmpty())
    <div class="mb-6 space-y-3">
        @foreach ($demandesCollecte as $demande)
            <div class="relative overflow-hidden bg-emerald-500/10 border border-emerald-500/20 text-sm text-emerald-800 dark:text-emerald-400 rounded-2xl p-4 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4" role="alert">
                <div class="flex items-start gap-x-3.5">
                    <div>
                        <span class="font-bold text-emerald-700 dark:text-emerald-300">{{ __('Demande de collecte cash :') }}</span> {{ $demande->translated_message }}
                        <span class="block text-[11px] text-slate-500 dark:text-neutral-400 mt-1 font-semibold">{{ __('Signalée') }} {{ $demande->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                <div class="flex items-center gap-x-2 shrink-0">
                    <a href="{{ route('syndic.paiements') }}" class="py-1.5 px-3 inline-flex items-center gap-x-1.5 text-xs font-semibold rounded-xl border border-transparent bg-emerald-600 text-white hover:bg-emerald-700 shadow-sm transition-all">
                        <i data-lucide="plus" class="size-3.5"></i>
                        {{ __('Saisir le paiement') }}
                    </a>
                    <form action="{{ route('notifications.read.single', $demande->id) }}" method="POST" class="inline-block">
                        @csrf
                        <button type="submit" class="py-1.5 px-3 inline-flex items-center gap-x-1.5 text-xs font-semibold rounded-xl border border-emerald-200/30 hover:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 dark:border-emerald-900/30 transition-all" title="{{ __('Marquer comme traité et masquer') }}">
                            <i data-lucide="check" class="size-3.5"></i>
                            {{ __('Masquer') }}
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
    @endif

    {{-- En-tête de la page avec titre et bouton de création de signalement --}}
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 dark:text-white">{{ __('Tableau de Bord Syndic') }}</h2>
            <p class="text-sm text-slate-500 dark:text-neutral-400">{{ __('Suivi des immeubles et des résidents sous votre responsabilité.') }}</p>
        </div>
        {{-- Bouton ouvrant le modal de nouveau signalement et réinitialisant le formulaire via Alpine.js --}}
        <button type="button" @click="resetForm()" data-hs-overlay="#hs-modal-new-signalement" class="py-2.5 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-bold rounded-xl border border-transparent bg-gradient-to-r from-primary-600 to-purple-600 text-white hover:from-primary-700 hover:to-purple-700 shadow-md shadow-primary-500/15 transition-all glow-hover">
            <i data-lucide="plus" class="size-4.5"></i>
            {{ __('Nouveau signalement') }}
        </button>
    </div>

    {{-- Cartes statistiques (Mise en valeur premium avec effets de survol) --}}
    <div class="grid sm:grid-cols-3 gap-6 mb-8">
        {{-- Total Résidents --}}
        <div class="relative overflow-hidden bg-white dark:bg-[#0D121F] border border-gray-200/60 dark:border-slate-800/60 rounded-2xl p-6 shadow-premium transition-all hover:scale-[1.01] duration-300">
            <div class="absolute -right-6 -bottom-6 opacity-[0.08] dark:opacity-[0.12] text-primary-500">
                <i data-lucide="users" class="size-28"></i>
            </div>
            <div class="flex items-center gap-x-4">
                <div class="size-11 rounded-xl bg-primary-500/10 border border-primary-500/20 flex items-center justify-center text-primary-600 dark:text-primary-400">
                    <i data-lucide="users" class="size-5.5"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 dark:text-neutral-400 uppercase tracking-widest">{{ __('Total Résidents') }}</p>
                    <div class="flex items-baseline gap-x-2 mt-0.5">
                        <h3 class="text-2xl font-extrabold text-slate-800 dark:text-white">{{ $stats['total_residents'] }}</h3>
                        <span class="text-xs font-bold text-slate-400 dark:text-neutral-450">/ {{ $stats['total_appartements'] }} {{ trans_choice('appartement|appartements', $stats['total_appartements']) }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Montant Collecté ce mois --}}
        <div class="relative overflow-hidden bg-white dark:bg-[#0D121F] border border-gray-200/60 dark:border-slate-800/60 rounded-2xl p-6 shadow-premium transition-all hover:scale-[1.01] duration-300">
            <div class="absolute -right-6 -bottom-6 opacity-[0.08] dark:opacity-[0.12] text-emerald-500">
                <i data-lucide="wallet" class="size-28"></i>
            </div>
            <div class="flex items-center gap-x-4">
                <div class="size-11 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                    <i data-lucide="wallet" class="size-5.5"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 dark:text-neutral-400 uppercase tracking-widest">{{ __('Collecté ce mois') }}</p>
                    <h3 class="text-2xl font-extrabold text-emerald-600 dark:text-emerald-400 tracking-tight mt-0.5">{{ number_format($stats['paiements_ce_mois'], 2) }} {{ __('DH') }}</h3>
                </div>
            </div>
        </div>

        {{-- Incidents Techniques Ouverts --}}
        <div class="relative overflow-hidden bg-white dark:bg-[#0D121F] border border-gray-200/60 dark:border-slate-800/60 rounded-2xl p-6 shadow-premium transition-all hover:scale-[1.01] duration-300">
            <div class="absolute -right-6 -bottom-6 opacity-[0.08] dark:opacity-[0.12] text-rose-500">
                <i data-lucide="alert-triangle" class="size-28"></i>
            </div>
            <div class="flex items-center gap-x-4">
                <div class="size-11 rounded-xl bg-rose-500/10 border border-rose-500/20 flex items-center justify-center text-rose-600 dark:text-rose-400">
                    <i data-lucide="alert-triangle" class="size-5.5"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 dark:text-neutral-400 uppercase tracking-widest">{{ __('Problèmes Ouverts') }}</p>
                    <h3 class="text-2xl font-extrabold text-rose-600 dark:text-rose-500 mt-0.5">{{ $stats['incidents_ouverts'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Tableau d'activités récentes (Paiements et Signalements d'incidents unifiés) --}}
    <div class="mb-6">
        <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-4">{{ __('Activité récente sur vos immeubles') }}</h3>
        
        <div class="flex flex-col border border-gray-200/60 dark:border-slate-800/60 rounded-2xl shadow-premium bg-white dark:bg-[#0D121F] overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-250 dark:divide-slate-800">
                    <thead class="bg-slate-50 dark:bg-slate-900/50">
                        <tr>
                            <th scope="col" class="px-6 py-3.5 text-start text-xs font-bold text-slate-400 uppercase dark:text-neutral-450 tracking-wider">{{ __('Événement') }}</th>
                            <th scope="col" class="px-6 py-3.5 text-start text-xs font-bold text-slate-400 uppercase dark:text-neutral-450 tracking-wider">{{ __('Concerne') }}</th>
                            <th scope="col" class="px-6 py-3.5 text-start text-xs font-bold text-slate-400 uppercase dark:text-neutral-450 tracking-wider">{{ __('Détails') }}</th>
                            <th scope="col" class="px-6 py-3.5 text-end text-xs font-bold text-slate-400 uppercase dark:text-neutral-450 tracking-wider">{{ __('Date & Heure') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200/60 dark:divide-slate-800/60">
                        {{-- Boucle sur les activités fusionnées --}}
                        @forelse ($activites as $act)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/30 transition-colors {{ $act['bg_row'] }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-x-3">
                                    <div class="size-8 rounded-xl bg-{{ $act['color'] }}-500/10 border border-{{ $act['color'] }}-500/20 flex items-center justify-center text-{{ $act['color'] }}-600 dark:text-{{ $act['color'] }}-450">
                                        <i data-lucide="{{ $act['icon'] }}" class="size-4"></i>
                                    </div>
                                    <span class="block text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $act['evenement'] }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-neutral-400">{{ $act['concerne'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-850 dark:text-white font-medium">{{ $act['details'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-end text-sm text-slate-500 dark:text-neutral-400">
                                {{ \Carbon\Carbon::parse($act['date'])->diffForHumans() }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-sm text-slate-450 dark:text-neutral-500">
                                <div class="flex flex-col items-center justify-center py-4">
                                    <i data-lucide="calendar" class="size-8 text-slate-300 dark:text-neutral-600 mb-2"></i>
                                    <span>{{ __('Aucune activité récente pour le moment.') }}</span>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

{{-- Modal Nouveau Signalement (Remplaçant les select natifs par des dropdowns stylisés Alpine.js) --}}
<div id="hs-modal-new-signalement" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none" role="dialog" tabindex="-1" aria-labelledby="hs-modal-new-signalement-label">
    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
        <div class="w-full flex flex-col bg-white dark:bg-[#0D121F] border border-gray-200/60 dark:border-slate-800/60 shadow-premium rounded-2xl pointer-events-auto overflow-hidden">
            <div class="flex justify-between items-center py-4 px-6 border-b border-gray-200/60 dark:border-slate-800/60">
                <div class="flex items-center gap-x-2.5">
                    <div class="size-8 rounded-lg bg-primary-500/10 border border-primary-500/20 flex items-center justify-center text-primary-600 dark:text-primary-400">
                        <i data-lucide="plus" class="size-4"></i>
                    </div>
                    <h3 id="hs-modal-new-signalement-label" class="font-bold text-slate-800 dark:text-white">{{ __('Nouveau Signalement / Intervention') }}</h3>
                </div>
                <button type="button" @click="resetForm()" class="size-8 inline-flex justify-center items-center rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 dark:bg-neutral-850 dark:hover:bg-neutral-800 dark:text-neutral-400 transition-all" data-hs-overlay="#hs-modal-new-signalement">
                    <i data-lucide="x" class="size-4"></i>
                </button>
            </div>
            
            <form action="{{ route('syndic.interventions.store') }}" method="POST">
                @csrf
                {{-- Par défaut, le statut à la création est ouvert --}}
                <input type="hidden" name="statut" value="Ouvert">
                
                <div class="p-6 space-y-4">
                    {{-- Titre de l'incident --}}
                    <div>
                        <label class="block text-sm font-semibold mb-2 dark:text-neutral-200">{{ __('Titre de l’incident') }}</label>
                        <input name="titre" x-model="titre" type="text" class="py-2.5 px-4 block w-full border-gray-200 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 bg-white/50 dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-neutral-300 transition-all duration-200" placeholder="{{ __('Ex: Panne ascenseur, fuite d\'eau...') }}" required>
                    </div>
                    
                    {{-- Dropdown d'immeuble contrôlé par Alpine --}}
                    <div>
                        <label class="block text-sm font-semibold mb-2 dark:text-neutral-200">{{ __('Résidence / Immeuble') }}</label>
                        <div class="relative w-full inline-flex">
                            <button type="button" @click="openImmeubleDropdown = !openImmeubleDropdown" @click.outside="openImmeubleDropdown = false" class="py-2.5 px-4 w-full inline-flex justify-between items-center gap-x-2 text-sm font-semibold rounded-xl border border-gray-200 bg-white/50 hover:bg-white text-slate-800 shadow-sm dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-white dark:hover:bg-slate-900/50 transition-all duration-200 focus:outline-none">
                                <span x-text="immeuble_nom" class="truncate text-left pr-4"></span>
                                <i data-lucide="chevron-down" :class="openImmeubleDropdown ? 'rotate-180' : ''" class="size-4 transition-transform duration-200 text-gray-400"></i>
                            </button>
                            
                            {{-- Input caché synchronisé pour transmettre l'ID de l'immeuble via POST --}}
                            <input type="hidden" name="immeuble_id" :value="immeuble_id" required>
                            
                            {{-- Liste déroulante des immeubles --}}
                            <div x-show="openImmeubleDropdown" x-cloak class="absolute left-0 top-full z-[100] mt-2 w-full max-h-60 overflow-y-auto bg-white dark:bg-[#0D121F] border border-gray-200/60 dark:border-slate-800/60 shadow-xl rounded-xl p-1.5 backdrop-blur-md">
                                @foreach($immeubles as $immeuble)
                                    <button type="button" 
                                            @click="selectImmeuble('{{ $immeuble->id }}', '{{ addslashes($immeuble->nom) }}')" 
                                            class="w-full text-start flex items-center py-2.5 px-3 rounded-lg text-sm text-gray-700 dark:text-slate-350 hover:bg-gray-150 dark:hover:bg-slate-800/50 transition-colors">
                                        {{ $immeuble->nom }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    
                    {{-- Dropdown de priorité contrôlé par Alpine --}}
                    <div>
                        <label class="block text-sm font-semibold mb-2 dark:text-neutral-200">{{ __('Priorité de l’incident') }}</label>
                        <div class="relative w-full inline-flex">
                            <button type="button" @click="openPrioriteDropdown = !openPrioriteDropdown" @click.outside="openPrioriteDropdown = false" class="py-2.5 px-4 w-full inline-flex justify-between items-center gap-x-2 text-sm font-semibold rounded-xl border border-gray-200 bg-white/50 hover:bg-white text-slate-800 shadow-sm dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-white dark:hover:bg-slate-900/50 transition-all duration-200 focus:outline-none">
                                <span x-text="prioriteLabel" class="truncate text-left pr-4"></span>
                                <i data-lucide="chevron-down" :class="openPrioriteDropdown ? 'rotate-180' : ''" class="size-4 transition-transform duration-200 text-gray-400"></i>
                            </button>
                            
                            {{-- Input caché synchronisé pour transmettre la priorité via POST --}}
                            <input type="hidden" name="priorite" :value="priorite" required>
                            
                            {{-- Liste des priorités --}}
                            <div x-show="openPrioriteDropdown" x-cloak class="absolute left-0 top-full z-[100] mt-2 w-full bg-white dark:bg-[#0D121F] border border-gray-200/60 dark:border-slate-800/60 shadow-xl rounded-xl p-1.5 backdrop-blur-md">
                                <button type="button" @click="selectPriorite('basse')" class="w-full text-start flex items-center py-2.5 px-3 rounded-lg text-sm text-gray-700 dark:text-slate-350 hover:bg-gray-150 dark:hover:bg-slate-800/50 transition-colors">{{ __('Basse') }}</button>
                                <button type="button" @click="selectPriorite('moyenne')" class="w-full text-start flex items-center py-2.5 px-3 rounded-lg text-sm text-gray-700 dark:text-slate-350 hover:bg-gray-150 dark:hover:bg-slate-800/50 transition-colors">{{ __('Moyenne') }}</button>
                                <button type="button" @click="selectPriorite('haute')" class="w-full text-start flex items-center py-2.5 px-3 rounded-lg text-sm text-gray-700 dark:text-slate-350 hover:bg-gray-150 dark:hover:bg-slate-800/50 transition-colors">{{ __('Haute') }}</button>
                                <button type="button" @click="selectPriorite('urgente')" class="w-full text-start flex items-center py-2.5 px-3 rounded-lg text-sm text-gray-700 dark:text-slate-350 hover:bg-gray-150 dark:hover:bg-slate-800/50 transition-colors">{{ __('Urgente') }}</button>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Textarea description --}}
                    <div>
                        <label class="block text-sm font-semibold mb-2 dark:text-neutral-200">{{ __('Description détaillée') }}</label>
                        <textarea name="description" x-model="description" class="py-2.5 px-4 block w-full border-gray-200 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 bg-white/50 dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-neutral-300 transition-all duration-200" rows="4" placeholder="{{ __('Décrivez l\'incident technique...') }}" required></textarea>
                    </div>
                </div>
                
                {{-- Actions du formulaire modal --}}
                <div class="flex justify-end items-center gap-x-3 py-4 px-6 border-t border-gray-200/60 dark:border-slate-800/60 bg-slate-50/50 dark:bg-slate-900/30">
                    <button type="button" @click="resetForm()" class="py-2.5 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-xl border border-gray-200 bg-white hover:bg-gray-50 text-slate-800 dark:bg-neutral-850 dark:border-neutral-800 dark:text-white dark:hover:bg-neutral-800 transition-all shadow-sm" data-hs-overlay="#hs-modal-new-signalement">{{ __('Annuler') }}</button>
                    <button type="submit" class="py-2.5 px-4 inline-flex items-center gap-x-2 text-sm font-bold rounded-xl border border-transparent bg-gradient-to-r from-primary-600 to-purple-600 text-white hover:from-primary-700 hover:to-purple-700 shadow-md shadow-primary-500/15 transition-all glow-hover">{{ __('Créer le signalement') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
@endsection

