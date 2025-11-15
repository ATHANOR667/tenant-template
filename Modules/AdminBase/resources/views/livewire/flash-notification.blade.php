<div class="fixed top-6 right-6 z-[1000] w-full max-w-md"
     x-data="{
         show: @entangle('show'),
         timeout: null,
         hide() {
             this.show = false;
             clearTimeout(this.timeout);
         },
         init() {
             this.$watch('show', (value) => {
                 if (value) {
                     this.timeout = setTimeout(() => {
                         this.show = false;
                     }, 5000);
                 }
             });
         }
     }"
     x-show="show"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-90"
     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-90"
     @keydown.escape.window="hide()">

    @if($message)
        @php
            $colors = [
                'success' => 'bg-green-500 dark:bg-green-800',
                'error' => 'bg-red-500 dark:bg-red-800',
                'warning' => 'bg-amber-500 dark:bg-amber-800',
                'info' => 'bg-blue-500 dark:bg-blue-800',
            ];
            $icons = [
                'success' => '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                'error' => '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                'warning' => '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c.148 0 .285-.098.358-.23L20.892 13.5c.074-.132.074-.298 0-.43L12.358 3.33a.5.5 0 00-.895 0L3.108 13.07a.5.5 0 00.358.43z" /></svg>',
                'info' => '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
            ];
            $titles = [
                'success' => 'Succès',
                'error' => 'Erreur',
                'warning' => 'Attention',
                'info' => 'Information',
            ];
        @endphp
        <div class="relative rounded-2xl shadow-xl border border-gray-200/50 dark:border-gray-700/50 bg-opacity-90 backdrop-blur-sm text-white {{ $colors[$type] }} overflow-hidden">
            <div class="p-5 flex items-start gap-4">
                <div class="flex-shrink-0 mt-0.5">
                    {!! $icons[$type] !!}
                </div>
                <div class="flex-1 w-0">
                    <p class="text-base font-medium leading-6 tracking-tight">{{ $titles[$type] }}</p>
                    <p class="mt-1.5 text-sm leading-5 opacity-95 font-light">{{ $message }}</p>
                </div>
                <div class="flex-shrink-0 flex items-start">
                    <button @click="hide()"
                            class="inline-flex rounded-lg p-2 hover:bg-white/10 dark:hover:bg-black/20 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white/50"
                            aria-label="Fermer la notification">
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
