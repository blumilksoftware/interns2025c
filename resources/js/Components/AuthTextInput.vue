<script setup>
import { onMounted, ref } from 'vue'

defineProps({
  modelValue: {
    type: String,
    default: '',
  },
})

defineEmits(['update:modelValue'])

const input = ref(null)

onMounted(() => {
  if (input.value.hasAttribute('autofocus')) {
    input.value.focus()
  }
})

defineExpose({ focus: () => input.value.focus() })
</script>

<template>
  <input
    ref="input"
    class="border-amber-200 border-3 rounded-xl bg-orange-50/25 focus:border-orange-300 focus:ring-orange-300 font-sans text-gray-900 placeholder:text-gray-400"
    :value="modelValue"
    @input="$emit('update:modelValue', $event.target.value)"
  >
</template>


