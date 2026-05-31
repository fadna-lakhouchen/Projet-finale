@extends('layouts.app')

@push('styles')
<style>
    .hide-for-locataire { display: none; }
    body[data-role="proprietaire"] .hide-for-lotaire-flex { display: flex; }
</style>
@endpush

@section('content')
<div class="w-full pt-6 px-4 sm:px-6 md:px-8 pb-12">
    <!-- Action Required Banner (Only if unpaid) -->
    @if($stats['a_payer_mois'] > 0)
    <div>
        <div class="bg-amber-50 border border-amber-200 p-4 rounded-xl flex items-start gap-4 mb-6 dark:bg-amber-900/10 dark:border-amber-900/30">
            <i data-lucide="alert-triangle" class="size-6 text-amber-600 dark:text-amber-400 mt-0.5"></i>
            <div class="flex-1">
                <h3 class="text-sm font-bold text-amber-800 dark:text-amber-400">Rappel : Cotisations en attente</h3>
                <p class="text-sm text-amber-700 dark:text-amber-300 mt-1">Vous avez un solde de <strong class="font-bold">{{ number_format($stats['a_payer_mois'], 2) }} MAD</strong> en attente de règlement pour ce mois. Veuillez régler ce montant auprès de votre Syndic (en espèces ou par virement bancaire sur le compte CIH de la copropriété).</p>
            </div>
        </div>
    </div>
    @endif

    <div class="flex justify-between items-end mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Dashboard</h2>
            <p class="text-sm text-gray-600 dark:text-neutral-400">Bienvenue, {{ $user->prenom }}. Voici l'état de votre logement à {{ $immeuble->nom ?? 'votre immeuble' }}.</p>
        </div>
        <button type="button" data-hs-overlay="#hs-modal-new-signalement-resident" class="py-2 px-3 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-primary-600 text-white hover:bg-primary-700 shadow-sm transition-colors">
            <i data-lucide="plus" class="size-4"></i>
            Signaler un problème
        </button>
    </div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        <!-- Charges Card -->
        <div class="flex flex-col bg-white border border-gray-200 rounded-xl p-5 shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-500 font-medium">Charges du mois</p>
                <div class="p-2 {{ $stats['a_payer_mois'] > 0 ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600' }} rounded-lg">
                    <i data-lucide="{{ $stats['a_payer_mois'] > 0 ? 'file-warning' : 'check-circle' }}" class="size-5"></i>
                </div>
            </div>
            <div class="mt-3">
                <h3 class="text-2xl font-bold text-gray-800 dark:text-white">{{ number_format($stats['a_payer_mois'], 2) }} MAD</h3>
                <p class="text-sm {{ $stats['a_payer_mois'] > 0 ? 'text-red-500' : 'text-green-500' }} mt-1 font-medium">
                    {{ $stats['a_payer_mois'] > 0 ? 'À régler avant la fin du mois' : 'À jour ce mois-ci' }}
                </p>
            </div>
        </div>
        
        <!-- Paiements Effectués -->
        <div class="flex flex-col bg-white border border-gray-200 rounded-xl p-5 shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-500 font-medium">Paiements effectués</p>
                <div class="p-2 bg-green-100 text-green-600 rounded-lg"><i data-lucide="wallet" class="size-5"></i></div>
            </div>
            <div class="mt-3">
                <h3 class="text-2xl font-bold text-gray-800 dark:text-white">{{ number_format($stats['total_paye_annee'], 2) }} MAD</h3>
                <p class="text-sm text-gray-500 mt-1 dark:text-neutral-400">Total payé en {{ now()->year }}</p>
            </div>
        </div>

        <!-- Incidents Card -->
        <div class="flex flex-col bg-white border border-gray-200 rounded-xl p-5 shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-500 font-medium">Signalements ouverts</p>
                <div class="p-2 bg-orange-100 text-orange-600 rounded-lg"><i data-lucide="alert-triangle" class="size-5"></i></div>
            </div>
            <div class="mt-3">
                <h3 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $stats['incidents_ouverts'] }}</h3>
                <p class="text-sm text-gray-500 mt-1 dark:text-neutral-400">En cours de traitement</p>
            </div>
        </div>
    </div>

    <!-- Suivi des Règlements de l'Immeuble -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm mt-8 overflow-hidden dark:bg-neutral-800 dark:border-neutral-700">
        <div class="px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 dark:border-neutral-700 bg-gray-50/50 dark:bg-neutral-800/50">
            <div>
                <h2 class="text-lg font-bold text-gray-800 dark:text-white flex items-center gap-2">
                    <i data-lucide="users" class="size-5 text-primary-500"></i>
                    Suivi des Règlements de l'Immeuble
                </h2>
                <p class="text-xs text-gray-500 dark:text-neutral-400 mt-0.5">Suivi en temps réel des charges et des paiements de l'immeuble <strong>{{ $immeuble->nom ?? 'N/A' }}</strong>.</p>
            </div>
            <!-- Dynamic stats summary of building -->
            @php
                $totalBuildingCharges = $transparenceCharges->count();
                $paidBuildingCharges = $transparenceCharges->filter(fn($c) => strtolower($c->statut) === 'payé')->count();
                $pctPaid = $totalBuildingCharges > 0 ? round(($paidBuildingCharges / $totalBuildingCharges) * 100) : 0;
            @endphp
            <div class="flex items-center gap-3">
                <span class="text-xs font-semibold text-gray-600 dark:text-neutral-400">Taux de recouvrement :</span>
                <div class="flex items-center gap-1.5">
                    <div class="w-24 bg-gray-200 rounded-full h-2 dark:bg-neutral-700 overflow-hidden">
                        <div class="bg-emerald-500 h-2 rounded-full transition-all duration-500" style="width: {{ $pctPaid }}%"></div>
                    </div>
                    <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400">{{ $pctPaid }}%</span>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                <thead class="bg-gray-50 dark:bg-neutral-800">
                    <tr>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Appartement</th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Copropriétaire</th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Période</th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Reste à payer</th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                    @forelse($transparenceCharges as $charge)
                    @php
                        $isMyCharge = $charge->appartement && $charge->appartement->residents->contains('id', $user->id);
                        $statusClass = match(strtolower($charge->statut)) {
                            'payé' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400',
                            'partiel' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400',
                            default => 'bg-rose-100 text-rose-800 dark:bg-rose-900/30 dark:text-rose-400',
                        };
                        $statusLabel = match(strtolower($charge->statut)) {
                            'payé' => 'Payé',
                            'partiel' => 'Partiel',
                            default => 'Impayé',
                        };
                    @endphp
                    <tr class="transition-colors {{ $isMyCharge ? 'bg-primary-50/50 hover:bg-primary-50 dark:bg-primary-900/10 dark:hover:bg-primary-900/20' : 'hover:bg-gray-50 dark:hover:bg-neutral-700/50' }}">
                        <td class="px-6 py-4 text-sm font-semibold text-gray-800 dark:text-neutral-200 whitespace-nowrap">
                            Appt {{ $charge->appartement->numero ?? 'N/A' }}
                            @if($isMyCharge)
                            <span class="ms-2 inline-flex items-center gap-1.5 py-0.5 px-2 rounded-full text-xs font-semibold bg-primary-100 text-primary-800 dark:bg-primary-900/50 dark:text-primary-400">
                                Moi
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-neutral-200 whitespace-nowrap">
                            {{ $charge->resident_nom }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-neutral-400 whitespace-nowrap">
                            {{ ucfirst(\Carbon\Carbon::parse($charge->date_echeance)->translatedFormat('F Y')) }}
                        </td>
                        <td class="px-6 py-4 text-sm whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="font-bold text-gray-800 dark:text-neutral-200">{{ number_format($charge->reste_a_payer, 2) }} MAD</span>
                                @if(strtolower($charge->statut) === 'partiel')
                                    <span class="text-xs text-gray-400 dark:text-neutral-500">Sur {{ number_format($charge->montant, 2) }} MAD</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-full text-xs font-bold {{ $statusClass }}">
                                <span class="size-1.5 rounded-full bg-current"></span>
                                {{ $statusLabel }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-neutral-400">Aucune charge générée pour cet immeuble.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Activity Table -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm mt-8 overflow-hidden dark:bg-neutral-800 dark:border-neutral-700">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center dark:border-neutral-700">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Activité récente</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                <thead class="bg-gray-50 dark:bg-neutral-800">
                    <tr>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Date</th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Activité</th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Détails</th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Statut</th>
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
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500 dark:text-neutral-400">Aucune activité récente.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Signaler un problème -->
<div id="hs-modal-new-signalement-resident" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none" role="dialog" tabindex="-1" aria-labelledby="hs-modal-new-signalement-resident-label">
    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
        <div class="w-full flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
            <div class="flex justify-between items-center py-3 px-4 border-b dark:border-neutral-700">
                <h3 id="hs-modal-new-signalement-resident-label" class="font-bold text-gray-800 dark:text-white">Signaler un nouveau problème</h3>
                <button type="button" class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-gray-100 text-gray-800 hover:bg-gray-200 focus:outline-none focus:bg-gray-200 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-700 dark:hover:bg-neutral-600 dark:text-neutral-400" data-hs-overlay="#hs-modal-new-signalement-resident">
                    <i data-lucide="x" class="size-4"></i>
                </button>
            </div>
            <div class="p-4 overflow-y-auto">
                <form id="resident-incident-form" action="{{ route('resident.incidents.store') }}" method="POST"
                    x-data="{
                        priorite: 'moyenne',
                        openPrio: false,
                        prioLabel: 'Moyenne',
                        prioColor: 'blue',
                        setPrio(val, label, color) {
                            this.priorite = val;
                            this.prioLabel = label;
                            this.prioColor = color;
                            this.openPrio = false;
                        }
                    }">
                    @csrf
                    <div class="grid gap-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-2 dark:text-white">Titre du problème <span class="text-red-500">*</span></label>
                            <input type="text" name="titre" required class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" placeholder="Ex: Fuite d'eau dans le couloir, Ascenseur en panne...">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2 dark:text-white">Priorité <span class="text-red-500">*</span></label>
                            <input type="hidden" name="priorite" :value="priorite">
                            <div class="relative">
                                <button type="button"
                                    @click="openPrio = !openPrio"
                                    @click.outside="openPrio = false"
                                    class="w-full py-3 px-4 inline-flex items-center justify-between gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-200 shadow-sm hover:bg-gray-50 dark:hover:bg-neutral-800 transition-all duration-200">
                                    <span class="flex items-center gap-2">
                                        <span class="size-2 rounded-full inline-block"
                                            :class="{
                                                'bg-gray-400': prioColor === 'gray',
                                                'bg-blue-500': prioColor === 'blue',
                                                'bg-orange-500': prioColor === 'orange',
                                                'bg-red-500': prioColor === 'red'
                                            }"></span>
                                        <span x-text="prioLabel"></span>
                                    </span>
                                    <i data-lucide="chevron-down" class="size-4 text-gray-400 transition-transform duration-200" :class="openPrio ? 'rotate-180' : ''"></i>
                                </button>
                                <div x-show="openPrio" x-cloak
                                    class="absolute left-0 top-full z-[200] mt-1 w-full bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-700 shadow-xl rounded-lg p-1.5">
                                    <div @click="setPrio('basse', 'Basse', 'gray')"
                                        class="cursor-pointer flex items-center gap-2.5 py-2 px-3 rounded-md text-sm text-gray-700 dark:text-neutral-300 hover:bg-gray-100 dark:hover:bg-neutral-800 transition-colors">
                                        <span class="size-2 rounded-full bg-gray-400 flex-shrink-0"></span> Basse
                                    </div>
                                    <div @click="setPrio('moyenne', 'Moyenne', 'blue')"
                                        class="cursor-pointer flex items-center gap-2.5 py-2 px-3 rounded-md text-sm text-gray-700 dark:text-neutral-300 hover:bg-gray-100 dark:hover:bg-neutral-800 transition-colors">
                                        <span class="size-2 rounded-full bg-blue-500 flex-shrink-0"></span> Moyenne
                                    </div>
                                    <div @click="setPrio('haute', 'Haute', 'orange')"
                                        class="cursor-pointer flex items-center gap-2.5 py-2 px-3 rounded-md text-sm text-gray-700 dark:text-neutral-300 hover:bg-gray-100 dark:hover:bg-neutral-800 transition-colors">
                                        <span class="size-2 rounded-full bg-orange-500 flex-shrink-0"></span> Haute
                                    </div>
                                    <div @click="setPrio('urgente', 'Urgente', 'red')"
                                        class="cursor-pointer flex items-center gap-2.5 py-2 px-3 rounded-md text-sm text-gray-700 dark:text-neutral-300 hover:bg-gray-100 dark:hover:bg-neutral-800 transition-colors">
                                        <span class="size-2 rounded-full bg-red-500 flex-shrink-0"></span> Urgente
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2 dark:text-white">Description <span class="text-red-500">*</span></label>
                            <textarea name="description" rows="4" required class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" placeholder="Détaillez le problème rencontré..."></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
                <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700" data-hs-overlay="#hs-modal-new-signalement-resident">Annuler</button>
                <button type="submit" form="resident-incident-form" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-primary-600 text-white hover:bg-primary-700 shadow-sm transition-colors">Envoyer le signalement</button>
        </div>
    </div>
</div>
@endsection
