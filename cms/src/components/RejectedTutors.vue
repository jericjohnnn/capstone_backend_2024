<template>
  <div class="flex flex-col">
    <div class="mb-4 ml-6">
      <div class="flex flex-col flex-grow">
        <div class="flex flex-col w-[68rem] space-y-2">
          <!-- Header Row with Columns -->
          <div class="flex font-bold text-gray-700 bg-red-200 justify-between rounded-md border-b py-2">
            <div class="w-10 text-center">ID</div>
            <div class="w-28 text-center">Name</div>
            <div class="w-16 text-center">Profile Image</div>
            <div class="w-32 text-center">Address</div>
            <div class="w-36 text-center">Contact Number</div>
            <div class="w-28 text-center">Birthdate</div>
            <div class="w-20 text-center">Gender</div>
            <div class="w-32 text-center">Course And Year</div>
            <div class="w-28 text-center">School ID Number</div>
            <div class="w-20 text-center">Tutor Rate</div>
            <div class="w-28 text-center">Contacted Status</div>
            <div class="w-28 text-center">Approval Status</div>
          </div>

          <!-- Scrollable Rejected Tutors Data Rows Container -->
          <div class="overflow-y-auto max-h-[400px] w-[69rem]">
            <div v-for="tutor in filteredTutors" :key="tutor.id" class="flex text-gray-600 justify-between py-2 w-full border-2 rounded-md bg-red-100">
              <div class="w-10 text-center">{{ tutor.user_id }}</div>
              <div class="w-28 text-center">{{ tutor.first_name }} {{ tutor.last_name }}</div>
              <div class="w-16 text-center">
                <img :src="tutor.profile_image" alt="Profile" class="w-8 h-8 rounded-full mx-auto" />
              </div>
              <div class="w-36 text-center">{{ tutor.address }}</div>
              <div class="w-32 text-center">{{ tutor.contact_number }}</div>
              <div class="w-28 text-center">{{ formatDate(tutor.birthdate) }}</div>
              <div class="w-20 text-center">{{ tutor.gender }}</div>
              <div class="w-32 text-center">{{ tutor.course }} {{ tutor.year }}</div>
              <div class="w-28 text-center">{{ tutor.school_id_number }}</div>
              <div class="w-20 text-center">{{ tutor.tutor_rate }}</div>
              <div class="w-28 text-center">
                <button
                  @click="toggleContactedStatus(tutor)"
                  :class="tutor.contacted_status ? 'bg-green-500' : 'bg-gray-500'"
                  class="text-white px-2 py-1 rounded hover:bg-opacity-75"
                >
                  {{ tutor.contacted_status ? 'Contacted' : 'Not Contacted' }}
                </button>
              </div>

              <div class="w-28 text-center text-red-600 font-semibold">{{ tutor.approval_status }}</div>
              
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import axiosInstance from '@/axiosInstance';

const tutors = ref([]); // Array to hold all tutors
const { searchQuery } = defineProps({ searchQuery: String });

const filteredTutors = computed(() => {
  return tutors.value.filter(tutor => 
    tutor.approval_status === 'Rejected' &&
    (tutor.first_name.toLowerCase().includes(searchQuery.toLowerCase()) ||
    tutor.last_name.toLowerCase().includes(searchQuery.toLowerCase()) ||
    tutor.address.toLowerCase().includes(searchQuery.toLowerCase()) ||
    tutor.contact_number.includes(searchQuery))
  );
});

// Fetch tutors function that gets all tutors and filters for rejected status
async function fetchTutors() {
  try {
    const response = await axiosInstance.get('/api/admin/all-tutors');
    tutors.value = response.data.all_tutors; // Set the fetched tutors data
  } catch (error) {
    console.error('Error fetching tutors:', error);
  }
}

// Reactivate a tutor's rejection status
async function reactivateTutor(tutorId) {
  try {
    await axiosInstance.patch(`/api/admin/approval-status/${tutorId}`, {
      approval_status: 'Pending', // Change status back to "Pending"
    });
    updateTutorStatus(tutorId, 'Pending');
  } catch (error) {
    console.error('Error reactivating tutor:', error);
  }
}

// Update tutor status locally
function updateTutorStatus(tutorId, status) {
  const tutor = tutors.value.find(t => t.id === tutorId);
  if (tutor) {
    tutor.approval_status = status;
  }
}

// Toggle contacted status
async function toggleContactedStatus(tutor) {
  try {
    const newStatus = !tutor.contacted_status;
    await axiosInstance.patch(`/api/admin/contacted-status/${tutor.id}`, {
      contacted_status: newStatus,
    });
    tutor.contacted_status = newStatus;
  } catch (error) {
    console.error('Error updating contacted status:', error);
  }
}

// Format date for display
function formatDate(dateString) {
  return new Date(dateString).toLocaleDateString();
}

// Fetch tutors when the component is mounted
onMounted(fetchTutors);
</script>
