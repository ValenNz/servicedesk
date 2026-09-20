<template>
  <AppLayout>
    <Notification ref="notificationRef" />

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
      <div>
        <h1 class="text-2xl font-bold text-gray-800">My Activity Log</h1>
        <p class="text-gray-400 text-sm mt-1">
          Track your ticket updates, comments, and actions
        </p>
      </div>
      <div class="flex gap-2">
        <button 
          @click="exportLogs"
          class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-2xl font-semibold text-sm hover:bg-gray-50 transition-all"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
          </svg>
          Export
        </button>
      </div>
    </div>

    <div class="bg-white rounded-3xl p-4 mb-6 border border-gray-100 shadow-sm">
      <div class="grid grid-cols-1 xl:grid-cols-12 gap-3">
        <div class="relative xl:col-span-4">
          <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Search your activities..."
            class="w-full pl-9 pr-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-300"
            @input="handleSearch"
          />
        </div>
        
        <div class="xl:col-span-3">
          <select 
            v-model="filterStatus" 
            class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20"
          >
            <option value="">All Types</option>
            <option value="ticket_created">Ticket Created</option>
            <option value="ticket_assigned">Ticket Assigned</option>
            <option value="comment_added">Comment Added</option>
            <option value="status_changed">Status Changed</option>
            <option value="user_login">Login</option>
            <option value="user_logout">Logout</option>
          </select>
        </div>
        
        <div class="xl:col-span-3">
          <select 
            v-model="filterDate" 
            class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20"
          >
            <option value="all">All Time</option>
            <option value="today">Today</option>
            <option value="week">This Week</option>
            <option value="month">This Month</option>
          </select>
        </div>

        <div class="xl:col-span-2 flex items-center justify-end">
          <button 
            v-if="hasActiveFilters"
            @click="clearAllFilters"
            class="p-2.5 bg-red-50 text-red-600 border border-red-100 rounded-xl hover:bg-red-100 transition-colors"
            title="Clear Filters"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>
    </div>

    <div class="mb-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
      <p class="text-sm text-gray-500">
        Showing 
        <span class="font-semibold text-gray-800">{{ startItem }}</span> 
        to 
        <span class="font-semibold text-gray-800">{{ endItem }}</span> 
        of 
        <span class="font-semibold text-gray-800">{{ filteredData.length }}</span> 
        activities
      </p>
      
      <div class="flex items-center gap-2">
        <span class="text-sm text-gray-500">Show:</span>
        <select 
          v-model="itemsPerPage" 
          class="px-3 py-1.5 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20"
        >
          <option :value="10">10</option>
          <option :value="20">20</option>
          <option :value="50">50</option>
        </select>
        <span class="text-sm text-gray-500">per page</span>
      </div>
    </div>

    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
      <div class="divide-y divide-gray-100">
        <div 
          v-for="activity in paginatedData" 
          :key="activity.id" 
          class="p-6 hover:bg-gray-50 transition-colors"
        >
          <div class="flex items-start gap-4">
            <div 
              class="w-10 h-10 rounded-2xl flex items-center justify-center flex-shrink-0"
              :class="getActivityIcon(activity.type).bg"
            >
              <component 
                :is="getActivityIcon(activity.type).component" 
                class="w-5 h-5"
                :class="getActivityIcon(activity.type).color"
              />
            </div>
            
            <div class="flex-1 min-w-0">
              <div class="flex items-start justify-between gap-4 mb-1">
                <div>
                  <p class="text-sm font-semibold text-gray-800">
                    {{ activity.description }}
                  </p>
                  <p class="text-xs text-gray-500 mt-0.5 flex items-center gap-1">
                    <span>by {{ activity.user }}</span>
                    <span>•</span>
                    <span>{{ activity.timestamp }}</span>
                  </p>
                </div>
                <span 
                  class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium whitespace-nowrap"
                  :class="getActivityTypeBadge(activity.type)"
                >
                  {{ formatActivityType(activity.type) }}
                </span>
              </div>
              
              <div v-if="activity.details" class="mt-2 flex items-center gap-4 text-xs text-gray-500">
                <span v-if="activity.details.ticket" class="flex items-center gap-1 bg-gray-100 px-2 py-1 rounded-md">
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                  </svg>
                  {{ activity.details.ticket }}
                </span>
                <span v-if="activity.details.from && activity.details.to" class="flex items-center gap-1">
                  <span class="line-through opacity-60">{{ activity.details.from }}</span>
                  <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                  <span class="font-semibold text-gray-700">{{ activity.details.to }}</span>
                </span>
              </div>
            </div>
          </div>
        </div>
        
        <div v-if="filteredData.length === 0" class="p-12 text-center">
          <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <p class="text-gray-500 font-medium">No activities found</p>
          <p class="text-sm text-gray-400 mt-1">Try adjusting your filters</p>
        </div>
      </div>
    </div>

    <div v-if="totalPages > 1" class="flex items-center justify-end mt-8 pt-6 border-t border-gray-100">
      <div class="flex items-center gap-2">
        <button
          @click="prevPage"
          :disabled="currentPage === 1"
          class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
        >
          Previous
        </button>
        
        <button
          v-for="page in displayPages"
          :key="page"
          @click="currentPage = page"
          class="w-10 h-10 flex items-center justify-center text-sm font-medium rounded-xl transition-colors"
          :class="currentPage === page ? 'bg-red-600 text-white shadow-md shadow-red-600/25' : 'text-gray-600 hover:bg-gray-100'"
        >
          {{ page }}
        </button>
        
        <button
          @click="nextPage"
          :disabled="currentPage === totalPages"
          class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
        >
          Next
        </button>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import AppLayout from '../../components/layout/AppLayout.vue'
