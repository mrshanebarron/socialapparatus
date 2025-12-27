<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SocialApparatus') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="bg-surface-100 dark:bg-surface-950">
        <div class="font-sans text-gray-900 dark:text-gray-100 antialiased min-h-screen flex flex-col">
            <div class="flex-1">
                {{ $slot }}
            </div>

            <!-- Footer -->
            <footer class="py-6 text-center text-sm text-surface-600 dark:text-surface-400">
                <p>Powered by <a href="https://socialapparatus.com" target="_blank" class="text-primary-600 dark:text-primary-400 hover:underline font-medium">SocialApparatus</a> · Built with love by <a href="https://sbarron.com" target="_blank" class="text-primary-600 dark:text-primary-400 hover:underline font-medium">Shane Barron</a></p>
            </footer>
        </div>

        @livewireScripts
    </body>
</html>
