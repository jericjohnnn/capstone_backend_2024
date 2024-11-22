<template>
  <div>
    <NotificationToast 
      :show="notification.show"
      :message="notification.message"
      :type="notification.type"
    />
    
    <h2 class="flex justify-between items-center mb-3">
      <span class="font-semibold">Subjects</span>
      <button @click="toggleEditMode" class="text-blue-500 text-sm hover:text-blue-600">
        {{ isEditing ? 'Cancel' : 'Edit' }}
      </button>
    </h2>

    <!-- Subjects Display -->
    <div class="flex flex-wrap gap-2">
      <span
        v-for="subject in subjects"
        :key="subject.id"
        class="flex items-center px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full"
      >
        {{ subject.name }}
        <button 
          v-if="isEditing" 
          @click="removeSubject(subject.id)" 
          class="ml-2 text-red-500 hover:text-red-600"
        >
          &times;
        </button>
      </span>
    </div>

    <!-- Edit Mode -->
    <div v-if="isEditing" class="mt-4 space-y-3">
      <div class="flex flex-col sm:flex-row gap-2">
        <select 
          v-model="selectedSubject" 
          class="flex-1 px-3 py-1.5 border rounded text-sm focus:outline-none focus:border-blue-500"
        >
          <option value="" disabled selected>Select a subject</option>
          <option 
            v-for="option in availableSubjects" 
            :key="option.id" 
            :value="option.id"
          >
            {{ option.name }}
          </option>
        </select>
        <button 
          @click="addSubject" 
          class="w-full sm:w-auto px-4 py-1.5 bg-blue-500 text-white text-sm rounded hover:bg-blue-600 transition-colors"
        >
          Add
        </button>
      </div>
      
      <div class="flex justify-end">
        <button 
          @click="saveChanges" 
          class="w-full sm:w-auto px-6 py-1.5 bg-green-500 text-white text-sm rounded hover:bg-green-600 transition-colors"
        >
          Save Changes
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axiosInstance from '@/axiosInstance';
import { getUserData } from '@/utils/user'
import NotificationToast from '@/components/Reusables/NotificationToast.vue'
import { useNotification } from '@/composables/useNotification'

const userData = getUserData()
const { notification, showNotification } = useNotification()
const subjects = ref(userData.value.subjects || []);
const isEditing = ref(false);
const originalSubjects = ref([]);
const availableSubjects = ref([]);
const selectedSubject = ref('');

const fetchSubjects = async () => {
  try {
    const { data } = await axiosInstance.get('/api/subjects');
    availableSubjects.value = data.subjects;
  } catch (error) {
    console.error('Error fetching subjects:', error);
    showNotification(
      'Failed to load available subjects',
      'error'
    )
  }
};

const toggleEditMode = () => {
  if (isEditing.value) {
    subjects.value = [...originalSubjects.value];
    selectedSubject.value = '';
  } else {
    originalSubjects.value = [...subjects.value];
  }
  isEditing.value = !isEditing.value;
};

const removeSubject = (subjectId) => {
  subjects.value = subjects.value.filter(subject => subject.id !== subjectId);
};

const addSubject = () => {
  if (!selectedSubject.value) return;
  
  const subjectToAdd = availableSubjects.value.find(subject => subject.id === selectedSubject.value);
  if (subjectToAdd && !subjects.value.some(subject => subject.id === subjectToAdd.id)) {
    subjects.value.push(subjectToAdd);
    selectedSubject.value = '';
  }
};

const saveChanges = async () => {
  try {
    const response = await axiosInstance.put('/api/edit-subjects', {
      subjects: subjects.value.map(subject => subject.id)
    });
    
    if (response.status === 200) {
      originalSubjects.value = [...subjects.value];
      userData.value.subjects = subjects.value;
      localStorage.setItem('user_data', JSON.stringify(userData.value));
      isEditing.value = false;
      showNotification('Subjects updated successfully!');
    }
  } catch (error) {
    console.error('Error saving subjects:', error);
    subjects.value = [...originalSubjects.value];
    showNotification(
      error.response?.data?.message || 'Failed to update subjects',
      'error'
    );
  }
};

onMounted(fetchSubjects);
</script>

<style scoped>
/* Add any additional styles here */
</style>
