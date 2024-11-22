<template>
  <!-- Subscribe -->
  <div id="contact-section" class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-16 mx-auto">
    <div class="grid md:grid-cols-2 gap-8">
      <div class="max-w-md">
        <h2 class="text-2xl font-bold md:text-3xl md:leading-tight text-white">
          Subscribe
        </h2>
        <p class="mt-3 text-blue-100">
          Subscribe and start making the most of every engagement.
        </p>
      </div>

      <form>
        <div class="w-full sm:max-w-lg md:ms-auto">
          <div class="flex flex-col items-center gap-2 sm:flex-row sm:gap-3">
            <div class="w-full">
              <label for="hero-input" class="sr-only">Search</label>
              <input
                type="text"
                id="hero-input"
                name="hero-input"
                class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-600 focus:ring-blue-600 disabled:opacity-50 disabled:pointer-events-none"
                placeholder="Enter your email"
              />
            </div>
            <a
              class="w-full sm:w-auto whitespace-nowrap py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-white text-blue-600 hover:bg-blue-50 focus:outline-none focus:bg-blue-50 disabled:opacity-50 disabled:pointer-events-none"
              href="#"
            >
              Subscribe
            </a>
          </div>
          <p class="mt-3 text-sm text-blue-100">
            No spam, unsubscribe at any time
          </p>
        </div>
      </form>
    </div>
  </div>
  <!-- End Subscribe -->
</template>
