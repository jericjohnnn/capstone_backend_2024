<template>
  <ol class="flex items-center whitespace-nowrap space-x-2">
    <li v-for="(crumb, index) in breadcrumbs" :key="index" class="inline-flex items-center">
      <router-link
        v-if="index < breadcrumbs.length - 1"
        :to="crumb.route"
        class="flex items-center text-sm text-gray-500 hover:text-blue-600 focus:outline-none focus:text-blue-600 transition-colors duration-200"
      >
        {{ crumb.label }}
      </router-link>
      <span
        v-else
        class="inline-flex items-center text-sm font-semibold text-gray-800 truncate"
        aria-current="page"
      >
        {{ crumb.label }}
      </span>
      <svg
        v-if="index < breadcrumbs.length - 1"
        class="shrink-0 mx-2 size-4 text-gray-400"
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
        <path d="m9 18 6-6-6-6"></path>
      </svg>
    </li>
  </ol>
</template>

<script setup>

defineProps({
  breadcrumbs: {
    type: Array,
    required: true,
    default: () => []
  }
})
</script>

<style scoped>
/* Add custom styles here if needed */
</style>
