<div x-data="{
    notifications: [],
    add(message, type = 'success', duration = 5000) {
        const id = Date.now() + Math.random();
        this.notifications.push({ id, message, type });
        if (duration > 0) {
            setTimeout(() => {
                this.remove(id);
            }, duration);
        }
    },
    remove(id) {
        this.notifications = this.notifications.filter(n => n.id !== id);
    }
}" 
x-init="
    @if (session()->has('message'))
        add(@js(session('message')), 'success');
    @endif
    @if (session()->has('success'))
        add(@js(session('success')), 'success');
    @endif
    @if (session()->has('error'))
        add(@js(session('error')), 'error');
    @endif
    @if (session()->has('warning'))
        add(@js(session('warning')), 'warning');
    @endif

    window.addEventListener('notify', event => {
        const data = event.detail[0] || event.detail;
        add(data.message, data.type || 'success', data.duration || 5000);
    });
"
class="fixed top-5 right-5 z-50 flex flex-col space-y-3 pointer-events-none max-w-sm w-full">
    <template x-for="n in notifications" :key="n.id">
        <div x-show="true" 
             x-transition:enter="transform ease-out duration-300 transition"
             x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
             x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="pointer-events-auto w-full rounded-xl shadow-2xl border p-4 backdrop-blur-md flex items-start space-x-3 transition-all"
             :class="{
                 'bg-white/95 dark:bg-gray-800/95 border-emerald-500/40 text-gray-800 dark:text-gray-100': n.type === 'success',
                 'bg-white/95 dark:bg-gray-800/95 border-rose-500/40 text-gray-800 dark:text-gray-100': n.type === 'error',
                 'bg-white/95 dark:bg-gray-800/95 border-amber-500/40 text-gray-800 dark:text-gray-100': n.type === 'warning',
                 'bg-white/95 dark:bg-gray-800/95 border-indigo-500/40 text-gray-800 dark:text-gray-100': n.type === 'info'
             }">
            
            <!-- Icon -->
            <div class="flex-shrink-0 mt-0.5">
                <template x-if="n.type === 'success'">
                    <div class="w-7 h-7 rounded-full bg-emerald-100 dark:bg-emerald-900/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                </template>
                <template x-if="n.type === 'error'">
                    <div class="w-7 h-7 rounded-full bg-rose-100 dark:bg-rose-900/60 text-rose-600 dark:text-rose-400 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </div>
                </template>
                <template x-if="n.type === 'warning'">
                    <div class="w-7 h-7 rounded-full bg-amber-100 dark:bg-amber-900/60 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                </template>
                <template x-if="n.type === 'info'">
                    <div class="w-7 h-7 rounded-full bg-indigo-100 dark:bg-indigo-900/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </template>
            </div>

            <!-- Content -->
            <div class="flex-1 text-sm font-medium leading-relaxed" x-text="n.message"></div>

            <!-- Close Button -->
            <button @click="remove(n.id)" class="flex-shrink-0 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    </template>
</div>
