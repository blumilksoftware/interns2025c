<script setup>
import { computed, ref } from 'vue'
import CellContent from './CellContent.vue'
import Pagination from './Pagination.vue'
import { getWidthByPattern } from '../data/columnWidths.js'

const props = defineProps({
  data: {
    type: Array,
    required: true,
  },
  itemsPerPage: {
    type: Number,
    default: 10,
  },
  currentPage: {
    type: Number,
    default: 1,
  },
})

const emit = defineEmits(['page-change', 'edit-item'])

const filters = ref({})
const sortColumn = ref('')
const sortDirection = ref('asc')
const showFilters = ref(false)

const columns = computed(() => {
  if (props.data.length === 0) return []
  
  const firstRow = props.data[0]
  return Object.keys(firstRow).map(key => ({
    key,
    label: key.charAt(0).toUpperCase() + key.slice(1).replace('_', ' '),
    width: getColumnWidth(key, firstRow[key], props.data),
  }))
})

function getColumnWidth(key, value, allData = []) {
  const patternWidth = getWidthByPattern(key)
  if (patternWidth) {
    return patternWidth
  }

  const type = typeof value
  const isNumber = type === 'number'
  const isBoolean = type === 'boolean'
  const isDate = value instanceof Date || (typeof value === 'string' && /^\d{4}-\d{2}-\d{2}/.test(value))

  if (isNumber) return 'w-16 sm:w-20'
  if (isBoolean) return 'w-16 sm:w-20'
  if (isDate) return 'w-24 sm:w-32'

  if (allData.length > 0) {
    const maxLength = Math.max(...allData.map(row => {
      const val = row[key]
      return val ? val.toString().length : 0
    }))

    if (maxLength <= 5) return 'w-12 sm:w-16'
    if (maxLength <= 10) return 'w-20 sm:w-24'
    if (maxLength <= 20) return 'w-28 sm:w-36'
    if (maxLength <= 40) return 'w-36 sm:w-48'
    if (maxLength <= 80) return 'w-48 sm:w-64'
  }

  return 'w-32 sm:w-40'
}

const totalPages = computed(() => Math.ceil(filteredData.value.length / props.itemsPerPage))
const paginatedData = computed(() => {
  const start = (props.currentPage - 1) * props.itemsPerPage
  const end = start + props.itemsPerPage
  return filteredData.value.slice(start, end)
})

const handlePageChange = (page) => {
  emit('page-change', page)
}

const handleEdit = (item) => {
  emit('edit-item', item)
}

const filteredData = computed(() => {
  let result = [...props.data]
  
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

const handleSort = (column) => {
  if (sortColumn.value === column) {
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc'
  } else {
    sortColumn.value = column
    sortDirection.value = 'asc'
  }
}

const handleFilter = (column, value) => {
  if (value && value.trim() !== '') {
    filters.value[column] = value
  } else {
    delete filters.value[column]
  }
}

const clearFilters = () => {
  filters.value = {}
  sortColumn.value = ''
  sortDirection.value = 'asc'
}
</script>

<template>
  <div class="bg-white shadow-sm rounded-lg overflow-hidden">
    <div class="p-4 sm:px-6 border-b border-gray-200">
      <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
        <div>
          <h3 class="text-base lg:text-lg font-medium text-gray-900">Dynamic Data Table</h3>
          <p class="text-xs lg:text-sm text-gray-500">Automatycznie dostosowuje się do danych z bazy</p>
        </div>
        <div class="flex flex-col lg:flex-row gap-2 w-full lg:w-auto">
          <button class="inline-flex items-center justify-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" @click="showFilters = !showFilters">
            <svg class="size-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z" /></svg>
            Filters
          </button>
          <button class="inline-flex items-center justify-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" @click="clearFilters">Clear All</button>
        </div>
      </div>

      <div v-if="showFilters" class="mt-4 grid grid-cols-1 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        <div v-for="column in columns" :key="column.key" class="space-y-1">
          <label :for="`filter-${column.key}`" class="block text-xs font-medium text-gray-700">{{ column.label }}</label>
          <input :id="`filter-${column.key}`" v-model="filters[column.key]" type="text" class="block w-full border-gray-300 rounded-md shadow-sm text-xs focus:ring-indigo-500 focus:border-indigo-500" :placeholder="`Filter ${column.label.toLowerCase()}...`" @input="handleFilter(column.key, $event.target.value)" />
        </div>
      </div>
    </div>

    <div class="overflow-auto max-h-[50vh]">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th v-for="column in columns" :key="column.key" :class="[column.width, 'p-2 sm:px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 select-none']" @click="handleSort(column.key)">
              <div class="flex items-center justify-between">
                <span>{{ column.label }}</span>
                <div class="flex flex-col ml-1">
                  <svg v-if="sortColumn === column.key && sortDirection === 'asc'" class="size-3 text-indigo-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" /></svg>
                  <svg v-else-if="sortColumn === column.key && sortDirection === 'desc'" class="size-3 text-indigo-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                  <svg v-else class="size-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                </div>
              </div>
            </th>
            <th class="w-20 p-3 sm:px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="row in paginatedData" :key="row.id" class="hover:bg-gray-50">
            <td v-for="column in columns" :key="column.key" :class="[column.width, 'p-2 sm:px-4 whitespace-nowrap text-sm text-gray-900']">
              <CellContent :column-key="column.key" :value="row[column.key]" />
            </td>
            <td class="p-2 sm:px-4 whitespace-nowrap text-sm text-gray-900">
              <button class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200" @click="handleEdit(row)">Edit</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <Pagination :current-page="currentPage" :total-pages="totalPages" :total-items="filteredData.length" :items-per-page="itemsPerPage" @page-change="handlePageChange" />
  </div>
</template> 
