import { createRouter, createWebHistory } from 'vue-router'
import NotFoundPage from '@/pages/NotFoundPage.vue'
import authRoutes from './authRoutes'
import adminRoutes from './adminRoutes'

function isAuthenticated() {
  return !!localStorage.getItem('cms_auth_token')
}

const router = createRouter({
  history: createWebHistory(),
  routes: [
    ...authRoutes,
    ...adminRoutes,
    {
      path: '/:catchAll(.*)', // Catch all unmatched routes
      name: 'NotFound',
      component: NotFoundPage,
    },
  ],
})

router.beforeEach((to, from, next) => {
  const publicPages = ['Login']
  const authRequired = !publicPages.includes(to.name)

  if (authRequired && !isAuthenticated()) {
    return next('/login')
  }

  if (isAuthenticated()) {
    if (publicPages.includes(to.name)) {
        return next('/')
      }
    }

  next()
})

export default router
