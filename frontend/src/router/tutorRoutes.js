import TutorNotificationPage from "@/pages/tutor/TutorNotificationPage.vue";
import TutorProfilePage from "@/pages/tutor/TutorProfilePage.vue";
import TutorBookDetailsPage from "@/pages/tutor/TutorBookDetailsPage.vue";
import TutorReceivedRequestsPage from "@/pages/tutor/TutorReceivedRequestsPage.vue";
import TutorSchedulePage from "@/pages/tutor/TutorSchedulePage.vue";
import OngoingBookingDetails from "@/components/tutor/TutorSchedule/OngoingBookingDetails.vue";
import TutorPendingApprovalPage from "@/pages/auth/TutorPendingApprovalPage.vue";

const tutorRoutes = [
  {
    path: '/tutor/profile',
    name: 'TutorProfile',
    component: TutorProfilePage
  },
  {
    path: '/tutor/schedule',
    name: 'TutorSchedule',
    component: TutorSchedulePage
  },
  {
    path: '/tutor/schedule/:bookId',
    name: 'OngoingTutorSchedule',
    component: OngoingBookingDetails
  },
  {
    path: '/tutor/requests',
    name: 'TutorRequests',
    component: TutorReceivedRequestsPage
  },
  {
    path: '/tutor/requests/:bookId',
    name: 'TutorBookDetails',
    component: TutorBookDetailsPage
  },
  {
    path: '/tutor/notifications',
    name: 'TutorNotifications',
    component: TutorNotificationPage
  },
  {
    path: '/tutor/pending-approval',
    name: 'TutorPendingApproval',
    component: TutorPendingApprovalPage
  },
];

export default tutorRoutes;
