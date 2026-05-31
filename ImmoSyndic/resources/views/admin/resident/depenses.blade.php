@extends('layouts.app')

@section('content')
<div x-data="residentDepenses" class="space-y-8">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight dark:text-white">Transparence Financière</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">
                Suivez en temps réel l'utilisation de vos cotisations et les dépenses effectuées pour l'immeuble 
                <span class="font-bold text-primary-600 dark:text-primary-400">{{ $immeuble->nom ?? 'N/A' }}</span>.
            </p>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="p-6 bg-gradient-to-br from-rose-500 to-red-600 rounded-2xl shadow-premium text-white relative overflow-hidden group">
            <div class="absolute right-0 bottom-0 translate-y-6 translate-x-6 text-white/10 group-hover:scale-110 transition-transform duration-500">
                <i data-lucide="receipt" class="size-36"></i>
            </div>
            <span class="block text-xs font-bold uppercase tracking-widest text-rose-200">Total des dépenses de l'immeuble</span>
            <span class="block text-3xl font-black mt-2 tracking-tight">{{ number_format($depenses->sum('montant'), 2) }} DH</span>
            <span class="block text-xs text-rose-100/80 mt-2 font-medium">Réparti sur {{ $depenses->count() }} opération(s)</span>
        </div>

        <div class="p-6 bg-white border border-gray-200/60 rounded-2xl shadow-premium dark:bg-[#0D121F] dark:border-slate-800/60 flex items-center gap-x-4">
            <div class="size-12 rounded-xl bg-primary-500/10 flex items-center justify-center text-primary-600 dark:bg-primary-950/20">
                <i data-lucide="check-square" class="size-6"></i>
            </div>
            <div>
                <span class="block text-xs font-bold text-slate-400 dark:text-neutral-450 uppercase tracking-wider">Justification Complète</span>
                <span class="block text-sm font-semibold text-slate-850 dark:text-white mt-1">Tous les reçus de paiement et factures sont joints.</span>
            </div>
        </div>
    </div>

    <!-- Table Container -->
    <div class="flex flex-col bg-white/85 dark:bg-[#0D121F]/90 border border-gray-200/60 dark:border-slate-800/60 rounded-2xl shadow-premium backdrop-blur-md overflow-hidden">
        
        <!-- Filters Bar -->
        <div class="px-6 py-5 border-b border-gray-200/60 dark:border-slate-800/60 bg-white/40 dark:bg-[#0D121F]/40 flex items-center">
            <!-- Search bar -->
            <div class="max-w-sm w-full relative">
                <input x-model="search" type="text" class="py-2.5 px-4 ps-11 block w-full border-gray-200 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 bg-white/50 dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-neutral-300 dark:placeholder-neutral-500 transition-all duration-200" placeholder="Rechercher par titre...">
                <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                    <i data-lucide="search" class="size-4 text-gray-400 dark:text-neutral-500"></i>
                </div>
            </div>
        </div>

        <!-- Table View -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200/60 dark:divide-slate-800/60">
                <thead class="bg-gray-50/50 dark:bg-[#090D16]/40">
                    <tr>
                        <th class="px-6 py-4 text-start text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-slate-400">Désignation</th>
                        <th class="px-6 py-4 text-start text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-slate-400">Montant</th>
                        <th class="px-6 py-4 text-start text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-slate-400">Date d'opération</th>
                        <th class="px-6 py-4 text-end text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-slate-400">Justificatif</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200/60 dark:divide-slate-800/60">
                    @forelse($depenses as $depense)
                    <tr x-show="matches('{{ addslashes($depense->titre) }}')" class="hover:bg-gray-50/50 dark:hover:bg-slate-900/30 transition-colors duration-150">
                        <!-- Désignation -->
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-sm font-semibold text-gray-800 dark:text-slate-200">{{ $depense->titre }}</span>
                                <span class="text-xs text-gray-450 dark:text-slate-500 line-clamp-1 mt-0.5">{{ $depense->description ?? 'Aucun détail fourni.' }}</span>
                            </div>
                        </td>

                        <!-- Montant -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-rose-600 dark:text-rose-450">
                            - {{ number_format($depense->montant, 2) }} DH
                        </td>

                        <!-- Date -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-slate-400">
                            {{ \Carbon\Carbon::parse($depense->date_depense)->translatedFormat('d F Y') }}
                        </td>

                        <!-- Justificatif -->
                        <td class="px-6 py-4 whitespace-nowrap text-end">
                            @if($depense->justificatif_path)
                                <a href="{{ $depense->justificatif_url }}" target="_blank" download class="inline-flex items-center gap-x-2 py-2 px-3 text-xs font-semibold rounded-xl border border-gray-200 bg-white hover:bg-gray-50 text-gray-800 dark:bg-slate-800 dark:border-slate-700 dark:text-white dark:hover:bg-slate-700 transition-all duration-200">
                                    <i data-lucide="download-cloud" class="size-3.5"></i>
                                    Télécharger le reçu
                                </a>
                            @else
                                <span class="text-xs text-gray-400 dark:text-slate-650 italic">Aucun reçu</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-sm text-gray-500 dark:text-slate-400">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <i data-lucide="receipt" class="size-10 text-gray-300 dark:text-slate-700"></i>
                                <span class="font-medium">Aucune dépense enregistrée pour votre immeuble pour le moment.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
