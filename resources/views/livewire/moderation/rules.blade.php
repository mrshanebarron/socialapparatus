<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Moderation Rules</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm">Automate content moderation with custom rules</p>
        </div>
        <button wire:click="openCreateModal" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
            + Add Rule
        </button>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        @if($rules->isEmpty())
            <div class="p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">No rules configured</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-4">Create your first moderation rule.</p>
                <button wire:click="openCreateModal" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    Add Rule
                </button>
            </div>
        @else
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Rule</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Pattern</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Action</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($rules as $rule)
                        <tr>
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $rule->name }}</p>
                                @if($rule->creator)
                                    <p class="text-xs text-gray-500 dark:text-gray-400">by {{ $rule->creator->name }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 capitalize">
                                {{ $rule->type }}
                            </td>
                            <td class="px-6 py-4">
                                <code class="text-xs bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">{{ Str::limit($rule->pattern, 30) }}</code>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex px-2 py-1 text-xs rounded-full capitalize
                                    {{ $rule->action === 'ban' ? 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300' : '' }}
                                    {{ $rule->action === 'delete' ? 'bg-orange-100 text-orange-700 dark:bg-orange-900 dark:text-orange-300' : '' }}
                                    {{ $rule->action === 'hide' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300' : '' }}
                                    {{ $rule->action === 'flag' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : '' }}
                                ">
                                    {{ $rule->action }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <button wire:click="toggleRule({{ $rule->id }})" class="relative inline-flex h-6 w-11 items-center rounded-full {{ $rule->is_active ? 'bg-indigo-600' : 'bg-gray-300 dark:bg-gray-600' }}">
                                    <span class="inline-block h-4 w-4 transform rounded-full bg-white transition {{ $rule->is_active ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                </button>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button wire:click="deleteRule({{ $rule->id }})" class="text-red-600 hover:text-red-800 text-sm" onclick="return confirm('Delete this rule?')">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <!-- Create Rule Modal -->
    @if($showCreateModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/50" wire:click="closeCreateModal"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Create Moderation Rule</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Rule Name</label>
                        <input type="text" wire:model="ruleName" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700" placeholder="e.g., Block spam links">
                        @error('ruleName') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Type</label>
                        <select wire:model="ruleType" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                            <option value="keyword">Keyword</option>
                            <option value="regex">Regex Pattern</option>
                            <option value="spam">Spam Detection</option>
                            <option value="link">Link Filter</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pattern</label>
                        <input type="text" wire:model="pattern" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700" placeholder="Enter keyword or regex pattern">
                        @error('pattern') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Action</label>
                            <select wire:model="action" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                                <option value="flag">Flag for review</option>
                                <option value="hide">Auto-hide</option>
                                <option value="delete">Auto-delete</option>
                                <option value="ban">Ban user</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Severity</label>
                            <select wire:model="severity" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                    </div>
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" wire:model="isActive" class="rounded">
                        <span class="text-sm text-gray-700 dark:text-gray-300">Enable rule immediately</span>
                    </label>
                    <div class="flex space-x-3">
                        <button wire:click="closeCreateModal" class="flex-1 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg">Cancel</button>
                        <button wire:click="createRule" class="flex-1 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Create</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
