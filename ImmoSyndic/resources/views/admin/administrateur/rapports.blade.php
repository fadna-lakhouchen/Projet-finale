@extends('layouts.admin')

@section('title', 'Rapports & Statistiques - ImmoSyndic Admin')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Rapports & Statistiques</h2>
            <p class="text-sm text-gray-600 dark:text-neutral-400">Analyse globale de la performance et de la santé financière.</p>
        </div>
        <x-admin.button icon="plus-circle">Générer Nouveau Rapport</x-admin.button>
    </div>

    <!-- Charts Placeholder (using a stylized card for now) -->
    <div class="grid lg:grid-cols-2 gap-6">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 dark:bg-neutral-800 dark:border-neutral-700 h-80 flex flex-col">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Évolution des Recettes</h3>
            <div class="flex-grow flex items-end gap-2 px-2">
                @foreach([35, 45, 30, 60, 80, 55, 70, 90, 85, 100, 75, 95] as $h)
                <div class="flex-grow bg-primary-500/20 hover:bg-primary-500 rounded-t-sm transition-all duration-300" style="height: {{ $h }}%"></div>
                @endforeach
            </div>
            <div class="mt-4 flex justify-between text-[10px] text-gray-400 uppercase tracking-tighter">
                <span>Jan</span><span>Mar</span><span>Mai</span><span>Jul</span><span>Sep</span><span>Nov</span>
            </div>
        </div>
        
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 dark:bg-neutral-800 dark:border-neutral-700 h-80">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Répartition des Dépenses</h3>
            <div class="relative size-48 mx-auto mt-8">
                <!-- Simple stylized CSS pie chart representation -->
                <div class="absolute inset-0 rounded-full border-[12px] border-primary-500"></div>
                <div class="absolute inset-0 rounded-full border-[12px] border-teal-500 border-t-transparent border-l-transparent"></div>
                <div class="absolute inset-0 flex items-center justify-center flex-col">
                    <span class="text-2xl font-bold text-gray-800 dark:text-white">74%</span>
                    <span class="text-[10px] text-gray-500">Maintenance</span>
                </div>
            </div>
            <div class="mt-6 flex justify-center gap-4">
                <div class="flex items-center gap-1.5 text-xs"><span class="size-2 rounded-full bg-primary-500"></span> Maintenance</div>
                <div class="flex items-center gap-1.5 text-xs"><span class="size-2 rounded-full bg-teal-500"></span> Électricité</div>
                <div class="flex items-center gap-1.5 text-xs"><span class="size-2 rounded-full bg-orange-500"></span> Autre</div>
            </div>
        </div>
    </div>
</div>
@endsection
