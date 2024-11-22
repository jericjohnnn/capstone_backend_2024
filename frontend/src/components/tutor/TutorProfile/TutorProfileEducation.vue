<template>
  <div class="space-y-4">
    <NotificationToast 
      :show="notification.show"
      :message="notification.message"
      :type="notification.type"
    />
    <!-- Education List -->
    <div
      v-for="(school, index) in userData.schools"
      :key="school.id"
      class="p-4 bg-white border rounded-lg transition-all"
      :class="{'shadow-sm hover:shadow-md': !editingIndex.includes(index)}"
    >
      <!-- Display Mode -->
      <div v-if="!editingIndex.includes(index)" class="flex flex-col sm:flex-row sm:items-start gap-4">
        <div class="flex-shrink-0 justify-items-center">
          <span class="w-12 h-12 flex items-center justify-center ">
            <img
              :src="schoolImage"
              alt="School Logo"
              class="w-12 h-12 object-cover "
            />
          </span>
        </div>
        
        <div class="flex-grow space-y-1">
          <p class="text-lg font-semibold text-gray-900">{{ school.name }}</p>
          <p class="text-sm text-gray-500 italic">{{ school.course }}</p>
          <p class="text-sm text-gray-600">
            {{ formatDate(school.start_date) }} - {{ school.end_date ? formatDate(school.end_date) : 'Present' }}
          </p>
        </div>
        
        <div class="flex sm:flex-col gap-3 mt-2 sm:mt-0">
          <button 
            @click="editSchool(index)" 
            class="text-blue-600 text-sm hover:text-blue-700 transition-colors"
          >
            Edit
          </button>
          <button 
            @click="deleteSchool(school.id, index)" 
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
              v-model="school.name"
              placeholder="School Name"
              class="w-full px-3 py-1.5 border rounded text-sm focus:border-blue-500 focus:outline-none"
            />
            <input
              type="text"
              v-model="school.course"
              placeholder="Course"
              class="w-full px-3 py-1.5 border rounded text-sm focus:border-blue-500 focus:outline-none"
            />
            <div class="flex flex-col sm:flex-row gap-2">
              <input
                type="date"
                v-model="school.start_date"
                class="flex-1 px-3 py-1.5 border rounded text-sm focus:border-blue-500 focus:outline-none"
              />
              <input
                type="date"
                v-model="school.end_date"
                class="flex-1 px-3 py-1.5 border rounded text-sm focus:border-blue-500 focus:outline-none"
              />
            </div>
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
            @click="saveSchool(index)" 
            class="px-4 py-1.5 text-sm bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors"
          >
            Save
          </button>
        </div>
      </div>
    </div>

    <!-- Add New Education Button -->
    <button 
      v-if="!addingNew"
      @click="addNewSchool" 
      class="w-full py-2 px-4 text-sm text-blue-600 border border-blue-600 rounded-lg hover:bg-blue-50 transition-colors"
    >
      + Add Education
    </button>

    <!-- Add New Education Form -->
    <div v-if="addingNew" class="p-4 border rounded-lg space-y-4">
      <div class="space-y-2">
        <input
          type="text"
          v-model="newSchool.name"
          placeholder="School Name"
          class="w-full px-3 py-1.5 border rounded text-sm focus:border-blue-500 focus:outline-none"
        />
        <input
          type="text"
          v-model="newSchool.course"
          placeholder="Course"
          class="w-full px-3 py-1.5 border rounded text-sm focus:border-blue-500 focus:outline-none"
        />
        <div class="flex flex-col sm:flex-row gap-2">
          <input
            type="date"
            v-model="newSchool.start_date"
            class="flex-1 px-3 py-1.5 border rounded text-sm focus:border-blue-500 focus:outline-none"
          />
          <input
            type="date"
            v-model="newSchool.end_date"
            class="flex-1 px-3 py-1.5 border rounded text-sm focus:border-blue-500 focus:outline-none"
          />
        </div>
      </div>

      <div class="flex justify-end gap-2">
        <button 
          @click="cancelNewSchool" 
          class="px-4 py-1.5 text-sm text-gray-600 hover:text-gray-800 transition-colors"
        >
          Cancel
        </button>
        <button 
          @click="saveNewSchool" 
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
import schoolImage from '@/assets/school.png'
import { getUserData } from '@/utils/user'
import { formatDate } from '@/utils/dateTime'
import NotificationToast from '@/components/Reusables/NotificationToast.vue'
import { useNotification } from '@/composables/useNotification'

const userData = getUserData()
const { notification, showNotification } = useNotification()

const editingIndex = ref([])
const addingNew = ref(false)

const newSchool = ref({
  name: '',
  course: '',
  start_date: '',
  end_date: '',
})

const editSchool = index => {
  if (!editingIndex.value.includes(index)) {
    editingIndex.value.push(index)
  }
}

const saveSchool = async index => {
  try {
    const school = { ...userData.value.schools[index] }
    const dataToSave = {
      id: school.id,
      name: school.name,
      course: school.course,
      start_date: school.start_date,
      end_date: school.end_date,
    }
    await axiosInstance.put(`/api/edit-school/${school.id}`, dataToSave)
    localStorage.setItem('user_data', JSON.stringify(userData.value))
    showNotification('Education details updated successfully!')
    editingIndex.value = editingIndex.value.filter(i => i !== index)
  } catch (error) {
    console.error('Failed to update education:', error)
    showNotification(
      error.response?.data?.message || 'Failed to update education details',
      'error'
    )
  }
}

const cancelEdit = index => {
  editingIndex.value = editingIndex.value.filter(i => i !== index)
}

const addNewSchool = () => {
  addingNew.value = true
}

const saveNewSchool = async () => {
  try {
    const dataToSave = { ...newSchool.value }
    const response = await axiosInstance.post('/api/add-school', dataToSave)
    userData.value.schools.push(response.data.school)
    localStorage.setItem('user_data', JSON.stringify(userData.value))
    showNotification('New education added successfully!')
    addingNew.value = false
    newSchool.value = {
      name: '',
      course: '',
      start_date: '',
      end_date: '',
    }
  } catch (error) {
    console.error('Failed to add new education:', error)
    showNotification(
      error.response?.data?.message || 'Failed to add new education',
      'error'
    )
  }
}

const cancelNewSchool = () => {
  addingNew.value = false
  newSchool.value = {
    name: '',
    course: '',
    start_date: '',
    end_date: '',
  }
}

const deleteSchool = async (id, index) => {
  try {
    await axiosInstance.delete(`/api/delete-school/${id}`)
    userData.value.schools.splice(index, 1)
    localStorage.setItem('user_data', JSON.stringify(userData.value))
    showNotification('Education deleted successfully!', 'error')
  } catch (error) {
    console.error('Failed to delete education:', error)
    showNotification(
      error.response?.data?.message || 'Failed to delete education',
      'error'
    )
  }
}
</script>

<style scoped>
/* Add any custom styling here */
</style>
