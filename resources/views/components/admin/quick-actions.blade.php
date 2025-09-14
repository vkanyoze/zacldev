@props([
    'actions' => [
        [
            'title' => 'Add New User',
            'icon' => 'user-plus',
            'url' => '#',
            'color' => 'blue',
            'permission' => 'users.create'
        ],
        [
            'title' => 'Create Post',
            'icon' => 'document-text',
            'url' => '#',
            'color' => 'green',
            'permission' => 'posts.create'
        ],
        [
            'title' => 'View Reports',
            'icon' => 'chart-bar',
            'url' => '#',
            'color' => 'purple',
            'permission' => 'reports.view'
        ],
        [
            'title' => 'Settings',
            'icon' => 'cog',
            'url' => '#',
            'color' => 'gray',
            'permission' => 'settings.manage'
        ]
    ]
])

@php
    $colors = [
        'blue' => [
            'bg' => 'bg-blue-50',
            'icon' => 'text-blue-600',
            'hover' => 'hover:bg-blue-100',
            'ring' => 'ring-blue-600',
        ],
        'green' => [
            'bg' => 'bg-green-50',
            'icon' => 'text-green-600',
            'hover' => 'hover:bg-green-100',
            'ring' => 'ring-green-600',
        ],
        'purple' => [
            'bg' => 'bg-purple-50',
            'icon' => 'text-purple-600',
            'hover' => 'hover:bg-purple-100',
            'ring' => 'ring-purple-600',
        ],
        'yellow' => [
            'bg' => 'bg-yellow-50',
            'icon' => 'text-yellow-600',
            'hover' => 'hover:bg-yellow-100',
            'ring' => 'ring-yellow-600',
        ],
        'red' => [
            'bg' => 'bg-red-50',
            'icon' => 'text-red-600',
            'hover' => 'hover:bg-red-100',
            'ring' => 'ring-red-600',
        ],
        'gray' => [
            'bg' => 'bg-gray-50',
            'icon' => 'text-gray-600',
            'hover' => 'hover:bg-gray-100',
            'ring' => 'ring-gray-600',
        ],
    ];
    
    $icons = [
        'user-plus' => 'M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z',
        'document-text' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
        'chart-bar' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
        'cog' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z',
        'plus' => 'M12 4v16m8-8H4',
        'refresh' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15',
        'trash' => 'M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16',
        'pencil' => 'M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z',
        'mail' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
        'bell' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9',
    ];
@endphp

<div class="bg-white shadow-sm rounded-lg overflow-hidden">
    <div class="p-6
    ">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-medium text-gray-900">Quick Actions</h3>
            <button type="button" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                View all
            </button>
        </div>
        
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($actions as $action)
                @php
                    $color = $colors[$action['color'] ?? 'gray'];
                    $iconPath = $icons[$action['icon']] ?? '';
                @endphp
                
                <a href="{{ $action['url'] }}" 
                   class="relative flex items-center space-x-3 rounded-lg border border-gray-200 bg-white px-6 py-5 shadow-sm focus-within:ring-2 focus-within:ring-offset-2 {{ $color['hover'] }} focus:outline-none focus:ring-2 focus:ring-offset-2 {{ $color['ring'] }}">
                    <div class="flex-shrink-0 p-3 rounded-lg {{ $color['bg'] }} {{ $color['icon'] }}">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconPath }}" />
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-900">{{ $action['title'] }}</p>
                        <p class="truncate text-sm text-gray-500">Click to {{ strtolower($action['title']) }}</p>
                    </div>
                </a>
            @endforeach
        </div>
        
        <!-- Recently Used Actions -->
        <div class="mt-8">
            <h4 class="text-sm font-medium text-gray-500 mb-3">Recently Used</h4>
            <div class="flex space-x-4 overflow-x-auto pb-2">
                @foreach(array_slice($actions, 0, 3) as $recentAction)
                    @php
                        $color = $colors[$recentAction['color'] ?? 'gray'];
                        $iconPath = $icons[$recentAction['icon']] ?? '';
                    @endphp
                    <a href="{{ $recentAction['url'] }}" class="group flex flex-col items-center space-y-2 p-3 rounded-lg hover:bg-gray-50">
                        <div class="p-2 rounded-lg {{ $color['bg'] }} {{ $color['icon'] }} group-hover:bg-opacity-75">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconPath }}" />
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-gray-700 group-hover:text-gray-900">{{ $recentAction['title'] }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Track quick action clicks for "recently used" functionality
        const actionLinks = document.querySelectorAll('[data-track-action]');
        
        actionLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                const actionName = this.getAttribute('data-track-action');
                trackAction(actionName);
            });
        });
        
        function trackAction(actionName) {
            // Get existing actions from localStorage or initialize empty array
            let recentActions = JSON.parse(localStorage.getItem('recentActions') || '[]');
            
            // Remove if already exists (to prevent duplicates)
            recentActions = recentActions.filter(action => action !== actionName);
            
            // Add to beginning of array
            recentActions.unshift(actionName);
            
            // Keep only the last 5 actions
            if (recentActions.length > 5) {
                recentActions = recentActions.slice(0, 5);
            }
            
            // Save back to localStorage
            localStorage.setItem('recentActions', JSON.stringify(recentActions));
            
            // You might want to update the UI here if needed
            updateRecentActionsUI(recentActions);
        }
        
        function updateRecentActionsUI(actions) {
            // Implement UI update logic here
            console.log('Recent actions updated:', actions);
        }
        
        // Load recent actions on page load
        const recentActions = JSON.parse(localStorage.getItem('recentActions') || '[]');
        if (recentActions.length > 0) {
            updateRecentActionsUI(recentActions);
        }
    });
</script>
@endpush
