<script setup>
import { computed } from 'vue'
import { getKindForColumn } from '@/data/columnRenderers.js'
import { formatDateTime } from '@/helpers/dateTimeHelper.js'

const props = defineProps({
  columnKey: { type: String, required: true },
  value: { type: [String, Number, Boolean, Object, Array, Date], required: false, default: '' },
})

const kind = computed(() => getKindForColumn(props.columnKey))

const formattedValue = computed(() => {
  if (props.value === null || props.value === undefined) return '-'
  
  switch (kind.value) {
  case 'date':
    return formatDateTime(props.value)
  case 'email':
    return props.value
  case 'status':
    return props.value
  case 'ip':
    return props.value
  case 'details':
    return truncateText(props.value, 50)
  case 'rating':
    return props.value
  case 'age':
    return props.value
  case 'array':
    return props.value.join(', ')
  case 'tags':
    return Array.isArray(props.value)
      ? props.value.map(tag => tag?.name)
      : []
  default:
    return props.value
  }
})


function truncateText(text, maxLength) {
  if (!text || typeof text !== 'string') return text
  return text.length > maxLength ? text.substring(0, maxLength) + '...' : text
}
</script>

<template>
  <div class="flex items-center justify-center">
    <span v-if="kind === 'email'" class="text-blue-600 hover:text-blue-800">
      {{ formattedValue }}
    </span>
    <span v-else-if="kind === 'date'" class="text-gray-600 text-sm font-mono">
      {{ formattedValue }}
    </span>
    <div v-else-if="kind === 'tags'" class="flex flex-wrap gap-1 items-center">
      <span 
        v-for="(tagName, i) in formattedValue" 
        :key="i"
        class="px-3 text-sm font-medium text-gray-800"
      >
        {{ tagName }}
      </span>
    </div>
    <span v-else>
      {{ formattedValue }}
    </span>
  </div>
</template>
