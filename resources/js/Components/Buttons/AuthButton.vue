<script setup>

defineProps({
  type: {
    type: String,
    default: 'submit',
  },
})
</script>

<template>
  <button :type="type" class="border-b-4 border-[#eeb865] rounded transition-all duration-300 ease-out text-gray-500 font-bold text-md flex justify-center mx-auto p-3 hover:cursor-pointer hover:text-black">
    <slot />
  </button>
</template>
