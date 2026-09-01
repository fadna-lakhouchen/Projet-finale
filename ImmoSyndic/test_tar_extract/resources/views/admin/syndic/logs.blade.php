@extends('layouts.app')

@section('content')
<div x-data="syndicLogsComponent({{ json_encode($logs->map(function($log) {
        return [
            'id' => $log->id,
            'user_name' => $log->user ? ($log->user->prenom . ' ' . $log->user->nom) : 'Système',
            'user_role' => $log->user ? $log->user->role : 'N/A',
            'action' => $log->action,
            'model_type' => $log->model_type,
            'model_id' => $log->model_id,
            'modifications' => is_string($log->modifications) ? json_decode($log->modifications, true) : $log->modifications,
            'created_at' => $log->created_at ? $log->created_at->translatedFormat('d F Y \à H:i') : 'N/A',
            'created_at_raw' => $log->created_at ? $log->created_at->toIso8601String() : ''
        ];
    })) }})" class="space-y-8">

    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight dark:text-white">{{ __('Journal d\'Activité de l\'Équipe') }}</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">
                {{ __('Visualisez et suivez l\'historique complet de toutes les actions de création, modification et suppression effectuées par les syndics secondaires.') }}
            </p>
        </div>
    </div>

    <!-- Quick Stats Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <!-- Card 1 -->
        <div class="p-5 bg-white border border-gray-200/60 rounded-2xl shadow-premium hover:shadow-premium-hover transition-all duration-300 dark:bg-[#0D121F] dark:border-slate-800/60 flex items-center gap-x-4">
            <div class="size-11 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-600 dark:bg-emerald-500/20">
                <i data-lucide="plus-circle" class="size-5"></i>
            </div>
            <div>
                <span class="block text-xs font-bold text-slate-400 dark:text-neutral-450 uppercase tracking-wider">{{ __('Ajouts') }}</span>
                <span class="block text-xl font-extrabold text-slate-800 dark:text-white mt-0.5" x-text="logs.filter(l => l.action === 'created').length"></span>
            </div>
        </div>
        
        <!-- Card 2 -->
        <div class="p-5 bg-white border border-gray-200/60 rounded-2xl shadow-premium hover:shadow-premium-hover transition-all duration-300 dark:bg-[#0D121F] dark:border-slate-800/60 flex items-center gap-x-4">
            <div class="size-11 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-600 dark:bg-blue-500/20">
                <i data-lucide="edit" class="size-5"></i>
            </div>
            <div>
                <span class="block text-xs font-bold text-slate-400 dark:text-neutral-450 uppercase tracking-wider">{{ __('Modifications') }}</span>
                <span class="block text-xl font-extrabold text-slate-800 dark:text-white mt-0.5" x-text="logs.filter(l => l.action === 'updated').length"></span>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="p-5 bg-white border border-gray-200/60 rounded-2xl shadow-premium hover:shadow-premium-hover transition-all duration-300 dark:bg-[#0D121F] dark:border-slate-800/60 flex items-center gap-x-4">
            <div class="size-11 rounded-xl bg-rose-500/10 flex items-center justify-center text-rose-600 dark:bg-rose-500/20">
                <i data-lucide="trash-2" class="size-5"></i>
            </div>
            <div>
                <span class="block text-xs font-bold text-slate-400 dark:text-neutral-450 uppercase tracking-wider">{{ __('Suppressions') }}</span>
                <span class="block text-xl font-extrabold text-slate-800 dark:text-white mt-0.5" x-text="logs.filter(l => l.action === 'deleted').length"></span>
            </div>
        </div>
    </div>

    <!-- Table Container -->
    <div class="flex flex-col bg-white/85 dark:bg-[#0D121F]/90 border border-gray-200/60 dark:border-slate-800/60 rounded-2xl shadow-premium backdrop-blur-md">
        
        <!-- Filters Bar -->
        <div class="px-6 py-5 grid gap-4 md:flex md:justify-between md:items-center border-b border-gray-200/60 dark:border-slate-800/60 bg-white/40 dark:bg-[#0D121F]/40">
            <!-- Search bar -->
            <div class="sm:col-span-1 max-w-sm w-full relative">
                <input x-model="search" type="text" class="py-2.5 px-4 ps-11 block w-full border-gray-200 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 bg-white/50 dark:bg-[#090D16]/50 dark:border-slate-800/80 dark:text-neutral-300 dark:placeholder-neutral-500 transition-all duration-200" placeholder="{{ __('Rechercher par utilisateur, table...') }}">
                <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                    <i data-lucide="search" class="size-4 text-gray-400 dark:text-neutral-500"></i>
                </div>
            </div>

            <!-- Custom Dropdowns Filters -->
            <div class="flex items-center gap-3 relative">
                <!-- Action Type Filter -->
                <div class="relative" @click.outside="openAction = false">
                    <button @click="openAction = !openAction" type="button" class="py-2.5 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-xl border border-gray-200 bg-white/50 hover:bg-gray-50 dark:bg-[#090D16]/50 dark:border-slate-800 dark:text-white dark:hover:bg-slate-900/50 shadow-sm transition-all duration-200">
                        <span x-text="actionSelectionnee === 'all' ? '{{ addslashes(__('Toutes les actions')) }}' : (actionSelectionnee === 'created' ? '{{ addslashes(__('Ajout')) }}' : (actionSelectionnee === 'updated' ? '{{ addslashes(__('Modification')) }}' : '{{ addslashes(__('Suppression')) }}'))" class="truncate max-w-[150px]"></span>
                        <i data-lucide="chevron-down" class="size-4 text-gray-400 transition-transform duration-200" :class="{'rotate-180': openAction}"></i>
                    </button>
                    <div x-show="openAction" x-cloak class="absolute right-0 top-full z-[100] mt-2 w-48 bg-white/95 dark:bg-[#0D121F]/95 border border-gray-200/60 dark:border-slate-800/60 shadow-xl rounded-xl p-1.5 backdrop-blur-md">
                        <div @click="actionSelectionnee = 'all'; openAction = false" class="cursor-pointer py-2 px-3 rounded-lg text-sm text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800/50 transition-colors">{{ __('Toutes les actions') }}</div>
                        <div @click="actionSelectionnee = 'created'; openAction = false" class="cursor-pointer py-2 px-3 rounded-lg text-sm text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800/50 transition-colors">{{ __('Ajout') }}</div>
                        <div @click="actionSelectionnee = 'updated'; openAction = false" class="cursor-pointer py-2 px-3 rounded-lg text-sm text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800/50 transition-colors">{{ __('Modification') }}</div>
                        <div @click="actionSelectionnee = 'deleted'; openAction = false" class="cursor-pointer py-2 px-3 rounded-lg text-sm text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800/50 transition-colors">{{ __('Suppression') }}</div>
                    </div>
                </div>

                <!-- Date Filter -->
                <div class="relative" @click.outside="openDate = false">
                    <button @click="openDate = !openDate" type="button" class="py-2.5 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-xl border border-gray-200 bg-white/50 hover:bg-gray-50 dark:bg-[#090D16]/50 dark:border-slate-800 dark:text-white dark:hover:bg-slate-900/50 shadow-sm transition-all duration-200">
                        <span x-text="dateSelectionnee === 'all' ? '{{ addslashes(__('Toutes les dates')) }}' : (dateSelectionnee === 'today' ? '{{ addslashes(__('Aujourd\'hui')) }}' : '{{ addslashes(__('7 derniers jours')) }}')" class="truncate max-w-[150px]"></span>
                        <i data-lucide="chevron-down" class="size-4 text-gray-400 transition-transform duration-200" :class="{'rotate-180': openDate}"></i>
                    </button>
                    <div x-show="openDate" x-cloak class="absolute right-0 top-full z-[100] mt-2 w-48 bg-white/95 dark:bg-[#0D121F]/95 border border-gray-200/60 dark:border-slate-800/60 shadow-xl rounded-xl p-1.5 backdrop-blur-md">
                        <div @click="dateSelectionnee = 'all'; openDate = false" class="cursor-pointer py-2 px-3 rounded-lg text-sm text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800/50 transition-colors">{{ __('Toutes les dates') }}</div>
                        <div @click="dateSelectionnee = 'today'; openDate = false" class="cursor-pointer py-2 px-3 rounded-lg text-sm text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800/50 transition-colors">{{ __('Aujourd\'hui') }}</div>
                        <div @click="dateSelectionnee = 'week'; openDate = false" class="cursor-pointer py-2 px-3 rounded-lg text-sm text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800/50 transition-colors">{{ __('7 derniers jours') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table View -->
        <div class="overflow-x-auto rounded-b-2xl overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200/60 dark:divide-slate-800/60">
                <thead class="bg-gray-50/50 dark:bg-[#090D16]/40">
                    <tr>
                        <th class="px-6 py-4 text-start text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-slate-400">{{ __('Utilisateur') }}</th>
                        <th class="px-6 py-4 text-start text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-slate-400">{{ __('Action') }}</th>
                        <th class="px-6 py-4 text-start text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-slate-400">{{ __('Modèle / Cible') }}</th>
                        <th class="px-6 py-4 text-start text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-slate-400">{{ __('ID Cible') }}</th>
                        <th class="px-6 py-4 text-start text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-slate-400">{{ __('Date & Heure') }}</th>
                        <th class="px-6 py-4 text-end text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-slate-400">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200/60 dark:divide-slate-800/60">
                    <template x-for="log in filteredLogs" :key="log.id">
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-900/30 transition-colors duration-150">
                            <!-- Utilisateur -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-x-3">
                                    <div class="size-9 rounded-full bg-primary-500/10 dark:bg-primary-500/20 flex items-center justify-center font-bold text-primary-600 text-sm">
                                        <span x-text="log.user_name.charAt(0)"></span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-semibold text-gray-800 dark:text-slate-200" x-text="log.user_name"></span>
                                        <span class="text-[10px] font-bold text-slate-400 dark:text-neutral-450 uppercase tracking-wider mt-0.5">{{ __('Syndic Secondaire') }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Action -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <template x-if="log.action === 'created'">
                                    <span class="inline-flex items-center gap-x-1.5 py-1 px-2.5 rounded-full text-xs font-semibold bg-emerald-5 border border-emerald-100 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400 dark:border-emerald-900/20">
                                        <i data-lucide="plus-circle" class="size-3"></i> Ajout
                                    </span>
                                </template>
                                <template x-if="log.action === 'updated'">
                                    <span class="inline-flex items-center gap-x-1.5 py-1 px-2.5 rounded-full text-xs font-semibold bg-blue-5 border border-blue-100 text-blue-600 dark:bg-blue-950/40 dark:text-blue-400 dark:border-blue-900/20">
                                        <i data-lucide="edit" class="size-3"></i> Modification
                                    </span>
                                </template>
                                <template x-if="log.action === 'deleted'">
                                    <span class="inline-flex items-center gap-x-1.5 py-1 px-2.5 rounded-full text-xs font-semibold bg-rose-5 border border-rose-100 text-rose-600 dark:bg-rose-950/40 dark:text-rose-400 dark:border-rose-900/20">
                                        <i data-lucide="trash-2" class="size-3"></i> Suppression
                                    </span>
                                </template>
                            </td>

                            <!-- Cible / Modèle -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-slate-300 font-semibold" x-text="getFriendlyModel(log.model_type)"></td>

                            <!-- ID Cible -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-slate-400 font-mono" x-text="'#' + log.model_id"></td>

                            <!-- Date & Heure -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-slate-400" x-text="log.created_at"></td>

                            <!-- Actions -->
                            <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                <button type="button" @click="openDetails(log)" class="py-1.5 px-3 inline-flex items-center gap-x-2 text-xs font-semibold rounded-xl border border-gray-200 bg-white hover:bg-gray-50 text-gray-800 dark:bg-slate-800 dark:border-slate-700 dark:text-white dark:hover:bg-slate-700 transition-all duration-200">
                                    <i data-lucide="eye" class="size-3.5"></i> Voir Détails
                                </button>
                            </td>
                        </tr>
                    </template>

                    <!-- Empty state -->
                    <tr x-show="filteredLogs.length === 0">
                        <td colspan="6" class="px-6 py-12 text-center text-sm text-gray-500 dark:text-slate-400">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <i data-lucide="scroll" class="size-10 text-gray-300 dark:text-slate-700"></i>
                                <span class="font-medium">{{ __('Aucun log d\'activité ne correspond à vos filtres.') }}</span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Details Modal (Side-by-side JSON Diff visualizer) -->
    <div x-show="showModal" class="fixed inset-0 z-[100] overflow-y-auto bg-slate-950/40 backdrop-blur-sm flex items-center justify-center p-4" x-cloak style="display: none;" @keydown.escape.window="showModal = false">
        <div class="bg-white border border-gray-200/60 dark:border-slate-800/60 shadow-premium rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden flex flex-col dark:bg-[#0D121F]" @click.outside="showModal = false">
            
            <!-- Modal Header -->
            <div class="flex justify-between items-center py-4 px-5 border-b border-gray-200/60 dark:border-slate-800/60">
                <div>
                    <h3 class="font-bold text-gray-800 dark:text-white text-lg">{{ __('Détails de l\'Action') }}</h3>
                    <p class="text-xs text-slate-400 dark:text-neutral-450 mt-0.5" x-text="'Log ID #' + (selectedLog ? selectedLog.id : '')"></p>
                </div>
                <button type="button" @click="showModal = false" class="size-8 inline-flex justify-center items-center rounded-xl bg-gray-100 text-gray-800 hover:bg-gray-200 dark:bg-slate-800 dark:text-neutral-400 dark:hover:bg-slate-700 transition-colors">
                    <i data-lucide="x" class="size-4"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 overflow-y-auto space-y-4">
                
                <!-- Quick Info Header -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-4 bg-gray-50 border border-gray-150 rounded-xl dark:bg-neutral-900/50 dark:border-neutral-800/60">
                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Acteur</span>
                        <span class="text-sm font-semibold text-slate-800 dark:text-white mt-1 block" x-text="selectedLog ? selectedLog.user_name : ''"></span>
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ __('Type d\'élément') }}</span>
                        <span class="text-sm font-semibold text-slate-800 dark:text-white mt-1 block" x-text="selectedLog ? getFriendlyModel(selectedLog.model_type) : ''"></span>
                    </div>
                </div>

                <!-- Modifications Visual Diff -->
                <div class="space-y-3">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                        <i data-lucide="git-compare" class="size-4 text-primary-500"></i> Modifications effectuées
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Avant (Anciennes valeurs) -->
                        <div class="flex flex-col bg-rose-500/5 border border-rose-100/50 rounded-xl p-4 dark:bg-rose-950/5 dark:border-rose-900/20">
                            <span class="text-xs font-bold text-rose-600 dark:text-rose-400 flex items-center gap-1 mb-2 border-b border-rose-100/60 dark:border-rose-900/10 pb-1.5">
                                <i data-lucide="minus-circle" class="size-3.5"></i> Avant (Valeur d'origine)
                            </span>
                            <div class="space-y-1.5 flex-1 min-h-[80px]">
                                <template x-if="selectedLog && selectedLog.modifications && selectedLog.modifications.before">
                                    <div class="space-y-2">
                                        <template x-for="(val, key) in selectedLog.modifications.before">
                                            <div class="flex flex-col bg-rose-100/20 dark:bg-rose-950/20 py-1.5 px-2.5 rounded-lg text-xs">
                                                <span class="font-bold text-rose-800 dark:text-rose-300 uppercase tracking-wider text-[9px]" x-text="key"></span>
                                                <span class="text-rose-600 dark:text-rose-400 font-mono mt-0.5 break-all" x-text="val !== null ? val : 'NULL'"></span>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                                <template x-if="!selectedLog || !selectedLog.modifications || !selectedLog.modifications.before">
                                    <span class="text-xs text-slate-400 italic block text-center mt-6">{{ __('Aucune valeur précédente (Nouvel élément)') }}</span>
                                </template>
                            </div>
                        </div>

                        <!-- Après (Nouvelles valeurs) -->
                        <div class="flex flex-col bg-emerald-500/5 border border-emerald-100/50 rounded-xl p-4 dark:bg-emerald-950/5 dark:border-emerald-900/20">
                            <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 flex items-center gap-1 mb-2 border-b border-emerald-100/60 dark:border-emerald-900/10 pb-1.5">
                                <i data-lucide="plus-circle" class="size-3.5"></i> Après (Nouvelle valeur)
                            </span>
                            <div class="space-y-1.5 flex-1 min-h-[80px]">
                                <template x-if="selectedLog && selectedLog.modifications && selectedLog.modifications.after">
                                    <div class="space-y-2">
                                        <template x-for="(val, key) in selectedLog.modifications.after">
                                            <div class="flex flex-col bg-emerald-100/20 dark:bg-emerald-950/20 py-1.5 px-2.5 rounded-lg text-xs">
                                                <span class="font-bold text-emerald-800 dark:text-emerald-300 uppercase tracking-wider text-[9px]" x-text="key"></span>
                                                <span class="text-emerald-600 dark:text-emerald-400 font-mono mt-0.5 break-all" x-text="val !== null ? val : 'NULL'"></span>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                                <template x-if="!selectedLog || !selectedLog.modifications || !selectedLog.modifications.after">
                                    <span class="text-xs text-slate-400 italic block text-center mt-6">{{ __('Aucune modification enregistrée') }}</span>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Modal Footer -->
            <div class="flex justify-end items-center gap-x-3 py-3 px-5 border-t border-gray-100 dark:border-slate-800/60">
                <button type="button" @click="showModal = false" class="py-2 px-4 text-sm font-semibold bg-gradient-to-r from-primary-600 to-purple-600 hover:from-primary-700 hover:to-purple-700 text-white rounded-xl shadow-md shadow-primary-500/10 transition-all duration-300">
                    Fermer
                </button>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('syndicLogsComponent', (config) => ({
            logs: config || [],
            search: '',
            actionSelectionnee: 'all',
            dateSelectionnee: 'all',
            openAction: false,
            openDate: false,
            showModal: false,
            selectedLog: null,

            get filteredLogs() {
                return this.logs.filter(log => {
                    // Search term filter
                    const matchSearch = log.user_name.toLowerCase().includes(this.search.toLowerCase()) ||
                                        log.model_type.toLowerCase().includes(this.search.toLowerCase());
                    
                    // Action type filter
                    const matchAction = this.actionSelectionnee === 'all' || log.action === this.actionSelectionnee;

                    // Date filter
                    let matchDate = true;
                    if (this.dateSelectionnee === 'today') {
                        const todayStr = new Date().toDateString();
                        const logDateStr = new Date(log.created_at_raw).toDateString();
                        matchDate = todayStr === logDateStr;
                    } else if (this.dateSelectionnee === 'week') {
                        const sevenDaysAgo = new Date();
                        sevenDaysAgo.setDate(sevenDaysAgo.getDate() - 7);
                        const logDate = new Date(log.created_at_raw);
                        matchDate = logDate >= sevenDaysAgo;
                    }

                    return matchSearch && matchAction && matchDate;
                });
            },

            openDetails(log) {
                this.selectedLog = log;
                this.showModal = true;
            },

            getFriendlyModel(modelType) {
                if (!modelType) return 'N/A';
                const parts = modelType.split('\\');
                const modelName = parts[parts.length - 1];
                
                // Map to friendly French terms
                const mapping = {
                    'Charge': 'Cotisation (Charge)',
                    'Paiement': 'Paiement',
                    'Incident': 'Signalement Incident',
                    'Intervention': 'Intervention',
                    'Annonce': 'Annonce',
                    'User': 'Utilisateur / Résident',
                    'Immeuble': 'Immeuble',
                    'Document': 'Document',
                    'Depense': 'Dépense'
                };

                return mapping[modelName] || modelName;
            }
        }));
    });
</script>
@endpush
