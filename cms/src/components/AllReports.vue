<template>
  <div class=" p-4">
    <!-- Breadcrumb-like tabs -->
    <div class="flex space-x-2 mb-4">
      <div @click="showListView" :class="{
        'text-blue-600 font-bold underline': currentView === 'list',
      }" class="text-gray-600 ml-2">
        Reports List
      </div>
      <div v-if="currentView === 'details'" @click="showDetailsView" :class="{
        'text-blue-600 font-bold underline': currentView === 'details',
      }" class="text-gray-600">
        <span>//</span>
        Report Details
      </div>
    </div>

    <!-- Content based on view -->
    <div v-if="currentView === 'list'">
      <div v-if="loading" class="text-center">Loading reports...</div>
      <div v-else-if="reports.length === 0" class="text-center">
        No reports available.
      </div>
      <div v-else class="space-y-2 mx-2 h-[450px] overflow-y-auto">
        <div v-for="report in filteredReports" :key="report.id" class="bg-red-200 p-4 rounded shadow">
          <div class="flex flex-row gap-x-4">
            <img :src="report.complainant_profile_image" alt="Profile" class="w-16 h-16 rounded-full mb-2" />
            <div class="mr-auto">
              <h2 class="font-semibold" :class="{
                'text-blue-600': report.complainant_user_type === 'Tutor',
                'text-green-600': report.complainant_user_type === 'Student',
              }">
                {{ report.complainant_user_type }}
              </h2>
              <h2 class="font-semibold">
                {{ report.complainant_name }}<span class="font-normal"> has filed a complaint</span>
              </h2>
            </div>
            <div class="flex items-center">
              <button @click="goToReportDetails(report)" class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600 mb-1">
                View Report
              </button>
              <p class="ml-4">Status: {{ report.report_status }}</p>
            </div>
          </div>
        </div>
        <div class="mt-4">
          <button @click="fetchReports" class="bg-blue-500 text-white px-4 py-2 rounded">
            Refresh Reports
          </button>
        </div>
      </div>
    </div>

    <!-- Include the ReportDetails component for the details view -->
    <ReportDetails v-if="currentView === 'details'" :selectedReport="selectedReport" :updateReportStatus="updateReportStatus" :showListView="showListView" />
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import axiosInstance from '@/axiosInstance'
import ReportDetails from './ReportDetails.vue'

const reports = ref([])
const loading = ref(true)
const currentView = ref('list')
const selectedReport = ref(null)

const props = defineProps({
  searchQuery: {
    type: String,
    default: ''
  }
})

const fetchReports = async () => {
  loading.value = true
  try {
    const response = await axiosInstance.get('/api/admin/all-reports')  // This should return all reports
    reports.value = response.data.complainant_report  // No need for pagination anymore
  } catch (error) {
    console.error('Error fetching reports:', error)
  } finally {
    loading.value = false
  }
}

const goToReportDetails = async (report) => {
  loading.value = true
  try {
    const response = await axiosInstance.get(`/api/admin/${report.id}`)
    selectedReport.value = response.data.report_data
    currentView.value = 'details'
  } catch (error) {
    console.error('Error fetching report details:', error)
  } finally {
    loading.value = false
  }
}

const updateReportStatus = async (reportId, reportStatus, offenseStatus) => {
  try {
    await axiosInstance.patch(`/api/admin/report-status/${reportId}`, {
      status: reportStatus,
      offense_status: offenseStatus
    })
    const report = reports.value.find(r => r.id === reportId)
    if (report) {
      report.report_status = reportStatus
      report.offender_offense_status = offenseStatus
    }

    if (selectedReport.value && selectedReport.value.report_id === reportId) {
      selectedReport.value.report_status = reportStatus
      selectedReport.value.offender_offense_status = offenseStatus
    }
  } catch (error) {
    console.error('Error updating report status:', error)
  }
}

const showListView = () => {
  currentView.value = 'list'
}

const filteredReports = computed(() => {
  if (!props.searchQuery) return reports.value
  return reports.value.filter(report => 
    report.complainant_name.toLowerCase().includes(props.searchQuery.toLowerCase())
  )
})

onMounted(fetchReports)
</script>
