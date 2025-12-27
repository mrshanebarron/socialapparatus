@props([
    'padding' => 'md',
    'hover' => false,
    'divided' => false,
])

@php
$paddings = [
    'none' => '',
    'sm' => 'p-4',
    'md' => 'p-6',
    'lg' => 'p-8',
];

$baseClasses = 'bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 overflow-hidden';
$hoverClasses = $hover ? 'transition-all duration-300 hover:shadow-soft-lg hover:border-surface-300 dark:hover:border-surface-600 hover:-translate-y-0.5' : 'shadow-soft';
$paddingClass = $paddings[$padding] ?? $paddings['md'];
$dividerClass = $divided ? 'divide-y divide-surface-200 dark:divide-surface-700' : '';
@endphp

<div {{ $attributes->merge(['class' => "$baseClasses $hoverClasses $dividerClass"]) }}>
    @if($padding !== 'none')
        <div class="{{ $paddingClass }}">
            {{ $slot }}
        </div>
    @else
        {{ $slot }}
    @endif
</div>
