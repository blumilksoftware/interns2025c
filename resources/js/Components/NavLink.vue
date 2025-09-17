<script setup>
import { computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'

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

const page = usePage()

const isActive = computed(() => {
  if (props.active) return true
  const currentUrl = page.url || ''
  const target = props.href || ''
  if (!target || target === '#') return false
  return currentUrl === target || currentUrl.startsWith(`${target}/`)
})

const classes = computed(() => {
  return isActive.value
    ? 'cursor-pointer inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-black font-semibold focus:outline-none transition duration-150 ease-in-out'
    : 'cursor-pointer inline-flex items-center px-1 pt-1 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 focus:outline-none focus:text-gray-700 transition duration-150 ease-in-out'
})
</script>

<template>
  <Link :href="href" :class="classes">
    <slot />
  </Link>
</template>
