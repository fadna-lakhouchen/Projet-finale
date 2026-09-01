@extends('layouts.public')

@section('title', 'ImmoSyn - La gestion de copropriété réinventée')

@section('content')
    {{-- Hero Section --}}
    @include('public.components.hero')

    {{-- Features Bento Grid --}}
    @include('public.components.features')

    {{-- Solutions & Roles --}}
    @include('public.components.solutions')

    {{-- About ImmoSyn --}}
    @include('public.components.about')

    {{-- Call to Action --}}
    @include('public.components.cta')
@endsection
