@extends('layouts.admin')

@section('title', 'Gestion des Syndics - ImmoSyndic Admin')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Gestion des Syndics</h2>
            <p class="text-sm text-gray-600 dark:text-neutral-400">Gérez les comptes des syndics et leurs attributions.</p>
        </div>
        <x-admin.button icon="user-plus">
            Nouveau Syndic
        </x-admin.button>
    </div>

    <!-- Stats for Syndics -->
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        <x-admin.stat-card title="Syndics Actifs" value="8" icon="shield-check" />
        <x-admin.stat-card title="Immeubles Non Assignés" value="2" icon="home" :trendUp="false" trend="Alerte" />
        <x-admin.stat-card title="Dernière Validation" value="Aujourd'hui" icon="check-circle" />
    </div>

    <!-- Syndics Table -->
    <x-admin.table :headers="['Syndic / Cabinet', 'Immeubles Assignés', 'Statut Compte', 'Actions']">
        <tr class="hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors">
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center gap-x-3">
                    <div class="size-10 bg-primary-100 rounded-full flex items-center justify-center text-primary-600 font-bold dark:bg-primary-900/40">
                        CP
                    </div>
                    <div>
                        <span class="block text-sm font-semibold text-gray-800 dark:text-neutral-200">Cabinet ProImmo</span>
                        <span class="block text-xs text-gray-500 dark:text-neutral-400">contact@proimmo.ma</span>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                <div class="flex -space-x-2 overflow-hidden">
                    <span class="inline-flex items-center justify-center size-8 rounded-full bg-gray-200 text-xs font-medium text-gray-800 ring-2 ring-white dark:bg-neutral-700 dark:text-white dark:ring-neutral-800">R1</span>
                    <span class="inline-flex items-center justify-center size-8 rounded-full bg-gray-200 text-xs font-medium text-gray-800 ring-2 ring-white dark:bg-neutral-700 dark:text-white dark:ring-neutral-800">R2</span>
                    <span class="inline-flex items-center justify-center size-8 rounded-full bg-primary-100 text-xs font-medium text-primary-800 ring-2 ring-white dark:bg-primary-900/40 dark:text-primary-400 dark:ring-neutral-800">+2</span>
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex items-center gap-x-1.5 py-1 px-2.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-800/30 dark:text-emerald-500">
                    Vérifié
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                <div class="flex justify-end gap-2">
                    <x-admin.button variant="ghost" size="sm" icon="eye" title="Voir détails" />
                    <x-admin.button variant="ghost" size="sm" icon="settings" title="Permissions" />
                </div>
            </td>
        </tr>
    </x-admin.table>
</div>
@endsection
