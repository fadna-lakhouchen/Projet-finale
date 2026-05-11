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
    <div x-data="{ paid: false }" x-show="!paid">
        <div class="bg-red-50 border border-red-200 p-4 rounded-xl flex items-start gap-4 mb-6">
            <i data-lucide="alert-circle" class="size-6 text-red-500 mt-0.5"></i>
            <div class="flex-1">
                <h3 class="text-sm font-semibold text-red-800">Action requise : Charges impayées</h3>
                <p class="text-sm text-red-700 mt-1">Vous avez un solde de <strong class="font-bold">{{ number_format($stats['a_payer_mois'], 2) }} MAD</strong> en attente pour ce mois.</p>
            </div>
            <button data-hs-overlay="#hs-modal-payment-simulation" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">Régler maintenant</button>
        </div>
    </div>
    @endif

    <div class="flex justify-between items-end mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Vue d'ensemble</h2>
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
                <form action="#" method="POST">
                    @csrf
                    <div class="grid gap-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-2 dark:text-white">Titre du problème</label>
                            <input type="text" name="titre" required class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" placeholder="Ex: Fuite d'eau, Panne d'ascenseur...">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2 dark:text-white">Priorité</label>
                            <select name="priorite" class="py-3 px-4 pe-9 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
                                <option value="basse">Basse</option>
                                <option value="moyenne" selected>Moyenne</option>
                                <option value="haute">Haute</option>
                                <option value="urgente">Urgente</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2 dark:text-white">Description</label>
                            <textarea name="description" rows="4" required class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" placeholder="Détaillez le problème rencontré..."></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
                <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700" data-hs-overlay="#hs-modal-new-signalement-resident">Annuler</button>
                <button type="submit" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-primary-600 text-white hover:bg-primary-700 shadow-sm transition-colors">Envoyer le signalement</button>
            </div>
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
                        <div class="bg-gray-50 p-4 rounded-lg dark:bg-neutral-900 mb-4">
                            <div class="flex justify-between mb-1">
                                <span class="text-sm text-gray-600 dark:text-neutral-400">Total à payer</span>
                                <span class="text-sm font-bold text-gray-800 dark:text-white">{{ number_format($stats['a_payer_mois'], 2) }} MAD</span>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium mb-1 dark:text-white">Titulaire de la carte</label>
                                <input type="text" class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" placeholder="{{ $user->fullName }}">
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
                                    <span>Payer {{ number_format($stats['a_payer_mois'], 2) }} MAD</span>
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
                        <h4 class="text-xl font-bold text-gray-800 dark:text-white mb-2">Paiement réussi !</h4>
                        <p class="text-gray-600 dark:text-neutral-400 mb-6">Votre transaction a été validée avec succès. Votre reçu sera disponible dans quelques instants.</p>
                        <button @click="location.reload()" type="button" class="py-2 px-4 bg-gray-100 text-gray-800 rounded-lg text-sm font-medium hover:bg-gray-200 dark:bg-neutral-700 dark:text-white">Fermer</button>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
@endsection
