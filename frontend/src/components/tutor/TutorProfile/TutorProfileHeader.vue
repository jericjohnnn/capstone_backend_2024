<template>
  <div class="p-4 bg-white rounded-lg shadow-md">
    <NotificationToast 
      :show="notification.show"
      :message="notification.message"
      :type="notification.type"
    />
    
    <!-- Profile Display -->
    <div v-if="!isProfileEdit" class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div class="flex flex-col md:flex-row items-center md:items-center gap-4">
        <img
          :src="userData.profile_image || defaultProfileImage"
          class="w-24 h-24 md:w-20 md:h-20 rounded-full object-cover bg-gray-200"
          alt="Profile Image"
        />
        <div class="text-center  md:text-left">
          <h2 class="text-xl md:text-lg font-semibold">
            {{ userData.first_name }} {{ userData.last_name }}
          </h2>
          <p class="text-gray-600">{{ userEmail }}</p>
        </div>
      </div>
      <div class="flex justify-center md:justify-end gap-4">
        <button
          @click="isProfileEdit = true"
          class="px-4 py-2 text-sm text-white bg-gray-600 rounded hover:bg-gray-700"
        >
          Edit
        </button>
      </div>
    </div>

    <!-- Profile Edit Form -->
    <div v-if="isProfileEdit" class="flex flex-col md:flex-row  justify-between gap-4 md:items-center ">
      <div class="flex flex-col md:flex-row gap-4">
        <!-- Profile Image Upload -->
        <div class="relative w-24 h-24 md:w-20 md:h-20 mx-auto md:mx-0">
          <input
            type="file"
            ref="fileInput"
            @change="handleFileUpload"
            class="hidden"
            accept="image/*"
          />
          <div
            @click="openFilePicker"
            class="w-full h-full cursor-pointer relative group"
          >
            <img
              :src="currentProfileImage"
              class="w-full h-full rounded-full object-cover bg-gray-200"
              alt="Profile Image"
            />
            <div
              class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all rounded-full"
            >
              <span class="text-white text-3xl opacity-0 group-hover:opacity-100">+</span>
            </div>
          </div>
        </div>
        <!-- Edit Form -->
        <div class="flex flex-col w-full md:w-64 gap-2  justify-center">
          <div class="flex flex-col md:flex-row gap-2 ">
            <input
              v-model="userInfoData.first_name"
              placeholder="First name"
              class="w-full px-2 py-1 border rounded text-sm"
            />
            <input
              v-model="userInfoData.last_name"
              placeholder="Last name"
              class="w-full px-2 py-1 border rounded text-sm"
            />
          </div>
          <p class="text-center md:text-left text-gray-600">{{ userEmail }}</p>
        </div>
      </div>
      <div class="flex justify-center md:justify-end gap-4 ">
        <button
          @click="cancelEdit"
          class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800"
        >
          Cancel
        </button>
        <button
          @click="submitNewDetails"
          class="px-4 py-2 text-sm text-green-600 hover:text-green-800"
        >
          Save
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import axiosInstance from '@/axiosInstance'
import { getUserData } from '@/utils/user'
import NotificationToast from '@/components/Reusables/NotificationToast.vue'
import { useNotification } from '@/composables/useNotification'

const userEmail = localStorage.getItem('user_email')
const userData = getUserData()
const { notification, showNotification } = useNotification()

const defaultProfileImage =
  'data:image/svg+xml;base64,' +
  btoa(`
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
    <circle cx="12" cy="12" r="11" fill="white" stroke="#E5E7EB" stroke-width="2"/>
    <circle cx="12" cy="8" r="3.5" fill="#9CA3AF"/>
    <path d="M12 12.5c-3 0-5.5 1.5-7 3.5 1.5 3 4 5 7 5s5.5-2 7-5c-1.5-2-4-3.5-7-3.5z" fill="#9CA3AF"/>
  </svg>`)

const userInfoData = reactive({
  first_name: userData.value.first_name,
  last_name: userData.value.last_name,
})
const selectedImage = ref(null)

const isProfileEdit = ref(false)
const currentProfileImage = ref(
  userData.value.profile_image || defaultProfileImage,
)

const fileInput = ref(null)
const openFilePicker = () => {
  fileInput.value.click()
}

const handleFileUpload = e => {
  const file = e.target.files[0]
  if (file) {
    selectedImage.value = file
    currentProfileImage.value = URL.createObjectURL(file)
  }
}

const submitNewDetails = async () => {
  const formData = new FormData()
  formData.append('first_name', userInfoData.first_name)
  formData.append('last_name', userInfoData.last_name)
  if (selectedImage.value) formData.append('profile_image', selectedImage.value)

  try {
    const response = await axiosInstance.post('/api/edit-details', formData)
    userData.value = response.data.tutor
    localStorage.setItem('user_data', JSON.stringify(userData.value))
    showNotification('Profile updated successfully!')
    isProfileEdit.value = false
  } catch (error) {
    console.error('Update failed:', error)
    showNotification(
      error.response?.data?.message || 'Failed to update profile',
      'error'
    )
  }
}

const cancelEdit = () => {
  currentProfileImage.value = userData.value.profile_image || defaultProfileImage
  selectedImage.value = null
  userInfoData.first_name = userData.value.first_name
  userInfoData.last_name = userData.value.last_name
  isProfileEdit.value = false
}
</script>
