<template>
  <div class="p-4 w-full max-w-lg mx-auto">
    <!-- Star Rating -->
    <div class="mb-4 text-center">
      <label class="block text-sm font-medium text-gray-700 mb-2">Rate your experience</label>
      <div class="flex items-center justify-center gap-1">
        <button 
          v-for="star in 5" 
          :key="star"
          @click="setRating(star)"
          class="focus:outline-none"
          type="button"
        >
          <svg 
            :class="star <= rating ? 'text-yellow-400' : 'text-gray-300'"
            class="w-8 h-8 cursor-pointer transition-colors duration-200 hover:text-yellow-400"
            fill="currentColor"
            viewBox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path 
              d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
            />
          </svg>
        </button>
      </div>
      <p class="mt-1 text-sm text-gray-500">
        {{ getRatingText() }}
      </p>
    </div>

    <!-- Review Details -->
    <div>
      <label class="block text-sm font-medium text-gray-700 mb-2">Review Details</label>
      <textarea
        class="w-full p-3 h-32 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-all duration-200 ease-in-out resize-none"
        placeholder="Share your experience with this tutor..."
        v-model="reviewMessage"
        maxlength="500"
      ></textarea>
      <p class="mt-1 text-sm text-gray-500 text-right">
        {{ reviewMessage.length }}/500
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'

const rating = ref(0)
const reviewMessage = ref('')

const emit = defineEmits(['update:rating', 'update:reviewMessage'])

const setRating = (value) => {
  rating.value = value
  emit('update:rating', value)
}

watch(reviewMessage, (newValue) => {
  emit('update:reviewMessage', newValue)
})

watch(rating, (newValue) => {
  emit('update:rating', newValue)
})

const getRatingText = () => {
  switch (rating.value) {
    case 1:
      return 'Poor'
    case 2:
      return 'Fair'
    case 3:
      return 'Good'
    case 4:
      return 'Very Good'
    case 5:
      return 'Excellent'
    default:
      return 'Click to rate'
  }
}
</script>
  