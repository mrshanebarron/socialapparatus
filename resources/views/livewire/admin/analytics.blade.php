<div class="max-w-7xl mx-auto py-8 px-4">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Analytics Dashboard</h1>
        <div class="flex space-x-2">
            @foreach(['7' => '7 Days', '30' => '30 Days', '90' => '90 Days'] as $days => $label)
                <button wire:click="setPeriod('{{ $days }}')" class="px-3 py-1 rounded {{ $period == $days ? 'bg-indigo-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                    {{ $label }}
                </button>
            @endforeach
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Users</h3>
            <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($metrics['total_users']) }}</p>
            <p class="mt-1 text-sm text-green-600">+{{ number_format($metrics['new_users']) }} new</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Users</h3>
            <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($metrics['active_users']) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Posts</h3>
            <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($metrics['total_posts']) }}</p>
            <p class="mt-1 text-sm text-green-600">+{{ number_format($metrics['new_posts']) }} new</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Groups</h3>
            <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($metrics['total_groups']) }}</p>
            <p class="mt-1 text-sm text-green-600">+{{ number_format($metrics['new_groups']) }} new</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Chart -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Activity Trends</h2>
            @if(count($metrics['daily'] ?? []) > 0)
                <div class="h-64 flex items-end justify-between space-x-1">
                    @foreach($metrics['daily'] as $day)
                        <div class="flex-1 flex flex-col items-center">
                            <div class="w-full bg-indigo-500 rounded-t" style="height: {{ max(1, ($day['users'] / max(1, max(array_column($metrics['daily'], 'users')))) * 100) }}%"></div>
                            <span class="text-xs text-gray-500 dark:text-gray-400 mt-2">{{ $day['date'] }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 dark:text-gray-400 text-center py-8">No data available for this period</p>
            @endif
        </div>

        <!-- Insights -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Insights</h2>
            @if($insights->isEmpty())
                <p class="text-gray-500 dark:text-gray-400 text-center py-8">No new insights</p>
            @else
                <div class="space-y-4">
                    @foreach($insights as $insight)
                        <div class="p-3 rounded-lg {{ $insight->priority === 'critical' ? 'bg-red-50 dark:bg-red-900/20' : ($insight->priority === 'high' ? 'bg-orange-50 dark:bg-orange-900/20' : 'bg-blue-50 dark:bg-blue-900/20') }}">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="font-medium text-gray-900 dark:text-white text-sm">{{ $insight->title }}</h3>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">{{ $insight->description }}</p>
                                </div>
                                <button wire:click="dismissInsight({{ $insight->id }})" class="text-gray-400 hover:text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
