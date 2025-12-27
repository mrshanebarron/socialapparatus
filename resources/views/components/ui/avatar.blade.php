@props([
    'src' => null,
    'alt' => '',
    'size' => 'md',
    'status' => null,
    'ring' => false,
    'initials' => null,
])

@php
$sizes = [
    'xs' => 'w-6 h-6 text-xs',
    'sm' => 'w-8 h-8 text-sm',
    'md' => 'w-10 h-10 text-sm',
    'lg' => 'w-12 h-12 text-base',
    'xl' => 'w-16 h-16 text-lg',
    '2xl' => 'w-20 h-20 text-xl',
    '3xl' => 'w-24 h-24 text-2xl',
];

$statusSizes = [
    'xs' => 'w-1.5 h-1.5',
    'sm' => 'w-2 h-2',
    'md' => 'w-2.5 h-2.5',
    'lg' => 'w-3 h-3',
    'xl' => 'w-3.5 h-3.5',
    '2xl' => 'w-4 h-4',
    '3xl' => 'w-5 h-5',
];

$statusColors = [
    'online' => 'bg-green-500',
    'offline' => 'bg-surface-400',
    'busy' => 'bg-red-500',
    'away' => 'bg-yellow-500',
];

$sizeClass = $sizes[$size] ?? $sizes['md'];
$statusSize = $statusSizes[$size] ?? $statusSizes['md'];
$ringClass = $ring ? 'ring-2 ring-white dark:ring-surface-800' : '';
@endphp

<div class="relative inline-flex">
    @if($src)
        <img src="{{ $src }}" alt="{{ $alt }}" {{ $attributes->merge(['class' => "rounded-full object-cover $sizeClass $ringClass"]) }}>
    @elseif($initials)
        <div {{ $attributes->merge(['class' => "rounded-full bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center text-white font-semibold $sizeClass $ringClass"]) }}>
            {{ $initials }}
        </div>
    @else
        <div {{ $attributes->merge(['class' => "rounded-full bg-surface-200 dark:bg-surface-700 flex items-center justify-center $sizeClass $ringClass"]) }}>
            <svg class="w-1/2 h-1/2 text-surface-400 dark:text-surface-500" fill="currentColor" viewBox="0 0 24 24">
                <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
        </div>
    @endif

    @if($status)
        <span class="absolute bottom-0 right-0 block {{ $statusSize }} rounded-full ring-2 ring-white dark:ring-surface-800 {{ $statusColors[$status] ?? $statusColors['offline'] }}"></span>
    @endif
</div>
