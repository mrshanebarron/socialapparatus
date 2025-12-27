<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
        @if(session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                {{ session('message') }}
            </div>
        @endif

        @if(session()->has('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                {{ session('error') }}
            </div>
        @endif

        <!-- Data Export Section -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Download Your Data</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                    Request a copy of all your data. This includes posts, messages, photos, and account information.
                </p>

                <button wire:click="requestDataExport" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                    Request Data Export
                </button>

                @if($dataExports->isNotEmpty())
                    <div class="mt-6">
                        <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Recent Export Requests</h3>
                        <div class="space-y-2">
                            @foreach($dataExports as $export)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div>
                                        <span class="text-sm text-gray-900 dark:text-white">
                                            Requested {{ $export->requested_at->format('M j, Y') }}
                                        </span>
                                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                            @if($export->status === 'ready') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                                            @elseif($export->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100
                                            @elseif($export->status === 'processing') bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100
                                            @else bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-100
                                            @endif">
                                            {{ ucfirst($export->status) }}
                                        </span>
                                    </div>
                                    @if($export->status === 'ready' && $export->file_path)
                                        <a href="{{ Storage::url($export->file_path) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                            Download
                                        </a>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Account Deletion Section -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Delete Account</h2>

                @if($pendingDeletion)
                    <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-lg p-4">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <span class="ml-2 text-red-800 dark:text-red-200 font-medium">Account scheduled for deletion</span>
                        </div>
                        <p class="mt-2 text-sm text-red-700 dark:text-red-300">
                            Your account will be permanently deleted on {{ $pendingDeletion->scheduled_for->format('F j, Y') }}.
                            You can cancel this at any time before that date.
                        </p>
                        <button wire:click="cancelAccountDeletion" class="mt-3 inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-red-300 dark:border-red-600 rounded-md font-semibold text-xs text-red-600 dark:text-red-400 uppercase tracking-widest hover:bg-red-50 dark:hover:bg-red-900/20">
                            Cancel Deletion
                        </button>
                    </div>
                @else
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                        Permanently delete your account and all associated data. This action cannot be undone after the grace period.
                    </p>
                    <button wire:click="$set('showDeleteModal', true)" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                        Delete My Account
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Delete Account Modal -->
    @if($showDeleteModal)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Delete Account</h3>

                    <div class="bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4 mb-4">
                        <p class="text-sm text-yellow-800 dark:text-yellow-200">
                            Your account will be scheduled for deletion in 30 days. During this period, you can still cancel the deletion.
                            After 30 days, all your data will be permanently removed.
                        </p>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Reason for leaving (optional)</label>
                            <select wire:model="deleteReason" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Select a reason</option>
                                <option value="privacy">Privacy concerns</option>
                                <option value="not_useful">Not useful to me</option>
                                <option value="too_much_time">Spending too much time</option>
                                <option value="another_account">Created another account</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Additional feedback (optional)</label>
                            <textarea wire:model="deleteFeedback" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Help us improve by sharing your feedback..."></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type DELETE to confirm</label>
                            <input wire:model="deleteConfirm" type="text" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <button wire:click="$set('showDeleteModal', false)" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-500">
                            Cancel
                        </button>
                        <button wire:click="scheduleAccountDeletion" class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700">
                            Schedule Deletion
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
