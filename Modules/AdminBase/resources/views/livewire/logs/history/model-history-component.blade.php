<div x-data="{ isOpen: @entangle('showModal') }" x-show="isOpen" x-cloak class="relative z-50" role="dialog" aria-modal="true" aria-labelledby="history-modal-title">
    <!-- Fond semi-transparent avec flou -->
    <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity duration-300 backdrop-blur-sm"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

    </div>

    <!-- Conteneur principal -->
    <div class="fixed inset-0 flex items-center justify-center p-4 overflow-y-auto">
        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full max-w-[95vw] sm:max-w-2xl transform transition-all"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="scale-95 translate-y-4"
             x-transition:enter-end="scale-100 translate-y-0"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="scale-100 translate-y-0"
             x-transition:leave-end="scale-95 translate-y-4">

            <!-- En-tête -->
            <div class="flex items-center justify-between p-4 sm:p-6 border-b border-gray-200 dark:border-gray-700">
                <h2 id="history-modal-title" class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-gray-100">Historique des Modifications</h2>
                <button wire:click="close" class="text-gray-500 dark:text-gray-400 hover:text-red-500 transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 p-1 rounded-full" aria-label="Fermer la modale">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Contenu -->
            <div class="p-4 sm:p-6 max-h-[70vh] overflow-y-auto">
                @if(empty($versions))
                    <div class="flex flex-col items-center justify-center py-12 text-center text-gray-500 dark:text-gray-400">
                        <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9.172 16.172L12 19m0-3l2.828 2.828M12 19l-3 3m3-3v3m0 0a4 4 0 01-5.656 0M9.172 16.172a4 4 0 010-5.656L12 7.172M9.172 10.516L7 13.5l-3 3M12 7.172l2.828-2.828m-2.828 2.828L12 4.172m0 3a4 4 0 015.656 0M12 7.172l3 3m-3-3l3 3"></path>
                        </svg>
                        <p class="text-lg font-medium">Aucun historique disponible.</p>
                        <p class="text-sm">Il n'y a pas de modifications à afficher pour cet élément.</p>
                    </div>
                @else
                    <!-- Navigation -->
                    <div class="flex items-center justify-between mb-8 p-4 bg-gray-100 dark:bg-gray-800 rounded-xl border-gray-100 dark:border-gray-700">
                        <button wire:click="prev" @if($currentIndex === 0) disabled @endif
                        class="p-3 rounded-full transition-all duration-300 ease-in-out
                           text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-700
                           hover:bg-blue-500 hover:text-white dark:hover:bg-blue-600 dark:hover:text-white
                            disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:bg-gray-100 disabled:hover:text-gray-600
                            focus:outline-none focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800"
                                aria-label="Étape Précédente"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>

                        <div class="flex flex-col items-center select-none mx-4">
                            <span class="text-xs uppercase font-medium text-gray-500 dark:text-gray-400 tracking-wider">
                                   Progression
                            </span>
                            <div class="flex items-baseline mt-1 space-x-1">
                                <span class="text-4xl font-extrabold text-blue-600 dark:text-blue-400 leading-none">
                                    {{ $currentIndex + 1 }}
                                </span>
                                <span class="text-lg font-semibold text-gray-700 dark:text-gray-300 leading-none">
                                     / {{ count($versions) }}
                                </span>
                            </div>
                            <span class="text-sm font-semibold mt-1 text-gray-600 dark:text-gray-400">
                                @if($currentIndex === 0)
                                    <span class="text-blue-500 dark:text-blue-300 font-bold">Actuel</span>
                                @elseif($currentIndex === count($versions) - 1)
                                    <span class="text-red-500 dark:text-red-400 font-bold">Initial</span>
                                @else
                                    <span class="text-yellow-600 dark:text-yellow-400 font-bold">Historique</span>
                                @endif
                            </span>
                        </div>

                        <button wire:click="next" @if($currentIndex === count($versions) - 1) disabled @endif
                        class="p-3 rounded-full transition-all duration-300 ease-in-out
                           text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-700
                           hover:bg-blue-500 hover:text-white dark:hover:bg-blue-600 dark:hover:text-white
                           disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:bg-gray-100 disabled:hover:text-gray-600
                           focus:outline-none focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800"
                                aria-label="Étape Suivante"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    </div>

                    @php
                        $version = $versions[$currentIndex];
                        $by = $version['by'];
                        $changedByType = $by ? $by->getMorphClass() : 'system';
                        $operator = ($changedByType === 'system') ? 'Système' : ($by->nom ?? $by->last_name ?? '') . ' ' . ($by->prenom ?? $by->first_name ?? '') . ' (' . $changedByType . ')';
                        $isFile = function($value) {
                            return is_string($value) && (
                                str_starts_with($value, 'http://') ||
                                str_starts_with($value, 'https://') ||
                                str_contains($value, 'public/storage/') ||
                                str_contains($value, 'admin/photoProfil/') ||
                                preg_match('/\.(jpe?g|png|gif|svg|webp|pdf|docx?|xlsx?|pptx?|zip|rar)$/i', $value)
                            );
                        };
                    @endphp

                        <!-- Snapshot -->
                    <div class="bg-gray-100 dark:bg-gray-900 rounded-lg p-4 sm:p-6 shadow-md transition-shadow">
                        <div class="mb-4 sm:mb-6 space-y-3 border-b border-gray-200 dark:border-gray-700 pb-4">
                            <div class="flex items-center space-x-2">
                                @if($version['operation'] === 'created')
                                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <p class="text-sm font-semibold text-green-600 dark:text-green-400">Création</p>
                                @elseif($version['operation'] === 'updated')
                                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    <p class="text-sm font-semibold text-blue-600 dark:text-blue-400">Modification</p>
                                @elseif($version['operation'] === 'deleted')
                                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4M3 7h18"></path>
                                    </svg>
                                    <p class="text-sm font-semibold text-red-600 dark:text-red-400">Suppression</p>
                                @elseif($version['operation'] === 'restored')
                                    <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h5m0 0l-7 7m7-7H4m7 7l7-7m-7 7v5h5"></path>
                                    </svg>
                                    <p class="text-sm font-semibold text-yellow-600 dark:text-yellow-400">Restauration</p>
                                @else
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l2.414 2.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8"></path>
                                    </svg>
                                    <p class="text-sm font-semibold text-gray-600 dark:text-gray-400">État Actuel</p>
                                @endif
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Par: <span class="font-normal">{{ $operator }}</span>
                            </p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Date: <span class="font-normal">{{ $version['date']->format('d/m/Y H:i:s') }}</span>
                            </p>
                        </div>

                        <!-- Affichage des champs modifiés -->
                        @if(!empty($version['changed_fields']))
                            <div class="mb-4">
                                <p class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Champs Modifiés :</p>
                                <ul class="space-y-2">
                                    @foreach($version['changed_fields'] as $field => $change)
                                        <li x-data="{ expanded: false }" class="p-2 bg-blue-50 dark:bg-blue-900/50 rounded-md transition-colors"
                                            x-transition:enter="ease-out duration-300"
                                            x-transition:enter-start="opacity-0 translate-y-2"
                                            x-transition:enter-end="opacity-100 translate-y-0">
                                            <p class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ $field }}</p>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">
                                                Ancienne valeur : <span class="font-normal">{{ substr($change['old'] , 0 , 20) ?? 'N/A' }}</span>
                                            </p>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">
                                                Nouvelle valeur : <span class="font-normal">{{ substr($change['new'] , 0 ,20)?? 'N/A' }}</span>
                                            </p>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Affichage mobile -->
                        <div class="sm:hidden divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($version['state'] as $field => $value)
                                <div x-data="{ fullText: false }" class="py-3 @if(is_array($version['changed_fields']) && array_key_exists($field, $version['changed_fields'])) bg-blue-50 dark:bg-blue-900/50 @endif">
                                    <p class="text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ $field }}</p>
                                    <div class="mt-1 text-sm text-gray-900 dark:text-gray-100 break-words">
                                        @if($isFile($value))
                                            @php
                                                $url = str_starts_with($value, 'http') ? $value : asset('storage/' . $value);
                                            @endphp
                                            <a href="{{ $url }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline flex items-center">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17v2a2 2 0 002 2h14a2 2 0 002-2v-2M15 9l-3 3-3-3m3 3V3"></path>
                                                </svg>
                                                Télécharger le fichier
                                            </a>
                                        @else
                                            <span x-show="!fullText" class="inline">
                                                {{ Str::limit($value, 50, '...') }}
                                                @if(strlen($value) > 50)
                                                    <button @click="fullText = true" class="text-blue-600 dark:text-blue-400 hover:underline text-xs ml-1">Voir plus</button>
                                                @endif
                                            </span>
                                            <span x-show="fullText" class="inline">
                                                {{ $value ?? 'N/A' }}
                                                <button @click="fullText = false" class="text-blue-600 dark:text-blue-400 hover:underline text-xs ml-1">Voir moins</button>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                            @if(empty($version['state']))
                                <p class="text-center text-sm text-gray-500 dark:text-gray-400 py-4">État initial vide</p>
                            @endif
                        </div>

                        <!-- Affichage desktop -->
                        <div class="hidden sm:block overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-100 dark:bg-gray-800">
                                <tr>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Champ</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Valeur</th>
                                </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($version['state'] as $field => $value)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors @if(is_array($version['changed_fields']) && array_key_exists($field, $version['changed_fields'])) bg-blue-50 dark:bg-blue-900/50 @endif">
                                        <td class="px-3 py-3 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $field }}</td>
                                        <td class="px-3 py-3 text-sm text-gray-500 dark:text-gray-400">
                                            @if($isFile($value))
                                                @php
                                                    $url = str_starts_with($value, 'http') ? $value : asset('storage/' . $value);
                                                @endphp
                                                <a href="{{ $url }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline flex items-center">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17v2a2 2 0 002 2h14a2 2 0 002-2v-2M15 9l-3 3-3-3m3 3V3"></path>
                                                    </svg>
                                                    Télécharger le fichier
                                                </a>
                                            @else
                                                {{ $value ?? 'N/A' }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                @if(empty($version['state']))
                                    <tr>
                                        <td colspan="2" class="px-3 py-2 text-center text-sm text-gray-500 dark:text-gray-400">État initial vide</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

