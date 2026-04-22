@extends('layouts.auth')

@section('content')
<div class="w-full max-w-md mx-auto">
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-slate-900 dark:border-gray-700">
        <div class="p-4 sm:p-7">
            <div class="text-center">
                <h1 class="block text-2xl font-bold text-gray-800 dark:text-white">Mot de passe oublié ?</h1>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    Vous vous en souvenez ?
                    <a class="text-brand-600 decoration-2 hover:underline font-medium dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="{{ route('login') }}">
                        Se connecter ici
                    </a>
                </p>
            </div>

            <div class="mt-5">
                @if (session('status'))
                    <div class="mb-4 bg-green-100 border border-green-200 text-sm text-green-800 rounded-lg p-4 dark:bg-green-800/10 dark:border-green-900 dark:text-green-500" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Form -->
                <form method="POST" action="{{ route('password.email') }}">
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

                        <button type="submit" class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-brand-600 text-white hover:bg-brand-700 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">Réinitialiser le mot de passe</button>
                    </div>
                </form>
                <!-- End Form -->
            </div>
        </div>
    </div>
</div>
@endsection
