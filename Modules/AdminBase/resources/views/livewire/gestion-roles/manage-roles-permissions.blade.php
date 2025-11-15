<div
    x-data="{
        createFormOpen: false,
        selectedRoleId: @entangle('selectedRoleId').live,
        rolePermissions: @entangle('rolePermissions').live,

        isPermissionsModified: false,
        initialPermissions: [],

        checkIfModified() {
            if (! Array.isArray(this.rolePermissions)) {
                this.isPermissionsModified = false;
                return;
            }

            const current = new Set(this.rolePermissions.map(id => parseInt(id)));
            const initial = new Set(this.initialPermissions.map(id => parseInt(id)));

            if (current.size !== initial.size) {
                this.isPermissionsModified = true;
                return;
            }

            this.isPermissionsModified = ![...initial].every(id => current.has(id));
        },

        updateInitialState() {
            this.initialPermissions = [...this.rolePermissions];
            this.isPermissionsModified = false;
        },

        init() {
            this.updateInitialState();
            this.$watch('rolePermissions', () => this.checkIfModified());
            Livewire.on('roleSelected', () => this.updateInitialState());
            Livewire.on('dataUpdate', () => this.updateInitialState());
            Livewire.on('roleDeselected', () => {
                this.initialPermissions = [];
                this.isPermissionsModified = false;
            });
        }
    }"
    class="container mx-auto px-4 py-10 antialiased"
