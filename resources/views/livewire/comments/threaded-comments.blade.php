<div class="space-y-4">
    <!-- New Comment Form -->
    @auth
        <form wire:submit.prevent="addComment" class="flex space-x-3">
            <img src="{{ auth()->user()->profile_photo_url }}" class="w-10 h-10 rounded-full flex-shrink-0">
            <div class="flex-1">
                <textarea wire:model="newComment" rows="2" placeholder="Write a comment..."
                          class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 resize-none"></textarea>
                <div class="flex justify-end mt-2">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm">
                        Comment
                    </button>
                </div>
            </div>
        </form>
    @endauth

    <!-- Sort Options -->
    <div class="flex items-center space-x-4 text-sm">
        <span class="text-gray-500 dark:text-gray-400">Sort by:</span>
        <button wire:click="$set('sortBy', 'newest')" class="{{ $sortBy === 'newest' ? 'text-indigo-600 font-medium' : 'text-gray-600 dark:text-gray-400' }}">Newest</button>
        <button wire:click="$set('sortBy', 'oldest')" class="{{ $sortBy === 'oldest' ? 'text-indigo-600 font-medium' : 'text-gray-600 dark:text-gray-400' }}">Oldest</button>
        <button wire:click="$set('sortBy', 'popular')" class="{{ $sortBy === 'popular' ? 'text-indigo-600 font-medium' : 'text-gray-600 dark:text-gray-400' }}">Popular</button>
    </div>

    <!-- Comments List -->
    <div class="space-y-4">
        @foreach($comments as $comment)
            @include('livewire.comments.partials.comment', ['comment' => $comment, 'depth' => 0])
        @endforeach
    </div>

    @if($comments->isEmpty())
        <p class="text-center text-gray-500 dark:text-gray-400 py-8">No comments yet. Be the first to comment!</p>
    @endif
</div>
