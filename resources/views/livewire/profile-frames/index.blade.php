<div class="max-w-4xl mx-auto py-8 px-4">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Profile Frames</h1>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded-lg">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 rounded-lg">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Preview -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 sticky top-4">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Preview</h2>
                <div class="relative w-48 h-48 mx-auto">
                    <img src="{{ auth()->user()->profile_photo_url }}" alt="Profile" class="w-full h-full rounded-full object-cover">
                    @if($currentFrame)
                        <img src="{{ $currentFrame->frame->image_url }}" alt="Frame" class="absolute inset-0 w-full h-full pointer-events-none">
                    @endif
                </div>
                @if($currentFrame)
                    <div class="mt-4 text-center">
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $currentFrame->frame->name }}</p>
                        <button wire:click="removeFrame" class="mt-2 text-sm text-red-600 hover:text-red-700">Remove Frame</button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Frame Selection -->
        <div class="lg:col-span-2">
            <!-- Categories -->
            <div class="flex overflow-x-auto space-x-2 pb-4 mb-4">
                @foreach($categories as $category)
                    <button wire:click="selectCategory({{ $category->id }})" class="px-4 py-2 rounded-full whitespace-nowrap {{ $selectedCategory === $category->id ? 'bg-indigo-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                        {{ $category->name }}
                    </button>
                @endforeach
            </div>

            <!-- Frames Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                @foreach($categories->firstWhere('id', $selectedCategory)?->frames ?? collect() as $frame)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                        <div class="relative aspect-square">
                            <img src="{{ $frame->image_url }}" alt="{{ $frame->name }}" class="w-full h-full object-contain">
                            @if($frame->is_premium)
                                <span class="absolute top-2 right-2 bg-yellow-500 text-white text-xs px-2 py-1 rounded-full">Premium</span>
                            @endif
                        </div>
                        <div class="p-3">
                            <h3 class="font-medium text-gray-900 dark:text-white truncate">{{ $frame->name }}</h3>
                            @if($frame->coin_cost > 0)
                                <p class="text-sm text-yellow-600">{{ $frame->coin_cost }} coins</p>
                            @else
                                <p class="text-sm text-green-600">Free</p>
                            @endif
                            <button wire:click="applyFrame({{ $frame->id }})" class="mt-2 w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700 transition text-sm">
                                Apply
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
