<template>
  <div class="login-container">
    <h1 class="text-3xl font-bold underline">CMS Login</h1>
    <form @submit.prevent="handleLogin">
      <div class="input-group">
        <label for="email">Email</label>
        <input
          type="email"
          id="email"
          v-model="email"
          placeholder="Enter your email"
          required
        />
      </div>

      <div class="input-group">
        <label for="password">Password</label>
        <input
          type="password"
          id="password"
          v-model="password"
          placeholder="Enter your password"
          required
        />
      </div>

      <button type="submit">Login</button>
    </form>

    <p v-if="errorMessage" class="error">{{ errorMessage }}</p>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import axiosInstance from '@/axiosInstance'

// State for form inputs and error handling
const email = ref('')
const password = ref('')
const errorMessage = ref('')

// Vue Router instance for navigation
const router = useRouter()

// Login handler function
const handleLogin = async () => {
  try {
    const response = await axiosInstance.post('api/admin/login', {
      email: email.value,
      password: password.value,
    })

    const { user_type, token } = response.data

    // Store token in localStorage
    localStorage.setItem('cms_auth_token', token)
    localStorage.setItem('user_type', user_type)

    router.push('/')

    // Redirect to home page or another protected route after successful login
  } catch (error) {
    // Handle error (e.g., incorrect credentials or API failure)
    errorMessage.value =
      error.response?.data?.message || 'An error occurred. Please try again.'
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
  font-weight: bold;
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
  background-color: #4caf50;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

button:hover {
  background-color: #45a049;
}

.error {
  color: red;
  margin-top: 10px;
}
</style>
