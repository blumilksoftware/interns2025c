<script setup>
import { computed } from 'vue'

const emit = defineEmits(['update:checked'])

const props = defineProps({
  checked: {
    type: [Array, Boolean],
    default: false,
  },
  value: {
    type: String,
    default: null,
  },
})

const proxyChecked = computed({
  get() {
    return props.checked
  },

  set(val) {
    emit('update:checked', val)
  },
})
</script>


<template>
  <label class="inline-flex items-center cursor-pointer select-none focus:outline-none">
    <input
      v-model="proxyChecked"
      type="checkbox"
      :value="value"
      class="sr-only peer focus:outline-none"
    >
    <svg
      viewBox="0 0 24 24"
      class="size-8 text-gray-400 transition-all duration-150 ease-out hover:text-gray-600 hover:scale-110 focus:outline-none peer-checked:text-yellow-400 peer-checked:scale-110"
      fill="currentColor"
      aria-hidden="true"
    >
      <circle cx="7" cy="8" r="2" />
      <circle cx="12" cy="6.5" r="2" />
      <circle cx="17" cy="8" r="2" />
      <path d="M12 12c-3.5 0-6 2.2-6 4.5 0 1.4 1.2 2.5 2.8 2.5 1.4 0 2.5-.6 3.2-1.4.7.8 1.8 1.4 3.2 1.4 1.6 0 2.8-1.1 2.8-2.5 0-2.3-2.5-4.5-6-4.5z" />
    </svg>
  </label>
</template>
