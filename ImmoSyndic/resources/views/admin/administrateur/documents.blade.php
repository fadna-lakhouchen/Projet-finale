@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-850 dark:text-white">Documents & Archives</h2>
            <p class="text-sm text-slate-550 dark:text-neutral-400">Coffre-fort documentaire et gestion des archives de copropriété.</p>
        </div>
        <button class="py-2.5 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-bold rounded-xl border border-transparent bg-primary-600 text-white hover:bg-primary-700 shadow-md shadow-primary-500/10 transition-all glow-hover">
            <i data-lucide="upload" class="size-4"></i>
            Déposer un Document
        </button>
    </div>

    <!-- Storage Usage -->
    <div class="bg-white border border-gray-200/60 rounded-2xl shadow-premium p-6 dark:bg-[#0D121F] dark:border-slate-800/60">
        <div class="flex justify-between items-center mb-3">
            <h4 class="text-sm font-bold text-slate-800 dark:text-white">Utilisation du Stockage</h4>
            <span class="text-xs font-semibold text-slate-400 dark:text-neutral-450">65% (1.3 GB / 2 GB)</span>
        </div>
        <div class="flex w-full h-2.5 bg-gray-100 rounded-full overflow-hidden dark:bg-slate-800 shadow-inner">
            <div class="flex flex-col justify-center overflow-hidden bg-gradient-to-r from-primary-500 to-purple-500 rounded-full" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
    </div>

    <!-- Folders/Categories -->
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="p-5 bg-white border border-gray-200/60 rounded-2xl shadow-premium hover:shadow-premium-hover transition-all duration-300 dark:bg-[#0D121F] dark:border-slate-800/60 cursor-pointer group">
            <div class="flex items-center gap-x-4">
                <div class="size-11 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300">
                    <i data-lucide="folder" class="size-5"></i>
                </div>
                <div>
                    <span class="block text-sm font-bold text-slate-800 dark:text-white">Procès-verbaux</span>
                    <span class="block text-xs font-semibold text-slate-400 dark:text-neutral-450 mt-0.5">24 fichiers</span>
                </div>
            </div>
        </div>
        <div class="p-5 bg-white border border-gray-200/60 rounded-2xl shadow-premium hover:shadow-premium-hover transition-all duration-300 dark:bg-[#0D121F] dark:border-slate-800/60 cursor-pointer group">
            <div class="flex items-center gap-x-4">
                <div class="size-11 rounded-xl bg-purple-500/10 flex items-center justify-center text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition-all duration-300">
                    <i data-lucide="file-text" class="size-5"></i>
                </div>
                <div>
                    <span class="block text-sm font-bold text-slate-800 dark:text-white">Contrats & Devis</span>
                    <span class="block text-xs font-semibold text-slate-400 dark:text-neutral-450 mt-0.5">12 fichiers</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Documents Table -->
    <div class="flex flex-col border border-gray-200/60 dark:border-slate-800/60 rounded-2xl shadow-premium bg-white dark:bg-[#0D121F] overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200/60 dark:border-slate-800/60 bg-slate-50/50 dark:bg-slate-900/30">
            <h4 class="text-sm font-bold text-slate-800 dark:text-white">Documents Récents</h4>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-250 dark:divide-slate-800">
                <thead class="bg-slate-50 dark:bg-slate-900/50">
                    <tr>
                        <th class="px-6 py-3.5 text-start text-xs font-semibold text-slate-400 uppercase dark:text-neutral-450">Nom du Fichier</th>
                        <th class="px-6 py-3.5 text-start text-xs font-semibold text-slate-400 uppercase dark:text-neutral-450">Type</th>
                        <th class="px-6 py-3.5 text-start text-xs font-semibold text-slate-400 uppercase dark:text-neutral-450">Taille</th>
                        <th class="px-6 py-3.5 text-start text-xs font-semibold text-slate-400 uppercase dark:text-neutral-450">Date d'ajout</th>
                        <th class="px-6 py-3.5 text-end text-xs font-semibold text-slate-400 uppercase dark:text-neutral-450">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200/60 dark:divide-slate-800/60">
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/10 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-x-3">
                                <i data-lucide="file-text" class="size-4.5 text-red-500"></i>
                                <span class="text-sm font-semibold text-slate-800 dark:text-neutral-250">PV_AG_Avril_2026.pdf</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-xs font-bold text-slate-400 uppercase">PDF</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-550 dark:text-neutral-400">2.4 MB</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-550 dark:text-neutral-400">Hier</td>
                        <td class="px-6 py-4 whitespace-nowrap text-end">
                            <button class="py-2 px-3 inline-flex items-center justify-center rounded-xl bg-slate-100/80 text-slate-600 hover:bg-primary-50 hover:text-primary-600 dark:bg-slate-800 dark:text-neutral-400 dark:hover:bg-slate-700 dark:hover:text-white transition-all duration-300">
                                <i data-lucide="download" class="size-4"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
