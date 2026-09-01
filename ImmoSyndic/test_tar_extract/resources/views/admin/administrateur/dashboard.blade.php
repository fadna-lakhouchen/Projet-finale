@extends('layouts.app')

@section('content')
@php
    $maxCount = max(array_column($activityByDay, 'count') ?: [1]);
    $actionColors = [
        'created' => ['bg' => 'bg-emerald-500/15', 'text' => 'text-emerald-400', 'dot' => 'bg-emerald-400'],
        'updated' => ['bg' => 'bg-blue-500/15',    'text' => 'text-blue-400',    'dot' => 'bg-blue-400'],
        'deleted' => ['bg' => 'bg-rose-500/15',    'text' => 'text-rose-400',    'dot' => 'bg-rose-400'],
        'login'   => ['bg' => 'bg-purple-500/15',  'text' => 'text-purple-400',  'dot' => 'bg-purple-400'],
    ];
@endphp
<div class="space-y-8" x-data="adminDashboard">

    {{-- Alert Banner --}}
    @if($stats['paiements_retard'] > 0)
    <div class="bg-amber-500/10 border border-amber-500/20 text-sm text-amber-600 dark:text-amber-400 rounded-2xl p-4 shadow-premium backdrop-blur-md flex items-start gap-x-3" role="alert">
        <i data-lucide="alert-circle" class="size-5 shrink-0 mt-0.5 text-amber-500"></i>
        <div>
            <span class="font-bold">{{ __('Attention :') }}</span> {{ $stats['paiements_retard'] }} {{ __('paiements de cotisations sont actuellement en retard ce mois-ci.') }}
        </div>
    </div>
    @endif

    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-850 dark:text-white">{{ __('Supervision Dashboard') }}</h2>
            <p class="text-sm text-slate-550 dark:text-neutral-400">{{ __("Suivi global de l'activité de copropriété, de la performance financière et des alertes.") }}</p>
        </div>
        <div class="flex items-center gap-x-3">
            {{-- Auto-refresh countdown --}}
            <div class="flex items-center gap-x-2 py-2 px-3 rounded-xl bg-slate-900/60 border border-slate-800/60 text-xs font-semibold text-slate-400">
                <span class="size-1.5 inline-block bg-emerald-400 rounded-full animate-ping"></span>{{ __('Actualisation dans') }}<span class="text-emerald-400 font-bold tabular-nums" x-text="countdown + 's'"></span>
            </div>
            <button type="button" data-hs-overlay="#hs-modal-export-report" class="py-2.5 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-bold rounded-xl border border-transparent bg-gradient-to-r from-primary-600 to-purple-600 text-white hover:from-primary-700 hover:to-purple-700 shadow-md shadow-primary-500/15 transition-all glow-hover">
                <i data-lucide="download" class="size-4"></i>{{ __('Exporter Rapport') }}</button>
        </div>
    </div>

    {{-- KPI Cards Row 1 --}}
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <div class="p-5 bg-white border border-gray-200/60 rounded-2xl shadow-premium hover:shadow-premium-hover transition-all duration-300 dark:bg-[#0D121F] dark:border-slate-800/60 flex items-center justify-between group">
            <div class="space-y-2">
                <span class="text-xs font-bold text-slate-400 dark:text-neutral-450 uppercase tracking-wider">{{ __('Total Résidents') }}</span>
                <div class="flex items-baseline gap-x-2">
                    <span class="text-3xl font-extrabold text-slate-850 dark:text-white leading-none">{{ number_format($stats['total_residents']) }}</span>
                </div>
            </div>
            <div class="size-12 rounded-xl bg-primary-500/10 text-primary-500 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                <i data-lucide="users" class="size-5.5"></i>
            </div>
        </div>

        <div class="p-5 bg-white border border-gray-200/60 rounded-2xl shadow-premium hover:shadow-premium-hover transition-all duration-300 dark:bg-[#0D121F] dark:border-slate-800/60 flex items-center justify-between group">
            <div class="space-y-2">
                <span class="text-xs font-bold text-slate-400 dark:text-neutral-450 uppercase tracking-wider">{{ __('Paiements en Retard') }}</span>
                <div class="flex items-baseline gap-x-2">
                    <span class="text-3xl font-extrabold text-rose-500 leading-none">{{ $stats['paiements_retard'] }}</span>
                    <span class="text-xs font-semibold text-slate-400 dark:text-neutral-500">/ {{ $stats['total_paiements_attendus'] }}</span>
                </div>
            </div>
            <div class="size-12 rounded-xl bg-rose-500/10 text-rose-500 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                <i data-lucide="wallet" class="size-5.5"></i>
            </div>
        </div>

        <div class="p-5 bg-white border border-gray-200/60 rounded-2xl shadow-premium hover:shadow-premium-hover transition-all duration-300 dark:bg-[#0D121F] dark:border-slate-800/60 flex items-center justify-between group">
            <div class="space-y-2">
                <span class="text-xs font-bold text-slate-400 dark:text-neutral-450 uppercase tracking-wider">{{ __('Problèmes Ouverts') }}</span>
                <div class="flex items-baseline gap-x-2">
                    <span class="text-3xl font-extrabold text-slate-850 dark:text-white leading-none">{{ $stats['incidents_ouverts'] }}</span>
                </div>
            </div>
            <div class="size-12 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                <i data-lucide="alert-triangle" class="size-5.5"></i>
            </div>
        </div>

        <div class="p-5 bg-white border border-gray-200/60 rounded-2xl shadow-premium hover:shadow-premium-hover transition-all duration-300 dark:bg-[#0D121F] dark:border-slate-800/60 flex items-center justify-between group">
            <div class="space-y-2">
                <span class="text-xs font-bold text-slate-400 dark:text-neutral-450 uppercase tracking-wider">{{ __('Immeubles Gérés') }}</span>
                <div class="flex items-baseline gap-x-2">
                    <span class="text-3xl font-extrabold text-slate-850 dark:text-white leading-none">{{ $stats['total_immeubles'] }}</span>
                </div>
            </div>
            <div class="size-12 rounded-xl bg-purple-500/10 text-purple-500 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                <i data-lucide="building-2" class="size-5.5"></i>
            </div>
        </div>
    </div>

    {{-- KPI Cards Row 2 — Activité Système --}}
    <div class="grid sm:grid-cols-2 gap-4 sm:gap-6">
        <div class="p-5 bg-white border border-gray-200/60 rounded-2xl shadow-premium dark:bg-[#0D121F] dark:border-slate-800/60 flex items-center justify-between group hover:shadow-premium-hover transition-all duration-300">
            <div class="space-y-1.5">
                <span class="text-xs font-bold text-slate-400 dark:text-neutral-450 uppercase tracking-wider">{{ __("Actions Aujourd'hui") }}</span>
                <div class="flex items-baseline gap-x-2">
                    <span class="text-3xl font-extrabold text-emerald-400 leading-none">{{ $stats['logs_today'] }}</span>
                    <span class="text-xs font-semibold text-slate-400">{{ __('événements') }}</span>
                </div>
                <p class="text-[11px] text-slate-400 dark:text-neutral-500">
                    <span class="font-bold text-slate-600 dark:text-neutral-300">{{ $stats['logs_last_hour'] }}</span>{{ __('dans la dernière heure') }}</p>
            </div>
            <div class="size-12 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                <i data-lucide="activity" class="size-5.5"></i>
            </div>
        </div>

        <div class="p-5 bg-white border border-gray-200/60 rounded-2xl shadow-premium dark:bg-[#0D121F] dark:border-slate-800/60 flex items-center justify-between group hover:shadow-premium-hover transition-all duration-300">
            <div class="space-y-1.5">
                <span class="text-xs font-bold text-slate-400 dark:text-neutral-450 uppercase tracking-wider">{{ __('Dernière Action') }}</span>
                @if($recentActivity->first())
                    <div class="flex items-baseline gap-x-2">
                        <span class="text-lg font-extrabold text-slate-850 dark:text-white leading-none truncate max-w-[160px]">
                            {{ $recentActivity->first()->user->prenom ?? '—' }} {{ $recentActivity->first()->user->nom ?? '' }}
                        </span>
                    </div>
                    <p class="text-[11px] text-slate-400 dark:text-neutral-500">
                        <span class="font-bold text-blue-400">{{ __($recentActivity->first()->action) }}</span>
                        · {{ $recentActivity->first()->created_at ? $recentActivity->first()->created_at->diffForHumans() : '—' }}
                    </p>
                @else
                    <span class="text-sm text-slate-400">{{ __('Aucune activité') }}</span>
                @endif
            </div>
            <div class="size-12 rounded-xl bg-blue-500/10 text-blue-400 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                <i data-lucide="zap" class="size-5.5"></i>
            </div>
        </div>
    </div>

    {{-- Graphique Activité 7 jours --}}
    <div class="p-6 bg-white border border-gray-200/60 rounded-2xl shadow-premium dark:bg-[#0D121F] dark:border-slate-800/60">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h4 class="text-sm font-bold text-slate-800 dark:text-white">{{ __('Activité des 7 derniers jours') }}</h4>
                <p class="text-xs text-slate-400 mt-0.5">{{ __("Nombre d'événements enregistrés par jour") }}</p>
            </div>
            <span class="inline-flex items-center gap-x-1.5 py-1 px-2.5 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-400">
                <i data-lucide="bar-chart-2" class="size-3"></i>{{ __('7 jours') }}</span>
        </div>
        {{-- Chart bars area: fixed 120px height + 28px for labels below --}}
        <div class="flex items-end gap-2" style="height: 120px;">
            @foreach($activityByDay as $day)
                @php
                    $maxPx   = 100;
                    $barPx   = $maxCount > 0 ? max(6, (int) round(($day['count'] / $maxCount) * $maxPx)) : 6;
                    $isToday = $day['date'] === now()->toDateString();
                @endphp
                <div class="flex-1 flex flex-col items-center justify-end gap-y-1 h-full group/bar">
                    {{-- Count tooltip on hover --}}
                    <span class="text-[10px] font-bold tabular-nums opacity-0 group-hover/bar:opacity-100 transition-opacity
                                 {{ $isToday ? 'text-primary-400' : 'text-slate-400 dark:text-neutral-500' }}">
                        {{ $day['count'] }}
                    </span>
                    {{-- Bar --}}
                    <div class="w-full rounded-t-md transition-all duration-500 cursor-default
                                {{ $isToday
                                    ? 'bg-gradient-to-t from-primary-600 to-primary-400 group-hover/bar:from-primary-500 group-hover/bar:to-primary-300'
                                    : 'bg-gradient-to-t from-slate-600 to-slate-500 dark:from-slate-700 dark:to-slate-600 group-hover/bar:from-primary-600 group-hover/bar:to-primary-400' }}"
                         style="height: {{ $barPx }}px;"
                         title="{{ $day['count'] }} {{ __('actions le') }} {{ $day['date'] }}">
                    </div>
                    {{-- Day label --}}
                    <span class="text-[10px] font-semibold mt-1
                                 {{ $isToday ? 'text-primary-400' : 'text-slate-400 dark:text-neutral-500' }}">
                        {{ $day['label'] }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Deux colonnes : Top Actions + Top Utilisateurs --}}
    <div class="grid lg:grid-cols-2 gap-6">

        {{-- Top Actions --}}
        <div class="p-6 bg-white border border-gray-200/60 rounded-2xl shadow-premium dark:bg-[#0D121F] dark:border-slate-800/60">
            <div class="flex items-center justify-between mb-5">
                <h4 class="text-sm font-bold text-slate-800 dark:text-white">{{ __('Actions les plus fréquentes') }}</h4>
                <i data-lucide="trending-up" class="size-4 text-slate-400"></i>
            </div>
            @if($topActions->isEmpty())
                <p class="text-sm text-slate-400 text-center py-4">{{ __('Aucune donnée disponible.') }}</p>
            @else
                <div class="space-y-3">
                    @foreach($topActions as $action)
                        @php
                            $key = strtolower($action->action);
                            $color = $actionColors[$key] ?? ['bg' => 'bg-slate-500/15', 'text' => 'text-slate-400', 'dot' => 'bg-slate-400'];
                            $total = $topActions->sum('count');
                            $pct = $total > 0 ? round(($action->count / $total) * 100) : 0;
                        @endphp
                        <div class="flex items-center gap-x-3">
                            <span class="inline-flex items-center gap-x-1.5 py-0.5 px-2.5 rounded-full text-xs font-bold {{ $color['bg'] }} {{ $color['text'] }} w-24 shrink-0">
                                <span class="size-1.5 rounded-full {{ $color['dot'] }}"></span>
                                {{ ucfirst($action->action) }}
                            </span>
                            <div class="flex-1 bg-slate-100 dark:bg-slate-800/60 rounded-full h-2 overflow-hidden">
                                <div class="h-full rounded-full {{ $color['dot'] }} transition-all duration-700" style="width: {{ $pct }}%"></div>
                            </div>
                            <span class="text-xs font-bold text-slate-600 dark:text-neutral-300 w-8 text-right tabular-nums">{{ $action->count }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Top Utilisateurs --}}
        <div class="p-6 bg-white border border-gray-200/60 rounded-2xl shadow-premium dark:bg-[#0D121F] dark:border-slate-800/60">
            <div class="flex items-center justify-between mb-5">
                <h4 class="text-sm font-bold text-slate-800 dark:text-white">{{ __('Utilisateurs les plus actifs') }}</h4>
                <i data-lucide="flame" class="size-4 text-orange-400"></i>
            </div>
            @if($topUsers->isEmpty())
                <p class="text-sm text-slate-400 text-center py-4">{{ __('Aucune donnée disponible.') }}</p>
            @else
                <div class="space-y-3">
                    @foreach($topUsers as $index => $entry)
                        @php $user = $entry->user; @endphp
                        @if($user)
                        <div class="flex items-center gap-x-3">
                            <span class="text-xs font-black w-5 text-center {{ $index === 0 ? 'text-amber-400' : 'text-slate-400' }}">
                                {{ $index + 1 }}
                            </span>
                            <div class="size-8 rounded-xl bg-gradient-to-tr from-primary-500 to-purple-600 text-white font-bold flex items-center justify-center text-xs shrink-0">
                                {{ substr($user->prenom, 0, 1) }}{{ substr($user->nom, 0, 1) }}
                            </div>
                            <div class="grow min-w-0">
                                <span class="block text-sm font-bold text-slate-800 dark:text-neutral-200 truncate">{{ $user->prenom }} {{ $user->nom }}</span>
                                <span class="block text-[10px] text-slate-400 dark:text-neutral-500 truncate">{{ $user->email }}</span>
                            </div>
                            <span class="inline-flex items-center py-0.5 px-2.5 rounded-full text-xs font-bold bg-primary-500/10 text-primary-400 shrink-0 tabular-nums">
                                {{ $entry->count }}
                            </span>
                        </div>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Activity Table —— améliorée --}}
    <div class="flex flex-col border border-gray-200/60 dark:border-slate-800/60 rounded-2xl shadow-premium bg-white dark:bg-[#0D121F] overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200/60 dark:border-slate-800/60 bg-slate-50/50 dark:bg-slate-900/30 flex items-center justify-between">
            <h4 class="text-sm font-bold text-slate-800 dark:text-white">{{ __('Activité Récente') }}</h4>
            <div class="flex items-center gap-x-3">
                <span class="inline-flex items-center gap-x-1.5 py-1 px-2.5 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-400">
                    <span class="size-1.5 inline-block bg-emerald-400 rounded-full animate-ping"></span> Live
                </span>
                <a href="{{ route('admin.logs') }}" class="inline-flex items-center gap-x-1.5 py-1 px-3 rounded-lg text-xs font-semibold text-primary-400 hover:text-primary-300 hover:bg-primary-500/10 transition-all duration-200">{{ __('Voir tout') }}<i data-lucide="arrow-right" class="size-3"></i>
                </a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-250 dark:divide-slate-800">
                <thead class="bg-slate-50 dark:bg-slate-900/50">
                    <tr>
                        <th scope="col" class="px-6 py-3.5 text-start text-xs font-semibold text-slate-400 uppercase dark:text-neutral-450">{{ __('Utilisateur') }}</th>
                        <th scope="col" class="px-6 py-3.5 text-start text-xs font-semibold text-slate-400 uppercase dark:text-neutral-450">{{ __('Action') }}</th>
                        <th scope="col" class="px-6 py-3.5 text-start text-xs font-semibold text-slate-400 uppercase dark:text-neutral-450">{{ __('Détails') }}</th>
                        <th scope="col" class="px-6 py-3.5 text-end text-xs font-semibold text-slate-400 uppercase dark:text-neutral-450">{{ __('Date') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200/60 dark:divide-slate-800/60">
                    @forelse($recentActivity as $log)
                    @php
                        $key = strtolower($log->action);
                        $color = $actionColors[$key] ?? ['bg' => 'bg-slate-500/15', 'text' => 'text-slate-400', 'dot' => 'bg-slate-400'];
                    @endphp
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/10 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-x-3">
                                <div class="size-8.5 rounded-xl bg-gradient-to-tr from-primary-500 to-purple-600 text-white font-bold flex items-center justify-center shadow-md shadow-primary-500/10 text-xs shrink-0">
                                    {{ substr($log->user->prenom ?? '?', 0, 1) }}{{ substr($log->user->nom ?? '', 0, 1) }}
                                </div>
                                <div class="grow">
                                    <span class="block text-sm font-bold text-slate-800 dark:text-neutral-250">{{ $log->user->prenom ?? '—' }} {{ $log->user->nom ?? '' }}</span>
                                    <span class="block text-[10px] text-slate-400 dark:text-neutral-500 font-semibold">{{ $log->user->email ?? '' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center gap-x-1.5 py-0.5 px-2.5 rounded-full text-xs font-bold {{ $color['bg'] }} {{ $color['text'] }}">
                                <span class="size-1.5 rounded-full {{ $color['dot'] }}"></span>
                                {{ ucfirst($log->action) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-550 dark:text-neutral-400 max-w-xs truncate">
                            {{ $log->details }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-end text-xs font-semibold text-slate-400 dark:text-neutral-450">
                            {{ $log->created_at ? $log->created_at->diffForHumans() : '—' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-sm font-medium text-slate-400 dark:text-neutral-500">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <i data-lucide="archive" class="size-8 text-slate-300 dark:text-neutral-700"></i>
                                <span>{{ __('Aucune activité récente enregistrée.') }}</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- Modal Export Rapport --}}
<div id="hs-modal-export-report" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none" role="dialog" tabindex="-1" aria-labelledby="hs-modal-export-report-label">
    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
        <div class="w-full flex flex-col bg-white/95 border border-gray-200/60 shadow-xl rounded-2xl pointer-events-auto dark:bg-slate-900/95 dark:border-slate-800/60 backdrop-blur-xl">
            <div class="flex justify-between items-center py-3.5 px-5 border-b border-gray-200/50 dark:border-slate-800/50">
                <h3 id="hs-modal-export-report-label" class="font-bold text-slate-800 dark:text-white">{{ __("Exporter un rapport d'activité") }}</h3>
                <button type="button" class="size-8 inline-flex justify-center items-center gap-x-2 rounded-xl border border-transparent bg-slate-100 text-slate-800 hover:bg-slate-200 focus:outline-none dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-neutral-400" aria-label="Close" data-hs-overlay="#hs-modal-export-report">
                    <span class="sr-only">{{ __('Close') }}</span>
                    <i data-lucide="x" class="size-4"></i>
                </button>
            </div>
            <div class="p-5 overflow-y-auto text-sm text-slate-600 dark:text-neutral-400 space-y-3">
                <p>{{ __("Sélectionnez le format et la période pour l'exportation du rapport d'activité global.") }}</p>
                <div class="space-y-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400">{{ __('Format') }}</label>
                    <select class="py-2.5 px-3 block w-full border-gray-200/80 rounded-xl text-sm dark:bg-slate-800 dark:border-slate-700 dark:text-neutral-300">
                        <option>{{ __('Format PDF (.pdf)') }}</option>
                        <option>{{ __('Format Excel (.xlsx)') }}</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end items-center gap-x-2 py-3 px-5 border-t border-gray-200/50 dark:border-slate-800/50">
                <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-xl border border-gray-200 bg-white text-slate-700 shadow-sm hover:bg-slate-50 dark:bg-slate-800 dark:border-slate-750 dark:text-white dark:hover:bg-slate-700" data-hs-overlay="#hs-modal-export-report">{{ __('Annuler') }}</button>
                <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-xl border border-transparent bg-gradient-to-r from-primary-600 to-purple-600 text-white hover:from-primary-700 hover:to-purple-700 shadow-md shadow-primary-500/10 transition-all glow-hover" data-hs-overlay="#hs-modal-export-report">{{ __("Générer l'export") }}</button>
            </div>
        </div>
    </div>
</div>
@endsection
