import { createRouter, createWebHistory } from 'vue-router'
import authRoutes from './authRoutes'
import tutorRoutes from './tutorRoutes'
import studentRoutes from './studentRoutes'
import NotFoundPage from '@/pages/NotFoundPage.vue'


function isAuthenticated() {
  return !!localStorage.getItem('app_auth_token')
}

function getUserType() {
  return localStorage.getItem('user_type')
}

const router = createRouter({
  history: createWebHistory(),
  routes: [
    ...authRoutes,
    ...tutorRoutes,
    ...studentRoutes,
    {
      path: '/:catchAll(.*)', // Catch all unmatched routes
      name: 'NotFound',
      component: NotFoundPage,
    },
  ],
  scrollBehavior() {
    return { top: 0, behavior: 'smooth' };
  },
})

router.beforeEach((to, from, next) => {
  const publicPages = [
    'Main',
    'Login',
    'Register',
    'TutorRegister',
    'StudentRegister',
  ]
  const authPages = ['Login', 'Register', 'TutorRegister', 'StudentRegister']
  const authRequired = !publicPages.includes(to.name)
  const userType = getUserType()
  const userData = JSON.parse(localStorage.getItem('user_data') || '{}')

  if (authRequired && !isAuthenticated()) {
    return next('/login')
  }

  if (isAuthenticated()) {
    if (authPages.includes(to.name)) {
      if (userType === 'Student') {
        return next('/student/home')
      } else if (userType === 'Tutor') {
        return next('/tutor/profile')
      }
    }

    // Handle Tutor specific routes
    if (userType === 'Tutor') {
      if (userData.approval_status === 'Pending' && 
          to.name !== 'TutorPendingApproval' && 
          to.path !== '/') {
        return next('/tutor/pending-approval')
      }else{

      if (to.path.startsWith('/student')) {
        return next('/tutor/profile')
      }

      if (to.path === '/tutor/' || to.path === '/tutor') {
          return next('/tutor/profile')
        }
      }
    }

    // Handle Student specific routes
    if (userType === 'Student') {
      if (to.path.startsWith('/tutor')) {
        return next('/student/home')
      }

      if (to.path === '/student/' || to.path === '/student') {
        return next('/student/home')
      }
    }
  }

  next()
})

router.afterEach((to, from, failure) => {
  if (!failure) {
    setTimeout(() => {
      window.HSStaticMethods.autoInit();
    }, 100)
  }
});

export default router
