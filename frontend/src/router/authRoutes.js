import LandingPage from '@/pages/LandingPage.vue';
import LoginPage from '@/pages/auth/LoginPage.vue';
import PickUserRegisterPage from '@/pages/auth/PickUserRegisterPage.vue';
import TutorRegisterPage from '@/pages/auth/TutorRegisterPage.vue';
import StudentRegisterPage from '@/pages/auth/StudentRegisterPage.vue';

const authRoutes = [
  {
    path: '/',
    name: 'Main',
    component: LandingPage
  },
  {
    path: '/login',
    name: 'Login',
    component: LoginPage
  },
  {
    path: '/register',
    name: 'Register',
    component: PickUserRegisterPage
  },
  {
    path: '/register/tutor',
    name: 'TutorRegister',
    component: TutorRegisterPage
  },
  {
    path: '/register/student',
    name: 'StudentRegister',
    component: StudentRegisterPage
  },
];

export default authRoutes;
