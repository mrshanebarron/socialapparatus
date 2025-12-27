<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Install - SocialApparatus</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-900 text-gray-100">
    <div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-2xl">
            <div class="flex justify-center">
                <svg class="h-16 w-16 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <h2 class="mt-4 text-center text-3xl font-extrabold text-white">
                SocialApparatus
            </h2>
            <p class="mt-2 text-center text-sm text-gray-400">
                Installation Wizard
            </p>

            <!-- Progress Steps -->
            <div class="mt-8">
                @php
                    $steps = [
                        ['name' => 'Welcome', 'route' => 'install.welcome'],
                        ['name' => 'Requirements', 'route' => 'install.requirements'],
                        ['name' => 'Database', 'route' => 'install.database'],
                        ['name' => 'Admin', 'route' => 'install.admin'],
                        ['name' => 'Site', 'route' => 'install.site'],
                        ['name' => 'Complete', 'route' => 'install.complete'],
                    ];
                    $currentRoute = request()->route()->getName();
                    $currentIndex = collect($steps)->search(fn($s) => $s['route'] === $currentRoute);
                @endphp
                <nav aria-label="Progress">
                    <ol class="flex items-center justify-center space-x-5">
                        @foreach($steps as $index => $step)
                            <li>
                                @if($index < $currentIndex)
                                    <span class="block w-2.5 h-2.5 bg-indigo-500 rounded-full" title="{{ $step['name'] }}"></span>
                                @elseif($index === $currentIndex)
                                    <span class="relative flex items-center justify-center" title="{{ $step['name'] }}">
                                        <span class="absolute w-5 h-5 p-px flex" aria-hidden="true">
                                            <span class="w-full h-full rounded-full bg-indigo-200 opacity-20"></span>
                                        </span>
                                        <span class="relative block w-2.5 h-2.5 bg-indigo-500 rounded-full"></span>
                                    </span>
                                @else
                                    <span class="block w-2.5 h-2.5 bg-gray-600 rounded-full" title="{{ $step['name'] }}"></span>
                                @endif
                            </li>
                        @endforeach
                    </ol>
                </nav>
            </div>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-2xl">
            <div class="bg-gray-800 py-8 px-4 shadow sm:rounded-lg sm:px-10">
                {{ $slot }}
            </div>
        </div>

        <p class="mt-8 text-center text-xs text-gray-500">
            SocialApparatus &copy; {{ date('Y') }}
        </p>
    </div>

    @livewireScripts
</body>
</html>
