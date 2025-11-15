<div x-data="{ currentStep: 1 }" {{-- Alpine gère 'currentStep' --}}
@set-default-credentials-step.window="currentStep = $event.detail.step" {{-- Écoute l'événement Livewire pour changer d'étape --}}
     class="relative flex items-center justify-center p-4 sm:p-6 w-full h-full bg-gradient-to-br from-gray-50 via-gray-100 to-gray-50 dark:from-gray-800 dark:via-gray-900 dark:to-gray-800">

    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl p-6 md:p-8 w-full max-w-sm md:max-w-md transition-all duration-300 ease-in-out hover:shadow-3xl transform hover:scale-[1.01]">
        <h2 class="text-3xl md:text-4xl font-bold mb-6 text-center text-gray-800 dark:text-white">Configuration des Identifiants Initiaux</h2>

        {{-- Close Button --}}
        {{-- Envoie l'événement au parent Alpine pour cacher le formulaire --}}
        <button type="button" @click="$dispatch('hide-default-credentials-form'); currentStep = 1;" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300 rounded-full transition-colors duration-200" aria-label="Close">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        {{-- Flash Messages and Errors --}}
        @if (session()->has('success'))
            <div class="bg-green-100 dark:bg-green-800 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-100 px-4 py-3 rounded-xl relative mb-4 shadow-md" role="alert">
                {{ session('success') }}
            </div>
        @endif
        @error('initialEmail')
        <div class="bg-red-100 dark:bg-red-800 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-100 px-4 py-3 rounded-xl relative mb-4 shadow-md" role="alert">
            {{ $message }}
        </div>
        @enderror
        @error('initialOtp')
        <div class="bg-red-100 dark:bg-red-800 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-100 px-4 py-3 rounded-xl relative mb-4 shadow-md" role="alert">
            {{ $message }}
        </div>
        @enderror
        @error('initialPassword')
        <div class="bg-red-100 dark:bg-red-800 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-100 px-4 py-3 rounded-xl relative mb-4 shadow-md" role="alert">
            {{ $message }}
        </div>
        @enderror

        {{-- Step 1: Request Email and Send OTP --}}
        <div x-show="currentStep === 1"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">
            <form wire:submit.prevent="sendOtp"> {{-- Livewire soumet le formulaire --}}
                <div class="mb-6">
                    <label for="initial_email" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Votre Email (pour l'OTP):</label>
                    <input type="email" id="initial_email" name="initial_email" wire:model.live="initialEmail"
                           class="shadow-sm appearance-none border border-gray-300 dark:border-gray-600 rounded-xl w-full py-3 px-4 text-gray-800 dark:text-white leading-tight focus:outline-none focus:ring-4 focus:ring-blue-200 dark:bg-gray-700 dark:focus:ring-blue-800 transition-all duration-300 ease-in-out"
                           required autocomplete="email">
                </div>
                <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-offset-2 focus:ring-blue-500 dark:bg-blue-700 dark:hover:bg-blue-600 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-900 transition-all duration-300 ease-in-out transform hover:scale-105">
                    Envoyer l'OTP
                </button>
            </form>
        </div>

        {{-- Step 2: Validate OTP and Set Password --}}
        <div x-show="currentStep === 2"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">
            <p class="text-gray-700 dark:text-gray-300 mb-6 text-center">Un OTP a été envoyé à l'adresse email fournie. Veuillez le saisir ci-dessous.</p>
            <form wire:submit.prevent="processCredentials"> {{-- Livewire soumet le formulaire --}}
                <div class="mb-6">
                    <label for="initial_otp" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">OTP:</label>
                    <input type="text" id="initial_otp" name="initial_otp" wire:model.live="initialOtp"
                           class="shadow-sm appearance-none border border-gray-300 dark:border-gray-600 rounded-xl w-full py-3 px-4 text-gray-800 dark:text-white leading-tight focus:outline-none focus:ring-4 focus:ring-blue-200 dark:bg-gray-700 dark:focus:ring-blue-800 transition-all duration-300 ease-in-out"
                           required>
                </div>
                <div class="mb-6">
                    <label for="initial_password" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Nouveau Mot de passe:</label>
                    <input type="password" id="initial_password" name="initial_password" wire:model.live="initialPassword"
                           class="shadow-sm appearance-none border border-gray-300 dark:border-gray-600 rounded-xl w-full py-3 px-4 text-gray-800 dark:text-white leading-tight focus:outline-none focus:ring-4 focus:ring-blue-200 dark:bg-gray-700 dark:focus:ring-blue-800 transition-all duration-300 ease-in-out"
                           required autocomplete="new-password">
                </div>
                <div class="mb-6">
                    <label for="initial_password_confirmation" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Confirmer le nouveau Mot de passe:</label>
                    <input type="password" id="initial_password_confirmation" name="initial_password_confirmation" wire:model.live="initialPasswordConfirmation"
                           class="shadow-sm appearance-none border border-gray-300 dark:border-gray-600 rounded-xl w-full py-3 px-4 text-gray-800 dark:text-white leading-tight focus:outline-none focus:ring-4 focus:ring-blue-200 dark:bg-gray-700 dark:focus:ring-blue-800 transition-all duration-300 ease-in-out"
                           required autocomplete="new-password">
                </div>
                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-offset-2 focus:ring-green-500 dark:bg-green-700 dark:hover:bg-green-600 dark:focus:ring-green-600 dark:focus:ring-offset-gray-900 transition-all duration-300 ease-in-out transform hover:scale-105">
                    Valider et Enregistrer
                </button>
                {{-- Bouton Retour : Alpine gère le changement d'étape --}}
                <button type="button" @click="currentStep = 1; $wire.goBackToStep1()" class="mt-4 w-full text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 font-semibold transition-colors duration-300 px-4 py-2 rounded-xl">Retour</button>
            </form>
        </div>
    </div>

</div>
