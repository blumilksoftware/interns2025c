<script setup>
import { computed } from 'vue'
import { Head } from '@inertiajs/vue3'
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue'

/* eslint-disable vue/no-v-html */
import DOMPurify from 'dompurify'

const props = defineProps({
  policy: {
    type: String,
    default: '',
  },
})

const sanitizedPolicy = computed(() => {
  return DOMPurify.sanitize(props.policy)
})

</script>

<template>
  <Head title="Privacy Policy" />

  <div class="font-sans text-gray-900 antialiased">
    <div class="pt-4 bg-gray-100">
      <div class="min-h-screen flex flex-col items-center pt-6 sm:pt-0">
        <div>
          <AuthenticationCardLogo />
        </div>

        <div class="w-full sm:max-w-2xl mt-6 p-6 bg-white shadow-md overflow-hidden sm:rounded-lg prose" v-html="sanitizedPolicy" />
      </div>
    </div>
  </div>
</template>
