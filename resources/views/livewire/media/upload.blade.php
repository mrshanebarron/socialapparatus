<div>
    @if ($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Upload Media</h3>
                            <button wire:click="closeModal" class="text-gray-400 hover:text-gray-500">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- Upload Zone -->
                        <div class="mb-4">
                            <label class="flex justify-center w-full h-32 px-4 transition bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-md appearance-none cursor-pointer hover:border-gray-400 focus:outline-none">
                                <span class="flex flex-col items-center justify-center">
                                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    <span class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                        Click to upload or drag and drop
                                    </span>
                                    <span class="text-xs text-gray-400">
                                        JPG, PNG, GIF, MP4, MOV up to 50MB
                                    </span>
                                </span>
                                <input type="file" wire:model="files" multiple accept="image/*,video/*" class="hidden">
                            </label>
                            @error('files.*') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <!-- Upload Progress -->
                        <div wire:loading wire:target="files" class="mb-4">
                            <div class="flex items-center justify-center">
                                <svg class="animate-spin h-5 w-5 text-indigo-500 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span class="text-sm text-gray-500">Processing files...</span>
                            </div>
                        </div>

                        <!-- Selected Files -->
                        @if (count($uploadedFiles) > 0)
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Selected Files ({{ count($uploadedFiles) }})</h4>
                                <div class="space-y-2 max-h-40 overflow-y-auto">
                                    @foreach ($uploadedFiles as $index => $file)
                                        <div class="flex items-center justify-between bg-gray-50 dark:bg-gray-700 p-2 rounded">
                                            <div class="flex items-center min-w-0">
                                                @if ($file['preview'])
                                                    <img src="{{ $file['preview'] }}" class="w-10 h-10 object-cover rounded mr-2">
                                                @else
                                                    <div class="w-10 h-10 bg-gray-200 dark:bg-gray-600 rounded mr-2 flex items-center justify-center">
                                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                        </svg>
                                                    </div>
                                                @endif
                                                <div class="min-w-0">
                                                    <p class="text-sm text-gray-900 dark:text-gray-100 truncate">{{ $file['name'] }}</p>
                                                    <p class="text-xs text-gray-500">{{ number_format($file['size'] / 1024 / 1024, 2) }} MB</p>
                                                </div>
                                            </div>
                                            <button wire:click="removeFile({{ $index }})" class="text-red-500 hover:text-red-700 ml-2">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Album Selection -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Album (optional)</label>
                            <select wire:model="albumId" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">No album</option>
                                @foreach ($albums as $album)
                                    <option value="{{ $album->id }}">{{ $album->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Privacy Selection -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Privacy</label>
                            <select wire:model="privacy" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="public">Public</option>
                                <option value="friends">Friends Only</option>
                                <option value="private">Only Me</option>
                            </select>
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button
                            wire:click="upload"
                            wire:loading.attr="disabled"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50"
                            {{ count($uploadedFiles) === 0 ? 'disabled' : '' }}
                        >
                            <span wire:loading.remove wire:target="upload">Upload {{ count($uploadedFiles) }} {{ Str::plural('file', count($uploadedFiles)) }}</span>
                            <span wire:loading wire:target="upload">Uploading...</span>
                        </button>
                        <button
                            wire:click="closeModal"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm"
                        >
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
