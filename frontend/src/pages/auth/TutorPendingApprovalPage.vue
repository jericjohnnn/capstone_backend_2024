<template>
  <NavBar />
  <div class="min-h-[calc(100vh-5rem)] flex items-center justify-center bg-gray-50">
    <div class="max-w-md w-full p-6 bg-white rounded-lg shadow-lg text-center">
      <div class="mb-4 size-20  w-full flex justify-center">
        <!-- You can add an icon here -->
        <img :src="pendingApprovalIcon" alt="pending approval">
      </div>

      <h2 class="text-2xl font-bold text-gray-900 mb-4">
        Registration Pending Approval
      </h2>
      
      <p class="text-gray-600 mb-8">
        Your registration is currently under review. Please wait for admin approval to access your account.
      </p>

      <div class="flex gap-4 justify-center">
        <button 
          @click="goHome" 
          class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors"
        >
          Home
        </button>
        
        <button 
          @click="logout" 
          class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors"
        >
          Logout
        </button>
      </div>
    </div>
  </div>
  <FooterSection />
</template>

<script setup>
import pendingApprovalIcon from '@/assets/approval.png'
import NavBar from '@/sections/NavBar.vue'
import FooterSection from '@/sections/FooterSection.vue'
import { useRouter } from 'vue-router'

const router = useRouter()

const goHome = () => {
  router.push('/')
}

const logout = () => {
  localStorage.clear()
  router.push('/login')
}
</script>

<style scoped>
/* Add styles here */
</style>
