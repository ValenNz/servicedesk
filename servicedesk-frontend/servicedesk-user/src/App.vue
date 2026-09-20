<script setup>
import { onMounted } from 'vue'
import { RouterView } from 'vue-router'
import { useUserStore } from './stores/userStore'

const userStore = useUserStore()

onMounted(async () => {
  console.log('🔄 Memuat data dari JSON Server...')
  await userStore.fetchData()
  console.log('✅ Data berhasil dimuat:', {
    tickets: userStore.tickets.length,
    users: userStore.users.length,
    categories: userStore.categories.length
  })
})
</script>

<template>
  <div class="min-h-screen bg-gray-50">
    <RouterView />
  </div>
</template>