import Notification from '../../components/common/Notification.vue'
import { useUserStore } from '../../stores/userStore' 
import { useTableFilter } from '../../composables/useTableFilter'
import { useNotification } from '../../composables/useNotification'
import { 
  Ticket, 
  UserPlus, 
  LogOut, 
  MessageSquare, 
  UserCheck,
  Trash2,
  Edit,
  AlertCircle
} from 'lucide-vue-next'

const userStore = useUserStore()
const { notificationRef, success, error } = useNotification()

const userName = localStorage.getItem('userName') || 'User'
const filterDate = ref('all')

const visibleActivities = computed(() => {
  const activities = userStore.activities || []
  
  return activities.filter(activity => {
    if (activity.user === userName) return true

    if (activity.details?.ticket) {
      const relatedTicket = userStore.tickets.find(t => String(t.id) === String(activity.details.ticket))
      if (relatedTicket && (relatedTicket.createdBy === userName || relatedTicket.assignedTo === userName)) {
        return true
      }
    }
    
    return false
  })
})

function filterActivities(data, filters) {
  return data.filter(activity => {
    const matchesSearch = filters.searchQuery === '' || 
      activity.description.toLowerCase().includes(filters.searchQuery.toLowerCase()) ||
      activity.user.toLowerCase().includes(filters.searchQuery.toLowerCase())
    
    const matchesType = filters.status === '' || activity.type === filters.status
    
    let matchesDate = true
    if (filterDate.value !== 'all') {
      const activityDate = new Date(activity.timestamp)
      const now = new Date()
      
      if (filterDate.value === 'today') {
        matchesDate = activityDate.toDateString() === now.toDateString()
      } else if (filterDate.value === 'week') {
        const weekAgo = new Date(now.getTime() - 7 * 24 * 60 * 60 * 1000)
        matchesDate = activityDate >= weekAgo
      } else if (filterDate.value === 'month') {
        matchesDate = activityDate.getMonth() === now.getMonth() && activityDate.getFullYear() === now.getFullYear()
      }
    }
    
    return matchesSearch && matchesType && matchesDate
  })
}

