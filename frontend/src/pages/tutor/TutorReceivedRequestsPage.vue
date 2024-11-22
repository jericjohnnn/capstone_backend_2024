<template>
  <main class="bg-blue-100">
    <SideBar>
      <main class="container grid grid-rows-[auto,1fr] gap-4 py-5">
        <BreadCrumb
          :breadcrumbs="[{ label: 'Requests', route: '/tutor/requests' }]"
        />

        <div>
          <RequestsTabs>
            <template #pending>
              <PendingRequestsCard />
            </template>
            <template #completed>
              <CompletedRequestsCard />
            </template>
          </RequestsTabs>
        </div>
      </main>
    </SideBar>
    <FooterSection class="block md:hidden" />
    <!-- <HelpButton /> -->
  </main>
</template>

<script setup>
import FooterSection from '@/sections/FooterSection.vue'
import BreadCrumb from '@/components/BreadCrumb.vue'
import SideBar from '@/components/SideBar.vue'
import CompletedRequestsCard from '@/components/tutor/TutorReceivedRequests/CompletedRequestsCard.vue'
import PendingRequestsCard from '@/components/tutor/TutorReceivedRequests/PendingRequestsCard.vue'
import RequestsTabs from '@/components/tutor/TutorReceivedRequests/RequestsTabs.vue'
// import HelpButton from '@/components/HelpButton.vue'
</script>
