@props([
    'icon' => 'inbox',
    'title' => 'No items',
    'description' => null,
    'action' => null,
    'actionText' => null,
])

<div {{ $attributes->merge(['class' => 'text-center py-12 px-6']) }}>
    <div class="mx-auto w-16 h-16 rounded-full bg-surface-100 dark:bg-surface-800 flex items-center justify-center mb-4">
        <x-dynamic-component :component="'heroicon-o-' . $icon" class="w-8 h-8 text-surface-400 dark:text-surface-500" />
    </div>

    <h3 class="text-lg font-semibold text-surface-900 dark:text-surface-100 mb-1">
        {{ $title }}
    </h3>

    @if($description)
        <p class="text-surface-500 dark:text-surface-400 max-w-sm mx-auto">
            {{ $description }}
        </p>
    @endif

    @if($action && $actionText)
        <div class="mt-6">
            <x-ui.button :href="$action" variant="primary" size="md">
                {{ $actionText }}
            </x-ui.button>
        </div>
    @endif

    @if($slot->isNotEmpty())
        <div class="mt-6">
            {{ $slot }}
        </div>
    @endif
</div>
