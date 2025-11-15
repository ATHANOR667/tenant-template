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
                 class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl transform transition-all w-full max-w-5xl max-h-[95vh] overflow-y-auto">

                {{-- Modal Header --}}
                <div class="sticky top-0 z-10 flex items-center justify-between p-6 border-b border-gray-100 dark:border-gray-700/50 bg-white dark:bg-gray-800 rounded-t-2xl">
                    <h3 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 flex items-center">
                        <svg class="h-6 w-6 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A9.973 9.973 0 0112 15c1.444 0 2.813.386 4.024 1.054M15 11a3 3 0 11-6 0 3 3 0 016 0zM21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ $admin ? 'Profil de ' . $admin->prenom . ' ' . $admin->nom : 'Chargement...' }}
                    </h3>
                    <button type="button" wire:click="closeModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
                        <span class="sr-only">Fermer</span>
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                @if ($admin)
                    <div  class="p-6 space-y-10">

                        {{-- Section 1: Informations Générales & Contact (inchangée) --}}
                        <section class="border-b pb-6 border-gray-100 dark:border-gray-700/50">
                            <h4 class="text-xl font-medium text-gray-700 dark:text-gray-200 mb-6 flex items-center">
                                <svg class="h-5 w-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                Détails Personnels
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
                                        <label for="{{ $field['model'] }}" class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">
                                            {{ $field['label'] }} @if(isset($field['required']) && $field['required'])<span class="text-red-500">*</span>@endif
                                        </label>
                                        <input type="{{ $field['type'] ?? 'text' }}" id="{{ $field['model'] }}" wire:model.defer="{{ $field['model'] }}"
                                               class="w-full px-4 py-2 text-base rounded-xl shadow-inner border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100
                                          focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out @error($field['model']) border-red-500 ring-red-500/50 @enderror">
                                        @error($field['model']) <p class="mt-1 text-xs text-red-500 dark:text-red-400 font-medium">{{ $message }}</p> @enderror
                                    </div>
                                @endforeach
                            </div>
                        </section>

                        {{-- Section 2: Sécurité & Connexion (inchangée) --}}
                        <section class="border-b pb-6 border-gray-100 dark:border-gray-700/50">
                            <h4 class="text-xl font-medium text-gray-700 dark:text-gray-200 mb-6 flex items-center">
                                <svg class="h-5 w-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2v6a2 2 0 01-2 2h-4a2 2 0 01-2-2v-6a2 2 0 012-2h4z"></path></svg>
                                Détails de Sécurité
                            </h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4 text-sm bg-gray-50 dark:bg-gray-700/50 p-4 rounded-xl">

                                {{-- Mot de passe --}}
                                <div class="flex flex-col">
                                    <dt class="font-medium text-gray-500 dark:text-gray-400">Mot de Passe</dt>
                                    <dd class="mt-1 font-semibold text-gray-900 dark:text-gray-100">{{ $admin->password ? '********' : 'Non défini' }}</dd>
                                </div>
                                {{-- Dernier changement de mot de passe --}}
                                <div class="flex flex-col">
                                    <dt class="font-medium text-gray-500 dark:text-gray-400">Dernier changement</dt>
                                    <dd class="mt-1 text-gray-900 dark:text-gray-100">{{ $admin->password_changed_at ? $admin->password_changed_at->format('d/m/Y H:i') : 'Jamais' }}</dd>
                                </div>
                                {{-- Passcode --}}
                                <div class="flex flex-col">
                                    <dt class="font-medium text-gray-500 dark:text-gray-400">Passcode</dt>
                                    <dd class="mt-1 font-semibold text-gray-900 dark:text-gray-100">{{ $admin->passcode ? '********' : 'Non défini' }}</dd>
                                </div>
                                {{-- Date de réinitialisation Passcode --}}
                                <div class="flex flex-col">
                                    <dt class="font-medium text-gray-500 dark:text-gray-400">Statut réinitialisation</dt>
                                    <dd class="mt-1 text-gray-900 dark:text-gray-100">{{ $admin->passcode_reset_date ? $admin->passcode_reset_date->format('d/m/Y H:i') : 'Jamais' }}</dd>
                                </div>
                            </div>
                        </section>

                        {{-- Section 3: Images & Fichiers --}}
                        <section class="border-b pb-6 border-gray-100 dark:border-gray-700/50">
                            <h4 class="text-xl font-medium text-gray-700 dark:text-gray-200 mb-6 flex items-center">
                                <svg class="h-5 w-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L15 15m0 0l4.818-4.818a2 2 0 012.828 0M16 16l-3.5-3.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Fichiers et Identité
                            </h4>
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                                {{-- Photo de Profil (inchangée) --}}
                                <div class="lg:col-span-1 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                    <label class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-3">Photo de Profil</label>
                                    <div class="flex flex-col items-center space-y-4">
                                        <div class="relative">
                                            @if ($newPhotoProfil)
                                                <img src="{{ $newPhotoProfil->temporaryUrl() }}" class="h-28 w-28 object-cover rounded-full shadow-lg border-4 border-blue-200 dark:border-blue-700" alt="Nouvelle Photo">
                                            @elseif ($admin->photoProfil)
                                                <img src="{{ asset('storage/' . $admin->photoProfil) }}" class="h-28 w-28 object-cover rounded-full shadow-lg border-4 border-white dark:border-gray-700" alt="Photo Actuelle">
                                            @else
                                                <div class="h-28 w-28 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center text-blue-600 dark:text-blue-300 text-4xl font-light border-4 border-white dark:border-gray-700">
                                                    {{ strtoupper(substr($prenom, 0, 1)) }}{{ strtoupper(substr($nom, 0, 1)) }}
                                                </div>
                                            @endif
                                            <div wire:loading wire:target="newPhotoProfil" class="absolute inset-0 bg-black/50 rounded-full flex items-center justify-center text-white text-sm">Chargement...</div>
                                        </div>

                                        <input type="file" id="newPhotoProfil" wire:model="newPhotoProfil" class="file-input-style">
                                        @error('newPhotoProfil') <p class="mt-1 text-xs text-red-500 dark:text-red-400 font-medium">{{ $message }}</p> @enderror

                                        @if ($admin->photoProfil)
                                            <label class="inline-flex items-center text-sm text-gray-600 dark:text-gray-400">
                                                <input type="checkbox" wire:model.defer="deletePhotoProfil" class="form-checkbox h-4 w-4 text-red-600 border-gray-300 dark:border-gray-600 rounded">
                                                <span class="ml-2">Supprimer la photo actuelle</span>
                                            </label>
                                        @endif
                                    </div>
                                </div>

                                {{-- Pièces d'Identité (Recto/Verso) & QR Code --}}
                                <div class="lg:col-span-2 space-y-6">

                                    {{-- Pièce d'Identité Flip Card (CORRIGÉ) --}}
                                    <div x-data="{ showRecto: @entangle('showPieceIdentiteRecto'), isFlipping: @entangle('isFlipping') }" class="relative bg-gray-50 dark:bg-gray-700/50 p-4 rounded-xl">
                                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-3">Vérification d'Identité</label>
                                        <div class="flip-card w-full h-48 rounded-lg overflow-hidden border border-gray-300 dark:border-gray-600 shadow-md perspective-1000">
                                            <div class="flip-card-inner w-full h-full relative" :class="showRecto ? '' : 'flipping'">

                                                {{-- Recto --}}
                                                <div class="flip-card-front w-full h-full absolute bg-gray-100 dark:bg-gray-700 flex flex-col items-center justify-center backface-hidden p-2">
                                                    @php
                                                        $rectoSource = $newPieceIdentiteRecto ? $newPieceIdentiteRecto->temporaryUrl() : ($admin->pieceIdentiteRecto ? asset('storage/' . $admin->pieceIdentiteRecto) : null);
                                                    @endphp
                                                    @if ($rectoSource)
                                                        <img src="{{ $rectoSource }}" alt="Pièce d'Identité Recto"
                                                             class="object-contain max-h-[85%] max-w-full cursor-pointer rounded-md"
                                                             @click="isFlipping = true; setTimeout(() => { showRecto = false; isFlipping = false; }, 500);">
                                                        <span class="absolute bottom-1 text-xs font-semibold text-gray-700 dark:text-gray-300">RECTO (Cliquer pour Verso)</span>
                                                    @else
                                                        <p class="text-gray-500 dark:text-gray-400">Recto non disponible</p>
                                                    @endif
                                                    <div wire:loading wire:target="newPieceIdentiteRecto" class="absolute inset-0 bg-black/50 flex items-center justify-center text-white text-sm">Chargement...</div>
                                                </div>

                                                {{-- Verso --}}
                                                <div class="flip-card-back w-full h-full absolute bg-gray-100 dark:bg-gray-700 flex flex-col items-center justify-center backface-hidden p-2">
                                                    @php
                                                        $versoSource = $newPieceIdentiteVerso ? $newPieceIdentiteVerso->temporaryUrl() : ($admin->pieceIdentiteVerso ? asset('storage/' . $admin->pieceIdentiteVerso) : null);
                                                    @endphp
                                                    @if ($versoSource)
                                                        <img src="{{ $versoSource }}" alt="Pièce d'Identité Verso"
                                                             class="object-contain max-h-[85%] max-w-full cursor-pointer rounded-md"
                                                             @click="isFlipping = true; setTimeout(() => { showRecto = true; isFlipping = false; }, 500);">
                                                        <span class="absolute bottom-1 text-xs font-semibold text-gray-700 dark:text-gray-300">VERSO (Cliquer pour Recto)</span>
                                                    @else
                                                        <p class="text-gray-500 dark:text-gray-400">Verso non disponible</p>
                                                    @endif
                                                    <div wire:loading wire:target="newPieceIdentiteVerso" class="absolute inset-0 bg-black/50 flex items-center justify-center text-white text-sm">Chargement...</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                                            <div>
                                                <label for="newPieceIdentiteRecto" class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">Recto (Max 2MB)</label>
                                                <input type="file" id="newPieceIdentiteRecto" wire:model="newPieceIdentiteRecto" class="file-input-style">
                                                @error('newPieceIdentiteRecto') <p class="mt-1 text-xs text-red-500 dark:text-red-400 font-medium">{{ $message }}</p> @enderror
                                                @if ($admin->pieceIdentiteRecto && !$newPieceIdentiteRecto)
                                                    <label class="inline-flex items-center mt-1 text-xs text-gray-500 dark:text-gray-400">
                                                        <input type="checkbox" wire:model.defer="deletePieceIdentiteRecto" class="form-checkbox h-3 w-3 text-red-600 border-gray-300 dark:border-gray-600 rounded">
                                                        <span class="ml-1">Supprimer l'actuel Recto</span>
                                                    </label>
                                                @endif
                                            </div>
                                            <div>
                                                <label for="newPieceIdentiteVerso" class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">Verso (Max 2MB)</label>
                                                <input type="file" id="newPieceIdentiteVerso" wire:model="newPieceIdentiteVerso" class="file-input-style">
                                                @error('newPieceIdentiteVerso') <p class="mt-1 text-xs text-red-500 dark:text-red-400 font-medium">{{ $message }}</p> @enderror
                                                @if ($admin->pieceIdentiteVerso && !$newPieceIdentiteVerso)
                                                    <label class="inline-flex items-center mt-1 text-xs text-gray-500 dark:text-gray-400">
                                                        <input type="checkbox" wire:model.defer="deletePieceIdentiteVerso" class="form-checkbox h-3 w-3 text-red-600 border-gray-300 dark:border-gray-600 rounded">
                                                        <span class="ml-1">Supprimer l'actuel Verso</span>
                                                    </label>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    {{-- QR Code du Matricule (inchangé) --}}
                                    <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl flex items-center justify-between">
                                        <div class="space-y-1">
                                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-300">Code QR du Matricule</label>
                                            <p class="text-xl font-bold text-gray-900 dark:text-gray-100">
                                                {{ $admin->matricule }}
                                            </p>
                                        </div>
                                        <div class="p-2 bg-white rounded-lg shadow-inner">
                                            {{-- Remplacez par le code généré par QrCode si nécessaire --}}
                                            {!! QrCode::size(80)->generate($admin->matricule) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        {{-- Section 4: Attribution des Rôles (Utilisation du sous-composant) --}}
                        <section>
                            <h4 class="text-xl font-medium text-gray-700 dark:text-gray-200 mb-6 flex items-center">
                                <svg class="h-5 w-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-6 0h6m-6 0v2"></path></svg>
                                Attribution des Rôles
                            </h4>

                            {{-- INSERTION DU SOUS-COMPOSANT  --}}
                            <livewire:adminbase::super-admin.manage-admins.admin-role-selector :admin="$admin" />

                        </section>

                        {{-- Boutons d'action (inchangés) --}}
                        <div class="sticky bottom-0 bg-white dark:bg-gray-800 p-4 -mx-6 border-t
                                  border-gray-100 dark:border-gray-700/50 rounded-b-2xl flex flex-wrap justify-end gap-3">
                            <button type="button" wire:click="closeModal"
                                    class="order-3 sm:order-1 flex-1 sm:flex-none w-full sm:w-auto px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                                Fermer
                            </button>
                            <button type="button" wire:click="generatePdf"
                                    class="order-2 sm:order-2 flex-1 sm:flex-none w-full sm:w-auto px-6 py-2 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-red-500 hover:bg-red-600 transition-colors
                               focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:focus:ring-offset-gray-800">
                                <svg class="h-4 w-4 inline mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Générer PDF
                            </button>
                            <button type="submit"
                                    class="order-1 sm:order-3 flex-1 sm:flex-none w-full sm:w-auto px-6 py-2 border border-transparent rounded-xl shadow-md text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors
                               focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800"
                                    wire:loading.attr="disabled" >
                                <span wire:loading.remove wire:click="updateAdmin">Mettre à jour le Profil</span>
                                <span wire:loading >Mise à jour...</span>
                            </button>
                        </div>
                    </div>
                @else
                    <div class="p-12 text-center text-gray-500 dark:text-gray-400">
                        Chargement du profil ou administrateur non trouvé...
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        /* Styles pour le File Input */
        .file-input-style {
            display: block;
            width: 100%;
            font-size: 0.875rem; /* text-sm */
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

        /* Flip Card Styles (3D Effect) */
        .perspective-1000 {
            perspective: 1000px;
        }
        .flip-card-inner {
            transform-style: preserve-3d;
            transition: transform 0.6s;
        }
        .flipping {
            transform: rotateY(180deg);
        }
        .flip-card-front, .flip-card-back {
            backface-visibility: hidden;
        }
        .flip-card-back {
            transform: rotateY(180deg);
        }
    </style>
</div>
