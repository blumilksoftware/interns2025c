<script setup>
import { Head  } from '@inertiajs/vue3'
import Header from '@/Components/Header.vue'
import Footer from '@/Components/Footer.vue'
defineProps({
  title: {
    type: String,
    default: 'Profile',
  },
})
</script>

<template>
  <div>
    <Head :title="title" />
    <div class="min-h-screen relative flex flex-col">
      <Header />
      <main class="flex-1">
        <slot />
      </main>
      <Footer />
    </div>
  </div>
</template>
