@extends('layouts.app')

@section('content')
<section class="space-y-6">
    <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Nouveau Signalement</h2>
    
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-sm text-green-800 rounded-lg p-4 mb-6 dark:bg-green-900/30 dark:border-green-800 dark:text-green-400">
        <div class="flex">
            <i data-lucide="check-circle" class="size-4 mt-0.5"></i>
            <div class="ms-3">
                <h3 class="font-semibold">{{ session('success') }}</h3>
            </div>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-sm text-red-800 rounded-lg p-4 mb-6 dark:bg-red-900/30 dark:border-red-850 dark:text-red-400">
        <div class="flex">
            <i data-lucide="alert-circle" class="size-4 mt-0.5"></i>
            <div class="ms-3">
                <h3 class="font-semibold">{{ session('error') }}</h3>
            </div>
        </div>
    </div>
    @endif

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 max-w-2xl dark:bg-neutral-800 dark:border-neutral-700">
        <form id="incident-form" action="{{ route('resident.incidents.store') }}" method="POST"
            x-data="{
                priorite: 'moyenne',
                openPrio: false,
                prioLabel: 'Moyenne',
                prioColor: 'blue',
                setPrio(val, label, color) {
                    this.priorite = val;
                    this.prioLabel = label;
                    this.prioColor = color;
                    this.openPrio = false;
                }
            }">
            @csrf
            <div class="grid gap-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2 dark:text-white">Titre du problème <span class="text-red-500">*</span></label>
                        <input type="text" name="titre" required
                            class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm border dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 focus:border-primary-500 focus:ring-primary-500"
                            placeholder="Ex: Fuite d'eau, Ascenseur en panne...">
                    </div>

                    {{-- Priorité — Preline-style dropdown --}}
                    <div>
                        <label class="block text-sm font-medium mb-2 dark:text-white">Priorité <span class="text-red-500">*</span></label>
                        <input type="hidden" name="priorite" :value="priorite">
                        <div class="relative">
                            <button type="button"
                                @click="openPrio = !openPrio"
                                @click.outside="openPrio = false"
                                class="w-full py-3 px-4 inline-flex items-center justify-between gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-200 shadow-sm hover:bg-gray-50 dark:hover:bg-neutral-700/60 transition-all duration-200">
                                <span class="flex items-center gap-2">
                                    <span class="size-2 rounded-full inline-block"
                                        :class="{
                                            'bg-gray-400': prioColor === 'gray',
                                            'bg-blue-500': prioColor === 'blue',
                                            'bg-orange-500': prioColor === 'orange',
                                            'bg-red-500': prioColor === 'red'
                                        }"></span>
                                    <span x-text="prioLabel"></span>
                                </span>
                                <i data-lucide="chevron-down" class="size-4 text-gray-400 transition-transform duration-200" :class="openPrio ? 'rotate-180' : ''"></i>
                            </button>
                            <div x-show="openPrio" x-cloak
                                class="absolute left-0 top-full z-[100] mt-1 w-full bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 shadow-xl rounded-lg p-1.5">
                                <div @click="setPrio('basse', 'Basse', 'gray')"
                                    class="cursor-pointer flex items-center gap-2.5 py-2 px-3 rounded-md text-sm text-gray-700 dark:text-neutral-300 hover:bg-gray-100 dark:hover:bg-neutral-700/50 transition-colors">
                                    <span class="size-2 rounded-full bg-gray-400 flex-shrink-0"></span>
                                    Basse
                                </div>
                                <div @click="setPrio('moyenne', 'Moyenne', 'blue')"
                                    class="cursor-pointer flex items-center gap-2.5 py-2 px-3 rounded-md text-sm text-gray-700 dark:text-neutral-300 hover:bg-gray-100 dark:hover:bg-neutral-700/50 transition-colors">
                                    <span class="size-2 rounded-full bg-blue-500 flex-shrink-0"></span>
                                    Moyenne
                                </div>
                                <div @click="setPrio('haute', 'Haute', 'orange')"
                                    class="cursor-pointer flex items-center gap-2.5 py-2 px-3 rounded-md text-sm text-gray-700 dark:text-neutral-300 hover:bg-gray-100 dark:hover:bg-neutral-700/50 transition-colors">
                                    <span class="size-2 rounded-full bg-orange-500 flex-shrink-0"></span>
                                    Haute
                                </div>
                                <div @click="setPrio('urgente', 'Urgente', 'red')"
                                    class="cursor-pointer flex items-center gap-2.5 py-2 px-3 rounded-md text-sm text-gray-700 dark:text-neutral-300 hover:bg-gray-100 dark:hover:bg-neutral-700/50 transition-colors">
                                    <span class="size-2 rounded-full bg-red-500 flex-shrink-0"></span>
                                    Urgente
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2 dark:text-white">Description détaillée <span class="text-red-500">*</span></label>
                    <textarea name="description" rows="4" required class="py-3 px-4 block w-full border-gray-200 rounded-lg border text-sm dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 focus:border-primary-500 focus:ring-primary-500" placeholder="Décrivez le problème..."></textarea>
                </div>
                <div class="pt-2">
                    <button type="submit" class="py-3 px-6 bg-primary-600 text-white rounded-lg text-sm font-semibold hover:bg-primary-700 flex items-center gap-2 transition-colors">
                        <i data-lucide="send" class="size-4"></i> Envoyer
                    </button>
                </div>
            </div>
        </form>
    </div>

    <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6 mt-12">Historique de mes signalements</h2>

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-neutral-800 dark:border-neutral-700">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                <thead class="bg-gray-50 dark:bg-neutral-800">
                    <tr>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Date</th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Problème</th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Priorité</th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                    @forelse($incidents as $incident)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-neutral-200">
                            {{ $incident->created_at->translatedFormat('d M Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="block text-sm font-semibold text-gray-800 dark:text-neutral-200">{{ $incident->titre }}</span>
                            <span class="block text-xs text-gray-500 dark:text-neutral-400 text-truncate max-w-xs">{{ Str::limit($incident->description, 50) }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $prioColor = match($incident->priorite) {
                                    'urgente' => 'red',
                                    'haute' => 'orange',
                                    'moyenne' => 'blue',
                                    default => 'gray'
                                };
                            @endphp
                            <span class="inline-flex items-center gap-1.5 py-1 px-2 rounded-md text-xs font-medium bg-{{ $prioColor }}-100 text-{{ $prioColor }}-800 capitalize">
                                {{ $incident->priorite }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statutColor = match(strtolower($incident->statut)) {
                                    'nouveau', 'ouvert', 'à traiter' => 'blue',
                                    'en cours' => 'orange',
                                    'résolu', 'terminé' => 'green',
                                    default => 'gray'
                                };
                            @endphp
                            <span class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-{{ $statutColor }}-100 text-{{ $statutColor }}-800 dark:bg-{{ $statutColor }}-900/30 dark:text-{{ $statutColor }}-500 capitalize">
                                {{ $incident->statut }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500 dark:text-neutral-400">Vous n'avez pas encore déclaré d'incident.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection
