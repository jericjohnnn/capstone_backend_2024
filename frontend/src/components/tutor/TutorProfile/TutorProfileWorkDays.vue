<template>
  <div class="mb-6">
    <NotificationToast 
      :show="notification.show"
      :message="notification.message"
      :type="notification.type"
    />

    <div class="flex justify-between items-center mb-3">
      <h3 class="font-medium">Days available</h3>
      <button 
        @click="toggleEditMode" 
        class="text-blue-600 text-sm hover:text-blue-700 transition-colors"
      >
        {{ isEditing ? 'Cancel' : 'Edit' }}
      </button>
    </div>

    <!-- Display Mode -->
    <div v-if="!isEditing" class="flex flex-wrap gap-2">
      <div 
        v-if="availableDays.length === 0" 
        class="text-gray-500 text-sm w-full text-center py-2"
      >
        No days set
      </div>
      <span
        v-for="day in availableDays"
        :key="day"
        class="px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full"
      >
        {{ day.charAt(0).toUpperCase() + day.slice(1) }}
      </span>
    </div>

    <!-- Edit Mode -->
    <div v-else class="space-y-3">
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-1 lg:grid-cols-2 gap-2">
        <div
          v-for="day in daysOfWeek"
          :key="day"
          class="flex items-center gap-2 p-2 border rounded hover:bg-gray-50 transition-colors"
        >
          <input
            type="checkbox"
            :id="day"
            :value="day"
            v-model="selectedDays"
            class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500"
          />
          <label :for="day" class="text-sm cursor-pointer select-none">
            {{ day.charAt(0).toUpperCase() + day.slice(1) }}
          </label>
        </div>
      </div>

      <div class="flex justify-end pt-2">
        <button
          @click="saveDays"
          class="w-full sm:w-auto px-6 py-1.5 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition-colors"
        >
          Save
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue';
import axiosInstance from '@/axiosInstance'
import { getUserData } from '@/utils/user'
import NotificationToast from '@/components/Reusables/NotificationToast.vue'
import { useNotification } from '@/composables/useNotification'

const userData = getUserData()
const { notification, showNotification } = useNotification()

const daysOfWeek = [
  'monday',
  'tuesday',
  'wednesday',
  'thursday',
  'friday',
  'saturday',
  'sunday',
];

const availableDays = computed(() => {
  if (!userData.value.work_days || Object.keys(userData.value.work_days).length === 0) {
    return [];
  }
  return daysOfWeek.filter(day => userData.value.work_days[day]);
});

const isEditing = ref(false);
const selectedDays = ref([...availableDays.value]);

const toggleEditMode = () => {
  if (isEditing.value) {
    selectedDays.value = [...availableDays.value];
  }
  isEditing.value = !isEditing.value
}

const saveDays = async () => {
  const daysData = {}
  daysOfWeek.forEach(day => {
    daysData[day] = selectedDays.value.includes(day) ? 1 : 0
  })

  try {
    const response = await axiosInstance.put('/api/edit-work-days', daysData)
    userData.value.work_days = response.data.work_days
    localStorage.setItem('user_data', JSON.stringify(userData.value))
    isEditing.value = false
    showNotification('Work days updated successfully!')
  } catch (error) {
    console.error('Error saving days:', error)
    selectedDays.value = [...availableDays.value]
    showNotification(
      error.response?.data?.message || 'Failed to update work days',
      'error'
    )
  }
}

</script>
