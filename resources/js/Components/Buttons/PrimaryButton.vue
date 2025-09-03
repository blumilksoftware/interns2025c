<script setup>
// Deprecated component: keep as shim for backward compatibility
import AuthButton from '@/Components/Buttons/AuthButton.vue'
defineProps({ type: { type: String, default: 'submit' } })
</script>

<template>
  <AuthButton :type="type"><slot /></AuthButton>
</template>
