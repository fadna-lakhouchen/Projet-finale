@extends('layouts.app')

@section('content')
<div x-data="{ 
    search: '', 
    immeubleSelectionne: 'all', 
    statutSelectionne: 'all',
    showImm: false,
    showStat: false,
    isEditing: false,
    residentEnCours: { id: '', prenom: '', nom: '', email: '', telephone: '', cin: '', notes: '', role: 'resident', immeuble_id: '', numero_appartement: '', date_entree: '', override_mois_retard: '' },
    init() {
        this.$watch('residentEnCours.immeuble_id', (value) => {
            if (!this.isEditing) {
                this.residentEnCours.numero_appartement = '';
            }
        });
    },
    initAjout() {
        this.isEditing = false;
        this.residentEnCours = { id: '', prenom: '', nom: '', email: '', telephone: '', cin: '', notes: '', role: 'resident', immeuble_id: '', numero_appartement: '', date_entree: '', override_mois_retard: '' };
        if (window.editor) window.editor.commands.setContent('');
    },
    initEdit(id, prenom, nom, email, telephone, cin, immeuble_id, numero_appt, date_e, notes, override_mois_retard) {
        this.isEditing = true;
        this.residentEnCours = { id: id, prenom: prenom, nom: nom, email: email, telephone: telephone, cin: cin, notes: notes, role: 'resident', immeuble_id: immeuble_id, numero_appartement: numero_appt, date_entree: date_e, override_mois_retard: override_mois_retard };
        if (window.editor) window.editor.commands.setContent(notes || '');
    },
    matches(name, immeuble, role) {
        const s = this.search.toLowerCase();
        const matchesSearch = name.toLowerCase().includes(s);
        const matchesImmeuble = this.immeubleSelectionne === 'all' || immeuble === this.immeubleSelectionne;
        const matchesStatut = this.statutSelectionne === 'all' || role === this.statutSelectionne;
        return matchesSearch && matchesImmeuble && matchesStatut;
    }
}">
    <!-- Page Header -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 dark:text-white">Gestion des Résidents</h2>
            <p class="text-sm text-slate-500 dark:text-neutral-400">Administration complète et suivi de tous les locataires et propriétaires.</p>
        </div>
        <button @click="initAjout()" type="button" data-hs-overlay="#hs-modal-add-resident" class="py-2.5 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-bold rounded-xl border border-transparent bg-gradient-to-r from-primary-600 to-purple-600 text-white hover:from-primary-700 hover:to-purple-700 shadow-md shadow-primary-500/15 transition-all glow-hover">
            <i data-lucide="plus" class="size-4.5"></i>
            Ajouter un résident
        </button>
    </div>

    <!-- Table Section Container (Premium Glass Panel) -->
    <div class="flex flex-col border border-gray-200/60 dark:border-slate-800/60 rounded-2xl shadow-premium bg-white dark:bg-[#0D121F] overflow-hidden">
        <!-- Filters Header -->
        <div class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-b border-gray-200/60 dark:border-slate-800/60 bg-slate-50/50 dark:bg-slate-900/30">
            <div class="sm:col-span-1 max-w-sm w-full relative">
                <input x-model="search" type="text" class="py-2.5 px-4 ps-11 block w-full border-gray-200 dark:border-slate-850 dark:bg-[#080B11] dark:text-slate-300 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500" placeholder="Rechercher un résident...">
                <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                    <i data-lucide="search" class="size-4 text-gray-400"></i>
                </div>
            </div>

            <div class="sm:col-span-2 md:grow flex justify-end gap-x-2">
                <!-- Dropdown Immeubles -->
                <div class="relative inline-flex">
                    <button @click="showImm = !showImm; showStat = false" @click.outside="showImm = false" type="button" class="py-2.5 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-xl border border-gray-200/80 bg-white/80 hover:bg-white text-slate-800 shadow-sm dark:bg-neutral-800 dark:border-neutral-700 dark:text-white transition-all">
                        <span x-text="immeubleSelectionne === 'all' ? 'Filtrer par Immeuble' : immeubleSelectionne"></span>
                        <i data-lucide="chevron-down" :class="showImm ? 'rotate-180' : ''" class="size-4 transition-transform text-gray-400"></i>
                    </button>
                    <div x-show="showImm" class="absolute right-0 top-full z-[100] mt-2 w-60 bg-white border border-gray-200 shadow-xl rounded-2xl p-1.5 dark:bg-neutral-900 dark:border-neutral-800" style="display: none;"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100">
                        <div @click="immeubleSelectionne = 'all'; showImm = false" class="cursor-pointer flex items-center py-2 px-3 rounded-xl text-sm font-medium text-slate-700 hover:bg-slate-50 dark:text-neutral-350 dark:hover:bg-neutral-800/60">Tous les immeubles</div>
                        @foreach($immeubles as $immeuble)
                            <div @click="immeubleSelectionne = '{{ $immeuble->nom }}'; showImm = false" class="cursor-pointer flex items-center py-2 px-3 rounded-xl text-sm font-medium text-slate-700 hover:bg-slate-50 dark:text-neutral-350 dark:hover:bg-neutral-800/60">{{ $immeuble->nom }}</div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>

        <!-- Table Grid -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-250 dark:divide-slate-800">
                <thead class="bg-slate-50 dark:bg-slate-900/50">
                    <tr>
                        <th scope="col" class="px-6 py-3.5 text-start text-xs font-bold text-slate-400 uppercase dark:text-neutral-450 tracking-wider">Nom & Contact</th>
                        <th scope="col" class="px-6 py-3.5 text-start text-xs font-bold text-slate-400 uppercase dark:text-neutral-450 tracking-wider">Logement</th>
                        <th scope="col" class="px-6 py-3.5 text-start text-xs font-bold text-slate-400 uppercase dark:text-neutral-450 tracking-wider">État du Compte</th>
                        <th scope="col" class="px-6 py-3.5 text-end text-xs font-bold text-slate-400 uppercase dark:text-neutral-450 tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200/60 dark:divide-slate-800/60">
                    @foreach($residents as $resident)
                    @php
                        $appt = $resident->appartements->first();
                        $immeubleName = $appt ? $appt->immeuble->nom : 'N/A';
                        $immeubleId = $appt ? $appt->immeuble->id : '';
                        $apptNumero = $appt ? $appt->numero : '';
                        $apptInfo = $appt ? 'Appt ' . $appt->numero . ' • Étage ' . $appt->etage : 'Non assigné';
                    @endphp
                    <tr x-show="matches('{{ $resident->prenom }} {{ $resident->nom }}', '{{ $immeubleName }}')" class="hover:bg-slate-50/50 dark:hover:bg-slate-800/10 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-x-3.5">
                                <img class="size-10 rounded-xl ring-2 ring-primary-500/10 shrink-0 object-cover" src="https://ui-avatars.com/api/?name={{ urlencode($resident->prenom . '+' . $resident->nom) }}&background=3b66f5&color=fff&font-size=0.4" alt="Avatar">
                                <div class="grow">
                                    <span class="block text-sm font-bold text-slate-800 dark:text-neutral-250 leading-none mb-1">{{ $resident->prenom }} {{ $resident->nom }}</span>
                                    <span class="inline-flex items-center gap-x-1 text-[11px] text-slate-400 dark:text-neutral-500 font-semibold">
                                        <i data-lucide="mail" class="size-3"></i>{{ $resident->email }}
                                    </span>
                                    @if($resident->telephone)
                                        <span class="block text-[11px] text-slate-400 dark:text-neutral-500 font-semibold mt-0.5">
                                            <i data-lucide="phone" class="inline size-3 me-1"></i>{{ $resident->telephone }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-slate-700 dark:text-neutral-350">{{ $immeubleName }}</span>
                                <span class="text-[11px] text-slate-400 dark:text-neutral-500 font-semibold mt-0.5">{{ $apptInfo }}</span>
                                @if($appt && $appt->pivot->date_entree)
                                    <span class="text-[10px] text-slate-400 dark:text-neutral-500 font-semibold mt-1">
                                        Entrée: {{ \Carbon\Carbon::parse($appt->pivot->date_entree)->format('d/m/Y') }}
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center gap-x-1.5 py-1 px-3 rounded-full text-xs font-bold {{ $resident->is_active ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20' : 'bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20' }}">
                                <span class="size-1 inline-block rounded-full bg-current"></span>
                                {{ $resident->is_active ? 'Actif' : 'Inactif' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                            <div class="inline-flex items-center gap-x-2">
                                <button @click="initEdit('{{ $resident->id }}', '{{ addslashes($resident->prenom) }}', '{{ addslashes($resident->nom) }}', '{{ addslashes($resident->email) }}', '{{ $resident->telephone }}', '{{ $resident->cin ?? '' }}', '{{ $immeubleId }}', '{{ $appt ? $appt->numero : '' }}', '{{ $appt ? $appt->pivot->date_entree : '' }}', '{{ addslashes($resident->notes ?? '') }}', '{{ $appt ? $appt->override_mois_retard : '' }}')" type="button" class="p-2 inline-flex items-center justify-center gap-x-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 dark:bg-slate-800/40 dark:border-slate-800 dark:text-slate-300 dark:hover:bg-slate-800 transition-all">
                                    <i data-lucide="edit-2" class="size-4"></i>
                                </button>
                                <form action="{{ route('admin.residents.destroy', $resident->id) }}" method="POST" onsubmit="return confirm('Confirmer la suppression définitive de ce résident ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 inline-flex items-center justify-center gap-x-2 rounded-xl border border-red-200/40 bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 dark:bg-red-950/20 dark:text-red-400 dark:border-red-950/30 dark:hover:bg-red-950/40 transition-all">
                                        <i data-lucide="trash-2" class="size-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Layout (Optimized Glassmorphism) -->
    <div id="hs-modal-add-resident" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none" role="dialog" tabindex="-1">
        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
            <div class="w-full flex flex-col bg-white border border-gray-200/60 shadow-xl rounded-2xl pointer-events-auto dark:bg-slate-900 dark:border-slate-800/60 backdrop-blur-xl">
                <div class="flex justify-between items-center py-4 px-6 border-b border-gray-200/50 dark:border-slate-800/50">
                    <h3 class="font-bold text-slate-800 dark:text-white" x-text="isEditing ? 'Modifier le Résident' : 'Enregistrer un nouveau Résident'"></h3>
                    <button type="button" class="size-8 inline-flex justify-center items-center rounded-xl bg-slate-100 text-slate-800 hover:bg-slate-200 focus:outline-none dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-neutral-400" data-hs-overlay="#hs-modal-add-resident">
                        <i data-lucide="x" class="size-4"></i>
                    </button>
                </div>
                <div class="p-6 overflow-y-auto max-h-[75vh]">
                    <form :action="isEditing ? `/admin/residents/${residentEnCours.id}` : '{{ route('admin.residents.store') }}'" method="POST">
                        @csrf
                        <template x-if="isEditing">
                            <input type="hidden" name="_method" value="PUT">
                        </template>
                        <input type="hidden" name="id" x-model="residentEnCours.id">

                        <div class="grid gap-y-5">
                            <div class="grid sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold mb-2 dark:text-white">Prénom</label>
                                    <input name="prenom" x-model="residentEnCours.prenom" type="text" class="py-2.5 px-4 block w-full border-gray-200 dark:border-slate-850 dark:bg-[#080B11] dark:text-slate-300 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold mb-2 dark:text-white">Nom</label>
                                    <input name="nom" x-model="residentEnCours.nom" type="text" class="py-2.5 px-4 block w-full border-gray-200 dark:border-slate-850 dark:bg-[#080B11] dark:text-slate-300 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500" required>
                                </div>
                            </div>
                            <div class="grid sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold mb-2 dark:text-white">Email (Connexion)</label>
                                    <input name="email" x-model="residentEnCours.email" type="email" class="py-2.5 px-4 block w-full border-gray-200 dark:border-slate-850 dark:bg-[#080B11] dark:text-slate-300 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold mb-2 dark:text-white">Téléphone</label>
                                    <input name="telephone" x-model="residentEnCours.telephone" type="text" class="py-2.5 px-4 block w-full border-gray-200 dark:border-slate-850 dark:bg-[#080B11] dark:text-slate-300 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500" placeholder="Ex: 0600000000">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-2 dark:text-white">CIN</label>
                                <input name="cin" x-model="residentEnCours.cin" type="text" class="py-2.5 px-4 block w-full border-gray-200 dark:border-slate-850 dark:bg-[#080B11] dark:text-slate-300 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500" placeholder="Ex: AB123456">
                            </div>
                            <div class="grid sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold mb-2 dark:text-white">Immeuble</label>
                                    <div class="relative" x-data="{ open: false }">
                                        <button @click="open = !open" @click.outside="open = false" type="button" class="py-2.5 px-4 flex justify-between items-center w-full border-gray-200 dark:border-slate-850 dark:bg-[#080B11] dark:text-slate-300 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500 text-left">
                                            <span x-text="residentEnCours.immeuble_id ? ({ @foreach($immeubles as $imm) '{{ $imm->id }}': '{{ addslashes($imm->nom) }}', @endforeach }[residentEnCours.immeuble_id] || 'Sélectionner l\'immeuble') : 'Sélectionner l\'immeuble'"></span>
                                            <i data-lucide="chevron-down" class="size-4 text-gray-400 transition-transform duration-200" :class="{'rotate-180': open}"></i>
                                        </button>
                                        <input type="hidden" name="immeuble_id" :value="residentEnCours.immeuble_id">
                                        <div x-show="open" x-cloak class="absolute left-0 top-full z-[100] mt-2 w-full max-h-60 overflow-y-auto bg-white dark:bg-[#0D121F] border border-gray-200/60 dark:border-slate-800/60 shadow-xl rounded-xl p-1.5 backdrop-blur-md" style="display: none;">
                                            @foreach($immeubles as $immeuble)
                                                <div @click="residentEnCours.immeuble_id = '{{ $immeuble->id }}'; open = false" class="cursor-pointer py-2 px-3 rounded-lg text-sm text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800/50 transition-colors">{{ $immeuble->nom }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold mb-2 dark:text-white">Numéro d'appartement</label>
                                    <input name="numero_appartement" x-model="residentEnCours.numero_appartement" type="text" class="py-2.5 px-4 block w-full border-gray-200 dark:border-slate-850 dark:bg-[#080B11] dark:text-slate-300 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500" placeholder="Ex: 5, 12B..." required>
                                </div>
                            </div>
                            <div class="grid sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold mb-2 dark:text-white">Date d'entrée</label>
                                    <input name="date_entree" x-model="residentEnCours.date_entree" type="date" class="py-2.5 px-4 block w-full border-gray-200 dark:border-slate-850 dark:bg-[#080B11] dark:text-slate-300 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold mb-2 dark:text-white">Surcharge mois de retard</label>
                                    <input name="override_mois_retard" x-model="residentEnCours.override_mois_retard" type="number" min="0" class="py-2.5 px-4 block w-full border-gray-200 dark:border-slate-850 dark:bg-[#080B11] dark:text-slate-300 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500" placeholder="Automatique (laisser vide)">
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-end items-center gap-x-3 mt-8 border-t border-slate-100 dark:border-slate-800 pt-5">
                            <button type="button" class="py-2.5 px-4 text-sm font-semibold border rounded-xl border-slate-200 hover:bg-slate-50 dark:border-slate-800 dark:hover:bg-slate-800 dark:text-white transition-colors" data-hs-overlay="#hs-modal-add-resident">Annuler</button>
                            <button type="submit" class="py-2.5 px-4 text-sm font-bold bg-primary-600 text-white rounded-xl hover:bg-primary-700 shadow-md shadow-primary-500/10 transition-colors" x-text="isEditing ? 'Enregistrer les modifications' : 'Enregistrer'"></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
