<div>
    @if($justPoked)
        <span class="inline-flex items-center px-3 py-1.5 text-gray-500 dark:text-gray-400 text-sm">
            <span class="mr-1">👆</span> Poked!
        </span>
    @else
        <button wire:click="poke" class="inline-flex items-center px-3 py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
            <span class="mr-1">👆</span> Poke
        </button>
    @endif
</div>
