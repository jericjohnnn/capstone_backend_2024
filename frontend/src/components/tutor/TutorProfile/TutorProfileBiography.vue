<template>
  <div class="space-y-3">
    <NotificationToast 
      :show="notification.show"
      :message="notification.message"
      :type="notification.type"
    />

    <div class="flex justify-between items-center">
      <h3 class="font-semibold">Biography</h3>
      <button 
        @click="startEditing" 
        v-if="!isEditing"
        class="text-blue-600 text-sm hover:text-blue-700 transition-colors"
      >
        Edit
      </button>
    </div>

    <!-- Display Mode -->
    <div v-if="!isEditing" class="space-y-2">
      <div v-if="!userData.biography" class="text-sm text-gray-500 italic">
        No biography provided
      </div>
      <div v-else class="relative">
        <p 
          class="text-sm text-gray-700"
          :class="{ 'line-clamp-3': !showFullBio }"
        >
          {{ userData.biography }}
        </p>
        <div 
          v-if="bioIsLong"
          class="mt-1"
        >
          <button
            @click="showFullBio = !showFullBio"
            class="text-blue-600 text-sm hover:text-blue-700 transition-colors"
          >
            {{ showFullBio ? 'See less' : 'See more' }}
          </button>
        </div>
      </div>
    </div>

    <!-- Edit Mode -->
    <div v-else class="space-y-3">
      <textarea
        v-model="editedBiography"
        class="w-full p-3 border rounded-lg text-sm min-h-[120px] focus:border-blue-500 focus:outline-none resize-y"
        placeholder="Write your biography here..."
      ></textarea>
      
      <div class="flex justify-end gap-2">
        <button
          @click="cancelEdit"
          class="px-4 py-1.5 text-sm text-gray-600 hover:text-gray-800 transition-colors"
        >
          Cancel
        </button>
        <button
          @click="saveBiography"
          class="px-4 py-1.5 text-sm bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors"
        >
          Save
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import axiosInstance from '@/axiosInstance'
import { getUserData } from '@/utils/user'
import NotificationToast from '@/components/Reusables/NotificationToast.vue'
import { useNotification } from '@/composables/useNotification'

const userData = getUserData()
const isEditing = ref(false)
const editedBiography = ref(userData.value.biography || '')
const showFullBio = ref(false)
const { notification, showNotification } = useNotification()

// Compute if biography is long enough to need truncation
const bioIsLong = computed(() => {
  if (!userData.value.biography) return false
  return userData.value.biography.length > 150
})

const startEditing = () => {
  editedBiography.value = userData.value.biography || ''
  isEditing.value = true
}

const saveBiography = async () => {
  try {
    await axiosInstance.post('/api/edit-details', {
      biography: editedBiography.value,
    })
    userData.value.biography = editedBiography.value
    localStorage.setItem('user_data', JSON.stringify(userData.value))
    showNotification('Biography updated successfully!')
    isEditing.value = false
  } catch (error) {
    console.error('Failed to update biography:', error)
    showNotification(
      error.response?.data?.message || 'Failed to update biography',
      'error'
    )
  }
}

const cancelEdit = () => {
  isEditing.value = false
  showFullBio.value = false
  editedBiography.value = userData.value.biography || ''
}
</script>

<style>
.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
