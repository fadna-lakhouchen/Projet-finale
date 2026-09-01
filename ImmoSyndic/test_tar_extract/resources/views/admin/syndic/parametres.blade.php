@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800 dark:text-white">{{ __('Paramètres') }}</h2>
    <p class="text-sm text-gray-600 dark:text-neutral-400">{{ __('Gérez votre profil professionnel et vérifiez vos notifications.') }}</p>
</div>

@if(session('success'))
<div class="bg-green-50 border border-green-200 text-sm text-green-800 rounded-lg p-4 mb-6 dark:bg-green-900/30 dark:border-green-800 dark:text-green-400">
    <div class="flex">
        <i data-lucide="check-circle" class="size-4 mt-0.5"></i>
        <div class="ms-3">
            <h3 class="font-semibold">{{ session('success') }}</h3>
        </div>
    </div>
</div>
@endif

@if(session('error'))
<div class="bg-red-50 border border-red-200 text-sm text-red-800 rounded-lg p-4 mb-6 dark:bg-red-900/30 dark:border-red-850 dark:text-red-400">
    <div class="flex">
        <i data-lucide="alert-circle" class="size-4 mt-0.5"></i>
        <div class="ms-3">
            <h3 class="font-semibold">{{ session('error') }}</h3>
        </div>
    </div>
</div>
@endif