>

    {{-- Header (unchanged) --}}
    <div class="mb-10">
        <h1 class="text-4xl font-light text-gray-900 dark:text-gray-100 mb-2 border-b border-gray-200 dark:border-gray-700 pb-4">
            Gestion des Accès
        </h1>
        <p class="text-lg text-gray-600 dark:text-gray-400">
            Configurez les permissions pour le Guard :
            <span class="font-medium text-blue-600 dark:text-blue-400">{{ $guardName }}</span>
        </p>
    </div>

    {{-- Main Layout (unchanged) --}}
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-8">

        {{-- Column 1: Role List & Create Form (unchanged) --}}
        <div class="md:col-span-1 lg:col-span-1 space-y-6">

            {{-- Formulaire de Création de Rôle (unchanged) --}}
            <div class="p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-lg transition-shadow duration-300 hover:shadow-xl border border-gray-100 dark:border-gray-700">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center justify-between">
                    Nouveau Rôle
                    <button @click="createFormOpen = !createFormOpen" class="p-1 rounded-full text-blue-500 hover:text-blue-700 dark:hover:text-blue-300 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                        <svg x-show="!createFormOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        <svg x-show="createFormOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </h2>

                <div x-show="createFormOpen" x-collapse.duration.300ms>
                    <div class="mt-4 space-y-4">
                        <input type="text" wire:model.live.debounce.300ms="newRoleName" wire:keydown.enter="createRole"
                               placeholder="Nom du rôle (ex: editeur)"
                               class="block w-full px-4 py-2.5 rounded-xl border @error('newRoleName') border-red-400 ring-red-400 @else border-gray-300 dark:border-gray-600 @enderror dark:bg-gray-700 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out placeholder-gray-400 dark:placeholder-gray-500">
                        @error('newRoleName')
                        <p class="text-sm text-red-500 dark:text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                        <button wire:click="createRole" wire:loading.attr="disabled" wire:target="createRole"
                                class="w-full px-6 py-2.5 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-xl shadow-md transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center space-x-2">
                            <span wire:loading.remove wire:target="createRole">Ajouter le Rôle</span>
                            <span wire:loading wire:target="createRole" class="flex items-center">
                                <svg class="animate-spin h-5 w-5 mr-3 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                Création...
                            </span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Liste des Rôles (unchanged) --}}
            <div class="p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Rôles Disponibles</h3>
                <div class="space-y-3 max-h-96 overflow-y-auto custom-scrollbar">
                    @forelse ($roles as $role)
                        @php
                            $isProtected = in_array($role->name, ['super-admin', 'admin']);
                            $isSelected = $selectedRoleId == $role->id;
                        @endphp
                        <div class="p-4 flex items-center justify-between cursor-pointer rounded-xl transition-all duration-200 ease-in-out
                                    {{ $isSelected
                                        ? 'bg-blue-500 text-white shadow-lg shadow-blue-500/30'
                                        : 'hover:bg-blue-50 dark:hover:bg-gray-700/50 hover:shadow-md dark:text-gray-200' }}"
                             wire:click="selectRole({{ $role->id }})">

                            <span class="flex items-center space-x-3 {{ $isSelected ? 'font-medium' : 'text-gray-700 dark:text-gray-200' }}">
                                <svg class="h-6 w-6 {{ $isSelected ? 'text-white' : 'text-blue-500 dark:text-blue-400' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                                <span>{{ $role->name }}</span>
                            </span>

                            <button wire:click.stop="deleteRole({{ $role->id }})"
                                    class="p-1 rounded-full transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-red-500
                                    {{ $isProtected ? 'opacity-30 cursor-not-allowed text-gray-400' : ($isSelected ? 'text-white hover:bg-white/20' : 'text-red-500 hover:bg-red-100 dark:hover:bg-red-900/50') }}"
                                    title="{{ $isProtected ? 'Rôle protégé' : 'Supprimer ce rôle' }}"
                                {{ $isProtected ? 'disabled' : '' }}>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm6 0a1 1 0 10-2 0v6a1 1 0 102 0V8z" clip-rule="evenodd" /></svg>
                            </button>
                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400 text-center py-4">Aucun rôle trouvé.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Column 2: Permissions (UPDATED CHECKBOX STYLES) --}}
        <div class="md:col-span-2 lg:col-span-3 transition-all duration-300"
             :class="{ 'hidden': selectedRoleId === null, 'md:block': selectedRoleId === null }">

            <div x-show="selectedRoleId" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4 md:translate-y-0" x-transition:enter-end="opacity-100 transform translate-y-0">
                @if ($selectedRole)
                    <div class="p-8 bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700">
                        <h3 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center justify-between border-b dark:border-gray-700 pb-4">
                            Permissions de : <span class="text-blue-600 dark:text-blue-400 font-bold">{{ $selectedRole->name }}</span>
                            <button @click="$wire.selectRole({{ $selectedRole->id }})" class="md:hidden text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 p-2 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <span class="sr-only">Fermer les permissions</span>
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </h3>

                        <div class="max-h-[600px] overflow-y-auto pr-3 custom-scrollbar space-y-8">
                            @forelse ($this->groupedPermissions as $categorie => $permissionsOfCategory)
                                <div x-data="{ categoryOpen: true }" class="p-5 border rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/30 shadow-inner">
                                    <button @click="categoryOpen = !categoryOpen" class="w-full text-left focus:outline-none flex items-center justify-between">
                                        <h4 class="text-lg font-bold text-gray-900 dark:text-white flex items-center space-x-3">
                                            <span class="text-base font-semibold rounded-full px-3 py-1 leading-none transition-colors duration-200
                                                {{ $categorie == 'super-admin' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}
                                                {{ $categorie == 'admin' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' : '' }}
                                                @if (!in_array($categorie, ['super-admin', 'admin'])) bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 @endif">
                                                {{ ucfirst(str_replace('_', ' ', $categorie ?: 'Autres')) }}
                                            </span>
                                            <span class="text-sm font-normal text-gray-500 dark:text-gray-400">({{ $permissionsOfCategory->count() }} permissions)</span>
                                        </h4>
                                        <svg x-bind:class="{ 'rotate-180': categoryOpen }" class="w-5 h-5 transition-transform duration-200 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                    </button>

                                    <div x-show="categoryOpen" x-collapse.duration.300ms class="mt-4 space-y-2 border-t border-gray-200 dark:border-gray-600 pt-4">
                                        @foreach ($permissionsOfCategory as $permission)
                                            <label wire:key="perm-{{ $permission->id }}"
                                                   class="flex items-center space-x-3 text-gray-800 dark:text-gray-100 p-3 rounded-lg cursor-pointer transition-all duration-150 ease-in-out border border-transparent
                                                           {{ in_array($permission->id, $rolePermissions) ? 'bg-blue-50 dark:bg-blue-900/50 border-blue-200 dark:border-blue-700 font-medium' : 'hover:bg-gray-100 dark:hover:bg-gray-700/50' }}">

                                                {{-- CASE À COCHER AMÉLIORÉE --}}
                                                <input type="checkbox" wire:model.live="rolePermissions" value="{{ $permission->id }}"
                                                       class="form-checkbox h-6 w-6 text-blue-500 rounded-md border-2 border-gray-300 dark:border-gray-600 dark:bg-gray-700
                                                              dark:checked:bg-blue-600 dark:checked:border-blue-600 focus:ring-blue-500 focus:ring-offset-white dark:focus:ring-offset-gray-800
                                                              transition duration-200 ease-in-out cursor-pointer hover:border-blue-400 checked:bg-blue-500 checked:border-blue-500">

                                                <span class="text-sm select-none">{{ $permission->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 dark:text-gray-400 text-center py-8">Aucune permission n'a été trouvée pour ce guard.</p>
                            @endforelse
                        </div>

                        {{-- Bouton de Sauvegarde (unchanged) --}}
                        <button wire:click="updateRolePermissions"
                                wire:loading.attr="disabled"
                                wire:target="updateRolePermissions"
                                x-bind:disabled="!isPermissionsModified"
                                class="mt-10 px-8 py-3 w-full bg-green-500 hover:bg-green-600 text-white font-medium rounded-xl shadow-lg transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center space-x-3 text-lg"
                                :class="{ 'shadow-green-500/30': isPermissionsModified, 'shadow-none': !isPermissionsModified }">
                            <span wire:loading.remove wire:target="updateRolePermissions" x-text="isPermissionsModified ? 'Sauvegarder les Modifications (En attente)' : 'Permissions à Jour'">
                                Mettre à jour les Permissions
                            </span>
                            <span wire:loading wire:target="updateRolePermissions" class="flex items-center">
                                <svg class="animate-spin h-5 w-5 mr-3 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                <span>Sauvegarde en cours...</span>
                            </span>
                        </button>
                        @error('rolePermissions')
                        <p class="text-sm text-red-500 dark:text-red-400 mt-2 text-center">{{ $message }}</p>
                        @enderror
                    </div>
                @endif
            </div>

            {{-- Message d'attente (unchanged) --}}
            <div x-show="selectedRoleId === null"
                 class="h-full min-h-[400px] flex flex-col items-center justify-center text-center p-8 bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700">
                <svg class="h-20 w-20 text-blue-300 dark:text-blue-700/50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.55 4.55L15 19m0-4.55h-4.55A6 6 0 1111.45 7H15v3.55z" />
                </svg>
                <p class="mt-4 text-gray-500 dark:text-gray-400 text-xl font-light">
                    Commencez par <span class="font-medium text-blue-500 dark:text-blue-400">sélectionner un rôle</span> pour configurer ses accès.
                </p>
            </div>
        </div>
    </div>
</div>
