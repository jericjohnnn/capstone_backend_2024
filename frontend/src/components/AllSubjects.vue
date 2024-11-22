<template>
  <div class="relative flex items-center">
    <!-- Left Arrow -->
    <div
      ref="scrollContainer"
      class=" flex gap-3 overflow-x-auto px-10 pt-2 scroll-smooth no-scrollbar "
    >
    <button
      @click="scrollLeft"
      class="absolute left-0 z-10 bg-white shadow-sm rounded-md p-1 hover:bg-gray-50 transition-colors duration-200"
    >
      <svg
        class="w-6 h-6 text-gray-600"
        fill="none"
        stroke="currentColor"
        viewBox="0 0 24 24"
      >
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
      </svg>
    </button>

    <!-- Scrollable Container -->

      <button
        v-for="subject in subjects"
        :key="subject"
        class="px-5 py-1 text-sm rounded-md text-gray-500 bg-white border shadow-sm hover:bg-gray-50 whitespace-nowrap transition-colors duration-200"
        :class="{ 'bg-blue-100 border-blue-200': selectedSubject === subject }"
        @click="selectSubject(subject)"
      >
        {{ subject }}
      </button>

    <!-- Right Arrow -->
    <button
      @click="scrollRight"
      class=" absolute right-0 z-10 bg-white shadow-sm rounded-md p-1 hover:bg-gray-50 transition-colors duration-200"
    >
      <svg
        class="w-6 h-6 text-gray-600"
        fill="none"
        stroke="currentColor"
        viewBox="0 0 24 24"
      >
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
      </svg>
    </button>
  </div>

  </div>
</template>

<script setup>
import { ref } from 'vue'

// Sample subjects - you can pass these as props instead
const subjects = [
  'English',
  'P.E',
  'Science',
  'Math',
  'History',
  'Geography',
  'Physics',
  'Chemistry',
  'Biology',
  'Literature'
]

const selectedSubject = ref(null)
const scrollContainer = ref(null)

const selectSubject = (subject) => {
  selectedSubject.value = subject
  // Emit the selected subject if needed
  // emit('update:modelValue', subject)
}

const scrollLeft = () => {
  if (scrollContainer.value) {
    scrollContainer.value.scrollBy({
      left: -200,
      behavior: 'smooth'
    })
  }
}

const scrollRight = () => {
  if (scrollContainer.value) {
    scrollContainer.value.scrollBy({
      left: 200,
      behavior: 'smooth'
    })
  }
}
</script>

<style scoped>
/* Hide scrollbar but keep functionality */
.no-scrollbar::-webkit-scrollbar {
  display: none;
}
.no-scrollbar {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
</style>
