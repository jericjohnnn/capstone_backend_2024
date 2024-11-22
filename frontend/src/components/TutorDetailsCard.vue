<template>
  <div
    class="flex flex-col bg-white border shadow-sm rounded-xl h-full hover:shadow-lg transition"
  >
    <NotificationToast 
      :show="notification.show"
      :message="notification.message"
      :type="notification.type"
    />
    <!-- Header Section with Blue Background -->
    <div
      class="bg-gradient-to-r from-blue-600 to-blue-500 p-5 rounded-t-xl flex-none"
    >
      <div class="flex justify-between items-start">
        <!-- Tutor Info -->
        <div class="flex items-center w-3/5 gap-4">
          <img
            alt="Tutor avatar"
            :src="tutor.profile_image || defaultProfileImage"
            class="w-16 h-16 shrink-0 rounded-full object-cover border-2 border-blue-300/30"
          />
          <div class="text-white">
            <h2 class="font-semibold text-xl">
              {{ tutor.first_name }} {{ tutor.last_name }}
            </h2>
            <div class="flex items-center gap-2 mt-1 text-blue-100">
              <svg
                class="w-4 h-4"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                />
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                />
              </svg>
              <p class="text-sm">{{ tutor.address }}</p>
            </div>
          </div>
        </div>

        <!-- Actions -->
        <div class="flex flex-col items-center gap-2">
          <button
            @click="goToBook"
            class="bg-white px-6 py-2 rounded-md text-blue-600 font-medium hover:bg-blue-50 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-white/50"
          >
            Book Now
          </button>
          <!-- FOR REPORT -->
          <button
            class="underline text-blue-100 text-sm hover:text-white transition-colors duration-200 flex items-center gap-1"
            @click="openReportModal"
          >
            Report tutor
          </button>

          <PopUpModal
            v-model:toggleModal="isReportModalOpen"
            @openValue="handleModalOpen"
            @cancelButtonValue="handleModalCancel"
            @mainButtonValue="handleReportSubmit"
          >
            <template #modalTitle> Report Tutor </template>
            <template #mainContent>
              <ReportForm @update:reportReason="handleReportReasonUpdate" @update:reportMessage="handleReportMessageUpdate" />
            </template>
            <template #mainButton> Submit Report </template>
            <template #cancelButton> Cancel </template>
          </PopUpModal>
          <!-- FOR REPORT -->
        </div>
      </div>
    </div>

    <!-- Scrollable Content -->
    <div class="flex-1 overflow-y-auto p-6 space-y-8">
      <!-- Bio Section -->
      <div class="prose prose-sm max-w-none">
        <h3 class="text-lg font-semibold text-gray-900 mb-3">About</h3>
        <p class="text-gray-600 leading-relaxed">
          <span v-if="!showFullBio">{{ truncatedBio }}</span>
          <span v-else>{{ tutor.biography }}</span>
          <button
            v-if="tutor.biography?.length > 150"
            @click="showFullBio = !showFullBio"
            class="text-blue-600 font-medium ml-1 focus:outline-none hover:underline"
          >
            {{ showFullBio ? 'See less' : 'See more...' }}
          </button>
        </p>
      </div>

      <!-- Tutor Information Card -->
      <div class="prose prose-sm max-w-none">
        <h3 class="text-lg font-semibold text-gray-900 mb-3">
          Tutor Information
        </h3>

        <!-- Main Grid Container -->
        <div class="grid gap-4">
          <!-- Subjects Section -->
          <div class="bg-gray-50 p-4 rounded-lg text-center">
            <h4
              class="font-medium text-gray-900 mb-3 flex items-center justify-center gap-2"
            >
              <svg
                class="w-5 h-5 text-blue-500"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                />
              </svg>
              Subjects Offered
            </h4>
            <div class="flex flex-wrap justify-center gap-2">
              <span
                v-for="subject in tutor.subjects"
                :key="subject.id"
                class="px-3 py-1 bg-blue-100/50 text-blue-800 rounded-md text-sm font-medium"
              >
                {{ subject.name }}
              </span>
            </div>
          </div>

          <!-- Available Days -->
          <div class="bg-gray-50 p-4 rounded-lg text-center">
            <h4
              class="font-medium text-gray-900 mb-3 flex items-center justify-center gap-2"
            >
              <svg
                class="w-5 h-5 text-blue-500"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                />
              </svg>
              Available Days
            </h4>
            <div class="flex flex-wrap justify-center gap-2">
              <span
                v-for="day in availableDays"
                :key="day"
                class="px-3 py-1 bg-green-100/50 text-green-800 rounded-md text-sm font-medium"
              >
                {{ day }}
              </span>
            </div>
          </div>

          <!-- Bottom Row Grid -->
          <div class="grid md:grid-cols-2 gap-4">
            <div class="bg-gray-50 p-4 rounded-lg text-center">
              <h4
                class="font-medium text-gray-900 mb-3 flex items-center justify-center gap-2"
              >
                <svg
                  class="w-5 h-5 text-blue-500"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                  />
                </svg>
                Hours Available
              </h4>
              <p class="text-blue-600 font-semibold text-lg">
                {{ formatTo12Hour(tutor.work_days.start_time) }} -
                {{ formatTo12Hour(tutor.work_days.end_time) }}
              </p>
            </div>

            <!-- Rate Section -->
            <div class="bg-gray-50 p-4 rounded-lg text-center">
              <h4
                class="font-medium text-gray-900 mb-3 flex items-center justify-center gap-2"
              >
                <svg
                  class="w-5 h-5 text-blue-500"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                  />
                </svg>
                Rate
              </h4>
              <p class="text-blue-600 font-semibold text-lg">
                ₱{{ tutor.tutor_rate }}/hr
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Education Section -->
      <div>
        <h3
          class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2"
        >
          <svg
            class="w-5 h-5 text-blue-500"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path d="M12 14l9-5-9-5-9 5 9 5z" />
            <path
              d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"
            />
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"
            />
          </svg>
          Education
        </h3>

        <div
          v-if="!tutor.schools || tutor.schools.length === 0"
          class="text-gray-500"
        >
          <p>No education information available.</p>
        </div>

        <div v-else class="space-y-4">
          <div
            v-for="school in tutor.schools"
            :key="school.id"
            class="flex items-start p-4 rounded-lg border border-gray-100 hover:border-gray-200 transition-colors duration-200"
          >
            <img
              :src="schoolImage"
              alt="School Logo"
              class="w-12 h-12 object-cover mr-4"
            />
            <div>
              <p class="font-medium text-gray-900">
                {{ school.name }}
              </p>
              <p class="text-sm text-gray-600 mt-1">
                {{ school.course }}
              </p>
              <p class="text-sm text-gray-500 mt-1">
                {{ formatDate(school.start_date) }} -
                {{ school.end_date ? formatDate(school.end_date) : 'Present' }}
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Certificates Section -->
      <div>
        <h3
          class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2"
        >
          <svg
            class="w-5 h-5 text-blue-500"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"
            />
          </svg>
          Certifications
        </h3>

        <div
          v-if="!tutor.certificates || tutor.certificates.length === 0"
          class="text-gray-500"
        >
          <p>No certificates information available.</p>
        </div>

        <div v-else class="space-y-4">
          <div
            v-for="certificate in tutor.certificates"
            :key="certificate.id"
            class="flex items-start p-4 rounded-lg border border-gray-100 hover:border-gray-200 transition-colors duration-200"
          >
            <img
              :src="certificateImage"
              alt="Certificate Image"
              class="w-12 h-12 rounded-lg object-cover mr-4"
            />
            <div>
              <p class="font-medium text-gray-900">
                {{ certificate.title }}
              </p>
              <p class="text-sm text-gray-600 mt-1">
                {{ certificate.issuer }}
              </p>
              <p class="text-sm text-gray-500 mt-1">
                {{ formatDate(certificate.date_issued) }}
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Ratings Section -->
      <div>
        <div class="flex items-center gap-3 mb-4">
          <h3 class="text-lg font-semibold text-gray-900">Ratings</h3>
          <StarRating :rating="averageRatings" class="mt-1"></StarRating>
        </div>

        <div
          v-if="!tutor.ratings || tutor.ratings.length === 0"
          class="text-gray-500"
        >
          <p>No ratings information available.</p>
        </div>

        <div v-else>
          <RatingsCarousel
            class="w-full"
            :ratingComments="ratingComments"
          ></RatingsCarousel>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import ReportForm from './Reusables/ReportForm.vue'
import schoolImage from '@/assets/school.png'
import certificateImage from '@/assets/certificate.png'
import StarRating from './StarRating.vue'
import RatingsCarousel from './RatingsCarousel.vue'
import { computed, ref } from 'vue'
import { useRouter } from 'vue-router'
import { formatDate, formatTo12Hour } from '@/utils/dateTime'
import PopUpModal from './Reusables/PopUpModal.vue'
import axiosInstance from '@/axiosInstance'
import NotificationToast from './Reusables/NotificationToast.vue'
import { useNotification } from '@/composables/useNotification'

const defaultProfileImage =
  'data:image/svg+xml;base64,' +
  btoa(`
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
    <circle cx="12" cy="12" r="11" fill="white" stroke="#E5E7EB" stroke-width="2"/>
    <circle cx="12" cy="8" r="3.5" fill="#9CA3AF"/>
    <path d="M12 12.5c-3 0-5.5 1.5-7 3.5 1.5 3 4 5 7 5s5.5-2 7-5c-1.5-2-4-3.5-7-3.5z" fill="#9CA3AF"/>
  </svg>`)

const router = useRouter()

const props = defineProps({
  tutor: {
    type: Object,
    required: true,
  },
})

const { notification, showNotification } = useNotification()

function goToBook() {
  router.push({
    name: 'StudentBook',
    params: { tutorId: props.tutor.id },
  })
}

const availableDays = computed(() => {
  if (
    !props.tutor.work_days ||
    Object.keys(props.tutor.work_days).length === 0
  ) {
    return null
  }

  const daysOfWeek = [
    'monday',
    'tuesday',
    'wednesday',
    'thursday',
    'friday',
    'saturday',
    'sunday',
  ]

  return daysOfWeek
    .filter(day => props.tutor.work_days[day])
    .map(day => day.charAt(0).toUpperCase() + day.slice(1))
})

const averageRatings = computed(() => {
  if (!props.tutor.ratings || props.tutor.ratings.length === 0) {
    return 0
  }
  const ratings = props.tutor.ratings.map(rating => rating.rate)
  const total = ratings.reduce((sum, rate) => sum + rate, 0)
  return total / ratings.length
})

const ratingComments = computed(() => {
  const ratingsWithComments = props.tutor.ratings.filter(
    rating => !!rating.comment,
  )
  return ratingsWithComments
})

const showFullBio = ref(false)

const truncatedBio = computed(() => {
  if (!props.tutor?.biography) return ''
  return props.tutor.biography.length > 150
    ? props.tutor.biography.slice(0, 150) + '...'
    : props.tutor.biography
})

// 




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
    tutor_offender_id: props.tutor.id,
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
      error.response?.data?.message || 'Failed to submit report. Please try again.',
      'error'
    )
  }
}
// FOR REPORT
</script>
