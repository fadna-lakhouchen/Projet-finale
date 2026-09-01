@extends('layouts.auth')

@section('content')
<div class="w-full max-w-md mx-auto">
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-slate-900 dark:border-gray-700">
        <div class="p-4 sm:p-7">
            <div class="text-center">
                <h1 class="block text-2xl font-bold text-gray-800 dark:text-white">{{ __('Vérifiez votre adresse email') }}</h1>
            </div>

            <div class="mt-5">
                @if (session('resent'))
                    <div class="mb-4 bg-green-100 border border-green-200 text-sm text-green-800 rounded-lg p-4 dark:bg-green-800/10 dark:border-green-900 dark:text-green-500" role="alert">
                        {{ __('Un nouveau lien de vérification a été envoyé à votre adresse email.') }}
                    </div>
                @endif

                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Avant de continuer, veuillez vérifier votre email pour un lien de vérification.') }}
                    {{ __("Si vous n'avez pas reçu l'email,") }}
                </p>

                <form class="inline-block mt-3" method="POST" action="{{ route('verification.resend') }}">
                    @csrf
                    <button type="submit" class="text-brand-600 decoration-2 hover:underline font-medium dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">{{ __('cliquez ici pour en demander un autre') }}</button>.
                </form>

                <form class="mt-6 border-t border-gray-100 pt-4 dark:border-gray-800" method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-center text-xs text-gray-500 hover:text-red-600 font-semibold transition-colors">
                        {{ __('Se déconnecter / Retourner à l\'accueil') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
