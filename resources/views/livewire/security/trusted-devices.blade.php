<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Trusted Devices</h3>
        @if($devices->isNotEmpty())
            <button wire:click="removeAllDevices" wire:confirm="Remove all trusted devices?" class="text-sm text-red-600 hover:text-red-800">
                Remove All
            </button>
        @endif
    </div>

    @if($devices->isEmpty())
        <p class="text-gray-500 dark:text-gray-400 text-sm">No trusted devices.</p>
    @else
        <div class="space-y-3">
            @foreach($devices as $device)
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            @if($device->device_type === 'mobile')
                                <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                            @else
                                <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            @endif
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white text-sm">{{ $device->device_name ?? 'Unknown Device' }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $device->browser }} &bull; Last used {{ $device->last_used_at?->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                    <button wire:click="removeDevice({{ $device->id }})" class="text-red-600 hover:text-red-800 text-sm">
                        Remove
                    </button>
                </div>
            @endforeach
        </div>
    @endif
</div>
