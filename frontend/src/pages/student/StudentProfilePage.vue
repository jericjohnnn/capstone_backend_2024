<template>
  <main>
    <NotificationToast 
      :show="notification.show"
      :message="notification.message"
      :type="notification.type"
    />
    <SideBar>
      <main class="container mx-auto py-8">
        <div class="mb-6">
          <h1 class="text-lg font-bold text-black">Account Information</h1>
        </div>

        <!-- Render sections using v-for -->
        <div v-for="(section, index) in sections" :key="index" class="bg-white rounded-lg shadow-sm border border-gray-200 mb-4">
          <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-base font-semibold text-black">{{ section.title }}</h2>
          </div>

          <div class="divide-y divide-gray-200">
            <div 
              v-for="(field, fieldIndex) in section.fields" 
              :key="fieldIndex"
              class="grid grid-cols-1 md:grid-cols-2 px-6 py-4 items-center"
            >
              <span class="text-sm text-gray-600">{{ field.label }}</span>
              
              <template v-if="field.type === 'image'">
                <img 
                  :src="currentProfileImage"
                  class="w-24 h-24 md:w-20 md:h-20 rounded-full object-cover bg-gray-200"
                  alt="Profile Image"
                />
              </template>
              <span v-else class="text-sm font-medium text-gray-900">
                {{ getFieldValue(field) }}
              </span>
            </div>
          </div>
        </div>

        <!-- Account Actions Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
          <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-base font-semibold text-black">Account</h2>
          </div>

          <div v-for="(action, index) in actions" :key="index" class="px-6 py-4 flex justify-between items-center border-b border-gray-200">
            <div>
              <h3 class="text-sm font-medium text-gray-900">{{ action.title }}</h3>
              <p class="text-xs text-gray-600 mt-1">{{ action.description }}</p>
            </div>
            <button 
              @click="action.handler"
              :class="`px-4 py-2 text-sm font-medium text-white ${action.buttonClass} rounded-md transition-colors`"
            >
              {{ action.buttonText }}
            </button>
          </div>
        </div>
      </main>
    </SideBar>
  </main>

  <!-- Edit Profile Modal -->
  <div v-if="isEditModalVisible" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
      <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-black">Edit Profile</h3>
      </div>

      <form @submit.prevent="submitNewDetails" class="p-6">
        <!-- Profile Image -->
        <div class="flex items-center space-x-4 mb-6">
          <div class="relative w-20 h-20">
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
              <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all rounded-full">
                <span class="text-white text-3xl opacity-0 group-hover:opacity-100">+</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Form Fields -->
        <div class="space-y-4">
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
              <input
                v-model="editData.first_name"
                type="text"
                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
              <input
                v-model="editData.last_name"
                type="text"
                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md"
              />
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
            <input
              v-model="editData.address"
              type="text"
              class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Contact Number</label>
            <input
              v-model="editData.contact_number"
              type="text"
              class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">School ID Number</label>
            <input
              v-model="editData.school_id_number"
              type="text"
              class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Grade Level</label>
            <select 
              v-model="editData.grade_year"
              class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md"
            >
              <option value="">Select Grade Level</option>
              <option v-for="grade in 12" :key="grade" :value="grade">
                Grade {{ grade }}
              </option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Birthdate</label>
            <input
              v-model="editData.birthdate"
              type="date"
              class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md"
            />
          </div>
        </div>

        <div class="flex justify-end space-x-3 mt-6">
          <button 
            type="button" 
            @click="cancelEdit" 
            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200"
          >
            Cancel
          </button>
          <button 
            type="submit" 
            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700"
          >
            Save Changes
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Delete Confirmation Modal -->
  <div v-if="isDeleteModalVisible" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4 p-6">
      <h3 class="text-lg font-semibold text-black mb-2">Delete Account</h3>
      <p class="text-sm text-gray-600 mb-6">Are you sure you want to delete your account? This action cannot be undone.</p>
      
      <div class="flex justify-end space-x-3">
        <button 
          @click="closeDeleteModal" 
          class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors"
        >
          Cancel
        </button>
        <button 
          @click="deleteAccount" 
          class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 transition-colors"
        >
          Delete Account
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { useRouter } from 'vue-router'
import axiosInstance from '@/axiosInstance'
import SideBar from '@/components/SideBar.vue'
import { formatDate } from '@/utils/dateTime'
import { getUserData } from '@/utils/user'
import NotificationToast from '@/components/Reusables/NotificationToast.vue'
import { useNotification } from '@/composables/useNotification'

const router = useRouter()
const { notification, showNotification } = useNotification()

// Define defaultProfileImage first
const defaultProfileImage = 'data:image/svg+xml;base64,' + btoa(`
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
    <circle cx="12" cy="12" r="11" fill="white" stroke="#E5E7EB" stroke-width="2"/>
    <circle cx="12" cy="8" r="3.5" fill="#9CA3AF"/>
    <path d="M12 12.5c-3 0-5.5 1.5-7 3.5 1.5 3 4 5 7 5s5.5-2 7-5c-1.5-2-4-3.5-7-3.5z" fill="#9CA3AF"/>
  </svg>
`)

