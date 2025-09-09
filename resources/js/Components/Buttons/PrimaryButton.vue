<script setup>
defineProps({
  type: {
    type: String,
    default: 'submit',
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
    class="inline-flex items-center justify-center px-6 py-3 border-2 border-light-brown bg-white text-gray-700 font-semibold text-sm rounded-md shadow-sm transition-all duration-300 ease-out hover:bg-light-brown hover:text-white focus:bg-light-brown focus:text-white focus:outline-none focus:ring-2 focus:ring-light-brown focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-white disabled:hover:text-gray-700"
  >
    <slot />
  </button>
</template>
