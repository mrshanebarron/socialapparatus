<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Moderation Queue</h1>
        <a href="{{ route('moderation.rules') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 text-sm">
            Manage Rules
        </a>
    </div>

    @if(session('message'))
        <div class="bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg mb-6">
            {{ session('message') }}
        </div>
    @endif

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <p class="text-sm text-gray-500 dark:text-gray-400">Pending</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['pending'] }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <p class="text-sm text-gray-500 dark:text-gray-400">Escalated</p>
            <p class="text-2xl font-bold text-orange-600">{{ $stats['escalated'] }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <p class="text-sm text-gray-500 dark:text-gray-400">Reviewed Today</p>
            <p class="text-2xl font-bold text-green-600">{{ $stats['todayReviewed'] }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="flex flex-wrap gap-4 mb-6">
        <div class="flex rounded-lg overflow-hidden border dark:border-gray-700">
            <button wire:click="$set('filter', 'pending')" class="px-4 py-2 text-sm {{ $filter === 'pending' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300' }}">Pending</button>
            <button wire:click="$set('filter', 'assigned')" class="px-4 py-2 text-sm {{ $filter === 'assigned' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300' }}">Assigned to Me</button>
            <button wire:click="$set('filter', 'escalated')" class="px-4 py-2 text-sm {{ $filter === 'escalated' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300' }}">Escalated</button>
            <button wire:click="$set('filter', 'all')" class="px-4 py-2 text-sm {{ $filter === 'all' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300' }}">All</button>
        </div>
        <select wire:model.live="priority" class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 text-sm">
            <option value="">All Priorities</option>
            <option value="urgent">Urgent</option>
            <option value="high">High</option>
            <option value="medium">Medium</option>
            <option value="low">Low</option>
        </select>
    </div>

    <!-- Queue Items -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        @if($items->isEmpty())
            <div class="p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">All clear!</h3>
                <p class="text-gray-500 dark:text-gray-400">No items in the moderation queue.</p>
            </div>
        @else
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Content</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Reason</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Priority</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Reported</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($items as $item)
                        <tr class="{{ $item->is_escalated ? 'bg-orange-50 dark:bg-orange-900/10' : '' }}">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if($item->user)
                                        <img src="{{ $item->user->profile_photo_url }}" class="w-8 h-8 rounded-full mr-3">
                                    @endif
                                    <div>
                                        <p class="text-sm text-gray-900 dark:text-white truncate max-w-xs">
                                            {{ Str::limit($item->reportable?->content ?? 'Content unavailable', 50) }}
                                        </p>
                                        @if($item->user)
                                            <p class="text-xs text-gray-500 dark:text-gray-400">by {{ $item->user->name }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                {{ class_basename($item->content_type) }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                {{ $item->reason }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex px-2 py-1 text-xs rounded-full
                                    {{ $item->priority === 'urgent' ? 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300' : '' }}
                                    {{ $item->priority === 'high' ? 'bg-orange-100 text-orange-700 dark:bg-orange-900 dark:text-orange-300' : '' }}
                                    {{ $item->priority === 'medium' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300' : '' }}
                                    {{ $item->priority === 'low' ? 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300' : '' }}
                                ">
                                    {{ ucfirst($item->priority) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                {{ $item->created_at->diffForHumans() }}
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <button wire:click="openActionModal({{ $item->id }})" class="text-indigo-600 hover:text-indigo-800 text-sm">Review</button>
                                @if(!$item->assigned_to)
                                    <button wire:click="assignToMe({{ $item->id }})" class="text-gray-600 hover:text-gray-800 text-sm">Assign</button>
                                @endif
                                @if(!$item->is_escalated)
                                    <button wire:click="escalate({{ $item->id }})" class="text-orange-600 hover:text-orange-800 text-sm">Escalate</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="px-6 py-4">
                {{ $items->links() }}
            </div>
        @endif
    </div>

    <!-- Action Modal -->
    @if($showActionModal && $activeItem)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/50" wire:click="closeActionModal"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full max-h-[80vh] overflow-y-auto p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Review Content</h3>

                <!-- Content Preview -->
                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 mb-4">
                    @if($activeItem->user)
                        <div class="flex items-center space-x-3 mb-3">
                            <img src="{{ $activeItem->user->profile_photo_url }}" class="w-10 h-10 rounded-full">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $activeItem->user->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $activeItem->created_at->format('M d, Y g:i A') }}</p>
                            </div>
                        </div>
                    @endif
                    <p class="text-gray-700 dark:text-gray-300">{{ $activeItem->reportable?->content ?? 'Content unavailable' }}</p>
                </div>

                <!-- Report Info -->
                <div class="mb-4 text-sm">
                    <p class="text-gray-500 dark:text-gray-400"><strong>Reported for:</strong> {{ $activeItem->reason }}</p>
                    @if($activeItem->reporter)
                        <p class="text-gray-500 dark:text-gray-400"><strong>Reported by:</strong> {{ $activeItem->reporter->name }}</p>
                    @endif
                </div>

                <!-- Action Form -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Action</label>
                        <div class="grid grid-cols-2 md:grid-cols-5 gap-2">
                            @foreach(['approve' => 'Approve', 'remove' => 'Remove', 'warn' => 'Warn', 'ban' => 'Ban', 'dismiss' => 'Dismiss'] as $value => $label)
                                <label class="cursor-pointer">
                                    <input type="radio" wire:model="actionType" value="{{ $value }}" class="sr-only peer">
                                    <div class="text-center py-2 px-3 border rounded-lg peer-checked:border-indigo-500 peer-checked:bg-indigo-50 dark:peer-checked:bg-indigo-900/20 text-sm
                                        {{ $value === 'ban' ? 'peer-checked:border-red-500 peer-checked:bg-red-50 dark:peer-checked:bg-red-900/20' : '' }}
                                        {{ $value === 'approve' ? 'peer-checked:border-green-500 peer-checked:bg-green-50 dark:peer-checked:bg-green-900/20' : '' }}
                                    ">
                                        {{ $label }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notes</label>
                        <textarea wire:model="actionNotes" rows="3" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700" placeholder="Add notes about your decision..."></textarea>
                    </div>

                    @if($actionType === 'warn')
                        <div>
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" wire:model="sendWarning" class="rounded">
                                <span class="text-sm text-gray-700 dark:text-gray-300">Send warning notification to user</span>
                            </label>
                            @if($sendWarning)
                                <textarea wire:model="warningMessage" rows="2" class="mt-2 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700" placeholder="Warning message..."></textarea>
                            @endif
                        </div>
                    @endif

                    <div class="flex space-x-3">
                        <button wire:click="closeActionModal" class="flex-1 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg">Cancel</button>
                        <button wire:click="takeAction" class="flex-1 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700" @if(!$actionType) disabled @endif>
                            Confirm Action
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
