<template>
  <div class="flex flex-row">
    <SideBar />
    
    <div class="flex flex-col flex-grow">
      <Header @update:search="updateSearchQuery" />
      <div class="flex space-x-4 mb-3 ml-6 text-lg font-bold">
        <button
          @click="currentTab = 'all'"
          :class="currentTab === 'all' ? 'font-bold underline' : ''"
        >
          All
        </button>
        <button
          @click="currentTab = 'Pending'"
          :class="currentTab === 'Pending' ? 'font-bold underline' : ''"
        >
          Pending
        </button>
        <button
          @click="currentTab = 'Accepted'"
          :class="currentTab === 'Accepted' ? 'font-bold underline' : ''"
        >
          Accepted
        </button>
        <button
          @click="currentTab = 'Rejected'"
          :class="currentTab === 'Rejected' ? 'font-bold underline' : ''"
        >
          Rejected
        </button>
      </div>

      <!-- Conditional Rendering of Tab Components -->
      <component 
        :is="currentComponent" 
        :current-tab="currentTab" 
        :search-query="searchQuery" 
      />
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

import AllApplicants from '@/components/AllApplicants.vue';
import SideBar from '@/components/SideBar.vue';
import PendingTutors from '@/components/PendingTutors.vue';
import AcceptedTutors from '@/components/AcceptedTutors.vue';
import RejectedTutors from '@/components/RejectedTutors.vue';
import Header from '@/components/Header.vue';

const currentTab = ref('all');
const searchQuery = ref('');

// Update search query
const updateSearchQuery = (newQuery) => {
  searchQuery.value = newQuery;
}

// Dynamically set the component to load based on the selected tab
const currentComponent = computed(() => {
  switch (currentTab.value) {
    case 'Pending':
      return PendingTutors;
    case 'Accepted':
      return AcceptedTutors;
    case 'Rejected':
      return RejectedTutors;
    default:
      return AllApplicants;
  }
});
</script>
