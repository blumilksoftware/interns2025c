<script setup>
import { ref, computed } from 'vue'
import AdminSidebar from '@/Components/AdminSidebar.vue'
import DynamicTable from '@/Components/DynamicTable.vue'
import EditModal from '@/Components/EditModal.vue'

const dataSets = {
  pets: [
    { id: 1, name: 'Rex', type: 'Dog', breed: 'Golden Retriever', age: 5, status: 'Available', shelter: 'Happy Paws', created_at: '2024-01-15' },
    { id: 2, name: 'Whiskers', type: 'Cat', breed: 'Persian', age: 3, status: 'Adopted', shelter: 'Cat Care', created_at: '2024-01-10' },
    { id: 3, name: 'Buddy', type: 'Dog', breed: 'Labrador', age: 2, status: 'Available', shelter: 'Happy Paws', created_at: '2024-01-20' },
    { id: 4, name: 'Luna', type: 'Cat', breed: 'Siamese', age: 1, status: 'Available', shelter: 'Cat Care', created_at: '2024-01-25' },
    { id: 5, name: 'Max', type: 'Dog', breed: 'German Shepherd', age: 4, status: 'Available', shelter: 'Happy Paws', created_at: '2024-01-18' },
    { id: 6, name: 'Mittens', type: 'Cat', breed: 'Maine Coon', age: 2, status: 'Adopted', shelter: 'Cat Care', created_at: '2024-01-12' },
    { id: 7, name: 'Rocky', type: 'Dog', breed: 'Bulldog', age: 3, status: 'Available', shelter: 'Happy Paws', created_at: '2024-01-22' },
    { id: 8, name: 'Shadow', type: 'Cat', breed: 'Russian Blue', age: 4, status: 'Available', shelter: 'Cat Care', created_at: '2024-01-16' },
    { id: 9, name: 'Daisy', type: 'Dog', breed: 'Beagle', age: 1, status: 'Available', shelter: 'Happy Paws', created_at: '2024-01-28' },
    { id: 10, name: 'Oliver', type: 'Cat', breed: 'British Shorthair', age: 2, status: 'Adopted', shelter: 'Cat Care', created_at: '2024-01-14' },
    { id: 11, name: 'Charlie', type: 'Dog', breed: 'Poodle', age: 5, status: 'Available', shelter: 'Happy Paws', created_at: '2024-01-19' },
    { id: 12, name: 'Bella', type: 'Cat', breed: 'Ragdoll', age: 3, status: 'Available', shelter: 'Cat Care', created_at: '2024-01-21' },
  ],
  users: [
    { id: 1, name: 'John Doe', email: 'john@example.com', role: 'Admin', status: 'Active', last_login: '2024-01-30', created_at: '2024-01-01' },
    { id: 2, name: 'Jane Smith', email: 'jane@example.com', role: 'User', status: 'Active', last_login: '2024-01-29', created_at: '2024-01-02' },
    { id: 3, name: 'Bob Johnson', email: 'bob@example.com', role: 'Moderator', status: 'Inactive', last_login: '2024-01-25', created_at: '2024-01-03' },
    { id: 4, name: 'Alice Brown', email: 'alice@example.com', role: 'User', status: 'Active', last_login: '2024-01-30', created_at: '2024-01-04' },
    { id: 5, name: 'Charlie Wilson', email: 'charlie@example.com', role: 'Admin', status: 'Active', last_login: '2024-01-28', created_at: '2024-01-05' },
    { id: 6, name: 'Diana Davis', email: 'diana@example.com', role: 'User', status: 'Active', last_login: '2024-01-27', created_at: '2024-01-06' },
    { id: 7, name: 'Edward Miller', email: 'edward@example.com', role: 'Moderator', status: 'Inactive', last_login: '2024-01-20', created_at: '2024-01-07' },
    { id: 8, name: 'Fiona Garcia', email: 'fiona@example.com', role: 'User', status: 'Active', last_login: '2024-01-30', created_at: '2024-01-08' },
    { id: 9, name: 'George Martinez', email: 'george@example.com', role: 'Admin', status: 'Active', last_login: '2024-01-29', created_at: '2024-01-09' },
    { id: 10, name: 'Helen Taylor', email: 'helen@example.com', role: 'User', status: 'Active', last_login: '2024-01-26', created_at: '2024-01-10' },
  ],
  shelters: [
    { id: 1, name: 'Happy Paws', location: 'Warsaw', capacity: 50, current_occupancy: 35, rating: 4.8, status: 'Active', created_at: '2024-01-01' },
    { id: 2, name: 'Cat Care', location: 'Krakow', capacity: 30, current_occupancy: 28, rating: 4.9, status: 'Active', created_at: '2024-01-02' },
    { id: 3, name: 'Animal Haven', location: 'Gdansk', capacity: 40, current_occupancy: 22, rating: 4.7, status: 'Active', created_at: '2024-01-03' },
    { id: 4, name: 'Pet Paradise', location: 'Wroclaw', capacity: 60, current_occupancy: 45, rating: 4.6, status: 'Active', created_at: '2024-01-04' },
    { id: 5, name: 'Furry Friends', location: 'Poznan', capacity: 35, current_occupancy: 30, rating: 4.5, status: 'Active', created_at: '2024-01-05' },
    { id: 6, name: 'Safe Haven', location: 'Lodz', capacity: 25, current_occupancy: 18, rating: 4.8, status: 'Active', created_at: '2024-01-06' },
    { id: 7, name: 'Companion Care', location: 'Szczecin', capacity: 45, current_occupancy: 38, rating: 4.7, status: 'Active', created_at: '2024-01-07' },
    { id: 8, name: 'Animal Rescue', location: 'Bydgoszcz', capacity: 30, current_occupancy: 25, rating: 4.6, status: 'Active', created_at: '2024-01-08' },
  ],
  logs: [
    { id: 1, action: 'User Login', user_email: 'john@example.com', ip_address: '192.168.1.100', status: 'Success', timestamp: '2024-01-30 10:30:00' },
    { id: 2, action: 'Pet Added', user_email: 'jane@example.com', ip_address: '192.168.1.101', status: 'Success', timestamp: '2024-01-30 10:25:00' },
    { id: 3, action: 'User Logout', user_email: 'bob@example.com', ip_address: '192.168.1.102', status: 'Success', timestamp: '2024-01-30 10:20:00' },
    { id: 4, action: 'Shelter Updated', user_email: 'admin@example.com', ip_address: '192.168.1.103', status: 'Success', timestamp: '2024-01-30 10:15:00' },
    { id: 5, action: 'Failed Login', user_email: 'unknown@example.com', ip_address: '192.168.1.104', status: 'Failed', timestamp: '2024-01-30 10:10:00' },
    { id: 6, action: 'Pet Adopted', user_email: 'charlie@example.com', ip_address: '192.168.1.105', status: 'Success', timestamp: '2024-01-30 10:05:00' },
    { id: 7, action: 'User Created', user_email: 'admin@example.com', ip_address: '192.168.1.106', status: 'Success', timestamp: '2024-01-30 10:00:00' },
    { id: 8, action: 'Data Export', user_email: 'diana@example.com', ip_address: '192.168.1.107', status: 'Success', timestamp: '2024-01-30 09:55:00' },
    { id: 9, action: 'System Backup', user_email: 'system', ip_address: '192.168.1.108', status: 'Success', timestamp: '2024-01-30 09:50:00' },
    { id: 10, action: 'Error Logged', user: 'fiona@example.com', ip_address: '192.168.1.109', status: 'Error', timestamp: '2024-01-30 09:45:00' },
  ]
}

