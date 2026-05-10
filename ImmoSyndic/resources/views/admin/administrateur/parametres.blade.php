@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Paramètres</h2>
    <p class="text-sm text-gray-600 dark:text-neutral-400">Gérez votre profil administrateur et les préférences de la plateforme.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Left col : Profil et sécurité -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Profil Info -->
        <div class="bg-white border border-gray-200 shadow-sm rounded-xl p-6 dark:bg-neutral-800 dark:border-neutral-700">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Informations du Profil</h3>
            <form onsubmit="event.preventDefault();">
                <div class="flex items-center gap-4 mb-6">
                    <img class="inline-block size-16 rounded-full ring-2 ring-white" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->prenom . ' ' . auth()->user()->nom) }}&background=4f46e5&color=fff" alt="Avatar">
                    <div>
                        <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800">
                            Changer photo
                        </button>
                    </div>
                </div>

                <div class="grid sm:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="firstName" class="block text-sm font-medium mb-2 dark:text-white">Prénom</label>
                        <input type="text" id="firstName" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" value="{{ auth()->user()->prenom }}">
                    </div>
                    <div>
                        <label for="lastName" class="block text-sm font-medium mb-2 dark:text-white">Nom</label>
                        <input type="text" id="lastName" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" value="{{ auth()->user()->nom }}">
                    </div>
                </div>
                <div class="mb-4">
                    <label for="emailAddr" class="block text-sm font-medium mb-2 dark:text-white">Adresse Email</label>
                    <input type="email" id="emailAddr" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" value="{{ auth()->user()->email }}">
                </div>
                <button type="submit" class="py-2.5 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-primary-600 text-white hover:bg-primary-700">
                    Enregistrer les modifications
                </button>
            </form>
        </div>

        <!-- Changer Mot de passe -->
        <div class="bg-white border border-gray-200 shadow-sm rounded-xl p-6 dark:bg-neutral-800 dark:border-neutral-700">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Sécurité</h3>
            <form onsubmit="event.preventDefault();">
                <div class="mb-4">
                    <label for="currentPwd" class="block text-sm font-medium mb-2 dark:text-white">Mot de passe actuel</label>
                    <input type="password" id="currentPwd" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" placeholder="••••••••">
                </div>
                <div class="grid sm:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="newPwd" class="block text-sm font-medium mb-2 dark:text-white">Nouveau mot de passe</label>
                        <input type="password" id="newPwd" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" placeholder="••••••••">
                    </div>
                    <div>
                        <label for="confirmPwd" class="block text-sm font-medium mb-2 dark:text-white">Confirmer le nouveau mot de passe</label>
                        <input type="password" id="confirmPwd" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" placeholder="••••••••">
                    </div>
                </div>
                <button type="submit" class="py-2.5 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-gray-800 text-white hover:bg-gray-900 dark:bg-white dark:text-neutral-800 dark:hover:bg-neutral-200">
                    Mettre à jour le mot de passe
                </button>
            </form>
        </div>
    </div>

    <!-- Right col : Préférences -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Notifications -->
        <div class="bg-white border border-gray-200 shadow-sm rounded-xl p-6 dark:bg-neutral-800 dark:border-neutral-700">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Notifications par Email</h3>
            <ul class="flex flex-col gap-y-4">
                <li class="flex items-center justify-between">
                    <div>
                        <span class="block text-sm font-medium text-gray-800 dark:text-white">Nouveaux Paiements</span>
                        <span class="block text-xs text-gray-500 dark:text-neutral-400">Lorsqu'un locataire paie ses charges.</span>
                    </div>
                    <input type="checkbox" class="relative w-[3.25rem] h-7 bg-gray-100 checked:bg-none checked:bg-primary-600 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 ring-1 ring-transparent focus:border-primary-600 focus:ring-primary-600 ring-offset-white focus:outline-none appearance-none dark:bg-neutral-700 dark:checked:bg-primary-600 dark:focus:ring-offset-gray-800 before:inline-block before:size-6 before:bg-white checked:before:bg-blue-200 before:translate-x-0 checked:before:translate-x-full before:rounded-full before:shadow before:transform before:ring-0 before:transition before:ease-in-out before:duration-200 dark:before:bg-neutral-400 dark:checked:before:bg-white" checked>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection
