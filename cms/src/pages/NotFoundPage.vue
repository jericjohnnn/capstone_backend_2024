<template>
  <div class="container mx-auto text-center">
    <h1 class="text-5xl font-bold text-red-600">404</h1>
    <h2 class="text-3xl font-semibold">Page Not Found</h2>
    <p class="mt-4 text-lg">Oops! The page you are looking for does not exist.</p>
    <router-link to="/" class="mt-6 inline-block bg-blue-500 text-white py-2 px-4 rounded">
      Go Back to Home
    </router-link>
  </div>
</template>

<script setup>
// No additional logic is needed for this simple page
</script>

<style scoped>
.container {
  height: calc(100vh - 100px); /* Adjust height to fill the screen */
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
}
</style>
