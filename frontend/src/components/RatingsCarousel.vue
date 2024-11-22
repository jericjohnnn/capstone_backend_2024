<template>
  <div class="w-full relative">
    <div class="relative flex items-center justify-center overflow-hidden">
      <!-- Carousel Container -->
      <div
        class="flex w-full transition-transform duration-300 ease-in-out"
        :style="{ transform: `translateX(-${currentIndex * 100}%)` }"
      >
        <!-- Individual Rating Cards -->
        <div
          v-for="(rating, index) in ratingComments"
          :key="rating.id"
          class="flex-shrink-0 w-full px-4"
          :class="{
            'scale-100': index === currentIndex,
            'scale-75': index === currentIndex - 1 || index === currentIndex + 1,
            'opacity-50': index !== currentIndex,
          }"
        >
          <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg border border-gray-200 transition-shadow">
            <!-- Card Content -->
            <div class="flex">
              <div class="flex flex-col w-full bg-white rounded-xl">
                <div class="flex-auto p-4 md:p-6">
                  <p class="text-base italic md:text-lg text-gray-800">
                    "{{ rating.comment }}"
                  </p>
                </div>

                <div class="p-4 bg-gray-100 rounded-b-xl md:px-7">
                  <div class="flex items-center gap-x-3">
                    <div class="shrink-0">
                      <img
                        class="size-8 sm:h-[2.875rem] sm:w-[2.875rem] rounded-full"
                        :src="rating.student.profile_image || defaultProfileImage"
                        alt="Avatar"
                      />
                    </div>

                    <div class="grow">
                      <p class="text-sm sm:text-base font-semibold text-gray-800">
                        {{ rating.student.first_name }}
                        {{ rating.student.last_name }}
                      </p>
                      <StarRating :rating="rating.rate" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Navigation Buttons -->
      <button
        v-if="ratingComments.length > 1"
        class="absolute left-0 p-2 bg-gray-200 rounded-full hover:bg-gray-300 transition-colors"
        @click="prev"
      >
        ◀
      </button>
      <button
        v-if="ratingComments.length > 1"
        class="absolute right-0 p-2 bg-gray-200 rounded-full hover:bg-gray-300 transition-colors"
        @click="next"
      >
        ▶
      </button>
    </div>
  </div>
</template>

<script setup>
import StarRating from './StarRating.vue'
import { ref } from 'vue'

const defaultProfileImage =
  'data:image/svg+xml;base64,' +
  btoa(`
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
    <circle cx="12" cy="12" r="11" fill="white" stroke="#E5E7EB" stroke-width="2"/>
    <circle cx="12" cy="8" r="3.5" fill="#9CA3AF"/>
    <path d="M12 12.5c-3 0-5.5 1.5-7 3.5 1.5 3 4 5 7 5s5.5-2 7-5c-1.5-2-4-3.5-7-3.5z" fill="#9CA3AF"/>
  </svg>`)

const props = defineProps({
  ratingComments: {
    type: Array,
    required: true,
  },
})

const currentIndex = ref(0)

const prev = () => {
  currentIndex.value =
    currentIndex.value === 0
      ? props.ratingComments.length - 1
      : currentIndex.value - 1
}

const next = () => {
  currentIndex.value = (currentIndex.value + 1) % props.ratingComments.length
}
</script>

<style scoped>
/* Smooth scaling animation */
.flex-shrink-0 {
  transition:
    transform 0.3s ease-in-out,
    opacity 0.3s ease-in-out;
}
</style>
