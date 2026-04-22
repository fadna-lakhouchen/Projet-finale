@extends('layouts.admin')

@section('title', 'Paramètres - ImmoSyndic Admin')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Paramètres</h2>
        <p class="text-sm text-gray-600 dark:text-neutral-400">Gérez vos préférences de compte et les configurations du système.</p>
    </div>

    <div class="grid lg:grid-cols-3 gap-8">
        <!-- Navigation Tabs (Simple Vertical for settings) -->
        <div class="space-y-2">
            <button class="w-full flex items-center gap-x-3.5 py-2 px-3 text-sm text-primary-700 bg-primary-100 rounded-lg dark:bg-primary-900/40 dark:text-primary-400">
                <i data-lucide="user" class="size-4"></i> Profil
            </button>
            <button class="w-full flex items-center gap-x-3.5 py-2 px-3 text-sm text-gray-700 hover:bg-gray-100 rounded-lg dark:text-neutral-400 dark:hover:bg-neutral-700 transition-colors text-left">
                <i data-lucide="bell" class="size-4"></i> Notifications
            </button>
            <button class="w-full flex items-center gap-x-3.5 py-2 px-3 text-sm text-gray-700 hover:bg-gray-100 rounded-lg dark:text-neutral-400 dark:hover:bg-neutral-700 transition-colors text-left">
                <i data-lucide="shield" class="size-4"></i> Sécurité
            </button>
        </div>

        <!-- Settings Content -->
        <div class="lg:col-span-2">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 dark:bg-neutral-800 dark:border-neutral-700">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-6">Informations du Profil</h3>
                
                <form class="space-y-6">
                    <div class="flex items-center gap-x-6 mb-8">
                        <img class="size-20 rounded-full ring-4 ring-gray-100 dark:ring-neutral-700" src="https://ui-avatars.com/api/?name=Mohamed+Rifi&background=3b66f5&color=fff" alt="Avatar">
                        <x-admin.button variant="secondary" size="sm">Changer la photo</x-admin.button>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2 dark:text-white">Prénom</label>
                            <input type="text" value="Mohamed" class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2 dark:text-white">Nom</label>
                            <input type="text" value="Rifi" class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2 dark:text-white">Email</label>
                        <input type="email" value="mohamed.rifi@email.com" class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
                    </div>

                    <div class="flex justify-end gap-2 pt-4">
                        <x-admin.button variant="secondary">Annuler</x-admin.button>
                        <x-admin.button>Sauvegarder</x-admin.button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
