
import AdminPage from '@/pages/admin/AdminPage.vue'
import ActiveTutors from '@/pages/admin/ActiveTutors.vue'
import TutorApplication from '@/pages/admin/TutorApplication.vue'
import ActiveStudents from '@/pages/admin/ActiveStudents.vue'
import UsersReport from '@/pages/admin/UsersReport.vue'



const adminRoutes = [
  {
    path: '/',
    name: 'Main',
    component: AdminPage,
  },
  {
    path: '/admin/tutorApplication',
    name: 'TutorApplication',
    component: TutorApplication,
  },
  {
    path: '/admin/activeTutors',
    name: 'ActiveTutors',
    component: ActiveTutors,
  },
  {
    path: '/admin/activeStudents',
    name: 'ActiveStudents',
    component: ActiveStudents,
  },
  {
    path: '/admin/reports',
    name: 'UsersReport',
    component: UsersReport,
  },
  
]

export default adminRoutes
