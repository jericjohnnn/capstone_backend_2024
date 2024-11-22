<template>
  <div class="flex flex-col">
    <div class="mb-4 ml-6">
      <div class="flex flex-col flex-grow">
        <div class="flex flex-col w-[68rem] space-y-2">
          <!-- Header Row with Columns -->
          <div class="flex font-bold text-gray-700 bg-gray-200 justify-between rounded-md border-b py-2">
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
            <div class="w-24 text-center">Actions</div>
          </div>

          <!-- Scrollable Tutor Data Rows Container -->
          <div class="overflow-y-auto max-h-[400px] w-[69rem]">
            <div v-for="tutor in filteredTutors" :key="tutor.id" class="flex text-gray-600 justify-between py-2 w-full border-2 rounded-md bg-gray-100">
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

              <div class="w-28 text-center">{{ tutor.approval_status }}</div>
              <div class="w-24 text-center">
                <div v-if="tutor.approval_status === 'Pending'">
                  <button @click="acceptTutor(tutor.id)" class="bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600 mb-1">Accept</button>
                  <button @click="rejectTutor(tutor.id)" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">Reject</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import axiosInstance from '@/axiosInstance';

const tutors = ref([]);
const currentTab = ref('all');
const props = defineProps(['searchQuery']); // Receive search query prop

// Fetch tutors without pagination
async function fetchTutors(query = '') {
  try {
    const response = await axiosInstance.get(`/api/admin/all-tutors?search=${query}`);
    tutors.value = response.data.all_tutors;  // Fetch all tutors, no pagination
  } catch (error) {
    console.error('Error fetching tutors:', error);
  }
}

// Watch for search query changes
watch(() => props.searchQuery, (newQuery) => {
  fetchTutors(newQuery);  // Make sure search is included in API call
});

// Filter tutors based on the selected tab and search query
const filteredTutors = computed(() => {
  let filtered = tutors.value;

  // Apply local filtering based on the search query
  if (props.searchQuery) {
    const query = props.searchQuery.toLowerCase();
    filtered = filtered.filter(tutor =>
      tutor.first_name.toLowerCase().includes(query) ||
      tutor.last_name.toLowerCase().includes(query) ||
      tutor.address.toLowerCase().includes(query) ||
      tutor.contact_number.includes(query)
    );
  }

  // Apply the tab filter if necessary
  if (currentTab.value !== 'all') {
    filtered = filtered.filter(tutor => tutor.approval_status === capitalize(currentTab.value));
  }

  return filtered;
});

// Accept a tutor
async function acceptTutor(tutorId) {
  try {
    await axiosInstance.patch(`/api/admin/approval-status/${tutorId}`, {
      approval_status: 'Accepted',
    });
    updateTutorStatus(tutorId, 'Accepted');
  } catch (error) {
    console.error('Error accepting tutor:', error);
  }
}

// Reject a tutor
async function rejectTutor(tutorId) {
  try {
    await axiosInstance.patch(`/api/admin/approval-status/${tutorId}`, {
      approval_status: 'Rejected',
    });
    updateTutorStatus(tutorId, 'Rejected');
  } catch (error) {
    console.error('Error rejecting tutor:', error);
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

// Capitalize function for tab filtering
function capitalize(string) {
  return string.charAt(0).toUpperCase() + string.slice(1);
}

// Fetch tutors when the component is mounted
onMounted(() => fetchTutors(props.searchQuery || ''));
</script>
