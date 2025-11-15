<div class="p-8 bg-white dark:bg-gray-800 shadow-xl rounded-3xl border border-gray-100 dark:border-gray-700">

    <h3 class="text-4xl font-light text-gray-900 dark:text-gray-100 mb-8 flex items-center border-b border-gray-200 dark:border-gray-700 pb-4">
        <svg class="h-8 w-8 text-blue-500 mr-4" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 15h2v-6h-2v6zm0-8h2V7h-2v2z"/>
        </svg>
        Statistiques des Accès
        <span class="ml-4 px-3 py-1 bg-blue-100 text-blue-800 dark:bg-blue-700 dark:text-blue-200 text-sm font-medium rounded-full">
            Guard: {{ $guardName }}
        </span>
    </h3>

    {{-- Stats sur les Permissions: Regroupement par CATÉGORIE --}}
    <div class="mb-12 pt-4">
        <h4 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-8 flex items-center">
            <svg class="h-6 w-6 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.617 7.243a4.004 4.004 0 00-7.368-2.189l-.27-.47a8.008 8.008 0 00-6.103-12.008l-.208-.008A8.012 8.012 0 004 20h.01"></path>
            </svg>
            Permissions par Rôle (Regroupées par Catégorie)
        </h4>

        @if(empty($permissionRoleCounts))
            <div class="bg-blue-50 dark:bg-blue-900/30 p-8 rounded-xl text-center text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-700 shadow-inner">
                <p class="mb-2 text-lg font-medium">Aucune statistique de permission n'est disponible.</p>
                <p class="text-sm">Veuillez créer des permissions et les attacher à des rôles pour voir les données ici.</p>
            </div>
        @else

            {{-- Boucle directe sur les catégories --}}
            @foreach($this->permissionsGroupedByCategory as $category => $permissionsInGroup)
                {{-- SECTION CATÉGORIE : DÉFAUT FERMÉ --}}
                <div x-data="{ categoryOpen: false }" class="mb-8 p-6 bg-gray-50 dark:bg-gray-700/30 rounded-xl shadow-sm transition-all duration-300 hover:shadow-md">
                    <h5 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-4 flex items-center justify-between cursor-pointer border-b border-gray-200 dark:border-gray-700 pb-3" @click="categoryOpen = !categoryOpen">
                        <span>
                            Catégorie: <span class="capitalize text-blue-600 dark:text-blue-400">{{ str_replace('_', ' ', $category ?: 'Général') }}</span>
                            <span class="text-sm font-normal text-gray-500 dark:text-gray-400 ml-2">({{ count($permissionsInGroup) }} permissions)</span>
                        </span>
                        {{-- Icône de flèche pour indiquer l'état fermé/ouvert --}}
                        <svg x-bind:class="{ 'rotate-180': categoryOpen }" class="h-5 w-5 text-gray-500 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </h5>
                    <div x-show="categoryOpen" x-collapse.duration.300ms class="mt-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                            @foreach ($permissionsInGroup as $permission)
                                @php
                                    $percentage = $allRolesCount > 0 ? round(($permission['count'] / $allRolesCount) * 100) : 0;
                                @endphp
                                <div class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 transition-all duration-200 hover:shadow-xl">
                                    <h6 class="font-bold text-xl mb-3 text-gray-900 dark:text-gray-100">
                                        {{ $permission['name'] }}
                                    </h6>

                                    {{-- Progress Bar --}}
                                    <div class="mb-4">
                                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-1">
                                            Attribuée à <span class="font-bold text-blue-600 dark:text-blue-400">{{ $permission['count'] }}</span> rôles ({{ $percentage }}%) :
                                        </p>
                                        <div class="w-full bg-blue-100 rounded-full h-3 dark:bg-gray-700/50">
                                            <div class="h-3 rounded-full bg-blue-500 transition-all duration-500 ease-out" style="width: {{ $percentage }}%"></div>
                                        </div>
                                    </div>

                                    @if ($permission['count'] > 0)
                                        {{-- DÉTAILS DES RÔLES : DÉFAUT FERMÉ --}}
                                        <div x-data="{ rolesOpen: false }">
                                            <h6 class="font-medium text-gray-700 dark:text-gray-300 mb-1 flex justify-between items-center cursor-pointer" @click="rolesOpen = !rolesOpen">
                                                Rôles associés ({{ $permission['count'] }})
                                                <svg x-bind:class="{ 'rotate-90': rolesOpen }" class="h-4 w-4 text-gray-400 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                            </h6>
                                            <ul x-show="rolesOpen" x-collapse.duration.300ms class="list-disc list-inside text-sm text-gray-600 dark:text-gray-400 space-y-0.5 max-h-20 overflow-y-auto custom-scrollbar pl-3 pt-1">
                                                @foreach ($permission['roles'] as $roleName)
                                                    <li class="truncate">{{ $roleName }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @else
                                        <p class="text-gray-500 dark:text-gray-400 text-sm mt-3">Aucun rôle ne dispose de cette permission.</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>


    {{-- Stats sur les Rôles --}}
    {{-- SECTION RÔLES : DÉFAUT FERMÉ --}}
    <div x-data="{ open: false }" class="pt-6">
        <h4 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-8 flex items-center justify-between cursor-pointer" @click="open = !open">
            <div class="flex items-center">
                <svg class="h-6 w-6 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20h-5.022m0-13-2.5 5.5m0 0a3.1 3.1 0 01-2.984 2.5H6.5a3.1 3.1 0 01-3.08-2.5m10.584 2.5a3.1 3.1 0 002.984-2.5H18.5a3.1 3.1 0 003.08-2.5H20M12 12a3 3 0 100-6 3 3 0 000 6z"></path>
                </svg>
                Rôles Attribués aux Utilisateurs
            </div>
            {{-- Icône de flèche pour indiquer l'état fermé/ouvert --}}
            <svg x-bind:class="{ 'rotate-180': open }" class="h-5 w-5 text-gray-500 transform transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </h4>
        <div x-show="open" x-collapse.duration.300ms>
            @if(empty($roleUserCounts))
                <div class="bg-blue-50 dark:bg-blue-900/30 p-8 rounded-xl text-center text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-700 shadow-inner">
                    <p class="mb-2 text-lg font-medium">Aucune statistique de rôle n'est disponible.</p>
                    <p class="text-sm">Veuillez créer des rôles et les attribuer à des utilisateurs pour voir les données ici.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @php
                        try {
                            $totalUsersWithRoles = app($userModelClass)::count();
                        } catch (\Exception $e) {
                            $totalUsersWithRoles = 0;
                        }
                    @endphp
                    @foreach ($roleUserCounts as $roleId => $data)
                        {{-- DÉTAILS DU RÔLE : DÉFAUT FERMÉ --}}
                        <div x-data="{ roleDetailsOpen: false }"
                             class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 transition-all duration-200 hover:shadow-xl">
                            <h5 class="font-bold text-xl mb-2 text-gray-900 dark:text-gray-100 flex items-center justify-between cursor-pointer" @click="roleDetailsOpen = !roleDetailsOpen">
                                <span>{{ $data['name'] }}</span>
                                <svg x-bind:class="{ 'rotate-90': roleDetailsOpen }" class="h-5 w-5 text-gray-400 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </h5>

                            {{-- Progress Bar (User Count) --}}
                            @php
                                $percentageUsers = $totalUsersWithRoles > 0 ? round(($data['count'] / $totalUsersWithRoles) * 100) : 0;
                            @endphp
                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-1">
                                Utilisateurs : <span class="font-bold text-blue-600 dark:text-blue-400">{{ $data['count'] }}</span> ({{ $percentageUsers }}% total)
                            </p>
                            <div class="w-full bg-blue-100 rounded-full h-3 dark:bg-gray-700/50 mb-4">
                                <div class="h-3 rounded-full bg-blue-500 transition-all duration-500 ease-out" style="width: {{ $percentageUsers }}%"></div>
                            </div>

                            {{-- Détails (Users et Catégories associées) --}}
                            <div x-show="roleDetailsOpen" x-collapse.duration.300ms class="pt-3 border-t border-gray-200 dark:border-gray-700 space-y-3">
                                @if ($data['count'] > 0)
                                    <h6 class="font-medium text-gray-700 dark:text-gray-300">Utilisateurs associés :</h6>
                                    <ul class="list-disc list-inside text-sm text-gray-600 dark:text-gray-400 space-y-0.5 max-h-20 overflow-y-auto custom-scrollbar pl-3">
                                        @foreach ($data['users'] as $userName)
                                            <li class="truncate">{{ $userName }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-gray-500 dark:text-gray-400 text-sm">Aucun utilisateur ne dispose de ce rôle.</p>
                                @endif

                                @if (!empty($data['categories']))
                                    <h6 class="font-medium text-gray-700 dark:text-gray-300 pt-2">Catégories de ce rôle :</h6>
                                    <ul class="list-disc list-inside text-sm text-gray-600 dark:text-gray-400 space-y-0.5 max-h-20 overflow-y-auto custom-scrollbar pl-3">
                                        @foreach ($data['categories'] as $categoryName)
                                            <li class="capitalize truncate">{{ $categoryName }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-gray-500 dark:text-gray-400 text-sm pt-2">Aucune catégorie de permission n'est associée à ce rôle.</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Scrollbar style (unchanged) --}}
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: var(--tw-bg-gray-100, #f3f4f6);
            border-radius: 10px;
        }

        .dark .custom-scrollbar::-webkit-scrollbar-track {
            background: var(--tw-bg-gray-700, #374151);
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #93c5fd;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #60a5fa;
        }

        .custom-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: #93c5fd #f3f4f6;
        }
    </style>
</div>
