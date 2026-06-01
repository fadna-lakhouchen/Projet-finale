@extends('layouts.auth')

@section('content')
<main class="w-full max-w-md mx-auto p-4">
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
        <div class="p-4 sm:p-7">
            <div class="text-center">
                <div class="flex justify-center mb-5">
                    <img src="{{ asset('logo.png') }}" alt="Logo ImmoSyndic" class="h-20 w-auto object-contain">
                </div>
                <h1 class="block text-2xl font-bold text-gray-800 dark:text-white">Créer un compte</h1>
                <p class="mt-2 text-sm text-gray-600 dark:text-neutral-400">
                    Vous avez déjà un compte ?
                    <a class="text-primary-600 decoration-2 hover:underline font-medium dark:text-primary-400" href="{{ route('login') }}">
                        Se connecter
                    </a>
                </p>
            </div>

            <div class="mt-5">
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="grid gap-y-4">

                        {{-- Prénom + Nom --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="prenom" class="block text-sm mb-2 dark:text-white">Prénom</label>
                                <div class="relative">
                                    <input type="text" id="prenom" name="prenom"
                                        class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600 @error('prenom') border-red-500 @enderror"
                                        required value="{{ old('prenom') }}" autofocus>
                                    @error('prenom')
                                    <div class="absolute inset-y-0 end-0 flex items-center pointer-events-none pe-3">
                                        <i data-lucide="alert-circle" class="size-5 text-red-500"></i>
                                    </div>
                                    @enderror
                                </div>
                                @error('prenom')
                                    <p class="text-xs text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="nom" class="block text-sm mb-2 dark:text-white">Nom</label>
                                <div class="relative">
                                    <input type="text" id="nom" name="nom"
                                        class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600 @error('nom') border-red-500 @enderror"
                                        required value="{{ old('nom') }}">
                                    @error('nom')
                                    <div class="absolute inset-y-0 end-0 flex items-center pointer-events-none pe-3">
                                        <i data-lucide="alert-circle" class="size-5 text-red-500"></i>
                                    </div>
                                    @enderror
                                </div>
                                @error('nom')
                                    <p class="text-xs text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Email with real-time validation --}}
                        <div x-data="{
                            email: '{{ old('email') }}',
                            isValid: true,
                            isChecking: false,
                            isTaken: false,
                            feedbackMessage: '',
                            
                            checkEmail() {
                                if (!this.email) {
                                    this.isValid = true;
                                    this.isTaken = false;
                                    this.feedbackMessage = '';
                                    return;
                                }
                                
                                // Regex pattern validation
                                const pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                                if (!pattern.test(this.email)) {
                                    this.isValid = false;
                                    this.isTaken = false;
                                    this.feedbackMessage = 'Format d\'adresse email invalide.';
                                    return;
                                }
                                
                                this.isValid = true;
                                this.isChecking = true;
                                this.feedbackMessage = '';
                                
                                fetch('/register/check-email?email=' + encodeURIComponent(this.email))
                                    .then(response => response.json())
                                    .then(data => {
                                        this.isChecking = false;
                                        if (!data.valid) {
                                            this.isValid = false;
                                            this.isTaken = false;
                                            this.feedbackMessage = data.message;
                                        } else if (data.exists) {
                                            this.isValid = true;
                                            this.isTaken = true;
                                            this.feedbackMessage = data.message;
                                        } else {
                                            this.isValid = true;
                                            this.isTaken = false;
                                            this.feedbackMessage = data.message;
                                        }
                                    })
                                    .catch(() => {
                                        this.isChecking = false;
                                    });
                            }
                        }">
                            <label for="email" class="block text-sm mb-2 dark:text-white">Adresse email</label>
                            <div class="relative">
                                <input type="email" id="email" name="email" x-model="email" @input.debounce.500ms="checkEmail"
                                    class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600"
                                    :class="!isValid || isTaken ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : (email && !isTaken && isValid && !isChecking ? 'border-emerald-500 focus:border-emerald-500 focus:ring-emerald-500' : 'border-gray-200')"
                                    required>
                                
                                <!-- Loading / Feedback icons -->
                                <div class="absolute inset-y-0 end-0 flex items-center pe-3 pointer-events-none">
                                    <template x-if="isChecking">
                                        <svg class="animate-spin size-4.5 text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </template>
                                    <template x-if="!isChecking && email && isTaken">
                                        <i data-lucide="alert-circle" class="size-5 text-red-500"></i>
                                    </template>
                                    <template x-if="!isChecking && email && !isTaken && isValid">
                                        <i data-lucide="check-circle" class="size-5 text-emerald-500"></i>
                                    </template>
                                </div>
                            </div>
                            
                            <!-- Real-time dynamic helper text -->
                            <p x-show="feedbackMessage" x-text="feedbackMessage" class="text-xs mt-2" 
                               :class="!isValid || isTaken ? 'text-red-600 dark:text-red-400' : 'text-emerald-600 dark:text-emerald-400'"></p>
                               
                            @error('email')
                                <p class="text-xs text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Mot de passe --}}
                        <div>
                            <label for="password" class="block text-sm mb-2 dark:text-white">Mot de passe</label>
                            <div class="relative">
                                <input type="password" id="password" name="password"
                                    class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600 @error('password') border-red-500 @enderror"
                                    required autocomplete="new-password">
                                @error('password')
                                <div class="absolute inset-y-0 end-0 flex items-center pointer-events-none pe-3">
                                    <i data-lucide="alert-circle" class="size-5 text-red-500"></i>
                                </div>
                                @enderror
                            </div>
                            @error('password')
                                <p class="text-xs text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Confirmer le mot de passe --}}
                        <div>
                            <label for="password-confirm" class="block text-sm mb-2 dark:text-white">Confirmer le mot de passe</label>
                            <div class="relative">
                                <input type="password" id="password-confirm" name="password_confirmation"
                                    class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600"
                                    required autocomplete="new-password">
                            </div>
                        </div>

                        {{-- Section Immeuble (Style Dropdown w Option Switch) --}}
                        <div x-data='{
                            isAddingCustomImmeuble: false,
                            selectedImmeubleId: "",
                            selectedImmeubleNom: "",
                            selectedImmeubleVille: "",
                            immeubleSearch: "",
                            openImmeubleDropdown: false,
                            immeubles: {!! json_encode($immeubles ?? [], JSON_HEX_APOS) !!},
                            
                            get filteredImmeubles() {
                                if (!this.immeubleSearch) return this.immeubles;
                                return this.immeubles.filter(i => i.nom.toLowerCase().includes(this.immeubleSearch.toLowerCase()));
                            },
                            
                            selectImmeuble(id, nom, ville) {
                                this.selectedImmeubleId = id;
                                this.selectedImmeubleNom = nom;
                                this.selectedImmeubleVille = ville;
                                this.openImmeubleDropdown = false;
                                this.isAddingCustomImmeuble = false;
                            },
                            
                            switchToCustom() {
                                this.isAddingCustomImmeuble = true;
                                this.selectedImmeubleId = "";
                                this.selectedImmeubleNom = "";
                                this.selectedImmeubleVille = "";
                                this.openImmeubleDropdown = false;
                            },
                            
                            switchToDropdown() {
                                this.isAddingCustomImmeuble = false;
                                this.selectedImmeubleId = "";
                                this.selectedImmeubleNom = "";
                                this.selectedImmeubleVille = "";
                            }
                        }' class="mt-4 p-4 bg-gray-50 border border-gray-200 rounded-xl dark:bg-neutral-900/50 dark:border-neutral-700/60">
                            
                            <h3 class="text-sm font-bold text-slate-800 dark:text-white mb-4 flex items-center gap-2">
                                <i data-lucide="building" class="size-4.5 text-primary-500"></i>
                                Informations de l'immeuble
                            </h3>

                            <input type="hidden" name="immeuble_id" :value="selectedImmeubleId">
                            <input type="hidden" name="immeuble_type" :value="isAddingCustomImmeuble ? 'new' : 'existing'">

                            <!-- Cas 1: Sélectionner un immeuble existant (Dropdown style) -->
                            <div x-show="!isAddingCustomImmeuble" class="relative" @click.outside="openImmeubleDropdown = false">
                                <label class="block text-sm mb-2 dark:text-white">Nom de l'immeuble</label>
                                
                                <!-- Trigger button of dropdown -->
                                <button @click="openImmeubleDropdown = !openImmeubleDropdown" 
                                        type="button" 
                                        class="py-2.5 px-4 flex items-center justify-between w-full bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-750 dark:text-slate-300 rounded-xl text-sm transition-all duration-200 focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 shadow-sm">
                                    <span x-text="selectedImmeubleNom ? selectedImmeubleNom : '-- Sélectionner un immeuble --'" class="truncate"></span>
                                    <i data-lucide="chevron-down" class="size-4 text-gray-400 transition-transform duration-200" :class="openImmeubleDropdown ? 'rotate-180' : ''"></i>
                                </button>

                                <!-- Dropdown panel list -->
                                <div x-show="openImmeubleDropdown" 
                                     style="display: none;" 
                                     class="absolute z-50 w-full mt-2 bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-750 rounded-xl shadow-lg p-2 max-h-64 overflow-y-auto">
                                    
                                    <!-- Quick Search Input inside dropdown -->
                                    <div class="px-2 py-1.5 border-b border-gray-100 dark:border-neutral-800 mb-1 flex items-center gap-2">
                                        <i data-lucide="search" class="size-3.5 text-gray-400 shrink-0"></i>
                                        <input type="text" 
                                               x-model="immeubleSearch" 
                                               @click.stop 
                                               placeholder="Rechercher..." 
                                               class="w-full text-xs bg-transparent border-0 p-0 focus:ring-0 focus:outline-none dark:text-white">
                                    </div>

                                    <!-- List items -->
                                    <div class="space-y-0.5">
                                        <template x-for="immeuble in filteredImmeubles" :key="immeuble.id">
                                            <button type="button" 
                                                    @click="selectImmeuble(immeuble.id, immeuble.nom, immeuble.ville)"
                                                    class="w-full text-left py-2 px-3 rounded-lg text-sm text-slate-700 hover:bg-primary-50 hover:text-primary-600 dark:text-neutral-350 dark:hover:bg-neutral-800 transition-colors flex items-center justify-between"
                                                    :class="selectedImmeubleId === immeuble.id ? 'bg-primary-50/70 text-primary-600 dark:bg-neutral-800' : ''">
                                                <span x-text="immeuble.nom" class="font-medium"></span>
                                                <span class="text-xs text-slate-400" x-text="immeuble.ville"></span>
                                            </button>
                                        </template>

                                        <!-- No results -->
                                        <div x-show="filteredImmeubles.length === 0" class="py-3 px-3 text-center text-xs text-slate-400">
                                            Aucun immeuble trouvé.
                                        </div>

                                        <!-- Separator -->
                                        <hr class="my-1 border-gray-100 dark:border-neutral-800">

                                        <!-- Option: Add new building -->
                                        <button type="button" 
                                                @click="switchToCustom()"
                                                class="w-full text-left py-2.5 px-3 rounded-lg text-xs font-bold text-primary-600 hover:bg-primary-50 dark:text-primary-400 dark:hover:bg-neutral-800/60 transition-colors flex items-center gap-2">
                                            <i data-lucide="plus" class="size-4 shrink-0"></i>
                                            Autre immeuble...
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Cas 2: Saisie manuelle du nouvel immeuble (Text Input + Retour Arrow on left) -->
                            <div x-show="isAddingCustomImmeuble" style="display: none;" class="space-y-4">
                                <!-- Nom du nouvel immeuble avec flèche de retour à gauche -->
                                <div>
                                    <label for="immeuble_nom" class="block text-sm mb-2 dark:text-white">Nom de l'immeuble</label>
                                    <div class="relative">
                                        <!-- Flèche retour à gauche -->
                                        <button type="button" 
                                                @click="switchToDropdown()" 
                                                class="absolute inset-y-0 start-0 flex items-center ps-3 text-gray-400 hover:text-gray-600 dark:hover:text-white transition-colors"
                                                title="Retour à la liste">
                                            <i data-lucide="arrow-left" class="size-5"></i>
                                        </button>
                                        
                                        <input type="text" 
                                               id="immeuble_nom" 
                                               name="immeuble_nom" 
                                               x-model="selectedImmeubleNom"
                                               :required="isAddingCustomImmeuble"
                                               class="py-3 ps-10 pe-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600"
                                               placeholder="Ex: Résidence Al Akhawayn">
                                    </div>
                                </div>

                                <!-- Ville du nouvel immeuble -->
                                <div>
                                    <label for="immeuble_ville" class="block text-sm mb-2 dark:text-white">Ville de l'immeuble</label>
                                    <input type="text" 
                                           id="immeuble_ville" 
                                           name="immeuble_ville" 
                                           x-model="selectedImmeubleVille"
                                           :required="isAddingCustomImmeuble"
                                           class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600"
                                           placeholder="Ex: Casablanca">
                                </div>
                            </div>
                        </div>

                        {{-- Submit --}}
                        <button type="submit" class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-primary-600 text-white hover:bg-primary-700 disabled:opacity-50 disabled:pointer-events-none">
                            Créer mon compte
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection
