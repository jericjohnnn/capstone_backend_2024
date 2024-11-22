<template>
  <div class="mb-6">
    <NotificationToast 
      :show="notification.show"
      :message="notification.message"
      :type="notification.type"
    />

    <div class="flex justify-between items-center mb-3">
      <h3 class="font-medium">Hours available</h3>
      <button 
        @click="toggleEdit" 
        class="text-blue-600 text-sm hover:text-blue-700 transition-colors"
      >
        {{ isEditing ? 'Cancel' : 'Edit' }}
      </button>
    </div>

    <!-- Display Mode -->
    <div v-if="!isEditing" class="px-2">
      <p class="text-blue-600 text-sm font-medium">
        {{ formatTo12Hour(userData.work_days.start_time) }} - 
        {{ formatTo12Hour(userData.work_days.end_time) }}
      </p>
    </div>

    <!-- Edit Mode -->
    <div v-else class="space-y-4">
      <div class="grid grid-cols-1 sm:grid-cols-5 gap-3 items-center">
        <!-- Start Time -->
        <div class="col-span-1 sm:col-span-2">
          <div class="flex items-center gap-2 justify-center sm:justify-start">
            <select 
              v-model="startTime.hour" 
              class="w-20 px-3 py-1.5 border rounded text-sm focus:border-blue-500 focus:outline-none"
            >
              <option v-for="h in 12" :key="h" :value="h">{{ h }}</option>
            </select>
            <select 
              v-model="startTime.period" 
              class="w-20 px-3 py-1.5 border rounded text-sm focus:border-blue-500 focus:outline-none"
            >
              <option value="AM">AM</option>
              <option value="PM">PM</option>
            </select>
          </div>
        </div>

        <!-- Separator -->
        <div class="col-span-1 text-center text-gray-500">
          <span class="hidden sm:inline">to</span>
          <div class="sm:hidden w-full flex items-center gap-4">
            <div class="flex-1 h-px bg-gray-200"></div>
            <span>to</span>
            <div class="flex-1 h-px bg-gray-200"></div>
          </div>
        </div>

        <!-- End Time -->
        <div class="col-span-1 sm:col-span-2">
          <div class="flex items-center gap-2 justify-center sm:justify-start">
            <select 
              v-model="endTime.hour" 
              class="w-20 px-3 py-1.5 border rounded text-sm focus:border-blue-500 focus:outline-none"
            >
              <option v-for="h in 12" :key="h" :value="h">{{ h }}</option>
            </select>
            <select 
              v-model="endTime.period" 
              class="w-20 px-3 py-1.5 border rounded text-sm focus:border-blue-500 focus:outline-none"
            >
              <option value="AM">AM</option>
              <option value="PM">PM</option>
            </select>
          </div>
        </div>
      </div>

      <div class="flex justify-end gap-2 pt-2">
        <button
          @click="saveHours"
          class="px-4 py-1.5 text-sm bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors"
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
import { parseTo12Hour,  formatTo12Hour, convertTo24HourFromObject } from '@/utils/dateTime'
import { getUserData } from '@/utils/user'
import NotificationToast from '@/components/Reusables/NotificationToast.vue'
import { useNotification } from '@/composables/useNotification'

const userData = getUserData()
const isEditing = ref(false)
const { notification, showNotification } = useNotification()

// Store original values for cancellation
const originalStartTime = ref(parseTo12Hour(userData.value.work_days?.start_time || '06:00:00'))
const originalEndTime = ref(parseTo12Hour(userData.value.work_days?.end_time || '20:00:00'))

const startTime = ref({ ...originalStartTime.value })
const endTime = ref({ ...originalEndTime.value })

function toggleEdit() {
  if (isEditing.value) {
    // Reset to original values when cancelling
    startTime.value = { ...originalStartTime.value }
    endTime.value = { ...originalEndTime.value }
  }
  isEditing.value = !isEditing.value
}

async function saveHours() {
  const start24 = convertTo24HourFromObject(startTime.value)
  const end24 = convertTo24HourFromObject(endTime.value)


  try {
    await axiosInstance.put('/api/edit-work-days', {
      start_time: start24,
      end_time: end24
    })

    userData.value.work_days = { 
      ...userData.value.work_days,
      start_time: start24, 
      end_time: end24 
    }
    localStorage.setItem('user_data', JSON.stringify(userData.value))
    
    originalStartTime.value = { ...startTime.value }
    originalEndTime.value = { ...endTime.value }
    
    showNotification('Work hours updated successfully!')
    isEditing.value = false
  } catch (error) {
    console.error('Error saving hours:', error)
    showNotification(
      error.response?.data?.message || 'Failed to update work hours',
      'error'
    )
    
    // Reset to original values on error
    startTime.value = { ...originalStartTime.value }
    endTime.value = { ...originalEndTime.value }
  }
}
</script>