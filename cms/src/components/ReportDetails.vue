<template>
  <div v-if="selectedReport" class="py-4 mx-2 bg-white shadow rounded">
    <div class="flex flex-col sm:flex-row gap-4">
      <!-- Left section for Complainant and Offender details -->
      <div class="bg-blue-300 rounded-sm w-full sm:w-[400px] p-4 space-y-6">
        <div>
          <div class="mb-6">
            <div class="flex items-center mb-4 pb-4 border-b">
              <img :src="selectedReport.complainant_profile_image" alt="Complainant Profile" class="w-14 h-14 rounded-full mr-4" />
              <div>
                <p><strong>Complainant:</strong> {{ selectedReport.complainant_name }}</p>
                <p><strong>User Type:</strong> {{ selectedReport.complainant_user_type }}</p>
              </div>
            </div>
          </div>

          <div class="mb-6">
            <div class="flex border-b pb-4 mb-4">
              <img :src="selectedReport.offender_profile_image" alt="Offender Profile" class="w-14 h-14 rounded-full mr-4" />
              <div>
                <p><strong>Offender:</strong> {{ selectedReport.offender_name }}</p>
                <p><strong>User Type:</strong> {{ selectedReport.offender_user_type }}</p>
                <p><strong>Offense Status:</strong> {{ selectedReport.offender_offense_status }}</p>
                <p><strong>Contact Number:</strong> {{ selectedReport.offender_contact_number }}</p>
                <p><strong>Address:</strong> {{ selectedReport.offender_address }}</p>
              </div>
            </div>
            <div class="mt-10">
              <p><strong>Status:</strong> {{ selectedReport.report_status }}</p>
            </div>
          </div>
        </div>
        <div v-if="selectedReport.report_status === 'Pending'" class="space-x-2">
          <div class="flex flex-row space-x-2">
            <button @click="updateReportStatus(selectedReport.report_id, 'Resolved', 'Warned')"
              class="bg-red-800 text-white px-4 py-2 rounded hover:bg-red-900 w-full">
              Warn
            </button>
            <button @click="updateReportStatus(selectedReport.report_id, 'Resolved', 'Penalized')"
              class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 w-full">
              Penalize
            </button>
            <button @click="updateReportStatus(selectedReport.report_id, 'Resolved', 'Banned')"
              class="bg-red-400 text-white px-4 py-2 rounded hover:bg-red-500 w-full">
              Ban
            </button>
          </div>
        </div>
      </div>

      <!-- Right section for Report message and title -->
      <div class="flex-1 ">
        <div class="space-y-2">
          <div class="border border-black p-2 flex flex-col rounded-sm bg-red-200">
            <p class="text-md font-semibold flex justify-start mr-2">Subject:</p>
            <p class="text-md font-normal mx-auto">{{ selectedReport.report_title }}</p>
          </div>
          <div>
            <div class="border border-black p-2 flex flex-col h-[300px] rounded-sm bg-red-200">
              <p class="text-md font-semibold flex justify-start mr-2 mb-2">Message:</p>
              <p class="text-md font-normal mx-auto px-4">{{ selectedReport.report_message }}</p>
            </div>
          </div>
          <div class="flex justify-end">
            <button @click="showListView" class="bg-gray-400 text-black px-6 py-2 rounded hover:bg-gray-600">
              Done
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div v-else class="text-center">Loading report details...</div>
</template>

<script setup>
import { ref, defineProps } from 'vue'

const props = defineProps({
  selectedReport: {
    type: Object,
    required: true
  },
  updateReportStatus: {
    type: Function,
    required: true
  },
  showListView: {
    type: Function,
    required: true
  }
})
</script>

<style scoped>
/* Add custom styles if needed */
</style>
