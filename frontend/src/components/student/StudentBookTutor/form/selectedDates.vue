<template>
  <div>
    <p class="text-base  ">Selected dates:</p>
    <div
      v-if="dates.length === 0"
      class=" flex justify-center items-center "
    >
      <p class="text-slate-400">No dates selected</p>
    </div>
    <div v-else class="flex flex-wrap gap-2 justify-center w-full py-2">
      <div v-for="date in dates" :key="date.id" class="">
        <div class="bg-blue-500 text-white text-sm px-3 py-2 grid grid-cols-[auto,1fr] gap-1 rounded-lg">

          <div class="font-semibold  text-sm">
            {{ formatDate(date.start) }}
          </div>
          <button @click="removeDate(date.id)" class="font-bold col-span-1 rounded-full bg-red-400">X</button>

          <div class="text-sm col-span-2">
            {{ formatTo12Hour(extractTimeFromDateTimeString(date.start)) }} to
            {{ formatTo12Hour(extractTimeFromDateTimeString(date.end)) }}
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import {
  formatDate,
  formatTo12Hour,
  extractTimeFromDateTimeString,
} from '@/utils/dateTime'

const props = defineProps({
  dates: {
    type: Array,
    default: () => [],
  },
})

const dates = computed(() => (props.dates ? props.dates : []))

const emit = defineEmits(['remove-date'])

const removeDate = id => {
  emit('remove-date', id)
}
</script>
