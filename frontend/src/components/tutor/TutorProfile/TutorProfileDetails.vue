<template>
  <div class="mb-6">
    <NotificationToast 
      :show="notification.show"
      :message="notification.message"
      :type="notification.type"
    />

    <div class="flex justify-between items-center mb-3">
      <h3 class="font-medium">Personal Details</h3>
      <button 
        @click="toggleEditMode" 
        class="text-blue-600 text-sm hover:text-blue-700 transition-colors"
      >
        {{ isEditing ? 'Cancel' : 'Edit' }}
      </button>
    </div>

    <!-- Display Mode -->
    <div v-if="!isEditing" class="space-y-2">
      <div class="flex flex-col px-2">
        <span class="text-xs text-gray-500">Birthdate</span>
        <span class="text-sm">{{ formatDate(userData.birthdate) }}</span>
      </div>
      <div class="flex flex-col px-2">
        <span class="text-xs text-gray-500">Address</span>
        <span class="text-sm">{{ userData.address || 'Not set' }}</span>
      </div>
      <div class="flex flex-col px-2">
        <span class="text-xs text-gray-500">Contact Number</span>
        <span class="text-sm">{{ userData.contact_number || 'Not set' }}</span>
      </div>
    </div>

    <!-- Edit Mode -->
    <div v-else class="space-y-3">
      <div class="flex flex-col space-y-1">
        <label class="text-xs text-gray-500">Birthdate</label>
        <input
          type="date"
          v-model="birthdate"
          class="w-full px-3 py-1.5 border rounded text-sm focus:border-blue-500 focus:outline-none"
        />
      </div>

      <div class="flex flex-col space-y-1">
        <label class="text-xs text-gray-500">Address</label>
        <input
          type="text"
          v-model="address"
          placeholder="Enter your address"
          class="w-full px-3 py-1.5 border rounded text-sm focus:border-blue-500 focus:outline-none"
        />
      </div>

      <div class="flex flex-col space-y-1">
        <label class="text-xs text-gray-500">Contact Number</label>
        <input
          type="text"
          v-model="contactNumber"
          placeholder="Enter your contact number"
          class="w-full px-3 py-1.5 border rounded text-sm focus:border-blue-500 focus:outline-none"
        />
      </div>

      <div class="flex justify-end pt-2">
        <button
          @click="saveDetails"
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
import { formatDate } from '@/utils/dateTime'
import { getUserData } from '@/utils/user'
import NotificationToast from '@/components/Reusables/NotificationToast.vue'
import { useNotification } from '@/composables/useNotification'

const userData = getUserData()
const { notification, showNotification } = useNotification()

const birthdate = ref(userData.value.birthdate)
const address = ref(userData.value.address)
const contactNumber = ref(userData.value.contact_number)
const isEditing = ref(false)

const saveDetails = async () => {
  try {
    const data = {
      birthdate: birthdate.value,
      address: address.value,
      contact_number: contactNumber.value,
    }

    await axiosInstance.post('api/edit-details', data)

    userData.value.birthdate = data.birthdate
    userData.value.address = data.address
    userData.value.contact_number = data.contact_number
    localStorage.setItem('user_data', JSON.stringify(userData.value))
    
    showNotification('Personal details updated successfully!')
    isEditing.value = false
  } catch (error) {
    console.error('Error saving personal details:', error)
    showNotification(
      error.response?.data?.message || 'Failed to update personal details',
      'error'
    )
  }
}

const toggleEditMode = () => {
  if (isEditing.value) {
    birthdate.value = userData.value.birthdate
    address.value = userData.value.address
    contactNumber.value = userData.value.contact_number
  }
  isEditing.value = !isEditing.value
}
</script>