const {
  searchQuery,
  filterStatus,
  currentPage,
  itemsPerPage,
  hasActiveFilters,
  filteredData,
  paginatedData,
  totalPages,
  startItem,
  endItem,
  displayPages,
  handleSearch,
  clearAllFilters: composableClearAllFilters,
  prevPage,
  nextPage
} = useTableFilter(visibleActivities, filterActivities)

function clearAllFilters() {
  composableClearAllFilters()
  filterDate.value = 'all'
}

watch([searchQuery, filterStatus, filterDate, itemsPerPage], () => {
  currentPage.value = 1
})

function getActivityIcon(type) {
  const icons = {
    ticket_created: { component: Ticket, bg: 'bg-blue-50', color: 'text-blue-600' },
    ticket_updated: { component: Edit, bg: 'bg-yellow-50', color: 'text-yellow-600' },
    ticket_deleted: { component: Trash2, bg: 'bg-red-50', color: 'text-red-600' },
    ticket_assigned: { component: UserCheck, bg: 'bg-green-50', color: 'text-green-600' },
    comment_added: { component: MessageSquare, bg: 'bg-purple-50', color: 'text-purple-600' },
    status_changed: { component: AlertCircle, bg: 'bg-orange-50', color: 'text-orange-600' },
    user_login: { component: UserPlus, bg: 'bg-teal-50', color: 'text-teal-600' },
    user_logout: { component: LogOut, bg: 'bg-gray-50', color: 'text-gray-600' }
  }
  return icons[type] || { component: Ticket, bg: 'bg-gray-50', color: 'text-gray-600' }
}

function formatActivityType(type) {
  return type.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ')
}

function getActivityTypeBadge(type) {
  const badges = {
    ticket_created: 'bg-blue-50 text-blue-700',
    ticket_updated: 'bg-yellow-50 text-yellow-700',
    ticket_deleted: 'bg-red-50 text-red-700',
    ticket_assigned: 'bg-green-50 text-green-700',
    comment_added: 'bg-purple-50 text-purple-700',
    status_changed: 'bg-orange-50 text-orange-700',
    user_login: 'bg-teal-50 text-teal-700',
    user_logout: 'bg-gray-50 text-gray-700'
  }
  return badges[type] || 'bg-gray-50 text-gray-700'
}

function exportLogs() {
  if (filteredData.value.length === 0) {
    error('No activities found to export.', 'Export Failed')
    return
  }

  const headers = ['ID', 'Type', 'Description', 'User', 'Timestamp', 'Ticket', 'Details']
  
  const escapeCsv = (value) => {
    if (value === null || value === undefined) return ''
    const str = String(value)
    if (str.includes(',') || str.includes('"') || str.includes('\n')) {
      return `"${str.replace(/"/g, '""')}"`
    }
    return str
  }

  const rows = filteredData.value.map(activity => {
    const details = []
    if (activity.details?.ticket) details.push(`Ticket: ${activity.details.ticket}`)
    if (activity.details?.from) details.push(`From: ${activity.details.from}`)
    if (activity.details?.to) details.push(`To: ${activity.details.to}`)
    
    return [
      activity.id,
      escapeCsv(formatActivityType(activity.type)),
      escapeCsv(activity.description),
      escapeCsv(activity.user),
      escapeCsv(activity.timestamp),
      escapeCsv(activity.details?.ticket || ''),
      escapeCsv(details.join(' | '))
    ].join(',')
  })

  const BOM = '\uFEFF'
  const csvContent = BOM + [headers.join(','), ...rows].join('\n')
  const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' })
  const url = URL.createObjectURL(blob)
  const link = document.createElement('a')
  
  const date = new Date().toISOString().split('T')[0]
  link.setAttribute('href', url)
  link.setAttribute('download', `my-activity-log-${date}.csv`)
  link.style.visibility = 'hidden'
  
  document.body.appendChild(link)
  link.click()
  document.body.removeChild(link)
  URL.revokeObjectURL(url)
  
  success(`Successfully exported ${filteredData.value.length} activities.`, 'Export Successful')
}
</script>