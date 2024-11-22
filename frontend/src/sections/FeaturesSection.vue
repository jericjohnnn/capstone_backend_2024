<template>
  <!-- Features -->
  <div id="features-section" class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto bg-white">
  <!-- Title -->
  <div class="max-w-2xl mx-auto text-center mb-10 lg:mb-14">
      <h2 class="text-2xl  text-blue-600 font-bold md:text-4xl md:leading-tight">
        App Features
      </h2>
      <p class="mt-1 text-gray-700">What we offer</p>
    </div>

    <div class="aspect-w-16 aspect-h-7">
      <img
        class="w-full object-cover rounded-xl"
        src="/display.svg"
        alt="Features Image"
      />
    </div>

    <!-- Grid -->
    <div class="mt-5 lg:mt-16 grid lg:grid-cols-3 gap-8 lg:gap-12">
      <div class="lg:col-span-1">
        <h2 class="font-bold text-2xl md:text-3xl text-gray-800">
          Empowering Learning Through Peer Connection
        </h2>
        <p class="mt-2 md:mt-4 text-gray-500">
          TUDLO connects students with qualified peer tutors, creating an
          engaging learning environment where knowledge is shared and academic
          goals are achieved together.
        </p>
      </div>

      <div class="lg:col-span-2">
        <div class="grid sm:grid-cols-2 gap-8 md:gap-12">
          <!-- First Feature -->
          <div class="flex gap-x-5">
            <svg class="shrink-0 mt-1 size-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect width="18" height="10" x="3" y="11" rx="2" />
              <circle cx="12" cy="5" r="2" />
              <path d="M12 7v4" />
              <line x1="8" x2="8" y1="16" y2="16" />
              <line x1="16" x2="16" y1="16" y2="16" />
            </svg>
            <div class="grow">
              <h3 class="text-lg font-semibold text-gray-800">
                Flexible Learning Options
              </h3>
              <p class="mt-1 text-gray-600">
                Choose between online and in-person tutoring sessions to match
                your learning style and schedule.
              </p>
            </div>
          </div>

          <!-- Second Feature -->
          <div class="flex gap-x-5">
            <svg class="shrink-0 mt-1 size-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M7 10v12" />
              <path d="M15 5.88 14 10h5.83a2 2 0 0 1 1.92 2.56l-2.33 8A2 2 0 0 1 17.5 22H4a2 2 0 0 1-2-2v-8a2 2 0 0 1 2-2h2.76a2 2 0 0 0 1.79-1.11L12 2h0a3.13 3.13 0 0 1 3 3.88Z" />
            </svg>
            <div class="grow">
              <h3 class="text-lg font-semibold text-gray-800">
                Verified Peer Tutors
              </h3>
              <p class="mt-1 text-gray-600">
                Connect with qualified and vetted student tutors who excel in
                their academic fields.
              </p>
            </div>
          </div>

          <!-- Third Feature -->
          <div class="flex gap-x-5">
            <svg class="shrink-0 mt-1 size-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z" />
              <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z" />
            </svg>
            <div class="grow">
              <h3 class="text-lg font-semibold text-gray-800">
                Easy Scheduling
              </h3>
              <p class="mt-1 text-gray-600">
                Book and manage tutoring sessions with our intuitive scheduling
                system that works around your availability.
              </p>
            </div>
          </div>

          <!-- Fourth Feature -->
          <div class="flex gap-x-5">
            <svg class="shrink-0 mt-1 size-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
              <circle cx="9" cy="7" r="4" />
              <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
              <path d="M16 3.13a4 4 0 0 1 0 7.75" />
            </svg>
            <div class="grow">
              <h3 class="text-lg font-semibold text-gray-800">
                Quality Assurance
              </h3>
              <p class="mt-1 text-gray-600">
                Rate and review your tutoring experience to help maintain high
                teaching standards across our platform.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup></script>
