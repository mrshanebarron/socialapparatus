<div>
    <div class="text-center">
        <h3 class="text-lg font-medium text-white">Database Configuration</h3>
        <p class="mt-2 text-sm text-gray-400">
            Choose your database type and configure the connection.
        </p>
    </div>

    <div class="mt-8 space-y-6">
        <!-- Database Type Selection -->
        <div>
            <label class="text-sm font-medium text-white">Database Type</label>
            <div class="mt-2 grid grid-cols-2 gap-4">
                <label class="relative flex cursor-pointer rounded-lg border p-4 focus:outline-none {{ $driver === 'sqlite' ? 'border-indigo-500 bg-indigo-900/20' : 'border-gray-600 bg-gray-700' }}">
                    <input type="radio" wire:model.live="driver" value="sqlite" class="sr-only">
                    <div class="flex flex-1 flex-col">
                        <span class="block text-sm font-medium text-white">SQLite</span>
                        <span class="mt-1 text-xs text-gray-400">Simple file-based database. Best for small to medium sites.</span>
                    </div>
                    @if($driver === 'sqlite')
                        <svg class="h-5 w-5 text-indigo-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    @endif
                </label>

                <label class="relative flex cursor-pointer rounded-lg border p-4 focus:outline-none {{ $driver === 'mysql' ? 'border-indigo-500 bg-indigo-900/20' : 'border-gray-600 bg-gray-700' }}">
                    <input type="radio" wire:model.live="driver" value="mysql" class="sr-only">
                    <div class="flex flex-1 flex-col">
                        <span class="block text-sm font-medium text-white">MySQL</span>
                        <span class="mt-1 text-xs text-gray-400">Full database server. Best for large sites.</span>
                    </div>
                    @if($driver === 'mysql')
                        <svg class="h-5 w-5 text-indigo-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    @endif
                </label>
            </div>
        </div>

        <!-- MySQL Configuration -->
        @if($driver === 'mysql')
            <div class="space-y-4 bg-gray-700 rounded-lg p-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="host" class="block text-sm font-medium text-gray-300">Host</label>
                        <input type="text" wire:model="host" id="host" class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="127.0.0.1">
                    </div>
                    <div>
                        <label for="port" class="block text-sm font-medium text-gray-300">Port</label>
                        <input type="text" wire:model="port" id="port" class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="3306">
                    </div>
                </div>
                <div>
                    <label for="database" class="block text-sm font-medium text-gray-300">Database Name</label>
                    <input type="text" wire:model="database" id="database" class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="socialapparatus">
                    <p class="mt-1 text-xs text-gray-400">Database will be created if it doesn't exist.</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-300">Username</label>
                        <input type="text" wire:model="username" id="username" class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="root">
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-300">Password</label>
                        <input type="password" wire:model="password" id="password" class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                </div>
            </div>
        @else
            <div class="bg-gray-700 rounded-lg p-4">
                <p class="text-sm text-gray-300">
                    SQLite will use a local file at <code class="text-indigo-400">database/database.sqlite</code>. No additional configuration needed.
                </p>
            </div>
        @endif

        <!-- Test Connection Result -->
        @if($tested)
            <div class="rounded-lg p-4 {{ $testPassed ? 'bg-green-900/30 border border-green-700' : 'bg-red-900/30 border border-red-700' }}">
                <div class="flex">
                    @if($testPassed)
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    @else
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    @endif
                    <p class="ml-3 text-sm {{ $testPassed ? 'text-green-300' : 'text-red-300' }}">
                        {{ $testMessage }}
                    </p>
                </div>
            </div>
        @endif
    </div>

    <div class="mt-8 flex justify-between">
        <a href="{{ route('install.requirements') }}" class="inline-flex items-center px-4 py-2 border border-gray-600 text-sm font-medium rounded-md text-gray-300 bg-gray-700 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 focus:ring-offset-gray-800">
            <svg class="mr-2 -ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back
        </a>

        <div class="flex space-x-3">
            <button wire:click="testConnection" type="button" class="inline-flex items-center px-4 py-2 border border-gray-600 text-sm font-medium rounded-md text-gray-300 bg-gray-700 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 focus:ring-offset-gray-800">
                Test Connection
            </button>

            <button wire:click="configureAndMigrate" type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 focus:ring-offset-gray-800 disabled:opacity-50 disabled:cursor-not-allowed" {{ $migrating ? 'disabled' : '' }}>
                @if($migrating)
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Running Migrations...
                @else
                    Continue
                    <svg class="ml-2 -mr-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                @endif
            </button>
        </div>
    </div>
</div>
