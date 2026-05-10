@extends('layouts.app')

@section('content')
<div class="w-full pt-6 px-4 sm:px-6 md:px-8 pb-12">

    <!-- Alert Banner -->
    <div class="bg-yellow-50 border border-yellow-200 text-sm text-yellow-800 rounded-lg p-4 mb-6 shadow-sm dark:bg-yellow-800/10 dark:border-yellow-900 dark:text-yellow-500 flex items-start gap-x-3" role="alert">
        <i data-lucide="alert-circle" class="size-5 shrink-0 mt-0.5"></i>
        <div>
            <span class="font-bold">À faire :</span> L'intervention concernant l'ascenseur du bâtiment A (Résidence Al Amal) nécessite une affectation de prestataire d'urgence.
        </div>
    </div>

    <div class="mb-6 flex justify-between items-end">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Vue d'ensemble</h2>
            <p class="text-sm text-gray-600 dark:text-neutral-400">Suivi des immeubles dont vous avez la responsabilité.</p>
        </div>
        <button type="button" data-hs-overlay="#hs-modal-new-signalement" class="py-2 px-3 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-primary-600 text-white hover:bg-primary-700 shadow-sm transition-colors">
            <i data-lucide="plus" class="size-4"></i>
            Nouveau signalement
        </button>
    </div>

    <!-- 3 Stats Cards Row -->
    <div class="grid sm:grid-cols-3 gap-4 sm:gap-6 mb-8">
        <!-- Card 1 -->
        <div class="flex flex-col bg-white border border-gray-200 shadow-sm rounded-xl dark:bg-neutral-800 dark:border-neutral-700">
            <div class="p-4 md:p-5">
                <div class="flex items-center gap-x-2">
                    <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-neutral-500">Total Résidents</p>
                </div>
                <div class="mt-1 flex items-center gap-x-2">
                    <h3 class="text-xl sm:text-2xl font-medium text-gray-800 dark:text-neutral-200">{{ $stats['total_residents'] }}</h3>
                    <span class="text-sm text-gray-500 dark:text-neutral-400">/ {{ $stats['total_appartements'] }} appts</span>
                </div>
            </div>
        </div>
        <!-- Card 2 -->
        <div class="flex flex-col bg-white border border-gray-200 shadow-sm rounded-xl dark:bg-neutral-800 dark:border-neutral-700">
            <div class="p-4 md:p-5">
                <div class="flex items-center gap-x-2">
                    <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-neutral-500">Collecté ce mois</p>
                </div>
                <div class="mt-1 flex items-center gap-x-2">
                    <h3 class="text-xl sm:text-2xl font-medium text-gray-800 dark:text-neutral-200">{{ number_format($stats['paiements_ce_mois'], 2) }} DH</h3>
                </div>
            </div>
        </div>
        <!-- Card 3 -->
        <div class="flex flex-col bg-white border border-gray-200 shadow-sm rounded-xl dark:bg-neutral-800 dark:border-neutral-700">
            <div class="p-4 md:p-5">
                <div class="flex items-center gap-x-2">
                    <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-neutral-500">Problèmes ouverts</p>
                </div>
                <div class="mt-1 flex items-center gap-x-2">
                    <h3 class="text-xl sm:text-2xl font-medium text-red-600 dark:text-red-500">{{ $stats['incidents_ouverts'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Table -->
    <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Activité récente sur vos immeubles</h3>
    <div class="flex flex-col">
        <div class="-m-1.5 overflow-x-auto">
            <div class="p-1.5 min-w-full inline-block align-middle">
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-neutral-800 dark:border-neutral-700">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                        <thead class="bg-gray-50 dark:bg-neutral-800">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Événement</th>
                                <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Concerne</th>
                                <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Détails</th>
                                <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Date & Heure</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                            <tr class="hover:bg-gray-50 dark:hover:bg-neutral-700 bg-red-50/30 dark:bg-red-900/10">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-x-3">
                                        <div class="size-8 rounded-full bg-red-100 flex items-center justify-center text-red-600 dark:bg-red-800/30 dark:text-red-500">
                                            <i data-lucide="alert-triangle" class="size-4"></i>
                                        </div>
                                        <span class="block text-sm font-semibold text-gray-800 dark:text-neutral-200">Signalement (Admin)</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">Résidence Al Amal</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">Ordre d'intervention: Ascenseur Bât A</td>
                                <td class="px-6 py-4 whitespace-nowrap text-end text-sm text-gray-500 dark:text-neutral-400">Aujourd'hui, 09:12</td>
                            </tr>
                            <tr class="hover:bg-gray-50 dark:hover:bg-neutral-700">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-x-3">
                                        <div class="size-8 rounded-full bg-green-100 flex items-center justify-center text-green-600 dark:bg-green-800/30 dark:text-green-500">
                                            <i data-lucide="check" class="size-4"></i>
                                        </div>
                                        <span class="block text-sm font-semibold text-gray-800 dark:text-neutral-200">Paiement reçu</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">Tour Hassan - Appt 12</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">850 MAD (Charges Mars)</td>
                                <td class="px-6 py-4 whitespace-nowrap text-end text-sm text-gray-500 dark:text-neutral-400">Hier, 18:30</td>
                            </tr>
                            <tr class="hover:bg-gray-50 dark:hover:bg-neutral-700">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-x-3">
                                        <div class="size-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 dark:bg-blue-800/30 dark:text-blue-500">
                                            <i data-lucide="info" class="size-4"></i>
                                        </div>
                                        <span class="block text-sm font-semibold text-gray-800 dark:text-neutral-200">Signalement Résident</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">Résidence Al Amal - Appt 4</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">Ampoule couloir grillée</td>
                                <td class="px-6 py-4 whitespace-nowrap text-end text-sm text-gray-500 dark:text-neutral-400">10 Mars 2026</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nouveau Signalement -->
<div id="hs-modal-new-signalement" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none" role="dialog" tabindex="-1" aria-labelledby="hs-modal-new-signalement-label">
    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
        <div class="w-full flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
            <div class="flex justify-between items-center py-3 px-4 border-b dark:border-neutral-700">
                <h3 id="hs-modal-new-signalement-label" class="font-bold text-gray-800 dark:text-white">Nouveau Signalement / Intervention</h3>
                <button type="button" class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-gray-100 text-gray-800 hover:bg-gray-200 focus:outline-none focus:bg-gray-200 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-700 dark:hover:bg-neutral-600 dark:text-neutral-400 dark:focus:bg-neutral-600" aria-label="Close" data-hs-overlay="#hs-modal-new-signalement">
                    <span class="sr-only">Close</span>
                    <i data-lucide="x" class="size-4"></i>
                </button>
            </div>
            <div class="p-4 overflow-y-auto">
                <p>Form to add new signalement here...</p>
            </div>
            <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
                <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700" data-hs-overlay="#hs-modal-new-signalement">Annuler</button>
                <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-primary-600 text-white hover:bg-primary-700" data-hs-overlay="#hs-modal-new-signalement">Créer le signalement</button>
            </div>
        </div>
    </div>
</div>
@endsection
