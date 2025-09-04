<script setup>
import { computed, ref, watch } from 'vue'
import { useI18n } from 'vue-i18n'
import CellContent from './CellContent.vue'
import Pagination from './Pagination.vue'
import { getColumnLabel, getColumnOptions, getColumnRenderer, getColumnType } from '@/data/columns/index'
import { router } from '@inertiajs/vue3'
import { routes } from '@/routes'
import OptionButton from '@/Components/Buttons/OptionButton.vue'
import { ArchiveBoxXMarkIcon, CheckCircleIcon, PencilSquareIcon } from '@heroicons/vue/20/solid'

const { t } = useI18n()

function formatDateForFilter(v) {
  const d = new Date(v)
  if (isNaN(d.getTime())) {
    return String(v ?? '')
  }
  
  const day = String(d.getDate()).padStart(2, '0')
  const month = String(d.getMonth() + 1).padStart(2, '0')
  const year = d.getFullYear()
  
  return `${day}-${month}-${year}`
}

function getFilterType(dataSetType, columnKey) {
  const columnType = getColumnType(dataSetType, columnKey)
  
  if (columnType === 'datetime-local' || columnType === 'date') {
    return 'date'
  }
  
  if (columnType === 'select') {
    return 'select'
  }
  
  return 'text'
}

