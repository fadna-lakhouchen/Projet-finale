@extends('layouts.app')

@section('content')
<section class="space-y-6">
    <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Nouveau Signalement</h2>
    
    <div id="success-alert" class="hidden bg-green-50 border border-green-200 text-sm text-green-800 rounded-lg p-4 mb-6 dark:bg-green-900/30 dark:border-green-800 dark:text-green-400">
        <div class="flex">
            <i data-lucide="check-circle" class="size-4 mt-0.5"></i>
            <div class="ms-3">
                <h3 class="font-semibold">Signalement envoyé avec succès!</h3>
            </div>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 max-w-2xl dark:bg-neutral-800 dark:border-neutral-700">
        <form id="incident-form" onsubmit="event.preventDefault(); document.getElementById('success-alert').classList.remove('hidden'); this.reset();">
            <div class="grid gap-y-5">
                <div>
                    <label class="block text-sm font-medium mb-2 dark:text-white">Type de problème <span class="text-red-500">*</span></label>
                    <select required class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm bg-white border dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 focus:border-primary-500 focus:ring-primary-500">
                        <option value="" selected disabled>Sélectionner une catégorie</option>
                        <option>Plomberie / Fuite</option>
                        <option>Électricité / Éclairage</option>
                        <option>Ascenseur en panne</option>
                        <option>Problème de voisinage</option>
                        <option>Autre</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2 dark:text-white">Description détaillée <span class="text-red-500">*</span></label>
                    <textarea rows="4" required class="py-3 px-4 block w-full border-gray-200 rounded-lg border text-sm dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 focus:border-primary-500 focus:ring-primary-500" placeholder="Décrivez le problème..."></textarea>
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
                                $statutColor = match($incident->statut) {
                                    'nouveau' => 'blue',
                                    'en cours' => 'orange',
                                    'résolu' => 'green',
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
