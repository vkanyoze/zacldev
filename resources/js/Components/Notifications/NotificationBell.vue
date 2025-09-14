<template>
  <div class="relative">
    <button 
      @click="toggleNotifications"
      class="p-2 rounded-full text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 relative"
      :class="{ 'text-indigo-600': hasUnread }"
    >
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
      </svg>
      <span v-if="unreadCount > 0" class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
    </button>

    <!-- Notification dropdown -->
    <div v-if="isOpen" class="origin-top-right absolute right-0 mt-2 w-80 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
      <div class="py-1">
        <div class="px-4 py-2 border-b border-gray-200 flex justify-between items-center">
          <h3 class="text-lg font-medium text-gray-900">Notifications</h3>
          <button 
            v-if="unreadCount > 0"
            @click="markAllAsRead"
            class="text-sm text-indigo-600 hover:text-indigo-900"
          >
            Mark all as read
          </button>
        </div>
        
        <div class="max-h-96 overflow-y-auto">
          <div v-if="notifications.length === 0" class="px-4 py-6 text-center text-gray-500">
            No new notifications
          </div>
          
          <template v-else>
            <div 
              v-for="notification in notifications" 
              :key="notification.id"
              class="px-4 py-3 hover:bg-gray-50 border-b border-gray-100"
              :class="{ 'bg-gray-50': !notification.read_at }"
            >
              <div class="flex items-start">
                <div class="flex-shrink-0 pt-0.5">
                  <span class="h-8 w-8 rounded-full flex items-center justify-center" :class="getNotificationIconClass(notification.data.type)">
                    <i :class="getNotificationIcon(notification.data.type)" class="text-white"></i>
                  </span>
                </div>
                <div class="ml-3 flex-1">
                  <p class="text-sm font-medium text-gray-900">{{ notification.data.title }}</p>
                  <p class="text-sm text-gray-500">{{ notification.data.message }}</p>
                  <p class="text-xs text-gray-400 mt-1">{{ formatTime(notification.created_at) }}</p>
                </div>
                <button 
                  @click="markAsRead(notification.id)"
                  class="ml-4 flex-shrink-0 text-gray-400 hover:text-gray-500"
                  title="Mark as read"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                  </svg>
                </button>
              </div>
            </div>
          </template>
        </div>
        
        <div v-if="notifications.length > 0" class="px-4 py-2 border-t border-gray-200 text-center">
          <a href="/admin/notifications" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">
            View all notifications
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'NotificationBell',
  
  props: {
    initialNotifications: {
      type: Array,
      default: () => []
    },
    unreadCount: {
      type: Number,
      default: 0
    }
  },
  
  data() {
    return {
      isOpen: false,
      notifications: [...this.initialNotifications],
    };
  },
  
  computed: {
    hasUnread() {
      return this.unreadCount > 0;
    }
  },
  
  mounted() {
    // Close dropdown when clicking outside
    document.addEventListener('click', this.handleClickOutside);
    
    // Listen for new notifications
    window.addEventListener('new-notification', this.handleNewNotification);
  },
  
  beforeUnmount() {
    document.removeEventListener('click', this.handleClickOutside);
    window.removeEventListener('new-notification', this.handleNewNotification);
  },
  
  methods: {
    toggleNotifications() {
      this.isOpen = !this.isOpen;
      if (this.isOpen) {
        this.fetchNotifications();
      }
    },
    
    handleClickOutside(event) {
      if (!this.$el.contains(event.target)) {
        this.isOpen = false;
      }
    },
    
    async fetchNotifications() {
      try {
        const response = await axios.get('/api/notifications');
        this.notifications = response.data;
      } catch (error) {
        console.error('Error fetching notifications:', error);
      }
    },
    
    async markAsRead(notificationId) {
      try {
        await axios.post(`/api/notifications/${notificationId}/read`);
        const notification = this.notifications.find(n => n.id === notificationId);
        if (notification) {
          notification.read_at = new Date().toISOString();
          this.$emit('notification-read');
        }
      } catch (error) {
        console.error('Error marking notification as read:', error);
      }
    },
    
    async markAllAsRead() {
      try {
        await axios.post('/api/notifications/mark-all-read');
        this.notifications = this.notifications.map(notification => ({
          ...notification,
          read_at: notification.read_at || new Date().toISOString()
        }));
        this.$emit('all-notifications-read');
      } catch (error) {
        console.error('Error marking all notifications as read:', error);
      }
    },
    
    handleNewNotification(event) {
      const notification = event.detail.notification;
      this.notifications.unshift({
        id: notification.id || Date.now(),
        data: notification,
        created_at: new Date().toISOString(),
        read_at: null
      });
      this.$emit('new-notification', notification);
    },
    
    getNotificationIcon(type) {
      const icons = {
        success: 'fas fa-check',
        error: 'fas fa-exclamation-circle',
        warning: 'fas fa-exclamation-triangle',
        info: 'fas fa-info-circle',
        default: 'fas fa-bell'
      };
      return icons[type] || icons.default;
    },
    
    getNotificationIconClass(type) {
      const colors = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        warning: 'bg-yellow-500',
        info: 'bg-blue-500',
        default: 'bg-gray-500'
      };
      return colors[type] || colors.default;
    },
    
    formatTime(dateString) {
      if (!dateString) return '';
      const date = new Date(dateString);
      return new Intl.DateTimeFormat('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      }).format(date);
    }
  }
};
</script>

<style scoped>
/* Add any custom styles here */
</style>