const props = defineProps({
  data: {
    type: Array,
    required: true,
  },
  dataSetType: {
    type: String,
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

watch(() => props.dataSetType, () => {
  filters.value = {}
})

const columns = computed(() => {
  if (props.data.length === 0) return []
  
  const firstRow = props.data[0]
  return Object.keys(firstRow).map(key => ({
    key,
    label: getColumnLabel(props.dataSetType, key),
    renderer: getColumnRenderer(props.dataSetType, key),
  }))
})

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

const handleAcceptPet = (item) => {
  router.post(routes.admin.acceptPet(item.id))
}

const handleRejectPet = (item) => {
  router.delete(routes.admin.rejectPet(item.id))
}

const filteredData = computed(() => {
  let result = [...props.data]
  
  Object.keys(filters.value).forEach(key => {
    if (filters.value[key] && filters.value[key].trim() !== '') {
      result = result.filter(item => {
        const value = item[key]
        if (value === null || value === undefined) return false
        
        const filterValue = filters.value[key].toLowerCase()
        const filterType = getFilterType(props.dataSetType, key)
        
        if (filterType === 'date') {
          if (filterValue.includes('-')) {
            const parts = filterValue.split('-')
            
            const itemDate = new Date(value)
            if (isNaN(itemDate.getTime())) {
              return false
            }
            
            const itemDay = String(itemDate.getDate()).padStart(2, '0')
            const itemMonth = String(itemDate.getMonth() + 1).padStart(2, '0')
            const itemYear = itemDate.getFullYear().toString()
            
            if (parts.length >= 1 && parts[0]) {
              if (!itemDay.startsWith(parts[0])) {
                return false
              }
            }
            
            if (parts.length >= 2 && parts[1]) {
              if (!itemMonth.startsWith(parts[1])) {
                return false
              }
            }
            
            if (parts.length >= 3 && parts[2]) {
              if (!itemYear.startsWith(parts[2])) {
                return false
              }
            }
            
            return true
          }
          
          const itemDate = new Date(value)
          if (isNaN(itemDate.getTime())) {
            return false
          }
          
          const formattedDate = formatDateForFilter(value)
          return formattedDate.toLowerCase().includes(filterValue.toLowerCase())
        } else if (filterType === 'select') {
          return value.toString().toLowerCase() === filterValue
        } else {
          const isDateField = /(date|created_at|updated_at|timestamp|last_login)/i.test(key)
          
          let itemValue
          if (isDateField) {
            const originalValue = value.toString().toLowerCase()
            const formattedValue = formatDateForFilter(value).toLowerCase()
            itemValue = `${originalValue} ${formattedValue}`
          } else {
            itemValue = value.toString().toLowerCase()
          }
          
          return itemValue.includes(filterValue)
        }
      })
    }
  })
  
  if (sortColumn.value && sortDirection.value) {
    result.sort((a, b) => {
      const aValue = a[sortColumn.value]
      const bValue = b[sortColumn.value]
      
      if (aValue === null || aValue === undefined) return 1
      if (bValue === null || bValue === undefined) return -1
      
      let comparison
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

const handleDateFilter = (column, value) => {
  if (value && value.trim() !== '') {
    const parts = value.split('-')
    if (parts.length === 3) {
      const day = parts[0]
      const month = parts[1]
      const year = parts[2]
      
      if (day && month && year && day.length === 2 && month.length === 2 && year.length === 4) {
        filters.value[column] = value
      } else {
        filters.value[column] = value
      }
    } else {
      filters.value[column] = value
    }
  } else {
    delete filters.value[column]
  }
}

const clearFilters = () => {
  filters.value = {}
  sortColumn.value = ''
  sortDirection.value = 'asc'
}

const handleNativeDateChange = (columnKey, date) => {
  if (date) {
    const parts = date.split('-')
    filters.value[columnKey] = `${parts[2]}-${parts[1]}-${parts[0]}`
  }
}
</script>

<template>
  <div class="bg-white shadow-sm rounded-lg overflow-hidden w-full min-w-0">
    <div class="p-4 sm:px-6 border-b border-gray-200">
      <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
        <div>
          <h3 class="text-base lg:text-lg font-medium text-gray-900">{{ t('admin.table.title') }}</h3>
          <p class="text-xs lg:text-sm text-gray-500">{{ t('admin.table.subtitle') }}</p>
        </div>
        <div class="flex flex-col lg:flex-row gap-2 w-full lg:w-auto">
          <button class="inline-flex items-center justify-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" @click="showFilters = !showFilters">
            <svg class="size-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z" /></svg>
            {{ t('admin.table.filters') }}
          </button>
          <button class="inline-flex items-center justify-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" @click="clearFilters">{{ t('admin.table.clearAll') }}</button>
        </div>
      </div>

      <div v-if="showFilters" class="mt-4 grid grid-cols-1 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        <div v-for="column in columns" :key="column.key" class="space-y-1">
          <label :for="`filter-${column.key}`" class="inline text-xs font-medium text-gray-700">{{ column.label }}</label>
          
          <div v-if="getFilterType(props.dataSetType, column.key) === 'date'" class="relative">
            <input 
              :id="`filter-${column.key}`" 
              v-model="filters[column.key]" 
              type="text" 
              class="block w-full pr-8 border-gray-300 rounded-md shadow-sm text-xs focus:ring-indigo-500 focus:border-indigo-500"
              placeholder="DD-MM-YYYY"
              @input="handleDateFilter(column.key, $event.target.value)"
            >
            <input
              :id="`date-picker-${column.key}`"
              type="date"
              class="absolute inset-y-0 right-[-12px] w-20 opacity-0 cursor-pointer"
              @change="handleNativeDateChange(column.key, $event.target.value)"
            >
            <button
              type="button"
              class="absolute inset-y-0 right-0 pr-2 flex items-center text-gray-400 hover:text-gray-600 pointer-events-none"
            >
              <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
            </button>
          </div>
          
          <select 
            v-else-if="getFilterType(props.dataSetType, column.key) === 'select'"
            :id="`filter-${column.key}`" 
            v-model="filters[column.key]" 
            class="block w-full border-gray-300 rounded-md shadow-sm text-xs focus:ring-indigo-500 focus:border-indigo-500"
            @change="handleFilter(column.key, $event.target.value)"
          >
            <option value="">{{ t('admin.table.all') }} {{ column.label.toLowerCase() }}</option>
            <option v-for="option in getColumnOptions(props.dataSetType, column.key)" :key="option" :value="option">
              {{ option }}
            </option>
          </select>
          
          <input 
            v-else
            :id="`filter-${column.key}`" 
            v-model="filters[column.key]" 
            type="text" 
            class="block w-full border-gray-300 rounded-md shadow-sm text-xs focus:ring-indigo-500 focus:border-indigo-500" 
            :placeholder="t('admin.table.filterPlaceholder', { field: column.label.toLowerCase() })" 
            @input="handleFilter(column.key, $event.target.value)"
          >
        </div>
      </div>
    </div>

    <div class="overflow-x-auto max-w-full min-w-0">
      <table class="divide-y divide-gray-200 ">
        <thead class="bg-gray-50">
          <tr>
            <th v-for="column in columns" :key="column.key" class="text-wrap break-words p-2 sm:px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 select-none" @click="handleSort(column.key)">
              <div class="flex items-center justify-between">
                <span>{{ column.label }}</span>
                <div class="flex flex-col ml-1">
                  <svg v-if="sortColumn === column.key && sortDirection === 'asc'" class="size-3 text-indigo-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" /></svg>
                  <svg v-else-if="sortColumn === column.key && sortDirection === 'desc'" class="size-3 text-indigo-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                  <svg v-else class="size-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                </div>
              </div>
            </th>
            <th class="w-20 p-3 sm:px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ t('admin.table.actions') }}</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200 ">
          <tr v-for="row in paginatedData" :key="row.id" class="hover:bg-gray-50">
            <td v-for="column in columns" :key="column.key" class="p-2 sm:px-4 text-sm text-center text-gray-900 border-2 border-gray-100 ">
              <div class="overflow-auto h-30 w-auto flex justify-center items-center">
                <CellContent :column-key="column.key" :value="row[column.key] ?? '-' " />
              </div>
            </td>
            <td class="p-2 sm:px-4 text-sm text-gray-900">
              <div class="flex flex-col items-stretch gap-2 ">
                <OptionButton :icon="PencilSquareIcon" :text="t('admin.table.edit')" :class="'bg-indigo-600 hover:bg-indigo-500 focus-visible:outline-indigo-600'" @click="handleEdit(row)" />
                <OptionButton v-if="props.dataSetType === 'incomingPetsRequests'" :icon="CheckCircleIcon" :text="t('admin.table.accept')" :class="'bg-lime-600 hover:bg-lime-500 focus-visible:outline-lime-600'" @click="handleAcceptPet(row)" />
                <OptionButton v-if="props.dataSetType === 'incomingPetsRequests'" :icon="ArchiveBoxXMarkIcon" :text="t('admin.table.reject')" :class="'bg-red-600 hover:bg-red-500 focus-visible:outline-red-600'" @click="handleRejectPet(row)" />
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <Pagination :current-page="currentPage" :total-pages="totalPages" :total-items="filteredData.length" :items-per-page="itemsPerPage" @page-change="handlePageChange" />
  </div>
</template> 
