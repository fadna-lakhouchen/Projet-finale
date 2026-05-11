@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Paramètres</h2>
    <p class="text-sm text-gray-600 dark:text-neutral-400">Gérez votre profil professionnel et vérifiez vos notifications.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Left col -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Profil Info -->
        <div class="bg-white border border-gray-200 shadow-sm rounded-xl p-6 dark:bg-neutral-800 dark:border-neutral-700">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Profil Syndic</h3>
            <form onsubmit="event.preventDefault();">
                <div class="flex items-center gap-4 mb-6">
                    <img class="inline-block size-16 rounded-full ring-2 ring-white"
                        src="https://ui-avatars.com/api/?name={{ urlencode($user->prenom . ' ' . $user->nom) }}&background=4f46e5&color=fff"
                        alt="Avatar">
                    <div>
                        <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800">
                            Mettre à jour la photo
                        </button>
                    </div>
                </div>

                <div class="grid sm:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="firstName" class="block text-sm font-medium mb-2 dark:text-white">Prénom</label>
                        <input type="text" id="firstName" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" value="{{ $user->prenom }}">
                    </div>
                    <div>
                        <label for="lastName" class="block text-sm font-medium mb-2 dark:text-white">Nom</label>
                        <input type="text" id="lastName" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" value="{{ $user->nom }}">
                    </div>
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium mb-2 dark:text-white">Adresse Email</label>
                    <input type="email" id="email" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" value="{{ $user->email }}">
                </div>
                <div class="mb-4">
                    <label for="phone" class="block text-sm font-medium mb-2 dark:text-white">Téléphone Contact Urgence</label>
                    <input type="tel" id="phone" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" value="{{ $user->telephone }}">
                </div>
                <div class="mb-4">
                    <label for="apartment" class="block text-sm font-medium mb-2 dark:text-white">Appartement (Résident & Syndic)</label>
                    <input type="text" id="apartment" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm bg-gray-50 focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400" value="{{ $user->appartements->first() ? 'Apt ' . $user->appartements->first()->numero . ' - ' . $user->appartements->first()->immeuble->nom : 'N/A' }}" disabled>
                </div>
                <button type="submit" class="py-2.5 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-primary-600 text-white hover:bg-primary-700">
                    Enregistrer modification
                </button>
            </form>
        </div>

        <!-- Changer Mot de passe -->
        <div class="bg-white border border-gray-200 shadow-sm rounded-xl p-6 dark:bg-neutral-800 dark:border-neutral-700">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Sécurité</h3>
            <form onsubmit="event.preventDefault();">
                <div class="mb-4">
                    <label for="currentPwd" class="block text-sm font-medium mb-2 dark:text-white">Mot de passe actuel</label>
                    <input type="password" id="currentPwd" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
                </div>
                <div class="grid sm:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="newPwd" class="block text-sm font-medium mb-2 dark:text-white">Nouveau mot de passe</label>
                        <input type="password" id="newPwd" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
                    </div>
                    <div>
                        <label for="confirmPwd" class="block text-sm font-medium mb-2 dark:text-white">Confirmer</label>
                        <input type="password" id="confirmPwd" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
                    </div>
                </div>
                <button type="submit" class="py-2.5 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-gray-200 bg-white text-gray-800 hover:bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800">
                    Mettre à jour le mot de passe
                </button>
            </form>
        </div>
    </div>

    <!-- Right col -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Notifications Toggle -->
        <div class="bg-white border border-gray-200 shadow-sm rounded-xl p-6 dark:bg-neutral-800 dark:border-neutral-700">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Notifications</h3>

            <ul class="flex flex-col gap-y-4">
                <li class="flex items-center justify-between">
                    <div>
                        <span class="block text-sm font-medium text-gray-800 dark:text-white">Paiements (Résidents)</span>
                        <span class="block text-xs text-gray-500 dark:text-neutral-400">Email à chaque versement.</span>
                    </div>
                    <input type="checkbox" id="hs-toggle-payment" class="relative w-[3.25rem] h-7 bg-gray-100 checked:bg-none checked:bg-primary-600 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 ring-1 ring-transparent focus:border-primary-600 focus:ring-primary-600 ring-offset-white focus:outline-none appearance-none dark:bg-neutral-700 dark:checked:bg-primary-600 dark:focus:ring-offset-gray-800 before:inline-block before:size-6 before:bg-white checked:before:bg-blue-200 before:translate-x-0 checked:before:translate-x-full before:rounded-full before:shadow before:transform before:ring-0 before:transition before:ease-in-out before:duration-200 dark:before:bg-neutral-400 dark:checked:before:bg-white">
                </li>
                <hr class="border-gray-200 dark:border-neutral-700">
                <li class="flex items-center justify-between">
                    <div>
                        <span class="block text-sm font-medium text-gray-800 dark:text-white">Problèmes & Signalements</span>
                        <span class="block text-xs text-gray-500 dark:text-neutral-400">Alerte immédiate.</span>
                    </div>
                    <input type="checkbox" id="hs-toggle-incident" class="relative w-[3.25rem] h-7 bg-gray-100 checked:bg-none checked:bg-primary-600 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 ring-1 ring-transparent focus:border-primary-600 focus:ring-primary-600 ring-offset-white focus:outline-none appearance-none dark:bg-neutral-700 dark:checked:bg-primary-600 dark:focus:ring-offset-gray-800 before:inline-block before:size-6 before:bg-white checked:before:bg-blue-200 before:translate-x-0 checked:before:translate-x-full before:rounded-full before:shadow before:transform before:ring-0 before:transition before:ease-in-out before:duration-200 dark:before:bg-neutral-400 dark:checked:before:bg-white" checked>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection
