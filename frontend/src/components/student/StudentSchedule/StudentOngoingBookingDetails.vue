<template>
  <main class="bg-blue-50">
    <NotificationToast
      :show="notification.show"
      :message="notification.message"
      :type="notification.type"
    />
    <PopUpModal
      v-model:toggleModal="isReportModalOpen"
      @openValue="handleModalOpen"
      @cancelButtonValue="handleModalCancel"
      @mainButtonValue="handleReportSubmit"
    >
      <template #modalTitle> Report Tutor </template>
      <template #mainContent>
        <ReportForm
          @update:reportReason="handleReportReasonUpdate"
          @update:reportMessage="handleReportMessageUpdate"
        />
      </template>
      <template #mainButton> Submit Report </template>
      <template #cancelButton> Cancel </template>
    </PopUpModal>

    <SideBar>
      <main
        class="container grid grid-rows-[auto,auto,1fr] grid-cols-1 md:grid-rows-[auto,1fr] md:grid-cols-5 py-5 gap-4 min-h-screen"
      >
        <!-- Breadcrumb -->
        <div class="col-span-1 md:col-span-5">
          <BreadCrumb
            :breadcrumbs="[
              { label: 'Schedule', route: '/student/schedule' },
              { label: 'Booking Details', route: '' },
            ]"
          />
        </div>

        <!-- Overview Section -->
        <div
          class="md:row-span-1 md:col-span-2 bg-white rounded-lg py-3 md:overflow-auto shadow-sm scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-200"
          :class="!bookDetails ? 'md:overflow-hidden' : ''"
        >
          <h2 class="text-xl font-medium text-center">Overview</h2>
          <div
            v-if="!bookDetails"
            class="flex min-h-40 justify-center items-center md:h-full"
          >
            <LoaderSpinner />
          </div>
          <div v-if="bookDetails" class="flex flex-col gap-4 px-2 md:px-5 py-4">
            <!-- Profile Section -->
            <div class="flex items-center gap-2">
              <div class="shrink-0">
                <img
                  class="h-14 w-14 rounded-full"
                  :src="bookDetails.tutor.profile_image || defaultProfileImage"
                  alt="profile image"
                />
              </div>
              <div class="w-full">
                <div class="flex justify-between rounded">
                  <p class="font-medium text-lg">
                    {{ bookDetails.tutor.first_name }}
                    {{ bookDetails.tutor.last_name }}
                  </p>
                  <button
                    @click="openReportModal"
                    class="border-2 border-blue-600 text-blue-600 rounded-md px-2 py-1 text-sm"
                  >
                    Report
                  </button>
                </div>
              </div>
            </div>

            <!-- Details -->
            <!-- STATUS -->
            <div class="flex flex-col gap-3">
              <div class="flex items-center gap-2">
                <h2 class="font-medium w-full">Status:</h2>
                <span
                  class="bg-blue-600  text-white px-3 py-1 rounded-full text-sm text-center w-full"
                >
                  {{ bookDetails.status || 'pending' }}
                </span>
              </div>
              <!-- SUBJECT -->
              <div class="flex items-center gap-2">
                <h2 class="font-medium w-full">Subject:</h2>
                <span
                  class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm w-full text-center"
                >
                  {{ bookDetails.subject }}
                </span>
              </div>

              <!-- LEARNING PREFERENCE -->
              <div class="flex items-center gap-2">
                <h2 class="font-medium w-full">Learning Preference:</h2>
                <span
                  class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm w-full text-center"
                >
                  {{ bookDetails.learning_mode || 'online' }}
                </span>
              </div>

              <!-- ONLINE MEETING PLATFORM OR LOCATION -->
              <div class="flex items-center gap-2">
                <h2 class="font-medium w-full shrink">
                  {{
                    bookDetails.learning_mode === 'Online'
                      ? 'Online Meeting Platform:'
                      : 'Location:'
                  }}
                </h2>
                <span
                  class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full w-full text-sm text-center"
                >
                  {{
                    bookDetails.learning_mode === 'Online'
                      ? bookDetails.online_meeting_platform
                      : bookDetails.location
                  }}
                </span>
              </div>

              <!-- CONTACT INFO -->
              <div class="flex items-center gap-2">
                <h2 class="font-medium w-full">Contact Info:</h2>
                <span
                  class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm w-full text-center"
                >
                  {{ bookDetails.contact_number }}
                </span>
              </div>

              <!-- Date and Time -->
              <div v-if="bookDetails.messages?.length">
                <h2 class="font-medium w-full mb-2">Date & Time:</h2>
                <div
                  v-for="dateTime in bookDetails.messages[
                    bookDetails.messages.length - 1
                  ].dates"
                  :key="dateTime.id"
                  class="text-blue-600 w-full"
                >
                  {{ formatDate(dateTime.start_time) }}
                  {{ formatTo12Hour(extractTimeFromISO(dateTime.start_time)) }}
                  -
                  {{ formatTo12Hour(extractTimeFromISO(dateTime.end_time)) }}
                </div>
              </div>
              <div>
                <button
                  :disabled="isExpired"
                  @click="updateBookingStatus('Completed')"
                  class="bg-blue-400 w-full p-2 rounded-md text-white hover:bg-blue-500 transition-colors"
                >
                  Mark as Complete
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Messages Section -->
        <div
          class="row-span-1 md:col-span-3 md:max-h-[calc(100vh-4.8rem)] md:overflow-auto bg-white rounded-lg p-3 shadow-sm"
        >
          <div
            v-if="!bookDetails"
            class="flex min-h-40 justify-center items-center md:h-full"
          >
            <LoaderSpinner />
          </div>
          <div v-if="bookDetails" class="md:px-3">
            <h2 class="text-xl font-medium text-center pb-2">Messages</h2>
            <div class="space-y-6">
              <div
                v-for="(message, index) in bookDetails.messages"
                :key="message.id"
                class="py-3 rounded-lg border"
                :class="
                  index % 2 === 0
                    ? 'bg-blue-100 border-blue-300'
                    : 'bg-slate-100 border-slate-300'
                "
              >
                <h3 class="text-base text-center font-medium">
                  {{
                    index % 2 === 0
                      ? 'Your message'
                      : bookDetails.tutor.first_name +
                        ' ' +
                        bookDetails.tutor.last_name +
                        "'s message"
                  }}
                </h3>
                <p class="pl-2 text-xs">Title:</p>
                <div class="pb-2 px-2">
                  <div
                    class="p-2 border rounded min-h-20 overflow-auto"
                    :class="
                      index % 2 === 0
                        ? 'border-blue-400 bg-white/40'
                        : 'border-slate-400 bg-white/40'
                    "
                  >
                    <p>{{ message.title }}</p>
                  </div>
                </div>
                <p class="pl-2 text-xs">Message:</p>
                <div class="pb-2 px-2">
                  <div
                    class="p-2 border rounded min-h-40"
                    :class="
                      index % 2 === 0
                        ? 'border-blue-400 bg-white/40'
                        : 'border-slate-400 bg-white/40'
                    "
                  >
                    {{ message.message }}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!--  -->
      </main>
    </SideBar>
    <FooterSection class="md:hidden" />
    <HelpButton />
  </main>
