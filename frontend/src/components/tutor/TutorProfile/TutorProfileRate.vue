<template>
  <div class="mb-6">
    <NotificationToast 
      :show="notification.show"
      :message="notification.message"
      :type="notification.type"
    />

    <div class="flex justify-between items-center mb-3">
      <h3 class="font-medium">Tutor Rate</h3>
      <button 
        @click="toggleEditMode" 
        class="text-blue-600 text-sm hover:text-blue-700 transition-colors"
      >
        {{ isEditing ? 'Cancel' : 'Edit' }}
      </button>
    </div>

    <!-- Display Mode -->
    <div v-if="!isEditing" class="px-2">
      <p class="text-blue-600 text-sm font-medium">
        ₱{{ userData.tutor_rate }}/Session
      </p>
    </div>

    <!-- Edit Mode -->
    <div v-else class="space-y-3">
      <div class="flex flex-col sm:flex-row gap-3">
        <div class="flex-1">
          <div class="relative">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">₱</span>
            <input
              type="number"
              v-model.number="tutorRate"
              class="w-full pl-8 pr-3 py-1.5 border rounded text-sm focus:border-blue-500 focus:outline-none"
              min="0"
              placeholder="Enter rate per session"
            />
          </div>
          <p class="text-xs text-gray-500 mt-1">Rate per session</p>
        </div>
      </div>

      <div class="flex justify-end pt-2">
        <button
          @click="saveTutorRate"
          class="w-full sm:w-auto px-6 py-1.5 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition-colors"
        >
          Save
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import axiosInstance from '@/axiosInstance'
import { getUserData } from '@/utils/user'
import NotificationToast from '@/components/Reusables/NotificationToast.vue'
import { useNotification } from '@/composables/useNotification'

const userData = getUserData()
const tutorRate = ref(userData.value.tutor_rate || 0)
const isEditing = ref(false)
const { notification, showNotification } = useNotification()

const saveTutorRate = async () => {
  try {
    const data = {
      tutor_rate: Math.floor(tutorRate.value)
    }

    await axiosInstance.post('/api/edit-details', data)

    userData.value.tutor_rate = data.tutor_rate
    localStorage.setItem('user_data', JSON.stringify(userData.value))
    showNotification('Tutor rate updated successfully!')
    isEditing.value = false
  } catch (error) {
    console.error('Error saving tutor rate:', error)
    showNotification(
      error.response?.data?.message || 'Failed to update tutor rate',
      'error'
    )
  }
}

const toggleEditMode = () => {
  if (isEditing.value) {
    tutorRate.value = userData.value.tutor_rate
  }
  isEditing.value = !isEditing.value
}
</script>
