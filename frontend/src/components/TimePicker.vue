<template>
  <!-- <div class="outline grid grid-cols-2 gap-3 grid-flow-row py-2"></div> -->
  <div class=" grid grid-rows-3 gap-3 py-2 md:grid-rows-2">
    <div class="flex  items-center text-sm ">
      <label class="min-w-12 md:min-w-10 ">Start:</label>
      <div class=" flex w-full gap-1">
        <select v-model="startHour" class="grow text-sm rounded">
          <option v-for="hour in 12" :key="hour" :value="hour">
            {{ String(hour).padStart(2, '0') }}
          </option>
        </select>
        <select v-model="startMinute" class="grow text-sm  rounded">
          <option value="00">00</option>
          <option value="30">30</option>
        </select>
        <select v-model="startPeriod" class="grow  text-sm rounded">
          <option value="AM">AM</option>
          <option value="PM">PM</option>
        </select>
      </div>
    </div>

    <div class="flex  items-center text-sm ">
      <label class="min-w-12 md:min-w-10 ">End:</label>
      <div class=" flex w-full gap-1">
        <select v-model="endHour" class="grow text-sm rounded">
          <option v-for="hour in 12" :key="hour" :value="hour">
            {{ String(hour).padStart(2, '0') }}
          </option>
        </select>
        <select v-model="endMinute" class="grow text-sm rounded">
          <option value="00">00</option>
          <option value="30">30</option>
        </select>
        <select v-model="endPeriod" class="grow text-sm rounded">
          <option value="AM">AM</option>
          <option value="PM">PM</option>
        </select>
      </div>
    </div>
    <div class=" md:col-span-2">
      <button
        @click="addTime"
        class="w-full text-white p-2 roundedtransition-colors "
        :disabled="isDisabled"
      >
        <p class="block md:hidden">{{ isDisabled ? 'Select a date above' : 'Add time' }}</p>
        <p class="hidden md:block">{{ isDisabled ? 'Select a date below' : 'Add time' }}</p>
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { convertTo24Hour } from '@/utils/dateTime'

const emit = defineEmits([
  'update:start-time',
  'update:end-time',
  'triggerTimeUpdate',
])

defineProps({
  isDisabled: {
    type: Boolean,
    required: false,
    default: false,
  },
})

const startHour = ref(9)
const startMinute = ref('00')
const startPeriod = ref('AM')

const endHour = ref(10)
const endMinute = ref('00')
const endPeriod = ref('AM')

const addTime = () => {
  const startTime = convertTo24Hour(
    startHour.value,
    startMinute.value,
    startPeriod.value,
  )
  const endTime = convertTo24Hour(
    endHour.value,
    endMinute.value,
    endPeriod.value,
  )
  emit('update:start-time', startTime)
  emit('update:end-time', endTime)
  emit('triggerTimeUpdate')
}
</script>

<style scoped>
button {
  display: inline-block;
  width: 100%;
  background-color: rgb(96 165 250);
  color: white;
  padding: 0.5rem;
  border-radius: 0.375rem;
  text-align: center;
  cursor: pointer;
}

button:hover {
  background-color: rgb(83, 148, 228);
}

select {
  padding: 0.5rem;
  border-radius: 0.375rem;
  border: 1px solid #e2e8f0;
}

select:focus {
  outline: none;
  border-color: #3182ce;
}

button:disabled {
  background-color: #d3d3d3;
  color: #888888;
  cursor: not-allowed;
  opacity: 0.6;
  pointer-events: none;
}

button:disabled:hover {
  background-color: #d3d3d3;
  color: #888888;
}
</style>
