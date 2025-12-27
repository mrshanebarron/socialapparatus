@props([
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'icon' => null,
    'iconPosition' => 'left',
    'loading' => false,
    'disabled' => false,
])

@php
$baseClasses = 'inline-flex items-center justify-center font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';

$variants = [
    'primary' => 'bg-primary-600 text-white hover:bg-primary-700 focus:ring-primary-500 shadow-soft hover:shadow-soft-lg active:scale-[0.98]',
    'secondary' => 'bg-white dark:bg-surface-800 text-surface-900 dark:text-surface-100 border border-surface-300 dark:border-surface-600 hover:bg-surface-50 dark:hover:bg-surface-700 focus:ring-primary-500',
    'accent' => 'bg-accent-500 text-white hover:bg-accent-600 focus:ring-accent-500 shadow-soft hover:shadow-soft-lg active:scale-[0.98]',
    'ghost' => 'text-surface-600 dark:text-surface-400 hover:bg-surface-100 dark:hover:bg-surface-800 hover:text-surface-900 dark:hover:text-surface-100 focus:ring-primary-500',
    'danger' => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500 active:scale-[0.98]',
    'link' => 'text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 underline-offset-4 hover:underline focus:ring-primary-500 p-0',
];

$sizes = [
    'xs' => 'text-xs px-2.5 py-1.5 rounded-md gap-1.5',
    'sm' => 'text-sm px-3 py-2 rounded-lg gap-2',
    'md' => 'text-sm px-4 py-2.5 rounded-lg gap-2',
    'lg' => 'text-base px-5 py-3 rounded-xl gap-2.5',
    'xl' => 'text-lg px-6 py-4 rounded-xl gap-3',
];

$iconSizes = [
    'xs' => 'w-3.5 h-3.5',
    'sm' => 'w-4 h-4',
    'md' => 'w-5 h-5',
    'lg' => 'w-5 h-5',
    'xl' => 'w-6 h-6',
];

$classes = $baseClasses . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
$iconClass = $iconSizes[$size] ?? $iconSizes['md'];
@endphp

@if($href && !$disabled)
    <a wire:navigate href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($loading)
            <svg class="animate-spin {{ $iconClass }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        @elseif($icon && $iconPosition === 'left')
            <x-dynamic-component :component="'heroicon-o-' . $icon" :class="$iconClass" />
        @endif
        {{ $slot }}
        @if($icon && $iconPosition === 'right' && !$loading)
            <x-dynamic-component :component="'heroicon-o-' . $icon" :class="$iconClass" />
        @endif
    </a>
@else
    <button {{ $attributes->merge(['class' => $classes, 'disabled' => $disabled || $loading]) }}>
        @if($loading)
            <svg class="animate-spin {{ $iconClass }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        @elseif($icon && $iconPosition === 'left')
            <x-dynamic-component :component="'heroicon-o-' . $icon" :class="$iconClass" />
        @endif
        {{ $slot }}
        @if($icon && $iconPosition === 'right' && !$loading)
            <x-dynamic-component :component="'heroicon-o-' . $icon" :class="$iconClass" />
        @endif
    </button>
@endif
