<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue'
import { useI18n } from 'vue-i18n'
import AdminSidebar from '../../Components/AdminSidebar.vue'
import DynamicTable from '../../Components/DynamicTable.vue'
import EditModal from '../../Components/EditModal.vue'
import { dataSets } from '../../data/adminData.js'

import { Bars3Icon } from '@heroicons/vue/20/solid'

const { t } = useI18n()

function formatDateForSearch(v) {
  const d = new Date(v)
  if (isNaN(d.getTime())) {
    return String(v ?? '')
  }
  
  const day = String(d.getDate()).padStart(2, '0')
  const month = String(d.getMonth() + 1).padStart(2, '0')
  const year = d.getFullYear()
  
  return `${day}-${month}-${year}`
}

const currentDataSet = ref('pets')
const currentPage = ref(1)
const itemsPerPage = 10
const searchQuery = ref('')

const filteredData = computed(() => {
  if (!searchQuery.value.trim()) {
    return dataSets[currentDataSet.value]
  }
  
  const query = searchQuery.value.toLowerCase()
  return dataSets[currentDataSet.value].filter(item => {
    return Object.keys(item).some(key => {
      const value = item[key]
      if (value === null || value === undefined) return false
      
      const isDateField = /(date|created_at|updated_at|timestamp|last_login)/i.test(key)
      
      let searchValue
      if (isDateField) {
        const originalValue = String(value).toLowerCase()
        const formattedValue = formatDateForSearch(value).toLowerCase()
        searchValue = `${originalValue} ${formattedValue}`
      } else {
        searchValue = String(value).toLowerCase()
      }
      
      return searchValue.includes(query)
    })
  })
})

const tableData = computed(() => filteredData.value)
  
const isModalOpen = ref(false)
const editingItem = ref({})

const handleDataSetChange = (dataSetKey) => {
  currentDataSet.value = dataSetKey
  currentPage.value = 1
  searchQuery.value = ''
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

const isSidebarOpen = ref(false)

const openSidebar = () => {
  isSidebarOpen.value = true
}

const closeSidebar = () => {
  isSidebarOpen.value = false
}

function handleResize() {
  if (window.innerWidth >= 1280) {
    isSidebarOpen.value = false
  }
}

onMounted(() => {
  document.title = t('title.adminPanel')
  window.addEventListener('resize', handleResize)
})

onBeforeUnmount(() => {
  window.removeEventListener('resize', handleResize)
})
</script>

<template>
  <div class="flex flex-col xl:flex-row min-h-screen bg-gray-100">
    <button 
      class="xl:hidden fixed top-4 right-4 z-30 bg-gray-800/20 text-white p-2 rounded focus:outline-none focus:ring-2 focus:ring-gray-400" 
      @click="openSidebar"
    >
      <Bars3Icon class="size-6" />
    </button>
    
    <AdminSidebar 
      :is-open="isSidebarOpen"
      @data-set-change="handleDataSetChange" 
      @close="closeSidebar"
    />
    <div class="flex-1">
      <div class="w-full px-2 sm:px-4 md:px-6 py-4">
        <div class="mb-2 sm:mb-4">
          <h2 class="text-sm sm:text-base md:text-lg font-medium text-gray-900">
            {{ t('admin.currentlyViewing') }} <span class="text-blue-600 capitalize">{{ currentDataSet }}</span>
            <span class="text-xs sm:text-sm text-gray-500 ml-2">({{ tableData.length }} {{ t('admin.records') }})</span>
          </h2>
        </div>
        <div class="mb-2 sm:mb-4">
          <div class="w-full max-w-md">
            <label for="search-input" class="block text-sm font-medium text-gray-700 mb-1">
              {{ t('admin.searchInAllFields') }}
            </label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="size-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
              </div>
              <input
                id="search-input"
                v-model="searchQuery"
                type="text"
                :placeholder="t('admin.searchPlaceholder')"
                class="block w-full px-10 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder:text-gray-500 focus:outline-none focus:placeholder:text-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
              >
              <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                <button
                  v-if="searchQuery"
                  type="button"
                  class="text-gray-400 hover:text-gray-600 focus:outline-none focus:text-gray-600"
                  @click="clearSearch"
                >
                  <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
            </div>
          </div>
        </div>
        <div class="overflow-x-auto">
          <DynamicTable 
            :data="tableData"
            :data-set-type="currentDataSet"
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
