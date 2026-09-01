@extends('layouts.app')

@section('content')
<div class="space-y-6" x-data="{ isOpen: false, editForm: { id: '', statut: 'non_payé', notes: '', date_paiement: '' } }">
    
    {{-- Breadcrumbs & Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="space-y-1">
            <div class="flex items-center gap-x-2 text-xs text-slate-400 dark:text-neutral-500 font-bold uppercase tracking-wider">
                <a href="{{ route('admin.syndics') }}" class="hover:text-primary-500 transition-colors">{{ __('Syndics') }}</a>
                <i data-lucide="chevron-right" class="size-3"></i>
                <span class="text-slate-500 dark:text-neutral-450">{{ __('Historique de Facturation') }}</span>
            </div>
            <h2 class="text-2xl font-bold text-slate-850 dark:text-white">
                {{ __('Historique :') }} {{ $user->prenom }} {{ $user->nom }}
            </h2>
        </div>
        <div class="flex items-center gap-x-3">
            <a href="{{ route('admin.syndics') }}" class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-xl border border-gray-200 bg-white text-slate-700 shadow-sm hover:bg-slate-50 dark:bg-slate-800 dark:border-slate-700 dark:text-white dark:hover:bg-slate-700/80 transition-all">
                <i data-lucide="arrow-left" class="size-4"></i> {{ __('Retour') }}
            </a>
            
            {{-- Toggle Status --}}
            <form action="{{ route('admin.syndics.toggle-status', $user->id) }}" method="POST" onsubmit="return confirm('{{ $user->is_active ? __('Suspendre ce compte syndic ?') : __('Réactiver ce compte syndic ?') }}');">
                @csrf
                <button type="submit" class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-bold rounded-xl border {{ $user->is_active ? 'border-amber-200/40 bg-amber-50 text-amber-600 hover:bg-amber-100 dark:bg-amber-950/20 dark:text-amber-400 dark:border-amber-950/30' : 'border-emerald-200/40 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-950/30' }} transition-all shadow-sm">
                    @if($user->is_active)
                        <i data-lucide="ban" class="size-4"></i> {{ __('Suspendre l\'accès') }}
                    @else
                        <i data-lucide="check-circle" class="size-4"></i> {{ __('Activer l\'accès') }}
                    @endif
                </button>
            </form>
        </div>
    </div>

    {{-- Detail Card --}}
    <div class="grid md:grid-cols-3 gap-6">
        {{-- Profile Card --}}
        <div class="md:col-span-1 p-5 bg-white border border-gray-200/60 rounded-2xl shadow-premium dark:bg-[#0D121F] dark:border-slate-800/60 flex flex-col justify-between">
            <div class="space-y-4">
                <div class="flex items-center gap-x-3">
                    <div class="size-12 rounded-2xl bg-gradient-to-tr from-primary-500 to-purple-600 text-white font-extrabold flex items-center justify-center text-lg shadow-md shadow-primary-500/10 shrink-0">
                        {{ substr($user->prenom, 0, 1) }}{{ substr($user->nom, 0, 1) }}
                    </div>
                    <div>
                        <span class="block text-base font-bold text-slate-850 dark:text-white leading-tight">{{ $user->prenom }} {{ $user->nom }}</span>
                        <span class="inline-flex items-center gap-x-1.5 py-0.5 px-2 rounded-full text-[10px] font-bold mt-1 {{ $user->is_active ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20' : 'bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20' }}">
                            <span class="size-1 inline-block rounded-full bg-current"></span>
                            {{ $user->is_active ? __('Compte Actif') : __('Compte Suspendu') }}
                        </span>
                    </div>
                </div>
                
                <hr class="border-gray-150 dark:border-neutral-800/50">

                <div class="space-y-2.5 text-sm text-slate-600 dark:text-neutral-400">
                    <div class="flex items-center gap-x-2">
                        <i data-lucide="mail" class="size-4 text-slate-400"></i>
                        <span class="truncate">{{ $user->email }}</span>
                    </div>
                    <div class="flex items-center gap-x-2">
                        <i data-lucide="phone" class="size-4 text-slate-400"></i>
                        <span>{{ $user->telephone ?? '—' }}</span>
                    </div>
                    <div class="flex items-center gap-x-2">
                        <i data-lucide="map-pin" class="size-4 text-slate-400"></i>
                        <span>{{ $user->ville ?? '—' }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Subscription Detail Card --}}
        <div class="md:col-span-2 p-5 bg-white border border-gray-200/60 rounded-2xl shadow-premium dark:bg-[#0D121F] dark:border-slate-800/60">
            @php $subInfo = $user->calculateTotalSubscription(); @endphp
            <div class="flex items-center justify-between mb-4 pb-4 border-b border-gray-150 dark:border-neutral-800/50">
                <div class="space-y-1">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">{{ __('Abonnement Mensuel Dû') }}</span>
                    <div class="text-3xl font-extrabold text-slate-850 dark:text-white leading-none">
                        {{ number_format($subInfo['total_price'], 2) }} <span class="text-sm font-semibold text-gray-500">{{ __('DH/mois') }}</span>
                    </div>
                </div>
                <div class="size-12 rounded-xl bg-primary-500/10 text-primary-500 flex items-center justify-center">
                    <i data-lucide="credit-card" class="size-6"></i>
                </div>
            </div>

            <div class="grid sm:grid-cols-2 gap-4 max-h-40 overflow-y-auto pr-1">
                @forelse($subInfo['breakdown'] as $item)
                    <div class="bg-slate-50 dark:bg-slate-900/40 rounded-xl p-3 border border-gray-150 dark:border-slate-850">
                        <div class="font-bold text-xs text-slate-800 dark:text-slate-200 flex items-center gap-x-1.5">
                            <i data-lucide="building" class="size-3.5 text-primary-500"></i>
                            {{ $item['immeuble']->nom }}
                        </div>
                        <div class="flex justify-between text-[11px] text-slate-500 dark:text-neutral-400 mt-2">
                            <span>{{ $item['calculation']['residents_count'] }} {{ __('Résidents') }} × 4 {{ __('DH') }}</span>
                            <span class="font-bold text-slate-700 dark:text-slate-300">{{ number_format($item['calculation']['residents_price'], 2) }} {{ __('DH') }}</span>
                        </div>
                        <div class="flex justify-between text-[11px] text-slate-500 dark:text-neutral-400 mt-1">
                            <span>{{ $item['calculation']['syndics_count'] }} {{ __('Syndics') }} × 8 {{ __('DH') }}</span>
                            <span class="font-bold text-slate-700 dark:text-slate-300">{{ number_format($item['calculation']['syndics_price'], 2) }} {{ __('DH') }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 italic col-span-2">{{ __('Aucun immeuble géré comme principal.') }}</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Billing History Table --}}
    <div class="flex flex-col border border-gray-200/60 dark:border-slate-800/60 rounded-2xl shadow-premium bg-white dark:bg-[#0D121F] overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200/60 dark:border-slate-800/60 bg-slate-50/50 dark:bg-slate-900/30 flex items-center justify-between">
            <h4 class="text-sm font-bold text-slate-800 dark:text-white">{{ __('Historique des Factures d\'Abonnement') }}</h4>
            <span class="text-xs text-slate-400 font-semibold">{{ count($abonnements) }} {{ __('périodes enregistrées') }}</span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-250 dark:divide-slate-800">
                <thead class="bg-slate-50 dark:bg-slate-900/50">
                    <tr>
                        <th scope="col" class="px-6 py-3.5 text-start text-xs font-semibold text-slate-400 uppercase dark:text-neutral-450">{{ __('Période') }}</th>
                        <th scope="col" class="px-6 py-3.5 text-start text-xs font-semibold text-slate-400 uppercase dark:text-neutral-450">{{ __('Montant') }}</th>
                        <th scope="col" class="px-6 py-3.5 text-start text-xs font-semibold text-slate-400 uppercase dark:text-neutral-450">{{ __('Statut') }}</th>
                        <th scope="col" class="px-6 py-3.5 text-start text-xs font-semibold text-slate-400 uppercase dark:text-neutral-450">{{ __('Date de Règlement') }}</th>
                        <th scope="col" class="px-6 py-3.5 text-start text-xs font-semibold text-slate-400 uppercase dark:text-neutral-450">{{ __('Notes / Commentaires') }}</th>
                        <th scope="col" class="px-6 py-3.5 text-end text-xs font-semibold text-slate-400 uppercase dark:text-neutral-450">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200/60 dark:divide-slate-800/60">
                    @forelse($abonnements as $ab)
                    @php
                        $moisNoms = [
                            1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril', 5 => 'Mai', 6 => 'Juin',
                            7 => 'Juillet', 8 => 'Août', 9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
                        ];
                        $moisTrad = __($moisNoms[$ab->mois] ?? 'Inconnu');
                    @endphp
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/10 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-850 dark:text-neutral-200">
                            {{ $moisTrad }} {{ $ab->annee }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-extrabold text-slate-800 dark:text-white">
                            {{ number_format($ab->montant, 2) }} {{ __('DH') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center gap-x-1.5 py-1 px-3 rounded-full text-xs font-bold {{ $ab->statut === 'payé' ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20' : 'bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20' }}">
                                <span class="size-1.5 inline-block rounded-full bg-current"></span>
                                {{ $ab->statut === 'payé' ? __('Payé') : __('Non Payé') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-neutral-455">
                            {{ $ab->date_paiement ? $ab->date_paiement->format('d/m/Y H:i') : '—' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600 dark:text-neutral-400 max-w-xs truncate">
                            {{ $ab->notes ?? '—' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                            <button type="button" @click="editForm = { id: '{{ $ab->id }}', statut: '{{ $ab->statut }}', notes: '{{ addslashes($ab->notes ?? '') }}', date_paiement: '{{ $ab->date_paiement ? $ab->date_paiement->format('Y-m-d') : '' }}' }; isOpen = true" class="py-1.5 px-3 inline-flex items-center gap-x-1.5 text-xs font-bold rounded-lg border border-gray-200 bg-white text-slate-800 shadow-sm hover:bg-slate-50 dark:bg-slate-800 dark:border-slate-700 dark:text-neutral-300 dark:hover:bg-slate-700 transition-all">
                                <i data-lucide="edit-3" class="size-3.5"></i> {{ __('Mettre à jour') }}
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-sm font-medium text-slate-400 dark:text-neutral-500">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <i data-lucide="credit-card" class="size-8 text-slate-300 dark:text-neutral-700"></i>
                                <span>{{ __('Aucune facture d\'abonnement générée pour le moment.') }}</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div x-show="isOpen" class="fixed inset-0 z-[150] overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4" style="display: none;" x-transition>
        <div class="w-full max-w-md bg-white border border-gray-200 shadow-xl rounded-2xl pointer-events-auto dark:bg-slate-900 dark:border-slate-800/80 overflow-hidden" @click.outside="isOpen = false">
            <div class="flex justify-between items-center py-3.5 px-5 border-b border-gray-200/50 dark:border-slate-800/50 bg-slate-50/50 dark:bg-slate-900/20">
                <h3 class="font-bold text-slate-800 dark:text-white">{{ __('Mettre à jour la Facture') }}</h3>
                <button type="button" @click="isOpen = false" class="size-8 inline-flex justify-center items-center gap-x-2 rounded-xl border border-transparent bg-slate-100 text-slate-800 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-neutral-400">
                    <i data-lucide="x" class="size-4"></i>
                </button>
            </div>
            
            <form :action="`/admin/abonnements/${editForm.id}/update`" method="POST">
                @csrf
                <div class="p-5 space-y-4">
                    {{-- Status --}}
                    <div class="space-y-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-400">{{ __('Statut du règlement') }}</label>
                        <select name="statut" x-model="editForm.statut" class="py-2.5 px-3 block w-full border-gray-200/80 rounded-xl text-sm dark:bg-slate-800 dark:border-slate-700 dark:text-neutral-350 focus:border-primary-500 focus:ring-primary-500">
                            <option value="non_payé">{{ __('Non Payé') }}</option>
                            <option value="payé">{{ __('Payé') }}</option>
                        </select>
                    </div>

                    {{-- Date --}}
                    <div class="space-y-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-400">{{ __('Date du paiement') }}</label>
                        <div class="relative">
                            <input type="text" name="date_paiement" x-model="editForm.date_paiement" x-init="flatpickr($el, { locale: '{{ app()->getLocale() }}', dateFormat: 'Y-m-d', onChange: (selectedDates, dateStr) => { editForm.date_paiement = dateStr; } }); $watch('editForm.date_paiement', val => $el._flatpickr.setDate(val))" class="py-2 px-3 ps-10 block w-full border-gray-200/80 rounded-xl text-sm dark:bg-slate-800 dark:border-slate-700 dark:text-neutral-350 focus:border-primary-500 focus:ring-primary-500">
                            <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-3">
                                <i data-lucide="calendar" class="size-4 text-gray-400 dark:text-neutral-500"></i>
                            </div>
                        </div>
                        <p class="text-[10px] text-slate-400 leading-normal">{{ __('* Si vide et validé comme "Payé", prendra la date d\'aujourd\'hui.') }}</p>
                    </div>

                    {{-- Notes --}}
                    <div class="space-y-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-400">{{ __('Commentaire / Notes') }}</label>
                        <textarea name="notes" x-model="editForm.notes" rows="3" placeholder="{{ __('Ex: Virement bancaire reçu sur CIH Bank.') }}" class="py-2 px-3 block w-full border-gray-200/80 rounded-xl text-sm dark:bg-slate-800 dark:border-slate-700 dark:text-neutral-350 focus:border-primary-500 focus:ring-primary-500"></textarea>
                    </div>
                </div>
                
                <div class="flex justify-end items-center gap-x-2 py-3 px-5 border-t border-gray-200/50 dark:border-slate-800/50 bg-slate-50/50 dark:bg-slate-900/20">
                    <button type="button" @click="isOpen = false" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-xl border border-gray-200 bg-white text-slate-700 shadow-sm hover:bg-slate-50 dark:bg-slate-800 dark:border-slate-750 dark:text-white dark:hover:bg-slate-700">{{ __('Annuler') }}</button>
                    <button type="submit" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-xl border border-transparent bg-gradient-to-r from-primary-600 to-purple-600 text-white hover:from-primary-700 hover:to-purple-700 shadow-md shadow-primary-500/10 transition-all glow-hover">{{ __('Enregistrer') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
