<div>
    <x-ui.card padding="none" class="overflow-hidden">
        <!-- Post Header -->
        <div class="p-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('profile.view', $post->user->profile?->username ?? $post->user->id) }}" class="relative">
                    <img class="h-11 w-11 rounded-xl object-cover ring-2 ring-surface-100 dark:ring-surface-700" src="{{ $post->user->profile_photo_url }}" alt="{{ $post->user->name }}">
                </a>
                <div>
                    <div class="flex items-center flex-wrap gap-1">
                        <a href="{{ route('profile.view', $post->user->profile?->username ?? $post->user->id) }}" class="font-semibold text-surface-900 dark:text-surface-100 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                            {{ $post->user->name }}
                        </a>
                        @if($post->feeling)
                            <span class="text-surface-500 dark:text-surface-400 text-sm">
                                is feeling {{ $feelingEmojis[$post->feeling] ?? '' }} {{ $post->feeling_display }}
                            </span>
                        @endif
                        @if($post->activity)
                            <span class="text-surface-500 dark:text-surface-400 text-sm">
                                is {{ $post->activity_display }}
                            </span>
                        @endif
                        @if($post->type === 'share')
                            <span class="text-surface-500 dark:text-surface-400 text-sm">shared a post</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-2 text-sm text-surface-500 dark:text-surface-400">
                        <span>{{ $post->created_at->diffForHumans() }}</span>
                        @if($post->is_edited)
                            <span class="inline-flex items-center gap-1 text-xs" title="Edited {{ $post->edited_at->diffForHumans() }}">
                                <x-heroicon-o-pencil class="w-3 h-3" />
                                Edited
                            </span>
                        @endif
                        <span class="inline-flex items-center" title="{{ ucfirst($post->visibility) }}">
                            @if ($post->visibility === 'public')
                                <x-heroicon-o-globe-alt class="h-4 w-4" />
                            @elseif ($post->visibility === 'friends')
                                <x-heroicon-o-users class="h-4 w-4" />
                            @else
                                <x-heroicon-o-lock-closed class="h-4 w-4" />
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <!-- Post Actions Dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="p-2 rounded-xl text-surface-400 hover:text-surface-600 dark:hover:text-surface-300 hover:bg-surface-100 dark:hover:bg-surface-700 transition-colors">
                    <x-heroicon-o-ellipsis-horizontal class="h-5 w-5" />
                </button>
                <div x-show="open" @click.away="open = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-56 bg-white dark:bg-surface-800 rounded-2xl shadow-soft-lg border border-surface-200 dark:border-surface-700 z-10 overflow-hidden"
                     style="display: none;">
                    <div class="py-2">
                        <button wire:click="savePost" class="flex w-full items-center gap-3 px-4 py-2.5 text-sm text-surface-700 dark:text-surface-300 hover:bg-surface-100 dark:hover:bg-surface-700 transition-colors">
                            <x-heroicon-o-bookmark class="w-4 h-4" />
                            {{ $isSaved ? 'Unsave Post' : 'Save Post' }}
                        </button>
                        @if ($post->user_id === auth()->id())
                            <button wire:click="openEditModal" class="flex w-full items-center gap-3 px-4 py-2.5 text-sm text-surface-700 dark:text-surface-300 hover:bg-surface-100 dark:hover:bg-surface-700 transition-colors">
                                <x-heroicon-o-pencil class="w-4 h-4" />
                                Edit Post
                            </button>
                            @if($post->is_edited)
                                <button wire:click="$set('showEditHistory', true)" class="flex w-full items-center gap-3 px-4 py-2.5 text-sm text-surface-700 dark:text-surface-300 hover:bg-surface-100 dark:hover:bg-surface-700 transition-colors">
                                    <x-heroicon-o-clock class="w-4 h-4" />
                                    View Edit History
                                </button>
                            @endif
                            <div class="border-t border-surface-200 dark:border-surface-700 my-2"></div>
                            <button wire:click="deletePost" wire:confirm="Are you sure you want to delete this post?" class="flex w-full items-center gap-3 px-4 py-2.5 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                <x-heroicon-o-trash class="w-4 h-4" />
                                Delete Post
                            </button>
                        @else
                            <button wire:click="hidePost" class="flex w-full items-center gap-3 px-4 py-2.5 text-sm text-surface-700 dark:text-surface-300 hover:bg-surface-100 dark:hover:bg-surface-700 transition-colors">
                                <x-heroicon-o-eye-slash class="w-4 h-4" />
                                Hide Post
                            </button>
                            <div class="border-t border-surface-200 dark:border-surface-700 my-2"></div>
                            <button wire:click="$set('showReportModal', true)" class="flex w-full items-center gap-3 px-4 py-2.5 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                <x-heroicon-o-flag class="w-4 h-4" />
                                Report Post
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Location Check-in -->
        @if($post->hasLocation())
            <div class="px-4 pb-3">
                <div class="inline-flex items-center gap-1.5 text-sm text-surface-500 dark:text-surface-400">
                    <x-heroicon-o-map-pin class="h-4 w-4 text-red-500" />
                    <span>at <span class="font-medium text-surface-700 dark:text-surface-300">{{ $post->location_name }}</span></span>
                </div>
            </div>
        @endif

        <!-- Post Content -->
        @if ($post->body)
            @if($post->hasBackgroundStyle())
                <div class="mx-4 mb-4 rounded-2xl p-8 text-center" style="{{ $post->background_style }}">
                    <p class="text-xl font-medium text-white whitespace-pre-wrap">{!! $post->formatted_body !!}</p>
                </div>
            @else
                <div class="px-4 pb-4">
                    <p class="text-surface-800 dark:text-surface-200 whitespace-pre-wrap leading-relaxed">{!! $post->formatted_body !!}</p>
                </div>
            @endif
        @endif

        <!-- GIF -->
        @if($post->gif_url)
            <div class="px-4 pb-4">
                <img src="{{ $post->gif_url }}" alt="GIF" class="rounded-2xl max-h-80 w-auto">
            </div>
        @endif

        <!-- Link Preview -->
        @if($post->link_preview && is_array($post->link_preview))
            <div class="mx-4 mb-4">
                <a href="{{ $post->link_preview['url'] ?? $post->link_url }}" target="_blank" rel="noopener" class="block border border-surface-200 dark:border-surface-700 rounded-2xl overflow-hidden hover:bg-surface-50 dark:hover:bg-surface-700/50 transition group">
                    @if(!empty($post->link_preview['image']))
                        <img src="{{ $post->link_preview['image'] }}" alt="" class="w-full h-48 object-cover group-hover:opacity-90 transition-opacity">
                    @endif
                    <div class="p-4">
                        @if(!empty($post->link_preview['title']))
                            <h4 class="font-semibold text-surface-900 dark:text-white line-clamp-2 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">{{ $post->link_preview['title'] }}</h4>
                        @endif
                        @if(!empty($post->link_preview['description']))
                            <p class="text-sm text-surface-500 dark:text-surface-400 mt-1.5 line-clamp-2">{{ $post->link_preview['description'] }}</p>
                        @endif
                        <p class="text-xs text-surface-400 dark:text-surface-500 mt-2 uppercase tracking-wide">{{ parse_url($post->link_preview['url'] ?? $post->link_url, PHP_URL_HOST) }}</p>
                    </div>
                </a>
            </div>
        @endif

        <!-- Poll -->
        @if($post->poll)
            <div class="mx-4 mb-4 border border-surface-200 dark:border-surface-700 rounded-2xl p-4">
                <h4 class="font-semibold text-surface-900 dark:text-white mb-4">{{ $post->poll->question }}</h4>
                @php
                    $hasVoted = auth()->check() && $post->poll->hasVoted(auth()->user());
                    $totalVotes = $post->poll->total_votes;
                @endphp
                <div class="space-y-2">
                    @foreach($post->poll->options as $option)
                        @php
                            $percentage = $totalVotes > 0 ? round(($option->votes_count / $totalVotes) * 100) : 0;
                            $isSelected = $hasVoted && $post->poll->votes()->where('user_id', auth()->id())->where('poll_option_id', $option->id)->exists();
                        @endphp
                        @if($hasVoted || $post->poll->hasEnded())
                            <div class="relative rounded-xl overflow-hidden">
                                <div class="absolute inset-0 bg-primary-100 dark:bg-primary-900/30 {{ $isSelected ? 'ring-2 ring-primary-500 ring-inset' : '' }}" style="width: {{ $percentage }}%"></div>
                                <div class="relative flex justify-between items-center px-4 py-3">
                                    <span class="text-sm text-surface-700 dark:text-surface-300 {{ $isSelected ? 'font-semibold' : '' }}">
                                        {{ $option->option_text }}
                                        @if($isSelected)
                                            <x-heroicon-s-check-circle class="inline h-4 w-4 text-primary-600 ml-1" />
                                        @endif
                                    </span>
                                    <span class="text-sm font-medium text-surface-600 dark:text-surface-400">{{ $percentage }}%</span>
                                </div>
                            </div>
                        @else
                            <button wire:click="votePoll({{ $option->id }})" class="w-full text-left px-4 py-3 border border-surface-300 dark:border-surface-600 rounded-xl hover:bg-surface-50 dark:hover:bg-surface-700 hover:border-primary-500 text-sm text-surface-700 dark:text-surface-300 transition-all">
                                {{ $option->option_text }}
                            </button>
                        @endif
                    @endforeach
                </div>
                <div class="mt-4 flex items-center justify-between text-xs text-surface-500 dark:text-surface-400">
                    <span>{{ $totalVotes }} {{ Str::plural('vote', $totalVotes) }}</span>
                    @if($post->poll->ends_at)
                        @if($post->poll->hasEnded())
                            <span class="text-red-500">Poll ended</span>
                        @else
                            <span>Ends {{ $post->poll->ends_at->diffForHumans() }}</span>
                        @endif
                    @endif
                </div>
            </div>
        @endif

        <!-- Shared Post -->
        @if($post->shared_post_id && $post->sharedPost)
            <div class="mx-4 mb-4 border border-surface-200 dark:border-surface-700 rounded-2xl overflow-hidden">
                <div class="p-4 bg-surface-50 dark:bg-surface-700/50">
                    <div class="flex items-center gap-3">
                        <img class="h-10 w-10 rounded-xl object-cover" src="{{ $post->sharedPost->user->profile_photo_url }}" alt="{{ $post->sharedPost->user->name }}">
                        <div>
                            <a href="{{ route('profile.view', $post->sharedPost->user->profile?->username ?? $post->sharedPost->user->id) }}" class="font-semibold text-sm text-surface-900 dark:text-surface-100 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                                {{ $post->sharedPost->user->name }}
                            </a>
                            <p class="text-xs text-surface-500 dark:text-surface-400">{{ $post->sharedPost->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @if($post->sharedPost->body)
                        <p class="mt-3 text-sm text-surface-800 dark:text-surface-200">{{ $post->sharedPost->body }}</p>
                    @endif
                </div>
                @if ($post->sharedPost->media && count($post->sharedPost->media) > 0)
                    <div class="{{ count($post->sharedPost->media) === 1 ? '' : 'grid grid-cols-2 gap-1' }}">
                        @foreach ($post->sharedPost->media as $mediaItem)
                            <img src="{{ Storage::url($mediaItem) }}" alt="Post image" class="w-full object-cover {{ count($post->sharedPost->media) === 1 ? 'max-h-64' : 'h-32' }}">
                        @endforeach
                    </div>
                @endif
            </div>
        @endif

        <!-- Post Media -->
        @if ($post->media && count($post->media) > 0)
            <div class="{{ count($post->media) === 1 ? '' : 'grid grid-cols-2 gap-1' }}">
                @foreach ($post->media as $mediaItem)
                    <img
                        src="{{ Storage::url($mediaItem) }}"
                        alt="Post image"
                        class="w-full object-cover {{ count($post->media) === 1 ? 'max-h-[500px]' : 'h-48' }}"
                    >
                @endforeach
            </div>
        @endif

        <!-- Engagement Stats -->
        <div class="px-4 py-3 flex items-center justify-between text-sm text-surface-500 dark:text-surface-400 border-t border-surface-100 dark:border-surface-700/50">
            <div class="flex items-center gap-2">
                @if (!empty($reactionCounts))
                    <div class="flex -space-x-1">
                        @foreach(array_slice(array_keys($reactionCounts), 0, 3) as $type)
                            <span class="inline-flex items-center justify-center w-6 h-6 bg-surface-100 dark:bg-surface-700 rounded-full text-sm" title="{{ ucfirst($type) }}">{{ $reactionEmojis[$type] }}</span>
                        @endforeach
                    </div>
                    <button wire:click="$set('showReactionsModal', true)" class="hover:underline">{{ array_sum($reactionCounts) }}</button>
                @elseif ($post->likes_count > 0)
                    <span>{{ $post->likes_count }} {{ Str::plural('like', $post->likes_count) }}</span>
                @endif
            </div>
            <div class="flex items-center gap-4">
                @if ($post->comments_count > 0)
                    <button wire:click="toggleComments" class="hover:text-surface-700 dark:hover:text-surface-300 transition-colors">
                        {{ $post->comments_count }} {{ Str::plural('comment', $post->comments_count) }}
                    </button>
                @endif
                @if ($post->shares_count > 0)
                    <span>{{ $post->shares_count }} {{ Str::plural('share', $post->shares_count) }}</span>
                @endif
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="px-4 py-2 flex items-center justify-around border-t border-surface-100 dark:border-surface-700/50">
            <!-- Reaction Button with Picker -->
            <div class="relative"
                 x-data="{ showPicker: false }"
                 @mouseenter="showPicker = true"
                 @mouseleave="showPicker = false">
                <button
                    @click="@if($userReaction) $wire.removeReaction() @else $wire.react('like') @endif"
                    class="flex items-center gap-2 px-4 py-2.5 rounded-xl hover:bg-surface-100 dark:hover:bg-surface-700 transition-all {{ $userReaction ? 'text-primary-600 dark:text-primary-400' : 'text-surface-500 dark:text-surface-400' }}"
                >
                    @if($userReaction)
                        <span class="text-xl">{{ $reactionEmojis[$userReaction->type] }}</span>
                        <span class="text-sm font-medium capitalize">{{ $userReaction->type }}</span>
                    @else
                        <x-heroicon-o-hand-thumb-up class="h-5 w-5" />
                        <span class="text-sm font-medium">Like</span>
                    @endif
                </button>

                <!-- Reaction Picker -->
                <div x-show="showPicker"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     class="absolute bottom-full left-0 pb-2 z-10"
                     style="display: none;">
                    <div class="bg-white dark:bg-surface-800 rounded-2xl shadow-soft-lg border border-surface-200 dark:border-surface-700 px-2 py-2 flex gap-1">
                        @foreach($reactionTypes as $type)
                            <button wire:click="react('{{ $type }}')"
                                    class="text-2xl hover:scale-125 transition-transform p-1.5 rounded-xl hover:bg-surface-100 dark:hover:bg-surface-700"
                                    title="{{ ucfirst($type) }}">
                                {{ $reactionEmojis[$type] }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <button
                wire:click="toggleComments"
                class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-surface-500 dark:text-surface-400 hover:bg-surface-100 dark:hover:bg-surface-700 transition-all"
            >
                <x-heroicon-o-chat-bubble-left class="h-5 w-5" />
                <span class="text-sm font-medium">Comment</span>
            </button>

            <!-- Share Button -->
            @if(!$post->shared_post_id)
                <button
                    wire:click="$set('showShareModal', true)"
                    class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-surface-500 dark:text-surface-400 hover:bg-surface-100 dark:hover:bg-surface-700 transition-all"
                >
                    <x-heroicon-o-share class="h-5 w-5" />
                    <span class="text-sm font-medium">Share</span>
                </button>
            @endif
        </div>

        <!-- Share Modal -->
        @if($showShareModal)
            <div class="fixed inset-0 z-50 overflow-y-auto" x-data>
                <div class="flex items-center justify-center min-h-screen px-4 py-8">
                    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="$set('showShareModal', false)"></div>
                    <div class="relative bg-white dark:bg-surface-800 rounded-2xl shadow-2xl max-w-lg w-full p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold text-surface-900 dark:text-white">Share Post</h3>
                            <button wire:click="$set('showShareModal', false)" class="p-2 rounded-xl text-surface-400 hover:text-surface-600 dark:hover:text-surface-300 hover:bg-surface-100 dark:hover:bg-surface-700 transition-colors">
                                <x-heroicon-o-x-mark class="w-5 h-5" />
                            </button>
                        </div>

                        <div class="space-y-4">
                            <textarea wire:model="shareBody"
                                      placeholder="Add a comment (optional)..."
                                      rows="3"
                                      class="w-full rounded-xl border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-700 text-surface-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 placeholder-surface-400"></textarea>

                            <!-- Preview of original post -->
                            <div class="border border-surface-200 dark:border-surface-700 rounded-xl p-4 bg-surface-50 dark:bg-surface-700/50">
                                <div class="flex items-center gap-3">
                                    <img class="h-10 w-10 rounded-xl" src="{{ $post->user->profile_photo_url }}" alt="{{ $post->user->name }}">
                                    <div>
                                        <p class="font-semibold text-sm text-surface-900 dark:text-white">{{ $post->user->name }}</p>
                                        <p class="text-xs text-surface-500 dark:text-surface-400">{{ $post->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                @if($post->body)
                                    <p class="mt-3 text-sm text-surface-600 dark:text-surface-300 line-clamp-3">{{ $post->body }}</p>
                                @endif
                            </div>

                            <!-- Visibility -->
                            <div>
                                <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">Who can see this?</label>
                                <select wire:model="shareVisibility" class="w-full rounded-xl border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-700 text-surface-900 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                                    <option value="public">Public</option>
                                    <option value="friends">Friends only</option>
                                    <option value="private">Only me</option>
                                </select>
                            </div>

                            <x-ui.button wire:click="sharePost" variant="primary" class="w-full">
                                Share Now
                            </x-ui.button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Comments Section -->
        @if ($showComments)
            <div class="border-t border-surface-100 dark:border-surface-700/50 p-4">
                <!-- Comment Form -->
                <form wire:submit="addComment" class="flex items-start gap-3 mb-4">
                    <img class="h-9 w-9 rounded-xl object-cover" src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}">
                    <div class="flex-1 flex items-center gap-2">
                        <input
                            type="text"
                            wire:model="newComment"
                            placeholder="Write a comment..."
                            class="flex-1 rounded-xl border-surface-300 dark:border-surface-600 bg-surface-50 dark:bg-surface-700 text-surface-900 dark:text-surface-200 text-sm focus:border-primary-500 focus:ring-primary-500 placeholder-surface-400"
                        >
                        <button
                            type="submit"
                            class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-gradient-to-r from-primary-500 to-accent-500 text-white hover:opacity-90 transition-opacity"
                        >
                            <x-heroicon-s-paper-airplane class="h-4 w-4" />
                        </button>
                    </div>
                </form>

                <!-- Comments List -->
                <div class="space-y-4">
                    @foreach ($post->topLevelComments as $comment)
                        <div class="flex items-start gap-3">
                            <a href="{{ route('profile.view', $comment->user->profile?->username ?? $comment->user->id) }}">
                                <img class="h-9 w-9 rounded-xl object-cover" src="{{ $comment->user->profile_photo_url }}" alt="{{ $comment->user->name }}">
                            </a>
                            <div class="flex-1">
                                <div class="bg-surface-100 dark:bg-surface-700 rounded-2xl px-4 py-2.5">
                                    <a href="{{ route('profile.view', $comment->user->profile?->username ?? $comment->user->id) }}" class="font-semibold text-sm text-surface-900 dark:text-surface-100 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                                        {{ $comment->user->name }}
                                    </a>
                                    <p class="text-sm text-surface-800 dark:text-surface-200 mt-0.5">{{ $comment->body }}</p>
                                </div>
                                <div class="flex items-center gap-4 mt-1.5 ml-4 text-xs text-surface-500 dark:text-surface-400">
                                    <span>{{ $comment->created_at->diffForHumans() }}</span>
                                    <button wire:click="likeComment({{ $comment->id }})"
                                            class="font-medium hover:text-primary-600 dark:hover:text-primary-400 transition-colors {{ $comment->likes()->where('user_id', auth()->id())->exists() ? 'text-primary-600 dark:text-primary-400' : '' }}">
                                        Like @if($comment->likes_count > 0)<span class="text-surface-400">({{ $comment->likes_count }})</span>@endif
                                    </button>
                                    <button wire:click="startReply({{ $comment->id }})"
                                            class="font-medium hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                                        Reply
                                    </button>
                                </div>

                                {{-- Reply Form --}}
                                @if($replyingToCommentId === $comment->id)
                                    <div class="mt-3 ml-4 flex items-start gap-2">
                                        <img class="h-7 w-7 rounded-lg object-cover" src="{{ auth()->user()->profile_photo_url }}" alt="">
                                        <div class="flex-1">
                                            <textarea wire:model="replyBody"
                                                      placeholder="Write a reply..."
                                                      rows="2"
                                                      class="w-full text-sm rounded-xl border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-700 text-surface-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 placeholder-surface-400 resize-none"></textarea>
                                            <div class="flex justify-end gap-2 mt-2">
                                                <button wire:click="cancelReply" class="text-xs px-3 py-1.5 rounded-lg text-surface-600 dark:text-surface-400 hover:bg-surface-100 dark:hover:bg-surface-700">
                                                    Cancel
                                                </button>
                                                <button wire:click="submitReply" class="text-xs px-3 py-1.5 rounded-lg bg-primary-600 text-white hover:bg-primary-700">
                                                    Reply
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                {{-- Show replies --}}
                                @if($comment->replies && $comment->replies->count() > 0)
                                    <div class="mt-3 ml-4 space-y-3">
                                        @foreach($comment->replies as $reply)
                                            <div class="flex items-start gap-2">
                                                <a href="{{ route('profile.view', $reply->user->profile?->username ?? $reply->user->id) }}">
                                                    <img class="h-7 w-7 rounded-lg object-cover" src="{{ $reply->user->profile_photo_url }}" alt="{{ $reply->user->name }}">
                                                </a>
                                                <div class="flex-1">
                                                    <div class="bg-surface-100 dark:bg-surface-700 rounded-xl px-3 py-2">
                                                        <a href="{{ route('profile.view', $reply->user->profile?->username ?? $reply->user->id) }}" class="font-semibold text-xs text-surface-900 dark:text-surface-100 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                                                            {{ $reply->user->name }}
                                                        </a>
                                                        <p class="text-xs text-surface-800 dark:text-surface-200 mt-0.5">{{ $reply->body }}</p>
                                                    </div>
                                                    <div class="flex items-center gap-3 mt-1 ml-3 text-xs text-surface-500 dark:text-surface-400">
                                                        <span>{{ $reply->created_at->diffForHumans() }}</span>
                                                        <button wire:click="likeComment({{ $reply->id }})"
                                                                class="font-medium hover:text-primary-600 dark:hover:text-primary-400 transition-colors {{ $reply->likes()->where('user_id', auth()->id())->exists() ? 'text-primary-600 dark:text-primary-400' : '' }}">
                                                            Like @if($reply->likes_count > 0)<span class="text-surface-400">({{ $reply->likes_count }})</span>@endif
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Edit Modal -->
        @if($showEditModal)
            <div class="fixed inset-0 z-50 overflow-y-auto" x-data>
                <div class="flex items-center justify-center min-h-screen px-4 py-8">
                    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="$set('showEditModal', false)"></div>
                    <div class="relative bg-white dark:bg-surface-800 rounded-2xl shadow-2xl max-w-lg w-full p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold text-surface-900 dark:text-white">Edit Post</h3>
                            <button wire:click="$set('showEditModal', false)" class="p-2 rounded-xl text-surface-400 hover:text-surface-600 dark:hover:text-surface-300 hover:bg-surface-100 dark:hover:bg-surface-700 transition-colors">
                                <x-heroicon-o-x-mark class="w-5 h-5" />
                            </button>
                        </div>
                        <div class="space-y-4">
                            <textarea wire:model="editBody"
                                      placeholder="What's on your mind?"
                                      rows="5"
                                      class="w-full rounded-xl border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-700 text-surface-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 placeholder-surface-400"></textarea>
                            <div class="flex justify-end gap-3">
                                <x-ui.button wire:click="$set('showEditModal', false)" variant="secondary">
                                    Cancel
                                </x-ui.button>
                                <x-ui.button wire:click="saveEdit" variant="primary">
                                    Save Changes
                                </x-ui.button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Edit History Modal -->
        @if($showEditHistory)
            <div class="fixed inset-0 z-50 overflow-y-auto" x-data>
                <div class="flex items-center justify-center min-h-screen px-4 py-8">
                    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="$set('showEditHistory', false)"></div>
                    <div class="relative bg-white dark:bg-surface-800 rounded-2xl shadow-2xl max-w-lg w-full p-6 max-h-[80vh] overflow-y-auto">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold text-surface-900 dark:text-white">Edit History</h3>
                            <button wire:click="$set('showEditHistory', false)" class="p-2 rounded-xl text-surface-400 hover:text-surface-600 dark:hover:text-surface-300 hover:bg-surface-100 dark:hover:bg-surface-700 transition-colors">
                                <x-heroicon-o-x-mark class="w-5 h-5" />
                            </button>
                        </div>
                        <div class="space-y-4">
                            <!-- Current version -->
                            <div class="border-l-4 border-primary-500 pl-4">
                                <div class="text-xs text-surface-500 dark:text-surface-400 mb-1 flex items-center gap-2">
                                    <x-ui.badge variant="primary" size="sm">Current</x-ui.badge>
                                    {{ $post->edited_at?->diffForHumans() ?? 'Now' }}
                                </div>
                                <p class="text-surface-800 dark:text-surface-200 whitespace-pre-wrap">{{ $post->body }}</p>
                            </div>

                            <!-- Previous versions -->
                            @foreach($post->edits as $edit)
                                <div class="border-l-4 border-surface-300 dark:border-surface-600 pl-4">
                                    <div class="text-xs text-surface-500 dark:text-surface-400 mb-1">
                                        {{ $edit->created_at->diffForHumans() }}
                                    </div>
                                    <p class="text-surface-600 dark:text-surface-400 whitespace-pre-wrap">{{ $edit->previous_body }}</p>
                                </div>
                            @endforeach

                            @if($post->edits->isEmpty())
                                <p class="text-surface-500 dark:text-surface-400 text-sm text-center py-4">No previous versions available</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Report Modal -->
        @if($showReportModal)
            <div class="fixed inset-0 z-50 overflow-y-auto" x-data>
                <div class="flex items-center justify-center min-h-screen px-4 py-8">
                    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="$set('showReportModal', false)"></div>
                    <div class="relative bg-white dark:bg-surface-800 rounded-2xl shadow-2xl max-w-md w-full p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold text-surface-900 dark:text-white">Report Post</h3>
                            <button wire:click="$set('showReportModal', false)" class="p-2 rounded-xl text-surface-400 hover:text-surface-600 dark:hover:text-surface-300 hover:bg-surface-100 dark:hover:bg-surface-700 transition-colors">
                                <x-heroicon-o-x-mark class="w-5 h-5" />
                            </button>
                        </div>

                        <p class="text-sm text-surface-600 dark:text-surface-400 mb-4">
                            Please select a reason for reporting this post:
                        </p>

                        <div class="space-y-2 mb-4">
                            @foreach($reportReasons as $key => $label)
                                <label class="flex items-center p-3 border border-surface-200 dark:border-surface-700 rounded-xl cursor-pointer hover:bg-surface-50 dark:hover:bg-surface-700 transition-colors {{ $reportReason === $key ? 'border-primary-500 bg-primary-50 dark:bg-primary-900/20' : '' }}">
                                    <input type="radio" wire:model="reportReason" value="{{ $key }}" class="mr-3 text-primary-600 focus:ring-primary-500">
                                    <span class="text-sm text-surface-700 dark:text-surface-300">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">
                                Additional Details (Optional)
                            </label>
                            <textarea wire:model="reportDetails" rows="3" placeholder="Provide more context about this report..."
                                      class="w-full rounded-xl border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-700 text-surface-900 dark:text-white text-sm focus:ring-primary-500 focus:border-primary-500 placeholder-surface-400"></textarea>
                        </div>

                        <div class="flex justify-end gap-3">
                            <x-ui.button wire:click="$set('showReportModal', false)" variant="secondary">
                                Cancel
                            </x-ui.button>
                            <x-ui.button wire:click="reportPost" variant="danger" :disabled="!$reportReason">
                                Submit Report
                            </x-ui.button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </x-ui.card>
</div>
