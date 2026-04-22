@extends('layouts.admin')

@section('title', 'Gestion des Résidents - ImmoSyndic Admin')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Gestion des Résidents</h2>
            <p class="text-sm text-gray-600 dark:text-neutral-400">Liste des copropriétaires et locataires inscrits.</p>
        </div>
        <x-admin.button icon="plus">
            Ajouter un Résident
        </x-admin.button>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 dark:bg-neutral-800 dark:border-neutral-700">
        <div class="flex flex-col sm:flex-row gap-4">
            <div class="grow relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <i data-lucide="search" class="size-4 text-gray-400"></i>
                </div>
                <input type="text" class="py-2 px-3 ps-10 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500" placeholder="Rechercher un résident...">
            </div>
            <select class="py-2 px-3 pe-9 block border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
                <option selected>Tous les immeubles</option>
                <option>Résidence Al Amal</option>
                <option>Immeuble Horizon</option>
            </select>
        </div>
    </div>

    <!-- Residents Table -->
    <x-admin.table :headers="['Résident', 'Immeuble / Unité', 'Statut', 'Actions']">
        <tr class="hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors">
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center gap-x-3">
                    <img class="size-10 rounded-full" src="https://ui-avatars.com/api/?name=Hassan+Afaiz&background=ebf0fe&color=3b66f5" alt="Avatar">
                    <div>
                        <span class="block text-sm font-semibold text-gray-800 dark:text-neutral-200">Hassan Afaiz</span>
                        <span class="block text-xs text-gray-500 dark:text-neutral-400">hassan.afaiz@email.com</span>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                Al Amal - Bât A, Appt 12
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex items-center gap-x-1.5 py-1 px-2.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-800/30 dark:text-green-500">
                    Actif
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                <div class="flex justify-end gap-2">
                    <x-admin.button variant="ghost" size="sm" icon="edit-2" title="Modifier" />
                    <x-admin.button variant="ghost" size="sm" icon="trash-2" class="text-red-600 hover:text-red-700" title="Supprimer" />
                </div>
            </td>
        </tr>
        <!-- Repeat for more rows as needed -->
    </x-admin.table>
</div>
@endsection
