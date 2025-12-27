<div>
    <x-ui.card padding="none" class="h-[calc(100vh-12rem)] flex overflow-hidden">
        <!-- Conversation List -->
        <div class="w-full md:w-80 border-r border-surface-200 dark:border-surface-700 flex flex-col {{ $activeConversation ? 'hidden md:flex' : '' }}">
            <!-- Header -->
            <div class="p-4 border-b border-surface-200 dark:border-surface-700">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-surface-900 dark:text-white">Messages</h2>
                    @livewire('messages.new-conversation')
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search conversations..."
                       class="w-full rounded-xl border-surface-300 dark:border-surface-600 dark:bg-surface-700 dark:text-white text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500">
            </div>

            <!-- Conversations -->
            <div class="flex-1 overflow-y-auto">
                @forelse($conversations as $conversation)
                    <button wire:click="conversationSelected({{ $conversation->id }})"
                            class="w-full p-4 flex items-center space-x-3 hover:bg-surface-50 dark:hover:bg-surface-700/50 transition-colors {{ $activeConversation?->id === $conversation->id ? 'bg-primary-50 dark:bg-primary-900/20' : '' }}">
                        <img class="h-12 w-12 rounded-xl object-cover flex-shrink-0"
                             src="{{ $conversation->avatar_url }}"
                             alt="{{ $conversation->display_name }}">
                        <div class="flex-1 min-w-0 text-left">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-surface-900 dark:text-white truncate">
                                    {{ $conversation->display_name }}
                                </p>
                                @if($conversation->latestMessage)
                                    <p class="text-xs text-surface-500 dark:text-surface-400">
                                        {{ $conversation->latestMessage->created_at->shortAbsoluteDiffForHumans() }}
                                    </p>
                                @endif
                            </div>
                            <div class="flex items-center justify-between">
                                <p class="text-sm text-surface-500 dark:text-surface-400 truncate">
                                    @if($conversation->latestMessage)
                                        @if($conversation->latestMessage->user_id === auth()->id())
                                            You:
                                        @endif
                                        {{ Str::limit($conversation->latestMessage->body, 30) }}
                                    @else
                                        No messages yet
                                    @endif
                                </p>
                                @php $unread = $conversation->getUnreadCountFor(auth()->user()) @endphp
                                @if($unread > 0)
                                    <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-primary-600 rounded-full">
                                        {{ $unread }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </button>
                @empty
                    <div class="p-8 text-center">
                        <x-heroicon-o-chat-bubble-left-right class="mx-auto h-12 w-12 text-surface-400" />
                        <h3 class="mt-2 text-sm font-medium text-surface-900 dark:text-white">No conversations</h3>
                        <p class="mt-1 text-sm text-surface-500 dark:text-surface-400">
                            Start a conversation with someone!
                        </p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Chat Area -->
        <div class="flex-1 flex flex-col {{ !$activeConversation ? 'hidden md:flex' : '' }}">
            @if($activeConversation)
                @livewire('messages.chat-box', ['conversation' => $activeConversation], key('chat-'.$activeConversation->id))
            @else
                <div class="flex-1 flex items-center justify-center">
                    <div class="text-center">
                        <x-heroicon-o-chat-bubble-left-right class="mx-auto h-16 w-16 text-surface-400" />
                        <h3 class="mt-4 text-lg font-medium text-surface-900 dark:text-white">Select a conversation</h3>
                        <p class="mt-2 text-sm text-surface-500 dark:text-surface-400">
                            Choose a conversation from the list or start a new one.
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </x-ui.card>
</div>
