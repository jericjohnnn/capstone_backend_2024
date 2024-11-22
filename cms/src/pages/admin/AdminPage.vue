<template>
      <div class="flex justify-center items-center h-screen">
      <div class="flex flex-col">
        <div class="text-4xl font-bold mb-4">Welcome To the Admin Dashboard</div>
      <router-link 
            to="/admin/tutorApplication" 
            class="block py-2 rounded-md w-full text-center hover:bg-gray-200 hover:text-blue-500"
            active-class="bg-gray-300 font-bold"
          >
            Get Started
          </router-link>
      </div>
    </div>
</template>
<script setup>

</script>
