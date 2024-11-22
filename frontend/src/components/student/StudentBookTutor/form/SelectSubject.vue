<template>
<div>
  <label class=" text-base ">Subject:</label>
  <select
    v-model="selectedSubject"
    class="w-full mt-2 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
  >
    <option
      v-for="subject in tutor.subjects"
      :key="subject.id"
      :value="subject.name"
    >
      {{ subject.name }}
    </option>
  </select>
</div>
</template>

<script setup>
import { ref, watch } from 'vue'

defineProps({
  tutor: {
    type: Object,
    required: true
  }
})

const selectedSubject = ref('')

const emit = defineEmits(['update:selectedSubject'])

watch(selectedSubject, (newVal) => {
  emit('update:selectedSubject', newVal)
})
</script>

