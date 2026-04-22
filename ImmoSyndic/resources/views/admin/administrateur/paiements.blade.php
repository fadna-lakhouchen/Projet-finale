@extends('layouts.admin')

@section('title', 'Paiements & Recouvrements - ImmoSyndic Admin')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Paiements & Recouvrements</h2>
            <p class="text-sm text-gray-600 dark:text-neutral-400">Suivi des flux financiers et des retards de paiement.</p>
        </div>
        <div class="flex gap-2">
            <x-admin.button variant="secondary" icon="file-text">Rapport de Caisse</x-admin.button>
            <x-admin.button icon="plus">Saisir Paiement</x-admin.button>
        </div>
    </div>

    <!-- Payment Stats -->
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <x-admin.stat-card title="Collecté (Ce mois)" value="45,800 MAD" icon="trending-up" trend="+12%" />
        <x-admin.stat-card title="En Attente" value="12,400 MAD" icon="clock" />
        <x-admin.stat-card title="Taux de Recouvrement" value="78%" icon="pie-chart" trend="+2%" />
        <x-admin.stat-card title="Retards Critiques" value="5" icon="alert-octagon" :trendUp="false" trend="Action requise" />
    </div>

    <!-- Payments Table -->
    <x-admin.table :headers="['Référence', 'Résident', 'Montant', 'Statut', 'Date']">
        <tr class="hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors">
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">#PAY-00124</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center gap-x-3">
                    <span class="text-sm font-semibold text-gray-800 dark:text-neutral-200">Hassan Afaiz</span>
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">850 MAD</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex items-center gap-x-1.5 py-1 px-2.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-800/30 dark:text-emerald-500">
                    Complété
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-end text-sm text-gray-500 dark:text-neutral-400">18 Avr 2026</td>
        </tr>
        <tr class="hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors">
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">#PAY-00125</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="text-sm font-semibold text-gray-800 dark:text-neutral-200">Omar Benjelloun</span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">1,200 MAD</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex items-center gap-x-1.5 py-1 px-2.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-800/30 dark:text-red-500">
                    En retard
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-end text-sm text-gray-500 dark:text-neutral-400">05 Avr 2026</td>
        </tr>
    </x-admin.table>
</div>
@endsection
