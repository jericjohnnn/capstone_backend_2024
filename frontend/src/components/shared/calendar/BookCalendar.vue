<template>
  <div class="flex flex-col">
    <NotificationToast 
      :show="notification.show"
      :message="notification.message"
      :type="notification.type"
    />
    <div class="order-last md:order-first">
      <TimePicker
        :isDisabled="activeView !== 'day'"
        @update:start-time="updateStartTime"
        @update:end-time="updateEndTime"
        @triggerTimeUpdate="addScheduleRequest"
      />
    </div>
    <vue-cal
      :click-to-navigate="activeView !== 'day'"
      v-model:active-view="activeView"
      :disable-views="['years', 'year', 'week']"
      :min-date="availableStartingDate"
      :hide-weekdays="hiddenWeekDays"
      :special-hours="specialHours"
      :time-from="0 * 60"
      :time-to="25 * 60"
      :events="events"
      @cell-click="getCellDate"
      small
      class="bg-white min-h-[450px] max-h-[450px] md:min-h-[calc(100vh-14rem)] md:max-h-[calc(100vh-14rem)] rounded-lg"
    >
      <template #title="{ view }">
        <span class="" v-if="view.id === 'month'">{{
          view.startDate.format('MMMM YYYY')
        }}</span>
        <span class="" v-if="view.id === 'day'">{{
          view.endDate.format('dddd | MMMM D')
        }}</span>
      </template>
      <template #event="{ event }">
        <div class="vuecal__event-title" v-html="event.title" />
        <span>{{ event.start.formatTime('hh:mm{am}') }}</span>
        <span>{{ event.end.formatTime('hh:mm{am}') }}</span>
      </template>
    </vue-cal>
  </div>
</template>

<script setup>
import TimePicker from '@/components/TimePicker.vue'
import { ref, watch } from 'vue'
import VueCal from 'vue-cal'
import 'vue-cal/dist/vuecal.css'
import {
  formatRawDateTime,
  formatTimeToInteger,
  formatDateAndTimeToTimestamp,
} from '@/utils/dateTime'
import {
  getAvailableStartingDate,
  getActiveView,
  getSpecialHours,
  getHiddenWeekDays,
} from '@/utils/calendar/calendarViews'
import {
  checkEventOverlap,
  getBusinessHours,
  formatNewEvent,
} from '@/utils/calendar/calendarActions'
import NotificationToast from '@/components/Reusables/NotificationToast.vue'
import { useNotification } from '@/composables/useNotification'

const props = defineProps({
  tutorBookings: {
    type: Array,
    required: true,
    default: () => [],
  },
  tutorWorkDays: {
    type: Object,
    required: true,
    default: () => {},
  },
  studentBookings: {
    type: Array,
    required: false,
    default: () => [],
  },
  idToRemove: {
    type: String,
    required: false,
    default: '',
  },
})

const hiddenWeekDays = ref([])
const specialHours = ref([])
const availableStartingDate = ref('')
const activeView = ref('')
const tutorWorkHours = ref([])

const emit = defineEmits(['update:added-schedules'])

const events = ref([])
const addedPendingSchedules = ref([])

//ADD NEW DATES
const selectedCellDate = ref('')
const selectedStartTime = ref('')
const selectedEndTime = ref('')

const { notification, showNotification } = useNotification()

const getCellDate = event => {
  selectedCellDate.value = event
}

const updateStartTime = time => {
  selectedStartTime.value = time
}

const updateEndTime = time => {
  selectedEndTime.value = time
}