@if($errors->any())
<div class="bg-red-50 border border-red-200 text-sm text-red-800 rounded-lg p-4 mb-6 dark:bg-red-900/30 dark:border-red-850 dark:text-red-400">
    <div class="flex">
        <i data-lucide="alert-circle" class="size-4 mt-0.5"></i>
        <div class="ms-3">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Left col -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Profil Info -->
        <div class="bg-white border border-gray-200 shadow-sm rounded-xl p-6 dark:bg-neutral-800 dark:border-neutral-700">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">{{ __('Profil Syndic') }}</h3>
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                <div class="flex items-center gap-4 mb-6">
                    <img class="inline-block size-16 rounded-full ring-2 ring-white"
                        src="https://ui-avatars.com/api/?name={{ urlencode($user->prenom . ' ' . $user->nom) }}&background=4f46e5&color=fff"
                        alt="Avatar">
                    <div>
                        <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800">
                            {{ __('Mettre à jour la photo') }}
                        </button>
                    </div>
                </div>

                <div class="grid sm:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="firstName" class="block text-sm font-medium mb-2 dark:text-white">{{ __('Prénom') }}</label>
                        <input type="text" id="firstName" name="prenom" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" value="{{ $user->prenom }}" required>
                    </div>
                    <div>
                        <label for="lastName" class="block text-sm font-medium mb-2 dark:text-white">{{ __('Nom') }}</label>
                        <input type="text" id="lastName" name="nom" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" value="{{ $user->nom }}" required>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium mb-2 dark:text-white">{{ __('Adresse email') }}</label>
                    <input type="email" id="email" name="email" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" value="{{ $user->email }}" required>
                </div>
                <div class="mb-4">
                    <label for="phone" class="block text-sm font-medium mb-2 dark:text-white">{{ __('Téléphone Contact Urgence') }}</label>
                    <input type="tel" id="phone" name="telephone" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" value="{{ $user->telephone }}">
                </div>
                <div class="mb-4">
                    <label for="apartment" class="block text-sm font-medium mb-2 dark:text-white">{{ __('Appartement (Résident & Syndic)') }}</label>
                    <input type="text" id="apartment" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm bg-gray-50 focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400" value="{{ $user->appartements->first() ? __('Appt') . ' ' . $user->appartements->first()->numero . ' - ' . $user->appartements->first()->immeuble->nom : __('Non assigné') }}" disabled>
                </div>
                <button type="submit" class="py-2.5 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-primary-600 text-white hover:bg-primary-700">
                    {{ __('Enregistrer modification') }}
                </button>
            </form>
        </div>

        <!-- Changer Mot de passe -->
        <div class="bg-white border border-gray-200 shadow-sm rounded-xl p-6 dark:bg-neutral-800 dark:border-neutral-700">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">{{ __('Sécurité') }}</h3>
            <form method="POST" action="{{ route('profile.password') }}">
                @csrf
                <div class="mb-4">
                    <label for="currentPwd" class="block text-sm font-medium mb-2 dark:text-white">{{ __('Mot de passe actuel') }}</label>
                    <input type="password" id="currentPwd" name="current_password" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" required>
                </div>
                <div class="grid sm:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="newPwd" class="block text-sm font-medium mb-2 dark:text-white">{{ __('Nouveau mot de passe') }}</label>
                        <input type="password" id="newPwd" name="password" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-[#080B11] dark:border-neutral-700 dark:text-neutral-400" required>
                    </div>
                    <div>
                        <label for="confirmPwd" class="block text-sm font-medium mb-2 dark:text-white">{{ __('Confirmer') }}</label>
                        <input type="password" id="confirmPwd" name="password_confirmation" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-[#080B11] dark:border-neutral-700 dark:text-neutral-400" required>
                    </div>
                </div>
                <button type="submit" class="py-2.5 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-gray-200 bg-white text-gray-800 hover:bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800">{{ __('Mettre à jour le mot de passe') }}</button>
            </form>
        </div>
    </div>

    <!-- Right col -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Abonnement & Facturation -->
        <div class="bg-white border border-gray-200 shadow-sm rounded-xl p-6 dark:bg-neutral-800 dark:border-neutral-700">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4 flex items-center gap-x-2">
                <i data-lucide="credit-card" class="size-5 text-primary-500"></i>
                {{ __('Abonnement ImmoSyn') }}
            </h3>
            
            <div class="mb-4 pb-4 border-b border-gray-150 dark:border-neutral-700">
                <div class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">{{ __('Montant Mensuel Actuel') }}</div>
                <div class="text-3xl font-extrabold text-slate-800 dark:text-white">
                    {{ number_format($subscription['total_price'], 2) }} <span class="text-sm font-semibold text-gray-500">{{ __('DH/mois') }}</span>
                </div>
            </div>

            <!-- Breakdown -->
            <div class="space-y-3 mb-5 max-h-48 overflow-y-auto pr-1">
                <div class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">{{ __('Détail par immeuble') }}</div>
                @forelse($subscription['breakdown'] as $item)
                    <div class="bg-slate-50 dark:bg-neutral-900 rounded-xl p-3 border border-gray-150 dark:border-neutral-800/40">
                        <div class="font-bold text-xs text-slate-800 dark:text-slate-200">{{ $item['immeuble']->nom }}</div>
                        <div class="flex justify-between text-xs text-slate-500 dark:text-neutral-400 mt-1.5">
                            <span>{{ $item['calculation']['residents_count'] }} {{ __('Résidents') }} × 4 {{ __('DH') }}</span>
                            <span class="font-semibold text-slate-700 dark:text-slate-300">{{ number_format($item['calculation']['residents_price'], 2) }} {{ __('DH') }}</span>
                        </div>
                        <div class="flex justify-between text-xs text-slate-500 dark:text-neutral-400 mt-1">
                            <span>{{ $item['calculation']['syndics_count'] }} {{ __('Syndics') }} × 8 {{ __('DH') }}</span>
                            <span class="font-semibold text-slate-700 dark:text-slate-300">{{ number_format($item['calculation']['syndics_price'], 2) }} {{ __('DH') }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 italic">{{ __('Aucun immeuble géré comme principal.') }}</p>
                @endforelse
            </div>

            <!-- Virement Instructions -->
            <div class="bg-primary-500/[0.03] border border-primary-500/20 rounded-xl p-4">
                <h4 class="text-[10px] font-bold text-primary-600 dark:text-primary-400 uppercase tracking-wider mb-2">{{ __('Instructions de Virement') }}</h4>
                <div class="space-y-1.5 text-xs text-slate-600 dark:text-neutral-400">
                    <div><span class="font-bold text-slate-700 dark:text-slate-300">{{ __('Titulaire du compte :') }}</span> FADNA LAKHOUCHEN</div>
                    <div><span class="font-bold text-slate-700 dark:text-slate-300">{{ __('Banque :') }}</span> {{ __('CIH Bank') }}</div>
                    <div class="bg-white dark:bg-neutral-900 p-2 rounded-lg border border-gray-200/50 dark:border-neutral-700 font-mono text-[11px] select-all cursor-pointer text-center font-bold text-slate-800 dark:text-slate-200" title="{{ __('Cliquer pour copier') }}">
                        230 010 5601671211000200 57
                    </div>
                    <p class="text-[10px] text-slate-400 leading-normal mt-2">
                        * {{ __('Veuillez effectuer le virement avant le 5 de chaque mois et transmettre le justificatif à l\'administrateur pour éviter la suspension du service.') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Notifications Toggle -->
        <div class="bg-white border border-gray-200 shadow-sm rounded-xl p-6 dark:bg-neutral-800 dark:border-neutral-700">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">{{ __('Notifications') }}</h3>

            <ul class="flex flex-col gap-y-4">
                <li class="flex items-center justify-between">
                    <div>
                        <span class="block text-sm font-medium text-gray-800 dark:text-white">{{ __('Paiements (Résidents)') }}</span>
                        <span class="block text-xs text-gray-500 dark:text-neutral-400">{{ __('Email à chaque versement.') }}</span>
                    </div>
                    <input type="checkbox" id="hs-toggle-payment" class="relative w-[3.25rem] h-7 bg-gray-100 checked:bg-none checked:bg-primary-600 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 ring-1 ring-transparent focus:border-primary-600 focus:ring-primary-600 ring-offset-white focus:outline-none appearance-none dark:bg-neutral-700 dark:checked:bg-primary-600 dark:focus:ring-offset-gray-800 before:inline-block before:size-6 before:bg-white checked:before:bg-blue-200 before:translate-x-0 checked:before:translate-x-full rtl:checked:before:-translate-x-full before:rounded-full before:shadow before:transform before:ring-0 before:transition before:ease-in-out before:duration-200 dark:before:bg-neutral-400 dark:checked:before:bg-white">
                </li>
                <hr class="border-gray-200 dark:border-neutral-700">
                <li class="flex items-center justify-between">
                    <div>
                        <span class="block text-sm font-medium text-gray-800 dark:text-white">{{ __('Problèmes & Signalements') }}</span>
                        <span class="block text-xs text-gray-500 dark:text-neutral-400">{{ __('Alerte immédiate.') }}</span>
                    </div>
                    <input type="checkbox" id="hs-toggle-incident" class="relative w-[3.25rem] h-7 bg-gray-100 checked:bg-none checked:bg-primary-600 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 ring-1 ring-transparent focus:border-primary-600 focus:ring-primary-600 ring-offset-white focus:outline-none appearance-none dark:bg-neutral-700 dark:checked:bg-primary-600 dark:focus:ring-offset-gray-800 before:inline-block before:size-6 before:bg-white checked:before:bg-blue-200 before:translate-x-0 checked:before:translate-x-full rtl:checked:before:-translate-x-full before:rounded-full before:shadow before:transform before:ring-0 before:transition before:ease-in-out before:duration-200 dark:before:bg-neutral-400 dark:checked:before:bg-white" checked>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection
