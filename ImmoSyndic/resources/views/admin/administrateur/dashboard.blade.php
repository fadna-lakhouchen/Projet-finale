@extends('layouts.app')

@section('content')
<div class="w-full pt-6 px-4 sm:px-6 md:px-8 pb-12">

    <!-- Alert Banner -->
    @if($stats['paiements_retard'] > 0)
    <div class="bg-yellow-50 border border-yellow-200 text-sm text-yellow-800 rounded-lg p-4 mb-6 shadow-sm dark:bg-yellow-800/10 dark:border-yellow-900 dark:text-yellow-500 flex items-start gap-x-3" role="alert">
        <i data-lucide="alert-circle" class="size-5 shrink-0 mt-0.5"></i>
        <div>
            <span class="font-bold">Attention :</span> {{ $stats['paiements_retard'] }} paiements sont en retard ce mois-ci.
        </div>
    </div>
    @endif

    <div class="mb-6 flex justify-between items-end">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Dashboard</h2>
            <p class="text-sm text-gray-600 dark:text-neutral-400">Suivi global de l'activité du syndic.</p>
        </div>
        <button type="button" data-hs-overlay="#hs-modal-export-report" class="py-2 px-3 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-primary-600 text-white hover:bg-primary-700 shadow-sm transition-colors">
            <i data-lucide="download" class="size-4"></i>
            Exporter Rapport
        </button>
    </div>

    <!-- Stats Grids -->
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
        <!-- Card -->
        <div class="flex flex-col bg-white border border-gray-200 shadow-sm rounded-xl dark:bg-neutral-800 dark:border-neutral-700">
            <div class="p-4 md:p-5">
                <div class="flex items-center gap-x-2">
                    <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-neutral-500">Total Résidents</p>
                </div>
                <div class="mt-1 flex items-center gap-x-2">
                    <h3 class="text-xl sm:text-2xl font-medium text-gray-800 dark:text-neutral-200">{{ number_format($stats['total_residents']) }}</h3>
                </div>
            </div>
        </div>
        <!-- End Card -->

        <!-- Card -->
        <div class="flex flex-col bg-white border border-gray-200 shadow-sm rounded-xl dark:bg-neutral-800 dark:border-neutral-700">
            <div class="p-4 md:p-5">
                <div class="flex items-center gap-x-2">
                    <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-neutral-500">Paiements / Retard</p>
                </div>
                <div class="mt-1 flex items-center gap-x-2">
                    <h3 class="text-xl sm:text-2xl font-medium text-red-600 dark:text-red-500">{{ $stats['paiements_retard'] }}</h3>
                    <span class="text-sm text-gray-500 dark:text-neutral-400">/ {{ $stats['total_paiements_attendus'] }}</span>
                </div>
            </div>
        </div>
        <!-- End Card -->

        <!-- Card -->
        <div class="flex flex-col bg-white border border-gray-200 shadow-sm rounded-xl dark:bg-neutral-800 dark:border-neutral-700">
            <div class="p-4 md:p-5">
                <div class="flex items-center gap-x-2">
                    <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-neutral-500">Problèmes ouverts</p>
                </div>
                <div class="mt-1 flex items-center gap-x-2">
                    <h3 class="text-xl sm:text-2xl font-medium text-gray-800 dark:text-neutral-200">{{ $stats['incidents_ouverts'] }}</h3>
                </div>
            </div>
        </div>
        <!-- End Card -->

        <!-- Card -->
        <div class="flex flex-col bg-white border border-gray-200 shadow-sm rounded-xl dark:bg-neutral-800 dark:border-neutral-700">
            <div class="p-4 md:p-5">
                <div class="flex items-center gap-x-2">
                    <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-neutral-500">Immeubles Gérés</p>
                </div>
                <div class="mt-1 flex items-center gap-x-2">
                    <h3 class="text-xl sm:text-2xl font-medium text-gray-800 dark:text-neutral-200">{{ $stats['total_immeubles'] }}</h3>
                </div>
            </div>
        </div>
        <!-- End Card -->
    </div>

    <!-- Activity Table -->
    <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Activité récente</h3>
    <div class="flex flex-col">
        <div class="-m-1.5 overflow-x-auto">
            <div class="p-1.5 min-w-full inline-block align-middle">
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-neutral-800 dark:border-neutral-700">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                        <thead class="bg-gray-50 dark:bg-neutral-800">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Utilisateur</th>
                                <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Action</th>
                                <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Détails</th>
                                <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                            @forelse($recentActivity as $log)
                            <tr class="hover:bg-gray-50 dark:hover:bg-neutral-700">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-x-3">
                                        <div class="size-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 font-bold dark:bg-neutral-700 dark:text-neutral-300 text-xs">
                                            {{ substr($log->user->prenom, 0, 1) }}{{ substr($log->user->nom, 0, 1) }}
                                        </div>
                                        <div class="grow">
                                            <span class="block text-sm font-semibold text-gray-800 dark:text-neutral-200">{{ $log->user->prenom }} {{ $log->user->nom }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">{{ $log->action }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">{{ $log->details }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-end text-sm text-gray-500 dark:text-neutral-400">{{ $log->created_at->diffForHumans() }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">Aucune activité récente.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Export Rapport -->
<div id="hs-modal-export-report" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none" role="dialog" tabindex="-1" aria-labelledby="hs-modal-export-report-label">
    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
        <div class="w-full flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
            <div class="flex justify-between items-center py-3 px-4 border-b dark:border-neutral-700">
                <h3 id="hs-modal-export-report-label" class="font-bold text-gray-800 dark:text-white">Exporter un rapport d'activité</h3>
                <button type="button" class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-gray-100 text-gray-800 hover:bg-gray-200 focus:outline-none focus:bg-gray-200 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-700 dark:hover:bg-neutral-600 dark:text-neutral-400 dark:focus:bg-neutral-600" aria-label="Close" data-hs-overlay="#hs-modal-export-report">
                    <span class="sr-only">Close</span>
                    <i data-lucide="x" class="size-4"></i>
                </button>
            </div>
            <div class="p-4 overflow-y-auto text-sm text-gray-600 dark:text-neutral-400">
                Sélectionnez le format et la période pour l'exportation du rapport d'activité global.
            </div>
            <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
                <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700" data-hs-overlay="#hs-modal-export-report">Annuler</button>
                <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-primary-600 text-white hover:bg-primary-700" data-hs-overlay="#hs-modal-export-report">Générer l'export</button>
            </div>
        </div>
    </div>
</div>
@endsection
