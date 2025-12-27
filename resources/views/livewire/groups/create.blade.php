<div class="max-w-2xl mx-auto">
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Create a New Group</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Build a community around shared interests.</p>
        </div>

        <form wire:submit="createGroup" class="p-6 space-y-6">
            <!-- Avatar Preview -->
            <div class="flex items-center space-x-6">
                <div class="shrink-0">
                    @if($avatar)
                        <img class="h-20 w-20 rounded-lg object-cover" src="{{ $avatar->temporaryUrl() }}" alt="Preview">
                    @else
                        <div class="h-20 w-20 rounded-lg bg-indigo-600 flex items-center justify-center">
                            <svg class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    @endif
                </div>
                <label class="block">
                    <span class="sr-only">Choose group avatar</span>
                    <input wire:model="avatar" type="file" accept="image/*"
                           class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-900 dark:file:text-indigo-300">
                </label>
            </div>
            @error('avatar') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Group Name</label>
                <input wire:model="name" type="text" id="name"
                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                       placeholder="Enter group name">
                @error('name') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                <textarea wire:model="description" id="description" rows="3"
                          class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                          placeholder="What is this group about?"></textarea>
                @error('description') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <!-- Privacy -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Privacy</label>
                <div class="space-y-2">
                    <label class="flex items-start p-3 border rounded-lg cursor-pointer {{ $privacy === 'public' ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20' : 'border-gray-200 dark:border-gray-600' }}">
                        <input wire:model="privacy" type="radio" value="public" class="mt-1 text-indigo-600">
                        <div class="ml-3">
                            <span class="block text-sm font-medium text-gray-900 dark:text-white">Public</span>
                            <span class="block text-sm text-gray-500 dark:text-gray-400">Anyone can see and join this group.</span>
                        </div>
                    </label>
                    <label class="flex items-start p-3 border rounded-lg cursor-pointer {{ $privacy === 'private' ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20' : 'border-gray-200 dark:border-gray-600' }}">
                        <input wire:model="privacy" type="radio" value="private" class="mt-1 text-indigo-600">
                        <div class="ml-3">
                            <span class="block text-sm font-medium text-gray-900 dark:text-white">Private</span>
                            <span class="block text-sm text-gray-500 dark:text-gray-400">Anyone can find this group, but only members can see posts.</span>
                        </div>
                    </label>
                    <label class="flex items-start p-3 border rounded-lg cursor-pointer {{ $privacy === 'secret' ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20' : 'border-gray-200 dark:border-gray-600' }}">
                        <input wire:model="privacy" type="radio" value="secret" class="mt-1 text-indigo-600">
                        <div class="ml-3">
                            <span class="block text-sm font-medium text-gray-900 dark:text-white">Secret</span>
                            <span class="block text-sm text-gray-500 dark:text-gray-400">Only members can find and see this group.</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Join Approval -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Member Approval</label>
                <div class="flex space-x-4">
                    <label class="flex items-center">
                        <input wire:model="join_approval" type="radio" value="auto" class="text-indigo-600">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Auto-approve</span>
                    </label>
                    <label class="flex items-center">
                        <input wire:model="join_approval" type="radio" value="admin" class="text-indigo-600">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Require admin approval</span>
                    </label>
                </div>
            </div>

            <!-- Settings -->
            <div class="space-y-3">
                <label class="flex items-center">
                    <input wire:model="allow_member_posts" type="checkbox" class="rounded text-indigo-600">
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Allow members to create posts</span>
                </label>
                <label class="flex items-center">
                    <input wire:model="allow_member_invites" type="checkbox" class="rounded text-indigo-600">
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Allow members to invite others</span>
                </label>
            </div>

            <!-- Submit -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('groups.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-500">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Create Group
                </button>
            </div>
        </form>
    </div>
</div>
