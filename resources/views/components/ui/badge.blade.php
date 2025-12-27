@props([
    'variant' => 'default',
    'size' => 'md',
    'dot' => false,
    'removable' => false,
])

@php
$variants = [
    'default' => 'bg-surface-100 dark:bg-surface-700 text-surface-700 dark:text-surface-300',
    'primary' => 'bg-primary-100 dark:bg-primary-900/50 text-primary-700 dark:text-primary-300',
    'success' => 'bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-300',
    'warning' => 'bg-yellow-100 dark:bg-yellow-900/50 text-yellow-700 dark:text-yellow-300',
    'danger' => 'bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-300',
    'info' => 'bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300',
];

$sizes = [
    'sm' => 'text-xs px-2 py-0.5',
    'md' => 'text-xs px-2.5 py-1',
    'lg' => 'text-sm px-3 py-1',
];

$dotColors = [
    'default' => 'bg-surface-500',
    'primary' => 'bg-primary-500',
    'success' => 'bg-green-500',
    'warning' => 'bg-yellow-500',
    'danger' => 'bg-red-500',
    'info' => 'bg-blue-500',
];

$classes = 'inline-flex items-center gap-1.5 font-medium rounded-full ' . ($variants[$variant] ?? $variants['default']) . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    @if($dot)
        <span class="w-1.5 h-1.5 rounded-full {{ $dotColors[$variant] ?? $dotColors['default'] }}"></span>
    @endif
    {{ $slot }}
    @if($removable)
        <button type="button" class="ml-0.5 -mr-1 h-4 w-4 rounded-full inline-flex items-center justify-center hover:bg-black/10 dark:hover:bg-white/10">
            <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
        </button>
    @endif
</span>
