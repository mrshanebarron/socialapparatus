<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <a href="{{ route('fundraisers.index') }}" class="inline-flex items-center text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Fundraisers
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                <!-- Cover Image -->
                @if($fundraiser->cover_image_url)
                    <img src="{{ $fundraiser->cover_image_url }}" alt="{{ $fundraiser->title }}" class="w-full aspect-video object-cover">
                @else
                    <div class="w-full aspect-video bg-gradient-to-r from-indigo-500 to-purple-500 flex items-center justify-center">
                        <svg class="w-24 h-24 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </div>
                @endif

                <div class="p-6">
                    <!-- Title & Category -->
                    <div class="mb-4">
                        <span class="inline-flex items-center px-2 py-1 text-xs bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 rounded-full mb-2">
                            {{ $categories[$fundraiser->category] ?? 'Other' }}
                        </span>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $fundraiser->title }}</h1>
                    </div>

                    <!-- Organizer -->
                    <div class="flex items-center space-x-3 mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                        <a href="{{ route('profile.view', $fundraiser->user->profile?->username ?? $fundraiser->user->id) }}">
                            <img src="{{ $fundraiser->user->profile_photo_url }}" alt="{{ $fundraiser->user->name }}" class="w-10 h-10 rounded-full">
                        </a>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Organized by</p>
                            <a href="{{ route('profile.view', $fundraiser->user->profile?->username ?? $fundraiser->user->id) }}" class="font-medium text-gray-900 dark:text-white hover:underline">
                                {{ $fundraiser->user->name }}
                            </a>
                        </div>
                    </div>

                    @if($fundraiser->beneficiary_name)
                        <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <p class="text-sm text-gray-600 dark:text-gray-400">Beneficiary</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $fundraiser->beneficiary_name }}</p>
                        </div>
                    @endif

                    <!-- Story -->
                    <div class="prose dark:prose-invert max-w-none">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Story</h3>
                        <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $fundraiser->story }}</p>
                    </div>
                </div>
            </div>

            <!-- Donations List -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow mt-6 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    Recent Donations ({{ $fundraiser->donors_count }})
                </h3>
                @if($recentDonations->isEmpty())
                    <p class="text-gray-500 dark:text-gray-400 text-center py-8">Be the first to donate!</p>
                @else
                    <div class="space-y-4">
                        @foreach($recentDonations as $donation)
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    @if($donation->is_anonymous)
                                        <div class="w-10 h-10 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                    @elseif($donation->user)
                                        <img src="{{ $donation->user->profile_photo_url }}" class="w-10 h-10 rounded-full">
                                    @else
                                        <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900 rounded-full flex items-center justify-center">
                                            <span class="text-indigo-600 dark:text-indigo-400 font-medium">{{ substr($donation->donor_name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <p class="font-medium text-gray-900 dark:text-white">{{ $donation->display_name }}</p>
                                        <span class="text-green-600 dark:text-green-400 font-semibold">{{ $donation->formatted_amount }}</span>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $donation->created_at->diffForHumans() }}</p>
                                    @if($donation->message)
                                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">"{{ $donation->message }}"</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Progress Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 sticky top-6">
                <!-- Progress -->
                <div class="mb-4">
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $fundraiser->formatted_raised }}</p>
                    <p class="text-gray-600 dark:text-gray-400">raised of {{ $fundraiser->formatted_goal }} goal</p>
                </div>

                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3 mb-4">
                    <div class="bg-green-500 h-3 rounded-full transition-all duration-500" style="width: {{ min(100, $fundraiser->progress_percent) }}%"></div>
                </div>

                <div class="flex items-center justify-between text-sm text-gray-600 dark:text-gray-400 mb-6">
                    <span>{{ $fundraiser->donors_count }} donors</span>
                    @if($fundraiser->ends_at)
                        @if($fundraiser->hasEnded())
                            <span class="text-red-500">Campaign ended</span>
                        @else
                            <span>{{ $fundraiser->ends_at->diffForHumans() }}</span>
                        @endif
                    @endif
                </div>

                @if(!$fundraiser->hasEnded())
                    <button wire:click="$set('showDonateModal', true)" class="w-full py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold mb-3">
                        Donate Now
                    </button>
                @endif

                <button wire:click="share" class="w-full py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 font-medium flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                    </svg>
                    Share
                </button>

                <p class="text-center text-xs text-gray-500 dark:text-gray-400 mt-4">
                    {{ $fundraiser->shares_count }} people have shared this
                </p>
            </div>
        </div>
    </div>

    <!-- Donate Modal -->
    @if($showDonateModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black/50" wire:click="$set('showDonateModal', false)"></div>
                <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Make a Donation</h3>
                        <button wire:click="$set('showDonateModal', false)" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Suggested Amounts -->
                    <div class="grid grid-cols-3 gap-2 mb-4">
                        @foreach($suggestedAmounts as $amount)
                            <button wire:click="setAmount({{ $amount }})"
                                    class="py-3 rounded-lg border text-center font-medium {{ $donationAmount == $amount ? 'border-green-500 bg-green-50 dark:bg-green-900/50 text-green-700 dark:text-green-300' : 'border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                ${{ $amount }}
                            </button>
                        @endforeach
                    </div>

                    <!-- Custom Amount -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Custom Amount</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">$</span>
                            <input type="number" wire:model="donationAmount" step="1" min="1"
                                   class="w-full pl-8 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>

                    <!-- Donor Name -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Your Name</label>
                        <input type="text" wire:model="donorName"
                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    </div>

                    <!-- Message -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Message (Optional)</label>
                        <textarea wire:model="donationMessage" rows="2" placeholder="Add an encouraging message..."
                                  class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></textarea>
                    </div>

                    <!-- Anonymous -->
                    <label class="flex items-center mb-6 text-sm text-gray-600 dark:text-gray-400">
                        <input type="checkbox" wire:model="isAnonymous" class="rounded border-gray-300 dark:border-gray-600 mr-2">
                        Donate anonymously
                    </label>

                    <button wire:click="donate" class="w-full py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="donate">Donate ${{ number_format($donationAmount, 0) }}</span>
                        <span wire:loading wire:target="donate">Processing...</span>
                    </button>

                    <p class="text-xs text-gray-500 text-center mt-4">
                        This is a demo. No actual payment will be processed.
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Share Modal -->
    @if($showShareModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black/50" wire:click="$set('showShareModal', false)"></div>
                <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Share this Fundraiser</h3>
                        <button wire:click="$set('showShareModal', false)" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="space-y-3">
                        <button onclick="navigator.clipboard.writeText('{{ url()->current() }}')" class="w-full py-3 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                            </svg>
                            Copy Link
                        </button>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="w-full py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center justify-center">
                            Share on Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($fundraiser->title) }}" target="_blank" class="w-full py-3 bg-sky-500 text-white rounded-lg hover:bg-sky-600 flex items-center justify-center">
                            Share on X (Twitter)
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
