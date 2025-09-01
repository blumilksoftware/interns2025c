<template>
  <button
    type="button"
    class="inline-flex items-center gap-x-3 rounded-md px-2.5 py-1.5 text-sm font-semibold text-white shadow-xs focus-visible:outline-2 focus-visible:outline-offset-2"
  >
    <component :is="icon" class="-ml-0.5 size-5" aria-hidden="true" />
    {{ text }}
  </button>
</template>

<script setup>
const props = defineProps({
  text: {
    type: String,
    required: true,
  },
  icon: {
    type: Object,
    required: true,
  },
})

</script>