const addScheduleRequest = () => {
  const newStart = formatDateAndTimeToTimestamp(
    selectedCellDate.value,
    selectedStartTime.value,
  )
  const newEnd = formatDateAndTimeToTimestamp(
    selectedCellDate.value,
    selectedEndTime.value,
  )

  const hasOverlap = checkEventOverlap(newStart, newEnd, events.value)

  if (hasOverlap) {
    showNotification('Event time overlaps with an existing event. Please choose another time.', 'error')
    return
  }

  const newEvent = formatNewEvent(
    selectedCellDate.value.format('YYYY-MM-DD'),
    selectedStartTime.value,
    selectedEndTime.value,
  )

  events.value.push(newEvent.newEvent)
  addedPendingSchedules.value.push(newEvent.newEvent)
  emit('update:added-schedules', addedPendingSchedules.value)
}

//

function deleteSchedule(id) {
  addedPendingSchedules.value = addedPendingSchedules.value.filter(
    schedule => schedule.id !== id
  )
  events.value = events.value.filter(schedule => schedule.id !== id)
  emit('update:added-schedules', addedPendingSchedules.value)
}

//WATCHES
watch(
  () => props.idToRemove,
  id => {
    deleteSchedule(id)
  },
)

watch(
  () => [props.tutorBookings, props.studentBookings],
  ([tutorBookings, studentBookings]) => {
    try {
      events.value = [
        ...(tutorBookings?.flatMap(
          booking =>
            booking.booking_dates?.map(date => ({
              id: date.id,
              booking_id: booking.booking_id,
              start: formatRawDateTime(date.start_time),
              end: formatRawDateTime(date.end_time),
              title: booking.subject || '',
              class: 'tutorBookings',
            })) || [],
        ) || []),
        ...(studentBookings?.flatMap(
          booking =>
            booking.booking_dates?.map(date => ({
              id: date.id,
              booking_id: booking.booking_id,
              start: formatRawDateTime(date.start_time),
              end: formatRawDateTime(date.end_time),
              title: booking.subject || '',
              class: 'studentBookings',
            })) || [],
        ) || []),
      ]

      hiddenWeekDays.value = getHiddenWeekDays(props.tutorWorkDays)
      specialHours.value = getSpecialHours(tutorWorkHours)
      availableStartingDate.value = getAvailableStartingDate()
      activeView.value = getActiveView()
      tutorWorkHours.value = getBusinessHours(
        formatTimeToInteger(props.tutorWorkDays.start_time),
        formatTimeToInteger(props.tutorWorkDays.end_time),
      )
    } catch (error) {
      console.error('Error updating calendar:', error)
      showNotification('Error updating calendar. Please try again.', 'error')
    }
  },
  {
    immediate: true,
    deep: true,
  },
)
</script>

<style>
.vuecal__event.tutorBookings {
  background-color: rgba(201, 201, 201, 0.902);
  border: 1px solid rgba(255, 253, 248, 0.902);
  color: hsla(0, 0%, 100%, 0.902);
  font-size: 0.75em;
}

.vuecal__event.tutorSchedule {
  background-color: rgba(235, 235, 235, 0.9);
  border: 1px solid rgba(255, 255, 255, 0.9);
  color: #8d8d8d;
  font-size: 0.75em;
  /* Adjust font size as needed */
}

.vuecal__event.addedSchedule {
  background-color: rgba(36, 77, 255, 0.9);
  border: 1px solid rgba(231, 236, 255, 0.9);
  color: #fff;
  font-size: 0.75em;
  /* Adjust font size as needed */
}

/*  */

.vuecal__cell--before-min {
  color: #b6d6c7;
}

.vuecal__cell--after-max {
  color: #00f0f0;
}

/*  */

.business-hours {
  background-color: rgba(255, 255, 0, 0.15);
  border: solid rgba(255, 210, 0, 0.3);
  border-width: 2px 0;
}

.vuecal__cell--disabled {
  text-decoration: line-through;
  color: #bbb;
}

/*  */

.vuecal__title-bar {
  color: #fff;
  background-color: rgb(147 197 253);
}

.vuecal__menu,
.vuecal__cell-events-count {
  color: #fff;
  background-color: rgb(37 99 235);
  border-radius: 0.5rem 0.5rem 0 0;
}
</style>
