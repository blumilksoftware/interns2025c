<script setup>
import { computed } from 'vue'
import StatusBadge from './StatusBadge.vue'
import { getKindForColumn } from '../data/columnRenderers.js'

const props = defineProps({
  columnKey: { type: String, required: true },
  value: { type: [String, Number, Boolean, Object, Array, Date], required: false, default: '' },
})

const kind = computed(() => getKindForColumn(props.columnKey))

function formatDate(v) {
  const d = new Date(v)
  return isNaN(d.getTime()) ? String(v ?? '') : d.toLocaleDateString()
}
</script>

<template>
  <StatusBadge v-if="kind === 'status'" :status="value" />

  <template v-else-if="kind === 'ip'">
    <span class="font-mono text-xs">{{ value }}</span>
  </template>

  <template v-else-if="kind === 'email'">
    <a :href="`mailto:${value}`" class="text-indigo-600 hover:text-indigo-800">{{ value }}</a>
  </template>

  <template v-else-if="kind === 'details'">
    <span class="truncate block max-w-xs" :title="String(value)">{{ value }}</span>
  </template>

  <template v-else-if="kind === 'date'">
    {{ formatDate(value) }}
  </template>

  <template v-else-if="kind === 'rating'">
    {{ value }}/5
  </template>

  <template v-else-if="kind === 'age'">
    <span class="font-mono text-xs">{{ value }}</span>
  </template>

  <template v-else>
    {{ value }}
  </template>
</template>