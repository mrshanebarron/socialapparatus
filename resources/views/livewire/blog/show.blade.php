<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Article Header -->
        <article class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            @if ($article->featured_image)
                <img src="{{ $article->featured_image_url }}" alt="{{ $article->title }}" class="w-full h-64 md:h-96 object-cover">
            @endif

            <div class="p-6 md:p-8">
                <!-- Back Link -->
                <a href="{{ route('blog.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 mb-6">
                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Blog
                </a>

                <!-- Meta Info -->
                <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-4">
                    <span>{{ $article->reading_time }} min read</span>
                    <span class="mx-2">&middot;</span>
                    <span>{{ $article->published_at?->format('F d, Y') }}</span>
                    <span class="mx-2">&middot;</span>
                    <span>{{ $article->views_count }} views</span>
                </div>

                <!-- Title -->
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-gray-100 mb-6">{{ $article->title }}</h1>

                <!-- Author -->
                <div class="flex items-center mb-8 pb-8 border-b border-gray-200 dark:border-gray-700">
                    <img class="h-12 w-12 rounded-full object-cover" src="{{ $article->user->profile_photo_url }}" alt="{{ $article->user->name }}">
                    <div class="ml-4">
                        <a href="{{ route('profile.view', $article->user->profile?->username ?? $article->user->id) }}" class="text-lg font-medium text-gray-900 dark:text-gray-100 hover:underline">
                            {{ $article->user->name }}
                        </a>
                        @if ($article->user->profile?->bio)
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ Str::limit($article->user->profile->bio, 100) }}</p>
                        @endif
                    </div>
                    @if ($article->user_id === auth()->id())
                        <a href="{{ route('blog.edit', $article) }}" class="ml-auto inline-flex items-center px-3 py-1 bg-gray-100 dark:bg-gray-700 rounded-md text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600">
                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit
                        </a>
                    @endif
                </div>

                <!-- Content -->
                <div class="prose prose-lg dark:prose-invert max-w-none mb-8">
                    {!! nl2br(e($article->body)) !!}
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-between py-4 border-t border-b border-gray-200 dark:border-gray-700">
                    <button
                        wire:click="toggleLike"
                        class="flex items-center space-x-2 {{ $article->isLikedBy(auth()->user()) ? 'text-red-500' : 'text-gray-500 dark:text-gray-400' }} hover:text-red-500"
                    >
                        @if ($article->isLikedBy(auth()->user()))
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                            </svg>
                        @else
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        @endif
                        <span>{{ $article->likes_count }} {{ Str::plural('like', $article->likes_count) }}</span>
                    </button>
                    <span class="text-gray-500 dark:text-gray-400">
                        {{ $article->comments_count }} {{ Str::plural('comment', $article->comments_count) }}
                    </span>
                </div>

                <!-- Comments Section -->
                @if ($article->comments_enabled)
                    <div class="mt-8">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-6">Comments</h3>

                        <!-- Comment Form -->
                        @auth
                            <form wire:submit="addComment" class="mb-8">
                                <div class="flex space-x-4">
                                    <img class="h-10 w-10 rounded-full object-cover" src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}">
                                    <div class="flex-1">
                                        <textarea
                                            wire:model="newComment"
                                            rows="3"
                                            placeholder="Write a comment..."
                                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        ></textarea>
                                        <div class="mt-2 flex justify-end">
                                            <button
                                                type="submit"
                                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700"
                                            >
                                                Post Comment
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        @else
                            <p class="text-gray-500 dark:text-gray-400 mb-8">
                                <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">Log in</a> to leave a comment.
                            </p>
                        @endauth

                        <!-- Comments List -->
                        <div class="space-y-6">
                            @forelse ($article->comments as $comment)
                                <div class="flex space-x-4">
                                    <img class="h-10 w-10 rounded-full object-cover" src="{{ $comment->user->profile_photo_url }}" alt="{{ $comment->user->name }}">
                                    <div class="flex-1">
                                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg px-4 py-3">
                                            <div class="flex items-center justify-between mb-1">
                                                <a href="{{ route('profile.view', $comment->user->profile?->username ?? $comment->user->id) }}" class="font-medium text-gray-900 dark:text-gray-100 hover:underline">
                                                    {{ $comment->user->name }}
                                                </a>
                                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-gray-700 dark:text-gray-300">{{ $comment->body }}</p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-center text-gray-500 dark:text-gray-400 py-8">No comments yet. Be the first to comment!</p>
                            @endforelse
                        </div>
                    </div>
                @else
                    <p class="mt-8 text-center text-gray-500 dark:text-gray-400">Comments are disabled for this article.</p>
                @endif
            </div>
        </article>
    </div>
</div>
