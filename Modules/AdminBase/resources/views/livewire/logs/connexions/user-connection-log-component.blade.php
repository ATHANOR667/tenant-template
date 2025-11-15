<div x-data="{ currentView: @entangle('currentView'), showFiltersModal: false }"
     class="space-y-10 p-4 sm:p-6 md:p-10 bg-gray-50 dark:bg-gray-900 transition-colors duration-300 min-h-screen">

    <div class="max-w-7xl mx-auto">

        {{-- En-tête Dynamique (Titre et Bouton de Retour) --}}
        <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-800 pb-4 mb-8">

            {{-- Titre : Réduit à text-3xl sur mobile --}}
            <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white transition-opacity duration-300">
                <span x-show="currentView === 'global'" x-transition:enter="opacity-0" x-transition:enter-end="opacity-100">
                    Tableau de bord des Connexions
                </span>
                <span x-show="currentView === 'user-profile'" x-transition:enter="opacity-0" x-transition:enter-end="opacity-100">
                    Profil Utilisateur : {{ $selectedUser?->full_name ?? 'Détails' }}
                </span>
            </h1>

            {{-- Bouton de retour : Icône seulement sur mobile (moins d'encombrement) --}}
            <div x-show="currentView === 'user-profile'"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform translate-x-4"
                 x-transition:enter-end="opacity-100 transform translate-x-0">
                <button wire:click="backToGlobal"
                        class="flex items-center text-blue-600 dark:text-blue-400 font-semibold
                                p-1.5 md:p-2 rounded-lg bg-gray-100 dark:bg-gray-800 hover:bg-blue-50 dark:hover:bg-blue-900/30
                                transition-all duration-300 shadow-sm text-sm md:text-base">
                    <svg class="h-5 w-5 md:mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span class="hidden md:block">Retour au Dashboard</span>
                </button>
            </div>
        </div>


        {{-- Section Filtres (Affichée uniquement en vue 'global') --}}
        <div x-show="currentView === 'global'" class="mb-8 p-4 bg-white dark:bg-gray-800 rounded-xl shadow-lg">
            {{-- ... (Le reste du code des filtres n'a pas changé) ... --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                {{-- Bouton des filtres --}}
                <button @click="showFiltersModal = true"
                        class="px-5 py-2 bg-blue-600 text-white font-semibold rounded-xl
                                hover:bg-blue-700 active:bg-blue-800 transition-all duration-200
                                flex items-center shadow-md">
                    <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.293.707l-2 2A1 1 0 019 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
                    </svg>
                    Modifier les Filtres
                </button>

                {{-- Affichage des filtres actifs comme badges --}}
                <div class="flex flex-wrap gap-2 text-sm">
                    <span class="text-gray-500 dark:text-gray-400 font-medium">Filtres Actifs :</span>

                    @if (!empty($filters['search']))
                        <span class="bg-blue-100 dark:bg-blue-800 text-blue-700 dark:text-blue-300 px-3 py-1 rounded-full flex items-center font-medium">
                            Recherche: <span class="ml-1 font-bold">{{ $filters['search'] }}</span>
                            <button wire:click="removeFilter('search')" class="ml-2 text-blue-500 hover:text-blue-700 dark:hover:text-white transition font-bold text-lg leading-none">&times;</button>
                        </span>
                    @endif
                    @if (!empty($filters['user_type']))
                        <span class="bg-blue-100 dark:bg-blue-800 text-blue-700 dark:text-blue-300 px-3 py-1 rounded-full flex items-center font-medium">
                            Type: <span class="ml-1 font-bold">{{ ucfirst($filters['user_type']) }}</span>
                            <button wire:click="removeFilter('user_type')" class="ml-2 text-blue-500 hover:text-blue-700 dark:hover:text-white transition font-bold text-lg leading-none">&times;</button>
                        </span>
                    @endif
                    {{-- Affichage de la période seulement si ce n'est pas la valeur par défaut --}}
                    @if (!empty($filters['period']) && $filters['period'] !== 'last_7_days')
                        <span class="bg-blue-100 dark:bg-blue-800 text-blue-700 dark:text-blue-300 px-3 py-1 rounded-full flex items-center font-medium">
                            Période: <span class="ml-1 font-bold">{{ $periodOptions[$filters['period']] ?? $filters['period'] }}</span>
                            <button wire:click="removeFilter('period')" class="ml-2 text-blue-500 hover:text-blue-700 dark:hover:text-white transition font-bold text-lg leading-none">&times;</button>
                        </span>
                    @endif
                    @if (!empty($filters['activity_status']))
                        <span class="bg-red-100 dark:bg-red-800 text-red-700 dark:text-red-300 px-3 py-1 rounded-full flex items-center font-medium">
                            Statut: <span class="ml-1 font-bold">Actifs (5 min)</span>
                            <button wire:click="removeFilter('activity_status')" class="ml-2 text-red-500 hover:text-red-700 dark:hover:text-white transition font-bold text-lg leading-none">&times;</button>
                        </span>
                    @endif
                    @if (!empty($filters['location_or_ip']))
                        <span class="bg-blue-100 dark:bg-blue-800 text-blue-700 dark:text-blue-300 px-3 py-1 rounded-full flex items-center font-medium">
                            Location/IP: <span class="ml-1 font-bold">{{ $filters['location_or_ip'] }}</span>
                            <button wire:click="removeFilter('location_or_ip')" class="ml-2 text-blue-500 hover:text-blue-700 dark:hover:text-white transition font-bold text-lg leading-none">&times;</button>
                        </span>
                    @endif

                    @if (empty($filters['search']) && empty($filters['user_type']) && empty($filters['activity_status']) && empty($filters['location_or_ip']) && $filters['period'] === 'last_7_days')
                        <span class="text-sm text-gray-400 italic">Par défaut : 7 derniers jours</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Modal pour filtres --}}
        <div x-show="showFiltersModal"
             @click.away="showFiltersModal = false"
             class="fixed inset-0 z-50 flex items-center justify-center p-4"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">

            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-2xl w-full max-w-lg mx-auto transform transition-all duration-300 border border-gray-200 dark:border-gray-700/50">

                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 border-b pb-3 dark:border-gray-700">Paramètres de Filtrage</h2>

                <div class="grid grid-cols-1 gap-6">

                    {{-- Recherche --}}
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Rechercher (Nom, Email)</label>
                        <div class="relative">
                            <input wire:model.live.debounce.300ms="filters.search" type="text" id="search" placeholder="Nom, Email, Matricule..."
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-300">
                            <svg class="absolute inset-y-0 left-0 ml-3 h-5 w-5 text-gray-400 dark:text-gray-500 pointer-events-none top-1/2 transform -translate-y-1/2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </div>
                    </div>

                    {{-- Type d'utilisateur --}}
                    <div>
                        <label for="user_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Type d'utilisateur</label>
                        <div class="relative">
                            <select wire:model.live="filters.user_type" id="user_type"
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-300 appearance-none">
                                <option value="">Tous les types</option>
                                @foreach ($userTypes as $type)
                                    <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                                @endforeach
                            </select>
                            <svg class="absolute inset-y-0 left-0 ml-3 h-5 w-5 text-gray-400 dark:text-gray-500 pointer-events-none top-1/2 transform -translate-y-1/2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                            <svg class="absolute inset-y-0 right-0 mr-3 h-5 w-5 text-gray-400 pointer-events-none top-1/2 transform -translate-y-1/2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                        </div>
                    </div>

                    {{-- Période --}}
                    <div>
                        <label for="period" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Période d'Analyse</label>
                        <div class="relative">
                            <select wire:model.live="filters.period" id="period"
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-300 appearance-none">
                                @foreach ($periodOptions as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            <svg class="absolute inset-y-0 left-0 ml-3 h-5 w-5 text-gray-400 dark:text-gray-500 pointer-events-none top-1/2 transform -translate-y-1/2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            <svg class="absolute inset-y-0 right-0 mr-3 h-5 w-5 text-gray-400 pointer-events-none top-1/2 transform -translate-y-1/2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                        </div>
                    </div>

                    {{-- Statut d'activité --}}
                    <div>
                        <label for="activity_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Statut d'Activité</label>
                        <div class="relative">
                            <select wire:model.live="filters.activity_status" id="activity_status"
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-300 appearance-none">
                                <option value="">Tous les statuts</option>
                                <option value="active">Actifs récemment (5 min)</option>
                            </select>
                            <svg class="absolute inset-y-0 left-0 ml-3 h-5 w-5 text-gray-400 dark:text-gray-500 pointer-events-none top-1/2 transform -translate-y-1/2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <svg class="absolute inset-y-0 right-0 mr-3 h-5 w-5 text-gray-400 pointer-events-none top-1/2 transform -translate-y-1/2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                        </div>
                    </div>

                    {{-- Location ou IP --}}
                    <div>
                        <label for="location_or_ip" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Filtrer par Localisation / IP</label>
                        <div class="relative">
                            <input wire:model.live.debounce.300ms="filters.location_or_ip" type="text" id="location_or_ip" placeholder="Ville, Pays, 192.168.1.1..."
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-300">
                            <svg class="absolute inset-y-0 left-0 ml-3 h-5 w-5 text-gray-400 dark:text-gray-500 pointer-events-none top-1/2 transform -translate-y-1/2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        </div>
                    </div>

                </div>

                {{-- Boutons d'action du Modal --}}
                <div class="flex justify-end gap-3 mt-8 pt-4 border-t dark:border-gray-700">
                    <button @click="showFiltersModal = false"
                            class="px-5 py-2 text-gray-600 dark:text-gray-300 rounded-lg
                                   hover:bg-gray-100 dark:hover:bg-gray-700 transition font-medium">
                        Annuler
                    </button>
                    <button wire:click="applyFilters" @click="showFiltersModal = false"
                            class="px-5 py-2 bg-blue-600 text-white rounded-lg
                                   hover:bg-blue-700 active:bg-blue-800 transition shadow-md font-medium">
                        Appliquer les Filtres
                    </button>
                </div>
            </div>
        </div>

        {{-- Contenu des Vues --}}

        {{-- Vue globale --}}
        <div x-show="currentView === 'global'"
             class="space-y-10"
             x-transition:enter="transition ease-out duration-500"
             x-transition:enter-start="opacity-0 transform translate-y-4"
             x-transition:enter-end="opacity-100 transform translate-y-0">

            {{-- Les composants Livewire reçoivent les filtres ici --}}
            <div class="p-0">
                @livewire('adminbase::logs.connexions.user-connection-stats', ['filters' => $filters])
            </div>

            <div class="p-0">
                @livewire('adminbase::logs.connexions.user-connection-list', ['filters' => $filters])
            </div>
        </div>

        {{-- Vue de profilage utilisateur --}}
        <div x-show="currentView === 'user-profile'"
             class="space-y-8"
             x-transition:enter="transition ease-out duration-500"
             x-transition:enter-start="opacity-0 transform translate-x-4"
             x-transition:enter-end="opacity-100 transform translate-x-0">
            @if ($selectedUser)
                {{-- La clé assure que le composant est rafraîchi quand l'utilisateur change --}}
                @livewire('adminbase::logs.connexions.user-profile-component', ['user' => $selectedUser], key($selectedUser->id))
            @endif
        </div>
    </div>
</div>
