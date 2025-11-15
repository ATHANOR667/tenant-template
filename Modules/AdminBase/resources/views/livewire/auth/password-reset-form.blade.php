<div x-data="{ step: @entangle('step').live }"
     class="flex items-center justify-center p-4 sm:p-6 w-full h-full bg-gradient-to-br from-gray-50 via-gray-100 to-gray-50 dark:from-gray-800 dark:via-gray-900 dark:to-gray-800">

    <div class="bg-white dark:bg-gray-800 p-6 md:p-8 rounded-3xl shadow-2xl max-w-sm md:max-w-md w-full transition-all duration-300 ease-in-out hover:shadow-3xl transform hover:scale-[1.01]">
        {{-- Flash Message --}}
        @if (session()->has('password_reset_success'))
            <div class="bg-green-100 dark:bg-green-800 border border-green-400 dark:border-green-700 text-green-700 dark:text-blue-100 px-4 py-3 rounded-xl relative mb-6 shadow-md" role="alert">
                {{ session('password_reset_success') }}
            </div>
        @endif

        {{-- Step 1: Send OTP to known email --}}
        <div x-show="step === 1"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">
            <h2 class="text-3xl md:text-4xl font-bold mb-6 text-center text-gray-800 dark:text-white">Réinitialiser le mot de passe</h2>
            <p class="text-gray-700 dark:text-gray-300 mb-6 text-center">Pour changer votre mot de passe, nous enverrons un code de vérification à votre email enregistré ({{ Auth::guard($this->guard)->user()->email }}).</p>
            <form wire:submit.prevent="sendPasswordOtp">
                <div class="flex justify-center">
                    <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-offset-2 focus:ring-blue-500 dark:bg-blue-700 dark:hover:bg-blue-600 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-900 transition-all duration-300 ease-in-out transform hover:scale-105">
                        Envoyer l'OTP
                    </button>
                </div>
                @error('otp') <p class="text-red-500 text-xs italic mt-2 text-center">{{ $message }}</p> @enderror
            </form>
        </div>

        {{-- Step 2: Enter OTP and new password --}}
        <div x-show="step === 2"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">
            <h2 class="text-3xl md:text-4xl font-bold mb-6 text-center text-gray-800 dark:text-white">Valider l'OTP</h2>
            <p class="text-gray-700 dark:text-gray-300 mb-6 text-center">Un code a été envoyé à {{ Auth::guard($this->guard)->user()->email }}. Veuillez le saisir ci-dessous avec votre nouveau mot de passe.</p>
            <form wire:submit.prevent="processPasswordChange">
                <div class="mb-6">
                    <label for="password_change_otp" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">OTP:</label>
                    <input type="text" id="password_change_otp" name="otp" wire:model.live="otp"
                           class="shadow-sm appearance-none border border-gray-300 dark:border-gray-600 rounded-xl w-full py-3 px-4 text-gray-800 dark:text-white leading-tight focus:outline-none focus:ring-4 focus:ring-blue-200 dark:bg-gray-700 dark:focus:ring-blue-800 transition-all duration-300 ease-in-out"
                           required>
                    @error('otp') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label for="new_password_password_change" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Nouveau Mot de passe:</label>
                    <input type="password" id="new_password_password_change" name="new_password" wire:model.live="newPassword"
                           class="shadow-sm appearance-none border border-gray-300 dark:border-gray-600 rounded-xl w-full py-3 px-4 text-gray-800 dark:text-white leading-tight focus:outline-none focus:ring-4 focus:ring-blue-200 dark:bg-gray-700 dark:focus:ring-blue-800 transition-all duration-300 ease-in-out"
                           required autocomplete="new-password">
                    @error('newPassword') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>

                <div class="mb-8">
                    <label for="new_password_confirmation_password_change" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Confirmer le nouveau Mot de passe:</label>
                    <input type="password" id="new_password_confirmation_password_change" name="new_password_confirmation" wire:model.live="newPasswordConfirmation"
                           class="shadow-sm appearance-none border border-gray-300 dark:border-gray-600 rounded-xl w-full py-3 px-4 text-gray-800 dark:text-white leading-tight focus:outline-none focus:ring-4 focus:ring-blue-200 dark:bg-gray-700 dark:focus:ring-blue-800 transition-all duration-300 ease-in-out"
                           required autocomplete="new-password">
                </div>

                <div class="flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0 sm:space-x-4">
                    <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-offset-2 focus:ring-blue-500 dark:bg-blue-700 dark:hover:bg-blue-600 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-900 transition-all duration-300 ease-in-out transform hover:scale-105">
                        Changer le Mot de Passe
                    </button>
                    <button type="button" @click="step = 1; $wire.goBackToStep1()" class="w-full sm:w-auto mt-4 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 font-semibold transition-colors duration-300 px-4 py-2 rounded-xl">Retour</button>
                </div>
            </form>
        </div>
    </div>

</div>