</template>

<script setup>
import FooterSection from '@/sections/FooterSection.vue'
import BreadCrumb from '@/components/BreadCrumb.vue'
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import SideBar from '@/components/SideBar.vue'
import HelpButton from '@/components/HelpButton.vue'
import axiosInstance from '@/axiosInstance'
import {
  formatDate,
  extractTimeFromISO,
  formatTo12Hour,
} from '@/utils/dateTime'
import LoaderSpinner from '@/components/Reusables/LoaderSpinner.vue'
import NotificationToast from '@/components/Reusables/NotificationToast.vue'
import { useNotification } from '@/composables/useNotification'
import PopUpModal from '@/components/Reusables/PopUpModal.vue'
import ReportForm from '@/components/Reusables/ReportForm.vue'

const route = useRoute()
const router = useRouter()
const defaultProfileImage =
  'data:image/svg+xml;base64,' +
  btoa(`
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
    <circle cx="12" cy="12" r="11" fill="white" stroke="#E5E7EB" stroke-width="2"/>
    <circle cx="12" cy="8" r="3.5" fill="#9CA3AF"/>
    <path d="M12 12.5c-3 0-5.5 1.5-7 3.5 1.5 3 4 5 7 5s5.5-2 7-5c-1.5-2-4-3.5-7-3.5z" fill="#9CA3AF"/>
  </svg>`)

