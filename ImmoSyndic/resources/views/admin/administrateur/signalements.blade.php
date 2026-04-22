@extends('layouts.admin')

@section('title', 'Signalements & Incidents - ImmoSyndic Admin')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Signalements & Incidents</h2>
            <p class="text-sm text-gray-600 dark:text-neutral-400">Suivi des réclamations et des interventions techniques.</p>
        </div>
        <div class="flex gap-2">
            <x-admin.button variant="secondary" icon="filter">Filtrer</x-admin.button>
        </div>
    </div>

    <!-- Incident Stats -->
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        <x-admin.stat-card title="Ouverts" value="12" icon="alert-circle" trend="Urgent" :trendUp="false" />
        <x-admin.stat-card title="En Cours" value="5" icon="loader" />
        <x-admin.stat-card title="Résolus (30j)" value="45" icon="check-circle" trend="+8" />
    </div>

    <!-- Incidents Table -->
    <x-admin.table :headers="['Incident', 'Priorité', 'Rapporté par', 'Status', 'Date']">
        <tr class="hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors">
            <td class="px-6 py-4 whitespace-nowrap">
                <div>
                    <span class="block text-sm font-semibold text-gray-800 dark:text-neutral-200">Panne Ascenseur</span>
                    <span class="block text-xs text-gray-500 dark:text-neutral-400">Résidence Al Amal - Bât A</span>
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex items-center gap-x-1.5 py-1 px-2.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-800/30 dark:text-red-500">
                    Haute
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">Hassan Afaiz</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex items-center gap-x-1.5 py-1 px-2.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800/30 dark:text-blue-500">
                    En cours
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-end text-sm text-gray-500 dark:text-neutral-400">Il y a 2h</td>
        </tr>
    </x-admin.table>
</div>
@endsection
