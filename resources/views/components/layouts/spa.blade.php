@props([
    'showLeftSidebar' => true,
    'showRightSidebar' => true,
])

<x-app-layout>
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        @if($showLeftSidebar)
            <!-- Left Sidebar -->
            <x-layouts.left-sidebar />
        @endif

        <!-- Main Content -->
        <div class="{{ $showLeftSidebar && $showRightSidebar ? 'lg:col-span-6' : ($showLeftSidebar || $showRightSidebar ? 'lg:col-span-9' : 'lg:col-span-12') }} space-y-6">
            {{ $slot }}
        </div>

        @if($showRightSidebar)
            <!-- Right Sidebar -->
            <x-layouts.right-sidebar />
        @endif
    </div>
</x-app-layout>
