@extends('layouts.admin')

@section('title', 'Dashboard - ImmoSyndic Admin')

@section('content')
<div class="space-y-8">
    <!-- Alert Banner (Alpine.js) -->
    <div x-data="{ show: true }" x-show="show" x-transition 
        class="bg-yellow-50 border border-yellow-200 text-sm text-yellow-800 rounded-lg p-4 shadow-sm dark:bg-yellow-800/10 dark:border-yellow-900 dark:text-yellow-500 flex items-start gap-x-3" role="alert">
        <i data-lucide="alert-circle" class="size-5 shrink-0 mt-0.5"></i>
        <div class="grow">
            <span class="font-bold">Attention :</span> 12 paiements sont en retard de plus de 30 jours ce mois-ci.
        </div>
        <button type="button" @click="show = false" class="size-5 inline-flex justify-center items-center gap-x-2 rounded-lg border border-transparent text-yellow-800 hover:bg-yellow-200 focus:outline-none focus:bg-yellow-200 dark:text-yellow-500 dark:hover:bg-yellow-900">
            <i data-lucide="x" class="size-4"></i>
        </button>
    </div>

    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Vue d'ensemble</h2>
            <p class="text-sm text-gray-600 dark:text-neutral-400">Suivi global de l'activité de la plateforme.</p>
        </div>
        <x-admin.button icon="download">
            Exporter Rapport
        </x-admin.button>
    </div>

    <!-- Stats Grid -->
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <x-admin.stat-card title="Total Résidents" value="1,245" trend="5%" icon="users" />
        <x-admin.stat-card title="Paiements / Retard" value="12" trend="180 total" :trendUp="false" icon="credit-card" />
        <x-admin.stat-card title="Signalements" value="23" trend="2 nouveaux" icon="alert-triangle" />
        <x-admin.stat-card title="Immeubles Gérés" value="14" icon="building" />
    </div>

    <!-- Activity Table -->
    <div class="space-y-4">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white">Activité récente</h3>
            <x-admin.button variant="ghost" size="sm">Voir tout</x-admin.button>
        </div>
        
        <x-admin.table :headers="['Utilisateur', 'Action', 'Cible / Détails', 'Date']">
            <tr class="hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center gap-x-3">
                        <div class="size-8 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-bold dark:bg-primary-900/40 dark:text-primary-400 text-xs">HS</div>
                        <span class="text-sm font-semibold text-gray-800 dark:text-neutral-200">Hassan Afaiz</span>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">Signalement (Ascenseur)</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">Résidence Al Amal - Bât A</td>
                <td class="px-6 py-4 whitespace-nowrap text-end text-sm text-gray-500 dark:text-neutral-400">Aujourd'hui, 10:23</td>
            </tr>
            <tr class="hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center gap-x-3">
                        <div class="size-8 rounded-full bg-teal-100 flex items-center justify-center text-teal-700 font-bold dark:bg-teal-900/40 dark:text-teal-400 text-xs">HN</div>
                        <span class="text-sm font-semibold text-gray-800 dark:text-neutral-200">Hassnae Chrifi</span>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">Paiement effectué</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">850 MAD - Charges T1</td>
                <td class="px-6 py-4 whitespace-nowrap text-end text-sm text-gray-500 dark:text-neutral-400">Hier, 15:40</td>
            </tr>
        </x-admin.table>
    </div>
</div>
@endsection
