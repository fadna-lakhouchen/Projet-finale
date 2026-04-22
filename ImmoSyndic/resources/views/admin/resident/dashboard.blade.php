@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <div class="bg-gradient-to-r from-brand-800 to-teal-500 rounded-3xl p-8 mb-8 text-white shadow-xl">
        <h1 class="text-3xl font-extrabold mb-2">Bonjour, {{ Auth::user()->prenom }} !</h1>
        <p class="text-teal-50 text-lg opacity-90">Bienvenue dans votre espace résident.</p>
    </div>
</div>
@endsection
