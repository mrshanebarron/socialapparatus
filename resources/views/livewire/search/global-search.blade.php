<div class="relative" x-data="{ focused: false }">
    <!-- Search Input -->
    <div class="relative">
        <input type="text"
               wire:model.live.debounce.300ms="query"
               @focus="focused = true"
               @click.away="focused = false"
               placeholder="Search..."
               class="w-full pl-10 pr-4 py-2.5 text-sm bg-surface-100 dark:bg-surface-700 border-0 rounded-xl focus:ring-2 focus:ring-primary-500 focus:bg-white dark:focus:bg-surface-600 text-surface-900 dark:text-white placeholder-surface-400 dark:placeholder-surface-500 transition-all duration-200">
        <div class="absolute inset-y-0 left-0 flex items-center pl-3">
            <x-heroicon-o-magnifying-glass class="w-5 h-5 text-surface-400" />
        </div>
        @if(strlen($query) > 0)
            <button wire:click="clearSearch" class="absolute inset-y-0 right-0 flex items-center pr-3">
                <x-heroicon-o-x-mark class="w-5 h-5 text-surface-400 hover:text-surface-600 dark:hover:text-surface-300 transition-colors" />
            </button>
        @endif
    </div>

    <!-- Search Results Dropdown -->
    @if($showResults && (count($results['users']) > 0 || count($results['groups']) > 0 || count($results['posts']) > 0 || count($results['articles']) > 0))
        <div class="absolute top-full left-0 right-0 mt-2 bg-white dark:bg-surface-800 rounded-2xl shadow-soft-lg border border-surface-200 dark:border-surface-700 z-50 max-h-96 overflow-y-auto"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0">

            <!-- Users -->
            @if(count($results['users']) > 0)
                <div class="px-4 py-2.5 border-b border-surface-100 dark:border-surface-700">
                    <h4 class="text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider">People</h4>
                </div>
                @foreach($results['users'] as $user)
                    <a href="{{ route('profile.view', $user) }}" class="flex items-center px-4 py-3 hover:bg-surface-50 dark:hover:bg-surface-700 transition-colors">
                        <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="w-10 h-10 rounded-xl object-cover">
                        <div class="ml-3">
                            <p class="text-sm font-semibold text-surface-900 dark:text-white">{{ $user->name }}</p>
                            @if($user->profile?->headline)
                                <p class="text-xs text-surface-500 dark:text-surface-400">{{ Str::limit($user->profile->headline, 40) }}</p>
                            @endif
                        </div>
                    </a>
                @endforeach
            @endif

            <!-- Groups -->
            @if(count($results['groups']) > 0)
                <div class="px-4 py-2.5 border-b border-surface-100 dark:border-surface-700 {{ count($results['users']) > 0 ? 'border-t' : '' }}">
                    <h4 class="text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider">Groups</h4>
                </div>
                @foreach($results['groups'] as $group)
                    <a href="{{ route('groups.show', $group) }}" class="flex items-center px-4 py-3 hover:bg-surface-50 dark:hover:bg-surface-700 transition-colors">
                        @if($group->cover_image)
                            <img src="{{ Storage::url($group->cover_image) }}" alt="{{ $group->name }}" class="w-10 h-10 rounded-xl object-cover">
                        @else
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center">
                                <x-heroicon-o-user-group class="w-5 h-5 text-white" />
                            </div>
                        @endif
                        <div class="ml-3">
                            <p class="text-sm font-semibold text-surface-900 dark:text-white">{{ $group->name }}</p>
                            <p class="text-xs text-surface-500 dark:text-surface-400">{{ $group->members_count }} members</p>
                        </div>
                    </a>
                @endforeach
            @endif

            <!-- Posts -->
            @if(count($results['posts']) > 0)
                <div class="px-4 py-2.5 border-b border-surface-100 dark:border-surface-700 border-t">
                    <h4 class="text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider">Posts</h4>
                </div>
                @foreach($results['posts'] as $post)
                    <a href="{{ route('posts.show', $post) }}" class="flex items-center px-4 py-3 hover:bg-surface-50 dark:hover:bg-surface-700 transition-colors">
                        <img src="{{ $post->user->profile_photo_url }}" alt="{{ $post->user->name }}" class="w-10 h-10 rounded-xl object-cover">
                        <div class="ml-3 flex-1 min-w-0">
                            <p class="text-sm text-surface-900 dark:text-white truncate">{{ Str::limit(strip_tags($post->body), 60) }}</p>
                            <p class="text-xs text-surface-500 dark:text-surface-400">by {{ $post->user->name }}</p>
                        </div>
                    </a>
                @endforeach
            @endif

            <!-- Articles -->
            @if(count($results['articles']) > 0)
                <div class="px-4 py-2.5 border-b border-surface-100 dark:border-surface-700 border-t">
                    <h4 class="text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider">Articles</h4>
                </div>
                @foreach($results['articles'] as $article)
                    <a href="{{ route('blog.show', $article) }}" class="flex items-center px-4 py-3 hover:bg-surface-50 dark:hover:bg-surface-700 transition-colors">
                        @if($article->featured_image)
                            <img src="{{ Storage::url($article->featured_image) }}" alt="{{ $article->title }}" class="w-10 h-10 rounded-xl object-cover">
                        @else
                            <div class="w-10 h-10 rounded-xl bg-surface-200 dark:bg-surface-600 flex items-center justify-center">
                                <x-heroicon-o-document-text class="w-5 h-5 text-surface-400" />
                            </div>
                        @endif
                        <div class="ml-3 flex-1 min-w-0">
                            <p class="text-sm font-semibold text-surface-900 dark:text-white truncate">{{ $article->title }}</p>
                            <p class="text-xs text-surface-500 dark:text-surface-400">by {{ $article->user->name }}</p>
                        </div>
                    </a>
                @endforeach
            @endif
        </div>
    @elseif($showResults && strlen($query) >= 2)
        <div class="absolute top-full left-0 right-0 mt-2 bg-white dark:bg-surface-800 rounded-2xl shadow-soft-lg border border-surface-200 dark:border-surface-700 z-50 px-4 py-8 text-center"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0">
            <x-heroicon-o-magnifying-glass class="w-12 h-12 mx-auto text-surface-300 dark:text-surface-600 mb-3" />
            <p class="text-sm text-surface-500 dark:text-surface-400">No results found for "{{ $query }}"</p>
        </div>
    @endif
</div>
