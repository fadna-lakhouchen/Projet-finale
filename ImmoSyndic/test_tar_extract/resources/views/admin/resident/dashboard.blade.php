@extends('layouts.app')

@push('styles')
<style>
    .hide-for-locataire { display: none; }
    body[data-role="proprietaire"] .hide-for-lotaire-flex { display: flex; }
</style>
@endpush

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">{{ __('Dashboard') }}</h2>
            <p class="text-sm text-gray-600 dark:text-neutral-400">{{ __('Bienvenue, :name. Voici l\'état de votre logement à :immeuble.', ['name' => $user->prenom, 'immeuble' => $immeuble->nom ?? __('votre immeuble')]) }}</p>
        </div>
        <button type="button" data-hs-overlay="#hs-modal-ready-to-pay" class="py-2.5 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-bold rounded-xl border border-transparent bg-gradient-to-r from-emerald-600 to-teal-600 text-white hover:from-emerald-700 hover:to-teal-700 shadow-md shadow-emerald-500/10 hover:shadow-lg transition-all duration-200">
            {{ __('Prêt à payer') }}
        </button>
    </div>

    <!-- Suivi des Règlements de l'Immeuble (Transparency) -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm mt-4 mb-6 overflow-hidden dark:bg-neutral-800 dark:border-neutral-700" 
         x-data="residentDashboard()">
        
        <div class="px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 dark:border-neutral-700 bg-gray-50/50 dark:bg-neutral-800/50">
            <div>
                <h2 class="text-lg font-bold text-gray-800 dark:text-white flex items-center gap-2">
                    <i data-lucide="users" class="size-5 text-primary-500"></i>
                    {{ __("Qui a payé les charges de l'immeuble ?") }}
                </h2>
                <p class="text-xs text-gray-500 dark:text-neutral-400 mt-0.5">{{ __("Suivi de l'état global et transparent pour l'immeuble") }} <strong>{{ $immeuble->nom ?? 'N/A' }}</strong>.</p>
            </div>
            
            <div class="text-xs font-semibold text-gray-600 dark:text-neutral-400 bg-gray-100 dark:bg-neutral-900 px-3 py-1.5 rounded-lg border border-gray-200 dark:border-neutral-800">
                {{ __('État des cotisations cumulées (historique complet)') }}
            </div>
        </div>

        <div class="p-6">
            <!-- Sleek Search Input -->
            <div class="mb-5 relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <i data-lucide="search" class="size-4 text-gray-400"></i>
                </div>
                <input type="text" 
                       x-model="search" 
                       placeholder="{{ __('Rechercher un appartement par son numéro... (Ex: 12)') }}" 
                       class="py-2.5 ps-10 pe-4 block w-full border-gray-200 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-300 bg-gray-50/50">
            </div>

            <!-- 2 Columns Grid: Non payé vs Payé -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Column 1: Non réglés (Red Column) -->
                <div class="flex flex-col bg-red-50/20 dark:bg-[#251015] border border-red-100 dark:border-red-900/20 rounded-2xl overflow-hidden shadow">
                    <div class="px-5 py-4 bg-red-50 dark:bg-[#3d1620] border-b border-red-100 dark:border-red-900/30 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="size-3 rounded-full bg-rose-500 animate-ping"></span>
                            <span class="text-sm font-black text-red-800 dark:text-red-400 uppercase tracking-wider">{{ __('Pas à jour ❌') }}</span>
                        </div>
                        <span class="text-xs font-black bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300 px-3 py-1 rounded-full shadow-inner">
                            {{ $appartementsEnRetard->count() }} {{ trans_choice('appartement|appartements', $appartementsEnRetard->count()) }}
                        </span>
                    </div>
                    
                    <div class="p-5 flex-1 max-h-72 overflow-y-auto pr-1">
                        @if($appartementsEnRetard->isEmpty())
                            <div class="flex flex-col items-center justify-center py-12 text-center h-full">
                                <div class="size-12 bg-emerald-100 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 rounded-full flex items-center justify-center mb-3 shadow">
                                    <i data-lucide="check" class="size-6"></i>
                                </div>
                                <h5 class="text-sm font-black text-gray-800 dark:text-white">{{ __('Tout le monde est en règle !') }}</h5>
                                <p class="text-xs text-gray-500 dark:text-neutral-400 mt-1">{{ __("Aucun retard de paiement dans l'immeuble.") }}</p>
                            </div>
                        @else
                            <div class="flex flex-wrap gap-2">
                                @foreach($appartementsEnRetard as $apt)
                                    @php
                                        $apptNum = $apt['numero'] ?? 'N/A';
                                        $months = $apt['unpaid_count'];
                                        $isMyApt = $apt['is_my_apt'];
                                    @endphp
                                    <div x-show="!search || '{{ $apptNum }}'.toLowerCase().includes(search.toLowerCase())" 
                                         x-transition
                                         class="relative py-2 px-3.5 rounded-xl text-sm font-extrabold bg-red-500 hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700 text-white shadow-sm border border-red-600/30 flex items-center gap-1.5 transition-all duration-150 hover:scale-105 select-none {{ $isMyApt ? 'ring-4 ring-primary-500 ring-offset-2 dark:ring-offset-neutral-900 z-10' : '' }}">
                                        <i data-lucide="alert-circle" class="size-4 flex-shrink-0"></i>
                                        <span>{{ __('Appt') }} <span class="font-mono">{{ $apptNum }}</span> <span class="opacity-90 font-semibold text-xs">(<span class="font-mono">{{ $months }}</span> {{ trans_choice('mois|mois', $months) }})</span></span>
                                        @if($isMyApt)
                                            <span class="text-[8px] bg-white text-red-600 font-extrabold px-1 rounded">{{ __('MOI') }}</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Column 2: Réglés (Green Column) -->
                <div class="flex flex-col bg-emerald-50/20 dark:bg-[#0b261b] border border-emerald-100 dark:border-emerald-900/20 rounded-2xl overflow-hidden shadow">
                    <div class="px-5 py-4 bg-emerald-50 dark:bg-[#113a29] border-b border-emerald-100 dark:border-emerald-900/30 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="size-3 rounded-full bg-emerald-500"></span>
                            <span class="text-sm font-black text-emerald-800 dark:text-emerald-400 uppercase tracking-wider">{{ __('En règle ✅') }}</span>
                        </div>
                        <span class="text-xs font-black bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300 px-3 py-1 rounded-full shadow-inner">
                            {{ $appartementsEnRegle->count() }} {{ trans_choice('appartement|appartements', $appartementsEnRegle->count()) }}
                        </span>
                    </div>
                    
                    <div class="p-5 flex-1 max-h-72 overflow-y-auto pr-1">
                        @if($appartementsEnRegle->isEmpty())
                            <div class="flex flex-col items-center justify-center py-12 text-center h-full">
                                <div class="size-12 bg-gray-100 dark:bg-neutral-800 text-gray-400 dark:text-neutral-500 rounded-full flex items-center justify-center mb-3 shadow">
                                    <i data-lucide="info" class="size-6"></i>
                                </div>
                                <h5 class="text-sm font-black text-gray-800 dark:text-white">{{ __('Aucun appartement en règle') }}</h5>
                                <p class="text-xs text-gray-500 dark:text-neutral-400 mt-1">{{ __('Tous les appartements ont des impayés.') }}</p>
                            </div>
                        @else
                            <div class="flex flex-wrap gap-2">
                                @foreach($appartementsEnRegle as $apt)
                                    @php
                                        $apptNum = $apt['numero'] ?? 'N/A';
                                        $isMyApt = $apt['is_my_apt'];
                                    @endphp
                                    <div x-show="!search || '{{ $apptNum }}'.toLowerCase().includes(search.toLowerCase())" 
                                         x-transition
                                         class="relative py-2 px-3.5 rounded-xl text-sm font-extrabold bg-emerald-500 hover:bg-emerald-600 dark:bg-emerald-600 dark:hover:bg-emerald-700 text-white shadow-sm border border-emerald-600/30 flex items-center gap-1.5 transition-all duration-150 hover:scale-105 select-none {{ $isMyApt ? 'ring-4 ring-primary-500 ring-offset-2 dark:ring-offset-neutral-900 z-10' : '' }}">
                                        <i data-lucide="check" class="size-4 flex-shrink-0"></i>
                                        <span>{{ __('Appt') }} <span class="font-mono">{{ $apptNum }}</span></span>
                                        @if($isMyApt)
                                            <span class="text-[8px] bg-white text-emerald-600 font-extrabold px-1 rounded">{{ __('MOI') }}</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Required Banner (Moved below transparency section) -->
    @if($stats['a_payer_mois'] > 0)
    <div class="mb-6">
        <div class="bg-[#FFFDF5] border border-amber-200 p-4 rounded-xl flex items-start gap-4 dark:bg-amber-950/10 dark:border-amber-900/30 shadow-sm">
            <i data-lucide="alert-triangle" class="size-6 text-amber-600 dark:text-amber-400 mt-0.5 flex-shrink-0"></i>
            <div class="flex-1">
                <h3 class="text-sm font-bold text-amber-800 dark:text-amber-400">{{ __('Rappel : Cotisations en attente') }}</h3>
                <p class="text-sm text-amber-700 dark:text-amber-300 mt-1">{{ __('Vous avez un solde de') }} <strong class="font-bold">{{ number_format($stats['a_payer_mois'], 2) }} {{ __('MAD') }}</strong> {{ __('en attente de règlement pour ce mois. Veuillez régler ce montant auprès de votre Syndic (en espèces ou par virement bancaire sur le compte CIH de la copropriété).') }}</p>
            </div>
        </div>
    </div>
    @endif

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6">
        <!-- Charges Card -->
        <div class="flex flex-col bg-white border border-gray-200 rounded-xl p-5 shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-500 font-medium">{{ __('Charges du mois') }}</p>
                <div class="p-2 {{ $stats['a_payer_mois'] > 0 ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600' }} rounded-lg">
                    <i data-lucide="{{ $stats['a_payer_mois'] > 0 ? 'file-warning' : 'check-circle' }}" class="size-5"></i>
                </div>
            </div>
            <div class="mt-3">
                <h3 class="text-2xl font-bold text-gray-800 dark:text-white">{{ number_format($stats['a_payer_mois'], 2) }} {{ __('MAD') }}</h3>
                <p class="text-sm {{ $stats['a_payer_mois'] > 0 ? 'text-red-500' : 'text-green-500' }} mt-1 font-medium">
                    {{ $stats['a_payer_mois'] > 0 ? __('À régler avant la fin du mois') : __('À jour ce mois-ci') }}
                </p>
            </div>
        </div>
        
        <!-- Paiements Effectués -->
        <div class="flex flex-col bg-white border border-gray-200 rounded-xl p-5 shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-500 font-medium">{{ __('Paiements effectués') }}</p>
                <div class="p-2 bg-green-100 text-green-600 rounded-lg"><i data-lucide="wallet" class="size-5"></i></div>
            </div>
            <div class="mt-3">
                <h3 class="text-2xl font-bold text-gray-800 dark:text-white">{{ number_format($stats['total_paye_annee'], 2) }} {{ __('MAD') }}</h3>
                <p class="text-sm text-gray-500 mt-1 dark:text-neutral-400">{{ __('Total payé en') }} {{ now()->year }}</p>
            </div>
        </div>

        <!-- Incidents Card -->
        <div class="flex flex-col bg-white border border-gray-200 rounded-xl p-5 shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-500 font-medium">{{ __('Signalements ouverts') }}</p>
                <div class="p-2 bg-orange-100 text-orange-600 rounded-lg"><i data-lucide="alert-triangle" class="size-5"></i></div>
            </div>
            <div class="mt-3">
                <h3 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $stats['incidents_ouverts'] }}</h3>
                <p class="text-sm text-gray-500 mt-1 dark:text-neutral-400 font-medium">{{ __('En cours de traitement') }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm mt-8 overflow-hidden dark:bg-neutral-800 dark:border-neutral-700">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center dark:border-neutral-700">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white">{{ __('Activité Récente') }}</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                <thead class="bg-gray-50 dark:bg-neutral-800">
                    <tr>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">{{ __('Date') }}</th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">{{ __('Activité') }}</th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">{{ __('Détails') }}</th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">{{ __('Statut') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                    @forelse($activites as $activite)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-neutral-200 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($activite['date'])->translatedFormat('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-neutral-200 font-medium">
                            {{ $activite['type'] }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-neutral-400">
                            {{ $activite['description'] }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-full text-xs font-medium bg-{{ $activite['color'] }}-100 text-{{ $activite['color'] }}-800 dark:bg-{{ $activite['color'] }}-900/30 dark:text-{{ $activite['color'] }}-500 capitalize">
                                {{ $activite['statut'] }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500 dark:text-neutral-400">{{ __('Aucune activité récente.') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal: Prêt à payer -->
    <div id="hs-modal-ready-to-pay" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none bg-slate-950/40 backdrop-blur-sm" role="dialog" tabindex="-1" aria-labelledby="hs-modal-ready-to-pay-label">
        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
            <div class="w-full flex flex-col bg-white border border-gray-200/60 shadow-premium rounded-2xl pointer-events-auto dark:bg-neutral-900 dark:border-neutral-800">
                <div class="flex justify-between items-center py-4 px-5 border-b dark:border-neutral-800">
                    <h3 id="hs-modal-ready-to-pay-label" class="font-bold text-gray-800 dark:text-white text-lg flex items-center gap-2">
                        {{ __('Signaler que je suis prêt à payer') }}
                    </h3>
                    <button type="button" class="size-8 inline-flex justify-center items-center rounded-xl bg-gray-100 text-gray-800 hover:bg-gray-200 dark:bg-slate-800 dark:text-neutral-400 dark:hover:bg-slate-700 transition-colors" data-hs-overlay="#hs-modal-ready-to-pay">
                        <i data-lucide="x" class="size-4"></i>
                    </button>
                </div>
                <form action="{{ route('resident.ready-to-pay') }}" method="POST"
                      x-data="{
                          openSelectCharge: false,
                          selectedChargeId: '',
                          selectedChargeLabel: '{{ __('-- Choisir une cotisation impayée --') }}'
                      }">
                    @csrf
                    <div class="p-6 overflow-y-auto space-y-4">
                        <p class="text-sm text-gray-500 dark:text-neutral-400">
                            {{ __('Indiquez au Syndic que vous disposez du montant en espèces. Il passera récupérer le règlement directement à votre appartement.') }}
                        </p>

                        <div>
                            <label class="block text-sm font-semibold mb-2 dark:text-white">{{ __('Sélectionner la cotisation concernée') }} <span class="text-red-500">*</span></label>
                            
                            <div class="relative w-full inline-flex">
                                <button type="button" @click="openSelectCharge = !openSelectCharge" @click.outside="openSelectCharge = false" class="py-2.5 px-4 w-full inline-flex justify-between items-center gap-x-2 text-sm font-semibold rounded-xl border border-gray-200/80 bg-white/50 hover:bg-white text-slate-800 shadow-sm dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-white dark:hover:bg-slate-900/50 transition-all duration-200 text-left">
                                    <span x-text="selectedChargeLabel" class="truncate text-left pr-4"></span>
                                    <i data-lucide="chevron-down" :class="openSelectCharge ? 'rotate-180' : ''" class="size-4 transition-transform duration-200 text-gray-400"></i>
                                </button>
                                
                                <input type="hidden" name="charge_id" :value="selectedChargeId" required>
                                
                                <div x-show="openSelectCharge" x-cloak class="absolute left-0 top-full z-[100] mt-2 w-full max-h-60 overflow-y-auto bg-white dark:bg-[#0D121F] border border-gray-200/60 dark:border-slate-800/60 shadow-xl rounded-xl p-1.5 backdrop-blur-md" style="display: none;">
                                    @forelse($mesChargesImpayees as $charge)
                                        @php
                                            $dateFr = \App\Models\Notification::translateMonthYear(\Carbon\Carbon::parse($charge->date_echeance)->translatedFormat('F Y'));
                                            $label = __("Cotisation") . " " . $dateFr . " - " . number_format($charge->reste_a_payer, 2) . " " . __("MAD");
                                        @endphp
                                        <button type="button" 
                                                @click="
                                                    selectedChargeId = '{{ $charge->id }}';
                                                    selectedChargeLabel = '{{ $label }}';
                                                    openSelectCharge = false;
                                                " 
                                                class="w-full text-start flex items-center py-2.5 px-3 rounded-lg text-sm text-gray-700 dark:text-slate-350 hover:bg-gray-150 dark:hover:bg-slate-800/50 transition-colors">
                                            {{ $label }}
                                        </button>
                                    @empty
                                        <button type="button" disabled class="w-full text-start flex items-center py-2 px-3 text-sm text-gray-400 dark:text-slate-650 cursor-not-allowed">
                                            {{ __('Aucune cotisation impayée') }}
                                        </button>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold mb-2 dark:text-white">{{ __('Note de disponibilité') }}</label>
                            <textarea name="note" rows="3" class="py-3 px-4 block w-full border-gray-200 dark:border-slate-850 dark:bg-[#080B11] dark:text-slate-300 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500" placeholder="{{ __('Ex: Disponible ce soir après 18h, appeler au 0600000000 avant de passer...') }}"></textarea>
                        </div>
                    </div>
                    <div class="flex justify-end items-center gap-x-2 py-3 px-5 border-t dark:border-neutral-800 bg-gray-50/50 dark:bg-neutral-900/30 rounded-b-2xl">
                        <button type="button" class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-xl border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 dark:bg-neutral-850 dark:border-neutral-800 dark:text-white dark:hover:bg-neutral-800" data-hs-overlay="#hs-modal-ready-to-pay">{{ __('Annuler') }}</button>
                        <button type="submit" class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-xl border border-transparent bg-gradient-to-r from-emerald-600 to-teal-600 text-white hover:from-emerald-700 hover:to-teal-700 shadow-md shadow-emerald-500/10 hover:shadow-lg transition-all duration-200" :disabled="!selectedChargeId">
                            <i data-lucide="send" class="size-4"></i>
                            {{ __('Envoyer le signalement') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
