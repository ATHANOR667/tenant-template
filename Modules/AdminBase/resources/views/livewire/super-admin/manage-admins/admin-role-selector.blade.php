<div class="space-y-6">

    {{-- Section 1: Sélecteur de Rôles --}}
    <div class="p-4 bg-white dark:bg-gray-800 shadow-md rounded-xl border border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">
            Choisir les Rôles à Attribuer
        </h3>

        <div class="flex flex-wrap gap-3">
            {{-- Parcours de tous les rôles disponibles --}}
            @forelse ($availableRoles as $role)
                @php
                    $roleIdStr = (string)$role->id;
                    // Vérifie l'état actuel de la sélection
                    $isSelected = in_array($roleIdStr, $selectedRoles);
                    // Vérifie si le rôle était déjà attribué (l'état initial)
                    $wasCurrent = in_array($roleIdStr, $currentRoles);

                    // Détermine les classes de style pour l'état
                    $baseClass = 'inline-flex items-center cursor-pointer px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 shadow-sm whitespace-nowrap';
                    $styleClasses = $isSelected
                        ? 'bg-blue-600 text-white hover:bg-blue-700 ring-2 ring-blue-500 ring-offset-2 dark:ring-offset-gray-900'
                        : 'bg-gray-100 text-gray-700 border border-gray-300 hover:bg-blue-50 hover:border-blue-500 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600';
                @endphp

                <div wire:key="role-{{ $role->id }}"
                     wire:click="toggleRole('{{ $role->id }}')"
                     class="{{ $baseClass }} {{ $styleClasses }}">

                    <span>{{ \Illuminate\Support\Str::title($role->name) }}</span>

                    {{-- Indication visuelle de la modification --}}
                    @if ($wasCurrent && !$isSelected)
                        <span class="ml-2 text-red-100 bg-red-500 rounded-full px-1 text-xs">Retrait</span>
                    @elseif (!$wasCurrent && $isSelected)
                        <span class="ml-2 text-green-100 bg-green-500 rounded-full px-1 text-xs">Ajout</span>
                    @endif
                </div>
            @empty
                <p class="text-gray-500 dark:text-gray-400">Aucun rôle disponible.</p>
            @endforelse
        </div>
    </div>

    {{-- Section 2: Affichage des Permissions et Bouton d'Enregistrement --}}
    <div class="flex flex-col lg:flex-row lg:items-start gap-6">

        {{-- Colonne de visualisation des Permissions --}}
        <div class="w-full @if($admin) lg:w-3/4 @endif">
            <div class="p-4 bg-white dark:bg-gray-800 shadow-md rounded-xl border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4 border-b pb-2">
                    Permissions Regroupées de ({{ count($selectedRoles) }} Rôle(s) Sélectionné(s))
                </h3>

                @if (!empty($selectedRolesPermissions))
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach ($selectedRolesPermissions as $category => $permissions)
                            <div wire:key="perm-{{ $category }}" class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-100 dark:border-gray-600">
                                <p class="font-bold text-gray-700 dark:text-gray-200 mb-2 capitalize border-b border-gray-200 dark:border-gray-600 pb-1">
                                    {{ str_replace('_', ' ', $category ?: 'Autres') }} ({{ count($permissions) }})
                                </p>
                                <div class="flex flex-wrap gap-1 text-xs max-h-32 overflow-y-auto">
                                    @foreach ($permissions as $permissionName)
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full dark:bg-blue-900 dark:text-blue-300 shadow-sm">
                                            {{ $permissionName }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 dark:text-gray-400 text-center py-4">
                        Sélectionnez un ou plusieurs rôles pour visualiser l'ensemble des permissions attribuées.
                    </p>
                @endif
            </div>
        </div>

        {{-- Colonne du Bouton d'Enregistrement (Visible uniquement si un administrateur est chargé, en mode édition) --}}
        @if ($admin)
            <div class="lg:sticky lg:top-4 w-full lg:w-1/4">
                <div class="p-4 bg-white dark:bg-gray-800 shadow-md rounded-xl border border-gray-200 dark:border-gray-700">
                    <button wire:click="saveRoles"
                            wire:loading.attr="disabled"
                            wire:target="saveRoles"
                            class="w-full flex items-center justify-center px-4 py-3 border border-transparent rounded-lg font-semibold text-white
                                   bg-green-600 hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600
                                   transition duration-150 ease-in-out disabled:opacity-50 disabled:cursor-not-allowed text-lg">

                        <span wire:loading.remove wire:target="saveRoles">
                            Mettre à jour les Rôles ({{ count($selectedRoles) }})
                        </span>
                        <span wire:loading wire:target="saveRoles" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Mise à jour...
                        </span>
                    </button>

                    <p class="mt-3 text-sm text-gray-500 dark:text-gray-400 text-center">
                        La modification est appliquée directement à l'administrateur.
                    </p>
                </div>
            </div>
        @endif
    </div>

</div>
