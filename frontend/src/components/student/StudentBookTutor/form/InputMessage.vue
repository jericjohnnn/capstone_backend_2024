<template>
  <div>
    <label class="text-base ">Message:</label>
    <div class=" bg-white mt-2 p-3 rounded-lg">
      <div class="flex flex-col gap-2   overflow-hidden">
        <input
          v-model="topic"
          placeholder="Enter the topic that needs tutoring"
          class="w-full rounded-lg border focus:ring-0 focus:border-blue-600"
        />
        <textarea
          v-model="message"
          placeholder="Enter objectives, concerns, and further details of your inquiry"
          class="w-full rounded-lg h-32 border focus:ring-0 focus:border-blue-600"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'

const topic = ref('')
const message = ref('')

const emit = defineEmits(['update:tutorTopic', 'update:tutorMessage'])

watch(topic, newVal => {
  emit('update:tutorTopic', newVal)
})

watch(message, newVal => {
  emit('update:tutorMessage', newVal)
})
</script>
