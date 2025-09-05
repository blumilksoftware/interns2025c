<script setup>
defineProps({
  type: {
    type: String,
    default: 'button',
  },
  disabled: {
    type: Boolean,
    default: false,
  },
})
</script>

<template>
  <button 
    :type="type" 
    :disabled="disabled"
    class="inline-flex items-center justify-center px-6 py-3 border-2 border-red-500 bg-white text-red-600 font-semibold text-sm rounded-md shadow-sm transition-all duration-300 ease-out hover:bg-red-500 hover:text-white focus:bg-red-500 focus:text-white focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-white disabled:hover:text-red-600"
  >
    <slot />
  </button>
</template>
