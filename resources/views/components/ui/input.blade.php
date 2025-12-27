@props([
    'label' => null,
    'hint' => null,
    'error' => null,
    'prefix' => null,
    'suffix' => null,
    'size' => 'md',
])

@php
$sizes = [
    'sm' => 'text-sm px-3 py-2',
    'md' => 'text-sm px-4 py-2.5',
    'lg' => 'text-base px-4 py-3',
];

$inputClasses = 'block w-full rounded-xl border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 text-surface-900 dark:text-surface-100 placeholder-surface-400 dark:placeholder-surface-500 shadow-sm focus:border-primary-500 focus:ring-primary-500 transition-colors duration-200 ' . ($sizes[$size] ?? $sizes['md']);

if ($error) {
    $inputClasses .= ' border-red-500 focus:border-red-500 focus:ring-red-500';
}
if ($prefix || $suffix) {
    $inputClasses .= $prefix ? ' pl-10' : '';
    $inputClasses .= $suffix ? ' pr-10' : '';
}
@endphp

<div>
    @if($label)
        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">
            {{ $label }}
        </label>
    @endif

    <div class="relative">
        @if($prefix)
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <span class="text-surface-400 dark:text-surface-500">{{ $prefix }}</span>
            </div>
        @endif

        <input {{ $attributes->merge(['class' => $inputClasses]) }}>

        @if($suffix)
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <span class="text-surface-400 dark:text-surface-500">{{ $suffix }}</span>
            </div>
        @endif
    </div>

    @if($hint && !$error)
        <p class="mt-2 text-sm text-surface-500 dark:text-surface-400">{{ $hint }}</p>
    @endif

    @if($error)
        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $error }}</p>
    @endif
</div>
