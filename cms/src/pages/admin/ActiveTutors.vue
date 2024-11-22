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
          @click="currentTab = 'Unflagged'"
          :class="currentTab === 'Unflagged' ? 'font-bold underline' : ''"
        >
          Unflagged
        </button>
        <button
          @click="currentTab = 'Warned'"
          :class="currentTab === 'Warned' ? 'font-bold underline' : ''"
        >
          Warned
        </button>
        <button
          @click="currentTab = 'Penalized'"
          :class="currentTab === 'Penalized' ? 'font-bold underline' : ''"
        >
          Penalized
        </button>
        <button
          @click="currentTab = 'Banned'"
          :class="currentTab === 'Banned' ? 'font-bold underline' : ''"
        >
          Banned
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
import AllTutors from '@/components/AllTutors.vue';
import UnflaggedTutors from '@/components/UnflaggedTutors.vue';
import WarnedTutors from '@/components/WarnedTutors.vue';
import PenalizedTutors from '@/components/PenalizedTutors.vue';
import BannedTutors from '@/components/BannedTutors.vue';
import Header from '@/components/Header.vue';

const currentTab = ref('all')
const searchQuery = ref('');

// Update search query
const updateSearchQuery = (newQuery) => {
  searchQuery.value = newQuery;
}


// Dynamically set the component to load based on the selected tab
const currentComponent = computed(() => {
  switch (currentTab.value) {
    case 'Unflagged':
      return UnflaggedTutors
    case 'Warned':
      return WarnedTutors
    case 'Penalized':
      return PenalizedTutors
    case 'Banned':
      return BannedTutors
    default:
      return AllTutors
  }
})
</script>
