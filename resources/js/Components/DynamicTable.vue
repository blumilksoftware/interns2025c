<script setup>
import { computed, ref } from 'vue'
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

// State for filtering and sorting
const filters = ref({})
const sortColumn = ref('')
const sortDirection = ref('asc')
const showFilters = ref(false)

// Automatic column detection
const columns = computed(() => {
  if (props.data.length === 0) return []
  
  const firstRow = props.data[0]
  return Object.keys(firstRow).map(key => ({
    key,
    label: key.charAt(0).toUpperCase() + key.slice(1).replace('_', ' '),
    width: getColumnWidth(key, firstRow[key])
  }))
})

// Function determining column width based on data type
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
    case 'user_email':
      return 'w-48' // 192px
    case 'ip_address':
      return 'w-36' // 144px
    case 'timestamp':
      return 'w-40' // 160px
    case 'action':
      return 'w-32' // 128px
    case 'details':
      return 'w-64' // 256px
    case 'user_agent':
      return 'w-48' // 192px
    default:
      return 'w-auto'
  }
}

// Function formatting values
function formatValue(key, value) {
  switch (key) {
    case 'created_at':
    case 'last_login':
    case 'timestamp':
      return new Date(value).toLocaleDateString()
    case 'rating':
      return `${value}/5`
    case 'ip_address':
      return `<span class="font-mono text-xs">${value}</span>`
    case 'user_email':
      return `<a href="mailto:${value}" class="text-indigo-600 hover:text-indigo-800">${value}</a>`
    case 'email':
      return `<a href="mailto:${value}" class="text-indigo-600 hover:text-indigo-800">${value}</a>`
    case 'details':
    case 'user_agent':
      return `<span class="truncate block max-w-xs" title="${value}">${value}</span>`
    default:
      return value
  }
}

// Pagination calculations
const totalPages = computed(() => Math.ceil(filteredData.value.length / props.itemsPerPage))
const paginatedData = computed(() => {
  const start = (props.currentPage - 1) * props.itemsPerPage
  const end = start + props.itemsPerPage
  return filteredData.value.slice(start, end)
})

// Page change function
const handlePageChange = (page) => {
  emit('page-change', page)
}

// Edit item function
const handleEdit = (item) => {
  emit('edit-item', item)
}

// Data filtering function
const filteredData = computed(() => {
  let result = [...props.data]
  
  // Apply filters
  Object.keys(filters.value).forEach(key => {
    if (filters.value[key] && filters.value[key].trim() !== '') {
      result = result.filter(item => {
        const value = item[key]
        if (value === null || value === undefined) return false
        
        const filterValue = filters.value[key].toLowerCase()
        const itemValue = value.toString().toLowerCase()
        
        return itemValue.includes(filterValue)
      })
    }
  })
  
  // Apply sorting
  if (sortColumn.value && sortDirection.value) {
    result.sort((a, b) => {
      const aValue = a[sortColumn.value]
      const bValue = b[sortColumn.value]
      
      if (aValue === null || aValue === undefined) return 1
      if (bValue === null || bValue === undefined) return -1
      
      let comparison = 0
      if (typeof aValue === 'number' && typeof bValue === 'number') {
        comparison = aValue - bValue
      } else {
        comparison = aValue.toString().localeCompare(bValue.toString())
      }
      
      return sortDirection.value === 'asc' ? comparison : -comparison
    })
  }
  
  return result
})

// Sorting function
const handleSort = (column) => {
  if (sortColumn.value === column) {
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc'
  } else {
    sortColumn.value = column
    sortDirection.value = 'asc'
  }
}

// Filtering function
const handleFilter = (column, value) => {
  if (value && value.trim() !== '') {
    filters.value[column] = value
  } else {
    delete filters.value[column]
  }
}

// Clear filters function
const clearFilters = () => {
  filters.value = {}
  sortColumn.value = ''
  sortDirection.value = 'asc'
}
</script>

<template>
  <div class="bg-white shadow-sm rounded-lg overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
      <div class="flex justify-between items-center">
        <div>
          <h3 class="text-lg font-medium text-gray-900">Dynamic Data Table</h3>
          <p class="text-sm text-gray-500">Automatycznie dostosowuje się do danych z bazy</p>
        </div>
        <div class="flex space-x-2">
          <button
            @click="showFilters = !showFilters"
            class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"></path>
            </svg>
            Filters
          </button>
          <button
            @click="clearFilters"
            class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            Clear All
          </button>
        </div>
      </div>
      
      <!-- Filters -->
      <div v-if="showFilters" class="mt-4 grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <div v-for="column in columns" :key="column.key" class="space-y-1">
          <label :for="`filter-${column.key}`" class="block text-xs font-medium text-gray-700">
            {{ column.label }}
          </label>
          <input
            :id="`filter-${column.key}`"
            v-model="filters[column.key]"
            @input="handleFilter(column.key, $event.target.value)"
            type="text"
            class="block w-full border-gray-300 rounded-md shadow-sm text-xs focus:ring-indigo-500 focus:border-indigo-500"
            :placeholder="`Filter ${column.label.toLowerCase()}...`"
          />
        </div>
      </div>
    </div>
    
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th 
              v-for="column in columns" 
              :key="column.key"
              @click="handleSort(column.key)"
              :class="[
                column.width, 
                'px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 select-none'
              ]"
            >
              <div class="flex items-center justify-between">
                <span>{{ column.label }}</span>
                <div class="flex flex-col ml-1">
                  <svg 
                    v-if="sortColumn === column.key && sortDirection === 'asc'"
                    class="w-3 h-3 text-indigo-600" 
                    fill="currentColor" 
                    viewBox="0 0 20 20"
                  >
                    <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                  </svg>
                  <svg 
                    v-else-if="sortColumn === column.key && sortDirection === 'desc'"
                    class="w-3 h-3 text-indigo-600" 
                    fill="currentColor" 
                    viewBox="0 0 20 20"
                  >
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                  </svg>
                  <svg 
                    v-else
                    class="w-3 h-3 text-gray-400" 
                    fill="currentColor" 
                    viewBox="0 0 20 20"
                  >
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                  </svg>
                </div>
              </div>
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
              <span v-else v-html="formatValue(column.key, row[column.key])"></span>
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
    
    <!-- Results information -->
    <div class="px-6 py-3 bg-gray-50 border-t border-gray-200">
      <p class="text-sm text-gray-600">
        Showing {{ paginatedData.length }} of {{ filteredData.length }} results
        <span v-if="Object.keys(filters).length > 0" class="text-indigo-600">
          (filtered from {{ data.length }} total)
        </span>
      </p>
    </div>
    
    <!-- Pagination -->
    <Pagination 
      :current-page="currentPage"
      :total-pages="totalPages"
      :total-items="filteredData.length"
      :items-per-page="itemsPerPage"
      @page-change="handlePageChange"
    />
  </div>
</template> 