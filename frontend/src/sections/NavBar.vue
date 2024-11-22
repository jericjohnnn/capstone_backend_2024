<template>
  <div class="relative">
    <header class="w-full bg-blue-600 py-3">
      <nav class="mx-auto px-4 flex items-center justify-between sm:justify-between">
        <!-- Brand -->
        <a 
          class="cursor-pointer flex items-center justify-center" 
          :class="{ 'flex-1': hideNavElements }"
          @click="router.push('/')"
        > 
          <img 
            src="@/assets/logo.svg" 
            alt="Logo"
            class="h-5" 
          />
        </a>

        <!-- Menu Items for Desktop -->
        <div v-if="!hideNavElements" class="hidden sm:flex sm:items-center">
          <a class="px-3 py-2 text-white hover:text-gray-200 cursor-pointer" @click="$emit('scrollTo', 'about')">About</a>
          <a class="px-3 py-2 text-white hover:text-gray-200 cursor-pointer" @click="$emit('scrollTo', 'team')">Our Team</a>
          <a class="px-3 py-2 text-white hover:text-gray-200 cursor-pointer" @click="$emit('scrollTo', 'contact')">Contact</a>
        </div>

        <!-- Right side buttons -->
        <div class="flex items-center space-x-2">
          <!-- Mobile menu button -->
          <button
            v-if="!hideNavElements"
            type="button"
            class="sm:hidden p-2 rounded-lg border border-white text-white hover:bg-blue-700"
            @click="isMenuOpen = !isMenuOpen"
          >
            <svg
              class="w-5 h-5"
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                v-if="!isMenuOpen"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16"
              />
              <path
                v-else
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M6 18L18 6M6 6l12 12"
              />
            </svg>
          </button>

          <!-- Desktop login/register buttons -->
          <div v-if="!hideNavElements" class="">
            <div v-if="isLoggedIn" class="hidden sm:block cursor-pointer">
              <a
                @click="userData.approval_status === 'Pending' || newTutor ? router.push({ name: 'TutorPendingApproval' }) : goToApp()"
                class="px-3 py-2 text-sm font-medium rounded-lg border border-white text-white hover:bg-blue-700"
              >
                {{ newTutor ? 'Back' : (user_type === 'Student' ? 'Go to home' : 'Go to profile') }}
              </a>
            </div>
            <div v-else class="hidden sm:flex space-x-2 cursor-pointer">
              <a
                @click="router.push('/login')"
                class="px-3 py-2 text-sm font-medium rounded-lg border border-white text-white hover:bg-blue-700 cursor-pointer"
              >
                Login
              </a>
              <a
                @click="router.push('/register')"
                class="px-3 py-2 text-sm font-medium rounded-lg bg-white text-blue-600 hover:bg-gray-100 cursor-pointer"
              >
                Sign up
              </a>
            </div>
          </div>
        </div>
      </nav>
    </header>

    <!-- Mobile Menu Dropdown -->
    <Transition
      v-if="!hideNavElements"
      enter-active-class="transition duration-200 ease-out"
      enter-from-class="transform -translate-y-1 opacity-0"
      enter-to-class="transform translate-y-0 opacity-100"
      leave-active-class="transition duration-150 ease-in"
      leave-from-class="transform translate-y-0 opacity-100"
      leave-to-class="transform -translate-y-1 opacity-0"
    >
      <div
        v-if="isMenuOpen"
        class="sm:hidden z-40 absolute w-full bg-white shadow-lg"
      >
        <div class="flex flex-col p-4">
          <a class="px-3 py-2 text-gray-800 hover:text-gray-600 cursor-pointer" @click="$emit('scrollTo', 'about')">About</a>
          <a class="px-3 py-2 text-gray-800 hover:text-gray-600 cursor-pointer" @click="$emit('scrollTo', 'team')">Our Team</a>
          <a class="px-3 py-2 text-gray-800 hover:text-gray-600 cursor-pointer" @click="$emit('scrollTo', 'contact')">Contact</a>
          <div class="border-t border-gray-200 my-2"></div>

          <div v-if="isLoggedIn" class="cursor-pointer">
            <a
              @click="userData.approval_status === 'Pending' || newTutor ? router.push({ name: 'TutorPendingApproval' }) : goToApp()"
              class="px-3 py-2 text-gray-800 hover:text-gray-600"
            >
              {{ newTutor  ? 'Back' : (user_type === 'Student' ? 'Go to home' : 'Go to profile') }}
            </a>
          </div>
          <div v-else class="cursor-pointer flex flex-col">
            <a
              @click="router.push('/login')"
              class="px-3 py-2 text-gray-800 hover:text-gray-600 cursor-pointer"
            >
              Login
            </a>
            <a
              @click="router.push('/register')"
              class="px-3 py-2 text-gray-800 hover:text-gray-600 cursor-pointer"
            >
              Sign up
            </a>
          </div>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRouter, useRoute } from 'vue-router'

const router = useRouter()
const route = useRoute()
const hideNavElements = computed(() => {
  return (!isLoggedIn.value && (route.path === '/login' || route.path === '/register')) ||
         route.path === '/tutor/pending-approval'
})

const isMenuOpen = ref(false)

const userData = ref(JSON.parse(localStorage.getItem('user_data')))
const newTutor = ref(!!localStorage.getItem('newTutor') || false)
const isLoggedIn = ref(!!localStorage.getItem('app_auth_token'))
const user_type = ref(localStorage.getItem('user_type'))

const goToApp = () => {
  if (user_type.value === 'Student') {
    router.push({ name: 'StudentHome' })
  } else {
    router.push({ name: 'TutorProfile' })
  }
}
</script>