<template>
  <header class="sticky top-0 z-20 bg-white border-b border-gray-200 px-4 lg:px-6 py-3">
    <div class="flex items-center justify-between gap-4">
      <div class="flex items-center gap-4 flex-1">
        <button
          class="lg:hidden p-2 rounded-lg hover:bg-gray-100"
          @click="$emit('toggle-sidebar')"
        >
          <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>

        <div class="relative flex-1 max-w-md">
          <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
          <input
            type="text"
            placeholder="Search your tickets..."
            class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
          />
        </div>
      </div>

      <div class="flex items-center gap-3">
        <div class="relative" ref="notificationContainer">
          <button 
            @click="toggleNotifications"
            class="relative p-2 rounded-xl hover:bg-gray-100 transition-colors"
            :class="{ 'bg-gray-100': showNotifications }"
          >
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <span 
              v-if="unreadCount > 0" 
              class="absolute top-1 right-1 min-w-[18px] h-[18px] px-1 flex items-center justify-center bg-red-600 text-white text-[10px] font-bold rounded-full ring-2 ring-white animate-pulse"
            >
              {{ unreadCount > 9 ? '9+' : unreadCount }}
            </span>
          </button>

          <div 
            v-if="showNotifications"
            class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden z-50 animate-fade-in"
            @click.stop
          >
            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 bg-gray-50/50">
              <div>
                <h3 class="text-sm font-bold text-gray-800">Notifications</h3>
                <p class="text-xs text-gray-500 mt-0.5">You have {{ unreadCount }} unread</p>
              </div>
              <button 
                v-if="unreadCount > 0"
                @click="markAllAsRead"
                class="text-xs font-semibold text-red-600 hover:text-red-700 hover:underline transition-colors"
              >
                Mark all read
              </button>
            </div>

            <div class="max-h-96 overflow-y-auto custom-scrollbar">
              <div v-if="notifications.length === 0" class="p-8 text-center">
                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                  <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                  </svg>
                </div>
                <p class="text-sm font-medium text-gray-700">No notifications</p>
                <p class="text-xs text-gray-400 mt-1">You're all caught up!</p>
              </div>

              <div v-else>
                <div 
                  v-for="notif in notifications" 
                  :key="notif.id"
                  @click="handleNotificationClick(notif)"
                  class="px-4 py-3 hover:bg-gray-50 cursor-pointer transition-colors border-b border-gray-50 last:border-0"
                  :class="{ 'bg-red-50/30': !notif.read }"
                >
                  <div class="flex gap-3">
                    <div 
                      class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                      :class="getNotifStyle(notif.type).bg"
                    >
                      <svg 
                        class="w-4 h-4" 
                        :class="getNotifStyle(notif.type).color"
                        fill="none" 
                        stroke="currentColor" 
                        viewBox="0 0 24 24"
                      >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="getNotifStyle(notif.type).icon" />
                      </svg>
                    </div>

                    <div class="flex-1 min-w-0">
                      <p class="text-sm text-gray-800 leading-snug" v-html="notif.message"></p>
                      <p class="text-xs text-gray-400 mt-1">{{ notif.time }}</p>
                    </div>

                    <div v-if="!notif.read" class="w-2 h-2 bg-red-500 rounded-full flex-shrink-0 mt-2"></div>
                  </div>
                </div>
              </div>
            </div>

            <div class="px-4 py-3 border-t border-gray-100 bg-gray-50/50">
              <button 
                @click="viewAllNotifications"
                class="w-full text-center text-sm font-semibold text-red-600 hover:text-red-700 hover:underline transition-colors"
              >
                View all notifications →
              </button>
            </div>
          </div>
        </div>

        <div class="flex items-center gap-2 pl-3 border-l border-gray-200">
          <div class="w-9 h-9 bg-red-600 rounded-full flex items-center justify-center text-white text-sm font-semibold shadow-sm">
            {{ currentUserData.avatar }}
          </div>
          <div class="hidden sm:block">
            <p class="text-sm font-semibold text-gray-800">{{ currentUserData.name }}</p>
            <p class="text-xs text-gray-500 capitalize">{{ currentUserData.role }}</p>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'
import { useUserStore } from '../../stores/userStore'

const userStore = useUserStore()
const router = useRouter()
const emit = defineEmits(['toggle-sidebar'])

const showNotifications = ref(false)
const notificationContainer = ref(null) 

const notifications = ref([
  {
    id: 1,
    type: 'assign',
    message: 'Your ticket <b>TKT-002</b> has been assigned to an agent',
    time: '15 minutes ago',
    read: false,
    link: '/tickets/TKT-002'
  },
  {
    id: 2,
    type: 'comment',
    message: 'An agent added a comment on your ticket <b>TKT-002</b>',
    time: '1 hour ago',
    read: false,
    link: '/tickets/TKT-002'
  },
  {
    id: 3,
    type: 'status',
    message: 'Your ticket <b>TKT-003</b> status changed to Resolved',
    time: '3 hours ago',
    read: true,
    link: '/tickets/TKT-003'
  }
])

const currentUserData = computed(() => {
  if (userStore.currentUser) {
    return {
      name: userStore.currentUser.name,
      role: userStore.currentUser.role,
      avatar: userStore.currentUser.avatar || userStore.currentUser.name.charAt(0).toUpperCase()
    }
  }
  const name = localStorage.getItem('userName') || 'User'
  const role = localStorage.getItem('userRole') || 'user'
  return {
    name: name,
    role: role,
    avatar: name.charAt(0).toUpperCase()
  }
})

const unreadCount = computed(() => {
  return notifications.value.filter(n => !n.read).length
})

function toggleNotifications() {
  showNotifications.value = !showNotifications.value
}

function getNotifStyle(type) {
  const styles = {
    ticket: {
      bg: 'bg-red-100',
      color: 'text-red-600',
      icon: 'M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z'
    },
    assign: {
      bg: 'bg-blue-100',
      color: 'text-blue-600',
      icon: 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'
    },
    comment: {
      bg: 'bg-purple-100',
      color: 'text-purple-600',
      icon: 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'
    },
    status: {
      bg: 'bg-green-100',
      color: 'text-green-600',
      icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'
    }
  }
  return styles[type] || styles.ticket
}

function markAsRead(id) {
  const notif = notifications.value.find(n => n.id === id)
  if (notif) {
    notif.read = true
  }
}

function markAllAsRead() {
  notifications.value.forEach(n => {
    n.read = true
  })
}

function handleNotificationClick(notif) {
  markAsRead(notif.id)
  showNotifications.value = false
  
  if (notif.link) {
    router.push(notif.link)
  }
}

function viewAllNotifications() {
  showNotifications.value = false
  router.push('/activity')
}

function handleClickOutside(event) {
  if (notificationContainer.value && !notificationContainer.value.contains(event.target)) {
    showNotifications.value = false
  }
}

onMounted(() => {
  document.addEventListener('click', handleClickOutside)
})

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside)
})
</script>

<style scoped>
@keyframes fade-in {
  from { 
    opacity: 0; 
    transform: translateY(-10px); 
  }
  to { 
    opacity: 1; 
    transform: translateY(0); 
  }
}
.animate-fade-in {
  animation: fade-in 0.2s ease-out forwards;
}

.custom-scrollbar::-webkit-scrollbar {
  width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background-color: #e5e7eb;
  border-radius: 20px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background-color: #d1d5db;
}
</style>