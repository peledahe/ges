<div 
    x-data="{ 
        showInstallPrompt: false,
        deferredPrompt: null,
        isIOS: false,
        
        init() {
            // Check if app is already installed
            if (window.matchMedia('(display-mode: standalone)').matches) {
                return;
            }

            // Detect iOS
            const userAgent = window.navigator.userAgent.toLowerCase();
            this.isIOS = /iphone|ipad|ipod/.test(userAgent);

            // Handle Android 'beforeinstallprompt'
            window.addEventListener('beforeinstallprompt', (e) => {
                e.preventDefault();
                this.deferredPrompt = e;
                // Add a small delay so it doesn't pop up immediately
                setTimeout(() => {
                    this.showInstallPrompt = true;
                }, 3000);
            });

            // Show iOS prompt (simulated)
            if (this.isIOS && !this.isInStandaloneMode()) {
                 setTimeout(() => {
                    this.showInstallPrompt = true;
                }, 3000);
            }
        },

        isInStandaloneMode() {
            return ('standalone' in window.navigator) && (window.navigator.standalone);
        },

        installApp() {
            if (this.deferredPrompt) {
                this.deferredPrompt.prompt();
                this.deferredPrompt.userChoice.then((choiceResult) => {
                    if (choiceResult.outcome === 'accepted') {
                        this.showInstallPrompt = false;
                    }
                    this.deferredPrompt = null;
                });
            }
        },

        dismiss() {
            this.showInstallPrompt = false;
        }
    }" 
    x-show="showInstallPrompt"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-full"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 translate-y-full"
    class="fixed bottom-0 left-0 right-0 z-50 p-4 m-4 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 max-w-md mx-auto"
    style="display: none;"
>
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-3">
             <div class="flex-shrink-0">
                <img src="/img/icons/icon.svg" class="h-10 w-10 rounded-lg bg-gray-100" alt="App Icon">
            </div>
            <div>
                <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100">Instalar Aplicación</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400">Acceso rápido y mejor experiencia</p>
            </div>
        </div>
        <button @click="dismiss" class="text-gray-400 hover:text-gray-500">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Android Install Button -->
    <div x-show="!isIOS" class="mt-4">
        <button 
            @click="installApp"
            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-md text-sm flex justify-center items-center transition-colors"
        >
            Instalar ahora
        </button>
    </div>

    <!-- iOS Instructions -->
    <div x-show="isIOS" class="mt-4 text-sm text-gray-600 dark:text-gray-300">
        <p class="mb-2">Para instalar en iOS:</p>
        <ol class="list-decimal list-inside space-y-1 text-xs">
            <li>Toca el botón <strong>Compartir</strong> <svg class="inline h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" /></svg></li>
            <li>Selecciona <strong>"Agregar a Inicio"</strong> <svg class="inline h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></li>
        </ol>
    </div>
</div>