const userData = getUserData()
const isEditModalVisible = ref(false)
const isDeleteModalVisible = ref(false)
const fileInput = ref(null)
const selectedImage = ref(null)
const currentProfileImage = ref(userData.value.profile_image || defaultProfileImage)
const editData = reactive({
  first_name: userData.value.first_name || '',
  last_name: userData.value.last_name || '',
  birthdate: userData.value.birthdate || '',
  address: userData.value.address || '',
  contact_number: userData.value.contact_number || '',
  school_id_number: userData.value.school_id_number || '',
  grade_year: userData.value.grade_year || ''
})

// Configuration objects
const sections = [
  {
    title: 'Personal Information',
    fields: [
      { label: 'Name', value: user => `${user.first_name} ${user.last_name}` },
      { label: 'Address', value: 'address' },
      { label: 'Contact Number', value: 'contact_number' }
    ]
  },
  {
    title: 'Profile and Other Data',
    fields: [
      { label: 'Profile Image', value: 'profile_image', type: 'image' },
      { label: 'Birthdate', value: user => user.birthdate ? formatDate(user.birthdate) : '' },
      { label: 'School ID Number', value: 'school_id_number' },
      { label: 'Grade Level', value: 'grade_year' },
      { label: 'Account Created', value: user => user.created_at ? formatDate(user.created_at) : '' }
    ]
  }
]

const actions = [
  {
    title: 'Edit Account',
    description: 'Update your personal and account information for accuracy and convenience.',
    buttonText: 'Edit',
    buttonClass: 'bg-blue-600 hover:bg-blue-700',
    handler: () => toggleEditMode()
  },
  {
    title: 'Delete Account',
    description: 'All account information will be permanently deleted without the possibility of restoration.',
    buttonText: 'Delete Account',
    buttonClass: 'bg-red-600 hover:bg-red-700',
    handler: () => openDeleteModal()
  }
]

// Helper function to get field values
const getFieldValue = (field) => {
  if (typeof field.value === 'function') {
    return field.value(userData.value)
  }
  return userData.value?.[field.value]
}

const toggleEditMode = () => {
  if (!isEditModalVisible.value) {
    editData.first_name = userData.value.first_name || ''
    editData.last_name = userData.value.last_name || ''
    editData.birthdate = userData.value.birthdate || ''
    editData.address = userData.value.address || ''
    editData.contact_number = userData.value.contact_number || ''
    editData.school_id_number = userData.value.school_id_number || ''
    editData.grade_year = userData.value.grade_year || ''
  }
  isEditModalVisible.value = !isEditModalVisible.value
}

const openFilePicker = () => {
  fileInput.value.click()
}

const handleFileUpload = e => {
  const file = e.target.files[0]
  if (file) {
    if (file.size > 5000000) { // 5MB limit
      showNotification('Image size should be less than 5MB', 'error')
      return
    }
    
    selectedImage.value = file
    currentProfileImage.value = URL.createObjectURL(file)
  }
}

const submitNewDetails = async () => {
  const formData = new FormData()
  
  // Only append fields that have values
  Object.entries(editData).forEach(([key, value]) => {
    if (value) formData.append(key, value)
  })

  if (selectedImage.value) {
    formData.append('profile_image', selectedImage.value)
  }

  try {
    const response = await axiosInstance.post('/api/edit-student-details', formData)
    userData.value = response.data.student
    localStorage.setItem('user_data', JSON.stringify(userData.value))
    isEditModalVisible.value = false
    showNotification('Profile updated successfully', 'success')
  } catch (error) {
    console.error('Update failed:', error)
    showNotification(error.response?.data?.message || 'Failed to update profile', 'error')
  }
}

const cancelEdit = () => {
  currentProfileImage.value = userData.value.profile_image || defaultProfileImage
  selectedImage.value = null
  Object.assign(editData, {
    first_name: userData.value.first_name || '',
    last_name: userData.value.last_name || '',
    birthdate: userData.value.birthdate || '',
    address: userData.value.address || '',
    contact_number: userData.value.contact_number || '',
    school_id_number: userData.value.school_id_number || '',
    grade_year: userData.value.grade_year || ''
  })
  isEditModalVisible.value = false
}

const deleteAccount = async () => {
  try {
    await axiosInstance.delete('/api/delete-student-account')
    const keysToRemove = [
      'user_data', 'student_data', 'app_auth_token',
      'user_email', 'user_full_name', 'user_type'
    ]
    keysToRemove.forEach(key => localStorage.removeItem(key))
    sessionStorage.clear()
    showNotification('Account deleted successfully', 'success')
    setTimeout(() => {
      router.push('/')
    }, 1500)
  } catch (error) {
    console.error("Error deleting account:", error)
    showNotification(error.response?.data?.message || 'Failed to delete account', 'error')
  }
}

const openDeleteModal = () => isDeleteModalVisible.value = true
const closeDeleteModal = () => isDeleteModalVisible.value = false
</script>

<style scoped>
/* Optional: Style for modal form and other elements */
</style>
