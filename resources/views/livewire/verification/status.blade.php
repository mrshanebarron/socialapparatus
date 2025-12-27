<div class="max-w-2xl mx-auto py-8 px-4">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Verification Status</h1>

    @if($badge)
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-6 mb-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="w-12 h-12 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h2 class="text-lg font-semibold text-green-800 dark:text-green-200">Verified</h2>
                    <p class="text-green-700 dark:text-green-300">{{ $badge->display_name ?? auth()->user()->name }}</p>
                    <p class="text-sm text-green-600 dark:text-green-400 mt-1">
                        {{ ucfirst($badge->badge_type) }} badge since {{ $badge->granted_at->format('F j, Y') }}
                    </p>
                </div>
            </div>
        </div>
    @elseif($request)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Request #{{ $request->id }}</h2>
                    <span class="px-3 py-1 rounded-full text-sm font-medium
                        {{ $request->status === 'approved' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                        {{ $request->status === 'rejected' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}
                        {{ $request->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : '' }}
                        {{ $request->status === 'under_review' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : '' }}
                    ">
                        {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                    </span>
                </div>

                <dl class="grid grid-cols-1 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Type</dt>
                        <dd class="mt-1 text-gray-900 dark:text-white">{{ ucfirst($request->type) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Name</dt>
                        <dd class="mt-1 text-gray-900 dark:text-white">{{ $request->full_legal_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Submitted</dt>
                        <dd class="mt-1 text-gray-900 dark:text-white">{{ $request->created_at->format('F j, Y g:i A') }}</dd>
                    </div>
                    @if($request->rejection_reason)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Rejection Reason</dt>
                            <dd class="mt-1 text-red-600 dark:text-red-400">{{ $request->rejection_reason }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            <!-- Timeline -->
            <div class="border-t border-gray-200 dark:border-gray-700 px-6 py-4">
                <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Timeline</h3>
                <ol class="space-y-3">
                    <li class="flex items-start">
                        <span class="w-2 h-2 mt-2 rounded-full bg-green-500 mr-3"></span>
                        <div>
                            <p class="text-sm text-gray-900 dark:text-white">Request submitted</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $request->created_at->diffForHumans() }}</p>
                        </div>
                    </li>
                    @if($request->status !== 'pending')
                        <li class="flex items-start">
                            <span class="w-2 h-2 mt-2 rounded-full {{ $request->status === 'approved' ? 'bg-green-500' : 'bg-red-500' }} mr-3"></span>
                            <div>
                                <p class="text-sm text-gray-900 dark:text-white">{{ ucfirst($request->status) }}</p>
                                @if($request->reviewed_at)
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $request->reviewed_at->diffForHumans() }}</p>
                                @endif
                            </div>
                        </li>
                    @endif
                </ol>
            </div>
        </div>
    @else
        <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-lg shadow">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No verification request</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Apply for verification to get a badge.</p>
            <a href="{{ route('verification.request') }}" class="mt-4 inline-block bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                Request Verification
            </a>
        </div>
    @endif
</div>
