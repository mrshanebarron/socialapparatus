<div>
    <div class="text-center">
        <h3 class="text-lg font-medium text-white">Welcome to SocialApparatus</h3>
        <p class="mt-2 text-sm text-gray-400">
            Thank you for choosing SocialApparatus! This wizard will guide you through the installation process.
        </p>
    </div>

    <div class="mt-8 space-y-4">
        <div class="bg-gray-700 rounded-lg p-4">
            <h4 class="text-sm font-medium text-white">What you'll need:</h4>
            <ul class="mt-2 text-sm text-gray-300 list-disc list-inside space-y-1">
                <li>PHP 8.2 or higher</li>
                <li>SQLite or MySQL database</li>
                <li>Required PHP extensions (we'll check these)</li>
                <li>Admin account details</li>
            </ul>
        </div>

        <div class="bg-gray-700 rounded-lg p-4">
            <h4 class="text-sm font-medium text-white">Installation steps:</h4>
            <ol class="mt-2 text-sm text-gray-300 list-decimal list-inside space-y-1">
                <li>Check system requirements</li>
                <li>Configure your database</li>
                <li>Create admin account</li>
                <li>Set up your site</li>
                <li>Start using SocialApparatus!</li>
            </ol>
        </div>
    </div>

    <div class="mt-8 flex justify-end">
        <a href="{{ route('install.requirements') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 focus:ring-offset-gray-800">
            Get Started
            <svg class="ml-2 -mr-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
    </div>
</div>
