<script setup>
import { computed } from 'vue'
import StatusBadge from './StatusBadge.vue'
import Pagination from './Pagination.vue'

const props = defineProps({
  data: {
    type: Array,
    required: true
  },
  itemsPerPage: {
    type: Number,
    default: 10
  },
  currentPage: {
    type: Number,
    default: 1
  }
})

const emit = defineEmits(['page-change', 'edit-item'])

// Automatyczne wykrywanie kolumn
const columns = computed(() => {
  if (props.data.length === 0) return []
  
  const firstRow = props.data[0]
  return Object.keys(firstRow).map(key => ({
    key,
    label: key.charAt(0).toUpperCase() + key.slice(1).replace('_', ' '),
    width: getColumnWidth(key, firstRow[key])
  }))
})

// Funkcja określająca szerokość kolumny na podstawie typu danych
function getColumnWidth(key, value) {
  const type = typeof value
  
  switch (key) {
    case 'id':
      return 'w-16' // 64px
    case 'name':
      return 'w-32' // 128px
    case 'type':
      return 'w-20' // 80px
    case 'breed':
      return 'w-36' // 144px
    case 'age':
      return 'w-16' // 64px
    case 'status':
      return 'w-24' // 96px
    case 'shelter':
      return 'w-40' // 160px
    case 'created_at':
      return 'w-32' // 128px
    case 'email':
      return 'w-48' // 192px
    case 'role':
      return 'w-24' // 96px
    case 'last_login':
      return 'w-32' // 128px
    case 'location':
      return 'w-32' // 128px
    case 'capacity':
      return 'w-20' // 80px
    case 'current_occupancy':
      return 'w-28' // 112px
    case 'rating':
      return 'w-20' // 80px
    case 'action':
      return 'w-32' // 128px
    case 'user':
      return 'w-48' // 192px
    case 'ip_address':
      return 'w-36' // 144px
    case 'timestamp':
      return 'w-40' // 160px
    default:
      return 'w-auto'
  }
}

// Funkcja formatująca wartości
function formatValue(key, value) {
  switch (key) {
    case 'created_at':
    case 'last_login':
    case 'timestamp':
      return new Date(value).toLocaleDateString()
    case 'rating':
      return `${value}/5`
    default:
      return value
  }
}

// Obliczenia paginacji
const totalPages = computed(() => Math.ceil(props.data.length / props.itemsPerPage))
const paginatedData = computed(() => {
  const start = (props.currentPage - 1) * props.itemsPerPage
  const end = start + props.itemsPerPage
  return props.data.slice(start, end)
})

const handlePageChange = (page) => {
  emit('page-change', page)
}

const handleEdit = (item) => {
  emit('edit-item', item)
}
</script>

<template>
  <div class="bg-white shadow-sm rounded-lg overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
      <h3 class="text-lg font-medium text-gray-900">Dynamic Data Table</h3>
      <p class="text-sm text-gray-500">Automatycznie dostosowuje się do danych z bazy</p>
    </div>
    
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th 
              v-for="column in columns" 
              :key="column.key"
              :class="[column.width, 'px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider']"
            >
              {{ column.label }}
            </th>
            <th class="w-20 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Actions
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="row in paginatedData" :key="row.id" class="hover:bg-gray-50">
            <td 
              v-for="column in columns" 
              :key="column.key"
              :class="[column.width, 'px-6 py-4 whitespace-nowrap text-sm text-gray-900']"
            >
              <StatusBadge v-if="column.key === 'status'" :status="row[column.key]" />
              <span v-else>{{ formatValue(column.key, row[column.key]) }}</span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
              <button
                @click="handleEdit(row)"
                class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200"
              >
                Edit
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    
    <!-- Pagination -->
    <Pagination 
      :current-page="currentPage"
      :total-pages="totalPages"
      :total-items="data.length"
      :items-per-page="itemsPerPage"
      @page-change="handlePageChange"
    />
  </div>
</template> 