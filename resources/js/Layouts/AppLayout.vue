<script setup>
import { Head  } from '@inertiajs/vue3'
import Header from '@/Components/Header.vue'
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
    <div class="min-h-screen bg-[#fefae0]/50 relative">
      <Header />
      <main>
        <slot />
      </main>
    </div>
  </div>
</template>
