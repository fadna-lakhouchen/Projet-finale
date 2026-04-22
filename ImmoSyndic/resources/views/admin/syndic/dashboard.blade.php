@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">Espace Syndic</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Gestion de vos copropriétés et résidences.</p>
        </div>
    </div>
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white border shadow-sm rounded-xl p-5 dark:bg-slate-900 dark:border-gray-700">
            <p class="text-xs uppercase tracking-wide text-gray-500">Immeubles</p>
            <h3 class="text-xl font-medium text-gray-800 dark:text-gray-200">4</h3>
        </div>
    </div>
</div>
@endsection
