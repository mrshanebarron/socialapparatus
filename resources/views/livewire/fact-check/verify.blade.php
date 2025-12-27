<div>
    @if($verification)
        <!-- Display existing verification -->
        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-4">
            <div class="flex items-center justify-between mb-2">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium" style="background-color: {{ $verification->label->color }}20; color: {{ $verification->label->color }}">
                    {{ $verification->label->icon }} {{ $verification->label->name }}
                </span>
                @if($verification->is_disputed)
                    <span class="text-xs text-orange-600">Disputed</span>
                @endif
            </div>
            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $verification->explanation }}</p>
            @if($verification->source_url)
                <a href="{{ $verification->source_url }}" target="_blank" class="text-xs text-indigo-600 hover:underline mt-2 inline-block">
                    View Source
                </a>
            @endif
            <div class="flex items-center justify-between mt-3 text-xs text-gray-500 dark:text-gray-400">
                <span>Verified by {{ $verification->verifier?->name }} {{ $verification->verified_at?->diffForHumans() }}</span>
                @auth
                    <button wire:click="openDisputeModal" class="text-orange-600 hover:text-orange-800">
                        Dispute this
                    </button>
                @endauth
            </div>
        </div>

        <!-- Disputes -->
        @if($disputes->isNotEmpty())
            <div class="mt-4">
                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Disputes ({{ $disputes->count() }})</h4>
                <div class="space-y-2">
                    @foreach($disputes as $dispute)
                        <div class="bg-orange-50 dark:bg-orange-900/20 rounded-lg p-3 text-sm">
                            <p class="text-gray-700 dark:text-gray-300">{{ $dispute->reason }}</p>
                            @if($dispute->evidence)
                                <p class="text-gray-500 dark:text-gray-400 mt-1 text-xs">Evidence: {{ Str::limit($dispute->evidence, 100) }}</p>
                            @endif
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                {{ $dispute->user->name }} - {{ $dispute->created_at->diffForHumans() }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @else
        <!-- No verification yet -->
        @if(auth()->user()?->canFactCheck())
            <button wire:click="openVerifyModal" class="text-sm text-indigo-600 hover:text-indigo-800">
                + Add Fact-Check
            </button>
        @endif
    @endif

    <!-- Verify Modal -->
    @if($showVerifyModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/50" wire:click="closeVerifyModal"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Add Fact-Check</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Label</label>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach($labels as $label)
                                <label class="cursor-pointer">
                                    <input type="radio" wire:model="selectedLabelId" value="{{ $label->id }}" class="sr-only peer">
                                    <div class="p-2 border rounded-lg text-center peer-checked:ring-2 peer-checked:ring-indigo-500" style="border-color: {{ $label->color }}40">
                                        <span class="text-lg">{{ $label->icon }}</span>
                                        <p class="text-xs mt-1" style="color: {{ $label->color }}">{{ $label->name }}</p>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        @error('selectedLabelId') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Explanation</label>
                        <textarea wire:model="explanation" rows="4" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700" placeholder="Provide context and evidence for this rating..."></textarea>
                        @error('explanation') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Source URL (optional)</label>
                        <input type="url" wire:model="sourceUrl" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700" placeholder="https://...">
                        @error('sourceUrl') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex space-x-3">
                        <button wire:click="closeVerifyModal" class="flex-1 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg">Cancel</button>
                        <button wire:click="submitVerification" class="flex-1 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Dispute Modal -->
    @if($showDisputeModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/50" wire:click="closeDisputeModal"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Dispute Fact-Check</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Reason for dispute</label>
                        <textarea wire:model="disputeReason" rows="4" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700" placeholder="Explain why you believe this fact-check is incorrect..."></textarea>
                        @error('disputeReason') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Supporting evidence (optional)</label>
                        <textarea wire:model="disputeEvidence" rows="2" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700" placeholder="Links or references to support your dispute..."></textarea>
                    </div>
                    <div class="flex space-x-3">
                        <button wire:click="closeDisputeModal" class="flex-1 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg">Cancel</button>
                        <button wire:click="submitDispute" class="flex-1 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700">Submit Dispute</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
