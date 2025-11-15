@props([
    'label' => '',
    'icon' => '',
    'fileField' => '',
    'cameraField' => '',
    'preview' => null,
])

<div x-data="(() => { const fileField = @js($fileField); const label = @js($label); return {
        cameraActive: false,
        currentCameraProperty: null,
        stream: null,
        videoElement: null,

        initCamera() {
            console.log('Initializing camera for ' + fileField);
            this.videoElement = this.$refs.video;
            if (!this.videoElement) {
                console.error('Video element not found for ' + fileField + '. Ensure x-ref=\'video\' is set.');
                return;
            }

            navigator.mediaDevices.getUserMedia({ video: true })
                .then(s => {
                    console.log('Camera stream obtained for ' + fileField);
                    this.stream = s;
                    this.videoElement.srcObject = s;
                    this.videoElement.play().then(() => {
                        console.log('Camera playing for ' + fileField);
                        this.cameraActive = true;
                    }).catch(err => {
                        console.error('Error playing video for ' + fileField + ': ', err);
                    });
                })
                .catch(err => {
                    console.error('Error accessing camera for ' + fileField + ': ', err);
                    alert('Impossible d\'accéder à la caméra pour ' + label + '. Vérifiez vos permissions ou si une autre application utilise la caméra.');
                    this.cameraActive = false;
                    this.stopCamera();
                    this.currentCameraProperty = null;
                });
        },
        stopCamera() {
            console.log('Stopping camera for ' + fileField);
            if (this.stream) {
                this.stream.getTracks().forEach(track => track.stop());
                this.stream = null;
            }
            this.cameraActive = false;
        },
        takePhoto() {
            if (!this.videoElement || !this.currentCameraProperty) {
                console.error('Cannot take photo: videoElement or currentCameraProperty missing for ' + fileField);
                return;
            }

            const canvas = document.createElement('canvas');
            canvas.width = this.videoElement.videoWidth;
            canvas.height = this.videoElement.videoHeight;
            const context = canvas.getContext('2d');
            context.drawImage(this.videoElement, 0, 0, canvas.width, canvas.height);

            const imageData = canvas.toDataURL('image/jpeg', 0.9);
            console.log('Photo captured for ' + fileField);

            $wire.processCameraImage(this.currentCameraProperty, imageData);

            this.stopCamera();
            this.currentCameraProperty = null;
        },
        openCameraModal(propertyName) {
            console.log('Opening camera modal for ' + fileField + ' with property: ', propertyName);
            this.currentCameraProperty = propertyName;
            this.initCamera();
            this.cameraActive = true;
        }
    }} )()"
     @open-camera-modal-{{ $fileField }}.window="cameraActive = true">

    {{-- Modal pour la caméra --}}
    <div class="fixed inset-0 bg-gray-900 bg-opacity-80 z-50 flex items-center justify-center" id="camera-modal-{{ $fileField }}"
         x-show="cameraActive" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         @click.away="stopCamera(); currentCameraProperty = null;">
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-2xl mx-4 sm:mx-6 w-full max-w-lg" @click.stop>
            <h3 class="text-xl font-extrabold mb-4 text-gray-800 dark:text-white text-center">Capturez votre photo pour {{ $label }}</h3>
            <video x-ref="video" class="w-full aspect-video rounded-xl object-cover mb-4 ring-2 ring-green-500 shadow-md" autoplay playsinline></video>
            <div class="flex justify-center gap-4">
                <button type="button" @click="stopCamera(); currentCameraProperty = null;"
                        class="px-6 py-2 rounded-full font-bold text-white bg-red-500 hover:bg-red-600 transition-colors duration-200 shadow-lg">Annuler</button>
                <button type="button" @click="takePhoto()"
                        class="px-6 py-2 rounded-full font-bold text-white bg-green-600 hover:bg-green-700 transition-colors duration-200 shadow-lg">Capturer</button>
            </div>
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            <i class="{{ $icon }} text-green-500 mr-2"></i>{{ $label }}
        </label>
        <div class="flex flex-col sm:flex-row items-center gap-4">
            @if ($preview)
                <div class="relative {{ $fileField === 'photoProfil' ? '' : 'flex-grow' }}">
                    <img src="{{ $preview }}" class="{{ $fileField === 'photoProfil' ? 'h-24 w-24 object-cover rounded-full' : 'w-full h-auto object-contain rounded-lg' }} shadow-md ring-2 ring-green-300 dark:ring-green-700 transition-transform duration-300 hover:scale-105" alt="Preview {{ $label }}">
                    <button type="button" wire:click="$set('{{ $fileField }}', null); $set('{{ $cameraField }}', null); $set('{{ $fileField }}Preview', null);" class="absolute top-0 right-0 p-1 bg-red-500 rounded-full text-white hover:bg-red-600 transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm6 0a1 1 0 01-2 0v6a1 1 0 112 0V8z" clip-rule="evenodd" /></svg>
                    </button>
                </div>
            @endif
            <div class="flex-grow flex flex-col sm:flex-row gap-2">
                <label for="{{ $fileField }}" class="flex-grow text-center cursor-pointer px-4 py-3 rounded-xl bg-gray-100 dark:bg-gray-800 text-green-600 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors duration-200 border-2 border-dashed border-gray-300 dark:border-gray-600 font-medium">
                    <input id="{{ $fileField }}" wire:model="{{ $fileField }}" type="file" class="sr-only" accept="image/*">
                    <span>Choisir un fichier</span>
                </label>
                <button type="button" @click="openCameraModal('{{ $cameraField }}')" class="w-full sm:w-auto px-4 py-3 rounded-xl bg-green-50 dark:bg-green-900 text-green-700 dark:text-green-300 hover:bg-green-100 dark:hover:bg-green-800 transition-colors duration-200 font-medium whitespace-nowrap">
                    <i class="fas fa-camera mr-2"></i>Prendre une photo
                </button>
            </div>
        </div>
        @error($fileField) <p class="mt-1 text-xs text-red-500 dark:text-red-400">{{ $message }}</p> @enderror
        @error($cameraField) <p class="mt-1 text-xs text-red-500 dark:text-red-400">{{ $message }}</p> @enderror
    </div>
</div>
