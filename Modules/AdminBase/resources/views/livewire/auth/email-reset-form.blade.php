<div x-data="{ step: @entangle('step').live }"
     class="flex items-center justify-center p-4 sm:p-6 w-full h-full bg-gradient-to-br from-gray-50 via-gray-100 to-gray-50 dark:from-gray-800 dark:via-gray-900 dark:to-gray-800">

    <div class="bg-white dark:bg-gray-800 p-6 md:p-8 rounded-3xl shadow-2xl max-w-sm md:max-w-md w-full transition-all duration-300 ease-in-out hover:shadow-3xl transform hover:scale-[1.01]">
        {{-- Flash Message --}}
        @if (session()->has('email_reset_success'))
            <div class="bg-green-100 dark:bg-green-800 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-100 px-4 py-3 rounded-xl relative mb-6 shadow-md" role="alert">
                {{ session('email_reset_success') }}
            </div>
        @endif

        {{-- Step 1: Request new email and current password --}}
        <div x-show="step === 1"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">
            <h2 class="text-3xl md:text-4xl font-bold mb-6 text-center text-gray-800 dark:text-white">Changer l'Email</h2>
            <form wire:submit.prevent="sendEmailOtp">
                <div class="mb-6">
                    <label for="new_email_email_change" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Nouvel Email:</label>
                    <input type="email" id="new_email_email_change" name="new_email" wire:model.live="newEmail"
                           class="shadow-sm appearance-none border border-gray-300 dark:border-gray-600 rounded-xl w-full py-3 px-4 text-gray-800 dark:text-white leading-tight focus:outline-none focus:ring-4 focus:ring-blue-200 dark:bg-gray-700 dark:focus:ring-blue-800 transition-all duration-300 ease-in-out"
                           required autocomplete="email">
                    @error('newEmail') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>

                <div class="mb-8">
                    <label for="current_password_email_change" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Mot de passe actuel (pour confirmer):</label>
                    <input type="password" id="current_password_email_change" name="current_password" wire:model.live="currentPassword"
                           class="shadow-sm appearance-none border border-gray-300 dark:border-gray-600 rounded-xl w-full py-3 px-4 text-gray-800 dark:text-white leading-tight focus:outline-none focus:ring-4 focus:ring-blue-200 dark:bg-gray-700 dark:focus:ring-blue-800 transition-all duration-300 ease-in-out"
                           required autocomplete="current-password">
                    @error('currentPassword') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-center">
                    <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-offset-2 focus:ring-blue-500 dark:bg-blue-700 dark:hover:bg-blue-600 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-900 transition-all duration-300 ease-in-out transform hover:scale-105">
                        Envoyer l'OTP au Nouvel Email
                    </button>
                </div>
            </form>
        </div>

        {{-- Step 2: Validate OTP for email change --}}
        <div x-show="step === 2"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">
            <h2 class="text-3xl md:text-4xl font-bold mb-6 text-center text-gray-800 dark:text-white">Valider l'OTP</h2>
            <p class="text-gray-700 dark:text-gray-300 mb-6 text-center">Un code a été envoyé à votre nouvelle adresse email. Veuillez le saisir ci-dessous.</p>
            <form wire:submit.prevent="processEmailChange">
                <div class="mb-6">
                    <label for="email_change_otp" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">OTP:</label>
                    <input type="text" id="email_change_otp" name="otp" wire:model.live="otp"
                           class="shadow-sm appearance-none border border-gray-300 dark:border-gray-600 rounded-xl w-full py-3 px-4 text-gray-800 dark:text-white leading-tight focus:outline-none focus:ring-4 focus:ring-blue-200 dark:bg-gray-700 dark:focus:ring-blue-800 transition-all duration-300 ease-in-out"
                           required>
                    @error('otp') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>
                <div class="flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0 sm:space-x-4">
                    <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-offset-2 focus:ring-blue-500 dark:bg-blue-700 dark:hover:bg-blue-600 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-900 transition-all duration-300 ease-in-out transform hover:scale-105">
                        Confirmer le changement d'Email
                    </button>
                    <button type="button" @click="step = 1; $wire.goBackToStep1()" class="mt-4 w-full sm:w-auto text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 font-semibold transition-colors duration-300 px-4 py-2 rounded-xl">Retour</button>
                </div>
            </form>
        </div>
    </div>

</div>
