<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  href: {
    type: String,
    default: '',
  },
  active: {
    type: Boolean,
    default: false,
  },
})

const classes = computed(() => {
  return props.active
    ? 'cursor-pointer inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-gray-900 dark:text-gray-100 focus:outline-none transition duration-150 ease-in-out'
    : 'cursor-pointer inline-flex items-center px-1 pt-1 border-transparent text-sm font-medium leading-5 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-800 focus:outline-none focus:text-gray-700 dark:focus:text-gray-300 transition duration-150 ease-in-out'
})
</script>

<template>
  <Link :href="href" :class="classes">
    <slot />
  </Link>
</template>
