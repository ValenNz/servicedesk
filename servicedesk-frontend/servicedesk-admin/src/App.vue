<script setup>
import { onMounted } from 'vue'
import { RouterView } from 'vue-router'
import { useAdminStore } from './stores/adminStore'

const adminStore = useAdminStore()

onMounted(async () => {
  console.log('Memuat data dari JSON Server...')
  await adminStore.fetchData()
  console.log('Data berhasil dimuat:', {
    tickets: adminStore.tickets.length,
    users: adminStore.users.length,
    categories: adminStore.categories.length,
    employees: adminStore.employees.length,
        activities: adminStore.activities.length 

  })
})
</script>

<template>
  <div class="min-h-screen bg-gray-50">
    <RouterView />
  </div>
</template>

<style>
</style>