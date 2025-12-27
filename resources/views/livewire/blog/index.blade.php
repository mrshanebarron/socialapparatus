<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Blog</h1>
            <div class="mt-4 sm:mt-0">
                <a href="{{ route('blog.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Write Article
                </a>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="mb-6 bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="-mb-px flex" aria-label="Tabs">
                    <button
                        wire:click="setFilter('all')"
                        class="w-1/3 py-4 px-1 text-center border-b-2 font-medium text-sm {{ $filter === 'all' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}"
                    >
                        All Articles
                    </button>
                    <button
                        wire:click="setFilter('my-articles')"
                        class="w-1/3 py-4 px-1 text-center border-b-2 font-medium text-sm {{ $filter === 'my-articles' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}"
                    >
                        My Articles
                    </button>
                    <button
                        wire:click="setFilter('drafts')"
                        class="w-1/3 py-4 px-1 text-center border-b-2 font-medium text-sm {{ $filter === 'drafts' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}"
                    >
                        Drafts
                    </button>
                </nav>
            </div>
        </div>

        <!-- Articles List -->
        @if ($articles->isEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No articles yet</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    @if ($filter === 'drafts')
                        You don't have any drafts.
                    @elseif ($filter === 'my-articles')
                        You haven't written any articles yet.
                    @else
                        Be the first to write an article!
                    @endif
                </p>
                <div class="mt-6">
                    <a href="{{ route('blog.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                        Write Your First Article
                    </a>
                </div>
            </div>
        @else
            <div class="space-y-6">
                @foreach ($articles as $article)
                    <article class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                        <div class="md:flex">
                            @if ($article->featured_image)
                                <div class="md:flex-shrink-0">
                                    <img class="h-48 w-full object-cover md:w-48" src="{{ $article->featured_image_url }}" alt="{{ $article->title }}">
                                </div>
                            @endif
                            <div class="p-6 {{ $article->featured_image ? '' : 'w-full' }}">
                                <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-2">
                                    @if ($article->isDraft())
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 mr-2">
                                            Draft
                                        </span>
                                    @endif
                                    <span>{{ $article->reading_time }} min read</span>
                                    <span class="mx-2">&middot;</span>
                                    <span>{{ $article->published_at?->format('M d, Y') ?? 'Not published' }}</span>
                                </div>
                                <a href="{{ route('blog.show', $article) }}" class="block mt-1">
                                    <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 hover:text-indigo-600 dark:hover:text-indigo-400">{{ $article->title }}</h2>
                                </a>
                                @if ($article->excerpt)
                                    <p class="mt-2 text-gray-600 dark:text-gray-300 line-clamp-2">{{ $article->excerpt }}</p>
                                @else
                                    <p class="mt-2 text-gray-600 dark:text-gray-300 line-clamp-2">{{ Str::limit(strip_tags($article->body), 200) }}</p>
                                @endif
                                <div class="mt-4 flex items-center justify-between">
                                    <div class="flex items-center">
                                        <img class="h-8 w-8 rounded-full object-cover" src="{{ $article->user->profile_photo_url }}" alt="{{ $article->user->name }}">
                                        <div class="ml-2">
                                            <a href="{{ route('profile.view', $article->user->profile?->username ?? $article->user->id) }}" class="text-sm font-medium text-gray-900 dark:text-gray-100 hover:underline">
                                                {{ $article->user->name }}
                                            </a>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-400">
                                        <span class="flex items-center">
                                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            {{ $article->views_count }}
                                        </span>
                                        <span class="flex items-center">
                                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                            </svg>
                                            {{ $article->likes_count }}
                                        </span>
                                        <span class="flex items-center">
                                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                            </svg>
                                            {{ $article->comments_count }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <!-- Pagination -->
            @if ($articles->hasPages())
                <div class="mt-6">
                    {{ $articles->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
