<template>
  <div class="flex flex-col">
    <div class="mb-4 ml-6">
      <div class="flex flex-col flex-grow">
        <div class="overflow-x-auto">
          <div class="flex flex-col w-[68rem] space-y-2">
            <!-- Header Row with Columns -->
            <div class="flex font-bold text-gray-700 bg-blue-300 justify-between rounded-md border-b py-2">
              <div class="w-10 text-center">ID</div>
              <div class="w-28 text-center">Full Name</div>
              <div class="w-16 text-center">Profile Image</div>
              <div class="w-32 text-center">Address</div>
              <div class="w-32 text-center">Contact Number</div>
              <div class="w-28 text-center">Birthdate</div>
              <div class="w-28 text-center">Offense Status</div>
              <div class="w-20 text-center">Actions</div>
            </div>

            <!-- Scrollable Students Data Rows -->
            <div class="overflow-y-auto max-h-[400px] w-[69rem]">
              <div v-for="student in filteredStudents" :key="student.user_id" class="flex text-gray-600 justify-between py-2 w-full border-2 rounded-md bg-blue-100">
                <div class="w-10 text-center">{{ student.user_id }}</div>
                <div class="w-28 text-center">{{ student.first_name }} {{ student.last_name }}</div>
                <div class="w-16 text-center">
                  <img :src="student.profile_image" alt="Profile" class="w-8 h-8 rounded-full mx-auto" />
                </div>
                <div class="w-32 text-center">{{ student.address }}</div>
                <div class="w-32 text-center">{{ student.contact_number }}</div>
                <div class="w-28 text-center">{{ formatDate(student.birthdate) }}</div>
                <div class="w-28 text-center font-semibold">{{ student.offense_status }}</div>
                <div class="w-20 text-center">
                  <button class="bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600 mb-1">Edit</button>
                  <button class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">Delete</button>
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
import { ref, onMounted, computed } from 'vue';
import axiosInstance from '@/axiosInstance'; // Use the axios instance

const students = ref([]);  // Initialize as an empty array
const { searchQuery } = defineProps({ searchQuery: String });
// Fetch students function that gets all student data
async function fetchStudents() {
  try {
    const response = await axiosInstance.get('/api/admin/all-students'); // Use axiosInstance for the API call
    students.value = response.data.all_students; // Set the fetched students data
  } catch (error) {
    console.error('Error fetching students:', error); // Log any errors
  }
}

// Helper function to format the birthdate
function formatDate(dateString) {
  return new Date(dateString).toLocaleDateString(); // Formats the date to a readable format
}

const filteredStudents = computed(() => {
  return students.value.filter(student => student.offense_status === 'Penalized' &&
    (student.first_name.toLowerCase().includes(searchQuery.toLowerCase()) ||
    student.last_name.toLowerCase().includes(searchQuery.toLowerCase()) ||
    student.address.toLowerCase().includes(searchQuery.toLowerCase()) ||
    student.contact_number.includes(searchQuery))
  );
});



// Fetch students when the component mounts
onMounted(fetchStudents);
</script>

<style scoped>
/* Add any necessary styling */
</style>
