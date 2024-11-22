<template>
  <div class="w-52 h-screen border-r-2">
    <div class="flex justify-center py-4">
      <h1 class="text-4xl font-bold font-serif">Tudlo</h1>
    </div>
    <div class="flex flex-col items-center text-lg py-4 font-thin ">
      <ul class="text-gray-700 space-y-4 px-4   w-full">
        <li>
          <router-link 
            to="/admin/tutorApplication" 
            class="block py-2 rounded-md w-full text-center hover:bg-gray-200"
            active-class="bg-gray-300 font-bold"
          >
            Tutor Application
          </router-link>
        </li>
        <li>
          <router-link 
            to="/admin/activeTutors" 
            class="block py-2 w-full rounded-md  text-center hover:bg-gray-200"
            active-class="bg-gray-300 font-bold"
          >
            Active Tutors
          </router-link>
        </li>
        <li>
          <router-link 
            to="/admin/activeStudents" 
            class="block py-2 w-full rounded-md  text-center hover:bg-gray-200"
            active-class="bg-gray-300 font-bold"
          >
            Active Students
          </router-link>
        </li>
        <li>
          <router-link 
            to="/admin/reports" 
            class="block py-2 w-full rounded-md  text-center hover:bg-gray-200"
            active-class="bg-gray-300 font-bold"
          >
            User Reports
          </router-link>
        </li>
      </ul>
    </div>
  </div>
</template>

<style scoped>
/* Optional: Add a min-width for extra stability */
.w-52 {
  min-width: 13rem;
}
</style>
