<div class="h-screen flex flex-col bg-gray-900">
    <!-- Video Area -->
    <div class="flex-1 flex">
        <div class="flex-1 relative">
            <div class="absolute inset-0 flex items-center justify-center bg-black">
                @if($party->video_provider === 'youtube')
                    <iframe
                        id="video-player"
                        class="w-full h-full"
                        src="https://www.youtube.com/embed/{{ Str::after($party->video_url, 'v=') }}?enablejsapi=1&autoplay=1&start={{ $party->current_time_seconds }}"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen>
                    </iframe>
                @else
                    <div class="text-white text-center">
                        <p>Video URL: {{ $party->video_url }}</p>
                    </div>
                @endif
            </div>

            <!-- Controls for host -->
            @if($party->host_id === auth()->id())
                <div class="absolute bottom-4 left-4 flex items-center space-x-2">
                    @if($party->status !== 'ended')
                        <button wire:click="endParty" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
                            End Party
                        </button>
                    @endif
                </div>
            @endif
        </div>

        <!-- Chat Sidebar -->
        <div class="w-80 bg-gray-800 flex flex-col">
            <!-- Participants -->
            <div class="p-4 border-b border-gray-700">
                <h3 class="text-white font-semibold mb-2">Watching ({{ $participants->count() }})</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($participants->take(6) as $participant)
                        <img src="{{ $participant->user->profile_photo_url }}" alt="{{ $participant->user->name }}" class="w-8 h-8 rounded-full" title="{{ $participant->user->name }}">
                    @endforeach
                    @if($participants->count() > 6)
                        <span class="w-8 h-8 rounded-full bg-gray-600 flex items-center justify-center text-white text-xs">+{{ $participants->count() - 6 }}</span>
                    @endif
                </div>
            </div>

            <!-- Messages -->
            <div class="flex-1 overflow-y-auto p-4 space-y-3" id="chat-messages">
                @foreach($messages as $msg)
                    <div class="flex items-start space-x-2">
                        <img src="{{ $msg->user->profile_photo_url }}" alt="" class="w-6 h-6 rounded-full flex-shrink-0">
                        <div>
                            <span class="text-sm font-medium text-gray-300">{{ $msg->user->name }}</span>
                            @if($msg->type === 'reaction')
                                <span class="text-2xl">{{ $msg->message }}</span>
                            @else
                                <p class="text-sm text-white">{{ $msg->message }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Reactions -->
            <div class="p-2 border-t border-gray-700 flex justify-center space-x-2">
                @foreach(['❤️', '😂', '😮', '👏', '🔥'] as $reaction)
                    <button wire:click="sendReaction('{{ $reaction }}')" class="text-2xl hover:scale-125 transition">{{ $reaction }}</button>
                @endforeach
            </div>

            <!-- Message Input -->
            <div class="p-4 border-t border-gray-700">
                <form wire:submit="sendMessage" class="flex space-x-2">
                    <input type="text" wire:model="message" placeholder="Send a message..." class="flex-1 bg-gray-700 text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                        Send
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
