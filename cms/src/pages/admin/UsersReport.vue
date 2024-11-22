<template>
  <div class="flex flex-row">
    <SideBar />
    <div class="flex flex-col flex-grow">
      <Header @update:search="updateSearchQuery"/>
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
          @click="currentTab = 'Resolved'"
          :class="currentTab === 'Resolved' ? 'font-bold underline' : ''"
        >
          Resolved
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
import { ref, computed } from 'vue'
import SideBar from '@/components/SideBar.vue'
import AllReports from '@/components/AllReports.vue';
import PendingReports from '@/components/PendingReports.vue';
import ResolvedReports from '@/components/ResolvedReports.vue';
import Header from '@/components/Header.vue';

const currentTab = ref('all')
const searchQuery = ref(''); // Define search query

// Update search query from Header
const updateSearchQuery = (query) => {
  searchQuery.value = query;
};

// Dynamically set the component to load based on the selected tab
const currentComponent = computed(() => {
  switch (currentTab.value) {
    case 'Pending':
      return PendingReports
    case 'Resolved':
      return ResolvedReports
    default:
      return AllReports
  }
})
</script>
