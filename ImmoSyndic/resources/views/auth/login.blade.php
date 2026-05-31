@extends('layouts.auth')

@section('content')
<main class="w-full max-w-md mx-auto p-6">
    <div class="mt-7 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
        <div class="p-4 sm:p-7">
            <div class="text-center">
                <div class="flex justify-center mb-5">
                    <img src="{{ asset('logo.png') }}" alt="Logo ImmoSyndic" class="h-20 w-auto object-contain">
                </div>
                <h1 class="block text-2xl font-bold text-gray-800 dark:text-white">Portail ImmoSyndic</h1>
                <p class="mt-2 text-sm text-gray-600 dark:text-neutral-400">
                    Vous n'avez pas de compte ?
                    <a class="text-primary-600 decoration-2 hover:underline font-medium dark:text-primary-400" href="{{ route('register') }}">
                        Créer un compte
                    </a>
                </p>
            </div>

            <div class="mt-5">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="grid gap-y-4">
                        
                        <div>
                            <label for="email" class="block text-sm mb-2 dark:text-white">Adresse email</label>
                            <div class="relative">
                                <input type="email" id="email" name="email" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600 @error('email') border-red-500 @enderror" required autocomplete="email" autofocus value="{{ old('email') }}">
                                @error('email')
                                <div class="absolute inset-y-0 end-0 flex items-center pointer-events-none pe-3">
                                    <i data-lucide="alert-circle" class="size-5 text-red-500"></i>
                                </div>
                                @enderror
                            </div>
                            @error('email')
                                <p class="text-xs text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <div class="flex justify-between items-center">
                                <label for="password" class="block text-sm mb-2 dark:text-white">Mot de passe</label>
                                @if (Route::has('password.request'))
                                    <a class="text-sm text-primary-600 decoration-2 hover:underline font-medium dark:text-primary-400" href="{{ route('password.request') }}">Mot de passe oublié ?</a>
                                @endif
                            </div>
                            <div class="relative">
                                <input type="password" id="password" name="password" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600 @error('password') border-red-500 @enderror" required autocomplete="current-password">
                                @error('password')
                                <div class="absolute inset-y-0 end-0 flex items-center pointer-events-none pe-3">
                                    <i data-lucide="alert-circle" class="size-5 text-red-500"></i>
                                </div>
                                @enderror
                            </div>
                            @error('password')
                                <p class="text-xs text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center">
                            <div class="flex">
                                <input id="remember" name="remember" type="checkbox" class="shrink-0 mt-0.5 border-gray-200 rounded text-primary-600 focus:ring-primary-500 dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-primary-500 dark:checked:border-primary-500 dark:focus:ring-offset-gray-800" {{ old('remember') ? 'checked' : '' }}>
                            </div>
                            <div class="ms-3">
                                <label for="remember" class="text-sm dark:text-white">Se souvenir de moi</label>
                            </div>
                        </div>

                        <button type="submit" class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-primary-600 text-white hover:bg-primary-700 disabled:opacity-50 disabled:pointer-events-none">
                            Se connecter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection
