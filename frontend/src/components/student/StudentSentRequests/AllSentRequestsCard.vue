<template>
  <div class="">
    <NotificationToast 
      :show="notification.show"
      :message="notification.message"
      :type="notification.type"
    />
    <!-- FOR RATE -->
    <PopUpModal
      v-model:toggleModal="isRateModalOpen"
      @openValue="handleModalOpen"
      @cancelButtonValue="handleModalCancel"
      @mainButtonValue="handleRateSubmit"
    >
      <template #modalTitle> Rate Tutor </template>
      <template #mainContent>
        <RateForm
          @update:rating="handleRateUpdate"
          @update:reviewMessage="handleReviewMessageUpdate"
        />
      </template>
      <template #mainButton> Submit feedback </template>
      <template #cancelButton> Cancel </template>
    </PopUpModal>
    <!-- FOR REPORT -->
    <!-- Tutor Cards -->
    <div
      v-if="!pendingRequests"
      class="min-h-[calc(100vh-15rem)] justify-center flex"
    >
      <div class="flex items-center justify-center">
        <LoaderSpinner />
      </div>
    </div>
    <div 
      v-else-if="pendingRequests.length === 0" 
      class="min-h-[calc(100vh-15rem)] flex items-center justify-center"
    >
      <p class="text-gray-600 text-lg">It's empty, start finding your tutor to fill this in</p>
    </div>
    <div v-else v-for="book in pendingRequests" :key="book.id" class="mb-3">
      <div
        class="grid grid-rows-[auto,1fr,auto] md:grid-cols-3 md:grid-rows-1 p-3 gap-2 rounded-xl bg-blue-200 border-blue-50 border-2"
      >
        <div class="flex items-center gap-2">
          <div class="shrink-0">
            <img
              class="shrink-0 size-12 md:size-16 rounded-full"
              :src="book.tutor.profile_image || defaultProfileImage"
              alt="profile image"
            />
          </div>
          <div
            class="flex justify-between w-full md:flex-col items-center md:items-start"
          >
            <h1 class="text-base grow md:text-lg font-medium text-gray-800">
              {{ book.tutor.first_name }} {{ book.tutor.last_name }}
            </h1>
            <button
              @click="selectTutor(book.tutor_id)"
              class="hidden md:block w-3/6 py-1.5 px-3 text-xs font-medium rounded-md border border-blue-600 text-blue-600 hover:bg-blue-50 transition-colors duration-200"
            >
              View Profile
            </button>
            <div class="">
              <p
                class="md:hidden block font-semibold"
                :class="book.status == 'Completed' ? 'text-green-600' : ''"
              >
                {{ book.status }}
              </p>
              <button
                @click="openRateModal(book.tutor_id)"
                class="block md:hidden w-full py-1.5 px-3 text-xs font-medium rounded-md border border-blue-600 text-blue-600 hover:bg-blue-50 transition-colors duration-200"
                :class="book.status !== 'Completed' ? 'hidden' : ''"
              >
                Rate tutor
              </button>
            </div>
          </div>
        </div>

        <hr class="block md:hidden my-2 border-white" />

        <div
          class="flex justify-between md:flex-col items-center order-first md:order-none md:justify-center"
        >
          <p class="">You requested for</p>
          <div
            class="flex justify-center w-fit px-6 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-800 border border-blue-300 mt-1"
          >
            {{ book.subject }}
          </div>
        </div>

        <div
          class="flex justify-between md:justify-end gap-3 w-full items-center md:col-span-1"
        >
          <p
            class="hidden md:block font-semibold"
            :class="book.status == 'Completed' ? 'text-green-600' : ''"
          >
            {{ book.status }}
          </p>
          <div class="w-full h-full md:hidden">
            <button
              @click="selectTutorMobile(book.tutor_id)"
              class="h-full w-full md:w-fit py-1.5 px-3 text-xs font-medium rounded-md border border-blue-600 text-blue-600 hover:bg-blue-50 transition-colors duration-200"
            >
              View Profile
            </button>
          </div>
          <div class="w-full md:w-3/6">
            <button
              @click="openRateModal(book.tutor_id)"
              class="hidden md:block w-full py-1.5 px-3 text-xs font-medium rounded-lg border border-blue-600 text-blue-600 hover:bg-blue-50 transition-colors duration-200"
              :class="book.status !== 'Completed' ? 'md:hidden' : ''"
            >
              Rate tutor
            </button>

            <button
              @click="goToBookDetails(book.id)"
              type="button"
              class="w-full py-2 px-4 text-sm md:text-xs font-normal rounded-lg border border-blue-400 bg-blue-600 text-white hover:bg-blue-700 focus:outline-none"
            >
              {{ book.status !== 'Completed' ? 'Review request' : 'View info' }}
            </button>
          </div>
        </div>
      </div>
    </div>
    <div>
      <PaginationLinks
        :links="paginationLinks"
        :current-page="currentPage"
        :last-page="lastPage"
      />
    </div>
  </div>
</template>

<script setup>
import PopUpModal from '@/components/Reusables/PopUpModal.vue'
import RateForm from '@/components/Reusables/RateForm.vue'
import LoaderSpinner from '@/components/Reusables/LoaderSpinner.vue'
import PaginationLinks from '@/components/PaginationLinks.vue'
import NotificationToast from '@/components/Reusables/NotificationToast.vue'
import { useNotification } from '@/composables/useNotification'
import { ref, onMounted, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axiosInstance from '@/axiosInstance'

const { notification, showNotification } = useNotification()

const router = useRouter()
const route = useRoute()

const defaultProfileImage =
  'data:image/svg+xml;base64,' +
  btoa(`
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
    <circle cx="12" cy="12" r="11" fill="white" stroke="#E5E7EB" stroke-width="2"/>
    <circle cx="12" cy="8" r="3.5" fill="#9CA3AF"/>
    <path d="M12 12.5c-3 0-5.5 1.5-7 3.5 1.5 3 4 5 7 5s5.5-2 7-5c-1.5-2-4-3.5-7-3.5z" fill="#9CA3AF"/>
  </svg>`)

const pendingRequests = ref(null)
const currentPage = ref(1)
const lastPage = ref(1)
const paginationLinks = ref([])

const fetchSentRequests = async (page = 1) => {
  try {
    const response = await axiosInstance.get(
      `/api/sent-tutor-requests?tab=all&page=${page}`,
    )
    const { data, current_page, last_page, links } = response.data.sent_requests
    pendingRequests.value = data
    currentPage.value = current_page
    lastPage.value = last_page
    paginationLinks.value = links
  } catch (err) {
    console.error('Error fetching student requests:', err)
    showNotification('Failed to load requests. Please try again.', 'error')
  }
}

const selectTutor = tutorId => {
  router.push({
    path: '/student/home',
    query: {
      page: currentPage.value,
      tutor_id: tutorId,
    },
  })
}

const selectTutorMobile = tutorId => {
  router.push({
    path: `/student/tutorProfile/${tutorId}`,
  })
}

const goToBookDetails = bookId => {
  router.push({
    name: 'StudentBookDetails',
    params: { bookId: bookId },
  })
}

watch(
  () => [route.query.tab, route.query.page],
  ([newTab, newPage]) => {
    if (route.query.tab === newTab) {
      fetchSentRequests(newPage)
    }
  },
)

onMounted(() => {
  const initialPage = parseInt(route.query.page) || 1
  fetchSentRequests(initialPage)
})

// FOR RATE
const isRateModalOpen = ref(false)

const rateNumber = ref(0)
const handleRateUpdate = value => {
  rateNumber.value = value
}

const rateMessage = ref('')
const handleReviewMessageUpdate = value => {
  rateMessage.value = value
}

const selectedTutorId = ref(null)

const openRateModal = (tutorId) => {
  selectedTutorId.value = tutorId
  isRateModalOpen.value = true
}

const handleModalOpen = value => {
  isRateModalOpen.value = value
}

const handleModalCancel = () => {
  isRateModalOpen.value = false
  selectedTutorId.value = null
  rateNumber.value = 0
  rateMessage.value = ''
}

const handleRateSubmit = async () => {
  if (rateNumber.value === 0) {
    showNotification('Please select a rating', 'error')
    return
  }


  const rateData = {
    tutor_id: selectedTutorId.value,
    rate: rateNumber.value,
    comment: rateMessage.value,
  }

  try {
    await axiosInstance.post('api/rate-tutor', rateData)
    showNotification('Review submitted successfully', 'success')
    isRateModalOpen.value = false

    // Reset form
    selectedTutorId.value = null
    rateNumber.value = 0
    rateMessage.value = ''

    // Refresh the requests list to show updated status
    fetchSentRequests(currentPage.value)
  } catch (error) {
    console.error('Error submitting review:', error)
    showNotification(
      error.response?.data?.message ||
        'Failed to submit review. Please try again.',
      'error'
    )
  }
}
// FOR RATE
</script>
