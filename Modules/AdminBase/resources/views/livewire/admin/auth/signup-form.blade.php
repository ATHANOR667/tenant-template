<div class="flex items-center justify-center min-h-screen bg-gray-100 dark:bg-gray-900 transition-colors duration-300 ease-in-out">
    <div class="bg-white dark:bg-gray-800 p-6 md:p-8 rounded-3xl shadow-2xl max-w-sm md:max-w-md w-full transition-all duration-300 ease-in-out hover:shadow-3xl transform hover:scale-[1.01]">

        <h2 class="text-3xl md:text-4xl font-bold mb-6 text-center text-gray-800 dark:text-white">Inscription Admin</h2>

        {{-- Étape 1: Saisie du matricule et de l'email --}}
        @if ($currentStep === 1)
            <form wire:submit.prevent="verifyMatriculeAndSendOtp" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
                <div class="mb-6">
                    <label for="matricule" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Matricule:</label>
                    <input type="text" id="matricule" wire:model.live="matricule"
                           class="shadow-sm appearance-none border border-gray-300 dark:border-gray-600 rounded-xl w-full py-3 px-4 text-gray-800 dark:text-white leading-tight focus:outline-none focus:ring-4 focus:ring-blue-200 dark:bg-gray-700 dark:focus:ring-blue-800 transition-all duration-300 ease-in-out"
                           required>
                    @error('matricule') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>

                <div class="mb-8">
                    <label for="email" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Adresse email:</label>
                    <input type="email" id="email" wire:model.live="email"
                           class="shadow-sm appearance-none border border-gray-300 dark:border-gray-600 rounded-xl w-full py-3 px-4 text-gray-800 dark:text-white leading-tight focus:outline-none focus:ring-4 focus:ring-blue-200 dark:bg-gray-700 dark:focus:ring-blue-800 transition-all duration-300 ease-in-out"
                           required autocomplete="email">
                    @error('email') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-center">
                    <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-offset-2 focus:ring-blue-500 dark:bg-blue-700 dark:hover:bg-blue-600 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-900 transition-all duration-300 ease-in-out transform hover:scale-105">
                        Vérifier et Envoyer OTP
                    </button>
                </div>
            </form>
        @endif

        {{-- Étape 2: Vérification de l'OTP --}}
        @if ($currentStep === 2)
            <form wire:submit.prevent="verifyOtp" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
                <div class="mb-6">
                    <p class="text-gray-700 dark:text-gray-300 text-center mb-6">Un code OTP a été envoyé à <span class="font-semibold">{{ $email }}</span>. Veuillez le saisir ci-dessous.</p>
                    <label for="otp" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Code OTP:</label>
                    <input type="text" id="otp" wire:model.live="otp"
                           class="shadow-sm appearance-none border border-gray-300 dark:border-gray-600 rounded-xl w-full py-3 px-4 text-gray-800 dark:text-white leading-tight focus:outline-none focus:ring-4 focus:ring-blue-200 dark:bg-gray-700 dark:focus:ring-blue-800 transition-all duration-300 ease-in-out"
                           required maxlength="6">
                    @error('otp') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>

                <div class="flex flex-col items-center justify-center">
                    <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-offset-2 focus:ring-blue-500 dark:bg-blue-700 dark:hover:bg-blue-600 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-900 transition-all duration-300 ease-in-out transform hover:scale-105 mb-4">
                        Vérifier OTP
                    </button>
                    <a wire:click.prevent="resendOtp" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 cursor-pointer">
                        Renvoyer le code OTP
                    </a>
                    <a wire:click.prevent="resetForm" class="inline-block align-baseline font-bold text-sm text-red-500 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 cursor-pointer mt-2">
                        Annuler et recommencer
                    </a>
                </div>
            </form>
        @endif

        {{-- Étape 3: Définition du mot de passe --}}
        @if ($currentStep === 3)
            <form wire:submit.prevent="registerAdmin" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
                <div class="mb-6">
                    <label for="password" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Nouveau mot de passe:</label>
                    <input type="password" id="password" wire:model.live="password"
                           class="shadow-sm appearance-none border border-gray-300 dark:border-gray-600 rounded-xl w-full py-3 px-4 text-gray-800 dark:text-white leading-tight focus:outline-none focus:ring-4 focus:ring-blue-200 dark:bg-gray-700 dark:focus:ring-blue-800 transition-all duration-300 ease-in-out"
                           required autocomplete="new-password">
                    @error('password') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>

                <div class="mb-8">
                    <label for="password_confirmation" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Confirmer le mot de passe:</label>
                    <input type="password" id="password_confirmation" wire:model.live="password_confirmation"
                           class="shadow-sm appearance-none border border-gray-300 dark:border-gray-600 rounded-xl w-full py-3 px-4 text-gray-800 dark:text-white leading-tight focus:outline-none focus:ring-4 focus:ring-blue-200 dark:bg-gray-700 dark:focus:ring-blue-800 transition-all duration-300 ease-in-out"
                           required autocomplete="new-password">
                    @error('password_confirmation') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>

                <div class="flex flex-col items-center justify-center">
                    <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-offset-2 focus:ring-blue-500 dark:bg-blue-700 dark:hover:bg-blue-600 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-900 transition-all duration-300 ease-in-out transform hover:scale-105 mb-4">
                        Définir le mot de passe
                    </button>
                    <a wire:click.prevent="resetForm" class="inline-block align-baseline font-bold text-sm text-red-500 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 cursor-pointer mt-2">
                        Annuler et recommencer
                    </a>
                </div>
            </form>
        @endif

        {{-- Lien vers la page de connexion --}}
        <div class="text-center mt-6">
            <a href="{{ route('admin.auth.disconnected.loginView') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 cursor-pointer">
                Déjà inscrit ? Connectez-vous
            </a>
        </div>
    </div>

</div>
