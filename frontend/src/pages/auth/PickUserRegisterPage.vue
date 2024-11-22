<template>
  <NavBar />
  <div class="flex flex-col items-center justify-center min-h-screen text-center">
    <h2 class="text-xl md:text-2xl">
      Sign-up as: 
      <span class="text-blue-500 font-bold">STUDENT</span> 
      or 
      <span class="text-blue-500 font-bold">TUTOR</span>
    </h2>

    <div class="flex gap-8 mt-5">
      <div
        class="border-2 rounded-lg p-5 w-40 cursor-pointer transition-colors duration-300 hover:border-blue-400"
        :class="{ 'border-blue-500': selectedRole === 'student', 'border-gray-200': selectedRole !== 'student' }"
        @click="selectRole('student')"
      >
        <img 
          :src="studentImage" 
          alt="Student" 
          class="w-20 h-20 mx-auto mb-3"
        />
        <h3 class="text-lg font-medium">STUDENT</h3>
      </div>

      <div
        class="border-2 rounded-lg p-5 w-40 cursor-pointer transition-colors duration-300 hover:border-blue-400"
        :class="{ 'border-blue-500': selectedRole === 'tutor', 'border-gray-200': selectedRole !== 'tutor' }"
        @click="selectRole('tutor')"
      >
        <img 
          :src="teacherImage" 
          alt="Tutor" 
          class="w-20 h-20 mx-auto mb-3"
        />
        <h3 class="text-lg font-medium">TUTOR</h3>
      </div>
    </div>

    <button 
      class="mt-5 px-5 py-2.5 rounded bg-blue-500 text-white transition-colors duration-300 
             disabled:bg-gray-300 disabled:cursor-not-allowed 
             enabled:hover:bg-blue-600"
      :disabled="!selectedRole" 
      @click="goToRegister"
    >
      Continue
    </button>
  </div>
  <FooterSection />
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import NavBar from '../../sections/NavBar.vue';
import FooterSection from '../../sections/FooterSection.vue';

// Import images
import studentImage from '@/assets/student.png';
import teacherImage from '@/assets/teacher.png';

const router = useRouter();
const selectedRole = ref('');

const selectRole = (role) => {
  selectedRole.value = role;
};

const goToRegister = () => {
  if (selectedRole.value === 'student') {
    router.push('/register/student');
  } else if (selectedRole.value === 'tutor') {
    router.push('/register/tutor');
  }
};
</script>
