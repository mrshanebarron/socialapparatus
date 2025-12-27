<div>
    <div class="text-center">
        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-900/50">
            <svg class="h-10 w-10 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        <h3 class="mt-4 text-lg font-medium text-white">Installation Complete!</h3>
        <p class="mt-2 text-sm text-gray-400">
            Your SocialApparatus community is ready to go.
        </p>
    </div>

    <div class="mt-8 space-y-4">
        <div class="bg-gray-700 rounded-lg p-4">
            <h4 class="text-sm font-medium text-white">What's next?</h4>
            <ul class="mt-2 text-sm text-gray-300 list-disc list-inside space-y-1">
                <li>Log in with your admin account</li>
                <li>Customize your site settings</li>
                <li>Invite members to your community</li>
                <li>Start creating content!</li>
            </ul>
        </div>

        <div class="bg-indigo-900/30 border border-indigo-700 rounded-lg p-4">
            <h4 class="text-sm font-medium text-indigo-300">Security Reminder</h4>
            <p class="mt-1 text-sm text-indigo-200">
                For production use, make sure to set <code class="bg-indigo-900 px-1 rounded">APP_DEBUG=false</code> in your .env file and configure proper HTTPS.
            </p>
        </div>
    </div>

    <div class="mt-8 flex justify-center">
        <a href="{{ route('login') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 focus:ring-offset-gray-800">
            Go to Login
            <svg class="ml-2 -mr-1 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
    </div>
</div>
