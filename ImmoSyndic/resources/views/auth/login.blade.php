@extends('layouts.auth')

@section('content')
<div class="w-full max-w-md mx-auto">
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-slate-900 dark:border-gray-700">
        <div class="p-4 sm:p-7">
            <div class="text-center">
                <h1 class="block text-2xl font-bold text-gray-800 dark:text-white">Se connecter</h1>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    Vous n'avez pas encore de compte ?
                    <a class="text-brand-600 decoration-2 hover:underline font-medium dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="{{ route('register') }}">
                        S'inscrire ici
                    </a>
                </p>
            </div>

            <div class="mt-5">
                <!-- Form -->
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="grid gap-y-4">
                        <!-- Form Group -->
                        <div>
                            <label for="email" class="block text-sm mb-2 dark:text-white">Adresse Email</label>
                            <div class="relative">
                                <input type="email" id="email" name="email" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-brand-500 focus:ring-brand-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600 @error('email') border-red-500 @enderror" required value="{{ old('email') }}" autofocus>
                                @error('email')
                                <div class="absolute inset-y-0 end-0 flex items-center pointer-events-none pe-3">
                                    <svg class="h-5 w-5 text-red-500" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
                                    </svg>
                                </div>
                                @enderror
                            </div>
                            @error('email')
                            <p class="text-xs text-red-600 mt-2" id="email-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <!-- End Form Group -->

                        <!-- Form Group -->
                        <div>
                            <div class="flex justify-between items-center">
                                <label for="password" class="block text-sm mb-2 dark:text-white">Mot de passe</label>
                                @if (Route::has('password.request'))
                                <a class="text-sm text-brand-600 decoration-2 hover:underline font-medium dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="{{ route('password.request') }}">Mot de passe oublié ?</a>
                                @endif
                            </div>
                            <div class="relative">
                                <input type="password" id="password" name="password" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-brand-500 focus:ring-brand-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600 @error('password') border-red-500 @enderror" required>
                                @error('password')
                                <div class="absolute inset-y-0 end-0 flex items-center pointer-events-none pe-3">
                                    <svg class="h-5 w-5 text-red-500" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
                                    </svg>
                                </div>
                                @enderror
                            </div>
                            @error('password')
                            <p class="text-xs text-red-600 mt-2" id="password-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <!-- End Form Group -->

                        <!-- Checkbox -->
                        <div class="flex items-center">
                            <div class="flex">
                                <input id="remember" name="remember" type="checkbox" class="shrink-0 mt-0.5 border-gray-200 rounded text-brand-600 focus:ring-brand-500 dark:bg-slate-900 dark:border-gray-700 dark:checked:bg-brand-500 dark:checked:border-brand-500 dark:focus:ring-offset-gray-800" {{ old('remember') ? 'checked' : '' }}>
                            </div>
                            <div class="ms-3">
                                <label for="remember" class="text-sm dark:text-white">Se souvenir de moi</label>
                            </div>
                        </div>
                        <!-- End Checkbox -->

                        <button type="submit" class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-brand-600 text-white hover:bg-brand-700 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">Se connecter</button>
                    </div>
                </form>
                <!-- End Form -->
            </div>
        </div>
    </div>
</div>
@endsection
