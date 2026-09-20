<template>
  <aside
    class="fixed left-0 top-0 z-40 h-screen w-64 bg-white flex flex-col transition-transform duration-300 lg:translate-x-0"
    :class="isOpen ? 'translate-x-0' : '-translate-x-full'"
    style="border-radius: 0 24px 24px 0;"
  >
    <div class="flex items-center gap-3 px-6 py-6">
      <div class="w-10 h-10 bg-red-600 rounded-2xl flex items-center justify-center shadow-lg shadow-red-600/30">
        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
        </svg>
      </div>
      <span class="text-xl font-bold text-gray-800">ServiceDesk</span>
    </div>

    <nav class="flex-1 px-4 py-2 space-y-1.5 overflow-y-auto">
      <router-link
        v-for="item in navItems"
        :key="item.path"
        :to="item.path"
        class="flex items-center gap-3 px-4 py-3 text-sm font-medium transition-all duration-200"
        :class="isActive(item.path) ? 'bg-red-50 text-red-700 rounded-2xl' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700 rounded-2xl'"
      >
        <component :is="item.icon" class="w-5 h-5" />
        {{ item.label }}
      </router-link>
    </nav>

    <div class="px-4 py-4 border-t border-gray-100">
      <div class="flex items-center gap-3 px-4 py-3 bg-gray-50 rounded-2xl mb-3">
        <div class="w-10 h-10 bg-red-600 rounded-xl flex items-center justify-center text-white text-sm font-bold shadow-md shadow-red-600/20">
          {{ userAvatar }}
        </div>
        <div class="flex-1 min-w-0">
          <p class="text-sm font-semibold text-gray-800 truncate">{{ userName }}</p>
          <p class="text-xs text-gray-400 capitalize">{{ userRole }}</p>
        </div>
      </div>
      
      <button 
        @click="handleLogout"
        class="w-full flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold text-red-600 bg-red-50 rounded-xl hover:bg-red-100 transition-colors"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
        </svg>
        Logout
      </button>
    </div>
  </aside>

  <div
    v-if="isOpen"
    class="fixed inset-0 z-30 bg-black/20 backdrop-blur-sm lg:hidden"
    @click="$emit('close')"
  ></div>
</template>

<script setup>
import { computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { 
  LayoutDashboard, 
  Ticket, 
  PlusCircle,
  Activity, 
  UserCircle 
} from 'lucide-vue-next'

const route = useRoute()
const router = useRouter()

defineProps({
  isOpen: { type: Boolean, default: false },
})

defineEmits(['close'])

const userRole = localStorage.getItem('userRole') || 'user'
const userName = localStorage.getItem('userName') || 'User'
const userAvatar = userName.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase()

const navItems = [
  { path: '/dashboard', label: 'Dashboard', icon: LayoutDashboard },
  { path: '/tickets', label: 'Daftar Tiket', icon: Ticket },
  { path: '/tickets/create', label: 'Buat Tiket', icon: PlusCircle },
  { path: '/activity', label: 'Aktivitas', icon: Activity },
  { path: '/profile', label: 'Profil', icon: UserCircle },
]

function isActive(path) {
  if (path === '/tickets' && route.path.startsWith('/tickets/')) {
    return true
  }
  return route.path === path
}

function handleLogout() {
  localStorage.removeItem('userToken')
  localStorage.removeItem('userRole')
  localStorage.removeItem('userName')
  localStorage.removeItem('userEmail')
  localStorage.removeItem('userId')
  
  window.location.replace('/login')
}
</script>