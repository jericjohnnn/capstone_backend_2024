<template>
  <main class="bg-blue-100  ">
    <SideBar>
      <main class="container grid grid-rows-[auto,1fr] gap-4 py-5  ">
        <BreadCrumb
          :breadcrumbs="[{ label: 'Requests', route: '/student/requests' }]"
          class=""
        />

        <div class=" ">
        <SentRequestsTabs >
          <template #all>
            <AllSentRequestsCard />
          </template>
          <template #pending>
            <PendingSentRequestsCard />
          </template>
          <template #completed>
            <CompletedSentRequestsCard />
          </template>
        </SentRequestsTabs>
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
import AllSentRequestsCard from '@/components/student/StudentSentRequests/AllSentRequestsCard.vue'
import CompletedSentRequestsCard from '@/components/student/StudentSentRequests/CompletedSentRequestsCard.vue'
import PendingSentRequestsCard from '@/components/student/StudentSentRequests/PendingSentRequestsCard.vue'
import SentRequestsTabs from '@/components/student/StudentSentRequests/SentRequestsTabs.vue'
// import HelpButton from '@/components/HelpButton.vue'
</script>
