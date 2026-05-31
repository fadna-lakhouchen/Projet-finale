@extends('layouts.app')

@section('content')
<div x-data="residentAnnonces" class="space-y-6 max-w-4xl mx-auto">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 dark:text-white">Annonces & Bulletins</h2>
            <p class="text-sm text-slate-500 dark:text-neutral-400">
                Restez informé des actualités, travaux et réunions de votre immeuble 
                <strong class="font-bold text-slate-800 dark:text-white">{{ $immeuble->nom ?? 'N/A' }}</strong>.
            </p>
        </div>
        <div class="relative max-w-xs w-full">
            <input x-model="search" type="text" class="py-2 px-4 ps-11 block w-full border border-gray-250 dark:border-slate-800 dark:bg-[#080B11] dark:text-slate-350 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 bg-white/50 dark:bg-slate-900/30" placeholder="Rechercher une annonce...">
            <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                <i data-lucide="search" class="size-4 text-gray-400"></i>
            </div>
        </div>
    </div>

    <!-- Feed Container -->
    <div class="space-y-4">
        @forelse($annonces as $annonce)
        <div x-show="matches('{{ addslashes($annonce->titre) }}', '{{ addslashes($annonce->contenu) }}')" class="flex flex-col bg-white/80 dark:bg-[#0D121F]/90 border border-gray-200/60 dark:border-slate-800/60 rounded-2xl shadow-premium backdrop-blur-md p-6 hover:shadow-premium-hover transition-all duration-300">
            <!-- Card Header -->
            <div class="flex flex-wrap items-center justify-between gap-4 border-b border-gray-100 dark:border-slate-800/60 pb-4 mb-4">
                <div class="flex items-center gap-x-3.5">
                    <img class="size-10 rounded-xl ring-2 ring-primary-500/10 shrink-0 object-cover" 
                         src="https://ui-avatars.com/api/?name={{ urlencode($annonce->syndic->prenom . ' ' . $annonce->syndic->nom) }}&background=3b66f5&color=fff&font-size=0.4" 
                         alt="Syndic Avatar">
                    <div>
                        <span class="block text-sm font-bold text-slate-800 dark:text-neutral-250 leading-none mb-1">
                            {{ $annonce->syndic->prenom }} {{ $annonce->syndic->nom }}
                        </span>
                        <span class="inline-flex items-center gap-x-1 py-0.5 px-2 rounded-full text-[9px] font-bold bg-primary-500/20 text-primary-400">
                            <span class="size-1 bg-primary-400 rounded-full"></span>
                            Syndic de l'immeuble
                        </span>
                    </div>
                </div>
                <div class="flex items-center gap-1.5 text-xs text-slate-450 dark:text-neutral-500 font-semibold">
                    <i data-lucide="calendar" class="size-4"></i>
                    <span>Publié le {{ \Carbon\Carbon::parse($annonce->date_publication)->translatedFormat('d F Y') }}</span>
                </div>
            </div>

            <!-- Card Content -->
            <div class="space-y-2">
                <h3 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2">
                    <i data-lucide="megaphone" class="size-5 text-primary-500 shrink-0"></i>
                    {{ $annonce->titre }}
                </h3>
                <p class="text-sm text-slate-600 dark:text-neutral-350 leading-relaxed whitespace-pre-wrap">{{ $annonce->contenu }}</p>
            </div>
        </div>
        @empty
        <!-- Empty State -->
        <div class="flex flex-col items-center justify-center text-center bg-white/80 dark:bg-[#0D121F]/90 border border-gray-200/60 dark:border-slate-800/60 rounded-2xl shadow-premium backdrop-blur-md p-12">
            <div class="p-4 bg-primary-500/10 rounded-2xl border border-primary-500/20 text-primary-500 mb-4 animate-pulse">
                <i data-lucide="megaphone" class="size-8"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-1">Aucune annonce disponible</h3>
            <p class="text-sm text-slate-500 dark:text-neutral-400 max-w-sm">Le Syndic n'a publié aucune annonce ou bulletin d'information pour votre immeuble pour le moment.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
