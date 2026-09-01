@extends('layouts.app')

@section('content')
    @yield('content')
@endsection

@push('styles')
    @stack('styles')
@endpush

@push('scripts')
    @stack('scripts')
@endpush

