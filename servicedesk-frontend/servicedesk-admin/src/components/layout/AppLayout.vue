<template>
  <div class="min-h-screen bg-[#f0f2f5]">
    <Sidebar :is-open="sidebarOpen" @close="sidebarOpen = false" />

    <div class="lg:pl-64">
      <Header @toggle-sidebar="sidebarOpen = !sidebarOpen" />

      <main class="p-4 lg:p-6">
        <slot />
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import Sidebar from './Sidebar.vue'
import Header from './Header.vue'
import { useAdminStore } from '../../stores/adminStore'

const sidebarOpen = ref(false)
const adminStore = useAdminStore()

onMounted(async () => {
  if (adminStore.tickets.length === 0) {
    console.log('Memuat data dari JSON Server...')
    await adminStore.fetchData()
  }
})
</script>