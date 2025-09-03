<script setup>
// Deprecated shim; use AuthTextInput instead
import AuthTextInput from '@/Components/AuthTextInput.vue'
defineProps({ modelValue: { type: String, default: '' } })
defineEmits(['update:modelValue'])
</script>

<template>
  <AuthTextInput :model-value="modelValue" @update:model-value="$emit('update:modelValue', $event)" />
</template>
