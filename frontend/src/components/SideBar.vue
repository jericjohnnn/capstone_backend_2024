<template>
  <div>
    <!-- Mobile Breadcrumb -->
    <div class="sticky top-0 inset-x-0 z-40 bg-white border-y px-4 sm:px-6 lg:px-8 lg:hidden">
      <div class="flex items-center py-2">
        <!-- Mobile Navigation Toggle -->
        <button
          type="button"
          class="size-8 flex justify-center items-center gap-x-2 border border-gray-200 text-gray-800 hover:text-gray-500 rounded-lg focus:outline-none focus:text-gray-500 disabled:opacity-50 disabled:pointer-events-none"
          @click="isSidebarOpen = !isSidebarOpen"
        >
          <span class="sr-only">Toggle Navigation</span>
          <svg
            class="shrink-0 size-4"
            xmlns="http://www.w3.org/2000/svg"
            width="24"
            height="24"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
          >
            <rect width="18" height="18" x="3" y="3" rx="2" />
            <path d="M15 3v18" />
            <path d="m8 9 3 3-3 3" />
          </svg>
        </button>

        <ol class="ms-3 flex items-center whitespace-nowrap">
          <li class="flex font-bold justify-center items-center text-sm text-gray-800">
            TUDLO
          </li>
        </ol>
      </div>
    </div>

    <!-- Sidebar -->
    <div
      :class="[
        'fixed inset-y-0 start-0 z-50 w-[260px] bg-blue-600 transition-transform duration-300 lg:translate-x-0 lg:block',
        isSidebarOpen ? 'translate-x-0' : '-translate-x-full'
      ]"
    >
      <div class="relative flex flex-col h-full">
        <!-- Logo Section -->
        <div class="px-8 pt-4">
          <a 
            @click="router.push('/')" 
            class="flex-none cursor-pointer flex py-5"
          >
            <img 
              src="@/assets/logo.svg" 
              alt="Logo"
              class="h-7" 
            />
          </a>
        </div>

        <!-- Navigation Content -->
        <div class="h-full overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
          <nav class="p-3 w-full flex flex-col h-full">
            <!-- Tutor Navigation -->
            <div v-if="userType === 'Tutor'" class="flex-1">
              <ul class="flex flex-col space-y-1">
                <li v-for="(item, index) in tutorNavItems" :key="index">
                  <router-link
                    :to="{ name: item.route }"
                    class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-white rounded-md hover:bg-white/10 focus:outline-none focus:bg-white/10 transition-colors duration-200"
                    :class="{ 'bg-white/10': $route.name === item.route }"
                  >
                    <component :is="item.icon" />
                    {{ item.label }}
                  </router-link>
                </li>
              </ul>
            </div>

            <!-- Student Navigation -->
            <div v-else class="flex-1">
              <ul class="flex flex-col space-y-1">
                <li v-for="(item, index) in studentNavItems" :key="index">
                  <router-link
                    :to="{ name: item.route }"
                    class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-white rounded-md hover:bg-white/10 focus:outline-none focus:bg-white/10 transition-colors duration-200"
                    :class="{ 'bg-white/10': $route.name === item.route }"
                  >
                    <component :is="item.icon" />
                    {{ item.label }}
                  </router-link>
                </li>
              </ul>
            </div>

            <!-- User Profile & Logout Section -->
            <div class="mt-auto">
              <hr class="my-4 border-white/20" />
              <div class="flex items-center gap-x-4 px-2.5 py-3">
                <div class="rounded-full w-10 h-10 bg-white/10 overflow-hidden">
                  <img
                    :src="userProfileImage || defaultProfileImage"
                    alt="profile image"
                    class="w-full h-full object-cover rounded-full"
                  />
                </div>
                <div class="flex flex-col">
                  <span class="text-sm font-medium text-white">{{ userData?.first_name }} {{ userData?.last_name }}</span>
                  <span class="text-xs text-white/70">{{ userEmail }}</span>
                </div>
              </div>
              <button
                @click="logout"
                class="w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-white rounded-md hover:bg-white/10 transition-colors duration-200"
              >
                <LogoutIcon />
                Logout
              </button>
            </div>
          </nav>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="w-full md:min-h-screen px-4 sm:px-6 md:px-8 lg:ps-72">
      <slot></slot>
    </div>

    <!-- Backdrop -->
    <div
      v-if="isSidebarOpen"
      class="fixed inset-0 z-40 bg-black/50 lg:hidden"
      @click="isSidebarOpen = false"
    ></div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import axiosInstance from '@/axiosInstance'
import HomeIcon from '@/assets/HomeIcon.vue'
import NotificationIcon from '@/assets/NotificationIcon.vue'
import ProfileIcon from '@/assets/ProfileIcon.vue'
import RequestIcon from '@/assets/RequestIcon.vue'
import ScheduleIcon from '@/assets/ScheduleIcon.vue'
import LogoutIcon from '@/assets/LogoutIcon.vue'

// State
const isSidebarOpen = ref(false)
const router = useRouter()

// User data from localStorage
const userType = localStorage.getItem('user_type')
const userEmail = localStorage.getItem('user_email')
// const userFullName = localStorage.getItem('user_full_name')
const userData = JSON.parse(localStorage.getItem('user_data') || '{}')
const userProfileImage = userData?.profile_image

// Default profile image as base64 SVG
  const defaultProfileImage =
  'data:image/svg+xml;base64,' +
  btoa(`
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
    <circle cx="12" cy="12" r="11" fill="white" stroke="#E5E7EB" stroke-width="2"/>
    <circle cx="12" cy="8" r="3.5" fill="#9CA3AF"/>
    <path d="M12 12.5c-3 0-5.5 1.5-7 3.5 1.5 3 4 5 7 5s5.5-2 7-5c-1.5-2-4-3.5-7-3.5z" fill="#9CA3AF"/>
  </svg>`)

// Navigation items
const tutorNavItems = [
  { label: 'Profile', route: 'TutorProfile', icon: ProfileIcon },
  { label: 'Schedule', route: 'TutorSchedule', icon: ScheduleIcon },
  { label: 'Student requests', route: 'TutorRequests', icon: RequestIcon },
  { label: 'Notifications', route: 'TutorNotifications', icon: NotificationIcon },
]

const studentNavItems = [
  { label: 'Home', route: 'StudentHome', icon: HomeIcon },
  { label: 'Profile', route: 'StudentProfile', icon: ProfileIcon },
  { label: 'Schedule', route: 'StudentSchedule', icon: ScheduleIcon },
  { label: 'Your requests', route: 'StudentRequests', icon: RequestIcon },
  { label: 'Notifications', route: 'StudentNotifications', icon: NotificationIcon }
]

// Logout function
async function logout() {
  try {
    await axiosInstance.post('/api/logout')

    // const itemsToRemove = ['app_auth_token', 'user_type', 'user_email', 'user_data', 'user_full_name']
    // itemsToRemove.forEach(item => localStorage.removeItem(item))

    localStorage.clear();

    // Remove authorization header
    delete axiosInstance.defaults.headers['Authorization']

    // Redirect to login
    router.push({ name: 'Login' })
  } catch (error) {
    console.error('Logout failed:', error)
  }
}
</script>
