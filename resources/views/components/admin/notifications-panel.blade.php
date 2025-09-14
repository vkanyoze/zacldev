@props([
    'notifications' => [],
    'unreadCount' => 0,
    'maxItems' => 5
])

<div x-data="{
    isOpen: false,
    unreadCount: {{ $unreadCount }},
    notifications: {{ json_encode($notifications) }},
    markAsRead(notificationId) {
        const notification = this.notifications.find(n => n.id === notificationId);
        if (notification && !notification.read_at) {
            fetch(`/admin/notifications/${notificationId}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
            }).then(response => {
                if (response.ok) {
                    notification.read_at = new Date().toISOString();
                    this.unreadCount = Math.max(0, this.unreadCount - 1);
                }
            });
        }
    },
    markAllAsRead() {
        if (this.unreadCount > 0) {
            fetch('{{ route("admin.notifications.markAllAsRead") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
            }).then(response => {
                if (response.ok) {
                    this.notifications.forEach(notification => {
                        if (!notification.read_at) {
                            notification.read_at = new Date().toISOString();
                        }
                    });
                    this.unreadCount = 0;
                }
            });
        }
    },
    getNotificationIcon(type) {
        const icons = {
            'info': 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
            'success': 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
            'warning': 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
            'error': 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
            'comment': 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z',
            'user': 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
            'payment': 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z',
        };
        return icons[type] || icons['info'];
    },
    getNotificationColor(type) {
        const colors = {
            'info': 'text-blue-500',
            'success': 'text-green-500',
            'warning': 'text-yellow-500',
            'error': 'text-red-500',
            'comment': 'text-purple-500',
            'user': 'text-indigo-500',
            'payment': 'text-emerald-500',
        };
        return colors[type] || 'text-gray-500';
    },
    formatTimeAgo(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diffInSeconds = Math.floor((now - date) / 1000);
        
        if (diffInSeconds < 60) return 'Just now';
        if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)}m ago`;
        if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)}h ago`;
        if (diffInSeconds < 604800) return `${Math.floor(diffInSeconds / 86400)}d ago`;
        return date.toLocaleDateString();
    }
}" 
x-init="
    // Listen for new notifications via Echo
    @if(config('broadcasting.default') !== 'null')
    window.Echo.private('App.Models.User.{{ auth()->id() }}')
        .notification((notification) => {
            // Add new notification to the top of the list
            this.notifications.unshift({
                id: notification.id,
                type: notification.type,
                title: notification.title,
                message: notification.message,
                read_at: null,
                created_at: new Date().toISOString(),
                data: notification.data
            });
            
            // Increment unread count
            this.unreadCount++;
            
            // Show browser notification if not focused
            if (document.hidden && Notification.permission === 'granted') {
                this.showBrowserNotification(notification);
            }
        });
    @endif
    
    // Request notification permission on component init
    if (Notification.permission !== 'denied') {
        Notification.requestPermission();
    }
">
    <div class="relative">
        <button @click="isOpen = !isOpen" class="p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 relative">
            <span class="sr-only">View notifications</span>
            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <template x-if="unreadCount > 0">
                <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
            </template>
        </button>

        <!-- Notification dropdown -->
        <div 
            x-show="isOpen" 
            @click.away="isOpen = false"
            x-transition:enter="transition ease-out duration-100 transform"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75 transform"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="origin-top-right absolute right-0 mt-2 w-80 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-200 focus:outline-none z-50"
            role="menu" 
            aria-orientation="vertical" 
            aria-labelledby="options-menu"
        >
            <div class="px-4 py-3 flex items-center justify-between border-b border-gray-200">
                <h3 class="text-sm font-medium text-gray-900">Notifications</h3>
                <div class="flex items-center">
                    <button 
                        @click="markAllAsRead" 
                        :disabled="unreadCount === 0"
                        :class="{'text-gray-400 cursor-not-allowed': unreadCount === 0, 'text-blue-600 hover:text-blue-800': unreadCount > 0}"
                        class="text-xs font-medium focus:outline-none"
                    >
                        Mark all as read
                    </button>
                    <a 
                        href="{{ route('admin.notifications.index') }}" 
                        class="ml-4 text-xs font-medium text-blue-600 hover:text-blue-800 focus:outline-none"
                    >
                        View all
                    </a>
                </div>
            </div>
            
            <div class="py-1 max-h-96 overflow-y-auto">
                <template x-if="notifications.length === 0">
                    <div class="px-4 py-6 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No notifications</h3>
                        <p class="mt-1 text-sm text-gray-500">You don't have any notifications yet.</p>
                    </div>
                </template>
                
                <template x-for="notification in notifications.slice(0, {{ $maxItems }})" :key="notification.id">
                    <a 
                        href="#" 
                        @click.prevent="
                            markAsRead(notification.id);
                            // Handle notification click (e.g., navigate to the related resource)
                            if (notification.data && notification.data.url) {
                                window.location.href = notification.data.url;
                            }
                        "
                        class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100"
                        :class="{'bg-blue-50': !notification.read_at}"
                    >
                        <div class="flex items-start">
                            <div class="flex-shrink-0 pt-0.5">
                                <div class="h-8 w-8 rounded-full flex items-center justify-center" :class="getNotificationColor(notification.type).replace('text', 'bg') + ' bg-opacity-20'">
                                    <svg class="h-5 w-5" :class="getNotificationColor(notification.type)" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" x-html="getNotificationIcon(notification.type)" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3 w-0 flex-1">
                                <p class="text-sm font-medium text-gray-900" x-text="notification.title"></p>
                                <p class="mt-1 text-sm text-gray-500" x-text="notification.message"></p>
                                <div class="mt-1 text-xs text-gray-400 flex items-center">
                                    <span x-text="formatTimeAgo(notification.created_at)"></span>
                                    <template x-if="!notification.read_at">
                                        <span class="ml-2 inline-block h-2 w-2 rounded-full bg-blue-500"></span>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </a>
                </template>
            </div>
            
            <div class="px-4 py-2 bg-gray-50 text-center border-t border-gray-200">
                <a href="{{ route('admin.notifications.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">View all notifications</a>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Custom scrollbar for notification dropdown */
    .notification-scroll::-webkit-scrollbar {
        width: 6px;
    }
    .notification-scroll::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    .notification-scroll::-webkit-scrollbar-thumb {
        background: #cbd5e0;
        border-radius: 10px;
    }
    .notification-scroll::-webkit-scrollbar-thumb:hover {
        background: #a0aec0;
    }
</style>
@endpush

@push('scripts')
<script>
    // Function to show browser notification
    function showBrowserNotification(notification) {
        const options = {
            body: notification.message,
            icon: '/images/notification-icon.png', // Path to your notification icon
            badge: '/images/notification-badge.png', // Path to your badge icon
            data: {
                url: notification.data?.url || window.location.href
            }
        };
        
        if (Notification.permission === 'granted') {
            const notification = new Notification(notification.title, options);
            
            notification.onclick = function(event) {
                event.preventDefault();
                window.focus();
                if (this.data && this.data.url) {
                    window.location.href = this.data.url;
                }
                this.close();
            };
        }
    }
    
    // Handle page visibility changes to show notifications when tab is not active
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            // Page is hidden, pause any auto-refreshing
        } else {
            // Page is visible again, refresh notifications
            // You might want to implement a refresh function here
        }
    });
</script>
@endpush
