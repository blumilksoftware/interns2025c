<script setup>
import { ref } from 'vue'
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
    { id: 1, action: 'User Login', user: 'john@example.com', ip_address: '192.168.1.100', status: 'Success', timestamp: '2024-01-30 10:30:00' },
    { id: 2, action: 'Pet Added', user: 'jane@example.com', ip_address: '192.168.1.101', status: 'Success', timestamp: '2024-01-30 10:25:00' },
    { id: 3, action: 'User Logout', user: 'bob@example.com', ip_address: '192.168.1.102', status: 'Success', timestamp: '2024-01-30 10:20:00' },
    { id: 4, action: 'Shelter Updated', user: 'admin@example.com', ip_address: '192.168.1.103', status: 'Success', timestamp: '2024-01-30 10:15:00' },
    { id: 5, action: 'Failed Login', user: 'unknown@example.com', ip_address: '192.168.1.104', status: 'Failed', timestamp: '2024-01-30 10:10:00' },
    { id: 6, action: 'Pet Adopted', user: 'charlie@example.com', ip_address: '192.168.1.105', status: 'Success', timestamp: '2024-01-30 10:05:00' },
    { id: 7, action: 'User Created', user: 'admin@example.com', ip_address: '192.168.1.106', status: 'Success', timestamp: '2024-01-30 10:00:00' },
    { id: 8, action: 'Data Export', user: 'diana@example.com', ip_address: '192.168.1.107', status: 'Success', timestamp: '2024-01-30 09:55:00' },
    { id: 9, action: 'System Backup', user: 'system', ip_address: '192.168.1.108', status: 'Success', timestamp: '2024-01-30 09:50:00' },
    { id: 10, action: 'Error Logged', user: 'fiona@example.com', ip_address: '192.168.1.109', status: 'Error', timestamp: '2024-01-30 09:45:00' },
  ]
}

const currentDataSet = ref('pets')
const currentPage = ref(1)
const itemsPerPage = 10

const tableData = ref(dataSets.pets)
  
const isModalOpen = ref(false)
const editingItem = ref({})

const handleDataSetChange = (dataSetKey) => {
  currentDataSet.value = dataSetKey
  tableData.value = dataSets[dataSetKey]
  currentPage.value = 1
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
</script>

<template>
  <div class="flex flex-row min-h-screen">
    <AdminSidebar @data-set-change="handleDataSetChange" />
    
    <div class="flex-1 bg-gray-100">
      <div class="p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-4">Dashboard</h1>
        
        <div class="mb-6">
          <h2 class="text-lg font-medium text-gray-900">
            Currently viewing: <span class="text-blue-600 capitalize">{{ currentDataSet }}</span>
            <span class="text-sm text-gray-500 ml-2">({{ tableData.length }} records)</span>
          </h2>
        </div>
        
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
</template>