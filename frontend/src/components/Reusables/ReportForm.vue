<template>
  <div class="p-2 sm:p-4 w-full max-w-lg mx-auto">
    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700 mb-2">Report Reason</label>
      <select
        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-all duration-200 ease-in-out bg-white"
        v-model="reportReason"
      >
        <option value="" disabled selected>Select a reason...</option>
        <option value="Inappropriate Behavior">Inappropriate Behavior</option>
        <option value="No Show">No Show</option>
        <option value="Harassment">Harassment</option>
        <option value="Other">Other</option>
      </select>
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700 mb-2">Report Details</label>
      <textarea
        class="w-full p-3 h-36 sm:h-52 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-all duration-200 ease-in-out resize-none"
        placeholder="Please provide detailed information about your report..."
        v-model="reportMessage"
        maxlength="500"
      ></textarea>
      <p class="mt-1 text-xs text-gray-500 text-right">
        {{ reportMessage.length }}/500
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'

const reportReason = ref('')
const reportMessage = ref('')

const emit = defineEmits(['update:reportReason', 'update:reportMessage'])

watch(reportReason, value => {
  emit('update:reportReason', value)
})

watch(reportMessage, value => {
  emit('update:reportMessage', value)
})
</script>
