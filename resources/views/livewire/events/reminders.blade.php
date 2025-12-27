<div>
    <div class="flex items-center justify-between mb-4">
        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Reminders</h4>
        <button wire:click="$set('showModal', true)" class="text-sm text-indigo-600 hover:text-indigo-800">
            Add Reminder
        </button>
    </div>

    @if($reminders->isEmpty())
        <p class="text-sm text-gray-500 dark:text-gray-400">No reminders set for this event.</p>
    @else
        <div class="space-y-2">
            @foreach($reminders as $reminder)
                <div class="flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-700 rounded">
                    <div class="flex items-center space-x-2">
                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span class="text-sm text-gray-900 dark:text-white">
                            {{ str_replace('_', ' ', $reminder->remind_before) }} before
                        </span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">
                            ({{ ucfirst($reminder->notification_type) }})
                        </span>
                    </div>
                    <button wire:click="removeReminder({{ $reminder->id }})" class="text-red-500 hover:text-red-700">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Add Reminder Modal -->
    @if($showModal)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-sm w-full mx-4">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Add Reminder</h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Remind me</label>
                            <select wire:model="remindBefore" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="15_min">15 minutes before</option>
                                <option value="30_min">30 minutes before</option>
                                <option value="1_hour">1 hour before</option>
                                <option value="2_hours">2 hours before</option>
                                <option value="1_day">1 day before</option>
                                <option value="1_week">1 week before</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notification type</label>
                            <select wire:model="notificationType" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="notification">In-app notification</option>
                                <option value="email">Email</option>
                                <option value="both">Both</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <button wire:click="$set('showModal', false)" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-500">
                            Cancel
                        </button>
                        <button wire:click="addReminder" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
                            Add Reminder
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
