<template>
  <div class="flex items-center">
    <template v-for="index in 5" :key="index">
      <!-- Full Star -->
      <svg
        v-if="index <= Math.floor(rating)"
        class="shrink-0 size-4 text-yellow-400 "
        xmlns="http://www.w3.org/2000/svg"
        width="16"
        height="16"
        fill="currentColor"
        viewBox="0 0 16 16"
      >
        <path
          d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"
        />
      </svg>

      <!-- Partial Star -->
      <svg
        v-else-if="index === Math.ceil(rating) && hasDecimal"
        class="shrink-0 size-4"
        xmlns="http://www.w3.org/2000/svg"
        width="16"
        height="16"
        fill="currentColor"
        viewBox="0 0 16 16"
      >
        <defs>
          <linearGradient :id="'gradient-'+index" x1="0%" y1="0%" x2="100%" y2="0%">
            <stop offset="0%" style="stop-color: #FBBF24; stop-opacity: 1" />
            <stop :offset="decimalPercentage + '%'" style="stop-color: #FBBF24; stop-opacity: 1" />
            <stop :offset="decimalPercentage + '%'" style="stop-color: #D1D5DB; stop-opacity: 1" />
            <stop offset="100%" style="stop-color: #D1D5DB; stop-opacity: 1" />
          </linearGradient>
        </defs>
        <path
          d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"
          :fill="'url(#gradient-'+index+')'"
        />
      </svg>

      <!-- Empty Star -->
      <svg
        v-else
        class="shrink-0 size-4 text-gray-300 "
        xmlns="http://www.w3.org/2000/svg"
        width="16"
        height="16"
        fill="currentColor"
        viewBox="0 0 16 16"
      >
        <path
          d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"
        />
      </svg>
    </template>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  rating: {
    type: [Number, null],
    required: true,
    validator: (value) => value >= 0 && value <= 5
  }
})

const hasDecimal = computed(() => {
  return props.rating % 1 !== 0
})

const decimalPercentage = computed(() => {
  return ((props.rating % 1) * 100).toFixed(0)
})
</script>
