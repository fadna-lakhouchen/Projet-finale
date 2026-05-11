@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl border border-gray-200 dark:border-gray-700">
        <div class="p-8">
            <div class="flex items-center justify-between mb-8">
                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">
                    {{ __('Tableau de bord') }}
                </h1>
            </div>

            @if (session('status'))
                <div class="mb-6 bg-green-100 border border-green-200 text-sm text-green-800 rounded-lg p-4 dark:bg-green-800/10 dark:border-green-900 dark:text-green-500" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Welcome Card -->
                <div class="col-span-1 md:col-span-3 bg-gradient-to-r from-brand-800 to-teal-500 rounded-2xl p-8 text-white shadow-lg">
                    <h2 class="text-2xl font-bold mb-2">Bienvenue, {{ Auth::user()->prenom }} !</h2>
                    <p class="text-teal-50 font-medium opacity-90">Vous êtes maintenant connecté à votre espace ImmoSyndic.</p>
                </div>

                <!-- Stats/Quick Links (Placeholders) -->
                <div class="bg-gray-50 dark:bg-gray-700/50 p-6 rounded-xl border border-gray-100 dark:border-gray-600">
                    <h3 class="font-bold text-gray-800 dark:text-white mb-2">Mes Appartements</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Gérez vos biens et locataires.</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700/50 p-6 rounded-xl border border-gray-100 dark:border-gray-600">
                    <h3 class="font-bold text-gray-800 dark:text-white mb-2">Charges & Paiements</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Suivez vos factures et paiements.</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700/50 p-6 rounded-xl border border-gray-100 dark:border-gray-600">
                    <h3 class="font-bold text-gray-800 dark:text-white mb-2">Incidents</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Signalez ou suivez des incidents.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
