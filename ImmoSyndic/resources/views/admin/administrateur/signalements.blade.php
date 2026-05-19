@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-850 dark:text-white">Signalements & Incidents</h2>
            <p class="text-sm text-slate-550 dark:text-neutral-400">Suivi des réclamations et des interventions techniques.</p>
        </div>
        <div class="flex gap-2">
            <button class="py-2.5 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-bold rounded-xl border border-gray-200/60 bg-white text-slate-700 hover:bg-slate-50 hover:text-slate-800 shadow-premium transition-all glow-hover dark:bg-[#0D121F] dark:border-slate-800/60 dark:text-neutral-350 dark:hover:bg-slate-800/30 dark:hover:text-white">
                <i data-lucide="filter" class="size-4"></i>
                Filtrer
            </button>
        </div>
    </div>

    <!-- Incident Stats -->
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        <!-- Stat Card 1 -->
        <div class="p-5 bg-white border border-gray-200/60 rounded-2xl shadow-premium hover:shadow-premium-hover transition-all duration-300 dark:bg-[#0D121F] dark:border-slate-800/60 flex items-center justify-between group">
            <div class="space-y-2">
                <span class="text-xs font-bold text-slate-400 dark:text-neutral-450 uppercase tracking-wider">Ouverts</span>
                <div class="flex items-baseline gap-x-2">
                    <span class="text-3xl font-extrabold text-slate-850 dark:text-white leading-none">12</span>
                    <span class="inline-flex items-center gap-x-1 py-0.5 px-1.5 rounded-full text-[10px] font-bold bg-rose-500/10 text-rose-500">
                        Urgent
                    </span>
                </div>
            </div>
            <div class="size-12 rounded-xl bg-rose-500/10 text-rose-500 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                <i data-lucide="alert-circle" class="size-5.5"></i>
            </div>
        </div>

        <!-- Stat Card 2 -->
        <div class="p-5 bg-white border border-gray-200/60 rounded-2xl shadow-premium hover:shadow-premium-hover transition-all duration-300 dark:bg-[#0D121F] dark:border-slate-800/60 flex items-center justify-between group">
            <div class="space-y-2">
                <span class="text-xs font-bold text-slate-400 dark:text-neutral-450 uppercase tracking-wider">En Cours</span>
                <div class="flex items-baseline gap-x-2">
                    <span class="text-3xl font-extrabold text-slate-850 dark:text-white leading-none">5</span>
                </div>
            </div>
            <div class="size-12 rounded-xl bg-blue-500/10 text-blue-500 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                <i data-lucide="loader" class="size-5.5 animate-spin"></i>
            </div>
        </div>

        <!-- Stat Card 3 -->
        <div class="p-5 bg-white border border-gray-200/60 rounded-2xl shadow-premium hover:shadow-premium-hover transition-all duration-300 dark:bg-[#0D121F] dark:border-slate-800/60 flex items-center justify-between group">
            <div class="space-y-2">
                <span class="text-xs font-bold text-slate-400 dark:text-neutral-450 uppercase tracking-wider">Résolus (30j)</span>
                <div class="flex items-baseline gap-x-2">
                    <span class="text-3xl font-extrabold text-slate-850 dark:text-white leading-none">45</span>
                    <span class="inline-flex items-center gap-x-1 py-0.5 px-1.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-500">
                        +8
                    </span>
                </div>
            </div>
            <div class="size-12 rounded-xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                <i data-lucide="check-circle" class="size-5.5"></i>
            </div>
        </div>
    </div>

    <!-- Incidents Table -->
    <div class="flex flex-col border border-gray-200/60 dark:border-slate-800/60 rounded-2xl shadow-premium bg-white dark:bg-[#0D121F] overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200/60 dark:border-slate-800/60 bg-slate-50/50 dark:bg-slate-900/30 flex items-center justify-between">
            <h4 class="text-sm font-bold text-slate-800 dark:text-white">Liste des Signalements</h4>
            <span class="text-xs font-semibold text-slate-450">Mise à jour en temps réel</span>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-250 dark:divide-slate-800">
                <thead class="bg-slate-50 dark:bg-slate-900/50">
                    <tr>
                        <th class="px-6 py-3.5 text-start text-xs font-semibold text-slate-400 uppercase dark:text-neutral-450">Incident</th>
                        <th class="px-6 py-3.5 text-start text-xs font-semibold text-slate-400 uppercase dark:text-neutral-450">Priorité</th>
                        <th class="px-6 py-3.5 text-start text-xs font-semibold text-slate-400 uppercase dark:text-neutral-450">Rapporté par</th>
                        <th class="px-6 py-3.5 text-start text-xs font-semibold text-slate-400 uppercase dark:text-neutral-450">Status</th>
                        <th class="px-6 py-3.5 text-end text-xs font-semibold text-slate-400 uppercase dark:text-neutral-450">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200/60 dark:divide-slate-800/60">
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/10 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <span class="block text-sm font-semibold text-slate-800 dark:text-neutral-250">Panne Ascenseur</span>
                                <span class="block text-xs text-slate-400 dark:text-neutral-450 mt-0.5">Résidence Al Amal - Bât A</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center gap-x-1.5 py-1 px-2.5 rounded-full text-xs font-bold bg-rose-500/10 text-rose-500">
                                <span class="size-1.5 inline-block bg-rose-500 rounded-full"></span>
                                Haute
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-650 dark:text-neutral-300">Hassan Afaiz</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center gap-x-1.5 py-1 px-2.5 rounded-full text-xs font-bold bg-blue-500/10 text-blue-500">
                                <span class="size-1.5 inline-block bg-blue-500 rounded-full animate-pulse"></span>
                                En cours
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-end text-sm text-slate-400 dark:text-neutral-450 font-semibold">Il y a 2h</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

