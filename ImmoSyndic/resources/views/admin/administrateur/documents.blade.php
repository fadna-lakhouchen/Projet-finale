@extends('layouts.admin')

@section('title', 'Documents & Archives - ImmoSyndic Admin')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Documents & Archives</h2>
            <p class="text-sm text-gray-600 dark:text-neutral-400">Coffre-fort documentaire et gestion des archives de copropriété.</p>
        </div>
        <x-admin.button icon="upload">
            Déposer un Document
        </x-admin.button>
    </div>

    <!-- Storage Usage -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 dark:bg-neutral-800 dark:border-neutral-700">
        <div class="flex justify-between items-center mb-2">
            <h4 class="text-sm font-semibold text-gray-800 dark:text-white">Utilisation du Stockage</h4>
            <span class="text-xs text-gray-500">65% (1.3 GB / 2 GB)</span>
        </div>
        <div class="flex w-full h-2 bg-gray-200 rounded-full overflow-hidden dark:bg-neutral-700">
            <div class="flex flex-col justify-center overflow-hidden bg-primary-600" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
    </div>

    <!-- Folders/Categories -->
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="p-4 bg-white border border-gray-200 rounded-xl hover:shadow-md transition-shadow dark:bg-neutral-800 dark:border-neutral-700 cursor-pointer group">
            <div class="flex items-center gap-x-3">
                <div class="size-10 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                    <i data-lucide="folder" class="size-5"></i>
                </div>
                <div>
                    <span class="block text-sm font-bold dark:text-white">Procès-verbaux</span>
                    <span class="block text-xs text-gray-500">24 fichiers</span>
                </div>
            </div>
        </div>
        <div class="p-4 bg-white border border-gray-200 rounded-xl hover:shadow-md transition-shadow dark:bg-neutral-800 dark:border-neutral-700 cursor-pointer group">
            <div class="flex items-center gap-x-3">
                <div class="size-10 rounded-lg bg-orange-50 flex items-center justify-center text-orange-600 group-hover:bg-orange-600 group-hover:text-white transition-colors">
                    <i data-lucide="file-text" class="size-5"></i>
                </div>
                <div>
                    <span class="block text-sm font-bold dark:text-white">Contrats & Devis</span>
                    <span class="block text-xs text-gray-500">12 fichiers</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Documents Table -->
    <x-admin.table :headers="['Nom du Fichier', 'Type', 'Taille', 'Date d\'ajout', 'Actions']">
        <tr class="hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors">
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center gap-x-3">
                    <i data-lucide="file-text" class="size-4 text-red-500"></i>
                    <span class="text-sm font-medium text-gray-800 dark:text-neutral-200">PV_AG_Avril_2026.pdf</span>
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 uppercase">PDF</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2.4 MB</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Hier</td>
            <td class="px-6 py-4 whitespace-nowrap text-end">
                <x-admin.button variant="ghost" size="sm" icon="download" />
            </td>
        </tr>
    </x-admin.table>
</div>
@endsection
