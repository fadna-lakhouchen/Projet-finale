@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-850 dark:text-white">Rapports & Statistiques</h2>
            <p class="text-sm text-slate-550 dark:text-neutral-400">Analyse globale de la performance et de la santé financière.</p>
        </div>
        <button class="py-2.5 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-bold rounded-xl border border-transparent bg-gradient-to-r from-primary-600 to-purple-600 text-white hover:from-primary-700 hover:to-purple-700 shadow-md shadow-primary-500/15 transition-all glow-hover">
            <i data-lucide="plus-circle" class="size-4"></i>
            Générer Nouveau Rapport
        </button>
    </div>

    <!-- Charts Section -->
    <div class="grid lg:grid-cols-2 gap-6">
        <!-- Revenue Evolution Chart -->
        <div class="bg-white border border-gray-200/60 rounded-2xl shadow-premium p-6 dark:bg-[#0D121F] dark:border-slate-800/60 h-80 flex flex-col hover:shadow-premium-hover transition-all duration-300">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-base font-bold text-slate-800 dark:text-white">Évolution des Recettes</h3>
                <span class="inline-flex items-center gap-x-1 py-1 px-2 rounded-lg text-xs font-semibold bg-emerald-500/10 text-emerald-500">
                    <i data-lucide="trending-up" class="size-3.5"></i> +12% vs mois dernier
                </span>
            </div>
            <div class="flex-grow flex items-end gap-2 px-2">
                @foreach([35, 45, 30, 60, 80, 55, 70, 90, 85, 100, 75, 95] as $h)
                <div class="flex-grow bg-gradient-to-t from-primary-600/30 to-primary-500 hover:from-primary-600 hover:to-purple-600 rounded-t-lg transition-all duration-500 group relative cursor-pointer" style="height: {{ $h }}%">
                    <!-- Tooltip -->
                    <div class="absolute -top-10 left-1/2 -translate-x-1/2 scale-0 group-hover:scale-100 transition-all duration-300 z-10">
                        <div class="bg-slate-900 text-white text-[10px] font-bold py-1 px-2 rounded-md shadow-lg after:content-[''] after:absolute after:top-full after:left-1/2 after:-translate-x-1/2 after:border-4 after:border-transparent after:border-t-slate-900 whitespace-nowrap">
                            {{ $h * 150 }} DH
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-4 flex justify-between text-[10px] text-slate-400 font-bold uppercase tracking-wider">
                <span>Jan</span><span>Mar</span><span>Mai</span><span>Jul</span><span>Sep</span><span>Nov</span>
            </div>
        </div>
        
        <!-- Expenses Breakdown Chart -->
        <div class="bg-white border border-gray-200/60 rounded-2xl shadow-premium p-6 dark:bg-[#0D121F] dark:border-slate-800/60 h-80 flex flex-col hover:shadow-premium-hover transition-all duration-300">
            <h3 class="text-base font-bold text-slate-800 dark:text-white mb-4">Répartition des Dépenses</h3>
            <div class="flex-grow flex items-center justify-center relative">
                <div class="relative size-44">
                    <!-- Stylized SVG Donut Chart for professional crisp vector looks -->
                    <svg class="size-full" viewBox="0 0 36 36">
                        <!-- Background Circle -->
                        <path class="text-slate-100 dark:text-slate-800" stroke-width="4.5" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        <!-- Maintenance segment (74%) -->
                        <path class="text-primary-500 animate-[dash_1.5s_ease-in-out_forwards]" stroke-width="4.5" stroke-dasharray="74, 100" stroke-linecap="round" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        <!-- Electricity segment (16%) -->
                        <path class="text-teal-400" stroke-width="4.5" stroke-dasharray="16, 100" stroke-dashoffset="-74" stroke-linecap="round" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        <!-- Other segment (10%) -->
                        <path class="text-purple-400" stroke-width="4.5" stroke-dasharray="10, 100" stroke-dashoffset="-90" stroke-linecap="round" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center flex-col">
                        <span class="text-3xl font-extrabold text-slate-800 dark:text-white leading-none">74%</span>
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-1">Maintenance</span>
                    </div>
                </div>
            </div>
            <div class="mt-4 flex justify-center gap-6">
                <div class="flex items-center gap-2 text-xs font-semibold text-slate-600 dark:text-neutral-400">
                    <span class="size-2.5 rounded-full bg-primary-500"></span> Maintenance (74%)
                </div>
                <div class="flex items-center gap-2 text-xs font-semibold text-slate-600 dark:text-neutral-400">
                    <span class="size-2.5 rounded-full bg-teal-400"></span> Électricité (16%)
                </div>
                <div class="flex items-center gap-2 text-xs font-semibold text-slate-600 dark:text-neutral-400">
                    <span class="size-2.5 rounded-full bg-purple-400"></span> Autre (10%)
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

