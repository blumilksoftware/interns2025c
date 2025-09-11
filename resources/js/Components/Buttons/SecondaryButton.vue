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
    class="inline-flex items-center justify-center px-6 py-3 border-2 border-gray-300 bg-white text-gray-600 font-semibold text-sm rounded-md shadow-sm transition-all duration-300 ease-out hover:bg-gray-100 hover:border-gray-400 hover:text-gray-800 focus:bg-gray-100 focus:border-gray-400 focus:text-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-white disabled:hover:border-gray-300 disabled:hover:text-gray-600"
  >
    <slot />
  </button>
</template>
