<div>
    <div x-data="{ show: @entangle('showModal') }" x-show="show" x-cloak
         class="fixed inset-0 z-50 overflow-y-auto p-4 sm:p-6 lg:p-8">
        <div class="flex items-center justify-center min-h-screen">

            {{-- Overlay --}}
            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:leave="ease-in duration-200"
                 x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-900/70 backdrop-blur-sm transition-opacity"></div>

            {{-- Modal Panel --}}
            <div x-show="show" x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl transform transition-all w-full max-w-lg md:max-w-3xl lg:max-w-4xl max-h-[95vh] overflow-y-auto">

                {{-- Modal Header --}}
                <div class="sticky top-0 z-10 flex items-center justify-between p-6 border-b border-gray-100 dark:border-gray-700/50 bg-white dark:bg-gray-800 rounded-t-2xl">
                    <h3 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 flex items-center">
                        <svg class="h-6 w-6 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 9v3m0 3h.01m-6 0h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Créer un Nouvel Administrateur
                    </h3>
                    <button type="button" wire:click="closeModal"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
                        <span class="sr-only">Fermer</span>
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="createAdmin">
                    {{-- Contenu du Formulaire --}}
                    <div class="p-6 space-y-10 pb-20">

                        {{-- Section 1: Informations Générales --}}
                        <section class="border-b pb-6 border-gray-100 dark:border-gray-700/50">
                            <h4 class="text-xl font-medium text-gray-700 dark:text-gray-200 mb-6 flex items-center">
                                <svg class="h-5 w-5 text-blue-500 mr-2" fill="none" stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Informations de base
                            </h4>

                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                                @php
                                    $fields = [
                                        ['model' => 'nom', 'label' => 'Nom', 'required' => true],
                                        ['model' => 'prenom', 'label' => 'Prénom', 'required' => true],
                                        ['model' => 'email', 'label' => 'Email (optionnel)', 'type' => 'email'],
                                        ['model' => 'telephone', 'label' => 'Téléphone', 'type' => 'tel'],
                                        ['model' => 'pays', 'label' => 'Pays (optionnel)'],
                                        ['model' => 'ville', 'label' => 'Ville (optionnel)'],
                                    ];
                                @endphp

                                @foreach ($fields as $field)
                                    <div>
                                        <label for="{{ $field['model'] }}"
                                               class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">
                                            {{ $field['label'] }}
                                            @if(!empty($field['required']))<span class="text-red-500">*</span>@endif
                                        </label>
                                        <input type="{{ $field['type'] ?? 'text' }}"
                                               id="{{ $field['model'] }}"
                                               wire:model.defer="{{ $field['model'] }}"
                                               class="w-full px-4 py-2 text-base rounded-xl shadow-inner border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100
                                                      focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out @error($field['model']) border-red-500 ring-red-500/50 @enderror">
                                        @error($field['model'])
                                        <p class="mt-1 text-xs text-red-500 dark:text-red-400 font-medium">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endforeach
                            </div>
                        </section>

                        {{-- Section 2: Fichiers --}}
                        <section class="border-b pb-6 border-gray-100 dark:border-gray-700/50">
                            <h4 class="text-xl font-medium text-gray-700 dark:text-gray-200 mb-6 flex items-center">
                                <svg class="h-5 w-5 text-blue-500 mr-2" fill="none" stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M4 16l4.586-4.586a2 2 0 012.828 0L15 15m0 0l4.818-4.818a2 2 0 012.828 0M16 16l-3.5-3.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Fichiers et Identité
                            </h4>

                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                                {{-- Photo Profil --}}
                                <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                    <label for="photoProfil"
                                           class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-3">Photo
                                        de Profil (Max 2MB)</label>
                                    <input type="file" id="photoProfil" wire:model="photoProfil"
                                           class="file-input-style">
                                    @error('photoProfil')
                                    <p class="mt-1 text-xs text-red-500 dark:text-red-400 font-medium">{{ $message }}</p>
                                    @enderror
                                    @if ($photoProfil)
                                        <div class="mt-4 flex items-center justify-center">
                                            <img src="{{ $photoProfil->temporaryUrl() }}"
                                                 class="h-24 w-24 object-cover rounded-full shadow-lg border-4 border-blue-200 dark:border-blue-700"
                                                 alt="Prévisualisation Photo Profil">
                                        </div>
                                    @endif
                                </div>

                                {{-- Recto --}}
                                <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                    <label for="pieceIdentiteRecto"
                                           class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-3">Pièce
                                        d'Identité Recto (Max 2MB)</label>
                                    <input type="file" id="pieceIdentiteRecto" wire:model="pieceIdentiteRecto"
                                           class="file-input-style">
                                    @error('pieceIdentiteRecto')
                                    <p class="mt-1 text-xs text-red-500 dark:text-red-400 font-medium">{{ $message }}</p>
                                    @enderror
                                    @if ($pieceIdentiteRecto)
                                        <div class="mt-4 flex items-center justify-center">
                                            <img src="{{ $pieceIdentiteRecto->temporaryUrl() }}"
                                                 class="max-h-24 max-w-full object-contain rounded-md shadow-md"
                                                 alt="Prévisualisation Recto">
                                        </div>
                                    @endif
                                </div>

                                {{-- Verso --}}
                                <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                    <label for="pieceIdentiteVerso"
                                           class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-3">Pièce
                                        d'Identité Verso (Max 2MB)</label>
                                    <input type="file" id="pieceIdentiteVerso" wire:model="pieceIdentiteVerso"
                                           class="file-input-style">
                                    @error('pieceIdentiteVerso')
                                    <p class="mt-1 text-xs text-red-500 dark:text-red-400 font-medium">{{ $message }}</p>
                                    @enderror
                                    @if ($pieceIdentiteVerso)
                                        <div class="mt-4 flex items-center justify-center">
                                            <img src="{{ $pieceIdentiteVerso->temporaryUrl() }}"
                                                 class="max-h-24 max-w-full object-contain rounded-md shadow-md"
                                                 alt="Prévisualisation Verso">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </section>

                        {{-- Section 3: Attribution des Rôles --}}
                        <section>
                            <h4 class="text-xl font-medium text-gray-700 dark:text-gray-200 mb-6 flex items-center">
                                <svg class="h-5 w-5 text-blue-500 mr-2" fill="none" stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-6 0h6m-6 0v2"/>
                                </svg>
                                Attribution des Rôles
                            </h4>
                            <livewire:adminbase::super-admin.manage-admins.admin-role-selector />
                        </section>
                    </div>

                    {{-- Boutons d'action (inchangés) --}}
                    <div class="sticky bottom-0 bg-white dark:bg-gray-800 p-4 border-t border-gray-100 dark:border-gray-700/50 rounded-b-2xl flex justify-end gap-3">
                        <button type="button" wire:click="closeModal"
                                class="order-2 sm:order-1 min-w-0 px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                            Annuler
                        </button>

                        <button type="submit"
                                class="order-1 sm:order-2 min-w-0 px-6 py-2 border border-transparent rounded-xl shadow-md text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800"
                                wire:loading.attr="disabled">
                            <span wire:loading.remove>Créer l'Administrateur</span>
                            <span wire:loading>Création...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .file-input-style {
            display: block;
            width: 100%;
            font-size: 0.875rem;
            color: var(--tw-text-gray-500, #6b7280);
        }
        .file-input-style::-webkit-file-upload-button {
            padding: 0.5rem 0.75rem;
            margin-right: 0.5rem;
            border-radius: 9999px;
            border: 0;
            font-size: 0.875rem;
            font-weight: 600;
            background-color: var(--tw-bg-blue-50, #eff6ff);
            color: var(--tw-text-blue-700, #1d4ed8);
            cursor: pointer;
            transition: background-color 150ms;
        }
        .file-input-style:hover::-webkit-file-upload-button {
            background-color: var(--tw-bg-blue-100, #dbeafe);
        }
        .dark .file-input-style {
            color: var(--tw-text-gray-300, #d1d5db);
        }
        .dark .file-input-style::-webkit-file-upload-button {
            background-color: var(--tw-bg-gray-700, #374151);
            color: var(--tw-text-blue-300, #93c5fd);
        }
        .dark .file-input-style:hover::-webkit-file-upload-button {
            background-color: var(--tw-bg-gray-600, #4b5563);
        }
        .sticky.bottom-0 {
            box-sizing: border-box;
            max-width: 100%;
            overflow-x: hidden;
        }

        .sticky.bottom-0 button {
            max-width: 100%;
        }
    </style>
</div>
