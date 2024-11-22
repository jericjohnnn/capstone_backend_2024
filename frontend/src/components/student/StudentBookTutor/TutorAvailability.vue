<template>
  <div>
    <p
      v-if="!tutor.work_days || tutor.work_days.length === 0"
      class="text-gray-600"
    >
      No availability
    </p>
    <p v-else class="text-gray-600  text-sm pb-1">*
      {{ tutor.first_name }} {{ tutor.last_name }} is available
      {{ formatTo12Hour(tutor.work_days.start_time) }} -
      {{ formatTo12Hour(tutor.work_days.end_time) }}
    </p>
  </div>
</template>

<script setup>
import { formatTo12Hour } from '@/utils/dateTime.js'

defineProps({
  tutor: Object,
})
</script>
