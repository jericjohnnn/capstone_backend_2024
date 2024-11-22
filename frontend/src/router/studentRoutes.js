import StudentHomePage from "@/pages/student/StudentHomePage.vue";
import StudentBookTutorPage from "@/pages/student/StudentBookTutorPage.vue";
import StudentSchedulePage from "@/pages/student/StudentSchedulePage.vue";
import StudentSentRequestsPage from "@/pages/student/StudentSentRequestsPage.vue";
import StudentNotificationPage from "@/pages/student/StudentNotificationPage.vue";
import StudentProfilePage from "@/pages/student/StudentProfilePage.vue";
import StudentBookDetailsPage from "@/pages/student/StudentBookDetailsPage.vue";
import StudentOngoingBookingDetails from "@/components/student/StudentSchedule/StudentOngoingBookingDetails.vue";
import StudentTutorProfilePage from "@/pages/student/StudentTutorProfile.vue";
const studentRoutes = [
  {
    path: '/student/home',
    name: 'StudentHome',
    component: StudentHomePage
  },
  {
    path: '/student/tutorProfile/:tutorId',
    name: 'StudentTutorProfile',
    component: StudentTutorProfilePage
  },
  {
    path: '/student/profile',
    name: 'StudentProfile',
    component: StudentProfilePage
  },
  {
    path: '/student/schedule',
    name: 'StudentSchedule',
    component: StudentSchedulePage
  },
  {
    path: '/student/schedule/:bookId',
    name: 'OngoingStudentSchedule',
    component: StudentOngoingBookingDetails
  },
  {
    path: '/student/requests',
    name: 'StudentRequests',
    component: StudentSentRequestsPage
  },
  {
    path: '/student/requests/:bookId',
    name: 'StudentBookDetails',
    component: StudentBookDetailsPage
  },
  {
    path: '/student/notifications',
    name: 'StudentNotifications',
    component: StudentNotificationPage
  },
  {
    path: '/student/book/:tutorId',
    name: 'StudentBook',
    component: StudentBookTutorPage
  },
];

export default studentRoutes;
