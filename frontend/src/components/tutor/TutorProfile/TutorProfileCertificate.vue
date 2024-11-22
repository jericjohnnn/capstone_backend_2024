<template>
  <div class="space-y-4">
    <NotificationToast 
      :show="notification.show"
      :message="notification.message"
      :type="notification.type"
    />
    <!-- Certificate List -->
    <div
      v-for="(certificate, index) in userData.certificates"
      :key="certificate.id"
      class="p-4 bg-white border rounded-lg transition-all"
      :class="{'shadow-sm hover:shadow-md': !editingIndex.includes(index)}"
    >
      <!-- Display Mode -->
      <div v-if="!editingIndex.includes(index)" class="flex flex-col sm:flex-row sm:items-start gap-4">
        <div class="flex-shrink-0 justify-items-center">
          <span class="w-12 h-12 flex items-center justify-center">
            <img
              :src="certificateImage"
              alt="Certificate Image"
              class="w-12 h-12 object-cover "
            />
          </span>
        </div>
        
        <div class="flex-grow space-y-1">
          <p class="text-lg font-semibold text-gray-900">{{ certificate.title }}</p>
          <p class="text-sm text-gray-500 italic">{{ certificate.issuer }}</p>
          <p class="text-sm text-gray-600">{{ formatDate(certificate.date_issued) }}</p>
        </div>
        
        <div class="flex sm:flex-col gap-3 mt-2 sm:mt-0">
          <button 
            @click="editCertificate(index)" 
            class="text-blue-600 text-sm hover:text-blue-700 transition-colors"
          >
            Edit
          </button>
          <button 
            @click="deleteCertificate(certificate.id, index)" 
            class="text-red-600 text-sm hover:text-red-700 transition-colors"
          >
            Delete
          </button>
        </div>
      </div>

      <!-- Edit Mode -->
      <div v-else class="space-y-4">
        <div class="flex flex-col sm:flex-row gap-4">
          <div class="flex-grow space-y-2">
            <input
              type="text"
              v-model="certificate.title"
              placeholder="Certificate Title"
              class="w-full px-3 py-1.5 border rounded text-sm focus:border-blue-500 focus:outline-none"
            />
            <input
              type="text"
              v-model="certificate.issuer"
              placeholder="Issuer"
              class="w-full px-3 py-1.5 border rounded text-sm focus:border-blue-500 focus:outline-none"
            />
            <input
              type="date"
              v-model="certificate.date_issued"
              class="w-full px-3 py-1.5 border rounded text-sm focus:border-blue-500 focus:outline-none"
            />
          </div>
        </div>

        <div class="flex justify-end gap-2">
          <button 
            @click="cancelEdit(index)" 
            class="px-4 py-1.5 text-sm text-gray-600 hover:text-gray-800 transition-colors"
          >
            Cancel
          </button>
          <button 
            @click="saveCertificate(index)" 
            class="px-4 py-1.5 text-sm bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors"
          >
            Save
          </button>
        </div>
      </div>
    </div>

    <!-- Add New Certificate Button -->
    <button 
      v-if="!addingNew"
      @click="addNewCertificate" 
      class="w-full py-2 px-4 text-sm text-blue-600 border border-blue-600 rounded-lg hover:bg-blue-50 transition-colors"
    >
      + Add Certificate
    </button>

    <!-- Add New Certificate Form -->
    <div v-if="addingNew" class="p-4 border rounded-lg space-y-4">
      <div class="space-y-2">
        <input
          type="text"
          v-model="newCertificate.title"
          placeholder="Certificate Title"
          class="w-full px-3 py-1.5 border rounded text-sm focus:border-blue-500 focus:outline-none"
        />
        <input
          type="text"
          v-model="newCertificate.issuer"
          placeholder="Issuer"
          class="w-full px-3 py-1.5 border rounded text-sm focus:border-blue-500 focus:outline-none"
        />
        <input
          type="date"
          v-model="newCertificate.date_issued"
          class="w-full px-3 py-1.5 border rounded text-sm focus:border-blue-500 focus:outline-none"
        />
      </div>

      <div class="flex justify-end gap-2">
        <button 
          @click="cancelNewCertificate" 
          class="px-4 py-1.5 text-sm text-gray-600 hover:text-gray-800 transition-colors"
        >
          Cancel
        </button>
        <button 
          @click="saveNewCertificate" 
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
import certificateImage from '@/assets/certificate.png'
import { formatDate } from '@/utils/dateTime'
import { getUserData } from '@/utils/user'
import NotificationToast from '@/components/Reusables/NotificationToast.vue'
import { useNotification } from '@/composables/useNotification'

const userData = getUserData()
const { notification, showNotification } = useNotification()

const editingIndex = ref([])
const addingNew = ref(false)

const newCertificate = ref({
  title: '',
  issuer: '',
  date_issued: '',
})

const editCertificate = index => {
  if (!editingIndex.value.includes(index)) {
    editingIndex.value.push(index)
  }
}

const saveCertificate = async index => {
  try {
    const certificate = { ...userData.value.certificates[index] }
    const dataToSave = {
      id: certificate.id,
      title: certificate.title,
      issuer: certificate.issuer,
      date_issued: certificate.date_issued,
    }
    await axiosInstance.put(`/api/edit-certificate/${certificate.id}`, dataToSave)
    localStorage.setItem('user_data', JSON.stringify(userData.value))
    showNotification('Certificate updated successfully!')
    editingIndex.value = editingIndex.value.filter(i => i !== index)
  } catch (error) {
    console.error('Failed to update certificate:', error)
    showNotification(
      error.response?.data?.message || 'Failed to update certificate',
      'error'
    )
  }
}

const cancelEdit = index => {
  editingIndex.value = editingIndex.value.filter(i => i !== index)
}

const addNewCertificate = () => {
  addingNew.value = true
}

const saveNewCertificate = async () => {
  try {
    const dataToSave = { ...newCertificate.value }
    const response = await axiosInstance.post('/api/add-certificate', dataToSave)
    userData.value.certificates.push(response.data.certificate)
    localStorage.setItem('user_data', JSON.stringify(userData.value))
    showNotification('New certificate added successfully!')
    addingNew.value = false
    newCertificate.value = {
      title: '',
      issuer: '',
      date_issued: '',
    }
  } catch (error) {
    console.error('Failed to add new certificate:', error)
    showNotification(
      error.response?.data?.message || 'Failed to add new certificate',
      'error'
    )
  }
}

const cancelNewCertificate = () => {
  addingNew.value = false
  newCertificate.value = {
    title: '',
    issuer: '',
    date_issued: '',
  }
}

const deleteCertificate = async (id, index) => {
  try {
    await axiosInstance.delete(`/api/delete-certificate/${id}`)
    userData.value.certificates.splice(index, 1)
    localStorage.setItem('user_data', JSON.stringify(userData.value))
    showNotification('Certificate deleted successfully!', 'error')
  } catch (error) {
    console.error('Failed to delete certificate:', error)
    showNotification(
      error.response?.data?.message || 'Failed to delete certificate',
      'error'
    )
  }
}
</script>

<style scoped>
/* Add any custom styling here */
</style>
