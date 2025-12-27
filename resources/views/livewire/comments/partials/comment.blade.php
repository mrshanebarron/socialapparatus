@php $maxDepth = $maxDepth ?? 3; @endphp

<div class="flex space-x-3" style="margin-left: {{ $depth * 2 }}rem;" x-data="{ collapsed: false }">
    <img src="{{ $comment->user->profile_photo_url }}" class="w-8 h-8 rounded-full flex-shrink-0 mt-1">
    <div class="flex-1 min-w-0">
        <div class="bg-gray-100 dark:bg-gray-700 rounded-lg px-4 py-2">
            <div class="flex items-center space-x-2">
                <a href="{{ route('profile.view', $comment->user) }}" class="font-medium text-gray-900 dark:text-white hover:underline text-sm">
                    {{ $comment->user->name }}
                </a>
                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
            </div>
            @if($comment->is_deleted)
                <p class="text-gray-400 dark:text-gray-500 italic text-sm mt-1">[This comment has been deleted]</p>
            @else
                <p class="text-gray-700 dark:text-gray-300 text-sm mt-1">{{ $comment->content }}</p>
            @endif
        </div>

        <!-- Actions -->
        @if(!$comment->is_deleted)
            <div class="flex items-center space-x-4 mt-1 text-xs">
                @auth
                    @if($depth < $maxDepth)
                        <button wire:click="startReply({{ $comment->id }})" class="text-gray-500 dark:text-gray-400 hover:text-indigo-600">
                            Reply
                        </button>
                    @endif
                    @if($comment->user_id === auth()->id())
                        <button wire:click="deleteComment({{ $comment->id }})" class="text-gray-500 dark:text-gray-400 hover:text-red-600">
                            Delete
                        </button>
                    @endif
                @endauth
                @if($comment->replies_count > 0)
                    <button @click="collapsed = !collapsed" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                        <span x-show="!collapsed">Hide {{ $comment->replies_count }} {{ Str::plural('reply', $comment->replies_count) }}</span>
                        <span x-show="collapsed" x-cloak>Show {{ $comment->replies_count }} {{ Str::plural('reply', $comment->replies_count) }}</span>
                    </button>
                @endif
            </div>
        @endif

        <!-- Reply Form -->
        @if($replyingTo === $comment->id)
            <div class="mt-3 flex space-x-2">
                <img src="{{ auth()->user()->profile_photo_url }}" class="w-6 h-6 rounded-full flex-shrink-0">
                <div class="flex-1">
                    <textarea wire:model="replyContent" rows="2" placeholder="Write a reply..."
                              class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-sm resize-none"></textarea>
                    <div class="flex justify-end space-x-2 mt-2">
                        <button wire:click="cancelReply" class="px-3 py-1 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">
                            Cancel
                        </button>
                        <button wire:click="submitReply" class="px-3 py-1 bg-indigo-600 text-white rounded text-sm hover:bg-indigo-700">
                            Reply
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Nested Replies -->
        @if($comment->replies && $comment->replies->isNotEmpty())
            <div class="mt-3 space-y-3" x-show="!collapsed">
                @foreach($comment->replies as $reply)
                    @include('livewire.comments.partials.comment', ['comment' => $reply, 'depth' => $depth + 1, 'maxDepth' => $maxDepth])
                @endforeach
            </div>
        @endif
    </div>
</div>