const currentDataSet = ref('pets')
const currentPage = ref(1)
const itemsPerPage = 10
const searchQuery = ref('')
const selectedColumn = ref('all')

const availableColumns = computed(() => {
  if (dataSets[currentDataSet.value].length === 0) return []
  
  const firstRow = dataSets[currentDataSet.value][0]
  const columns = Object.keys(firstRow).map(key => ({
    value: key,
    label: key.charAt(0).toUpperCase() + key.slice(1).replace('_', ' ')
  }))
  
  return [
    { value: 'all', label: 'All columns' },
    ...columns
  ]
})

const filteredData = computed(() => {
  if (!searchQuery.value.trim()) {
    return dataSets[currentDataSet.value]
  }
  
  const query = searchQuery.value.toLowerCase()
  return dataSets[currentDataSet.value].filter(item => {
    if (selectedColumn.value === 'all') {
      return Object.values(item).some(value => 
        String(value).toLowerCase().includes(query)
      )
    } else {
      const value = item[selectedColumn.value]
      return String(value).toLowerCase().includes(query)
    }
  })
})

const tableData = computed(() => filteredData.value)
  
const isModalOpen = ref(false)
const editingItem = ref({})

const handleDataSetChange = (dataSetKey) => {
  currentDataSet.value = dataSetKey
  currentPage.value = 1
  searchQuery.value = ''
  selectedColumn.value = 'all'
}

