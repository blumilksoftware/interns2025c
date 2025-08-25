<script setup>
import { computed } from 'vue'
import StatusBadge from './StatusBadge.vue'
import { getKindForColumn } from '../data/columnRenderers.js'

const props = defineProps({
  columnKey: { type: String, required: true },
  value: { type: [String, Number, Boolean, Object, Array, Date], required: false, default: '' },
})
</script>

<template>
  {{ value }}
</template>
