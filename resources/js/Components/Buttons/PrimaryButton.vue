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
    class="rounded-lg inline-flex gap-3 items-center bg-white px-8 py-4 text-sm font-semibold text-black hover:border-black shadow-lg duration-200 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black border-2 border-gray-300 transition hover:cursor-pointer duration-200"
  >
    <slot />
  </button>
</template>
