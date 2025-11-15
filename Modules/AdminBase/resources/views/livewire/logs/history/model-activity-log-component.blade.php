<div class="p-4 bg-gray-100 dark:bg-gray-900 min-h-[calc(100vh-4rem)] font-sans antialiased" x-data="{ isFilterModalOpen: false }" x-cloak>
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-4 transition-colors duration-200">
        {{-- Ce composant Livewire interne pourrait charger l'état de l'historique --}}
        @livewire('adminbase::logs.history.model-history-component')

        <div class="space-y-6">
            <div class="flex flex-col items-center space-y-2">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white text-center tracking-tight">
                    Historique des Modifications 📜
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 text-center max-w-md">
                    Consultez et suivez les modifications en temps réel avec une interface claire et intuitive.
                </p>
            </div>

            {{-- Section de recherche et de filtres --}}
            <div class="space-y-4 sm:grid sm:grid-cols-4 sm:gap-4 sm:space-y-0">
                {{-- Champ de recherche --}}
                <div class="col-span-4 sm:col-span-2 relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="search" placeholder="Rechercher dans l'historique..."
                           class="block w-full rounded-xl border-0 bg-gray-50 dark:bg-gray-700 pl-10 pr-4 py-2.5 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-green-600 dark:focus:ring-green-500 shadow-sm transition-all duration-200">
                </div>

                {{-- Bouton Filtres (Mobile) --}}
                <div class="sm:col-span-2 md:hidden">
                    <button @click="isFilterModalOpen = true" class="w-full flex items-center justify-center space-x-2 rounded-xl py-2.5 px-4 text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path d="M5.5 8c0 .552.448 1 1 1h8c.552 0 1-.448 1-1s-.448-1-1-1h-8c-.552 0-1 .448-1 1zM2 4.5c0-.276.224-.5.5-.5h15a.5.5 0 010 1h-15a.5.5 0 01-.5-.5zM8 11c0-.276.224-.5.5-.5h3a.5.5 0 010 1h-3a.5.5 0 01-.5-.5z" clip-rule="evenodd" fill-rule="evenodd"></path>
                        </svg>
                        <span>Filtres</span>
                    </button>
                </div>

                {{-- Filtres (Desktop) --}}
                <div class="hidden md:grid md:grid-cols-3 md:gap-4 col-span-4">
                    <div>
                        <label for="operationFilter" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Opération</label>
                        <div class="relative">
                            <select wire:model.live="operationFilter" id="operationFilter" class="block w-full rounded-xl border-0 bg-gray-50 dark:bg-gray-700 py-2 pl-3 pr-10 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-green-600 dark:focus:ring-green-500 shadow-sm transition-all duration-200 appearance-none">
                                <option value="">Toutes</option>
                                <option value="created">Création</option>
                                <option value="updated">Mise à jour</option>
                                <option value="deleted">Suppression</option>
                                <option value="restored">Restauration</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-2 text-gray-500 dark:text-gray-400">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label for="logableTypeFilter" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Type de Modèle</label>
                        <div class="relative">
                            <select wire:model.live="logableTypeFilter" id="logableTypeFilter" class="block w-full rounded-xl border-0 bg-gray-50 dark:bg-gray-700 py-2 pl-3 pr-10 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-green-600 dark:focus:ring-green-500 shadow-sm transition-all duration-200 appearance-none">
                                <option value="">Tous les modèles</option>
                                @foreach($logableModels as $model)
                                    <option value="{{ $model }}">{{ ucfirst(class_basename($model)) }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-2 text-gray-500 dark:text-gray-400">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label for="changedByTypeFilter" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Auteur</label>
                        <div class="relative">
                            <select wire:model.live="changedByTypeFilter" id="changedByTypeFilter" class="block w-full rounded-xl border-0 bg-gray-50 dark:bg-gray-700 py-2 pl-3 pr-10 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-green-600 dark:focus:ring-green-500 shadow-sm transition-all duration-200 appearance-none">
                                <option value="">Tous les auteurs</option>
                                @foreach($changedByModels as $model)
                                    <option value="{{ $model }}">{{ ucfirst(class_basename($model)) }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-2 text-gray-500 dark:text-gray-400">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Affichage des logs (Mobile View) --}}
            <div class="mt-4 space-y-4 md:hidden">
                @forelse ($logs as $log)
                    @php
                        $firstChange = $log->changes ? (object) array_values($log->changes)[0] : null;
                        $fieldName = $log->changes ? array_keys($log->changes)[0] : ($log->operation === 'updated' ? 'Champs Multiples' : ($log->operation));
                    @endphp

                    <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-xl shadow-sm border border-gray-200 dark:border-gray-600 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 transition-all duration-200"
                         wire:click="showHistory('{{ $log->logable_id }}', '{{ $log->logable_type }}')">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-sm font-semibold uppercase text-green-600 dark:text-green-400 tracking-wide truncate">
                                {{ class_basename($log->logable_type) }}
                            </span>
                            @if ($log->operation === 'created')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100">
                                    Créé
                                </span>
                            @elseif ($log->operation === 'updated')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-700 dark:text-blue-100">
                                    Mise à jour
                                </span>
                            @elseif ($log->operation === 'deleted')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-100">
                                    Supprimé
                                </span>
                            @elseif ($log->operation === 'restored')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800 dark:bg-cyan-700 dark:text-cyan-100">
                                    Restauration
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-100">
                                    {{ ucfirst($log->operation) }}
                                </span>
                            @endif
                        </div>
                        <div class="space-y-2 text-sm text-gray-600 dark:text-gray-300">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h.01M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="truncate">{{ $log->created_at->format('d M Y à H:i') }}</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span class="truncate">{{ $log->changed_by->nom ?? $log->changed_by->email ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="mt-3 border-t border-gray-200 dark:border-gray-600 pt-3 text-sm text-gray-700 dark:text-gray-300">
                            @if ($log->changes)
                                <p class="font-medium truncate">Champ : {{ ucfirst($fieldName) }} @if(count($log->changes) > 1) (+{{ count($log->changes) - 1 }}) @endif</p>
                                @if ($firstChange)
                                    <div class="mt-2 flex flex-col space-y-1">
                                        <span class="text-xs text-red-600 dark:text-red-400 truncate">
                                            Ancien : {{ Str::limit($firstChange->old, 20) ?? 'N/A' }}
                                        </span>
                                        <span class="text-xs text-green-600 dark:text-green-400 truncate">
                                            Nouveau : {{ Str::limit($firstChange->new, 20) ?? 'N/A' }}
                                        </span>
                                    </div>
                                @endif
                            @else
                                <p class="text-sm text-gray-500 dark:text-gray-400 truncate">Opération : {{ ucfirst($log->operation) }}</p>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-sm text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-700 rounded-xl shadow-sm">
                        <svg class="mx-auto h-10 w-10 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1a9 9 0 1118 0 9 9 0 01-18 0z" />
                        </svg>
                        <h3 class="mt-2 text-base font-medium text-gray-900 dark:text-white">Aucun résultat</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Aucun log correspondant à votre recherche.
                        </p>
                    </div>
                @endforelse
            </div>

            {{-- Affichage des logs (Desktop Table View) --}}
            <div class="hidden md:block overflow-x-auto rounded-xl shadow-md">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700 sticky top-0 z-10">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Par</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Opération</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Modèle</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Champ(s)</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ancienne Valeur</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nouvelle Valeur</th>
                        <th scope="col" class="relative px-4 py-3"><span class="sr-only">Historique</span></th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                    @forelse ($logs as $log)
                        @php
                            $firstChange = $log->changes ? (object) array_values($log->changes)[0] : null;
                            $fieldName = $log->changes ? array_keys($log->changes)[0] : null;
                            $changeCount = $log->changes ? count($log->changes) : 0;
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200">
                            <td class="px-4 py-3 text-sm text-gray-800 dark:text-gray-200 whitespace-nowrap">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3 text-sm">
                                <div class="font-medium text-gray-900 dark:text-white truncate">{{ $log->changed_by->nom ?? $log->changed_by->email ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ class_basename($log->changed_by_type) }}</div>
                            </td>
                            <td class="px-4 py-3">
                                @if ($log->operation === 'created')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100">Créé</span>
                                @elseif ($log->operation === 'updated')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-700 dark:text-blue-100">Mise à jour</span>
                                @elseif ($log->operation === 'deleted')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-100">Supprimé</span>
                                @elseif ($log->operation === 'restored')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800 dark:bg-cyan-700 dark:text-cyan-100"> Restauration </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-100">{{ ucfirst($log->operation) }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white truncate">{{ class_basename($log->logable_type) }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                                @if ($changeCount > 0)
                                    <span class="font-medium text-gray-700 dark:text-gray-300 truncate">{{ ucfirst($fieldName) }}</span>
                                    @if ($changeCount > 1)
                                        <span class="text-xs text-green-500 dark:text-green-400"> (+{{ $changeCount - 1 }})</span>
                                    @endif
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400 truncate max-w-[150px]">
                                {{ Str::limit($firstChange->old ?? 'N/A', 15) }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400 truncate max-w-[150px]">
                                {{ Str::limit($firstChange->new ?? 'N/A', 15) }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <button wire:click="showHistory('{{ $log->logable_id }}', '{{ $log->logable_type }}')" class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 font-medium text-sm py-1.5 px-3 rounded-lg shadow-sm hover:shadow-md transition-all duration-200">
                                    Historique
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-10 text-center text-sm text-gray-500 dark:text-gray-400">
                                <svg class="mx-auto h-10 w-10 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1a9 9 0 1118 0 9 9 0 01-18 0z" />
                                </svg>
                                <h3 class="mt-2 text-base font-medium text-gray-900 dark:text-white">Aucun résultat</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    Aucun log correspondant à votre recherche.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Livewire --}}
            @if ($logs->hasPages())
                <div class="mt-4 p-4 bg-white dark:bg-gray-800 rounded-xl shadow-md">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>

        <div x-show="isFilterModalOpen" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-full" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-full" class="fixed inset-0 z-50 flex items-end justify-center md:hidden">
            {{-- Backdrop (Optionnel, non inclus ici mais souvent ajouté) --}}
            {{-- <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true"></div> --}}

            <div class="w-full bg-white dark:bg-gray-800 rounded-t-2xl shadow-xl p-4 transform transition-all duration-200" @click.outside="isFilterModalOpen = false">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Filtres</h3>
                    <button @click="isFilterModalOpen = false" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="space-y-4">
                    <div>
                        <label for="operationFilterMobile" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Opération</label>
                        <div class="relative">
                            <select wire:model.live="operationFilter" id="operationFilterMobile" class="block w-full rounded-xl border-0 bg-gray-50 dark:bg-gray-700 py-2 pl-3 pr-10 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-green-600 dark:focus:ring-green-500 shadow-sm transition-all duration-200 appearance-none">
                                <option value="">Toutes</option>
                                <option value="created">Création</option>
                                <option value="updated">Mise à jour</option>
                                <option value="deleted">Suppression</option>
                                <option value="restored">Restauration</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-2 text-gray-500 dark:text-gray-400">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label for="logableTypeFilterMobile" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Type de Modèle</label>
                        <div class="relative">
                            <select wire:model.live="logableTypeFilter" id="logableTypeFilterMobile" class="block w-full rounded-xl border-0 bg-gray-50 dark:bg-gray-700 py-2 pl-3 pr-10 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-green-600 dark:focus:ring-green-500 shadow-sm transition-all duration-200 appearance-none">
                                <option value="">Tous les modèles</option>
                                @foreach($logableModels as $model)
                                    <option value="{{ $model }}">{{ ucfirst(class_basename($model)) }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-2 text-gray-500 dark:text-gray-400">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label for="changedByTypeFilterMobile" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Auteur</label>
                        <div class="relative">
                            <select wire:model.live="changedByTypeFilter" id="changedByTypeFilterMobile" class="block w-full rounded-xl border-0 bg-gray-50 dark:bg-gray-700 py-2 pl-3 pr-10 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-green-600 dark:focus:ring-green-500 shadow-sm transition-all duration-200 appearance-none">
                                <option value="">Tous les auteurs</option>
                                @foreach($changedByModels as $model)
                                    <option value="{{ $model }}">{{ ucfirst(class_basename($model)) }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-2 text-gray-500 dark:text-gray-400">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        {{-- L'application du filtre se fait "live" via wire:model.live, mais un bouton est bon pour fermer la modale --}}
                        <button @click="isFilterModalOpen = false" class="px-4 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200">
                            Appliquer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
