@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-4 pe-4 py-3 text-start text-base font-medium text-primary-700 dark:text-primary-300 bg-primary-50 dark:bg-primary-900/30 border-l-4 border-primary-500 focus:outline-none transition duration-200 ease-in-out'
            : 'block w-full ps-4 pe-4 py-3 border-l-4 border-transparent text-start text-base font-medium text-surface-600 dark:text-surface-400 hover:text-surface-800 dark:hover:text-surface-200 hover:bg-surface-50 dark:hover:bg-surface-800 hover:border-surface-300 dark:hover:border-surface-600 focus:outline-none transition duration-200 ease-in-out';
@endphp

<a wire:navigate {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
