<script setup>
import { ref } from 'vue'
import AdminSidebar from '@/Components/AdminSidebar.vue'
import DynamicTable from '@/Components/DynamicTable.vue'

const tableData = ref([
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
  { id: 13, name: 'Cooper', type: 'Dog', breed: 'Border Collie', age: 2, status: 'Adopted', shelter: 'Happy Paws', created_at: '2024-01-17' },
  { id: 14, name: 'Sophie', type: 'Cat', breed: 'Bengal', age: 1, status: 'Available', shelter: 'Cat Care', created_at: '2024-01-26' },
  { id: 15, name: 'Tucker', type: 'Dog', breed: 'Husky', age: 4, status: 'Available', shelter: 'Happy Paws', created_at: '2024-01-23' },
  { id: 16, name: 'Milo', type: 'Cat', breed: 'Sphynx', age: 2, status: 'Available', shelter: 'Cat Care', created_at: '2024-01-24' },
  { id: 17, name: 'Bailey', type: 'Dog', breed: 'Cocker Spaniel', age: 3, status: 'Adopted', shelter: 'Happy Paws', created_at: '2024-01-13' },
  { id: 18, name: 'Chloe', type: 'Cat', breed: 'Abyssinian', age: 1, status: 'Available', shelter: 'Cat Care', created_at: '2024-01-27' },
  { id: 19, name: 'Bear', type: 'Dog', breed: 'Saint Bernard', age: 5, status: 'Available', shelter: 'Happy Paws', created_at: '2024-01-29' },
  { id: 20, name: 'Leo', type: 'Cat', breed: 'Norwegian Forest', age: 3, status: 'Available', shelter: 'Cat Care', created_at: '2024-01-30' },
])

const currentPage = ref(1)
const itemsPerPage = 10

const handlePageChange = (page) => {
  currentPage.value = page
}
</script>

<template>
  <div class="flex flex-row min-h-screen">
    <AdminSidebar />
    <div class="flex-1 bg-gray-100">
      <div class="p-6">
        <DynamicTable 
          :data="tableData"
          :current-page="currentPage"
          :items-per-page="itemsPerPage"
          @page-change="handlePageChange"
        />
      </div>
    </div>
  </div>
</template>