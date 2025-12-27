<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SocialApparatus') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
        <style>
            [x-cloak] { display: none !important; }

            /* SPA Navigation Progress Bar */
            [x-ref="progressBar"] {
                position: fixed;
                top: 0;
                left: 0;
                height: 3px;
                background: linear-gradient(90deg, #6366f1, #f97316);
                z-index: 9999;
                transition: width 0.3s ease;
            }

            /* Page transition animations */
            .page-enter {
                animation: pageEnter 0.2s ease-out;
            }
            @keyframes pageEnter {
                from { opacity: 0; transform: translateY(8px); }
                to { opacity: 1; transform: translateY(0); }
            }
        </style>

        <!-- Dark mode initialization (prevents flash) -->
        <script>
            (function() {
                function applyTheme() {
                    if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                }
                applyTheme();
                document.addEventListener('livewire:navigated', applyTheme);
            })();
        </script>
    </head>
    <body class="font-sans antialiased"
          x-data="{ navigating: false, progress: 0 }"
          x-on:livewire-navigate-start.window="navigating = true; progress = 30"
          x-on:livewire-navigate-end.window="progress = 100; setTimeout(() => { navigating = false; progress = 0 }, 200)">

        <!-- Navigation Progress Bar -->
        <div x-show="navigating"
             x-transition:enter="transition-opacity duration-150"
             x-transition:leave="transition-opacity duration-300"
             x-ref="progressBar"
             :style="'width: ' + progress + '%'"
             class="fixed top-0 left-0 h-0.5 bg-gradient-to-r from-primary-500 to-accent-500 z-[9999]"></div>

        <x-banner />

        <div class="min-h-screen bg-surface-50 dark:bg-surface-950 pb-20 md:pb-0">
            @livewire('navigation-menu')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white/80 dark:bg-surface-900/80 backdrop-blur-xl border-b border-surface-200 dark:border-surface-800">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main class="py-6 page-enter">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    {{ $slot }}
                </div>
            </main>

            <!-- Footer -->
            <footer class="border-t border-surface-200 dark:border-surface-800 bg-white/50 dark:bg-surface-900/50 backdrop-blur-sm">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-surface-600 dark:text-surface-400">
                        <p>Powered by <a href="https://socialapparatus.com" target="_blank" class="text-primary-600 dark:text-primary-400 hover:underline font-medium">SocialApparatus</a></p>
                        <p>Built with love by <a href="https://sbarron.com" target="_blank" class="text-primary-600 dark:text-primary-400 hover:underline font-medium">Shane Barron</a></p>
                    </div>
                </div>
            </footer>
        </div>

        @stack('modals')

        {{-- Toast Notifications --}}
        <x-ui.toast />

        @livewireScripts
        @stack('scripts')
    </body>
</html>
