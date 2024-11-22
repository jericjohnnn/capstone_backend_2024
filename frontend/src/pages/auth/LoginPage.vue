<template>
  <NavBar />
  <div
    class="min-h-[calc(100vh-5rem)] flex justify-center items-center bg-gray-50"
  >
    <NotificationToast
      :show="notification.show"
      :message="notification.message"
      :type="notification.type"
    />
    <div
      class="flex bg-white min-h-[calc(100vh-5rem)] w-full shadow-md md:rounded-lg flex-col justify-center items-center md:w-5/12 md:min-h-0 md:p-24"
    >
      <div class="sm:mx-auto sm:w-full sm:max-w-sm">
        <h2 class="text-center text-2xl font-bold text-gray-900">
          Sign in to your account
        </h2>
      </div>

      <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
        <form class="space-y-6" @submit.prevent="handleLogin">
          <div>
            <label for="email" class="block text-sm font-medium text-gray-900">
              Email address
            </label>
            <div class="mt-2">
              <input
                id="email"
                v-model="email"
                type="email"
                autocomplete="email"
                placeholder="Email"
                required
                class="block w-full rounded-lg px-3 py-2 text-gray-900 border border-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-blue-600 focus:border-blue-600"
              />
            </div>
          </div>

          <div>
            <div class="flex items-center justify-between">
              <label
                for="password"
                class="block text-sm font-medium text-gray-900"
              >
                Password
              </label>
              <div class="text-sm">
                <a
                  href="#"
                  class="font-semibold text-blue-600 hover:text-blue-500"
                >
                  Forgot password?
                </a>
              </div>
            </div>
            <div class="mt-2">
              <input
                id="password"
                v-model="password"
                type="password"
                autocomplete="current-password"
                placeholder="Password"
                required
                class="block w-full rounded-lg px-3 py-2 text-gray-900 border border-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-blue-600 focus:border-blue-600"
              />
            </div>
          </div>

          <div class="space-y-4">
            <button
              type="submit"
              :disabled="isLoading"
              class="flex w-full justify-center rounded-lg bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus:ring-2 focus:ring-offset-2 focus:ring-blue-600"
            >
              <span v-if="isLoading"><LoaderSpinner /></span>
              <span v-else>Sign in</span>
            </button>

            <p class="text-center text-sm text-gray-500">
              Not yet registered?
              <a
                href="/register"
                class="font-semibold text-blue-600 hover:text-blue-500"
              >
                Sign up
              </a>
            </p>
          </div>
        </form>
      </div>
    </div>
  </div>
  <FooterSection />
</template>

<script setup>
import FooterSection from '@/sections/FooterSection.vue'
import NavBar from '@/sections/NavBar.vue'
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import axiosInstance from '@/axiosInstance'
import NotificationToast from '@/components/Reusables/NotificationToast.vue'
import { useNotification } from '@/composables/useNotification'
import LoaderSpinner from '@/components/Reusables/LoaderSpinner.vue'

// State for form inputs and notification
const email = ref('')
const password = ref('')
const router = useRouter()
const { notification, showNotification } = useNotification()
const isLoading = ref(false)

// Login handler function
const handleLogin = async () => {
  isLoading.value = true

  try {
    const response = await axiosInstance.post('api/login', {
      email: email.value,
      password: password.value,
    })

    const { user_email, user_full_name, user_type, user_data, token } =
      response.data

    // Store token in localStorage
    localStorage.setItem('app_auth_token', token)
    localStorage.setItem('user_type', user_type)
    localStorage.setItem('user_email', user_email)
    localStorage.setItem('user_full_name', user_full_name)
    localStorage.setItem('user_data', JSON.stringify(user_data))

    showNotification('Login successful!', 'success')

    // Add setTimeout before routing
    setTimeout(() => {
      // Redirect based on user type
      if (user_type === 'Tutor') {
        if (user_data.approval_status === 'Pending') {
          router.push('/tutor/pending-approval')
        } else {
          router.push('/tutor/profile')
        }
      }
      if (user_type === 'Student') {
        router.push('/student/home')
      }
    }, 1500) // 1.5 seconds delay to show the success notification
  } catch (error) {
    console.error('Login error:', error)
    showNotification(
      error.response?.data?.message || 'Invalid email or password',
      'error',
    )
  } finally {
    isLoading.value = false
  }
}
</script>

<style scoped>
/* Basic styling */
.login-container {
  max-width: 400px;
  margin: 100px auto;
  padding: 20px;
  border: 1px solid #ccc;
  border-radius: 8px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  text-align: center;
}

.input-group {
  margin-bottom: 15px;
  text-align: left;
}

label {
  display: block;
  margin-bottom: 5px;
  font-weight: 500;
}

input {
  width: 100%;
  padding: 8px;
  margin-bottom: 5px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

button {
  width: 100%;
  padding: 10px;

  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.error {
  color: red;
  margin-top: 10px;
}
</style>
