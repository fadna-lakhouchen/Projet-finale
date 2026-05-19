@extends('layouts.app')

@section('content')
<div class="mb-8">
    <h2 class="text-2xl font-bold text-slate-800 dark:text-white">Paramètres Système</h2>
    <p class="text-sm text-slate-500 dark:text-neutral-400">Gérez votre profil administrateur, la sécurité et les préférences de notification de la plateforme.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Left col : Profil et sécurité -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Profil Info (Premium Glass Panel) -->
        <div class="bg-white dark:bg-[#0D121F] border border-gray-200/60 dark:border-slate-800/60 shadow-premium rounded-2xl p-6 transition-all duration-300">
            <div class="flex items-center gap-x-2.5 mb-6">
                <div class="size-9 rounded-xl bg-primary-500/10 border border-primary-500/20 flex items-center justify-center text-primary-600 dark:text-primary-400">
                    <i data-lucide="user" class="size-4.5"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800 dark:text-white">Informations du Profil</h3>
            </div>
            
            <form onsubmit="event.preventDefault();">
                <div class="flex items-center gap-4 mb-6">
                    <img class="inline-block size-16 rounded-2xl ring-2 ring-primary-500/20 dark:ring-primary-500/30" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->prenom . ' ' . auth()->user()->nom) }}&background=4f46e5&color=fff" alt="Avatar">
                    <div>
                        <button type="button" class="py-2.5 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-xl border border-gray-200 bg-white/80 hover:bg-white text-slate-800 shadow-sm dark:bg-neutral-800 dark:border-neutral-700 dark:text-white transition-all">
                            Changer photo
                        </button>
                    </div>
                </div>

                <div class="grid sm:grid-cols-2 gap-5 mb-5">
                    <div>
                        <label for="firstName" class="block text-sm font-semibold mb-2 text-slate-700 dark:text-slate-200">Prénom</label>
                        <input type="text" id="firstName" class="py-2.5 px-4 block w-full border-gray-200 dark:border-slate-850 dark:bg-[#080B11] dark:text-slate-300 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500" value="{{ auth()->user()->prenom }}">
                    </div>
                    <div>
                        <label for="lastName" class="block text-sm font-semibold mb-2 text-slate-700 dark:text-slate-200">Nom</label>
                        <input type="text" id="lastName" class="py-2.5 px-4 block w-full border-gray-200 dark:border-slate-850 dark:bg-[#080B11] dark:text-slate-300 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500" value="{{ auth()->user()->nom }}">
                    </div>
                </div>
                
                <div class="mb-6">
                    <label for="emailAddr" class="block text-sm font-semibold mb-2 text-slate-700 dark:text-slate-200">Adresse Email</label>
                    <input type="email" id="emailAddr" class="py-2.5 px-4 block w-full border-gray-200 dark:border-slate-850 dark:bg-[#080B11] dark:text-slate-300 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500" value="{{ auth()->user()->email }}">
                </div>

                <button type="submit" class="py-2.5 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-bold rounded-xl border border-transparent bg-gradient-to-r from-primary-600 to-purple-600 text-white hover:from-primary-700 hover:to-purple-700 shadow-md shadow-primary-500/15 transition-all glow-hover">
                    Enregistrer les modifications
                </button>
            </form>
        </div>

        <!-- Changer Mot de passe (Premium Glass Panel) -->
        <div class="bg-white dark:bg-[#0D121F] border border-gray-200/60 dark:border-slate-800/60 shadow-premium rounded-2xl p-6 transition-all duration-300">
            <div class="flex items-center gap-x-2.5 mb-6">
                <div class="size-9 rounded-xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-purple-600 dark:text-purple-400">
                    <i data-lucide="shield-check" class="size-4.5"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800 dark:text-white">Sécurité du Compte</h3>
            </div>

            <form onsubmit="event.preventDefault();">
                <div class="mb-5">
                    <label for="currentPwd" class="block text-sm font-semibold mb-2 text-slate-700 dark:text-slate-200">Mot de passe actuel</label>
                    <input type="password" id="currentPwd" class="py-2.5 px-4 block w-full border-gray-200 dark:border-slate-850 dark:bg-[#080B11] dark:text-slate-300 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500" placeholder="••••••••">
                </div>
                <div class="grid sm:grid-cols-2 gap-5 mb-6">
                    <div>
                        <label for="newPwd" class="block text-sm font-semibold mb-2 text-slate-700 dark:text-slate-200">Nouveau mot de passe</label>
                        <input type="password" id="newPwd" class="py-2.5 px-4 block w-full border-gray-200 dark:border-slate-850 dark:bg-[#080B11] dark:text-slate-300 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500" placeholder="••••••••">
                    </div>
                    <div>
                        <label for="confirmPwd" class="block text-sm font-semibold mb-2 text-slate-700 dark:text-slate-200">Confirmer le nouveau mot de passe</label>
                        <input type="password" id="confirmPwd" class="py-2.5 px-4 block w-full border-gray-200 dark:border-slate-850 dark:bg-[#080B11] dark:text-slate-300 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500" placeholder="••••••••">
                    </div>
                </div>
                <button type="submit" class="py-2.5 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-bold rounded-xl border border-transparent bg-slate-800 dark:bg-white text-white dark:text-slate-900 hover:bg-slate-900 dark:hover:bg-slate-100 shadow-md transition-all">
                    Mettre à jour le mot de passe
                </button>
            </form>
        </div>
    </div>

    <!-- Right col : Préférences -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Notifications (Premium Glass Panel) -->
        <div class="bg-white dark:bg-[#0D121F] border border-gray-200/60 dark:border-slate-800/60 shadow-premium rounded-2xl p-6 transition-all duration-300">
            <div class="flex items-center gap-x-2.5 mb-6">
                <div class="size-9 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-600 dark:text-amber-400">
                    <i data-lucide="bell" class="size-4.5"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800 dark:text-white">Préférences de Notification</h3>
            </div>

            <ul class="flex flex-col gap-y-4">
                <li class="flex items-center justify-between gap-x-4">
                    <div>
                        <span class="block text-sm font-semibold text-slate-800 dark:text-white">Nouveaux Paiements</span>
                        <span class="block text-xs text-slate-400 dark:text-neutral-450 mt-0.5">Lorsqu'un locataire paie ses charges mensuelles.</span>
                    </div>
                    <input type="checkbox" class="relative w-[3.25rem] h-7 bg-slate-100 checked:bg-none checked:bg-primary-600 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 ring-1 ring-transparent focus:border-primary-600 focus:ring-primary-600 ring-offset-white focus:outline-none appearance-none dark:bg-neutral-800 dark:checked:bg-primary-600 dark:focus:ring-offset-gray-800 before:inline-block before:size-6 before:bg-white checked:before:bg-blue-200 before:translate-x-0 checked:before:translate-x-full before:rounded-full before:shadow before:transform before:ring-0 before:transition before:ease-in-out before:duration-200 dark:before:bg-neutral-500 dark:checked:before:bg-white" checked>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection

