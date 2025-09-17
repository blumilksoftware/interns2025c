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
    <div class="min-h-screen bg-soft-cream/50 relative">
      <Header />
      <main>
        <slot />
      </main>
      <Footer />
    </div>
  </div>
</template>
