@extends('layouts.auth')

@section('content')
<main class="w-full max-w-md mx-auto p-4">
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
        <div class="p-4 sm:p-7">
            <div class="text-center">
                <div class="flex justify-center mb-5">
                    <img src="{{ asset('logo.png') }}" alt="Logo ImmoSyn" class="h-20 w-auto object-contain">
                </div>
                <h1 class="block text-2xl font-bold text-gray-800 dark:text-white">{{ __('Créer un compte') }}</h1>
                <p class="mt-2 text-sm text-gray-600 dark:text-neutral-400">
                    {{ __('Vous avez déjà un compte ?') }}
                    <a class="text-primary-600 decoration-2 hover:underline font-medium dark:text-primary-400" href="{{ route('login') }}">
                        {{ __('Se connecter') }}
                    </a>
                </p>
            </div>

            <div class="mt-5" x-data='{
                role: "{{ old("role", "syndic") }}",
                step: {{ $errors->any() ? 3 : ((session()->has("google_user_details") || old("role")) ? 2 : 1) }},
                numeroAppartement: "{{ old("numero_appartement", "") }}",
                
                immeubles: @json($immeubles ?? [], JSON_HEX_APOS),
                
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

                locale: "{{ app()->getLocale() }}",

                cityMapping: {
                    "casablanca": ["الدار البيضاء", "casablanca"],
                    "rabat": ["الرباط", "rabat"],
                    "marrakech": ["مراكش", "marrakech"],
                    "tanger": ["طنجة", "tanger", "tangier"],
                    "fez": ["فاس", "fès", "fez"],
                    "fès": ["فاس", "fès", "fez"],
                    "agadir": ["أكادير", "agadir"],
                    "meknès": ["مكناس", "meknès", "meknes"],
                    "oujda": ["وجدة", "oujda"],
                    "kénitra": ["القنيطرة", "kénitra", "kenitra"],
                    "tétouan": ["تطوان", "tétouan", "tetouan"],
                    "témara": ["تمارة", "témara", "temara"],
                    "safi": ["آسفي", "safi"],
                    "salé": ["سلا", "salé", "sale"],
                    "mohammedia": ["المحمدية", "mohammedia"],
                    "nador": ["الناظور", "nador"],
                    "el jadida": ["الجديدة", "el jadida"],
                    "taza": ["تازة", "taza"],
                    "settat": ["سطات", "settat"],
                    "larache": ["العرائش", "larache"],
                    "khemisset": ["الخميسات", "khemisset"],
                    "guelmim": ["كلميم", "guelmim"],
                    "berrechid": ["برشيد", "berrechid"],
                    "taourirt": ["تاوريرت", "taourirt"],
                    "fquih ben salah": ["الفقيه بن صالح", "fquih ben salah"],
                    "oued zem": ["وادي زم", "oued zem"],
                    "sidi slimane": ["سيدي سليمان", "sidi slimane"],
                    "errachidia": ["الرشيدية", "errachidia"],
                    "sidi kacem": ["سيدي قاسم", "sidi kacem"],
                    "sidi bouzid": ["سيدي بوزيد", "sidi bouzid"],
                    "sidi slimane echcharaa": ["سيدي سليمان الشراعة", "sidi slimane echcharaa", "sidi slimane echcharraa"],
                    "sidi slimane echcharraa": ["سيدي سليمان الشراعة", "sidi slimane echcharaa", "sidi slimane echcharraa"],
                    "khenifra": ["خنيفرة", "khenifra"],
                    "berkane": ["بركان", "berkane"],
                    "sefrou": ["صفرو", "sefrou"],
                    "taroudant": ["تارودانت", "taroudant"],
                    "el kelaa des sraghna": ["قلعة السراغنة", "el kelaa des sraghna", "qalat sraghna"],
                    "essaouira": ["الصويرة", "essaouira"],
                    "fnideq": ["الفنيدق", "fnideq"],
                    "sidi bennour": ["سيدي بنور", "sidi bennour"],
                    "tiznit": ["تيزنيت", "tiznit"],
                    "azrou": ["آزرو", "azrou"],
                    "benguerir": ["بنجرير", "benguerir"],
                    "midelt": ["ميدلت", "midelt"],
                    "bouskoura": ["بوسكورة", "bouskoura"],
                    "azemmour": ["أزمور", "azemmour"],
                    "bouznika": ["بوزنيقة", "bouznika"],
                    "al hoceïma": ["الحسيمة", "al hoceïma", "al hoceima"],
                    "dakhla": ["الداخلة", "dakhla"],
                    "guercif": ["جرسيف", "guercif"],
                    "ifrane": ["إفران", "ifrane"],
                    "laâyoune": ["العيون", "laâyoune", "laayoune"],
                    "ouarzazate": ["ورزازات", "ouarzazate"],
                    "skhirat": ["الصخيرات", "skhirat"]
                },

                cleanString(str) {
                    if (!str) return "";
                    return str.normalize("NFD")
                              .replace(/[\u0300-\u036f]/g, "")
                              .replace(/[\u200e\u200f\u202a-\u202e]/g, "")
                              .trim();
                },

                areCitiesEqual(a, b) {
                    if (!a || !b) return false;
                    const cleanA = this.cleanString(a).toLowerCase();
                    const cleanB = this.cleanString(b).toLowerCase();
                    if (cleanA === cleanB) return true;
                    const synonymsA = this.cityMapping[cleanA];
                    if (synonymsA) {
                        return synonymsA.some(s => this.cleanString(s).toLowerCase() === cleanB);
                    }
                    return false;
                },

                getDisplayCity(city) {
                    if (!city) return "";
                    const cleanCity = this.cleanString(city).toLowerCase();
                    const synonyms = this.cityMapping[cleanCity];
                    if (synonyms) {
                        if (this.locale === "ar") {
                            return synonyms[0];
                        } else {
                            return synonyms[1] || synonyms[0];
                        }
                    }
                    return city;
                },

                init() {
                    // Pre-process initial cityMapping to expand keys for O(1) direct lookup
                    const expanded = {};
                    for (const key in this.cityMapping) {
                        const synonyms = this.cityMapping[key];
                        synonyms.forEach(syn => {
                            if (syn) {
                                expanded[this.cleanString(syn).toLowerCase()] = synonyms;
                            }
                        });
                    }
                    this.cityMapping = expanded;

                    // Ajouter les villes presentes dans la base de donnees (arabe ou autre)
                    // pour affichage et correspondance avec les immeubles
                    const dbCities = this.immeubles.map(i => i.ville).filter(v => v);
                    const mergedWithDb = [...new Set([...this.allMoroccanCities, ...dbCities])];
                    this.allMoroccanCities = mergedWithDb.sort((a, b) => a.localeCompare(b, "ar", { sensitivity: "base" }));

                    // Fetch Moroccan cities from bilingual CDN (jsDelivr)
                    fetch("https://cdn.jsdelivr.net/gh/mehdibo/morocco-cities@master/cities.json")
                        .then(r => r.json())
                        .then(res => {
                            if (res && res.cities && Array.isArray(res.cities.data)) {
                                const fetched = [];
                                res.cities.data.forEach(item => {
                                    const fr = item.names.fr || item.names.en;
                                    const ar = item.names.ar;
                                    const en = item.names.en;
                                    if (fr && this.cleanString(fr).toLowerCase() !== "zeubelemok") {
                                        fetched.push(fr);
                                        const synonyms = [ar, fr, en].filter(Boolean);
                                        
                                        const normFr = this.cleanString(fr);
                                        if (normFr.toLowerCase() !== fr.toLowerCase()) {
                                            synonyms.push(normFr);
                                        }

                                        synonyms.forEach(syn => {
                                            const synKey = this.cleanString(syn).toLowerCase();
                                            if (!this.cityMapping[synKey]) {
                                                this.cityMapping[synKey] = synonyms;
                                            } else {
                                                const existing = this.cityMapping[synKey];
                                                synonyms.forEach(s => {
                                                    if (s && !existing.some(e => this.cleanString(e).toLowerCase() === this.cleanString(s).toLowerCase())) {
                                                        existing.push(s);
                                                    }
                                                });
                                            }
                                        });
                                    }
                                });
                                const merged = [...new Set([...this.allMoroccanCities, ...fetched])];
                                this.allMoroccanCities = merged.sort((a, b) => a.localeCompare(b, "ar", { sensitivity: "base" }));
                            }
                            
                            // Fetch unpkg as fallback/supplement
                            return fetch("https://unpkg.com/list-of-moroccan-cities/json/ville.json");
                        })
                        .then(r => r ? r.json() : null)
                        .then(data => {
                            if (Array.isArray(data)) {
                                const fetched = data.map(item => item.ville).filter(v => v && this.cleanString(v).toLowerCase() !== "zeubelemok");
                                fetched.forEach(city => {
                                    const key = this.cleanString(city).toLowerCase();
                                    if (!this.cityMapping[key]) {
                                        this.cityMapping[key] = [city];
                                    }
                                });
                                const merged = [...new Set([...this.allMoroccanCities, ...fetched])];
                                this.allMoroccanCities = merged.sort((a, b) => a.localeCompare(b, "ar", { sensitivity: "base" }));
                            }
                        })
                        .catch(err => console.error("Erreur chargement villes:", err));

                    let oldCity = "{{ old("immeuble_ville", "") }}";
                    if (oldCity) {
                        let uniqueCities = [...new Set(this.immeubles.map(i => i.ville))];
                        let matchedCity = uniqueCities.find(c => this.areCitiesEqual(c, oldCity));
                        if (matchedCity) {
                            this.selectedCity = matchedCity;
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
                            this.isAddingCustomImmeuble = this.isAddingCustomCity;
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
                    let list = this.allMoroccanCities;
                    if (this.citySearchQuery) {
                        const q = this.citySearchQuery.toLowerCase().trim();
                        list = list.filter(c => {
                            if (c.toLowerCase().includes(q)) return true;
                            const cleanC = this.cleanString(c).toLowerCase();
                            const synonyms = this.cityMapping[cleanC];
                            if (synonyms) {
                                return synonyms.some(s => this.cleanString(s).toLowerCase().includes(q));
                            }
                            return false;
                        });
                    }
                    
                    const seen = new Set();
                    return list.filter(c => {
                        const displayName = this.getDisplayCity(c);
                        const cleanDisplay = this.cleanString(displayName).toLowerCase();
                        if (seen.has(cleanDisplay)) {
                            return false;
                        }
                        seen.add(cleanDisplay);
                        return true;
                    });
                },
                
                get hasBuildingsInSelectedCity() {
                    let currentCity = this.isAddingCustomCity ? this.customCity : this.selectedCity;
                    if (!currentCity) return false;
                    return this.immeubles.some(i => i.ville && this.areCitiesEqual(i.ville, currentCity));
                },
                
                get filteredImmeublesInCity() {
                    let currentCity = this.isAddingCustomCity ? this.customCity : this.selectedCity;
                    if (!currentCity) return [];
                    const searchNormalized = this.immeubleSearch.toLowerCase();
                    
                    return this.immeubles.filter(i => 
                        i.ville && this.areCitiesEqual(i.ville, currentCity) && 
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
                        this.isAddingCustomImmeuble = false;
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
                },
                
                isStep2Valid() {
                    if (this.role === "syndic") {
                        let city = this.isAddingCustomCity ? this.customCity : this.selectedCity;
                        if (!city || city.trim() === "") return false;
                        if (this.isAddingCustomImmeuble) {
                            if (!this.immeubleSearch || this.immeubleSearch.trim() === "") return false;
                        } else {
                            if (!this.selectedImmeubleId) return false;
                        }
                        return true;
                    } else if (this.role === "resident") {
                        if (!this.selectedCity || this.selectedCity.trim() === "") return false;
                        if (!this.selectedImmeubleId) return false;
                        if (!this.numeroAppartement || this.numeroAppartement.trim() === "") return false;
                        return true;
                    }
                    return false;
                }
            }'>
                <!-- Role Selection Choice Screen -->
                <div x-show="step === 1" class="space-y-6">
                    <h2 class="text-sm font-bold text-center text-gray-500 dark:text-neutral-400">{{ __('Choisissez le type de compte à créer') }}</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Card: Syndic -->
                        <button type="button" @click="role = 'syndic'; step = 2" class="flex flex-col items-center justify-center p-6 text-center bg-gray-50 hover:bg-primary-50/20 dark:bg-neutral-900/50 dark:hover:bg-neutral-800/40 border border-gray-200 dark:border-neutral-750 rounded-2xl shadow-sm hover:shadow-md hover:border-primary-500 transition-all duration-200">
                            <div class="p-3 bg-primary-50 dark:bg-primary-950/40 rounded-xl mb-4 text-primary-600 dark:text-primary-400">
                                <i data-lucide="building" class="size-8"></i>
                            </div>
                            <h3 class="text-base font-bold text-gray-800 dark:text-white mb-1">{{ __('Syndic') }}</h3>
                            <p class="text-xs text-gray-500 dark:text-neutral-400 leading-relaxed">{{ __('Je gère un ou plusieurs immeubles.') }}</p>
                        </button>

                        <!-- Card: Resident -->
                        <button type="button" @click="role = 'resident'; step = 2" class="flex flex-col items-center justify-center p-6 text-center bg-gray-50 hover:bg-purple-50/20 dark:bg-neutral-900/50 dark:hover:bg-neutral-800/40 border border-gray-200 dark:border-neutral-750 rounded-2xl shadow-sm hover:shadow-md hover:border-purple-500 transition-all duration-200">
                            <div class="p-3 bg-purple-50 dark:bg-purple-950/40 rounded-xl mb-4 text-purple-600 dark:text-purple-400">
                                <i data-lucide="users" class="size-8"></i>
                            </div>
                            <h3 class="text-base font-bold text-gray-800 dark:text-white mb-1">{{ __('Résident') }}</h3>
                            <p class="text-xs text-gray-500 dark:text-neutral-400 leading-relaxed">{{ __('Je suis copropriétaire ou locataire dans un immeuble.') }}</p>
                        </button>
                    </div>
                </div>

                <form x-show="step > 1" style="display: none;" method="POST" action="{{ route('register') }}">
                    @csrf
                    <input type="hidden" name="role" :value="role">

                    @if(session()->has('google_user_details'))
                    <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 rounded-xl dark:bg-emerald-950/20 dark:border-emerald-800 text-emerald-800 dark:text-emerald-400 text-sm">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <svg class="size-4 shrink-0 text-emerald-600 dark:text-emerald-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>{{ __('Inscription avec Google') }} : <strong>{{ session('google_user_details.email') }}</strong></span>
                            </div>
                            <a href="{{ route('register.cancel-google') }}" class="text-xs font-semibold underline hover:text-emerald-900 dark:hover:text-emerald-300">{{ __('Annuler') }}</a>
                        </div>
                    </div>
                    @endif

                    @if(session('info'))
                    <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-xl dark:bg-blue-950/20 dark:border-blue-800 text-blue-800 dark:text-blue-400 text-sm">
                        <div class="flex items-center gap-2">
                            <svg class="size-4 shrink-0 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ session('info') }}</span>
                        </div>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl dark:bg-red-950/20 dark:border-red-800 text-red-800 dark:text-red-400 text-sm">
                        <div class="flex items-center gap-2">
                            <svg class="size-4 shrink-0 text-red-600 dark:text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <span>{{ session('error') }}</span>
                        </div>
                    </div>
                    @endif

                    @if(session('status'))
                    <div class="mb-4 p-4 bg-slate-50 border border-slate-200 rounded-xl dark:bg-neutral-900/50 dark:border-neutral-750 text-slate-800 dark:text-neutral-300 text-sm">
                        <div class="flex items-center gap-2">
                            <svg class="size-4 shrink-0 text-slate-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ session('status') }}</span>
                        </div>
                    </div>
                    @endif

                    <!-- Back Button + Role Selector Header -->
                    <div class="flex items-center justify-between mb-6 gap-x-4">
                        <button type="button" @click="step = 1" class="py-1.5 px-3 inline-flex items-center gap-x-1.5 text-xs font-semibold rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 dark:bg-neutral-900 dark:border-neutral-750 dark:text-neutral-300 dark:hover:bg-neutral-800" title="{{ __('Précédent') }}">
                            <i data-lucide="arrow-left" class="size-4" :class="document.documentElement.dir === 'rtl' ? 'rotate-180' : ''"></i>
                            <span>{{ __('Précédent') }}</span>
                        </button>
                        
                        <div class="flex bg-gray-100 dark:bg-neutral-900 p-1 rounded-xl w-full max-w-[200px]">
                            <button type="button" @click="role = 'syndic'; if (step > 1) { step = 2; }" class="flex-1 py-1.5 text-xs font-bold rounded-lg transition-all" :class="role === 'syndic' ? 'bg-white dark:bg-neutral-800 text-primary-600 dark:text-white shadow-sm' : 'text-gray-400 dark:text-neutral-500 hover:text-gray-650'">
                                {{ __('Syndic') }}
                            </button>
                            <button type="button" @click="role = 'resident'; if (step > 1) { step = 2; }" class="flex-1 py-1.5 text-xs font-bold rounded-lg transition-all" :class="role === 'resident' ? 'bg-white dark:bg-neutral-800 text-purple-600 dark:text-white shadow-sm' : 'text-gray-400 dark:text-neutral-500 hover:text-gray-650'">
                                {{ __('Résident') }}
                            </button>
                        </div>
                    </div>

                    <div class="grid gap-y-4">
                        <!-- ==================== SYNDIC FORM LAYOUT ==================== -->
                        <div x-show="role === 'syndic'" class="space-y-4">
                            
                            <!-- A. Building Info (FIRST) -->
                            <div x-show="step === 2" class="p-4 bg-gray-50 border border-gray-200 rounded-xl dark:bg-neutral-900/50 dark:border-neutral-700/60 space-y-4">
                                <h3 class="text-sm font-bold text-slate-800 dark:text-white mb-2 flex items-center gap-2">
                                    <i data-lucide="building" class="size-4.5 text-primary-500"></i>
                                    {{ __('Association de l\'immeuble') }}
                                </h3>
                                
                                <input type="hidden" name="immeuble_type" :value="isAddingCustomImmeuble ? 'new' : 'existing'" :disabled="role !== 'syndic'">
                                <input type="hidden" name="immeuble_id" :value="selectedImmeubleId" :disabled="role !== 'syndic' || isAddingCustomImmeuble">
                                <input type="hidden" name="immeuble_ville" :value="isAddingCustomCity ? customCity : selectedCity" :disabled="role !== 'syndic'">

                                <!-- City Select Dropdown -->
                                <div>
                                    <label class="block text-sm mb-2 dark:text-white">{{ __('Ville de l\'immeuble') }}</label>
                                    <div class="space-y-3 relative" @click.outside="openCityDropdown = false">
                                        <!-- Custom Button Dropdown (matching resident building selector styling) -->
                                        <button @click="openCityDropdown = !openCityDropdown" 
                                                type="button" 
                                                :disabled="role !== 'syndic'"
                                                class="py-2.5 px-4 flex items-center justify-between w-full bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-750 dark:text-slate-300 rounded-xl text-sm transition-all focus:outline-none focus:border-primary-500 shadow-sm disabled:opacity-50 disabled:pointer-events-none">
                                            <span x-text="isAddingCustomCity ? (customCity ? customCity : '{{ __('Autre ville...') }}') : (selectedCity ? getDisplayCity(selectedCity) : '{{ __('-- Sélectionner une ville --') }}')" class="truncate"></span>
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
                                                       placeholder="{{ __('Rechercher une ville...') }}" 
                                                       class="w-full text-xs bg-transparent border-0 p-0 focus:ring-0 focus:outline-none dark:text-white">
                                            </div>

                                            <div class="space-y-0.5">
                                                <template x-for="city in filteredCities" :key="city">
                                                    <button type="button" 
                                                            @click="selectCity(city); openCityDropdown = false; citySearchQuery = ''" 
                                                            class="w-full text-left py-2 px-3 rounded-lg text-sm text-slate-700 hover:bg-primary-50 hover:text-primary-600 dark:text-neutral-350 dark:hover:bg-neutral-800 transition-colors flex items-center justify-between"
                                                            :class="selectedCity === city && !isAddingCustomCity ? 'bg-primary-50/70 text-primary-600 dark:bg-neutral-800' : ''">
                                                        <span x-text="getDisplayCity(city)" class="font-medium"></span>
                                                        <svg x-show="selectedCity === city && !isAddingCustomCity" class="size-4 text-primary-600 dark:text-primary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </button>
                                                </template>
                                                
                                                <div x-show="filteredCities.length === 0" class="py-3 px-3 text-center text-xs text-slate-400">{{ __('Aucune ville trouvée.') }}</div>
                                                
                                                <hr class="my-1 border-gray-100 dark:border-neutral-800">
                                                <button type="button" 
                                                        @click="selectCity('custom'); openCityDropdown = false; citySearchQuery = ''" 
                                                        class="w-full text-left py-2 px-3 rounded-lg text-xs font-bold text-primary-600 hover:bg-primary-50 dark:text-primary-400 dark:hover:bg-neutral-800/60 transition-colors flex items-center gap-2"
                                                        :class="isAddingCustomCity ? 'bg-primary-50/70 dark:bg-neutral-800' : ''">
                                                    <svg class="size-4 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                                    </svg>
                                                    {{ __('Ajouter une autre ville...') }}
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <!-- Custom City Input -->
                                        <div x-show="isAddingCustomCity || allMoroccanCities.length === 0" style="display: none;">
                                            <label class="block text-xs mb-1 text-gray-500 dark:text-neutral-400">{{ __('Nom de la nouvelle ville') }}</label>
                                            <input type="text" 
                                                   x-model="customCity" 
                                                   @input="isAddingCustomImmeuble = true; selectedImmeubleId = ''; selectedImmeubleNom = '';"
                                                   :disabled="role !== 'syndic'"
                                                   placeholder="{{ __('Ex: Casablanca, Marrakech...') }}" 
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
                                        <label class="block text-sm mb-2 dark:text-white">{{ __('Immeuble') }}</label>
                                        
                                        <button @click="openImmeubleDropdown = !openImmeubleDropdown" 
                                                type="button" 
                                                :disabled="role !== 'syndic'"
                                                class="py-2.5 px-4 flex items-center justify-between w-full bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-750 dark:text-slate-300 rounded-xl text-sm transition-all focus:outline-none focus:border-primary-500 shadow-sm disabled:opacity-50 disabled:pointer-events-none">
                                            <span x-text="selectedImmeubleNom ? selectedImmeubleNom : '{{ __('-- Sélectionner un immeuble existant --') }}'" class="truncate font-medium"></span>
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
                                                       placeholder="{{ __('Rechercher un immeuble...') }}" 
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
                                                
                                                <div x-show="filteredImmeublesInCity.length === 0" class="py-3 px-3 text-center text-xs text-slate-400">{{ __('Aucun immeuble trouvé dans cette ville.') }}</div>
                                                
                                                <hr class="my-1 border-gray-100 dark:border-neutral-800">
                                                
                                                <button type="button" 
                                                        @click="isAddingCustomImmeuble = true; selectedImmeubleId = ''; selectedImmeubleNom = ''; immeubleSearch = ''; openImmeubleDropdown = false" 
                                                        class="w-full text-left py-2 px-3 rounded-lg text-xs font-bold text-primary-600 hover:bg-primary-50 dark:text-primary-400 dark:hover:bg-neutral-800/60 transition-colors flex items-center gap-2">
                                                    <svg class="size-4 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                                    </svg>
                                                    {{ __('Ajouter un nouvel immeuble...') }}
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
                                                {{ __('Ajouter un immeuble') }}
                                            </button>
                                        </div>
                                    </div>

                                    <!-- STAGE 2: Text Input for Adding a New Building -->
                                    <div x-show="isAddingCustomImmeuble" class="space-y-2 relative" @click.outside="openImmeubleDropdown = false">
                                        <label for="immeuble_nom" class="block text-sm mb-2 dark:text-white">{{ __('Nom du nouvel immeuble') }}</label>
                                        
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
                                                   placeholder="{{ __('Saisir le nom exact de l\'immeuble...') }}">
                                        </div>

                                        <!-- Suggestions List for New Building Input (to prevent duplicate names) -->
                                        <div x-show="openImmeubleDropdown && filteredImmeublesInCity.length > 0 && immeubleSearch.trim().length > 0" 
                                             style="display: none;" 
                                             class="absolute z-50 w-full mt-2 bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-750 rounded-xl shadow-lg p-2 max-h-60 overflow-y-auto">
                                            
                                            <div class="text-[10px] font-bold text-amber-600 dark:text-amber-400 px-3 py-1.5 bg-amber-50 dark:bg-amber-950/40 rounded-lg mb-2 leading-tight">
                                                {{ __('Attention : des immeubles avec des noms similaires existent déjà dans cette ville. S\'il s\'agit du vôtre, sélectionnez-le :') }}
                                            </div>
                                            
                                            <div class="space-y-0.5">
                                                <template x-for="immeuble in filteredImmeublesInCity" :key="immeuble.id">
                                                    <button type="button" 
                                                            @click="selectImmeuble(immeuble.id, immeuble.nom)" 
                                                            class="w-full text-left py-2 px-3 rounded-lg text-sm text-slate-700 hover:bg-primary-50 hover:text-primary-600 dark:text-neutral-350 dark:hover:bg-neutral-800 transition-colors flex items-center justify-between">
                                                        <span x-text="immeuble.nom" class="font-medium"></span>
                                                        <span class="text-xs text-primary-600 dark:text-primary-400 font-semibold flex items-center gap-1">
                                                            {{ __('Sélectionner') }}
                                                            <svg class="size-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                                            </svg>
                                                        </span>
                                                    </button>
                                                </template>
                                            </div>
                                        </div>

                                        <!-- Return to Selection Link -->
                                        <div class="mt-2 flex justify-start">
                                            <button type="button" 
                                                    @click="isAddingCustomImmeuble = false; selectedImmeubleId = ''; selectedImmeubleNom = ''; immeubleSearch = ''" 
                                                    class="text-xs font-semibold text-gray-500 hover:underline dark:text-neutral-400 flex items-center gap-1">
                                                <svg class="size-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                                </svg>
                                                {{ __('Retourner à la liste des immeubles existants') }}
                                            </button>
                                        </div>
                                    </div>

                                    @error('immeuble_nom') <p class="text-xs text-red-600 mt-2">{{ $message }}</p> @enderror
                                    @error('immeuble_id') <p class="text-xs text-red-600 mt-2">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <!-- B. Personal Info -->
                            <div x-show="step === 3" style="display: none;" class="space-y-4">
                                <!-- Nom -->
                                <div>
                                    <label for="syndic_nom" class="block text-sm mb-2 dark:text-white">{{ __('Nom complet') }}</label>
                                    <input type="text" id="syndic_nom" name="nom" :disabled="role !== 'syndic'" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 @error('nom') border-red-500 @enderror" required value="{{ session('google_user_details.nom') ? (session('google_user_details.prenom') . ' ' . session('google_user_details.nom')) : old('nom') }}" @if(session()->has('google_user_details')) readonly @endif placeholder="{{ __('Ex: Mohamed Alami') }}">
                                    @error('nom') <p class="text-xs text-red-600 mt-2">{{ $message }}</p> @enderror
                                </div>

                                <!-- Email -->
                                <div x-data="{
                                    email: '{{ session('google_user_details.email') ?? old('email') }}',
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
                                            this.feedbackMessage = '{{ __('Format d\'adresse email invalide.') }}';
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
                                    <label for="syndic_email" class="block text-sm mb-2 dark:text-white">{{ __('Adresse email') }}</label>
                                    <div class="relative">
                                        <input type="email" id="syndic_email" name="email" x-model="email" @input.debounce.500ms="checkEmail" :disabled="role !== 'syndic'" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" :class="!isValid || isTaken ? 'border-red-500 focus:border-red-500' : (email && !isTaken && isValid && !isChecking ? 'border-emerald-500 focus:border-emerald-500' : 'border-gray-200')" required @if(session()->has('google_user_details')) readonly @endif>
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

                                @if(!session()->has('google_user_details'))
                                <!-- Mot de passe -->
                                <div>
                                    <label for="syndic_password" class="block text-sm mb-2 dark:text-white">{{ __('Mot de passe') }}</label>
                                    <input type="password" id="syndic_password" name="password" :disabled="role !== 'syndic'" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 @error('password') border-red-500 @enderror" required autocomplete="new-password">
                                    @error('password') <p class="text-xs text-red-600 mt-2">{{ $message }}</p> @enderror
                                </div>

                                <!-- Confirmer le mot de passe -->
                                <div>
                                    <label for="syndic_password_confirm" class="block text-sm mb-2 dark:text-white">{{ __('Confirmer le mot de passe') }}</label>
                                    <input type="password" id="syndic_password_confirm" name="password_confirmation" :disabled="role !== 'syndic'" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" required autocomplete="new-password">
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- ==================== RESIDENT FORM LAYOUT ==================== -->
                        <div x-show="role === 'resident'" style="display: none;" class="space-y-4">
                            <!-- A. Building Info (FIRST) -->
                            <div x-show="step === 2" class="space-y-4 p-4 bg-gray-50 border border-gray-200 rounded-xl dark:bg-neutral-900/50 dark:border-neutral-700/60">
                                <h3 class="text-sm font-bold text-slate-800 dark:text-white mb-2 flex items-center gap-2">
                                    <i data-lucide="home" class="size-4.5 text-purple-500"></i>
                                    {{ __('Informations de logement') }}
                                </h3>

                                <input type="hidden" name="immeuble_ville" :value="selectedCity" :disabled="role !== 'resident'">

                                <!-- City Select for Resident -->
                                <div>
                                    <label class="block text-sm mb-2 dark:text-white">{{ __('Ville de l\'immeuble') }}</label>
                                    <div class="space-y-3 relative" @click.outside="openCityDropdown = false">
                                        <!-- Custom Button Dropdown -->
                                        <button @click="openCityDropdown = !openCityDropdown" 
                                                type="button" 
                                                :disabled="role !== 'resident'"
                                                class="py-2.5 px-4 flex items-center justify-between w-full bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-750 dark:text-slate-300 rounded-xl text-sm transition-all focus:outline-none focus:border-purple-500 shadow-sm disabled:opacity-50 disabled:pointer-events-none">
                                            <span x-text="selectedCity ? getDisplayCity(selectedCity) : '{{ __('-- Sélectionner une ville --') }}'" class="truncate"></span>
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
                                                       placeholder="{{ __('Rechercher une ville...') }}" 
                                                       class="w-full text-xs bg-transparent border-0 p-0 focus:ring-0 focus:outline-none dark:text-white">
                                            </div>

                                            <div class="space-y-0.5">
                                                <template x-for="city in filteredCities" :key="city">
                                                    <button type="button" 
                                                            @click="selectedCity = city; openCityDropdown = false; citySearchQuery = ''; selectedImmeubleId = ''; selectedImmeubleNom = ''; immeubleSearch = '';" 
                                                            class="w-full text-left py-2 px-3 rounded-lg text-sm text-slate-700 hover:bg-purple-50 hover:text-purple-600 dark:text-neutral-350 dark:hover:bg-neutral-800 transition-colors flex items-center justify-between"
                                                            :class="selectedCity === city ? 'bg-purple-50/70 text-purple-600 dark:bg-neutral-800' : ''">
                                                        <span x-text="getDisplayCity(city)" class="font-medium"></span>
                                                        <svg x-show="selectedCity === city" class="size-4 text-purple-600 dark:text-purple-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </button>
                                                </template>
                                                
                                                <div x-show="filteredCities.length === 0" class="py-3 px-3 text-center text-xs text-slate-400">{{ __('Aucune ville trouvée.') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Building Selection Dropdown (Only visible if a city is selected) -->
                                <div x-show="selectedCity.length > 0" 
                                     style="display: none;" 
                                     class="relative" 
                                     @click.outside="openImmeubleDropdown = false">
                                    <label class="block text-sm mb-2 dark:text-white">{{ __('Votre immeuble') }}</label>
                                    <button @click="openImmeubleDropdown = !openImmeubleDropdown" 
                                            type="button" 
                                            :disabled="role !== 'resident'"
                                            class="py-2.5 px-4 flex items-center justify-between w-full bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-750 dark:text-slate-300 rounded-xl text-sm transition-all focus:outline-none focus:border-purple-500 shadow-sm disabled:opacity-50 disabled:pointer-events-none">
                                        <span x-text="selectedImmeubleNom ? selectedImmeubleNom : '{{ __('-- Sélectionner votre immeuble --') }}'" class="truncate"></span>
                                        <i data-lucide="chevron-down" class="size-4 text-gray-400 transition-transform duration-200" :class="openImmeubleDropdown ? 'rotate-180' : ''"></i>
                                    </button>
                                    <input type="hidden" name="immeuble_id" :value="selectedImmeubleId" :disabled="role !== 'resident'">

                                    <div x-show="openImmeubleDropdown" style="display: none;" class="absolute z-50 w-full mt-2 bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-750 rounded-xl shadow-lg p-2 max-h-64 overflow-y-auto">
                                        <div class="px-2 py-1.5 border-b border-gray-100 dark:border-neutral-800 mb-1 flex items-center gap-2">
                                            <svg class="size-3.5 text-gray-400 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                            <input type="text" x-model="immeubleSearch" @click.stop placeholder="{{ __('Rechercher...') }}" class="w-full text-xs bg-transparent border-0 p-0 focus:ring-0 focus:outline-none dark:text-white">
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
                                            <div x-show="filteredImmeublesInCity.length === 0" class="py-3 px-3 text-center text-xs text-slate-400">{{ __('Aucun immeuble trouvé dans cette ville.') }}</div>
                                        </div>
                                    </div>
                                    @error('immeuble_id') <p class="text-xs text-red-600 mt-2">{{ $message }}</p> @enderror
                                </div>

                                 <!-- Numéro du logement -->
                                <div>
                                    <label for="resident_numero_appartement" class="block text-sm mb-2 dark:text-white">{{ __('Numéro de logement') }}</label>
                                    <input type="text" id="resident_numero_appartement" name="numero_appartement" x-model="numeroAppartement" :disabled="role !== 'resident'" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-purple-500 focus:ring-purple-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 @error('numero_appartement') border-red-500 @enderror" :required="role === 'resident'" placeholder="{{ __('Ex: 5, 12B...') }}">
                                    @error('numero_appartement') <p class="text-xs text-red-600 mt-2">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <!-- B. Personal Info (LAST) -->
                            <div x-show="step === 3" style="display: none;" class="space-y-4">
                                <!-- Nom -->
                                <div>
                                    <label for="resident_nom" class="block text-sm mb-2 dark:text-white">{{ __('Nom complet') }}</label>
                                    <input type="text" id="resident_nom" name="nom" :disabled="role !== 'resident'" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-purple-500 focus:ring-purple-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 @error('nom') border-red-500 @enderror" :required="role === 'resident'" value="{{ session('google_user_details.nom') ? (session('google_user_details.prenom') . ' ' . session('google_user_details.nom')) : old('nom') }}" @if(session()->has('google_user_details')) readonly @endif placeholder="{{ __('Ex: Mohamed Alami') }}">
                                    @error('nom') <p class="text-xs text-red-600 mt-2">{{ $message }}</p> @enderror
                                </div>

                                <!-- Email -->
                                <div x-data="{
                                    email: '{{ session('google_user_details.email') ?? old('email') }}',
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
                                            this.feedbackMessage = '{{ __('Format d\'adresse email invalide.') }}';
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
                                    <label for="resident_email" class="block text-sm mb-2 dark:text-white">{{ __('Adresse email') }}</label>
                                    <div class="relative">
                                        <input type="email" id="resident_email" name="email" x-model="email" @input.debounce.500ms="checkEmail" :disabled="role !== 'resident'" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-purple-500 focus:ring-purple-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" :class="!isValid || isTaken ? 'border-red-500 focus:border-red-500' : (email && !isTaken && isValid && !isChecking ? 'border-emerald-500 focus:border-emerald-500' : 'border-gray-200')" required @if(session()->has('google_user_details')) readonly @endif>
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

                                @if(!session()->has('google_user_details'))
                                <!-- Mot de passe -->
                                <div>
                                    <label for="resident_password" class="block text-sm mb-2 dark:text-white">{{ __('Mot de passe') }}</label>
                                    <input type="password" id="resident_password" name="password" :disabled="role !== 'resident'" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-purple-500 focus:ring-purple-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 @error('password') border-red-500 @enderror" :required="role === 'resident'" autocomplete="new-password">
                                    @error('password') <p class="text-xs text-red-600 mt-2">{{ $message }}</p> @enderror
                                </div>

                                <!-- Confirmer le mot de passe -->
                                <div>
                                    <label for="resident_password_confirm" class="block text-sm mb-2 dark:text-white">{{ __('Confirmer le mot de passe') }}</label>
                                    <input type="password" id="resident_password_confirm" name="password_confirmation" :disabled="role !== 'resident'" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-purple-500 focus:ring-purple-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" :required="role === 'resident'" autocomplete="new-password">
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Step 2 Buttons: Continue to Personal Info -->
                        <div x-show="step === 2" class="space-y-4">
                            <button type="button" 
                                    @click="if (isStep2Valid()) { step = 3; } else { alert('{{ __('Veuillez remplir toutes les informations de logement/immeuble avant de continuer.') }}'); }"
                                    class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-bold rounded-lg border border-transparent bg-primary-600 hover:bg-primary-700 text-white shadow-md disabled:opacity-50 disabled:pointer-events-none transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5">
                                {{ __('Suivant') }}
                                <i data-lucide="arrow-right" class="size-4" :class="document.documentElement.dir === 'rtl' ? 'rotate-180' : ''"></i>
                            </button>
                        </div>

                        <!-- Step 3 Buttons: standard registration submit or return -->
                        <div x-show="step === 3" class="space-y-4" style="display: none;">
                            <button type="submit" class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-bold rounded-lg border border-transparent bg-primary-600 hover:bg-primary-700 text-white shadow-md disabled:opacity-50 disabled:pointer-events-none transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5">
                                @if(session()->has('google_user_details'))
                                {{ __('Finaliser mon inscription') }}
                                @else
                                {{ __('Créer mon compte') }}
                                @endif
                            </button>

                            @if(!session()->has('google_user_details'))
                            <div class="py-3 flex items-center text-xs text-gray-400 uppercase before:flex-1 before:border-t before:border-gray-200 before:me-6 after:flex-1 after:border-t after:border-gray-200 after:ms-6 dark:text-neutral-500 dark:before:border-neutral-700 dark:after:border-neutral-700 font-semibold">
                                {{ __("Ou s'inscrire avec") }}
                            </div>

                            <button type="submit" 
                                    formaction="{{ route('auth.google.redirect.post') }}" 
                                    formnovalidate 
                                    class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800 transition-all duration-200 hover:shadow-md">
                                <svg class="w-5 h-auto shrink-0" viewBox="0 0 24 24" width="24" height="24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z" fill="#FBBC05"/>
                                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z" fill="#EA4335"/>
                                </svg>
                                {{ __('Continuer avec Google') }}
                            </button>
                            @endif

                            <button type="button" 
                                    @click="step = 2" 
                                    class="w-full mt-4 py-2 px-4 inline-flex justify-center items-center gap-x-2 text-xs font-semibold rounded-lg text-gray-500 hover:text-gray-800 hover:bg-gray-100 dark:text-neutral-400 dark:hover:text-neutral-200 dark:hover:bg-neutral-800 transition-colors duration-200">
                                <i data-lucide="arrow-left" class="size-4" :class="document.documentElement.dir === 'rtl' ? 'rotate-180' : ''"></i>
                                {{ __('Retour aux informations de logement') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection
