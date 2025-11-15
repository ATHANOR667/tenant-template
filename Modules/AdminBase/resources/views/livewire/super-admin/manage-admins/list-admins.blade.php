<div>
    <div class="p-6 bg-white dark:bg-gray-800 shadow-xl rounded-xl">

        {{-- En-tête : Titre, Recherche et Bouton Ajouter --}}
        <div class="mb-6 flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
            <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Liste des Administrateurs</h3>
            <div class="flex flex-col md:flex-row items-center space-y-2 md:space-y-0 md:space-x-4 w-full md:w-auto">
                {{-- Champ de Recherche --}}
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Rechercher un admin..."
                       class="w-full md:w-auto px-4 py-2 text-base rounded-xl shadow-sm
                          border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100
                          focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">

                {{-- Bouton Ajouter Admin (Padding réduit et coins arrondis améliorés) --}}
                <button wire:click="$dispatch('openCreateAdminModal')"
                        class="w-full md:w-auto px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-md
                           focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 focus:ring-offset-white dark:focus:ring-offset-gray-800
                           transition duration-150 ease-in-out flex items-center justify-center space-x-2">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    <span>Ajouter Admin</span>
                </button>
            </div>
        </div>

        @if ($admins->isEmpty() && !$search)
            <p class="text-gray-500 dark:text-gray-400 text-center py-8">Aucun administrateur enregistré.</p>
        @elseif ($admins->isEmpty() && $search)
            <p class="text-gray-500 dark:text-gray-400 text-center py-8">Aucun administrateur trouvé pour "{{ $search }}".</p>
        @else
            {{-- Version Mobile : Liste de cartes (visible sur mobile, cachée sur md+) --}}
            <div class="md:hidden space-y-4">
                @foreach ($admins as $admin)
                    <div wire:key="admin-mobile-{{ $admin->id }}" class="p-4 rounded-xl border border-gray-200 dark:border-gray-700 shadow-md
                                 {{ $admin->trashed() ? 'bg-red-50 dark:bg-red-950/20 opacity-75' : 'bg-white dark:bg-gray-800' }}">
                        <div class="flex items-center mb-4">
                            @if ($admin->photoProfil)
                                <img class="h-12 w-12 rounded-full object-cover" src="{{ asset('storage/'.  $admin->photoProfil) }}" alt="{{ $admin->nom }}">
                            @else
                                <div class="h-12 w-12 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center text-gray-500 dark:text-gray-300 font-bold text-xl">
                                    {{ strtoupper(substr($admin->prenom, 0, 1)) }}{{ strtoupper(substr($admin->nom, 0, 1)) }}
                                </div>
                            @endif
                            <div class="ml-4">
                                <div class="text-base font-semibold text-gray-900 dark:text-gray-100">
                                    {{ $admin->prenom }} {{ $admin->nom }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $admin->telephone }}</div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-y-2 gap-x-4 text-sm">
                            <div>
                                <div class="font-medium text-gray-500 dark:text-gray-400">Matricule</div>
                                <div class="text-gray-800 dark:text-gray-200">{{ $admin->matricule }}</div>
                            </div>
                            <div>
                                <div class="font-medium text-gray-500 dark:text-gray-400">Email</div>
                                <div class="text-gray-800 dark:text-gray-200 truncate">{{ $admin->email ?? 'N/A' }}</div>
                            </div>
                            <div>
                                <div class="font-medium text-gray-500 dark:text-gray-400">Rôles</div>
                                <div class="mt-1">
                                    @forelse ($admin->roles as $role)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $role->name === 'super-admin' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}
                                        {{ $role->name === 'admin' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' : '' }}
                                        {{ $role->name !== 'super-admin' && $role->name !== 'admin' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : '' }}
                                        mb-1">
                                            {{ $role->name }}
                                        </span>
                                    @empty
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                            Aucun Rôle
                                        </span>
                                    @endforelse
                                </div>
                            </div>
                            <div>
                                <div class="font-medium text-gray-500 dark:text-gray-400">Statut</div>
                                <div class="mt-1">
                                    @if ($admin->trashed())
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                            Supprimé
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            Actif
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 border-t border-gray-200 dark:border-gray-700 pt-4 flex justify-end space-x-2">
                            {{-- BOUTON ÉDITER (Conditionnel) --}}
                            @if (!$admin->trashed())
                                <button wire:click="openAdminProfileCard('{{ $admin->id }}')"
                                        class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-600 transition-colors duration-150 p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700"
                                        title="Modifier l'administrateur">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L15.232 5.232z"></path></svg>
                                </button>
                            @endif

                            @if ($admin->trashed())
                                <button wire:click="restoreAdmin('{{ $admin->id }}')"
                                        class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-600 transition-colors duration-150 p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700"
                                        title="Restaurer l'administrateur">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004 12c0 2.972 1.514 5.666 3.765 7.171m6.7-7.171a8.001 8.001 0 011.535 5.293L19 19v-5h.582"></path></svg>
                                </button>
                            @else
                                <button wire:click="deleteAdmin('{{ $admin->id }}')"
                                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-600 transition-colors duration-150 p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700"
                                        title="Supprimer l'administrateur">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Version Desktop : Tableau classique (caché sur mobile, visible sur md+) --}}
            <div class="hidden md:block overflow-x-auto shadow-sm rounded-xl border border-gray-200 dark:border-gray-700">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Nom Complet
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Matricule
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Email
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Rôles
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Statut
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($admins as $admin)
                        <tr wire:key="admin-{{ $admin->id }}" class="{{ $admin->trashed() ? 'bg-red-50 dark:bg-red-950/20 opacity-75' : 'hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if ($admin->photoProfil)
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/'.  $admin->photoProfil) }}" alt="{{ $admin->nom }}">
                                        </div>
                                    @else
                                        <div class="flex-shrink-0 h-10 w-10 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center text-gray-500 dark:text-gray-300 font-bold text-lg">
                                            {{ strtoupper(substr($admin->prenom, 0, 1)) }}{{ strtoupper(substr($admin->nom, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $admin->prenom }} {{ $admin->nom }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $admin->telephone }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                {{ $admin->matricule }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                {{ $admin->email ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                @forelse ($admin->roles as $role)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $role->name === 'super-admin' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}
                                        {{ $role->name === 'admin' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' : '' }}
                                        {{ $role->name !== 'super-admin' && $role->name !== 'admin' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : '' }}
                                        mb-1">
                                        {{ $role->name }}
                                    </span>
                                @empty
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                        Aucun Rôle
                                    </span>
                                @endforelse
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if ($admin->trashed())
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                        Supprimé
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        Actif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    {{-- BOUTON ÉDITER (Conditionnel) --}}
                                    @if (!$admin->trashed())
                                        <button wire:click="openAdminProfileCard('{{ $admin->id }}')"
                                                class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-600 transition-colors duration-150 p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700"
                                                title="Modifier l'administrateur">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L15.232 5.232z"></path></svg>
                                        </button>
                                    @endif

                                    @if ($admin->trashed())
                                        <button wire:click="restoreAdmin('{{ $admin->id }}')"
                                                class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-600 transition-colors duration-150 p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700"
                                                title="Restaurer l'administrateur">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004 12c0 2.972 1.514 5.666 3.765 7.171m6.7-7.171a8.001 8.001 0 011.535 5.293L19 19v-5h.582"></path></svg>
                                        </button>
                                    @else
                                        <button wire:click="deleteAdmin('{{ $admin->id }}')"
                                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-600 transition-colors duration-150 p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700"
                                                title="Supprimer l'administrateur">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $admins->links() }}
            </div>
        @endif
    </div>
</div>
