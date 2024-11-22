<template>
  <div class="border-b border-gray-200 ">
    <nav class="flex gap-x-4 overflow-x-auto scrollbar-hide">
      <button
        class="px-1 py-4 text-sm font-medium transition-colors"
        :class="{
          'text-blue-600 border-b-2 border-blue-600': currentTab === 'pending',
          'text-gray-500 hover:text-blue-600': currentTab !== 'pending',
        }"
        @click="switchTab('pending')"
      >
        Pending
      </button>
      <button
        class="px-1 py-4 text-sm font-medium transition-colors"
        :class="{
          'text-blue-600 border-b-2 border-blue-600': currentTab === 'completed',
          'text-gray-500 hover:text-blue-600': currentTab !== 'completed',
        }"
        @click="switchTab('completed')"
      >
        Completed
      </button>
    </nav>
    <hr class="border-white" />
  </div>

  <div class="mt-6">
    <div class="w-full" v-show="currentTab === 'pending'">
      <slot name="pending"></slot>
    </div>
    <div class="w-full" v-show="currentTab === 'completed'">
      <slot name="completed"></slot>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter, useRoute } from 'vue-router'

const router = useRouter()
const route = useRoute()
const currentTab = ref(route.query.tab || 'pending')

const switchTab = tabName => {
  currentTab.value = tabName

  router.push({
    path: '/tutor/requests',
    query: {
      tab: tabName,
    },
  })
}
</script>