// check booking if its expired
const isExpired = ref(true)

const checkIfExpired = () => {
  const dates =
    bookDetails.value.messages[bookDetails.value.messages.length - 1].dates

  const latestEndTime = dates.reduce((latest, date) => {
    const currentEndTime = new Date(date.end_time)
    return currentEndTime > latest ? currentEndTime : latest
  }, new Date(dates[0].end_time))

  const currentDate = new Date()

  if (latestEndTime < currentDate) {
    isExpired.value = !isExpired.value
  }
}

const updateBookingStatus = async status => {
  try {
    await axiosInstance.patch(
      `/api/update-student-booking/${route.params.bookId}`,
      {
        status: status,
      },
    )
    showNotification('Booking marked as completed successfully!')
    setTimeout(() => {
      router.push({ path: '/student/requests?tab=completed' })
    }, 1500)
  } catch (err) {
    console.error('Error updating booking status:', err)
    showNotification(
      err.response?.data?.message || 'Failed to update booking status',
      'error',
    )
  }
}

const bookDetails = ref(null)

const fetchBookingDetails = async bookId => {
  try {
    const response = await axiosInstance.get(
      `/api/student-book-details/${bookId}`,
    )
    bookDetails.value = response.data.book_details
    checkIfExpired()
  } catch (err) {
    console.error('Error fetching booking details:', err)
  }
}

const { notification, showNotification } = useNotification()

// FOR REPORT
const isReportModalOpen = ref(false)
const reportMessage = ref('')
const reportReason = ref('')

const handleReportReasonUpdate = value => {
  reportReason.value = value
}

const handleReportMessageUpdate = value => {
  reportMessage.value = value
}

const openReportModal = () => {
  isReportModalOpen.value = true
}

const handleModalOpen = value => {
  isReportModalOpen.value = value
}

const handleModalCancel = () => {
  isReportModalOpen.value = false
  reportReason.value = ''
  reportMessage.value = ''
}

const handleReportSubmit = async () => {
  if (!reportReason.value) {
    showNotification('Please select a reason for reporting', 'error')
    return
  }

  if (!reportMessage.value.trim()) {
    showNotification('Please provide details about your report', 'error')
    return
  }

  const reportData = {
    tutor_offender_id: bookDetails.value.tutor.id,
    title: reportReason.value,
    message: reportMessage.value,
  }

  try {
    await axiosInstance.post('api/create-report', reportData)
    showNotification('Report submitted successfully', 'success')
    isReportModalOpen.value = false

    // Reset form
    reportReason.value = ''
    reportMessage.value = ''
  } catch (error) {
    console.error('Error submitting report:', error)
    showNotification(
      error.response?.data?.message ||
        'Failed to submit report. Please try again.',
      'error',
    )
  }
}

onMounted(() => {
  const initialBookId = route.params.bookId
  if (initialBookId) {
    fetchBookingDetails(initialBookId)
  }
})
</script>
<style>
button:disabled {
  background-color: #d3d3d3;
  color: #888888;
  cursor: not-allowed;
  opacity: 0.6;
  pointer-events: none;
}

button:disabled:hover {
  background-color: #d3d3d3;
  color: #888888;
}
</style>
