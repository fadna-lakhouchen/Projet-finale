@extends('layouts.auth')

@section('content')
<div class="w-full max-w-md mx-auto">
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-slate-900 dark:border-gray-700">
        <div class="p-4 sm:p-7">
            <div class="text-center">
                <h1 class="block text-2xl font-bold text-gray-800 dark:text-white">Créer un compte</h1>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    Vous avez déjà un compte ?
                    <a class="text-brand-600 decoration-2 hover:underline font-medium dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="{{ route('login') }}">
                        Se connecter ici
                    </a>
                </p>
            </div>

            <div class="mt-5">
                <!-- Form -->
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="grid gap-y-4">
                        <!-- Form Group -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="prenom" class="block text-sm mb-2 dark:text-white">Prénom</label>
                                <input type="text" id="prenom" name="prenom" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-brand-500 focus:ring-brand-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600 @error('prenom') border-red-500 @enderror" required value="{{ old('prenom') }}" autofocus>
                                @error('prenom')
                                <p class="text-xs text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="nom" class="block text-sm mb-2 dark:text-white">Nom</label>
                                <input type="text" id="nom" name="nom" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-brand-500 focus:ring-brand-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600 @error('nom') border-red-500 @enderror" required value="{{ old('nom') }}">
                                @error('nom')
                                <p class="text-xs text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="email" class="block text-sm mb-2 dark:text-white">Adresse Email</label>
                            <input type="email" id="email" name="email" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-brand-500 focus:ring-brand-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600 @error('email') border-red-500 @enderror" required value="{{ old('email') }}">
                            @error('email')
                            <p class="text-xs text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm mb-2 dark:text-white">Mot de passe</label>
                            <input type="password" id="password" name="password" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-brand-500 focus:ring-brand-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600 @error('password') border-red-500 @enderror" required>
                            @error('password')
                            <p class="text-xs text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password-confirm" class="block text-sm mb-2 dark:text-white">Confirmer le mot de passe</label>
                            <input type="password" id="password-confirm" name="password_confirmation" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-brand-500 focus:ring-brand-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" required>
                        </div>

                        <button type="submit" class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-brand-600 text-white hover:bg-brand-700 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">S'inscrire</button>
                    </div>
                </form>
                <!-- End Form -->
            </div>
        </div>
    </div>
</div>
@endsection
