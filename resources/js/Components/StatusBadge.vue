<script setup>
defineProps({
  status: {
    type: String,
    required: true
  }
})

const badges = {
  'Available': 'bg-green-100 text-green-800',
  'Adopted': 'bg-blue-100 text-blue-800',
  'Pending': 'bg-yellow-100 text-yellow-800'
}
</script>

<template>
  <span class="px-2 py-1 text-xs font-medium rounded-full" :class="badges[status] || 'bg-gray-100 text-gray-800'">
    {{ status }}
  </span>
</template> 