<template>
  <div
    class="flex flex-col items-center py-3 rounded-xl bg-blue-50 "
  >
    <div v-if="Object.keys(tutor).length === 0">
      <LoaderSpinner />
    </div>
    <div
      v-else
      class="flex  md:px-3 flex-col md:flex-row items-center gap-4 text-center md:text-left"
    >
      <img
        :src="tutor.profile_image || defaultProfileImage"
        alt="Tutor"
        class="w-20 h-20 rounded-full object-cover"
      />
      <div class="flex-1 flex flex-col items-center md:items-start ">
        <StarRating :rating="averageRatings" class="mb-1" />
        <h2 class="text-lg font-semibold mb-1">
          {{ tutor.first_name }} {{ tutor.last_name }}
        </h2>
        <div class="flex flex-wrap justify-center md:justify-start gap-2">
          <span
            v-for="subject in tutor.subjects"
            :key="subject.id"
            class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm"
          >
            {{ subject.name }}
          </span>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import LoaderSpinner from '@/components/Reusables/LoaderSpinner.vue'
import StarRating from '@/components/StarRating.vue'
import { computed } from 'vue'

const props = defineProps({
  tutor: {
    type: Object,
    required: true,
  },
})

const defaultProfileImage =
  'data:image/svg+xml;base64,' +
  btoa(`
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
    <circle cx="12" cy="12" r="11" fill="white" stroke="#E5E7EB" stroke-width="2"/>
    <circle cx="12" cy="8" r="3.5" fill="#9CA3AF"/>
    <path d="M12 12.5c-3 0-5.5 1.5-7 3.5 1.5 3 4 5 7 5s5.5-2 7-5c-1.5-2-4-3.5-7-3.5z" fill="#9CA3AF"/>
  </svg>`)

const averageRatings = computed(() => {
  if (!props.tutor?.ratings || props.tutor.ratings.length === 0) {
    return 0
  }
  const ratings = props.tutor.ratings.map(rating => rating.rate)
  const total = ratings.reduce((sum, rate) => sum + rate, 0)
  return total / ratings.length
})
</script>
