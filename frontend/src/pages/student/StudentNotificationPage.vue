<template>
  <main class="min-h-screen bg-blue-100">
    <SideBar>
      <div class="container mx-auto py-8">
        <div class="mb-6">
          <h1 class="text-lg font-bold text-black">Notifications</h1>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
          <!-- Empty state when no notifications -->
          <div v-if="notifications.length === 0" class="flex flex-col items-center justify-center py-12">
            <div class="text-blue-600 mb-3">
              <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
              </svg>
            </div>
            <p class="text-gray-500 text-lg">No notifications yet</p>
          </div>

          <!-- Notifications list -->
          <div v-else class="divide-y divide-gray-200">
            <div
              v-for="notification in notifications"
              :key="notification.id"
              class="p-4 hover:bg-blue-50 transition-colors duration-150"
            >
              <div class="flex items-start space-x-4">
                <!-- Notification icon -->
                <div class="flex-shrink-0 pt-1">
                  <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                  </div>
                </div>

                <!-- Notification content -->
                <div class="flex-1 min-w-0">
                  <p class="text-sm font-medium text-gray-900">{{ notification.message }}</p>
                  <p class="text-xs text-gray-500 mt-1">
                    {{ new Date(notification.created_at).toLocaleDateString('en-US', {
                      year: 'numeric',
                      month: 'long',
                      day: 'numeric',
                      hour: '2-digit',
                      minute: '2-digit'
                    }) }}
                  </p>
                </div>

                <!-- Optional: Mark as read button or status -->
                <div class="flex-shrink-0">
                  <div class="h-2 w-2 rounded-full bg-blue-600"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </SideBar>

    <HelpButton />
  </main>
</template>

<style scoped>
.divide-y > div:last-child {
  border-bottom: none;
}
</style>

<script setup>
import { ref, onMounted } from 'vue';
import SideBar from '@/components/SideBar.vue';
import HelpButton from '@/components/HelpButton.vue';
import axiosInstance from '@/axiosInstance';

// Store notifications in a ref
const notifications = ref([]);

// Fetch notifications from the API on component mount
onMounted(async () => {
  try {
    const response = await axiosInstance.get('/api/user-notifications');
    console.log(response.data); // Log the full response

    // Sort notifications in descending order by created_at (latest first)
    notifications.value = response.data.notifications.sort((a, b) => {
      return new Date(b.created_at) - new Date(a.created_at);
    });
  } catch (error) {
    console.error('Error fetching notifications:', error);
  }
});
</script>
