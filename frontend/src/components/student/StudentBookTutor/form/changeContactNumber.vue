<template>
  <div class="">
    <div class="flex justify-between">
      <label class="text-base ">Contact number:</label>
      <div class="flex flex-wrap gap-4 ">
        <button
          type="button"
          @click="toggleEditing"
          class="text-sm text-blue-600 hover:text-blue-700"
        >
          {{ isReadonly ? 'Change number' : 'Apply' }}
        </button>
        <button
          v-if="!isReadonly"
          type="button"
          @click="cancelChanges"
          class="text-sm text-gray-600 hover:text-gray-700"
        >
          Cancel
        </button>
      </div>
    </div>
    <div class="mt-2">
      <div class="">
        <div v-if="isReadonly">
          <p class="py-2.5 px-3.5 rounded-lg bg-gray-50 text-base text-gray-700">
            {{ contactNumber }}
          </p>
        </div>
        <div v-else>
          <input
            v-model="contactNumber"
            type="text"
            :readonly="isReadonly"
            class="w-full text-base rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
  contactNumber: {
    type: String,
    required: true,
  },
})

const modifiedContactNumber = ref('')

const isReadonly = ref(true)

const toggleEditing = () => {
  isReadonly.value = !isReadonly.value
  modifiedContactNumber.value = contactNumber.value
}

const cancelChanges = () => {
  contactNumber.value = modifiedContactNumber.value
  isReadonly.value = true
}

const contactNumber = ref(props.contactNumber)

const emit = defineEmits(['update:contactNumber'])

watch(contactNumber, newVal => {
  emit('update:contactNumber', newVal)
})
</script>
