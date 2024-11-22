<template>
  <div class="min-h-screen bg-blue-600">
    <NavBar @scrollTo="scrollToSection" />
    <HeroSection />
    <TeamSection ref="teamSection" />
    <FeaturesSection ref="featuresSection" />
    <SubscribeSection ref="subscribeSection" />
    <FooterSection />
  </div>
</template>

<script setup>
import { ref } from 'vue';
import HeroSection from '../sections/HeroSection.vue';
import NavBar from '../sections/NavBar.vue';
import FooterSection from '../sections/FooterSection.vue';
import TeamSection from '../sections/TeamSection.vue';
import FeaturesSection from '../sections/FeaturesSection.vue';
import SubscribeSection from '../sections/SubscribeSection.vue';

const scrollToSection = (sectionName) => {
  const sectionMap = {
    'about': 'features-section',
    'team': 'team-section',
    'contact': 'contact-section'
  };

  const elementId = sectionMap[sectionName];
  if (elementId) {
    const element = document.getElementById(elementId);
    if (element) {
      element.scrollIntoView({ 
        behavior: 'smooth',
        block: 'start'
      });
    }
  }
};
</script>

<style scoped>

</style>