const handlePageChange = (page) => {
  currentPage.value = page
}

const handleEditItem = (item) => {
  editingItem.value = item
  isModalOpen.value = true
}

const closeModal = () => {
  isModalOpen.value = false
  editingItem.value = {}
}

const saveChanges = (updatedItem) => {
  const index = tableData.value.findIndex(item => item.id === updatedItem.id)
  if (index !== -1) {
    tableData.value[index] = { ...updatedItem }
    
    const dataSetKey = currentDataSet.value
    const dataSetIndex = dataSets[dataSetKey].findIndex(item => item.id === updatedItem.id)
    if (dataSetIndex !== -1) {
      dataSets[dataSetKey][dataSetIndex] = { ...updatedItem }
    }
  }
  
  closeModal()
}

const clearSearch = () => {
  searchQuery.value = ''
}
</script>

<template>
  <div class="flex flex-col xl:flex-row min-h-screen bg-gray-100">
    <AdminSidebar @data-set-change="handleDataSetChange" />
    <div class="flex-1">
      <div class="w-full px-2 sm:px-4 md:px-6 py-4">
        <div class="mb-2 sm:mb-4">
          <h2 class="text-sm sm:text-base md:text-lg font-medium text-gray-900">
            Currently viewing: <span class="text-blue-600 capitalize">{{ currentDataSet }}</span>
            <span class="text-xs sm:text-sm text-gray-500 ml-2">({{ tableData.length }} records)</span>
          </h2>
        </div>
        <div class="mb-2 sm:mb-4">
          <div class="flex flex-col sm:flex-row gap-2 sm:gap-4 items-start sm:items-end">
            <div class="w-full sm:flex-1">
              <label for="column-select" class="block text-sm font-medium text-gray-700 mb-1">
                Search in column
              </label>
              <select
                id="column-select"
                v-model="selectedColumn"
                class="block w-full border border-gray-300 rounded-md leading-5 bg-white text-gray-900 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
              >
                <option 
                  v-for="column in availableColumns" 
                  :key="column.value" 
                  :value="column.value"
                >
                  {{ column.label }}
                </option>
              </select>
            </div>
            <div class="w-full sm:flex-1">
              <label for="search-input" class="block text-sm font-medium text-gray-700 mb-1">
                Search query
              </label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                  </svg>
                </div>
                <input
                  id="search-input"
                  v-model="searchQuery"
                  type="text"
                  :placeholder="selectedColumn === 'all' ? 'Search in all fields...' : `Search in ${availableColumns.find(col => col.value === selectedColumn)?.label.toLowerCase()}...`"
                  class="block w-full pl-10 pr-10 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                />
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                  <button
                    v-if="searchQuery"
                    @click="clearSearch"
                    type="button"
                    class="text-gray-400 hover:text-gray-600 focus:outline-none focus:text-gray-600"
                  >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20" aria-hidden="true">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                  </button>
                </div>
              </div>
            </div>
          </div>
          <div v-if="searchQuery" class="mt-2 text-sm text-gray-600">
            Showing {{ tableData.length }} of {{ dataSets[currentDataSet].length }} results for "{{ searchQuery }}"
            <span v-if="selectedColumn !== 'all'">
              in {{ availableColumns.find(col => col.value === selectedColumn)?.label.toLowerCase() }}
            </span>
          </div>
        </div>
        <div class="overflow-x-auto">
          <DynamicTable 
            :data="tableData"
            :current-page="currentPage"
            :items-per-page="itemsPerPage"
            @page-change="handlePageChange"
            @edit-item="handleEditItem"
          />
        </div>
      </div>
      <EditModal
        :is-open="isModalOpen"
        :item="editingItem"
        :data-set-type="currentDataSet"
        @close="closeModal"
        @save="saveChanges"
      />
    </div>
  </div>
</template>