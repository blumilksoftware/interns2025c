<script setup>
import { computed } from 'vue'
import { IconPawFilled } from '@tabler/icons-vue'
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
    <IconPawFilled class="size-8 text-gray-400 transition-all duration-150 ease-out hover:text-gray-600 hover:scale-110 focus:outline-none peer-checked:text-yellow-400 peer-checked:scale-110" />
  </label>
</template>
