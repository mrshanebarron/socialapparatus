<div class="max-w-6xl mx-auto py-8 px-4">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Avatar Editor</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Preview -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 sticky top-4">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Your Avatar</h2>
                <div class="w-64 h-64 mx-auto bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-900 dark:to-purple-900 rounded-lg relative overflow-hidden">
                    <!-- Avatar layers would be rendered here -->
                    @foreach($selectedParts as $categorySlug => $partData)
                        @php $part = \App\Models\AvatarPart::find($partData['part_id']) @endphp
                        @if($part)
                            <img src="{{ $part->image_url }}" alt="" class="absolute inset-0 w-full h-full object-contain" style="{{ $partData['color'] ? 'filter: hue-rotate(' . $partData['color'] . 'deg)' : '' }}">
                        @endif
                    @endforeach
                </div>
                <p class="mt-4 text-center text-sm text-gray-600 dark:text-gray-400">{{ $avatar->name }}</p>
            </div>
        </div>

        <!-- Editor -->
        <div class="lg:col-span-2">
            <!-- Categories -->
            <div class="flex overflow-x-auto space-x-2 pb-4 mb-4">
                @foreach($categories as $category)
                    <button wire:click="selectCategory({{ $category->id }})" class="px-4 py-2 rounded-full whitespace-nowrap {{ $selectedCategory === $category->id ? 'bg-indigo-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                        {{ $category->name }}
                        @if($category->is_required)
                            <span class="text-red-500">*</span>
                        @endif
                    </button>
                @endforeach
            </div>

            <!-- Parts Grid -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-4">
                    @if($currentCategory)
                        <!-- None option -->
                        @if(!$currentCategory->is_required)
                            <button wire:click="removePart('{{ $currentCategory->slug }}')" class="aspect-square rounded-lg border-2 {{ !isset($selectedParts[$currentCategory->slug]) ? 'border-indigo-500' : 'border-gray-200 dark:border-gray-600' }} hover:border-indigo-400 transition flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                </svg>
                            </button>
                        @endif
                    @endif

                    @foreach($parts as $part)
                        <button wire:click="selectPart({{ $part->id }})" class="aspect-square rounded-lg border-2 {{ isset($selectedParts[$currentCategory?->slug]) && $selectedParts[$currentCategory->slug]['part_id'] == $part->id ? 'border-indigo-500' : 'border-gray-200 dark:border-gray-600' }} hover:border-indigo-400 transition overflow-hidden relative">
                            <img src="{{ $part->image_url }}" alt="{{ $part->name }}" class="w-full h-full object-contain">
                            @if($part->is_premium)
                                <span class="absolute top-1 right-1 bg-yellow-500 text-white text-xs px-1 rounded">{{ $part->coin_cost }}</span>
                            @endif
                        </button>
                    @endforeach
                </div>

                @if($parts->isEmpty())
                    <p class="text-center text-gray-500 dark:text-gray-400 py-8">No parts available in this category</p>
                @endif
            </div>
        </div>
    </div>
</div>
