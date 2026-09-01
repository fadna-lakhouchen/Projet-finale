@extends('layouts.auth')

@section('content')
<main class="w-full max-w-md mx-auto p-6">
    <div class="bg-white border border-gray-200 rounded-xl shadow-premium dark:bg-neutral-900 dark:border-neutral-800 overflow-hidden">
        <div class="p-6 sm:p-8">
            <div class="text-center">
                <!-- Logo -->
                <div class="flex justify-center mb-6">
                    <img src="{{ asset('logo.png') }}" alt="Logo ImmoSyn" class="h-16 w-auto object-contain">
                </div>

                <!-- Status Icon -->
                <div class="inline-flex justify-center items-center size-16 bg-amber-50 rounded-full dark:bg-amber-950/30 text-amber-600 dark:text-amber-400 mb-4 ring-8 ring-amber-500/10">
                    <i data-lucide="clock" class="size-8"></i>
                </div>

                <h1 class="block text-2xl font-bold text-gray-800 dark:text-white">{{ __('Compte en attente') }}</h1>
                <p class="mt-2 text-sm text-gray-600 dark:text-neutral-400">
                    Bonjour <span class="font-semibold text-gray-850 dark:text-white">{{ $user->prenom }} {{ $user->nom }}</span>, votre email a été validé !
                </p>
            </div>

            <div class="mt-6 space-y-4">
                <div class="p-4 bg-amber-50/50 border border-amber-100 rounded-xl text-center dark:bg-amber-950/20 dark:border-amber-900/30">
                    <p class="text-xs text-amber-800 dark:text-amber-300 leading-relaxed font-medium">
                        Votre compte est en attente d'activation par le syndic de votre immeuble. Pour des raisons de sécurité, vous ne pourrez accéder à votre tableau de bord qu'après approbation de votre inscription.
                    </p>
                </div>

                @php
                    $appt = $user->appartements->first();
                    $immeuble = $appt ? $appt->immeuble : null;
                @endphp

                <!-- Registration Details -->
                <div class="border border-gray-100 dark:border-neutral-800 rounded-xl p-4 space-y-3 bg-gray-50/50 dark:bg-neutral-900/50">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-neutral-500">{{ __('Détails de l\'inscription') }}</h3>
                    
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500 dark:text-neutral-400">{{ __('Immeuble') }}</span>
                        <span class="font-semibold text-gray-800 dark:text-neutral-200">{{ $immeuble ? $immeuble->nom : 'N/A' }}</span>
                    </div>

                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500 dark:text-neutral-400">{{ __('Appartement') }}</span>
                        <span class="font-semibold text-gray-800 dark:text-neutral-200">{{ $appt ? 'Appt ' . $appt->numero : 'N/A' }}</span>
                    </div>

                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500 dark:text-neutral-400">{{ __('Statut du compte') }}</span>
                        <span class="inline-flex items-center gap-x-1.5 py-1 px-2.5 rounded-full text-xs font-bold bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20">
                            En attente
                        </span>
                    </div>
                </div>

                <!-- Logout Form -->
                <form action="{{ route('logout') }}" method="POST" class="pt-2">
                    @csrf
                    <button type="submit" class="w-full py-2.5 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-xl border border-gray-200 bg-white text-gray-700 shadow-sm hover:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-350 dark:hover:bg-neutral-800/80 transition-all">
                        <i data-lucide="log-out" class="size-4"></i>
                        Se déconnecter
                    </button>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection
