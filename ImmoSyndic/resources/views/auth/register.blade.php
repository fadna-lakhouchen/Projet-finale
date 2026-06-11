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

            <div class="mt-5" x-data='{
                role: "{{ old("role", "syndic") }}",
                roleSelected: {{ $errors->any() ? "true" : "false" }},
                
                immeubles: {!! json_encode($immeubles ?? [], JSON_HEX_APOS) !!},
                
                allMoroccanCities: [
                    "Agadir", "Al Hoceïma", "Asilah", "Azrou", "Béni Mellal", "Berkane", "Berrechid", 
                    "Bouznika", "Casablanca", "Chefchaouen", "Dakhla", "El Jadida", "Errachidia", 
                    "Essaouira", "Fès", "Fnideq", "Guelmim", "Guercif", "Ifrane", "Kénitra", 
                    "Khouribga", "Laâyoune", "Larache", "Marrakech", "Meknès", "Mohammedia", 
                    "Nador", "Ouarzazate", "Oujda", "Rabat", "Safi", "Salé", "Sefrou", "Settat", 
                    "Sidi Kacem", "Sidi Slimane", "Skhirat", "Tanger", "Taroudant", "Taza", 
                    "Témara", "Tétouan", "Tiznit"
                ],
                selectedCity: "",
                isAddingCustomCity: false,
                customCity: "",
                openCityDropdown: false,
                citySearchQuery: "",
                
                isAddingCustomImmeuble: false,
                selectedImmeubleId: "{{ old("immeuble_id", "") }}",
                selectedImmeubleNom: "{{ old("immeuble_nom", "") }}",
                immeubleSearch: "{{ old("immeuble_nom", "") }}",
                openImmeubleDropdown: false,

                init() {
                    // Fetch Moroccan cities from CDN
                    fetch("https://unpkg.com/list-of-moroccan-cities/json/ville.json")
                        .then(r => r.json())
                        .then(data => {
                            if (Array.isArray(data)) {
                                const fetched = data.map(item => item.ville).filter(v => v);
                                const merged = [...new Set([...this.allMoroccanCities, ...fetched])];
                                this.allMoroccanCities = merged.sort((a, b) => a.localeCompare(b, "fr", { sensitivity: "base" }));
                            }
                        })
                        .catch(err => console.error("Erreur chargement villes:", err));

                    let oldCity = "{{ old("immeuble_ville", "") }}";
                    if (oldCity) {
                        let uniqueCities = [...new Set(this.immeubles.map(i => i.ville))];
                        if (uniqueCities.includes(oldCity)) {
                            this.selectedCity = oldCity;
                            this.isAddingCustomCity = false;
                        } else {
                            this.isAddingCustomCity = true;
                            this.customCity = oldCity;
                            this.selectedCity = oldCity;
                        }
                    }
                    
                    let oldImmeubleId = "{{ old("immeuble_id", "") }}";
                    if (oldImmeubleId) {
                        let matching = this.immeubles.find(i => i.id == oldImmeubleId);
                        if (matching) {
                            this.selectedImmeubleId = matching.id;
                            this.selectedImmeubleNom = matching.nom;
                            this.immeubleSearch = matching.nom;
                            this.isAddingCustomImmeuble = false;
                            if (matching.ville) {
                                this.selectedCity = matching.ville;
                            }
                        }
                    } else {
                        let oldImmeubleNom = "{{ old("immeuble_nom", "") }}";
                        if (oldImmeubleNom) {
                            this.immeubleSearch = oldImmeubleNom;
                            this.isAddingCustomImmeuble = true;
                        } else {
                            if (this.selectedCity) {
                                let currentCity = this.isAddingCustomCity ? this.customCity : this.selectedCity;
                                let buildingsInCity = this.immeubles.filter(i => i.ville && i.ville.toLowerCase() === currentCity.toLowerCase());
                                this.isAddingCustomImmeuble = (buildingsInCity.length === 0);
                            }
                        }
                    }

                    this.$watch("role", value => {
                        this.selectedCity = "";
                        this.isAddingCustomCity = false;
                        this.customCity = "";
                        this.openCityDropdown = false;
                        this.citySearchQuery = "";
                        this.isAddingCustomImmeuble = false;
                        this.selectedImmeubleId = "";
                        this.selectedImmeubleNom = "";
                        this.immeubleSearch = "";
                    });
                },
                
                get filteredCities() {
                    if (!this.citySearchQuery) return this.allMoroccanCities;
                    const q = this.citySearchQuery.toLowerCase();
                    return this.allMoroccanCities.filter(c => c.toLowerCase().includes(q));
                },
                
                get hasBuildingsInSelectedCity() {
                    let currentCity = this.isAddingCustomCity ? this.customCity : this.selectedCity;
                    if (!currentCity) return false;
                    const cityLower = currentCity.toLowerCase();
                    return this.immeubles.some(i => i.ville && i.ville.toLowerCase() === cityLower);
                },
                
                get filteredImmeublesInCity() {
                    let currentCity = this.isAddingCustomCity ? this.customCity : this.selectedCity;
                    if (!currentCity) return [];
                    const cityNormalized = currentCity.toLowerCase();
                    const searchNormalized = this.immeubleSearch.toLowerCase();
                    
                    return this.immeubles.filter(i => 
                        i.ville && i.ville.toLowerCase() === cityNormalized && 
                        i.nom.toLowerCase().includes(searchNormalized)
                    );
                },

                get filteredImmeubles() {
                    if (!this.immeubleSearch) return this.immeubles;
                    return this.immeubles.filter(i => i.nom.toLowerCase().includes(this.immeubleSearch.toLowerCase()));
                },
                
                selectCity(city) {
                    if (city === "custom") {
                        this.isAddingCustomCity = true;
                        this.selectedCity = "";
                        this.customCity = "";
                        this.isAddingCustomImmeuble = true;
                    } else {
                        this.isAddingCustomCity = false;
                        this.selectedCity = city;
                        this.customCity = "";
                        
                        let buildingsInCity = this.immeubles.filter(i => 
                            i.ville && i.ville.toLowerCase() === city.toLowerCase()
                        );
                        this.isAddingCustomImmeuble = (buildingsInCity.length === 0);
                    }
                    this.selectedImmeubleId = "";
                    this.selectedImmeubleNom = "";
                    this.immeubleSearch = "";
                },
                
                selectImmeuble(id, nom) {
                    this.selectedImmeubleId = id;
                    this.selectedImmeubleNom = nom;
                    this.immeubleSearch = nom;
                    this.openImmeubleDropdown = false;
                    this.isAddingCustomImmeuble = false;
                }
            }'>
                <!-- Role Selection Choice Screen -->
                <div x-show="!roleSelected" class="space-y-6">
                    <h2 class="text-sm font-bold text-center text-gray-500 dark:text-neutral-400">Choisissez le type de compte à créer</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Card: Syndic -->
                        <button type="button" @click="role = 'syndic'; roleSelected = true" class="flex flex-col items-center justify-center p-6 text-center bg-gray-50 hover:bg-primary-50/20 dark:bg-neutral-900/50 dark:hover:bg-neutral-800/40 border border-gray-200 dark:border-neutral-750 rounded-2xl shadow-sm hover:shadow-md hover:border-primary-500 transition-all duration-200">
                            <div class="p-3 bg-primary-50 dark:bg-primary-950/40 rounded-xl mb-4 text-primary-600 dark:text-primary-400">
                                <i data-lucide="building" class="size-8"></i>
                            </div>
                            <h3 class="text-base font-bold text-gray-800 dark:text-white mb-1">Syndic</h3>
                            <p class="text-xs text-gray-500 dark:text-neutral-400 leading-relaxed">Je gère un ou plusieurs immeubles (professionnel ou bénévole).</p>
                        </button>

                        <!-- Card: Resident -->
                        <button type="button" @click="role = 'resident'; roleSelected = true" class="flex flex-col items-center justify-center p-6 text-center bg-gray-50 hover:bg-purple-50/20 dark:bg-neutral-900/50 dark:hover:bg-neutral-800/40 border border-gray-200 dark:border-neutral-750 rounded-2xl shadow-sm hover:shadow-md hover:border-purple-500 transition-all duration-200">
                            <div class="p-3 bg-purple-50 dark:bg-purple-950/40 rounded-xl mb-4 text-purple-600 dark:text-purple-400">
                                <i data-lucide="users" class="size-8"></i>
                            </div>
                            <h3 class="text-base font-bold text-gray-800 dark:text-white mb-1">Résident</h3>
                            <p class="text-xs text-gray-500 dark:text-neutral-400 leading-relaxed">Je suis copropriétaire ou locataire dans un immeuble.</p>
                        </button>
                    </div>
                </div>

                <form x-show="roleSelected" style="display: none;" method="POST" action="{{ route('register') }}">
                    @csrf
                    <input type="hidden" name="role" :value="role">

                    <!-- Back Button + Role Selector Header -->
                    <div class="flex items-center justify-between mb-6 gap-x-4">
                        <button type="button" @click="roleSelected = false" class="py-1.5 px-3 inline-flex items-center gap-x-1.5 text-xs font-semibold rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 dark:bg-neutral-900 dark:border-neutral-750 dark:text-neutral-300 dark:hover:bg-neutral-800" title="Retour au choix du rôle">
                            <i data-lucide="arrow-left" class="size-4"></i>
                            <span>Rôles</span>
                        </button>
                        
                        <div class="flex bg-gray-100 dark:bg-neutral-900 p-1 rounded-xl w-full max-w-[200px]">
                            <button type="button" @click="role = 'syndic'" class="flex-1 py-1.5 text-xs font-bold rounded-lg transition-all" :class="role === 'syndic' ? 'bg-white dark:bg-neutral-800 text-primary-600 dark:text-white shadow-sm' : 'text-gray-400 dark:text-neutral-500 hover:text-gray-650'">
                                Syndic
                            </button>
                            <button type="button" @click="role = 'resident'" class="flex-1 py-1.5 text-xs font-bold rounded-lg transition-all" :class="role === 'resident' ? 'bg-white dark:bg-neutral-800 text-purple-600 dark:text-white shadow-sm' : 'text-gray-400 dark:text-neutral-500 hover:text-gray-650'">
                                Résident
                            </button>
                        </div>
                    </div>

                    <div class="grid gap-y-4">
                        <!-- ==================== SYNDIC FORM LAYOUT ==================== -->
                        <div x-show="role === 'syndic'" class="space-y-4">
                            
                            <!-- A. Building Info (FIRST) -->
                            <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl dark:bg-neutral-900/50 dark:border-neutral-700/60 space-y-4">
                                <h3 class="text-sm font-bold text-slate-800 dark:text-white mb-2 flex items-center gap-2">
                                    <i data-lucide="building" class="size-4.5 text-primary-500"></i>
                                    Association de l'immeuble
                                </h3>
                                
                                <input type="hidden" name="immeuble_type" :value="isAddingCustomImmeuble ? 'new' : 'existing'" :disabled="role !== 'syndic'">
                                <input type="hidden" name="immeuble_id" :value="selectedImmeubleId" :disabled="role !== 'syndic' || isAddingCustomImmeuble">
                                <input type="hidden" name="immeuble_ville" :value="isAddingCustomCity ? customCity : selectedCity" :disabled="role !== 'syndic'">

                                <!-- City Select Dropdown -->
                                <div>
                                    <label class="block text-sm mb-2 dark:text-white">Ville de l'immeuble</label>
                                    <div class="space-y-3 relative" @click.outside="openCityDropdown = false">
                                        <!-- Custom Button Dropdown (matching resident building selector styling) -->
                                        <button @click="openCityDropdown = !openCityDropdown" 
                                                type="button" 
                                                :disabled="role !== 'syndic'"
                                                class="py-2.5 px-4 flex items-center justify-between w-full bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-750 dark:text-slate-300 rounded-xl text-sm transition-all focus:outline-none focus:border-primary-500 shadow-sm disabled:opacity-50 disabled:pointer-events-none">
                                            <span x-text="isAddingCustomCity ? (customCity ? customCity : 'Autre ville...') : (selectedCity ? selectedCity : '-- Sélectionner une ville --')" class="truncate"></span>
                                            <i data-lucide="chevron-down" class="size-4 text-gray-400 transition-transform duration-200" :class="openCityDropdown ? 'rotate-180' : ''"></i>
                                        </button>

                                        <!-- Dropdown Menu -->
                                        <div x-show="openCityDropdown" 
                                             style="display: none;" 
                                             class="absolute z-50 w-full mt-2 bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-750 rounded-xl shadow-lg p-2 max-h-60 overflow-y-auto">
                                            <!-- Search Field inside Dropdown -->
                                            <div class="px-2 py-1.5 border-b border-gray-100 dark:border-neutral-800 mb-1 flex items-center gap-2">
                                                <svg class="size-3.5 text-gray-400 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                                </svg>
                                                <input type="text" 
                                                       x-model="citySearchQuery" 
                                                       @click.stop 
                                                       placeholder="Rechercher une ville..." 
                                                       class="w-full text-xs bg-transparent border-0 p-0 focus:ring-0 focus:outline-none dark:text-white">
                                            </div>

                                            <div class="space-y-0.5">
                                                <template x-for="city in filteredCities" :key="city">
                                                    <button type="button" 
                                                            @click="selectCity(city); openCityDropdown = false; citySearchQuery = ''" 
                                                            class="w-full text-left py-2 px-3 rounded-lg text-sm text-slate-700 hover:bg-primary-50 hover:text-primary-600 dark:text-neutral-350 dark:hover:bg-neutral-800 transition-colors flex items-center justify-between"
                                                            :class="selectedCity === city && !isAddingCustomCity ? 'bg-primary-50/70 text-primary-600 dark:bg-neutral-800' : ''">
                                                        <span x-text="city" class="font-medium"></span>
                                                        <svg x-show="selectedCity === city && !isAddingCustomCity" class="size-4 text-primary-600 dark:text-primary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </button>
                                                </template>
                                                
                                                <div x-show="filteredCities.length === 0" class="py-3 px-3 text-center text-xs text-slate-400">Aucune ville trouvée.</div>
                                                
                                                <hr class="my-1 border-gray-100 dark:border-neutral-800">
                                                <button type="button" 
                                                        @click="selectCity('custom'); openCityDropdown = false; citySearchQuery = ''" 
                                                        class="w-full text-left py-2 px-3 rounded-lg text-xs font-bold text-primary-600 hover:bg-primary-50 dark:text-primary-400 dark:hover:bg-neutral-800/60 transition-colors flex items-center gap-2"
                                                        :class="isAddingCustomCity ? 'bg-primary-50/70 dark:bg-neutral-800' : ''">
                                                    <svg class="size-4 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                                    </svg>
                                                    Ajouter une autre ville...
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <!-- Custom City Input -->
                                        <div x-show="isAddingCustomCity || allMoroccanCities.length === 0" style="display: none;">
                                            <label class="block text-xs mb-1 text-gray-500 dark:text-neutral-400">Nom de la nouvelle ville</label>
                                            <input type="text" 
                                                   x-model="customCity" 
                                                   @input="isAddingCustomImmeuble = true; selectedImmeubleId = ''; selectedImmeubleNom = '';"
                                                   :disabled="role !== 'syndic'"
                                                   placeholder="Ex: Casablanca, Marrakech..." 
                                                   class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
                                        </div>
                                    </div>
                                    @error('immeuble_ville') <p class="text-xs text-red-600 mt-2">{{ $message }}</p> @enderror
                                </div>

                                <!-- Building Selection/Creation Section -->
                                <div x-show="isAddingCustomCity ? customCity.length > 0 : selectedCity.length > 0" 
                                     style="display: none;" 
                                     class="space-y-4">
                                    
                                    <!-- STAGE 1: Dropdown Selection of Existing Buildings -->
                                    <div x-show="!isAddingCustomImmeuble" class="relative" @click.outside="openImmeubleDropdown = false">
                                        <label class="block text-sm mb-2 dark:text-white">Immeuble</label>
                                        
                                        <button @click="openImmeubleDropdown = !openImmeubleDropdown" 
                                                type="button" 
                                                :disabled="role !== 'syndic'"
                                                class="py-2.5 px-4 flex items-center justify-between w-full bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-750 dark:text-slate-300 rounded-xl text-sm transition-all focus:outline-none focus:border-primary-500 shadow-sm disabled:opacity-50 disabled:pointer-events-none">
                                            <span x-text="selectedImmeubleNom ? selectedImmeubleNom : '-- Sélectionner un immeuble existant --'" class="truncate font-medium"></span>
                                            <svg class="size-4 text-gray-400 transition-transform duration-200" :class="openImmeubleDropdown ? 'rotate-180' : ''" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>

                                        <!-- Dropdown Menu -->
                                        <div x-show="openImmeubleDropdown" 
                                             style="display: none;" 
                                             class="absolute z-50 w-full mt-2 bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-750 rounded-xl shadow-lg p-2 max-h-60 overflow-y-auto">
                                            
                                            <!-- Search Field inside Dropdown -->
                                            <div class="px-2 py-1.5 border-b border-gray-100 dark:border-neutral-800 mb-1 flex items-center gap-2">
                                                <svg class="size-3.5 text-gray-400 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                                </svg>
                                                <input type="text" 
                                                       x-model="immeubleSearch" 
                                                       @click.stop 
                                                       placeholder="Rechercher un immeuble..." 
                                                       class="w-full text-xs bg-transparent border-0 p-0 focus:ring-0 focus:outline-none dark:text-white">
                                            </div>

                                            <div class="space-y-0.5">
                                                <!-- Template to list buildings in the selected city -->
                                                <template x-for="immeuble in filteredImmeublesInCity" :key="immeuble.id">
                                                    <button type="button" 
                                                            @click="selectImmeuble(immeuble.id, immeuble.nom)" 
                                                            class="w-full text-left py-2 px-3 rounded-lg text-sm text-slate-700 hover:bg-primary-50 hover:text-primary-600 dark:text-neutral-350 dark:hover:bg-neutral-800 transition-colors flex items-center justify-between"
                                                            :class="selectedImmeubleId === immeuble.id ? 'bg-primary-50/70 text-primary-600 dark:bg-neutral-800' : ''">
                                                        <span x-text="immeuble.nom" class="font-medium"></span>
                                                        <svg x-show="selectedImmeubleId === immeuble.id" class="size-4 text-primary-600 dark:text-primary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </button>
                                                </template>
                                                
                                                <div x-show="filteredImmeublesInCity.length === 0" class="py-3 px-3 text-center text-xs text-slate-400">Aucun immeuble trouvé dans cette ville.</div>
                                                
                                                <hr class="my-1 border-gray-100 dark:border-neutral-800">
                                                
                                                <button type="button" 
                                                        @click="isAddingCustomImmeuble = true; selectedImmeubleId = ''; selectedImmeubleNom = ''; immeubleSearch = ''; openImmeubleDropdown = false" 
                                                        class="w-full text-left py-2 px-3 rounded-lg text-xs font-bold text-primary-600 hover:bg-primary-50 dark:text-primary-400 dark:hover:bg-neutral-800/60 transition-colors flex items-center gap-2">
                                                    <svg class="size-4 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                                    </svg>
                                                    Ajouter un nouvel immeuble...
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <!-- Helpful link if they don't find it -->
                                        <div class="mt-2 flex justify-end">
                                            <button type="button" 
                                                    @click="isAddingCustomImmeuble = true; selectedImmeubleId = ''; selectedImmeubleNom = ''; immeubleSearch = ''" 
                                                    class="text-xs font-semibold text-primary-600 hover:underline dark:text-primary-400 flex items-center gap-1">
                                                <svg class="size-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                                </svg>
                                                Ajouter un immeuble
                                            </button>
                                        </div>
                                    </div>

                                    <!-- STAGE 2: Text Input for Adding a New Building -->
                                    <div x-show="isAddingCustomImmeuble" class="space-y-2 relative" @click.outside="openImmeubleDropdown = false">
                                        <label for="immeuble_nom" class="block text-sm mb-2 dark:text-white">Nom du nouvel immeuble</label>
                                        
                                        <div class="relative">
                                            <input type="text" 
                                                   id="immeuble_nom" 
                                                   name="immeuble_nom" 
                                                   x-model="immeubleSearch" 
                                                   @focus="openImmeubleDropdown = true"
                                                   @input="selectedImmeubleId = ''; openImmeubleDropdown = true"
                                                   :required="role === 'syndic' && isAddingCustomImmeuble"
                                                   :disabled="role !== 'syndic' || !isAddingCustomImmeuble"
                                                   class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" 
                                                   placeholder="Saisir le nom exact de l'immeuble...">
                                        </div>

                                        <!-- Suggestions List for New Building Input (to prevent duplicate names) -->
                                        <div x-show="openImmeubleDropdown && filteredImmeublesInCity.length > 0 && immeubleSearch.trim().length > 0" 
                                             style="display: none;" 
                                             class="absolute z-50 w-full mt-2 bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-750 rounded-xl shadow-lg p-2 max-h-60 overflow-y-auto">
                                            
                                            <div class="text-[10px] font-bold text-amber-600 dark:text-amber-400 px-3 py-1.5 bg-amber-50 dark:bg-amber-950/40 rounded-lg mb-2 leading-tight">
                                                Attention : des immeubles avec des noms similaires existent déjà dans cette ville. S'il s'agit du vôtre, sélectionnez-le :
                                            </div>
                                            
                                            <div class="space-y-0.5">
                                                <template x-for="immeuble in filteredImmeublesInCity" :key="immeuble.id">
                                                    <button type="button" 
                                                            @click="selectImmeuble(immeuble.id, immeuble.nom)" 
                                                            class="w-full text-left py-2 px-3 rounded-lg text-sm text-slate-700 hover:bg-primary-50 hover:text-primary-600 dark:text-neutral-350 dark:hover:bg-neutral-800 transition-colors flex items-center justify-between">
                                                        <span x-text="immeuble.nom" class="font-medium"></span>
                                                        <span class="text-xs text-primary-600 dark:text-primary-400 font-semibold flex items-center gap-1">
                                                            Sélectionner
                                                            <svg class="size-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                                            </svg>
                                                        </span>
                                                    </button>
                                                </template>
                                            </div>
                                        </div>

                                        <!-- Return to Selection Link -->
                                        <div x-show="hasBuildingsInSelectedCity" style="display: none;" class="mt-2 flex justify-start">
                                            <button type="button" 
                                                    @click="isAddingCustomImmeuble = false; selectedImmeubleId = ''; selectedImmeubleNom = ''; immeubleSearch = ''" 
                                                    class="text-xs font-semibold text-gray-500 hover:underline dark:text-neutral-400 flex items-center gap-1">
                                                <svg class="size-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                                </svg>
                                                Retourner à la liste des immeubles existants
                                            </button>
                                        </div>
                                    </div>

                                    @error('immeuble_nom') <p class="text-xs text-red-600 mt-2">{{ $message }}</p> @enderror
                                    @error('immeuble_id') <p class="text-xs text-red-600 mt-2">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <!-- B. Personal Info -->
                            <div class="space-y-4">
                                <!-- Nom -->
                                <div>
                                    <label for="syndic_nom" class="block text-sm mb-2 dark:text-white">Nom</label>
                                    <input type="text" id="syndic_nom" name="nom" :disabled="role !== 'syndic'" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 @error('nom') border-red-500 @enderror" required value="{{ old('nom') }}" placeholder="Ex: Mohamed Alami">
                                    @error('nom') <p class="text-xs text-red-600 mt-2">{{ $message }}</p> @enderror
                                </div>

                                <!-- Email -->
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
                                    <label for="syndic_email" class="block text-sm mb-2 dark:text-white">Adresse email</label>
                                    <div class="relative">
                                        <input type="email" id="syndic_email" name="email" x-model="email" @input.debounce.500ms="checkEmail" :disabled="role !== 'syndic'" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" :class="!isValid || isTaken ? 'border-red-500 focus:border-red-500' : (email && !isTaken && isValid && !isChecking ? 'border-emerald-500 focus:border-emerald-500' : 'border-gray-200')" required>
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
                                    <p x-show="feedbackMessage" x-text="feedbackMessage" class="text-xs mt-2" :class="!isValid || isTaken ? 'text-red-600' : 'text-emerald-600'"></p>
                                    @error('email') <p class="text-xs text-red-600 mt-2">{{ $message }}</p> @enderror
                                </div>



                                <!-- Mot de passe -->
                                <div>
                                    <label for="syndic_password" class="block text-sm mb-2 dark:text-white">Mot de passe</label>
                                    <input type="password" id="syndic_password" name="password" :disabled="role !== 'syndic'" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 @error('password') border-red-500 @enderror" required autocomplete="new-password">
                                    @error('password') <p class="text-xs text-red-600 mt-2">{{ $message }}</p> @enderror
                                </div>

                                <!-- Confirmer le mot de passe -->
                                <div>
                                    <label for="syndic_password_confirm" class="block text-sm mb-2 dark:text-white">Confirmer le mot de passe</label>
                                    <input type="password" id="syndic_password_confirm" name="password_confirmation" :disabled="role !== 'syndic'" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" required autocomplete="new-password">
                                </div>
                            </div>
                        </div>

                        <!-- ==================== RESIDENT FORM LAYOUT ==================== -->
                        <div x-show="role === 'resident'" style="display: none;" class="space-y-4">
                            <!-- A. Building Info (FIRST) -->
                            <div class="space-y-4 p-4 bg-gray-50 border border-gray-200 rounded-xl dark:bg-neutral-900/50 dark:border-neutral-700/60">
                                <h3 class="text-sm font-bold text-slate-800 dark:text-white mb-2 flex items-center gap-2">
                                    <i data-lucide="home" class="size-4.5 text-purple-500"></i>
                                    Informations de logement
                                </h3>

                                <input type="hidden" name="immeuble_ville" :value="selectedCity" :disabled="role !== 'resident'">

                                <!-- City Select for Resident -->
                                <div>
                                    <label class="block text-sm mb-2 dark:text-white">Ville de l'immeuble</label>
                                    <div class="space-y-3 relative" @click.outside="openCityDropdown = false">
                                        <!-- Custom Button Dropdown -->
                                        <button @click="openCityDropdown = !openCityDropdown" 
                                                type="button" 
                                                :disabled="role !== 'resident'"
                                                class="py-2.5 px-4 flex items-center justify-between w-full bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-750 dark:text-slate-300 rounded-xl text-sm transition-all focus:outline-none focus:border-purple-500 shadow-sm disabled:opacity-50 disabled:pointer-events-none">
                                            <span x-text="selectedCity ? selectedCity : '-- Sélectionner une ville --'" class="truncate"></span>
                                            <i data-lucide="chevron-down" class="size-4 text-gray-400 transition-transform duration-200" :class="openCityDropdown ? 'rotate-180' : ''"></i>
                                        </button>

                                        <!-- Dropdown Menu -->
                                        <div x-show="openCityDropdown" 
                                             style="display: none;" 
                                             class="absolute z-50 w-full mt-2 bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-750 rounded-xl shadow-lg p-2 max-h-60 overflow-y-auto">
                                            <!-- Search Field inside Dropdown -->
                                            <div class="px-2 py-1.5 border-b border-gray-100 dark:border-neutral-800 mb-1 flex items-center gap-2">
                                                <svg class="size-3.5 text-gray-400 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                                </svg>
                                                <input type="text" 
                                                       x-model="citySearchQuery" 
                                                       @click.stop 
                                                       placeholder="Rechercher une ville..." 
                                                       class="w-full text-xs bg-transparent border-0 p-0 focus:ring-0 focus:outline-none dark:text-white">
                                            </div>

                                            <div class="space-y-0.5">
                                                <template x-for="city in filteredCities" :key="city">
                                                    <button type="button" 
                                                            @click="selectedCity = city; openCityDropdown = false; citySearchQuery = ''; selectedImmeubleId = ''; selectedImmeubleNom = ''; immeubleSearch = '';" 
                                                            class="w-full text-left py-2 px-3 rounded-lg text-sm text-slate-700 hover:bg-purple-50 hover:text-purple-600 dark:text-neutral-350 dark:hover:bg-neutral-800 transition-colors flex items-center justify-between"
                                                            :class="selectedCity === city ? 'bg-purple-50/70 text-purple-600 dark:bg-neutral-800' : ''">
                                                        <span x-text="city" class="font-medium"></span>
                                                        <svg x-show="selectedCity === city" class="size-4 text-purple-600 dark:text-purple-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </button>
                                                </template>
                                                
                                                <div x-show="filteredCities.length === 0" class="py-3 px-3 text-center text-xs text-slate-400">Aucune ville trouvée.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Building Selection Dropdown (Only visible if a city is selected) -->
                                <div x-show="selectedCity.length > 0" 
                                     style="display: none;" 
                                     class="relative" 
                                     @click.outside="openImmeubleDropdown = false">
                                    <label class="block text-sm mb-2 dark:text-white">Votre immeuble</label>
                                    <button @click="openImmeubleDropdown = !openImmeubleDropdown" 
                                            type="button" 
                                            :disabled="role !== 'resident'"
                                            class="py-2.5 px-4 flex items-center justify-between w-full bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-750 dark:text-slate-300 rounded-xl text-sm transition-all focus:outline-none focus:border-purple-500 shadow-sm disabled:opacity-50 disabled:pointer-events-none">
                                        <span x-text="selectedImmeubleNom ? selectedImmeubleNom : '-- Sélectionner votre immeuble --'" class="truncate"></span>
                                        <i data-lucide="chevron-down" class="size-4 text-gray-400 transition-transform duration-200" :class="openImmeubleDropdown ? 'rotate-180' : ''"></i>
                                    </button>
                                    <input type="hidden" name="immeuble_id" :value="selectedImmeubleId" :disabled="role !== 'resident'">

                                    <div x-show="openImmeubleDropdown" style="display: none;" class="absolute z-50 w-full mt-2 bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-750 rounded-xl shadow-lg p-2 max-h-64 overflow-y-auto">
                                        <div class="px-2 py-1.5 border-b border-gray-100 dark:border-neutral-800 mb-1 flex items-center gap-2">
                                            <svg class="size-3.5 text-gray-400 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                            <input type="text" x-model="immeubleSearch" @click.stop placeholder="Rechercher..." class="w-full text-xs bg-transparent border-0 p-0 focus:ring-0 focus:outline-none dark:text-white">
                                        </div>

                                        <div class="space-y-0.5">
                                            <template x-for="immeuble in filteredImmeublesInCity" :key="immeuble.id">
                                                <button type="button" @click="selectImmeuble(immeuble.id, immeuble.nom)" class="w-full text-left py-2 px-3 rounded-lg text-sm text-slate-700 hover:bg-purple-50 hover:text-purple-600 dark:text-neutral-350 dark:hover:bg-neutral-800 transition-colors flex items-center justify-between" :class="selectedImmeubleId === immeuble.id ? 'bg-purple-50/70 text-purple-600 dark:bg-neutral-800' : ''">
                                                    <span x-text="immeuble.nom" class="font-medium"></span>
                                                    <svg x-show="selectedImmeubleId === immeuble.id" class="size-4 text-purple-600 dark:text-purple-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </button>
                                            </template>
                                            <div x-show="filteredImmeublesInCity.length === 0" class="py-3 px-3 text-center text-xs text-slate-400">Aucun immeuble trouvé dans cette ville.</div>
                                        </div>
                                    </div>
                                    @error('immeuble_id') <p class="text-xs text-red-600 mt-2">{{ $message }}</p> @enderror
                                </div>

                                 <!-- Numéro du logement -->
                                <div>
                                    <label for="resident_numero_appartement" class="block text-sm mb-2 dark:text-white">Numéro de logement</label>
                                    <input type="text" id="resident_numero_appartement" name="numero_appartement" :disabled="role !== 'resident'" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-purple-500 focus:ring-purple-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 @error('numero_appartement') border-red-500 @enderror" :required="role === 'resident'" value="{{ old('numero_appartement') }}" placeholder="Ex: 5, 12B...">
                                    @error('numero_appartement') <p class="text-xs text-red-600 mt-2">{{ $message }}</p> @enderror
                                </div>


                            </div>

                            <!-- B. Personal Info (LAST) -->
                            <div class="space-y-4">
                                <!-- Nom -->
                                <div>
                                    <label for="resident_nom" class="block text-sm mb-2 dark:text-white">Nom</label>
                                    <input type="text" id="resident_nom" name="nom" :disabled="role !== 'resident'" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-purple-500 focus:ring-purple-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 @error('nom') border-red-500 @enderror" :required="role === 'resident'" value="{{ old('nom') }}" placeholder="Ex: Mohamed Alami">
                                    @error('nom') <p class="text-xs text-red-600 mt-2">{{ $message }}</p> @enderror
                                </div>

                                <!-- Email -->
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
                                    <label for="resident_email" class="block text-sm mb-2 dark:text-white">Adresse email</label>
                                    <div class="relative">
                                        <input type="email" id="resident_email" name="email" x-model="email" @input.debounce.500ms="checkEmail" :disabled="role !== 'resident'" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-purple-500 focus:ring-purple-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" :class="!isValid || isTaken ? 'border-red-500 focus:border-red-500' : (email && !isTaken && isValid && !isChecking ? 'border-emerald-500 focus:border-emerald-500' : 'border-gray-200')" required>
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
                                    <p x-show="feedbackMessage" x-text="feedbackMessage" class="text-xs mt-2" :class="!isValid || isTaken ? 'text-red-600' : 'text-emerald-600'"></p>
                                    @error('email') <p class="text-xs text-red-600 mt-2">{{ $message }}</p> @enderror
                                </div>



                                <!-- Mot de passe -->
                                <div>
                                    <label for="resident_password" class="block text-sm mb-2 dark:text-white">Mot de passe</label>
                                    <input type="password" id="resident_password" name="password" :disabled="role !== 'resident'" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-purple-500 focus:ring-purple-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 @error('password') border-red-500 @enderror" :required="role === 'resident'" autocomplete="new-password">
                                    @error('password') <p class="text-xs text-red-600 mt-2">{{ $message }}</p> @enderror
                                </div>

                                <!-- Confirmer le mot de passe -->
                                <div>
                                    <label for="resident_password_confirm" class="block text-sm mb-2 dark:text-white">Confirmer le mot de passe</label>
                                    <input type="password" id="resident_password_confirm" name="password_confirmation" :disabled="role !== 'resident'" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-purple-500 focus:ring-purple-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" :required="role === 'resident'" autocomplete="new-password">
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-primary-600 hover:bg-primary-700 text-white disabled:opacity-50 disabled:pointer-events-none transition-colors duration-200">
                            Créer mon compte
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection
