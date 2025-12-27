{{-- System-wide Toast Notifications --}}
<div x-data="{
        toasts: [],
        counter: 0,

        addToast(options) {
            const id = ++this.counter;
            const toast = {
                id,
                type: options.type || 'info',
                title: options.title || null,
                message: options.message || '',
                duration: options.duration || 5000,
                progress: 100,
                visible: true
            };

            this.toasts.push(toast);

            // Auto-remove after duration
            setTimeout(() => {
                this.removeToast(id);
            }, toast.duration);
        },

        removeToast(id) {
            const index = this.toasts.findIndex(t => t.id === id);
            if (index > -1) {
                this.toasts[index].visible = false;
                setTimeout(() => {
                    this.toasts = this.toasts.filter(t => t.id !== id);
                }, 300);
            }
        }
     }"
     x-on:toast.window="addToast($event.detail)"
     class="fixed bottom-4 right-4 z-[9999] flex flex-col gap-2 pointer-events-none"
     aria-live="polite">

    <template x-for="toast in toasts" :key="toast.id">
        <div x-show="toast.visible"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-x-8"
             x-transition:enter-end="opacity-100 translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-x-0"
             x-transition:leave-end="opacity-0 translate-x-8"
             class="pointer-events-auto max-w-sm w-full bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden border dark:border-gray-700"
             :class="{
                 'border-l-4 border-l-green-500': toast.type === 'success',
                 'border-l-4 border-l-red-500': toast.type === 'error',
                 'border-l-4 border-l-yellow-500': toast.type === 'warning',
                 'border-l-4 border-l-blue-500': toast.type === 'info'
             }">
            <div class="p-4">
                <div class="flex items-start">
                    {{-- Icon --}}
                    <div class="flex-shrink-0">
                        <template x-if="toast.type === 'success'">
                            <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </template>
                        <template x-if="toast.type === 'error'">
                            <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </template>
                        <template x-if="toast.type === 'warning'">
                            <svg class="h-5 w-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </template>
                        <template x-if="toast.type === 'info'">
                            <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </template>
                    </div>

                    {{-- Content --}}
                    <div class="ml-3 flex-1">
                        <p x-show="toast.title" class="text-sm font-medium text-gray-900 dark:text-white" x-text="toast.title"></p>
                        <p class="text-sm text-gray-600 dark:text-gray-300" :class="{ 'mt-1': toast.title }" x-text="toast.message"></p>
                    </div>

                    {{-- Close Button --}}
                    <div class="ml-4 flex-shrink-0">
                        <button @click="removeToast(toast.id)"
                                class="inline-flex text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 focus:outline-none">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
